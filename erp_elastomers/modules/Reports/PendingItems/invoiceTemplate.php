<?php
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	if($invoice_id != "" && $invoice_id != null && $invoice_id != 'pendingkeys') 
	{
		$sql			=	"update tbl_moulding_receive tmr
												inner join tbl_moulding_plan tmp on tmp.planid = tmr.planRef
												SET tmr.status = 6, tmp.status = 3 
											WHERE tmr.status > 0 and tmr.status < 6 and tmp.planDate < '$invoice_id'";
		$output			=	@getMySQLData($sql);
		echo $sql. "<br/>".$output['status']."<br/>".$output['errTxt'];				
		$sql			=	"update tbl_deflash_issue tdi
												inner join tbl_moulding_plan tmp on tmp.planid = SUBSTRING_INDEX(tdi.defiss, '-', 1)													
												set tdi.status = 2
											WHERE tdi.status = 1 and tmp.planDate  < '$invoice_id'";
		$output			=	@getMySQLData($sql);
		echo $sql. "<br/>".$output['status']."<br/>".$output['errTxt'];
		$sql			=	"update tbl_deflash_reciept tdr
												inner join tbl_moulding_plan tmp on tmp.planid = SUBSTRING_INDEX(tdr.issref, '-', 1)													
												set tdr.status = 2
											WHERE tdr.status = 1 and tmp.planDate  < '$invoice_id'" ;
		$output			=	@getMySQLData($sql);
		echo $sql. "<br/>".$output['status']."<br/>".$output['errTxt'];			

		$sql			=	"update tbl_rework trw
												inner join tbl_moulding_plan tmp on tmp.planid = SUBSTRING_INDEX(trw.planid, '-', 1)													
												set trw.status = 4
											WHERE trw.status > 0 and trw.status < 4 and tmp.planDate  < '$invoice_id'" ;
		$output			=	@getMySQLData($sql);	
		echo $sql. "<br/>".$output['status']."<br/>".$output['errTxt'];		
		$sql			=	"update tbl_mould_store tms
												inner join tbl_moulding_plan tmp on tmp.planid = SUBSTRING_INDEX(tms.planref, '-', 1)													
												set tms.avlQty = 0
											WHERE tms.avlQty > 0 and tmp.planDate  < '$invoice_id'" ;
		$output			=	@getMySQLData($sql);
		echo $sql. "<br/>".$output['status']."<br/>".$output['errTxt'];	
	}	
	
	if($_REQUEST["type"] == "RUNJOB") 
	{
		global $cmpd_grp_email;
		$lastMonth	=	date("F Y", strtotime(date('Y-m')." -1 month"));
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		// send email
		$aEmail = new AsyncCreatePDFAndEmail("Component/PendingItems","pendingkeys", $cmpd_grp_email,"","Pending Moulding and Trimming Receipts for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Pending Keys Report for :".$lastMonth);									
		$aEmail->start();
		exit();						
	}	

	if($invoice_id == "pendingkeys")
	{	
		$preMonth		=	date("F Y",mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
		
		$sql			=	"SELECT CONCAT( SUBSTRING_INDEX( tmr.modRecRef,  '_', 1 ) ,'-', SUBSTRING_INDEX( tmr.modRecRef,  '-' , -1 ) ) AS planId, DATE_FORMAT(tmp.invdate,'%d-%m-%y') as issdate,DATE_FORMAT(tmi.issueDate,'%d-%m-%y') as RecDate,tmp.cmpdName, tmp.operator, tmr.plannedLifts, (tmp.no_of_active_cavities * tmr.plannedLifts) as plannedqty
							FROM tbl_moulding_receive tmr
								inner join tbl_invoice_mould_plan tmp on tmp.planid = tmr.planRef
								left join tbl_moulding_plan tm on tm.planid = tmr.planRef
								left join (select * from (select mdIssRef,issueDate from tbl_moulding_issue group by  mdIssRef)t1) tmi on tm.mdIssRef=tmi.mdIssRef
							WHERE tmr.STATUS = 2 and date_format(tmp.invdate, '%M %Y') = '$preMonth' 
							order by tmp.operator,tmp.invdate,tmp.cmpdName";
		$keyInfo		=	@getMySQLData($sql);
		$particulars	=	$keyInfo['data'];
		$noOfKeys		=	count($particulars);
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo "Pending Keys for : ".$preMonth ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="6" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;">Pending Moulding Receipt</div>
                </td>
                <td rowspan="2" colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Date: <br /><?php echo date('d-m-Y'); ?></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
				<td colspan='6' align="center" style="font-size:14px;"><b>For the Month of : <?php echo $preMonth; ?></b>
				</td>
			<tr>
			<tr style="font-size:8px;" >
				<th width="3%">
					No
				</th>
				<th width="18%">
					Operator
				</th>				
				<th width="8%">
					Key No
				</th>
				<th width="12%">
					Plan Date
				</th>
				<th width="12%">
					Pending From
				</th>				
				<th width="15%">
					Part Number
				</th>
				<th width="8%">
					Iss. Lifts
				</th>	
				<th width="8%">
					Iss. Qty<sup>Nos</sup>
				</th>
				<th width="8%">
					Pend. Lifts
				</th>				
				<th>
					Pend. Qty<sup>Nos</sup>
				</th>				
			</tr>
            <?php
				$pgBrk		=	40;
				$sno		=	1;
				$operName	=	"";
				$tgPlnLifts	=	0;
				$tgPlnQty	=	0;
				$totPlnLifts=	0;
				$totPlnQty	=	0;
				
				for($p=0;$p<$noOfKeys;$p++){
					$plnLifts		=	$particulars[$p]['plannedLifts'];
					$plnQty			=	$particulars[$p]['plannedqty'];	
					$totPlnLifts	+=	$plnLifts;
					$totPlnQty		+=	$plnQty;
					if ( strcasecmp($operName,$particulars[$p]['operator']) == 0)	
					{						
						$printVal =  '&nbsp;';
						$tgPlnLifts	+=	$plnLifts;
						$tgPlnQty	+=	$plnQty;
					}
					else
					{
						if($tgPlnQty > 0)
						{
							echo '<tr height="20px">
										<td colspan="6" align="center">Total</td>
										<td class="content_bold content_right">
											'.(($tgPlnLifts > 0)?@number_format($tgPlnLifts):'&nbsp;').'
										</td>
										<td class="content_bold content_right">
											'.@number_format($tgPlnQty).'
										</td>
										<td class="content_bold content_right">
											'.(($tgPlnLifts > 0)?@number_format($tgPlnLifts):'&nbsp;').'
										</td>								
									   <td class="content_bold content_right">
											'.(($tgPenQty > 0)?@number_format($tgPenQty):@number_format($tgPlnQty)).'
										</td>
									</tr>';
						}
						$tgPlnLifts	=	$plnLifts;
						$tgPlnQty	=	$plnQty;
						$operName 	= 	$particulars[$p]['operator'];
						$printVal	=	$operName;						
					}					
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="10" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
							</td>
						</tr>
						<tr>
							<td colspan="10" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br /><br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="10" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;" >
							<th width="3%">
								No
							</th>
							<th width="18%">
								Operator
							</th>				
							<th width="8%">
								Key No
							</th>
							<th width="12%">
								Plan Date
							</th>
							<th width="12%">
								Pending From
							</th>				
							<th width="15%">
								Part Number
							</th>
							<th width="8%">
								Iss. Lifts
							</th>	
							<th width="8%">
								Iss. Qty<sup>Nos</sup>
							</th>
							<th width="8%">
								Pend. Lifts
							</th>				
							<th>
								Pend. Qty<sup>Nos</sup>
							</th>			
						</tr>									
					<?php	
						}
					?>					
	                <tr>
                        <td align="center" >
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center" >
                           <?php  echo $printVal ;?>                            
                        </td>
                        <td align="left">
							<?php print ($particulars[$p]['planId'])?$particulars[$p]['planId']:'&nbsp;';?>
                        </td>						
                        <td align="left">
                            <?php print ($particulars[$p]['issdate'])?$particulars[$p]['issdate']:'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['RecDate'])?$particulars[$p]['RecDate']:'&nbsp;' ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['cmpdName'])?$particulars[$p]['cmpdName']:'&nbsp;';?>
                        </td>
						<td align="right">
                            <?php print $plnLifts;?>
                        </td>
                        <td align="right">
							<?php print $plnQty;?>
                        </td>
						<td align="right">
                            <?php print $plnLifts;?>
                        </td>
                        <td align="right">
							<?php print $plnQty;?>
                        </td>					
                    </tr>
                    <?php
				}
				echo '<tr height="20px">
							<td colspan="6" align="center"> Total</td>
							<td class="content_bold content_right">
								'.@number_format($tgPlnLifts).'
							</td>
							<td class="content_bold content_right">
								'.@number_format($tgPlnQty).'
							</td>
							<td class="content_bold content_right">
								'.@number_format($tgPlnLifts).'
							</td>								
						   <td class="content_bold content_right">
								'.@number_format($tgPlnQty).'
							</td>
						</tr>';				
			?>	
            <tr height="30px">
            	<td colspan="6" class="content_center content_bold" >
                	Grand Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totPlnLifts); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totPlnQty); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totPlnLifts); ?>
                </td>								
               <td class="content_bold content_right">
                	<?php print @number_format($totPlnQty); ?>
                </td>				
            </tr>			 
            <tr>
            	<td colspan="10" class="content_left content_bold" >
                	Remarks: <BR /><BR /><BR /><BR />
                </td>
            </tr>	
			<tr>
            	<td colspan="5" class="content_left content_bold">
                	Prepared:
                </td>
				<td colspan="5" class="content_left content_bold" >
                	Approved:
                </td>	
            </tr>	
		</table>
		<div class="page_break" />
		<br /><br />		
		<?php 
		$sql			=	"SELECT distinct concat(SUBSTRING_INDEX(tmr.modRecRef,'_',1),TRIM(SUBSTR(tmr.modRecRef, LOCATE('-', tmr.modRecRef)))) as planId, DATE_FORMAT(tmp.invdate,'%d-%m-%y') as issdate,DATE_FORMAT(tdi.issdate ,'%d-%m-%y') as RecDate,tmp.cmpdName, tmp.operator, tdi.issqty as plannedqty, (tdi.issqty - ifnull((select sum(currrec) from tbl_deflash_reciept where issref = tmr.modRecRef),0)) as penqty 
							FROM tbl_moulding_receive tmr
								inner join tbl_invoice_mould_plan tmp on tmp.planid = tmr.planRef
								inner join tbl_deflash_issue tdi on tdi.defiss = tmr.modRecRef
							WHERE tmr.STATUS = 4 and date_format(tdi.issdate, '%M %Y') = '$preMonth' 
							order by tmp.operator,tmp.invdate,tmp.cmpdName";
							
		$keyInfo		=	@getMySQLData($sql);
		$particulars	=	$keyInfo['data'];
		$noOfKeys		=	count($particulars);
