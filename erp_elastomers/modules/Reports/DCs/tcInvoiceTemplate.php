<?php
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	$invarray			=	split("~",$invoice_id);
	$isDc				=	true;
	if($invarray[0] == "Inv")
	{
		$isDc			=	false;
		$invoice_id		=	$invarray[1];	
	}
	global	$cpdMonthCode;	
	$loc_sql			=	@getMySQLData("select value from tbl_settings where name='mixLocCode'");
	$locCode			=	$loc_sql['data'][0]['value'];		
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php print $invoice_id.$locCode; ?></title>
			<link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		</head>
		<body>	
	
	<?php
		$sql						= "select  dcItemId as batId, dcName, dcCode as cpdId, DATE_FORMAT(batFinalDate,'%d-%b-%Y')as batDate, if(cpdQanParam ='".$cpd_std_test_refnos[0]."',cpdQanValue,0) as hardVal, if(cpdQanParam ='".$cpd_std_test_refnos[1]."',cpdQanValue,0) as spGravityVal
												from tbl_invoice_dc_items t1									
												left outer join (select * from tbl_compound_qan where (cpdQanParam = '".$cpd_std_test_refnos[0]."' or cpdQanParam = '".$cpd_std_test_refnos[1]."')) t2 on t1.dcItemId = t2.batId
												inner join tbl_mixing t3 on t3.batId = t1.dcItemId																																
											where dcId='".$invoice_id."'";
		if($isDc == false)
			$sql						= "select  invPlanRef as batId, invName as dcName, invCode as cpdId, DATE_FORMAT(batFinalDate,'%d-%b-%Y')as batDate, if(cpdQanParam ='".$cpd_std_test_refnos[0]."',cpdQanValue,0) as hardVal, if(cpdQanParam ='".$cpd_std_test_refnos[1]."',cpdQanValue,0) as spGravityVal
												from tbl_invoice_sales_items t1									
												left outer join (select * from tbl_compound_qan where (cpdQanParam = '".$cpd_std_test_refnos[0]."' or cpdQanParam = '".$cpd_std_test_refnos[1]."')) t2 on t1.invPlanRef = t2.batId
												inner join tbl_mixing t3 on t3.batId = t1.invPlanRef																																
											where invId='".$invoice_id."'";
		$sql_particulars			=	"select batId, dcName, tab1.cpdId, batDate, sum(hardVal) as hardVal, sum(spGravityVal) as spGravityVal, concat(format(tab2.cpdQanULimit,0), ' - ',format(tab2.cpdQanSpec,0),' - ',format(tab2.cpdQanLLimit,0)) as hardnessSpec, concat(tab3.cpdQanULimit, ' - ',tab3.cpdQanSpec,' - ',tab3.cpdQanLLimit) as spGravitySpec
											from (".$sql.") tab1											
											inner join tbl_compound_qan_param tab2 on tab1.cpdId = tab2.cpdId and tab2.cpdQanParamRef = '".$cpd_std_test_refnos[0]."'
											inner join tbl_compound_qan_param tab3 on tab1.cpdId = tab3.cpdId and tab3.cpdQanParamRef = '".$cpd_std_test_refnos[1]."'
											group by batId order by batId";
		//echo $sql_particulars;
		$out_particulars	=	@getMySQLData($sql_particulars);
		$particulars		=	$out_particulars['data'];
		$noofBatches		=	count($particulars);
?>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(49);
				print $formArray[0]; 
			?>
		</p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td colspan="2" rowspan="2" align="center" style="padding:3px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="1" rowspan="2" width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
                </td>
            </tr>
            <tr>
            	<td colspan="4" class="content_center content_bold">
                	<?php print $formArray[1]. (($isDc)?" for DC No: ":" for Invoice No: "). $invoice_id.$locCode; ?>
                </td>
            </tr>
            <tr>
            	<th width="3%">
                	No
                </th>
            	<th width="12%">
                	Batch ID
                </th>
				<th width="15%">
                	Comp. ID
                </th>				
            	<th width="20%">
                	Hardness Spec
                </th>
				<th width="15%">
					Actual Value
                </th>
            	<th width="20%">
                	Specific Gavity Spec
                </th>
            	<th width="15%">
                	Actual Value
                </th>            	
             </tr>
            <?php
				$pgBrk		=	50;
				for($p=0;$p<$noofBatches;$p++){
					if($particulars[$p]['hardVal'] == 0 || $particulars[$p]['spGravityVal'] == 0)
					{
						echo 'Hardness/Specific Gravity testing not completed for batch Id :'.$particulars[$p]['batId'].' Click to <a href="javascript:window.open('."''".",'_self').close();".'">close</a>';
						exit();
					}					
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
							<th width="12%">
								Batch ID
							</th>
							<th width="15%">
								Comp. ID
							</th>				
							<th width="20%">
								Hardness Spec
							</th>
							<th width="15%">
								Actual Value
							</th>
							<th width="20%">
								Specific Gavity Spec
							</th>
							<th width="15%">
								Actual Value
							</th>            				
						 </tr>							
					<?php	
						}
					?>	
                    <tr>
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
						<td>
							<?php 
							$partBatId = "";
							if ($particulars[$p]['batDate'] != "" && $particulars[$p]['batDate'] != "0000-00-00") {
								$mixDate			= date("d-m-Y", strtotime($particulars[$p]['batDate']));
								list($d, $m, $y) 	= explode('-', $mixDate);
								$partBatId			=	"/".$d . $cpdMonthCode[$m+0];	
								}								
								print ($particulars[$p]['batId'])?(strpos($particulars[$p]['batId'],'_')!== false)?substr(strrchr($particulars[$p]['batId'], "_"),1).$partBatId:$particulars[$p]['batId'].$partBatId :'&nbsp;';
							?>                            
                        </td>
                        <td>
                            <?php print ($particulars[$p]['dcName'])?$particulars[$p]['dcName']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['hardnessSpec'])?$particulars[$p]['hardnessSpec']:'&nbsp;'; ?>
                        </td>
                        <td class="content_right">
                            <?php print ($particulars[$p]['hardVal'])?@number_format($particulars[$p]['hardVal'], 0):'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['spGravitySpec'])?$particulars[$p]['spGravitySpec']:'&nbsp;'; ?>
                        </td>
                        <td class="content_right">
                            <?php print ($particulars[$p]['spGravityVal'])?@number_format($particulars[$p]['spGravityVal'], 3):'&nbsp;'; ?>
                        </td>						
                    </tr>
                    <?php
				}
			?>	
           <tr>
				<td colspan="7" style="padding:0px;text-align:center">
                    As this is a Computer Generated statement, No Signature is required
                </td>
			</tr>			 
         </table>
    </body>
</html>