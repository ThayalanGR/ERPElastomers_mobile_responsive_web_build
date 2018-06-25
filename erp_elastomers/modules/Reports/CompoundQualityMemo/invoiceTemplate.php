<?php
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	if($_REQUEST["type"] == "RUNJOB") 
	{
		global $quality_grp_email;
		$lastMonth	=	date("F Y", strtotime(date('Y-m')." -1 month"));
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		// send email
		$aEmail = new AsyncCreatePDFAndEmail("Quality/CompoundApprovalRegister","summaryreport", $quality_grp_email,"","Compound Test Data Report for:".$lastMonth,"Dear Sir/Madam,\n Please find the attached file for the Compound Test Data Report for :".$lastMonth);									
		$aEmail->start();
		exit();						
	}
	else if ( $invoice_id == "summaryreport")
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_ADDR'].'/'.ISO_LOAD_MODULE.'/?type=summaryreport"</script>';
		exit();
	}		
	
	$inv_comp_qc		=	@getMySQLData("select *,SUBSTRING_INDEX(tm.batId, '_',-1) as dispBatId from tbl_compound_qan tcq 
												inner join (select *, (select uom_short_name  from tbl_uom where sno=paramUOM) as qanUOM from tbl_compound_qan_param tcqp inner join tbl_param tp on tp.sno = tcqp.cpdQanParamRef) tcqptp  on tcq.cpdId = tcqptp.cpdId and tcqptp.cpdQanParamRef = tcq.cpdQanParam  
												inner join tbl_mixing tm on tcq.batId = tm.batId
											where tcq.batId = '$invoice_id' ");
	$particulars		=	$inv_comp_qc['data'];
	$inv_comp_det		=	@getMySQLData("select cpdName,cpdPolymer from tbl_compound where cpdId  = '".$inv_comp_qc['data'][0]['cpdId']."' ");
	$batid				=	$inv_comp_qc['data'][0]['dispBatId'];
	/*print '<pre>';
	print_r($inv_comp_qc);
	print_r($inv_comp_qc_param);
	print '</pre>';*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $batid; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(34);
				print $formArray[0]; 
			?>
		</p>		
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="10" align="center" style="padding:0px;" >
                    <table cellpadding="3" cellspacing="0" border="0" id="print_out" style="border:0px;" >
                        <tr>
                            <td align="center" style="padding:10px;width:100px;" >
                                <img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                            </td>
                            <td class="content_bold cellpadding content_center" >
                                <div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
                                <?php echo @getCompanyDetails('address'); ?><br/>
                                Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                                website : <?php echo @getCompanyDetails('website'); ?>
                            </td>
                            <td width="100px" class="content_center content_bold uppercase" style="border-right:0px;" >&nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td colspan="11" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr>
                <td colspan="5" valign="top" style="border-right:0px;padding:0px;border-bottom:0px;" >
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Compound Reference
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $inv_comp_det['data'][0]['cpdName'];; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Base Polymer
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print $inv_comp_det['data'][0]['cpdPolymer'];; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="5" style="border-right:0px;padding:0px;border-bottom:0px;">
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Batch Reference
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $batid; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Test Date
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print date("d-M-Y", strtotime($inv_comp_qc['data'][0]['entry_on'])); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<th width="3%">
                	No
                </th>
            	<th width="15%">
                	Parameter
                </th>
            	<th width="15%">
                	Standard Ref.
                </th>
            	<th width="5%">
                	UoM
                </th>
            	<th width="15%">
                	Test Meth.
                </th>
            	<th width="8%">
                	Spec.
                </th>
            	<th width="8%">
                	Lower Limit
                </th>
                <th width="8%">
                	Upper Limit
                </th>
                <th width="8%" >
                	Observation
                </th>
                <th>
                	Status
                </th>
            </tr>
            <?php
				$totsno		=	15;
				$sno		=	1;
				if(count($particulars) > $totsno)
					$totsno =	count($particulars);
				for($p=0;$p<$totsno;$p++){
					?>
                    <tr height="10px">
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['paramName'])?$particulars[$p]['paramName']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['paramStdRef'])?$particulars[$p]['paramStdRef']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['qanUOM'])?$particulars[$p]['qanUOM']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['paramTestMethod'])?$particulars[$p]['paramTestMethod']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['cpdQanSpec'])?$particulars[$p]['cpdQanSpec']:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                            <?php 
									$cpdQanLLimit	=	$particulars[$p]['cpdQanLLimit'];
									$cpdQanULimit	=	$particulars[$p]['cpdQanULimit'];
									$cpdQanValue	=	$particulars[$p]['cpdQanValue'];
									print ($cpdQanLLimit)?$cpdQanLLimit:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                        	<?php print ($cpdQanULimit)?$cpdQanULimit:'&nbsp;'; ?>
                        </td>    
                        <td align="center" >
                        	<?php print ($cpdQanValue)?$cpdQanValue:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                        	<?php print ($cpdQanValue != "" && $cpdQanLLimit != "" && $cpdQanULimit != "" )?(($cpdQanValue <= $cpdQanULimit) && ($cpdQanValue >= $cpdQanLLimit) )?'OK':'NOT OK':'&nbsp;'; ?>
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="6" rowspan="2" class="content_left content_bold" style="height:80px;vertical-align:top;" >
                	Quality Inspector Remarks : <span class="content_normal">&nbsp;</span>
                </td>
            	<td colspan="4" valign="top" class="content_bold content_left" style="vertical-align:top;height:20px;" >
                	Result : 
                </td>
            </tr>
            <tr>
                <td colspan="4" valign="top"  class="content_bold content_left" style="vertical-align:top;"  >
                	For <?php  echo $_SESSION['app']['comp_name'];?> : <span style="float:right;vertical-align:bottom;padding-top:65px;" > Authorised Signatory </span>
                </td>
            </tr>
        </table>
    </body>
</html>