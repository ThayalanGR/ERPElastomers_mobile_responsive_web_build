<?php

	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']); 
	
	$sql_qc_item	=	@getMySQLData("select invRamName , invGrade, grnId, grnDate  from tbl_invoice_grn where grnId='$invoice_id'","arr");
	$qan_item		=	$sql_qc_item['data']['0'];
	
	$sql_qc_param	=	@getMySQLData("select rq.ramQanValue,rq.qanDate ,rq.ramQanRemarks ,rq.ramStatus, p.paramName ,p.paramStdRef ,(select uom_short_name from tbl_uom where sno=p.paramUOM) as UOM ,p.paramTestMethod ,rqp.ramQanSpec ,rqp.ramQanULimit ,rqp.ramQanLLimit ,rqp.ramSamPlan 
										from tbl_rawmaterial_qan rq 
											left outer join tbl_rawmaterial_qan_param rqp on  rqp.ramId = rq.ramId and rqp.ramQanParamRef = rq.ramQanParamRef 
											left outer join tbl_param p on p.sno = rq.ramQanParamRef
											where rq.grnid='$invoice_id'","arr");
	$qan_param		=	$sql_qc_param['data'];
	
	for($par=0; $par<count($qan_param); $par++){
				
		if($qan_param[$par]['ramQanLLimit'] > 0 && $qan_param[$par]['ramQanULimit'] > 0){
			$qan_param[$par]['status'] = ($qan_param[$par]['ramQanValue'] >= $qan_param[$par]['ramQanLLimit'] && $qan_param[$par]['ramQanValue'] <= $qan_param[$par]['ramQanULimit'])
												?"OK"
												:"OUT OF SPEC.";
		}
		else if($qan_param[$par]['ramQanLLimit'] > 0){
			$qan_param[$par]['status'] = ($qan_param[$par]['ramQanValue'] >= $qan_param[$par]['ramQanLLimit'])
												?"OK"
												:"OUT OF SPEC.";
		}
		else if($qan_param[$par]['ramQanULimit'] > 0){
			$qan_param[$par]['status'] = ($qan_param[$par]['ramQanValue'] <= $qan_param[$par]['ramQanULimit'])
												?"OK"
												:"OUT OF SPEC.";
		}
		else{
			$qan_param[$par]['status'] = "&ndash;";
		}
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $qan_item['grnid']; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(12);
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
            	<td colspan="10" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr>
                <td colspan="5" valign="top" style="border-right:0px;padding:0px;border-bottom:0px;" >
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Raw Material
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $qan_item['invRamName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Grade
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print $qan_item['invGrade']; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td colspan="5" style="border-right:0px;padding:0px;border-bottom:0px;">
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                GRN Reference
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $qan_item['grnId']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                GRN Date
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php  print date("d-M-Y",strtotime($qan_item['grnDate'])); ?>
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
				for($p=0;$p<$totsno;$p++){
					?>
                    <tr height="10px">
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center">
                            <?php print ($qan_param[$p]['paramName'])?$qan_param[$p]['paramName']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qan_param[$p]['paramStdRef'])?$qan_param[$p]['paramStdRef']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qan_param[$p]['UOM'])?$qan_param[$p]['UOM']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qan_param[$p]['paramTestMethod'])?$qan_param[$p]['paramTestMethod']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qan_param[$p]['ramQanSpec'])?$qan_param[$p]['ramQanSpec']:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                            <?php print ($qan_param[$p]['ramQanLLimit'])?$qan_param[$p]['ramQanLLimit']:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                        	<?php print ($qan_param[$p]['ramQanULimit'])?$qan_param[$p]['ramQanULimit']:'&nbsp;'; ?>
                        </td>    
                        <td align="center" >
                        	<?php print ($qan_param[$p]['ramQanValue'] != "")?$qan_param[$p]['ramQanValue']:'&nbsp;'; ?>
                        </td>
                        <td align="center" >
                        	<?php print ($qan_param[$p]['status'])?$qan_param[$p]['status']:'&nbsp;'; ?>
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="6" rowspan="2" class="content_left content_bold" style="vertical-align:top;" >
                	Quality Inspector Remarks : <span class="content_normal"><?php  print $qan_param[0]['ramQanRemarks']; ?></span>
                </td>
            	<td colspan="4" valign="top" class="content_bold content_left" style="vertical-align:top;height:20px;" >
                	Result : <?php ($qan_param[0]['ramStatus'] == 1)?( print "Approved"):(print "Rejected"); ?>
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