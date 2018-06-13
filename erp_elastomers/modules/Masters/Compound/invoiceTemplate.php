<?php
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$invIDs				=	"";
	$dispinvid			=	$invoice_id;
	if (strpos($invoice_id,',') !== false) {
		$invIDs			=	explode(",", $invoice_id);
	}
	else
	{
		$invIDs			=	array($invoice_id);	
	}
	// Get Form Details.
	$formArray		=	@getFormDetails(29);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $dispinvid ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>	
	
<?php 
	for($invCount=0;$invCount<count($invIDs);$invCount++)
	{
		$cpdid				=	$invIDs[$invCount];
		$sql_cpd			=	"select cpdName,batCountFullTest,ifnull(group_concat(concat(cpdSpecRef,'(',cpdCust,')')),'NA') as compSpec, DATE_FORMAT(entry_on, '%d-%b-%Y') as entryOn,DATE_FORMAT(update_on, '%d-%b-%Y') as updateOn, if(cpdMinStock > 0,cpdMinStock,10) as basePolyWgt 
									from tbl_compound tc
									left outer join tbl_compound_cust_spec tccs on tc.cpdId = tccs.cpdId
								where tc.cpdId='$cpdid' group by tc.cpdId";
		//echo $sql_cpd;
		$out_cpd			=	@getMySQLData($sql_cpd);
		$data				=	$out_cpd['data'][0];
		$basePolyWgt		=	$data['basePolyWgt'];	
		/*$sql_rmtot			=	"select sum( ramParts) as ramTotal  from (select ramParts from tbl_compound_rm where cpdId='$cpdid' group by item_no) tbl1 ";
		$out_rmtot			=	@getMySQLData($sql_rmtot);	
		$partsTotal			=	$out_rmtot['data'][0]['ramTotal'];
		if(!($partsTotal >= 0))
			$partsTotal		=	1000;
		$sql_polyWgt		=	"select sum(ramParts) as polyRamParts 
									from (select ramParts,ramClass from tbl_compound_rm tcr
											inner join tbl_rawmaterial tr on tcr.ramId = tr.ramId
										where tcr.cpdId='$cpdid' group by item_no) tbl1 
									where ramClass = 1 ";	*/		
		$sql_polyWgt		=	"select sum(polyRamParts) as polyRamParts from (select ramParts as polyRamParts 
									from tbl_compound_rm tcr
									inner join tbl_rawmaterial tr on tr.ramId = tcr.ramId and ramClass = 1
								where cpdId='$cpdid' group by item_no) tbl1 ";
		$out_polyWgt		=	@getMySQLData($sql_polyWgt);	
		$ramPolyWgt			=	$out_polyWgt['data'][0]['polyRamParts'];
		$sql_priPolyWgt		=	"select ramParts as polyRamParts from tbl_compound_rm where cpdId='$cpdid' order by item_no limit 1 ";
		$out_priPolyWgt		=	@getMySQLData($sql_priPolyWgt);	
		$ramPriPolyWgt		=	$out_priPolyWgt['data'][0]['polyRamParts'];		
