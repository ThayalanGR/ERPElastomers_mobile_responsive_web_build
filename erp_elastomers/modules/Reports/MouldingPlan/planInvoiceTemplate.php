<?php

	$batchDate		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$oper			=	(ISO_IS_REWRITE)?trim($_VAR['operator']):trim($_GET['operator']);
	$sql_batInfo	=	"	select imp.invid,  imp.no_of_active_cavities,imp.cmpdBlankWgt,imp.operator,
							imp.planid,imp.cmpdName , imp.cmpdcpdname, (imp.liftplanned * 1) as liftplanned,
							(if((select((imp.liftplanned * 1) * imp.no_of_active_cavities))>0,(select((imp.liftplanned * 1) * imp.no_of_active_cavities)),0)) as plannedQty,
							if((select((imp.liftplanned * imp.cmpdBlankWgt * imp.no_of_active_cavities)/1000))>0,(select((imp.liftplanned * imp.cmpdBlankWgt * imp.no_of_active_cavities)/1000)),0) as cmpdReq
						from tbl_invoice_mould_plan imp
							inner join tbl_moulding_plan tmp on imp.planid = tmp.planid
							inner join tbl_polymer_order tpo on tpo.polyName = imp.cmpdPolymer 
						where tmp.status > 0 and imp.invdate = '$batchDate' order by imp.operator,tpo.dispOrder asc";
	$batInfo		=	@getMySQLData($sql_batInfo);
	$particulars	=	$batInfo['data'];
	$noofKeys		=   count($particulars);
	$planDate		= 	date_format(new DateTime($batchDate),'d-m-Y');
	$issueDate		=	date('d-m-Y',(strtotime ( '-1 day' , strtotime ( $batchDate) ) ));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $planDate ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
	<?php if($oper == "In-House")
	{?>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(17);
				print $formArray[0]; 
			?>
		</p>		
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="10" class="content_bold cellpadding content_center" height="40px">
                	<div style="font-size:16px;"><?php print $formArray[1]; ?></div>
                </td>
                <td rowspan="2" colspan="2" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Prod Date: </div><div style="font-size:14px;" ><?php echo $planDate; ?></div>
                </td>
            </tr>
			<tr>
				<td colspan='10' align="center" style="font-size:14px;"><b>No of Keys: <?php echo $noofKeys; ?></b>
				</td>
			<tr>
			<tr style="font-size:8px;" >
				<th align="center" width="3%">S.No</th>
				<th align="center" width="6%">Key ID</th>
				<th align="center" width="9%">Part No.</th>              
				<th align="center" width="15%">Location</th>
				<th align="center" width="9%">Compound Name</th>
				<th align="center" width="4%">Cavities</th>
				<th align="center" width="6%">Blank Weight</th>
				<th align="center" width="6%">Cmpd. Req.</th>
				<th align="center" width="7%">Plan. Lift</th>
				<th align="center" width="7%">Act. Lift - I</th>
				<th align="center" width="7%">Act. Lift - II</th>
				<th align="center" width="7%" >Plan. Qty</th>
				<th align="center" width="7%" >Act. Qty - I</th>
				<th align="center">Act. Qty - II</th>				
			</tr>
            <?php
				$totsno		=	20;
				$pgBrk		=	20;
				if ($noofKeys > 20)
				{
					$totsno 	= $noofKeys;
				}				
				$sno		=	1;
				$tqty		=	0;
				$tcpdqty  	= 	0;
				$tpliftsqty	=	0;
				$tpcompqty 	= 	0;
				$polymerName = '';
				for($p=0;$p<$totsno;$p++){
					$tcpdqty 	+= 	$particulars[$p]['cmpdReq'];	
					$tpliftsqty += 	$particulars[$p]['liftplanned'];						
					$tpcompqty 	+=  $particulars[$p]['plannedQty'];
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="14" >
								Remarks: 
								<br /><br /><br /><br />
							</td>
						</tr>
						<tr>
							<td colspan="14" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="14" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;" >
							<th align="center" width="3%">S.No</th>
							<th align="center" width="6%">Key ID</th>
							<th align="center" width="9%">Part No.</th>              
							<th align="center" width="15%">Location</th>
							<th align="center" width="9%">Compound Name</th>
							<th align="center" width="4%">Cavities</th>
							<th align="center" width="6%">Blank Weight</th>
							<th align="center" width="6%">Cmpd. Req.</th>
							<th align="center" width="7%">Plan. Lift</th>
							<th align="center" width="7%">Act. Lift - I</th>
							<th align="center" width="7%">Act. Lift - II</th>
							<th align="center" width="7%" >Plan. Qty</th>
							<th align="center" width="7%" >Act. Qty - I</th>
							<th align="center" width="7%" >Act. Qty - II</th>				
						</tr>									
					<?php	
						}
					?>					
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center" >
                           <?php print ($particulars[$p]['planid'])?((strpos($particulars[$p]['planid'],'_')!== false)?strstr($particulars[$p]['planid'],"_",true):$particulars[$p]['planid']):'&nbsp;';?>                            
                        </td>
                        <td align="left">
							<?php print ($particulars[$p]['cmpdName'])?$particulars[$p]['cmpdName']:'&nbsp;';?>
                        </td>						
                        <td align="left">
                            <?php print ($particulars[$p]['operator'])?$particulars[$p]['operator']:'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['cmpdcpdname'])?$particulars[$p]['cmpdcpdname']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['no_of_active_cavities'])?$particulars[$p]['no_of_active_cavities']:'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print ($particulars[$p]['cmpdBlankWgt'])?@number_format($particulars[$p]['cmpdBlankWgt'],2):'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($particulars[$p]['cmpdReq'])?@number_format($particulars[$p]['cmpdReq'],3):'&nbsp;'; ?>
                        </td>
						<td align="right">
                            <?php print ($particulars[$p]['liftplanned'])?@number_format($particulars[$p]['liftplanned'],0):'&nbsp;'; ?>
                        </td>
						<td align="right">
                            &nbsp;
                        </td>
						<td align="right">
                            &nbsp;
                        </td>
						<td align="right">
                            <?php print ($particulars[$p]['plannedQty'])?@number_format($particulars[$p]['plannedQty'],0):'&nbsp;'; ?>
                        </td>	
						<td align="right">
                            &nbsp;
                        </td>
						<td align="right">
                            &nbsp;
                        </td>					
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="7" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tcpdqty, 3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tpliftsqty, 0); ?>
                </td>
				<td>
                	&nbsp;
                </td>
				<td>
                	&nbsp;
                </td>					
               <td class="content_bold content_right">
                	<?php echo @number_format($tpcompqty,0); ?>
                </td>
				<td>
                	&nbsp;
                </td>				
				<td>
                	&nbsp;
                </td>						
            </tr>			 
            <tr>
            	<td colspan="14" >
						Remarks: 
					<br /><br /><br /><br />
                </td>
            </tr>	
			<tr>
            	<td colspan="4" class="content_left content_bold">
                	Prepared:
                </td>
				<td colspan="3" class="content_left content_bold">
                	Supervisor:
                </td>	
				<td colspan="3" class="content_left content_bold">
                	Approved:
                </td>				
				<td colspan="4" class="content_left content_bold" >
                	Data Entry:
                </td>	
            </tr>	
		</table>