?>
		<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" height="45px">
                	<div style="font-size:16px;">Pending Triming Receipt Keys</div>
                </td>
                <td rowspan="2" colspan="2" width="70px" class="content_left content_bold uppercase" >
					<div style="font-size:12px;">Date: <br /><?php echo date('d-m-Y'); ?></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
                </td>
            </tr>
			<tr>
				<td colspan='4' align="center" style="font-size:14px;"><b>For the Month of : <?php echo $preMonth; ?></b>
				</td>
			<tr>
			<tr style="font-size:8px;" >
				<th width="5%">
					No
				</th>
				<th width="20%">
					Operator
				</th>				
				<th width="10%">
					Key No
				</th>
				<th width="15%">
					Plan Date
				</th>
				<th width="15%">
					Pending From
				</th>				
				<th width="15%">
					Part Number
				</th>
				<th width="10%">
					Iss. Qty<sup>Nos</sup>
				</th>
				<th>
					Pend. Qty<sup>Nos</sup>
				</th>				
			</tr>
            <?php
				$pgBrk		=	40;
				$sno		=	1;
				$operName	=	"";
				$plnQty		=	0;
				$penQty		=	0;
				$tgPlnQty	=	0;
				$tgPenQty	=	0;
				$totPlnQty	=	0;
				$totPenQty	=	0;
				
				for($p=0;$p<$noOfKeys;$p++){
					$plnQty			=	$particulars[$p]['plannedqty'];	
					$penQty			=	$particulars[$p]['penqty'];
					$totPlnQty		+=	$plnQty;
					$totPenQty		+=	$penQty;
			
					if ( strcasecmp($operName,$particulars[$p]['operator']) == 0)	
					{						
						$printVal =  '&nbsp;';
						$tgPlnQty	+=	$plnQty;
						$tgPenQty	+=	$penQty;						
					}
					else
					{
						if($tgPlnQty > 0)
						{
							echo '<tr height="20px">
										<td colspan="6" align="center">Total</td>
										<td class="content_bold content_right">
											'.@number_format($tgPlnQty).'
										</td>
									   <td class="content_bold content_right">
											'.@number_format($tgPenQty).'
										</td>
									</tr>';
						}
						$tgPlnQty	=	$plnQty;
						$tgPenQty	=	$penQty;
						$operName 	= 	$particulars[$p]['operator'];
						$printVal	=	$operName;						
					}					
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="10" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
							</td>
						</tr>
						<tr>
							<td colspan="10" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<BR /><BR />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="10" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px;" >
							<th width="5%">
								No
							</th>
							<th width="20%">
								Operator
							</th>				
							<th width="10%">
								Key No
							</th>
							<th width="15%">
								Plan Date
							</th>
							<th width="15%">
								Pending From
							</th>				
							<th width="15%">
								Part Number
							</th>
							<th width="10%">
								Iss. Qty<sup>Nos</sup>
							</th>
							<th>
								Pend. Qty<sup>Nos</sup>
							</th>			
						</tr>									
					<?php	
						}
					?>					
	                <tr>
                        <td align="center" >
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center" >
                           <?php  echo $printVal ;?>                            
                        </td>
                        <td align="left">
							<?php print ($particulars[$p]['planId'])?$particulars[$p]['planId']:'&nbsp;';?>
                        </td>						
                        <td align="left">
                            <?php print ($particulars[$p]['issdate'])?$particulars[$p]['issdate']:'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['RecDate'])?$particulars[$p]['RecDate']:'&nbsp;' ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['cmpdName'])?$particulars[$p]['cmpdName']:'&nbsp;';?>
                        </td>
                        <td align="right">
							<?php print $plnQty;?>
                        </td>
                        <td align="right">
							<?php print $penQty;?>
                        </td>					
                    </tr>
                    <?php
				}
				echo '<tr height="20px">
							<td colspan="6" align="center"> Total</td>
							<td class="content_bold content_right">
								'.@number_format($tgPlnQty).'
							</td>
							<td class="content_bold content_right">
								'.@number_format($tgPenQty).'
							</td>
						</tr>';				
			?>	
            <tr height="30px">
            	<td colspan="6" class="content_center content_bold" >
                	Grand Total
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($totPlnQty); ?>
                </td>
				<td class="content_bold content_right">
                	<?php print @number_format($totPenQty); ?>
                </td>				
            </tr>			 
            <tr>
            	<td colspan="10" class="content_left content_bold" >
                	Remarks: <BR /><BR /><BR /><BR />
                </td>
            </tr>	
			<tr>
            	<td colspan="5" class="content_left content_bold">
                	Prepared:
                </td>
				<td colspan="5" class="content_left content_bold" >
                	Approved:
                </td>	
            </tr>	
		</table>		
	</body>
	</html>
<?php } ?>
	
