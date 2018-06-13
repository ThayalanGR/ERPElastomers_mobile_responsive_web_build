<?php
	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	$dcItemType				=	(ISO_IS_REWRITE)?trim($_VAR['mod']):trim($_GET['mod']);	
	$getCusEmail			=	(ISO_IS_REWRITE)?trim($_VAR['cusEmail']):trim($_GET['cusEmail']);
	global	$cpdMonthCode,$gstChangeCutoff,$grn_role,$HSN;
	
	if($getCusEmail)
	{
		$output = "";
		if($dcItemType == 'testcert')
		{
 			$sql 			= 	"select batId, sum(hardVal) as hardVal, sum(spGravityVal) as spGravityVal 
									from (select  dcItemId as batId, if(cpdQanParam ='".$cpd_std_test_refnos[0]."',cpdQanValue,0) as hardVal, if(cpdQanParam ='".$cpd_std_test_refnos[1]."',cpdQanValue,0) as spGravityVal
												from tbl_invoice_dc_items t1 
												left outer join (select * from tbl_compound_qan where (cpdQanParam = '".$cpd_std_test_refnos[0]."' or cpdQanParam = '".$cpd_std_test_refnos[1]."')) t2 on t1.dcItemId = t2.batId 
											where dcId='".$invoice_id."') tab1 
								group by batId order by batId";
			$outSql 		=	@getMySQLData($sql);
	        $testDetails    =   $outSql['data'];
			// Get Records from the table
			foreach($testDetails as $keys=>$values) {
				foreach($values as $key=>$value) {
					if($key != "batId" && $value == 0)
					{
						echo 'Sending email failed as Hardness/Specific Gravity not completed for batch Id :'.$values['batId'].' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
						exit();
					}
					$output 	.=	'"'.$value.'",';
				}
				$output 	.=	"\n";
			}			
			$thefilecsv = $_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id."-tc.csv";
			file_put_contents($thefilecsv, $output);			
			$filesArr		=	glob($_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id."-tc.csv");
			$pstatus 		= 	createPDFforReport("Sales/DCRegister",$invoice_id);
			$tempPath		=	sys_get_temp_dir();
			if($pstatus == "")			
			{
				rename($tempPath.'/'.$invoice_id.'.pdf', $tempPath.'/CompoundApprovalReport'.$invoice_id.'.pdf');
				$thefilepdf = $tempPath.'/CompoundApprovalReport'.$invoice_id.'.pdf';	
				array_push($filesArr,$thefilepdf);	
			}
			
			$output = sendEmail(explode(",",$getCusEmail),"","Compound Test Certificate: ".$invoice_id,"Dear Sir/Madam, please find the Compound Test Certificate for upload/filing attached to this email<br\> Thank you <br\>".$_SESSION['app']['comp_name'],$filesArr);
			if($thefilecsv != null && $thefilecsv != "")
			{
				unlink($thefilecsv);
			}
			if($thefilepdf != null && $thefilepdf != "")
			{
				unlink($thefilepdf);
			}					
		}
		else 
		{
			$loc_sql		=	@getMySQLData("select value from tbl_settings where name='mixLocCode'");
			$locCode		=	$loc_sql['data'][0]['value'];			
			$sql			=	"select concat(dcId,'".$locCode."') as dcId,(select dcDate from tbl_invoice_dc where dcId=tdci.dcId)as dcdate,  dcName,dcItemId ,(select batFinalDate from tbl_mixing where batId=tdci.dcItemId )as batdate,dcQty from tbl_invoice_dc_items tdci where tdci.dcId='".$invoice_id."'";
			$dcType			=	"Compound";
			if ($dcItemType == 'cmpd')
			{
				$sql		=	"select concat(tdci.dcId,'".$locCode."') as dcId, tid.dcDate, dcName,dcItemId,FORMAT(dcQty,0) as dcQty  from tbl_invoice_dc_items tdci inner join tbl_invoice_dc tid on tid.dcId=tdci.dcId where tdci.dcId='".$invoice_id."'";
				$dcType		=	"Component";
			}
			$outSql 		=	@getMySQLData($sql);
	        $cpdDetails    	=   $outSql['data'];
			// Get Records from the table
			foreach($cpdDetails as $keys=>$values) {
				foreach($values as $key=>$value) {		
					if($key == 'dcItemId' && $dcItemType == 'cpd')
					{
						$mixDate			= 	$values['batdate'];
						$partBatId			=	"";
						if($mixDate != null && $mixDate != '0000-00-00' && $mixDate != '')
						{
							list($y, $m, $d) 	= 	explode('-', $mixDate);
							$partBatId			=	"/".$d . $cpdMonthCode[$m+0];	
						}
						$batId				=	$value;
						$output 			.=	'"'.$batId.$partBatId.'",';
					}
					else if($key != 'batdate')
					{
						$output .='"'.$value.'",';
					}			
				}
				$output .="\n";
			}
			
			$thefile 	= 	$_SERVER['DOCUMENT_ROOT']."/export/".$invoice_id.".csv";
			file_put_contents($thefile, $output);			
			$output = sendEmail(explode(",",$getCusEmail),"",$dcType." DC: ".$invoice_id,"please find the ".$dcType." DC for upload",$thefile);
		}
		if($output == 'success')
			echo '<script>javascript:window.open("","_self").close();</script>';
		else
			echo 'Sending email failed due to: ' .$output . ' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
		exit;	
	}
	
	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php print $invoice_id; ?></title>
			<link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		</head>
		<body>	
	
	<?php
	$formArray		=	@getFormDetails(39);
	$dcUOM			=	"Kgs";
	$isCmpd			=	false;
	if ($dcItemType == 'cmpd')
	{
		$dcUOM		=	"Nos";
		$isCmpd		=	true;
	}	
	$dcIDs				=	"";
	if (strpos($invoice_id,',') !== false) {
		$dcIDs			=	explode(",", $invoice_id);
	}
	else
	{
		$dcIDs			=	array($invoice_id);	
	}
	for($dcCount=0;$dcCount<count($dcIDs);$dcCount++)
	{
		$invoice_id			=	$dcIDs[$dcCount];	
		$sql_bill			=	"select * from tbl_invoice_dc where dcId='".$invoice_id."'";
		$sql_particulars	=	"select *, CONCAT( SUBSTRING_INDEX( dcItemId,  '_', 1 ) ,'-', SUBSTRING_INDEX( dcItemId,  '-' , -1 ) ) AS planId,(select DATE_FORMAT(batFinalDate,'%d-%b-%Y')as batDate from tbl_mixing where batId=tidi.dcItemId)as batdate from tbl_invoice_dc_items tidi where tidi.dcId='".$invoice_id."'";
		$out_bill			=	@getMySQLData($sql_bill);
		$out_particulars	=	@getMySQLData($sql_particulars);
		$data				=	$out_bill['data'][0];
		$particulars		=	$out_particulars['data'];
		$noofBatches		=	count($particulars);
		$addr				=	@preg_split("/[|]/", $data['dcConsignee'], -1, PREG_SPLIT_NO_EMPTY);
		$addr[1]			=	trim(@preg_replace("/<br \/>/", ", ", @preg_replace("/[,]/", ", ", $addr[1])));
		$buyer				=	$addr[0]."<br>".$addr[1];
		$custData			=	@getMySQLData("select cusGroup from tbl_customer where cusId='".$data['dcCusId']."'");
		$cusGroup			=	$custData['data'][0]['cusGroup'];
?>
		<p align="right">Form No:<?php print $formArray[0]; ?></p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td colspan="2" align="center" style="padding:3px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="1" width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
					<?php echo '<img src="'.$qr_genrate_url.'?id=dc~'.$invoice_id.'" />'; ?>
                </td>
            </tr>
            <tr>
            	<td colspan="7" class="content_center content_bold">
                	<?php print ($grn_role[$cusGroup] != 'self')?$formArray[1]:"Internal Material Transfer"; ?>
                </td>
            </tr>
			<?php 			
			if($data['dcDate'] > $gstChangeCutoff)
			{?>
            <tr>				
            	<td colspan="4" rowspan="5" valign="top" style="padding:0px;">
					<div style="padding-bottom:5px;">Name &amp; Address of Consignee:<br /></div>
					<div class="content_bold" style="font-size:14px;">
						<?php print strtoupper($buyer); ?>
					</div>
					<div  class="content_bold">
						<?php print ($data['dcBGSTN'])?'GSTIN :	 <span style="font-size:14px;">'.$data['dcBGSTN'].'</span><br/>':'';?>
						<?php print ($data['dcBPAN'])?'PAN No. : '. $data['dcBPAN']:'';?>						
					</div>												
				</td>
				<td style="border-right:0px;">
					<?php print ($grn_role[$cusGroup] != 'self')?"Delivery Challan No.":"Transfer No."; ?>
				</td>
				<td  colspan="2" class="content_bold"  style="font-size:14px;">
					: <?php print $data['dcId']; ?>
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;">
					<?php print ($grn_role[$cusGroup] != 'self')?"DC":"Transfer"; ?> Date
				</td>
				<td colspan="2" class="content_bold"  style="font-size:14px;" >
					: <?php print date("d-m-Y", strtotime($data['dcDate'])); ?>
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;">
					Shipping Date
				</td>
				<td colspan="2" class="content_bold">
					 : <?php print ($grn_role[$cusGroup] != 'self')? date("d-m-Y", strtotime($data['dcShipDate'])):"NA";  ?>&nbsp;
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;">
					GST No
				</td>
				<td colspan="2" class="content_bold" style="font-size:14px;">
					: <?php print $data['dcGSTN']; ?>
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;">
					PAN No
				</td>
				<td colspan="2" class="content_bold">
					: <?php print $data['dcPAN']; ?>
				</td>
			</tr>
			<?php if ($grn_role[$cusGroup] != 'self') { ?>
			<tr>
				<td colspan="2" style="border-right:0px;">
					Place of Supply
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print $data['dcSupplyPlace']; ?>&nbsp;
				</td>
				<td style="border-right:0px;">
					Estimated Taxable Value (Rs) 
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print @number_format($data['assessValue'],2); ?>&nbsp;
				</td>
			</tr>			
            <?php
				}
			}
			else
			{
			?>
            <tr>
            	<td colspan="7" valign="top" style="padding:0px;border-bottom:0px;">
					<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border:0px;">
                        <tr>
                            <td valign="top" width="50%" style="padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border:0px;">
                                    <tr>
                                        <td valign="top" height="98px" style="border-right:0px;">
                                            <div style="padding-bottom:5px;"><b>Name &amp; Address of Consignee:</b><br /></div>
                                            <div class="content_bold" style="font-size:14px;">
												<?php print strtoupper($buyer); ?>
											</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0px;border-right:0px;border-bottom:0px;" valign="top">
                                            <table border="0" cellspacing="0" cellpadding="3" style="width:100%;border:0px;">
                                                <tr>
                                                    <td colspan="5" class="content_bold content_center" style="border-right:0px;width:45%;">
                                                        Consignee's Details
                                                    </td>
                                                </tr>											
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;width:45%;">
                                                        TIN No. :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;">
                                                         : <?php print $data['dcBGSTN']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;">
                                                        CST No. / Date :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;">
                                                         : <?php print $data['dcBPAN']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="content_bold" style="border-right:0px;">
                                                        ECC No. :
                                                    </td>
                                                    <td colspan="3" style="border-right:0px;">
                                                         : <?php print $data['dcSupplyPlace']; ?>&nbsp;
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top" style="border-right:0px;padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                                    <tr>
                                        <td width="50%" class="content_bold" style="border-right:0px;">
                                            Delivery Challan No.
                                        </td>
                                        <td  class="content_bold"  style="border-right:0px;font-size:14px;">
                                            : <?php print $data['dcId']; ?>
                                        </td>
									</tr>
									<tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            DC Date
                                        </td>
                                        <td class="content_bold"  style="border-right:0px;font-size:14px;" >
                                            : <?php print date("d-m-Y", strtotime($data['dcDate'])); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Excise Control Code
                                        </td>
                                        <td  style="border-right:0px;">
                                            : <?php print $data['dcECCNo']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="content_bold" style="border-right:0px;">
                                            Excise Range
                                        </td>
                                        <td  style="border-right:0px;">
                                            : <?php print $data['dcERange']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Division
                                        </td>
                                        <td style="border-right:0px;">
                                            : <?php print $data['dcEDivision']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Comissionerate
                                        </td>
                                        <td  style="border-right:0px;">
                                            : <?php print $data['dcEComissionerate']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            TIN No.
                                        </td>
                                        <td style="border-right:0px;">
                                            : <?php print $data['dcGSTN']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            CST No. / Date
                                        </td>
                                        <td style="border-right:0px;">
                                             : <?php print $data['dcPAN']; ?>&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content_bold" style="border-right:0px;">
                                            Shipping Date
                                        </td>
                                        <td style="border-right:0px;">
                                             : <?php print date("d-m-Y", strtotime($data['dcShipDate'])); ?>&nbsp;
                                        </td>
                                    </tr>									
                                </table>
                            </td>
                        </tr>
                    </table>					
                </td>
            </tr>
			<?php } ?>
            <tr>
            	<th width="3%">
                	No
                </th>
            	<th width="12%" style="border-right:0px;">
                	&nbsp;
                </th>
            	<th width="15%" align='left'>
                	Item Name
                </th>
				<th width="15%">
					HSN Code
                </th>
            	<th width="25%">
                	Description
                </th>
            	<th width="15%">
                	Batch ID
                </th>
            	<th width="15%">
                	Qty (<?php print $dcUOM ?>)
                </th>
             </tr>
            <?php
				$totsno		=	30;
				$pgBrk		=	35;
				if ($noofBatches > 30)
				{
					$totsno = $noofBatches;
				}								
				$sno		=	1;
				$tqty		=	0;
				for($p=0;$p<$totsno;$p++){
					$tqty	=	$tqty + $particulars[$p]['dcQty'];
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="7" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="7" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr>
							<th width="3%">
								No
							</th>
							<th width="12%" style="border-right:0px;">
								&nbsp;
							</th>
							<th width="15%" align='left'>
								Item Name
							</th>
							<th width="15%">
								HSN Code
							</th>
							<th width="25%">
								Description
							</th>
							<th width="15%">
								Batch ID
							</th>
							<th width="15%">
								Qty (<?php print $dcUOM ?>)
							</th>			
						 </tr>							
					<?php	
						}
					?>	
                    <tr>
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
						<td colspan="2">
                            <?php print ($particulars[$p]['dcName'])?$particulars[$p]['dcName']:'&nbsp;'; ?>
                        </td>
						<td>
                            <?php 
							if( $p < $noofBatches )
							{
								if ($HSN[$dcItemType])								
									print $HSN[$dcItemType];
								else if ($dcItemType == 'ram')
								{
									//$ramData	=	@getMySQLData("select ramHSNCode from tbl_rawmaterial where ramid = '".$particulars[$p]['dcCode']."'");
									//print $ramData['data'][0]['ramHSNCode'];
									$ramData	=	@getMySQLData("select invHSNCode from tbl_invoice_grn where grnId = '".$particulars[$p]['dcItemId']."'");									
									print $ramData['data'][0]['invHSNCode'];
								}
							}							
							?>
							&nbsp;
                        </td>						
                        <td>
                            <?php print ($particulars[$p]['dcDesc'])?$particulars[$p]['dcDesc']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php							
							if($isCmpd)
							{
								print $particulars[$p]['planId'];
							}							
							else
							{
								$partBatId	=	"";
								if($particulars[$p]['batdate'] != "" && $particulars[$p]['batdate'] != "0000-00-00") {
									$mixDate			= date("d-m-Y", strtotime($particulars[$p]['batdate']));
									list($d, $m, $y) 	= explode('-', $mixDate);
									$partBatId			=	"/".$d . $cpdMonthCode[$m+0];	
								}
								$itemId		=	$particulars[$p]['dcItemId'];
								print ($itemId)?(strpos($itemId,'_')!== false)?substr(strrchr($itemId, "_"),1).$partBatId:$itemId.$partBatId :'&nbsp;';
							}
							?>
                        </td>
                        <td class="content_right">
                            <?php print ($particulars[$p]['dcQty'])?($isCmpd)?@number_format($particulars[$p]['dcQty'], 0):@number_format($particulars[$p]['dcQty'], 3):'&nbsp;'; ?>
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="6" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print ($isCmpd)?@number_format($tqty, 0):@number_format($tqty, 3); ?>
                </td>
           </tr>
           <tr>
				<td colspan="7" style="padding:0px;">
                    <table cellspacing="0" cellpadding="5" width="100%">
                        <tr height="50px">
                            <td rowspan="2" style="width:30%;border-bottom:0px;" valign="top">
								Remarks<br/><b><?php print $data['dcRemarks']; ?></b>&nbsp;
                            </td>
                            <td rowspan="2" style="width:30%;border-bottom:0px;" valign="top">
                                Receiver's Seal & Sign
                            </td>
							<td style="width:40%;border-bottom:0px;border-right:0px;" valign="top">
                                For <?php  echo $_SESSION['app']['comp_name'];?>
                            </td>
						</tr>
						<tr>
							<td style="text-align:right;border-bottom:0px;" valign="bottom">Authorised Signatory</td>
						</tr>
                    </table>
                </td>
			</tr>			 
            <tr>
            	<td colspan="7" style="padding:0px;border-bottom:0px;">
                    <table cellspacing="0" cellpadding="3" width="100%">
                        <tr>
                            <td style="line-height:1.5em;width:25%;border-bottom:0px;">
                                Consignee's Copy - <b>White</b>
                            </td>
                            <td style="line-height:1.5em;width:25%;border-bottom:0px;">
                                Transporter's Copy - <b>Blue</b>
                            </td>
                            <td style="line-height:1.5em;width:25%;border-bottom:0px;">
                                Assessee's Copy - <b>Pink</b>
                            </td>
                            <td style="line-height:1.5em;width:25%;border-bottom:0px;border-right:0px;">
                                Extra Copy - <b>Yellow</b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
		<?php 
			if($dcCount+1<count($dcIDs))
				echo "<div class='page_break'><br /></div>";
		}
		?>		
    </body>
</html>