<?php
	}
	/*$sql_CPDInfo		=	" select timp.cmpdcpdname,disporder,(tm.liftPlanned * no_of_active_cavities * cmpdBlankWgt )/1000 as totcpdadv, tm.planid
								from tbl_moulding_plan tm
									INNER JOIN tbl_invoice_mould_plan timp ON timp.planid = tm.planid 
									inner join tbl_polymer_order tpo on tpo.polyName = timp.cmpdPolymer
								WHERE tm.status > 0 AND tm.planDate = '$batchDate' and tm.operator = '$oper'  
								order by disporder,cmpdcpdname";*/
	$sql_CPDInfo		=	"select cmpdcpdname,disporder, sum(totcpdadv) as totcpdadv, sum(totcpdiss) as totcpdiss 
								from (
									(select cpdname as cmpdcpdname,disporder,0 as totcpdadv,sum(qtyiss) as totcpdiss 
									from tbl_moulding_issue tmi
										inner join tbl_component_cpd_recv tccr on tmi.batref = tccr.sno
										inner join tbl_compound	tc	on tc.cpdid	=	tccr.cpdid
										inner join tbl_polymer_order tpo on tpo.polyname = tc.cpdpolymer
									where tmi.status > 0 and tmi.mdIssRef in ( select distinct mdIssRef from  tbl_moulding_plan where planDate = '$batchDate' and operator = '$oper') group by tc.cpdname )
									UNION ALL
									(select timp.cmpdcpdname,disporder,sum(tm.liftPlanned * no_of_active_cavities * cmpdBlankWgt )/1000 as totcpdadv, 0 as totcpdiss
									from tbl_moulding_plan tm
										INNER JOIN tbl_invoice_mould_plan timp ON timp.planid = tm.planid 
										inner join tbl_polymer_order tpo on tpo.polyName = timp.cmpdPolymer
									WHERE tm.status > 0 AND tm.planDate = '$batchDate' and tm.operator = '$oper' group by timp.cmpdcpdname)
									)tbl1 group by cmpdcpdname order by disporder,cmpdcpdname";
	$cpdInfo			=	@getMySQLData($sql_CPDInfo);
	$cpdParticulars		=	$cpdInfo['data'];
	$noofCPDs			=   count($cpdParticulars);
