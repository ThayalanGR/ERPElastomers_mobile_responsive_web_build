<?php
	$batchDate				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$planDate				= 	date_format(new DateTime($batchDate),'d-m-Y');
	$sql_shiftInfo			=	"select distinct shift as shift from tbl_mixing  where is_mill_batch = 0 and status > 0 and  batdate='$batchDate' and is_open_stock = 0 order by shift asc";
	$shiftInfo				=	@getMySQLData($sql_shiftInfo);
	$shifts					=	$shiftInfo['data'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $planDate ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
<?php
	for($numShifts=0;$numShifts<count($shifts);$numShifts++)
	{
		$shift					=	$shifts[$numShifts]['shift'];
		$sql_batInfo			=	"select batid, tbm.cpdid,cpdname,masterbatchwgt,dumptemp,disporder,polyabbr,kneadtempmax,customer
										from tbl_mixing tbm 
										inner join tbl_compound tbc on tbm.cpdid=tbc.cpdid 
										inner join tbl_polymer_order tpo on tpo.polyname = tbc.cpdpolymer
									where  tbm.is_mill_batch = 0 and tbm.status > 0 and  tbm.batdate='$batchDate' and tbm.is_open_stock = 0 and tbm.shift = '$shift'
									order by disporder,cpdname,batid asc";
		$batInfo				=	@getMySQLData($sql_batInfo);
		$particulars			=	$batInfo['data'];
		$noofBatches			=   count($particulars);
		if($numShifts>0 )
		{	
			echo '<div class="page_break" /><br />';
		}				
	?>	
	
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(14);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="5" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;"><?php print $formArray[1]; ?></div>
                </td>
                <td rowspan="2" colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Prod Date: <br /><?php echo $planDate; ?></div>
					<div style="font-size:12px;">Shift:<?php echo $shift; ?></div>
					<div style="font-size:12px;">From: &nbsp; &nbsp;&nbsp; &nbsp; </div>
					<div style="font-size:12px;">To: &nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
				<td colspan='5' align="center" style="font-size:14px;"><b>No of Batches: <?php echo $noofBatches; ?></b>
				</td>
			<tr>
			<tr style="font-size:8px;" >
				<th width="5%">
					No
				</th>
				<th width="15%">
					Polymer
				</th>
				<th width="20%">
					Comp. Ref.
				</th>
				<th width="8%">
					Planned For
				</th>				
				<th width="10%">
					Batch No
				</th>
				<th width="10%">
					Adv. Dump Temp.<sup>&deg;C</sup>
				</th>				
				<th width="10%">
					Dump Temp.<sup>&deg;C</sup>
				</th>
				<th width="10%">
					Exp. Batch Wt.<sup>Kgs</sup>
				</th>				
				<th>
					Act. Batch Wt.<sup>Kgs</sup>
				</th>				
			</tr>
            <?php
				$totsno		=	30;
				$pgBrk		=	30;
				if ($noofBatches > 30)
				{
					$totsno 	= $noofBatches;
				}				
				$sno		=	1;
				$tqty		=	0;
				$tmasterqty = 	0;
				$polymerName = '';
				for($p=0;$p<$totsno;$p++){
					$sql_totwtinfo		=   "select sum(planqty) as sumqty  from tbl_invoice_mixplan_items where batid = '". $particulars[$p]['batid']."' and ramid in ( select ramid from tbl_compound_rm where cpdid = '". $particulars[$p]['cpdid']."' and is_final_chemical = 0)";
					$totwtInfo			=	@getMySQLData($sql_totwtinfo);
					$totwtdata			=	$totwtInfo['data'];				
					$tqty				=	$tqty + $totwtdata[0]['sumqty'];
					$tmasterqty 		=   $tmasterqty + $particulars[$p]['masterbatchwgt'];
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="9" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
							</td>
						</tr>
						<tr>
							<td colspan="9" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="9" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;" >
							<th width="5%">
								No
							</th>
							<th width="15%">
								Polymer
							</th>
							<th width="20%">
								Comp. Ref.
							</th>
							<th width="8%">
								Planned For
							</th>				
							<th width="10%">
								Batch No
							</th>
							<th width="10%">
								Adv. Dump Temp.<sup>&deg;C</sup>
							</th>				
							<th width="10%">
								Dump Temp.<sup>&deg;C</sup>
							</th>
							<th width="10%">
								Exp. Batch Wt.<sup>Kgs</sup>
							</th>				
							<th>
								Act. Batch Wt.<sup>Kgs</sup>
							</th>										
						</tr>									
					<?php	
						}
					?>					
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center" >
                           <?php 
							if ( strcasecmp($polymerName,$particulars[$p]['polyabbr']) == 0)	
							{						
								print '&nbsp;';
							}
							else
							{
								$polymerName = $particulars[$p]['polyabbr'];
								print $polymerName;
							}
							?>						
                            
                        </td>
                        <td align="left">
							<?php print ($particulars[$p]['cpdname'])?$particulars[$p]['cpdname']:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($particulars[$p]['customer'])?$particulars[$p]['customer']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
							<?php print ($particulars[$p]['batid'])?((strpos($particulars[$p]['batid'],'_')!== false)?substr(strrchr($particulars[$p]['batid'], "_"),1):$particulars[$p]['batid']):'&nbsp;';?>
                        </td>
                        <td align="right">
							<?php print ($particulars[$p]['kneadtempmax'])?$particulars[$p]['kneadtempmax']:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($particulars[$p]['dumptemp'] != '' && $particulars[$p]['dumptemp'] != '0.00') ?@number_format($particulars[$p]['dumptemp'],2):'&nbsp;'; ?>
                        </td>	
                        <td align="right">
                            <?php print ($totwtdata[0]['sumqty'])?@number_format($totwtdata[0]['sumqty'],3):'&nbsp;'; ?>
                        </td>						
                        <td align="right">
                            <?php print ($particulars[$p]['masterbatchwgt'] != '' && $particulars[$p]['masterbatchwgt'] != '0.000') ?@number_format($particulars[$p]['masterbatchwgt'],3):'&nbsp;'; ?>
                        </td>					
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="4" class="content_center content_bold" >
                	Total
                </td>
				<td class="content_center content_bold" >
                	<?php echo $noofBatches; ?>
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>						
                <td class="content_bold content_right">
                	<?php print @number_format($tqty, 3); ?>
                </td>								
               <td class="content_bold content_right">
                	<?php echo @number_format($tmasterqty,3); ?>
                </td>				
				
            </tr>			 
            <tr>
            	<td colspan="9" class="content_left content_bold" >
                	Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
                </td>
            </tr>	
			<tr>
            	<td colspan="2" class="content_left content_bold">
                	Prepared:
                </td>
				<td colspan="2" class="content_left content_bold">
                	Weighed:
                </td>	
				<td colspan="3" class="content_left content_bold">
                	Mixed:
                </td>				
				<td colspan="2" class="content_left content_bold" >
                	Approved:
                </td>	
            </tr>	
		</table>		
<?php
	} 
?>	
	<div class="page_break" />
	<br />
<?php
	$sql_BTypeInfo			=	"select distinct battype 
									from (select if(isColorCpd = 1,'Colour Batches','Normal') as battype
											from tbl_mixing t1
												INNER JOIN tbl_compound t2 on t1.cpdId = t2.cpdId	
											where t1.is_open_stock = 0 and t1.status > 0 and t1.batFinalDate = '$batchDate') tab1
									order by battype asc";
	$bTypeInfo				=	@getMySQLData($sql_BTypeInfo);
	$bTypes					=	$bTypeInfo['data'];
	for($rowCount=0;$rowCount<count($bTypes);$rowCount++)
	{
		$bType					=	$bTypes[$rowCount]["battype"];
		$sql_shiftInfo			=	"select distinct shift as shift 
										from tbl_mixing t1 
											inner join tbl_compound t2 on t1.cpdId = t2.cpdId													
										where t1.is_open_stock = 0 and t1.status > 0 and t1.batFinalDate = '$batchDate' ".(($bType == 'Colour Batches')?" and t2.isColorCpd = 1 ":" and t2.isColorCpd != 1 ")." 
										order by shift asc";
		$shiftInfo				=	@getMySQLData($sql_shiftInfo);
		$shifts					=	$shiftInfo['data'];	
		for($numShifts = 0;$numShifts < count($shifts);$numShifts++)
		{
			$shift					=	$shifts[$numShifts]['shift'];
			$sql_batInfo			=	"select t1.batId, t2.cpdName,t1.masterBatchWgt, sum(t3.planQty) as expBatchQty, disporder,polyabbr,customer
											from tbl_mixing t1 
												inner join tbl_compound t2 on t1.cpdId = t2.cpdId
												inner join tbl_invoice_mixplan_items t3 on t1.batId=t3.batId
												inner join tbl_polymer_order t4 on t4.polyname = t2.cpdpolymer
											where t1.is_open_stock = 0 and t1.status > 0 and t1.batFinalDate = '$batchDate' and ".(($bType == 'Colour Batches')?" t2.isColorCpd = 1 ":"  t2.isColorCpd != 1 ")." and t1.shift = '$shift'									
											group by t1.batId 
										order by disporder,cpdname,batid asc";	
			//echo $sql_batInfo;
			$batInfo				=	@getMySQLData($sql_batInfo);
			$particulars			=	$batInfo['data'];
			$noofBatches			=	count($particulars);
			if($numShifts>0 )
			{	
				echo '<div class="page_break" /><br />';
			}			
?>	
	
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(16);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="5" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;"><?php print $formArray[1].(($bType == 'Colour Batches')?" For Colour Batches":"");  ?></div>
                </td>
                <td rowspan="2" colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Prod Date: <br /><?php echo $planDate; ?></div>
					<div style="font-size:12px;">Shift:<?php echo $shift; ?></div>
					<div style="font-size:12px;">From: &nbsp; &nbsp;&nbsp; &nbsp; </div>
					<div style="font-size:12px;">To: &nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
				<td colspan='5' align="center" style="font-size:14px;"><b>No of Batches: <?php echo $noofBatches; ?></b>
				</td>
			<tr>
			<tr style="font-size:8px;">
				<th width="4%">
					No
				</th>
				<th width="8%">
					Polymer
				</th>
				<th width="17%">
					Comp. Ref.
				</th>
				<th width="10%">
					Planned For.
				</th>
				<th width="14%">
					Master Wt.<sup>Kgs</sup>
				</th>
				<th width="14%">
					Batch No.
				</th>
				<th width="14%">
					Tar. Bat. Wt.<sup>Kgs</sup>
				</th>
				<th width="14%">
					Act. Bat. Wt.<sup>Kgs</sup>
				</th>
				<th>
					Sign
				</th>				
			 </tr>
            <?php
				$totsno		=	30;
				$pgBrk		=	30;
				if ($noofBatches > 30)
				{
					$totsno = $noofBatches;
				}				
				$sno		=	1;
				$tMasterqty	=	0;
				$tTargetqty = 	0;
				$tFinalqty	=	0;
				$polymerName = '';
				for($p=0;$p<$totsno;$p++){
					$tMasterqty  	= 	$tMasterqty + $particulars[$p]['masterBatchWgt'] ;
					$tTargetqty 	= 	$tTargetqty + $particulars[$p]['expBatchQty'];	
					$sql_finalinfo	=   "select batRecvWgt  from tbl_mixing_recv where batid = '". $particulars[$p]['batId'] ."'";  								
					$finalInfo		=	@getMySQLData($sql_finalinfo);
					$finaldata		=	$finalInfo['data'];								
					$tFinalqty		= 	$tFinalqty + $finaldata[0]['batRecvWgt'];					
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="9" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
							</td>
						</tr>
						<tr>
							<td colspan="9" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="9" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;">
							<th width="4%">
								No
							</th>
							<th width="8%">
								Polymer
							</th>
							<th width="17%">
								Comp. Ref.
							</th>
							<th width="10%">
								Planned For
							</th>
							<th width="14%">
								Master Wt.<sup>Kgs</sup>
							</th>
							<th width="14%">
								Batch No.
							</th>
							<th width="14%">
								Tar. Bat. Wt.<sup>Kgs</sup>
							</th>
							<th width="14%">
								Act. Bat. Wt.<sup>Kgs</sup>
							</th>
							<th>
								Sign
							</th>				
						 </tr>							
					<?php	
						}
					?>	
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center">
							<?php
							if ( strcasecmp($polymerName,$particulars[$p]['polyabbr']) == 0)	
							{						
								print '&nbsp;';
							}
							else
							{
								$polymerName = $particulars[$p]['polyabbr'];
								print $polymerName;
							}
							?>								
                            
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['cpdName'])?$particulars[$p]['cpdName']:'&nbsp;';?>
                        </td>
                        <td align="center">
							<?php print ($particulars[$p]['customer'])?$particulars[$p]['customer']:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($particulars[$p]['masterBatchWgt'])?@number_format($particulars[$p]['masterBatchWgt'],3):'&nbsp;'; ?>
                        </td>
                       <td align="center">
							<?php print ($particulars[$p]['batId'])?((strpos($particulars[$p]['batId'],'_')!== false)?substr(strrchr($particulars[$p]['batId'], "_"),1):$particulars[$p]['batId']):'&nbsp;';?>
                        </td>								
                        <td align="right">
                            <?php print ($particulars[$p]['expBatchQty'])?@number_format($particulars[$p]['expBatchQty'],3):'&nbsp;'; ?>
                        </td>
                        <td align="right">
                            <?php print ($finaldata[0]['batRecvWgt'])?@number_format($finaldata[0]['batRecvWgt'],3):'&nbsp;'; ?>
                        </td>
                         <td align="center">
                            &nbsp;
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="3" class="content_center content_bold" >
                	Total
                </td>
				<td class="content_center content_bold" >
                	<?php echo $noofBatches; ?>
                </td>				
                <td class="content_bold content_right">
                	<?php print @number_format($tMasterqty, 3); ?>
                </td>
				<td class="content_center content_bold" >
                	<?php echo $noofBatches; ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tTargetqty, 3); ?>
                </td>
				<td class="content_bold content_right">
                	<?php print @number_format($tFinalqty, 3); ?>
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>						
             </tr>
            <tr>
            	<td colspan="9" class="content_left content_bold" >
                	Remarks/Observations/Complaints: <BR></BR><BR></BR>
                </td>
             </tr>	
			<tr>
            	<td colspan="3" class="content_left content_bold" >
                	Prepared:
                </td>
				<td colspan="2" class="content_left content_bold" >
                	Weighed:
                </td>	
				<td colspan="2" class="content_left content_bold" >
                	Mixed:
                </td>				
				<td colspan="2" class="content_left content_bold" >
                	Approved:
                </td>				

             </tr>			 
        </table>
<?php
		}		
		if($rowCount<count($bTypes)-1)
		{
?>
		<div class="page_break" />
		<br />
<?php 
		}
	} 
