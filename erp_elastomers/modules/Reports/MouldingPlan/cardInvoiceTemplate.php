<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Moulding Plan</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<!-- <br /> -->
<?php
	global $default_rejections;
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$operator		=	(ISO_IS_REWRITE)?trim($_VAR['operator']):trim($_GET['operator']);
	$planDet		=	explode("~",$invoice_id);
	$plan_id		= 	$planDet[0];
	$viewType		=	$planDet[1];
	if($viewType == null || $viewType == '')
	{
		$planIdArr	=  array('data' => array( array('planid' =>  $plan_id) ));
	}
	else if($viewType == 'all')
	{
		$planIdArr	= @getMySQLData("select planid from tbl_moulding_plan WHERE planDate ='$plan_id' and operator = '$operator' order by planid");
	}
	
	// Get Form Details.
	$formArray		=	@getFormDetails(22);	  	
	foreach($planIdArr['data'] as $key=>$planDet){
		$plankey		=	$planDet['planid'];
		//added for printing sample plan
		if(substr($plankey, 0, 1) == "S")
			$sql			=	"select *,'NA' as toolRack,(select part_number from tbl_develop_request where sno = tbl_sample_plan.rfqId) as cmpdName,(select cpdName from tbl_compound where cpdId = tbl_sample_plan.cpdId) as cmpdCpdName, (select toolcavities from tbl_trn where trnId = tbl_sample_plan.toolRef) as no_of_active_cavities,planid,1 as numShifts,plandate as invdate, '140 - 200' as cmpdPressure, concat(cmpdCurTemp-10,' - ',cmpdCurTemp+10)	as cmpdCurTemp from tbl_sample_plan where planid='$plankey'";
		else 
			$sql			=	"select * 
									from tbl_invoice_mould_plan imp
										inner join tbl_moulding_plan tmp on tmp.planid=imp.planid
										left join (select rack as toolRack, tool_ref from tbl_tool)tt on tt.tool_ref = imp.toolRef
										left JOIN (select cmpdId,cmpdProdGroup, cmpdRemarks from tbl_component) tc on tc.cmpdId = imp.cmpdId
										left join tbl_product_group tpg on tpg.prodType = tc.cmpdProdGroup
									where tmp.planid='$plankey'";
		//echo $sql ; //exit();
		$output			=	@getMySQLData($sql);
		extract($output['data'][0]);
		$planidn 		= 	($planid)?((strpos($planid,'_')!== false)?strstr($planid,"_",true):$planid):'&nbsp;';
		$rej_sql		=	"select rej_short_name as cmpdRejSName from tbl_component_rejection tcr
									inner join tbl_rejection tr on tr.sno = tcr.cmpdRejNo
								where cmpdId ='".$cmpdId."'";
		$rejParticulars	=	@getMySQLData($rej_sql);
		if(!($rejParticulars['count'] > 0))
		{
			$rej_sql		=	"select rej_short_name as cmpdRejSName from tbl_rejection where rej_short_name in ".$default_rejections ." order by sno ";
			$rejParticulars	=	@getMySQLData($rej_sql);		
		}
?>		
		<p align="right">Form No:
			<?php  print $formArray[0];	?>
		</p>         
		<table cellpadding="4" cellspacing="0" border="0" style="width:100%;" id="print_out" >
            <tr class="casual_normal">
				<td style="width:25%;" align="center">
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="40px" />
				</td>
				<td colspan="2">
					<div style="text-align:center;font-size:16px;font-weight:bold;"><?php  print $formArray[1];	?> <?php echo '<img src="'.$qr_genrate_url.'?id='.$planid.'" width="40px" />'; ?></div>
				</td>				
				<td style="width:20%;">
					<sup>Key</sup> <div style="margin-left:15px;font-size:16px;font-weight:bold;"><?php print $planidn;?></div>
					<div style="margin-left:15px;font-weight:bold;"> <?php echo date("d-m-Y", strtotime($invdate)); ?></div>
				</td>				
            </tr>
            <tr class="casual_normal">
				<td>
					<sup>Part No.</sup> <div style="margin-left:15px;font-weight:bold;"> <?php print $cmpdName.(($typeAbbr)?"(".$typeAbbr.getBlanksGroup($cmpdBlankWgt).")" :""); ?> </div>
				</td>				
				<td style="width:30%;">
					<sup>Tool Ref. (Rack / Cavs.)</sup> <div style="margin-left:15px;font-weight:bold;"> <?php  print $toolRef ."(".(($toolRack)?$toolRack:'NA')." / ".$no_of_active_cavities.")"  ?> </div>	
				</td>
				<td style="width:25%;">
					<sup>Location</sup><div style="margin-left:15px;font-weight:bold;"> <?php echo substr($operator,0,15);  ?> </div>				   
				</td>
				<td>
				   <sup>Lift</sup><div style="margin-left:15px;font-weight:bold;"> <?php echo number_format($liftPlanned); ?> </div>
				</td>			
            </tr>
            <tr class="casual_normal">
				<td>
					<sup>Comp.</sup> <div style="margin-left:15px;font-weight:bold;"> <?php print $cmpdCpdName; ?> </div>
				</td>
				<td colspan="2">
					<sup>Batch Ref.</sup> <div style="margin-left:15px;font-weight:bold;"> <?php print ($cmpdCpdBatId)?$cmpdCpdBatId:$mdIssRef; ?> </div>
				</td>				
				<td>
					<sup>Qty.</sup> <div style="margin-left:15px;font-weight:bold;"> <?php print number_format(($cmpdBlankWgt*$no_of_active_cavities*$liftPlanned)/1000,3); ?> Kgs</div>				   
				</td>
			</tr>
            <tr class="casual_normal">
				<td>
					<sup>BW</sup><div style="margin-left:15px;font-weight:bold;"> <?php print number_format($cmpdBlankWgt,2); ?> gm</div>
				</td>			
				<td colspan="2">
					<sup>Strip Dim. (lxbxth Ref. Only*)</sup> <div style="margin-left:40px;font-weight:bold;"> <?php print (($strip_dim2) && ($strip_dim2 > 0))?number_format($strip_dim2,2)." x":'- x '; ?>
					<?php print (($strip_dim3) && ($strip_dim3 > 0))?number_format($strip_dim3,2)." x":'- x '; ?>
					<?php print (($strip_dim1) && ($strip_dim1 > 0))?number_format($strip_dim1,2)." ":'-'; ?> mm</div>
				</td>
				<td>
					<sup>Strips/Lift</sup> <div style="margin-left:15px;font-weight:bold;"> <?php  print (($strips_per_lift) && ($strips_per_lift > 0))?number_format($strips_per_lift,0):'&nbsp'; ?> </div>
				</td>
            </tr>
			 <tr class="casual_small">
				<td colspan="2" rowspan="4" valign="top">
					<sup>Remarks</sup> <div style="margin-left:15px;font-size:14px;font-weight:bold;"><?php  print $cmpdRemarks ?></div>
				</td>	
				<td>
					<sup>NPD</sup>
				</td>
				<td>
					<div style="margin-left:15px;font-weight:bold;"> White</div>
				</td>
			 </tr>			
			<tr class="casual_small">
				<td>
					<sup>Regular</sup>
				</td>			
				<td>
					<div style="margin-left:15px;font-weight:bold;">Blue</div>
				</td>
			</tr>
			<tr class="casual_small">
				<td>
					<sup>IPC</sup>
				</td>			
				<td>
					<div style="margin-left:15px;font-weight:bold;">Yellow</div>
				</td>			 
			 </tr>
			<tr class="casual_small">
				<td>
					<sup>5M Change</sup>
				</td>			
				<td>
					<div style="margin-left:15px;font-weight:bold;">Pink</div>
				</td>			 
			 </tr>			 
 		</table>
		<?php		
			/*echo '<pre>';
				print_r($grn_items);
			echo '</pre>';*/
			$repeat_no	=	2;
			$shiftSuffix = array('y','x');			
			if($numShifts == 1)
			{
				$repeat_no = 1;
				$shiftSuffix = array('x');
			}			
			for($r=0; $r<$repeat_no; $r++){	
		?>
		<br /><br /> 
		<table cellpadding="4" cellspacing="0" border="0" style="width:100%" id="print_out" class="watermark">
            <tr class="casual_small">
				<td rowspan="3" style="width:10%;">
					<?php echo '<img src="'.$qr_genrate_url.'?id='.$planid.'-'.$shiftSuffix[$r].'" width="50px" />';?>
				</td>
				<td style="width:25%;">
					Key:<b><?php echo $planidn."-".$shiftSuffix[$r]; ?></b>
				</td>
				<td style="width:25%">
					Location:<b> <?php echo substr($operator,0,15);  ?> </b>
				</td>
				<td style="width:20%;">
					Start Count:
				</td>
				<td style="width:20%;">
					End Count:
				</td>
			</tr>
			<tr class="casual_small">
				<td>
					Part No. :<b><?php echo $cmpdName; ?></b>
				</td>			
				<td>
					Cavities:<b> <?php print $no_of_active_cavities; ?></b>
				</td>				
				<td>
					Plnd. Lifts:<b><?php print ($numShifts > 1)? (($r % 2 == 1)? number_format(ceil($liftPlanned/2)):number_format(floor($liftPlanned/2))):number_format($liftPlanned);?></b>
				</td>
				<td>
					Act. Lifts:<b>&nbsp;</b>
				</td>				
			</tr>
            <tr class="casual_small">
				<td>
					Comp.:<b> <?php echo $cmpdCpdName;  ?> </b>
				</td>
				<td colspan="4">
					Batch Ref. :<b><?php print ($cmpdCpdBatId)?$cmpdCpdBatId:$mdIssRef; ?></b>
				</td>
			</tr>
            <tr>
					<table cellpadding="4" cellspacing="0" border="0" style="width:100%" id="inner_print_out">
						<tr class="casual_small" style="font-weight:bold">			
							<td style="width:16%;text-align:center;" >Date</td>
							<td style="width:25%;text-align:center" >Process</td>
							<td style="width:14%;text-align:center" >Qty</td>
							<td style="width:12%;text-align:center" >Accepted</td>
							<td style="width:12%;text-align:center" >NG</td>
							<td style="text-align:center" >Signature</td>
						</tr>
						<tr class="casual_normal">			
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center;font-size:16px;font-weight:bold;">Blanking</td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
						</tr>					
						<tr class="casual_normal">			
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center;font-size:16px;font-weight:bold;">Moulding</td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
						</tr>
						<tr class="casual_normal">			
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center;font-size:16px;font-weight:bold;">Deflashing</td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
						</tr>
						<tr class="casual_normal">			
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center;font-size:16px;font-weight:bold;"> Visual</td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
						</tr>
						<tr class="casual_normal">			
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center;font-size:16px;font-weight:bold;"> Counting</td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
							<td style="text-align:center">&nbsp; </td>
						</tr>							
					</table>
            </tr>
        </table>
		<?php } ?>
		<div class="page_break">
            <br /><br /><br />
            <table cellpadding="4" cellspacing="0" border="0" style="width:100%;" id="print_out">
                <tr class="casual_small">
                     <td rowspan= '2' class="content_bold content_center" style="width:20%">
                        Parameters
                     </td>
                     <td rowspan= '2' class="content_bold content_center" style="width:10%">
                        Units
                     </td>					 
                     <td rowspan= '2' class="content_bold content_center" style="width:14%" >
                        Spec.
                     </td>
                    <td style="width:8%" class="content_bold content_center">Opr.</td>
                    <td style="width:8%" class="content_bold content_center">Pat.</td>
                    <td style="width:8%" class="content_bold content_center">Pat.</td>
                    <td style="width:8%" class="content_bold content_center">Opr.</td>
                    <td style="width:8%" class="content_bold content_center">Pat.</td>
                    <td style="width:8%" class="content_bold content_center">Pat.</td>
				 
                </tr>
                <tr class="casual_small">
                    <td style="width:8%" class="content_bold content_center">0</td>
                    <td style="width:8%" class="content_bold content_center">4</td>
                    <td style="width:8%" class="content_bold content_center">8</td>
                    <td style="width:8%" class="content_bold content_center">12</td>
                    <td style="width:8%" class="content_bold content_center">16</td>
                    <td style="width:8%" class="content_bold content_center">20</td>
                </tr>				
                <tr class="casual_small">
                    <td class="content_bold">
                        Nip Setting
                    </td>
                    <td> &nbsp;
                    </td>
                    <td class="content_bold content_center"><?php print (($strip_dim1) && ($strip_dim1 > 0))?number_format(round($strip_dim1 - ($strip_dim1 * 0.5),1),1)." - ". number_format($strip_dim1,1):'&nbsp;'; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>
                <tr class="casual_small">
                    <td class="content_bold">
                        Sheet Thickness
                    </td>
                    <td class="content_bold content_center">mm
                    </td>
                    <td class="content_bold content_center"><?php print (($strip_dim1) && ($strip_dim1 > 0))?number_format($strip_dim1 - 1,1)." - ".number_format($strip_dim1 + 2,1):'&nbsp;'; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>
                <tr class="casual_small">
                    <td class="content_bold">
                        Strip Breath
                    </td>
                    <td class="content_bold content_center">mm
                    </td>					
                    <td class="content_bold content_center" >
                        <?php print (($strip_dim3) && ($strip_dim3 > 0))?number_format($strip_dim3 - 1,1)." - ".number_format($strip_dim3 + 2,1):'&nbsp'; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>
                <tr class="casual_small">
                    <td class="content_bold">
                        Strip Weight
                    </td>
                    <td class="content_bold content_center">gm
                    </td>					
                    <td class="content_bold content_center" >
						&nbsp;
                        <?php 
							switch($strip_weight){
								case $strip_weight > 0 && $strip_weight <= 2 :
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.30),1); 
								break;
								case $strip_weight > 2 && $strip_weight <= 5 :
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.25),1);
								break;
								case $strip_weight > 5 && $strip_weight <= 10 :
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.15),1); 
								break;
								case $strip_weight > 10 :
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.10),1);
								break;								
							}							
						?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>				
                <tr class="casual_small">
                    <td class="content_bold">
                        Temperature
                    </td>
                    <td class="content_bold content_center"><sup>o</sup>C
                    </td>
                    <td class="content_bold content_center"><?php print (($cmpdCurTemp) && ($cmpdCurTemp > 0))?" ".number_format($cmpdCurTemp - 10,0)." - ".number_format($cmpdCurTemp + 10,0):'&nbsp'; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                </tr>
                <tr class="casual_small">
                    <td class="content_bold">
                        Curing Time
                    </td>
                    <td class="content_bold content_center">Seconds
                    </td>					
                    <td class="content_bold content_center">
                        <?php print $cmpdCurTime; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                </tr>
                <tr class="casual_small">
                    <td class="content_bold">
                        Pressure
                    </td>
                    <td class="content_bold content_center">Kg/cm<sup>2</sup>
                    </td>					
                    <td class="content_bold content_center" >
                        <?php print $cmpdPressure; ?>
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>				
                <tr class="casual_small">
                    <td class="content_bold">
                        Lift Progress
                    </td>
                    <td colspan="2" class="content_bold content_center">
                        Every 4 Hours
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                    <td>&nbsp;
                    </td>
                 </tr>
            </table>
		<?php
		
			/*echo '<pre>';
				print_r($grn_items);
			echo '</pre>';*/
			$repeat_no	=	2;
			if($numShifts == 1)
				$repeat_no = 1;
			for($r=0; $r<$repeat_no; $r++){
			
		?>
			<br /><br />
			<table cellpadding="4" cellspacing="0" border="0" style="width:100%;" id="print_out">
                <tr class="casual_normal">
					<?php					
						$rep_no	=	8;
						for($rejCount=0; $rejCount<$rep_no; $rejCount++){						
					?>
					<td style="width:5%" class="content_bold content_center" >
						<?php print	$rejParticulars['data'][$rejCount]['cmpdRejSName'];	?> &nbsp;
					</td>
					<td style="width:7.5%" class="content_bold content_center" >
						&nbsp;
					</td>					
					<?php } ?>					
                </tr>	
                <tr>
					<table cellpadding="4" cellspacing="0" border="0" style="width:100%" id="inner_print_out">
						<tr class="casual_small">				
							<td style="width:20%" class="content_bold content_center" >
								Parameter
							</td>
							<td style="width:15%" class="content_bold content_center" >
								Method
							</td>					 
							<td style="width:15%" class="content_bold content_center" >
								Spec (mm)
							</td>
							<td style="width:8%" class="content_bold content_center" >
								1
							</td>
							<td style="width:8%" class="content_bold content_center">
								2
							</td>
							<td style="width:8%" class="content_bold content_center">
								3
							</td>
							<td style="width:8%" class="content_bold content_center">
								4
							</td>
							<td style="width:8%" class="content_bold content_center">
								5
							</td>
							<td class="content_bold content_center">
								sign
							</td>
						</tr>
						<tr class="casual_normal">
							<td class="content_bold" style="white-space:nowrap;" >Flash Thickness
							</td>
							<td class="content_bold content_center">D.T.G
							</td>
							<td class="content_bold content_center" style="font-size:8px"> &lt; 0.20
							</td>
							<td class="content_bold" colspan="2"><sup>Opr. :</sup><div>&nbsp;</div>
							</td>
							<td class="content_bold" colspan="2"><sup>Pat. :</sup><div>&nbsp;</div>
							</td>
							<td class="content_bold" colspan="2"><sup>Pat. :</sup><div>&nbsp;</div>
							</td>							
						</tr>
						<?php 
							$dimSql			=	"select cmpddim, paramName, uom_short_name,paramTestMethod,cmpdDimSpec,cmpdDimLLimit,cmpdDimULimit from tbl_component_dim_param t1 
														left outer join tbl_param t3 on t3.sno = t1.cmpdDim
														left outer join tbl_uom t4 on t4.sno = t3.paramUOM 
														where t1.cmpdId='$cmpdId' and t3.status > 0 order by cmpddim";
							$dimParticulars	=	@getMySQLData($dimSql);
							$dimCount		=	5;
							for($dp=0;$dp<$dimCount;$dp++)
							{
						?>
								<tr class="casual_normal">
									<td class="content_bold"><?php print	$dimParticulars['data'][$dp]['paramName'];?> &nbsp;
									</td>
									<td class="content_bold content_center"><?php print	$dimParticulars['data'][$dp]['paramTestMethod'];?>&nbsp;
									</td>
									<td class="content_bold content_center" style="font-size:8px"><?php print	($dimParticulars['data'][$dp]['cmpdDimSpec'])?number_format($dimParticulars['data'][$dp]['cmpdDimLLimit'],2)."/".number_format($dimParticulars['data'][$dp]['cmpdDimULimit'],2) :'&nbsp;';?>
									</td>
									<td>&nbsp;
									</td>
									<td>&nbsp;
									</td>
									<td>&nbsp;
									</td>
									<td>&nbsp;
									</td>
									<td>&nbsp;
									</td>
									<td>&nbsp;
									</td>		
								</tr>
						<?php } ?>
					</table>
                </tr>				
			</table>
        <?php
 			}
	 ?>
        </div>
	<?php
		if($key < count($planIdArr['data']) -1 )
			echo "<div class='page_break'><br /></div>";
		}
	?>			
    </body>
</html>