?>
		<div class="page_break" />
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(18);
				print $formArray[0]; 
			?>
		</p>
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
             	<td rowspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;"><?php print $formArray[1]; ?></div>
                </td>
                <td rowspan="2" class="content_left content_bold uppercase" >
					<div style="font-size:14px;">Date: <BR><?php echo $issueDate; ?></BR></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	For <?php print $oper; ?>
                </td>			
			</tr>
			<tr style="font-size:8px;">
            	<th width="10%">
                	No
                </th>
            	<th width="25%">
                	Compound Name
                </th>
             	<th width="12%">
                	Advised Qty<sup>Kgs</sup>
                </th>
            	<th width="12%">
                	Issued Qty<sup>Kgs</sup>
                </th>
            	<th width="29%">
                	Batch Id
                </th>
            	<th>
                	Returned Qty<sup>Kgs</sup>
                </th>	 
             </tr>
            <?php
				$totRows		=	20;
				$ramPgBrk		=	20;
				if ($noofCPDs > $totRows)
				{
					$totRows = $noofCPDs;
				}
				$totAdvQty		=	0;
				$totIssQty		=	0;
				for($p=0;$p<$totRows;$p++){
					$totAdvQty	+=	$cpdParticulars[$p]['totcpdadv'];
					$totIssQty	+=	$cpdParticulars[$p]['totcpdiss'];
					if($p % $ramPgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="6" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="6" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;">
							<th width="10%">
								No
							</th>
							<th width="25%">
								Compound Name
							</th>
							<th width="12%">
								Advised Qty<sup>Kgs</sup>
							</th>
							<th width="12%">
								Issued Qty<sup>Kgs</sup>
							</th>
							<th width="29%">
								Batch Id
							</th>
							<th>
								Returned Qty<sup>Kgs</sup>
							</th>	 
						 </tr>									
					<?php	
						}
					?>						
	                <tr>
                        <td align="center" height="20px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left">
							<?php print ($cpdParticulars[$p]['cmpdcpdname'])?$cpdParticulars[$p]['cmpdcpdname']:'&nbsp;';?>
                        </td>
                        <td align="right">
                            <?php 
								if ($cpdParticulars[$p]['totcpdadv'] > 0) 
									print @number_format($cpdParticulars[$p]['totcpdadv'],3);
								else if($p < $noofCPDs)
									print'0.000';
								else
									print '&nbsp;';
							?>
                        </td>
						<td align="right">
                            <?php print ($cpdParticulars[$p]['totcpdiss'] > 0)?@number_format($cpdParticulars[$p]['totcpdiss'],3):'&nbsp;';?>
                        </td>						
                        <td align="center">
                            &nbsp;
                        </td>
                        <td align="center">
                            &nbsp;
                        </td>						
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="2" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totAdvQty, 3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print ($totIssQty > 0)?@number_format($totIssQty, 3):'&nbsp;'; ?>
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>			
             </tr>
            <tr>
            	<td colspan="3" >
					Remarks: 
					<br /><br /><br /><br />
                </td>			
				<td colspan="2" class="content_left content_bold" >
                	Issued By:
                </td>				
				<td colspan="2" class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
	<?php
	if($oper == "In-House")
	{		
		$sql_CPDShInfo		=	"select timp.cmpdcpdname,disporder,sum(tm.liftPlanned * no_of_active_cavities * cmpdBlankWgt )/1000 as totcpdadv, strip_dim1 as strip_thk
								from tbl_moulding_plan tm
									INNER JOIN tbl_invoice_mould_plan timp ON timp.planid = tm.planid 
									inner join tbl_polymer_order tpo on tpo.polyName = timp.cmpdPolymer
								WHERE tm.status > 0 AND  tm.planDate = '$batchDate'	and tm.operator = '$oper'  						
								group by cmpdcpdname,strip_dim1
								order by disporder,cmpdcpdname";
		$cpdShInfo			=	@getMySQLData($sql_CPDShInfo);
		$cpdShParticulars	=	$cpdShInfo['data'];
		$noofCPDs			=   count($cpdShParticulars);
		?>		
		<div class="page_break" />
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(19);
				print $formArray[0]; 
			?>
		</p>
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
             	<td colspan="1" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="3" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
                </td>
                <td class="content_left content_bold uppercase" >
					<div style="font-size:14px;">Date: <BR><?php echo $issueDate; ?></BR></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr style="font-size:8px;">
            	<th width="10%">
                	No
                </th>
            	<th width="40%">
                	Compound Name
                </th>
             	<th width="15%">
					Qty<sup>Kgs</sup>
                </th>
            	<th width="15%">
                	Sheet Thickness &#8723; 2 <sup>mm</sup>
                </th>
            	<th>
                	Remarks
                </th>	 
             </tr>
            <?php
				$totRows		=	20;
				$ramPgBrk		=	20;
				if ($noofCPDs > 20)
				{
					$totRows = $noofCPDs;
				}
				$totQty		=	0;
				for($p=0;$p<$totRows;$p++){
					$totQty	+=	$cpdShParticulars[$p]['totcpdadv'];
					if($p % $ramPgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="6" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="5" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;">
							<th width="10%">
								No
							</th>
							<th width="40%">
								Compound Name
							</th>
							<th width="15%">
								Qty<sup>Kgs</sup>
							</th>
							<th width="15%">
								Sheet Thickness &#8723; 2  <sup>mm</sup>
							</th>
							<th>
								Remarks
							</th>	 
						 </tr>									
					<?php	
						}
					?>						
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left">
							<?php print ($cpdShParticulars[$p]['cmpdcpdname'])?$cpdShParticulars[$p]['cmpdcpdname']:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($cpdShParticulars[$p]['totcpdadv'])?@number_format($cpdShParticulars[$p]['totcpdadv'],3):'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print ($cpdShParticulars[$p]['strip_thk'])?@number_format($cpdShParticulars[$p]['strip_thk'],1):'&nbsp;';?>
                        </td>						
                        <td align="center">
                            &nbsp;
                        </td>						
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="2" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totQty, 3); ?>
                </td>
                <td class="content_bold content_right">
                	&nbsp;
                </td>
                <td class="content_bold content_right">
                	&nbsp;
                </td>				
             </tr>
            <tr>
            	<td colspan="2" >
 					Remarks: 
					<br /><br /><br /><br />
                </td>			
				<td colspan="2" class="content_left content_bold" >
                	Issued By:
                </td>				
				<td class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
		<?php		
		$sql_CPDBlkInfo		=	"select timp.cmpdcpdname,disporder,sum(tm.liftPlanned * no_of_active_cavities * cmpdBlankWgt )/1000 as totcpdadv, strip_dim1 as strip_thk, strip_dim2 as strip_len, strip_dim1 as strip_bre, blank_type, blank_profile, strip_weight
								from tbl_moulding_plan tm
									INNER JOIN tbl_invoice_mould_plan timp ON timp.planid = tm.planid 
									inner join tbl_polymer_order tpo on tpo.polyName = timp.cmpdPolymer
								WHERE tm.status > 0 AND  tm.planDate = '$batchDate' and tm.operator = '$oper'  
								group by cmpdcpdname,strip_dim1,strip_dim2,strip_dim3,strip_weight
								order by disporder,cmpdcpdname";
		$CPDBlkInfo			=	@getMySQLData($sql_CPDBlkInfo);
		$cpdBlkParticulars	=	$CPDBlkInfo['data'];
		$noofCPDs			=   count($cpdBlkParticulars);
		?>		
		<div class="page_break" />
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(20);
				print $formArray[0]; 
			?>
		</p>
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
             	<td colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="7" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
                </td>
                <td class="content_left content_bold uppercase" >
					<div style="font-size:14px;">Date: <BR><?php echo $issueDate; ?></BR></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr style="font-size:8px;">
            	<th width="3%">
                	No
                </th>
            	<th width="18%">
                	Compound Name
                </th>
             	<th width="8%">
					Qty<sup>Kgs</sup>
                </th>
            	<th width="10%">
                	Type
                </th>
            	<th width="10%">
                	Profile
                </th>
            	<th width="14%" align="right">
                	Weight<sup>gm</sup>
                </th>
            	<th width="8%" align="right">
                	Thickness* <sup>mm</sup>
                </th>
            	<th width="8%" align="right">
                	Length* <sup>mm</sup>
                </th>
            	<th width="8%" align="right">
                	Breath* <sup>mm</sup>
                </th>				
            	<th>
                	Remarks
                </th>	 
             </tr>
            <?php
				$totRows		=	20;
				$ramPgBrk		=	20;
				if ($noofCPDs > 20)
				{
					$totRows = $noofCPDs;
				}
				$totQty		=	0;
				for($p=0;$p<$totRows;$p++){
					$totQty	+=	$cpdBlkParticulars[$p]['totcpdadv'];
					if($p % $ramPgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="10" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="10" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;">
							<th width="3%">
								No
							</th>
							<th width="18%">
								Compound Name
							</th>
							<th width="8%">
								Qty<sup>Kgs</sup>
							</th>
							<th width="10%">
								Type
							</th>
							<th width="10%">
								Profile
							</th>
							<th width="14%" align="right">
								Weight<sup>gm</sup>
							</th>
							<th width="8%" align="right">
								Thickness* <sup>mm</sup>
							</th>
							<th width="8%" align="right">
								Length* <sup>mm</sup>
							</th>
							<th width="8%" align="right">
								Breath* <sup>mm</sup>
							</th>				
							<th>
								Remarks
							</th>	 
						 </tr>									
					<?php	
						}
					?>						
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left">
							<?php print ($cpdBlkParticulars[$p]['cmpdcpdname'])?$cpdBlkParticulars[$p]['cmpdcpdname']:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($cpdBlkParticulars[$p]['totcpdadv'])?@number_format($cpdBlkParticulars[$p]['totcpdadv'],3):'&nbsp;';?>
                        </td>
                        <td align="left">
							<?php print ($cpdBlkParticulars[$p]['blank_type'])?$cpdBlkParticulars[$p]['blank_type']:'&nbsp;';?>
                        </td>
                        <td align="left">
							<?php print ($cpdBlkParticulars[$p]['blank_profile'])?$cpdBlkParticulars[$p]['blank_profile']:'&nbsp;';?>
                        </td>
                        <td align="right">
							&nbsp;
							<?php
								$strip_weight	=	str2num($cpdBlkParticulars[$p]['strip_weight']);
								if ($strip_weight <= 0 )
									print ""; 
								else if ($strip_weight > 1 && $strip_weight <= 2) 
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.30),1); 
								else if ($strip_weight > 2 && $strip_weight <= 5) 
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.25),1);
								else if  ($strip_weight > 5 && $strip_weight <= 10) 
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.15),1); 
								else if ($strip_weight > 10 )
									print number_format($strip_weight,1)." - ".ceiling($strip_weight + ($strip_weight * 0.10),1);
							?>						
                        </td>						
						<td align="right">
                            <?php print ($cpdBlkParticulars[$p]['strip_thk'] > 0)?(($cpdBlkParticulars[$p]['strip_thk'] > 1)?@number_format($cpdBlkParticulars[$p]['strip_thk'] - 1,1):@number_format($cpdBlkParticulars[$p]['strip_thk'],1)) . " - " .@number_format($cpdBlkParticulars[$p]['strip_thk'] + 2,1)  :'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print ($cpdBlkParticulars[$p]['strip_len'] > 0)?@number_format($cpdBlkParticulars[$p]['strip_len'],1):'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print ($cpdBlkParticulars[$p]['strip_bre'] > 0)?(($cpdBlkParticulars[$p]['strip_bre'] > 1)?@number_format($cpdBlkParticulars[$p]['strip_bre'] - 1,1):@number_format($cpdBlkParticulars[$p]['strip_bre'],1)). " - " .@number_format($cpdBlkParticulars[$p]['strip_bre'] + 2,1):'&nbsp;';?>
                        </td>						
                        <td align="center">
                            &nbsp;
                        </td>						
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="2" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totQty, 3); ?>
                </td>
                <td class="content_bold content_right" colspan="7">
                	* Reference Only
                </td>
             </tr>
            <tr>
            	<td colspan="5" >
  					Remarks: 
					<br /><br /><br /><br />
                </td>			
				<td colspan="3" class="content_left content_bold" >
                	Issued By:
                </td>				
				<td colspan="2" class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
	<?php } ?>
    </body>
</html>