?>
	<div class="page_break" />
	<br />
<?php

	$sql_custRMInfo			=	"select distinct customer as customer from tbl_mixing  where status > 0 and  batdate='$batchDate' and is_open_stock = 0 order by customer asc";
	$cusRMInfo				=	@getMySQLData($sql_custRMInfo);
	$rmCustomers			=	$cusRMInfo['data']; 
	
	for($rowCount=0;$rowCount<count($rmCustomers);$rowCount++)
	{
		$rmCustomer			=	$rmCustomers[$rowCount]["customer"];
		$sql_RAMInfo		=	"select ramid,ramname, planqty, ramclass, sum(ifnull(avlQty,0)) as bookqty from 
									(SELECT ramid,ramname, sum(planqty) as planqty, ramclass from 
										(SELECT trm.ramid,concat(ramname,'-',ramgrade) as ramname, planqty , ramclass 
											FROM (SELECT tbc.cpdid,tbc.ramid 
													FROM tbl_mixing tbm 
														INNER JOIN tbl_compound_rm tbc ON tbm.cpdid = tbc.cpdid and tbc.is_final_chemical = 1 
													WHERE tbm.is_mill_batch = 0 AND tbm.status > 0 AND tbm.is_open_stock = 0  AND tbm.batfinaldate ='$batchDate'  group by cpdid, ramid) tbl1 
												INNER JOIN (SELECT tbm.cpdid,tmp.ramid, planqty 
																FROM tbl_mixing tbm 
																	INNER JOIN tbl_invoice_mixplan_items tmp  on tbm.batid = tmp.batid
																WHERE tbm.is_mill_batch =0 AND tbm.status >0 AND tbm.batfinaldate ='$batchDate' and tbm.customer = '$rmCustomer') tbl2 on tbl1.cpdid = tbl2.cpdid and tbl1.ramid = tbl2.ramid  
												INNER JOIN tbl_rawmaterial trm on tbl1.ramid = trm.ramid							 
										UNION ALL
										SELECT trm.ramid,concat(ramname,'-',ramgrade) as ramname, planqty , ramclass 
											FROM (SELECT tbm.cpdid,tmp.ramid, planqty 
													FROM tbl_mixing tbm 
														INNER JOIN tbl_invoice_mixplan_items tmp on tbm.batid = tmp.batid
													WHERE tbm.is_mill_batch =1 AND tbm.status >0 AND tbm.batfinaldate ='$batchDate' and tbm.customer = '$rmCustomer') tbl1 
												INNER JOIN tbl_rawmaterial trm on tbl1.ramid = trm.ramid) as K2
										GROUP BY ramid 
										UNION ALL 
										SELECT trm.ramid,concat(ramname,'-',ramgrade) as ramname, sum(planqty) as planqty , ramclass 
											FROM (SELECT tbc.cpdid,tbc.ramid 
													FROM tbl_mixing tbm 
														INNER JOIN tbl_compound_rm tbc ON tbm.cpdid = tbc.cpdid and tbc.is_final_chemical = 0 
													WHERE tbm.is_mill_batch = 0 AND tbm.status > 0 AND tbm.is_open_stock = 0  AND tbm.batdate ='$batchDate' group by cpdid, ramid) tbl1
												INNER JOIN (SELECT tbm.cpdid,tmp.ramid, planqty 
																FROM tbl_mixing tbm
																	INNER JOIN tbl_invoice_mixplan_items tmp on tbm.batid = tmp.batid
																WHERE tbm.is_mill_batch =0 AND tbm.status >0 AND tbm.batdate ='$batchDate' and tbm.customer = '$rmCustomer') tbl2 on tbl1.cpdid = tbl2.cpdid and tbl1.ramid = tbl2.ramid
												INNER JOIN tbl_rawmaterial trm on tbl1.ramid = trm.ramid 
									GROUP BY ramid ) ftable
									LEFT JOIN tbl_invoice_grn tig on tig.invRamId = ftable.ramid and avlQty > 0
								GROUP BY ftable.ramid	
								ORDER BY ramclass";
	//echo $sql_RAMInfo;
	$ramInfo			=	@getMySQLData($sql_RAMInfo);
	$ramParticulars		=	$ramInfo['data'];
	$noofRAMs			=   count($ramParticulars);
	$mixIssIDArr		=	@getMySQLData("SELECT mixIssuId FROM tbl_mixing_issue where mixIssuDate = '".$batchDate."'");
	$mixIssuId  		= 	$mixIssIDArr['data'][0]['mixIssuId'];

	$sql_issRams		= 	@getMySQLData("select trm.ramid, concat(ramname,'-',ramgrade) as ramname, ramclass  
											from tbl_mixing_issue_rm trm
											inner join tbl_rawmaterial tr on trm.ramid = tr.ramid
									where mixIssuId = '$mixIssuId'");
	$issRamsParticulars	=	$sql_issRams['data'];
	for($ir=0;$ir<count($issRamsParticulars);$ir++)
	{	
		if (in_array_recursive($issRamsParticulars[$ir]['ramid'], $ramParticulars) == false)
		{
			$ramParticulars[$noofRAMs]['ramid']		=	$issRamsParticulars[$ir]['ramid'];
			$ramParticulars[$noofRAMs]['ramname']	=	$issRamsParticulars[$ir]['ramname'];
			$ramParticulars[$noofRAMs]['planqty']	=	0;			
			$ramParticulars[$noofRAMs]['ramclass']	=	$issRamsParticulars[$ir]['ramclass'];
			$noofRAMs++;
		}	
	}
?>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(50);
				print $formArray[0]; 
			?>
		</p>
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
             	<td colspan="1" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:20px;"><?php print $formArray[1] ." For ". $rmCustomer ?></div>
                </td>
                <td colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:14px;">MIS No: <BR><?php echo $mixIssuId; ?></BR></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
					<div style="font-size:14px;">Date: <br><?php echo $planDate; ?></br></div>
                </td>
            </tr>
			<tr style="font-size:8px;">
            	<th width="10%">
                	No
                </th>
            	<th width="30%">
                	Item
                </th>
             	<th width="15%">
                	Advised Qty<sup>Kgs</sup>
                </th>
            	<th width="15%">
                	Actual Qty<sup>Kgs</sup>
                </th>
            	<th width="15%">
                	GRN Number
                </th>
            	<th width="15%">
                	Book Qty
                </th>	 
             </tr>
            <?php
				$totRows			=	24;
				$ramPgBrk			=	24;
				if ($noofRAMs > $totRows)
				{
					$totRows 		= $noofRAMs;
				}
				$totArray			=	array(0,0,0,0,0);
				$sumArray			=	array('Total', 'Polymer', 'Filler', 'Plasticiser', 'Other Chemicals');
				$issTotArray		=	array(0,0,0,0,0);				
				for($p=0;$p<$totRows;$p++){
					$ramwgt			= 	$ramParticulars[$p]['planqty'];
					$ramName		=	$ramParticulars[$p]['ramname'];
					$totArray[0]	=	$totArray[0] + $ramwgt;
					$ramBookQty		=	$ramParticulars[$p]['bookqty'];
					$sqlMixIssue	=	@getMySQLData("select sum(mixIssuQty) as mixIssuQty, group_concat( distinct grnId SEPARATOR ',') as grnId  from tbl_mixing_issue_rm where mixIssuId = '$mixIssuId' and ramId = '".$ramParticulars[$p]['ramid']."' group by ramId", "arr");
					$ramIssWgt		=	$sqlMixIssue['data'][0]['mixIssuQty'];
					$issTotArray[0]	=	$issTotArray[0] + $ramIssWgt;
					if($ramParticulars[$p]['ramclass'] < 4)
					{
						$totArray[$ramParticulars[$p]['ramclass']] 		= 	$totArray[$ramParticulars[$p]['ramclass']] + $ramwgt;
						$issTotArray[$ramParticulars[$p]['ramclass']] 	= 	$issTotArray[$ramParticulars[$p]['ramclass']] + $ramIssWgt;
					}
					else
					{
						$totArray[4] 	= 	$totArray[4] + $ramwgt;
						$issTotArray[4] = 	$issTotArray[4] + $ramIssWgt;
					}
					if($p % $ramPgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="6" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR />
							</td>
						</tr>
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
							<th width="30%">
								Item
							</th>
							<th width="15%">
								Advised Qty<sup>Kgs</sup>
							</th>
							<th width="15%">
								Actual Qty<sup>Kgs</sup>
							</th>
							<th width="15%">
								GRN Number
							</th>
							<th width="15%">
								Book Qty
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
							<?php print ($ramName)?$ramName:'&nbsp;';?>
                        </td>						
                        <td align="right">
                            <?php print ($ramwgt >= 0 && $ramName)?@number_format($ramwgt,3):'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print ($ramIssWgt)?@number_format($ramIssWgt,3):'&nbsp;';?>
                        </td>						
                        <td align="center">
                            <?php print ($sqlMixIssue['data'][0]['grnId'])?$sqlMixIssue['data'][0]['grnId']:'&nbsp;';?>
                        </td>
                        <td align="right">
                            <?php print ($ramBookQty >= 0 && $ramBookQty)?@number_format($ramBookQty,3):'&nbsp;';?>
                        </td>						
                    </tr>
                    <?php
				}
			?>	
           <tr>
            	<td colspan="2" class="content_center content_bold" >
                	<?php print ($sumArray[0]); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totArray[0], 3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($issTotArray[0], 3); ?>
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>			
             </tr>			
			<tr>
            	<td colspan="6" class="content_center content_bold" >
                	Total Issue Summary
                </td>
             </tr>
			<?php 
			for($q=1;$q<5;$q++){?>
			<tr>
				<td align="center" height="19px">
                    <?php print $q; ?>
                </td>			
            	<td class="content_left content_bold" >
                	<?php print ($sumArray[$q]); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totArray[$q], 3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($issTotArray[$q], 3); ?>
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>			
             </tr> <?php
				}
			?>	
            <tr>
            	<td colspan="2" class="content_center content_bold" >
                	<?php print $sumArray[0]; ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totArray[0], 3); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($issTotArray[0], 3); ?>
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>
				<td class="content_bold content_center">
                	&nbsp;
                </td>			
             </tr>
            <tr>
            	<td colspan="2" class="content_left content_bold" >
                	Remarks: <BR></BR><BR></BR> E &amp; O.E
                </td>			
				<td colspan="2" class="content_left content_bold" >
                	Issued:
                </td>				
				<td colspan="2" class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
<?php
		if($rowCount<count($rmCustomers)-1)
		{
?>
		<div class="page_break" />
		<br />
<?php 
		}
	}
?>	
    </body>
</html>