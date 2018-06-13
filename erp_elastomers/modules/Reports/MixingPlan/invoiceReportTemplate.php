<?php

	$noOfMonths				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$cutOffDate 			= 	date('Y-m-d', mktime(0, 0, 0, date('n')-$noOfMonths, 1, date('y')));
	$sql_batInfo			=	"select * 
									from (select batDate, date_format(batDate,'%b-%Y') as batMonth, count(tbl_mixing.batId) as mastPlanned, sum(if(status > 1,1,0)) as mastActual, sum(planQty) as MasterPlanQty, sum(masterBatchWgt) as MasterActQty
											from tbl_mixing	
												inner join (select tm.batId, sum(planQty) as planQty 
																from tbl_invoice_mixplan_items tmp 
																	inner join tbl_mixing tm on tm.batId = tmp.batId and tm.is_mill_batch = 0 and batDate >= '$cutOffDate' 
																	inner join tbl_compound_rm tcr on tcr.ramId = tmp.ramId and tcr.cpdId = tm.cpdId and tcr.is_final_chemical = 0 group by tm.batId) pwt on pwt.batId = tbl_mixing.batId								
											where batDate >= '$cutOffDate' and is_mill_batch = 0
											group by date_format(batDate,'%b-%Y') ) tbl1
										right join (select date_format(batFinalDate,'%b-%Y') as batMonth, count(tbl_mixing.batId) as finalPlanned, sum(if(status > 2,1,0)) as finalActual, sum(planQty) as FinalPlanQty, sum(finalQty) as FinalActQty
													from tbl_mixing 
														inner join (select tm.batId, sum(planQty) as planQty from tbl_invoice_mixplan_items tmp 
																		inner join tbl_mixing tm on tm.batId = tmp.batId and batFinalDate >= '$cutOffDate' group by tm.batId) pwt on pwt.batId = tbl_mixing.batId
														left join (select tm.batId, sum(batRecvWgt) as finalQty from tbl_mixing_recv tmr 
																		inner join tbl_mixing tm on tm.batId = tmr.batId and batFinalDate >= '$cutOffDate' group by tm.batId) awt on awt.batId = tbl_mixing.batId									
													where batFinalDate >= '$cutOffDate'
													group by date_format(batFinalDate,'%b-%Y') ) tbl2 on tbl2.batMonth = tbl1.batMonth
										
									order by batDate";	
	$batInfo				=	@getMySQLData($sql_batInfo);
	$particulars			=	$batInfo['data'];
	$noofRecords			=	count($particulars);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mixing Plan Vs Actual Report</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="5" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;">Mixing Plan Vs Actual Report</div>
                </td>
                <td rowspan="2" colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Date: </div>
					<div style="font-size:14px;" ><?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?></div>
					<div style="font-size:34px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
				<td colspan='5' align="center" style="font-size:14px;"> <b>Report for last: <?php echo $noOfMonths; ?> months</b>
				</td>
			<tr>
			<tr style="font-size:8px;">
				<th width="14%">
					Month
				</th>
				<th width="8%">
					Master Batches (Planned)<sup>Nos</sup>
				</th>
				<th width="13%">
					Master Weight (Planned)<sup>Kgs</sup>
				</th>
				<th width="8%">
					Master Batches (Actual)<sup>Nos</sup>
				</th>
				<th width="13%">
					Master Weight (Actual)<sup>Kgs</sup>
				</th>
				<th width="8%">
					Final Batches (Planned)<sup>Nos</sup>
				</th>
				<th width="13%">
					Final Weight (Planned)<sup>Kgs</sup>
				</th>
				<th width="8%">
					Final Batches (Actual)<sup>Nos</sup>
				</th>				
				<th>
					Final Weight (Actual)<sup>Kgs</sup>
				</th>				
			 </tr>
            <?php
				$pgBrk				=	25;
				$tMasterPlanBatches	=	0;
				$tMasterPlanQty		=	0;	
				$tMasterActBatches 	= 	0;
				$tMasterActQty		=	0;	
				$tFinalPlanBatches	=	0;
				$tFinalPlanQty		=	0;	
				$tFinalActBatches 	= 	0;
				$tFinalActQty		=	0;					
				for($p=0;$p<$noofRecords;$p++){
					$tMasterPlanBatches = 	$tMasterPlanBatches + $particulars[$p]['mastPlanned'] ;
					$tMasterPlanQty 	= 	$tMasterPlanQty + $particulars[$p]['MasterPlanQty'];
					$tMasterActBatches 	= 	$tMasterActBatches + $particulars[$p]['mastActual'];
					$tMasterActQty 		= 	$tMasterActQty + $particulars[$p]['MasterActQty'];
					$tFinalPlanBatches  = 	$tFinalPlanBatches + $particulars[$p]['finalPlanned'] ;
					$tFinalPlanQty 		= 	$tFinalPlanQty + $particulars[$p]['FinalPlanQty'];					
					$tFinalActBatches 	= 	$tFinalActBatches + $particulars[$p]['finalActual'];
					$tFinalActQty 		= 	$tFinalActQty + $particulars[$p]['FinalActQty'];
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="9" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break">
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="9" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;">
							<th width="14%">
								Month
							</th>
							<th width="8%">
								Master Batches (Planned)<sup>Nos</sup>
							</th>
							<th width="13%">
								Master Weight (Planned)<sup>Kgs</sup>
							</th>
							<th width="8%">
								Master Batches (Actual)<sup>Nos</sup>
							</th>
							<th width="13%">
								Master Weight (Actual)<sup>Kgs</sup>
							</th>
							<th width="8%">
								Final Batches (Planned)<sup>Nos</sup>
							</th>
							<th width="13%">
								Final Weight (Planned)<sup>Kgs</sup>
							</th>
							<th width="8%">
								Final Batches (Actual)<sup>Nos</sup>
							</th>				
							<th>
								Final Weight (Actual)<sup>Kgs</sup>
							</th>			
						 </tr>							
					<?php	
						}
					?>	
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($particulars[$p]['batMonth'])?$particulars[$p]['batMonth']:'&nbsp;'; ?>
                        </td>
                        <td align="right">						
                            <?php print ($particulars[$p]['mastPlanned'])?@number_format($particulars[$p]['mastPlanned'],0):'0';?>
                        </td>
                        <td align="right">
                            <?php print ($particulars[$p]['MasterPlanQty'])?@number_format($particulars[$p]['MasterPlanQty'],3):'0.000';?>
                        </td>
                        <td align="right">						
                            <?php print ($particulars[$p]['mastActual'])?@number_format($particulars[$p]['mastActual'],0):'0';?>
                        </td>
                        <td align="right">
							<?php print ($particulars[$p]['MasterActQty'])?@number_format($particulars[$p]['MasterActQty'],3):'0.000';?>
                        </td>
                        <td align="right">						
                            <?php print ($particulars[$p]['finalPlanned'])?@number_format($particulars[$p]['finalPlanned'],0):'0';?>
                        </td>
                        <td align="right">
                            <?php print ($particulars[$p]['FinalPlanQty'])?@number_format($particulars[$p]['FinalPlanQty'],3):'0.000'; ?>
                        </td>
                        <td align="right">						
                            <?php print ($particulars[$p]['finalActual'])?@number_format($particulars[$p]['finalActual'],0):'0';?>
                        </td>
                        <td align="right">
                            <?php print ($particulars[$p]['FinalActQty'])?@number_format($particulars[$p]['FinalActQty'],3):'0.000'; ?>
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tMasterPlanBatches); ?>
                </td>
				<td class="content_right content_bold" >
                	<?php echo @number_format($tMasterPlanQty,3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tMasterActBatches); ?>
                </td>
				<td class="content_right content_bold" >
                	<?php echo @number_format($tMasterActQty,3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tFinalPlanBatches); ?>
                </td>
				<td class="content_right content_bold" >
                	<?php echo @number_format($tFinalPlanQty,3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tFinalActBatches); ?>
                </td>
				<td class="content_right content_bold" >
                	<?php echo @number_format($tFinalActQty,3); ?>
                </td>					
             </tr>
			<tr height="45px">
            	<td colspan="5" class="content_left content_bold" >
                	Prepared:
                </td>
				<td colspan="4" class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
    </body>
</html>