?>

		<p align="right">Form No: <?php print $formArray[0]; ?>	</p>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" id="print_out_header">
			<tr >
				<td rowspan="2" width="12%" align="center" >
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
				</td>
				<td width="70%" class="content_bold cellpadding content_center" height="45px">
					<div style="font-size:20px;"><?php print $formArray[1]; ?> for <?php echo $data['cpdName']; ?> </div>
				</td>
				<td rowspan="2" class="content_left content_bold uppercase" valign="top" >
					<div style="font-size:12px;">Effective From: </div><div style="font-size:14px;" ><?php echo $data['entryOn'] ?></div>
					<div style="font-size:12px;" > </div>
				</td>				
			</tr>
			<tr>
				<td class="content_center content_bold" >
					Customer Specs: <font size="2"><?php echo $data['compSpec']; ?></font>
				</td>
			</tr>
		</table>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
			<tr style="font-size:8px;" >
				<th width="2%">
					No
				</th>
				<th width="20%">
					Raw Material
				</th>
				<th width="8%">
					Parts<sup>PHR</sup>
				</th>
				<th width="10%">
					Std. Batch<sup>gms</sup>
				</th>
				<th width="24%">
					Approved Grade(s)
				</th>
				<th width="6%">
					Customer
				</th>
				<th>
					Part No.
				</th>				
			</tr>
            <?php
				$sql_rmdetails		=	"select tr.ramName, group_concat(tr.ramGrade) as ramGrade, (tcr.ramParts/$ramPolyWgt) * 100 as ramPHR, (tcr.ramParts/$ramPriPolyWgt) * 100 as ramPriPHR, ramClass from tbl_compound_rm tcr
											inner join tbl_rawmaterial tr on tcr.ramId = tr.ramId 
										where cpdId='$cpdid' and is_final_chemical = 0 group by tcr.item_no order by tr.ramClass";
				$out_rmdetails		=	@getMySQLData($sql_rmdetails);	
				$particulars		=	$out_rmdetails['data'];		
				$sql_cmpddetails	=	"select left(concat(t1.cmpdName,'(',t1.cmpdRefNo,')'),30) as partNumber,upper(if(cusGroup != '',cusGroup,'Others')) as cusGroup
										from tbl_component t1											
											inner join tbl_customer_cmpd_po_rate t2 on t2.cmpdId  = t1.cmpdId  and t2.status = 1
											inner join tbl_customer t3 on t3.cusId  = t2.cusId  
										where t1.status > 0 and t1.cmpdCpdId  = '$cpdid' group by t1.cmpdName,cusGroup order by (cmpdAMR*poRate),cusGroup ";
				$out_cmpddetails	=	@getMySQLData($sql_cmpddetails);	
				$cmpd_particulars	=	$out_cmpddetails['data'];		
				$totsno				=	12;
				$sno				=	1;
				$tpartsqty			=	0;
				$tkneadqty			=	0;
				$pno				=	0;
				for($p=0;$p<$totsno;$p++){
					$partsPHR	=	$particulars[$p]['ramPHR'];
					$tpartsqty	+=	$partsPHR;
					$kneadqty	=	$basePolyWgt * $particulars[$p]['ramPriPHR'] * 10;
					$tkneadqty 	+=  $kneadqty;
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left" >
							<?php print ($particulars[$p]['ramName'])?$particulars[$p]['ramName']:'&nbsp;';?>
                        </td>
                        <td align="right">
                            <?php print ($partsPHR)?@number_format($partsPHR,2):'&nbsp;'; ?>
                        </td>
                        <td align="right">
                            <?php print ($kneadqty)?@number_format($kneadqty):'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$p]['ramGrade'])?$particulars[$p]['ramGrade']:'&nbsp;';?>
                        </td>
						<td align="left">
							<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
						</td>
						<td align="left">
							<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
						</td>						
                    </tr>					
                    <?php
					$pno++;
				}
			?>
            <tr>
            	<td colspan="2" class="content_center content_bold" height="19px" >
                	Master Batch Weight
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tpartsqty,2); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tkneadqty); ?>
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
				</td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
				</td>					
            </tr>
			<?php $pno++;?>
			<tr>
 				<td colspan="5" class="content_center content_bold" height="19px">
                	Final Chemicals
                </td>	
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
				</td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
				</td>					
			
            </tr>
            <?php	
				$pno++;
				$sql_rmdetails		=	"select tr.ramName, group_concat(tr.ramGrade) as ramGrade, (tcr.ramParts/$ramPolyWgt) * 100 as ramPHR, (tcr.ramParts/$ramPriPolyWgt) * 100 as ramPriPHR  from tbl_compound_rm tcr
											inner join tbl_rawmaterial tr on tcr.ramId = tr.ramId 
										where cpdId='$cpdid' and is_final_chemical = 1 group by tcr.item_no order by tr.ramClass";
				$out_rmdetails		=	@getMySQLData($sql_rmdetails);	
				$particulars		=	$out_rmdetails['data'];					
				$totsno		=	3;
				$sno		=	1;
				for($p=0;$p<$totsno;$p++){
					$partsPHR	=	$particulars[$p]['ramPHR'];
					$tpartsqty	+=	$partsPHR;
					$kneadqty	=	$basePolyWgt * $particulars[$p]['ramPriPHR'] * 10;
					$tkneadqty 	+=  $kneadqty;
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left" >
							<?php print ($particulars[$p]['ramName'])?$particulars[$p]['ramName']:'&nbsp;';?>
                        </td>
                        <td align="right">
                            <?php print ($partsPHR)?@number_format($partsPHR,2):'&nbsp;'; ?>
                        </td>
                        <td align="right">
                            <?php print ($kneadqty)?@number_format($kneadqty):'&nbsp;'; ?>
                        </td>
                        <td align="left" >
                            <?php print ($particulars[$p]['ramGrade'])?$particulars[$p]['ramGrade']:'&nbsp;';?>							
                        </td>
						<td align="left">
							<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
						</td>
						<td align="left">
							<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
						</td>							
                    </tr>
                    <?php
					$pno++;
				}
			?>
            <tr>
            	<td colspan="2" class="content_center content_bold" height="19px">
                	Total Batch Weight
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tpartsqty,2); ?>
                </td>
                <td class="content_bold content_right">
                	<?php print @number_format($tkneadqty); ?>
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
				</td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
				</td>			
            </tr>
			<?php $pno++; ?>
			<tr>
 				<td colspan="5" class="content_center content_bold" height="19px">
                	Physical Properties (Full Test Every : <font size="2"><?php echo $data['batCountFullTest'] ?></font> Batches)
                </td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
				</td>
				<td align="left">
					<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
				</td>		
			</tr>
			<?php $pno++; ?>
			<tr style="font-size:8px;" height="19px">
				<th>
					No
				</th>
				<th>
					Properties
				</th>
				<th>
					UOM
				</th>
				<th>
					Spec
				</th>
				<th>
					Range
				</th>
				<td align="left" style="font-size:10px;">
					<?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
				</td>
				<td align="left" style="font-size:10px;">
					<?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
				</td>					
			</tr>
            <?php
				$pno++;
				$sql_qualdetails	=	"select t2.paramName,t3.uom_short_name,cpdQanSpec,cpdQanULimit,cpdQanLLimit
										from tbl_compound_qan_param t1
											inner join tbl_param t2 on t2.sno = t1.cpdQanParamRef
											inner join tbl_uom t3 on t3.sno = t2.paramUOM 
										where t1.cpdId = '$cpdid' order by t2.paramStdRef";
				$out_qualdetails	=	@getMySQLData($sql_qualdetails);	
				$qual_particulars	=	$out_qualdetails['data'];
				$totsno		=	15;
				$sno		=	1;
				for($p=0;$p<$totsno;$p++){
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left" >
							<?php print ($qual_particulars[$p]['paramName'])?$qual_particulars[$p]['paramName']:'&nbsp;';?>
                        </td>
                        <td align="center">
                            <?php print ($qual_particulars[$p]['uom_short_name'])?$qual_particulars[$p]['uom_short_name']:'&nbsp;'; ?>
                        </td>
                        <td align="right">
                            <?php print ($qual_particulars[$p]['cpdQanSpec'])?@number_format($qual_particulars[$p]['cpdQanSpec'],2):'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qual_particulars[$p]['cpdQanSpec'])?@number_format($qual_particulars[$p]['cpdQanLLimit'],2)." - ".@number_format($qual_particulars[$p]['cpdQanULimit'],2):'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($cmpd_particulars[$pno]['cusGroup'])?$cmpd_particulars[$pno]['cusGroup']:'&nbsp;';?>
                        </td>
                        <td align="left">
                            <?php print ($cmpd_particulars[$pno]['partNumber'])?$cmpd_particulars[$pno]['partNumber']:'&nbsp;';?>
                        </td>						
                    </tr>
                    <?php
					$pno++;
				}
			?>			
			<tr>
            	<td colspan="2" class="content_left content_bold" valign="top" height="19px">
                	Prepared:
                </td>
				<td colspan="2" class="content_left content_bold" valign="top">
                	Reviewed:
                </td>	
				<td colspan="2" class="content_left content_bold" valign="top">
                	Approved:
                </td>				
				<td class="content_left content_bold" >
					updated:<?php echo $data['updateOn']; ?>
                </td>	
            </tr>				
        </table>
		<?php 
			if($invCount+1<count($invIDs))
				echo "<div class='page_break'><br /></div>";
		}
		?>
		
    </body>
</html>