<?php
	global $complaints_upload_dir,$rca_upload_dir,$closeverify_upload_dir;
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$inv_comp		=	@getMySQLData("select DATE_FORMAT(complaintDate, '%d-%m-%Y') as complaintDate,description,tcomp.status,DATE_FORMAT(tcomp.entry_on, '%d-%m-%Y') as entry_on,tuent.fullName as entry_by,mode,
										nature, compCusRef, DATE_FORMAT(compCusRefDate, '%d-%m-%Y') as compCusRefDate,retQty,retDocRef,DATE_FORMAT(retDate, '%d-%m-%Y') as retDate,corrAction, DATE_FORMAT(corr_action_target, '%d-%m-%Y') as corr_action_target,
										DATE_FORMAT(corr_action_on, '%d-%m-%Y') as corr_action_on,tuca.fullName as corr_action_by,analysisMethod,prevAction, DATE_FORMAT(prev_action_on, '%d-%m-%Y') as prev_action_on,tupa.fullName as prev_action_by,closureRemarks,
										DATE_FORMAT(closure_target, '%d-%m-%Y') as closure_target,DATE_FORMAT(closure_on, '%d-%m-%Y') as closure_on,tucl.fullName as closure_by,
										sketch_file_name,rcadoc_file_name,closeverify_file_name,'/".$complaints_upload_dir."' as filepath,'/".$rca_upload_dir."' as rcafilepath,'/".$closeverify_upload_dir."' as closeverifyfilepath
										from tbl_complaint tcomp
											left join tbl_users tuca on tuca.userId = tcomp.corr_action_by
											left join tbl_users tupa on tupa.userId = tcomp.prev_action_by
											left join tbl_users tucl on tucl.userId = tcomp.closure_by
											left join tbl_users tuent on tuent.userId = tcomp.entry_by
										where complaintId = '$invoice_id'");
	$particulars	=	$inv_comp['data'][0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invoice_id; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
	<p align="right">Form No:
		<?php 
			// Get Form Details.
			$formArray		=	@getFormDetails(53);
			print $formArray[0]; 
		?>
	</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="6" align="center" style="padding:0px;border-right:0px;border-bottom:0px;" >
                    <table cellpadding="3" cellspacing="0" border="0" id="inner_print_out" style="border:0px;" >
                        <tr>
                            <td align="center" style="padding:10px;width:100px;" >
                                <img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                            </td>
                            <td class="content_bold cellpadding content_center" >
								<font size="4"><?php print $formArray[1]; ?></font>
                            </td>
                            <td width="120px">
								<br/>ID 	: <b><?php print $invoice_id; ?></b>
								<br/>Date 	: <b><?php print $particulars['complaintDate']; ?></b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
			<tr>
				<td colspan="6" class="content_bold">
					Description
				</td>
			</tr>
			<tr style="height:180px;vertical-align:top;">
				<td colspan="6">
					<?php print $particulars['description']; ?>
				</td>
			</tr>
			<tr>
				<td class="content_bold" style="border-right:0px;">Raised By</td>
				<td colspan="2">:<?php print $particulars['entry_by']; ?></td>
				<td class="content_bold" style="border-right:0px;">Raised On</td>
				<td colspan="2">:<?php print ($particulars['entry_on'] != '00-00-0000')?$particulars['entry_on']:'-'; ?></td>				
			</tr>
			<tr>
				<td colspan="6" class="content_bold" style="border-bottom:0px;">
					Corrective Action
				</td>
			</tr>
			<tr style="height:180px;vertical-align:top;">
				<td colspan="6">
					<?php print $particulars['corrAction']; ?>
				</td>
			</tr>
			<tr>
				<td class="content_bold" style="border-right:0px;">Corr. Action By</td>
				<td>:<?php print $particulars['corr_action_by']; ?></td>
				<td class="content_bold" style="border-right:0px;">Target</td>
				<td>:<?php print ($particulars['corr_action_target'] != '00-00-0000')?$particulars['corr_action_target']:'-'; ?></td>
				<td class="content_bold" style="border-right:0px;">Actual</td>
				<td>:<?php print ($particulars['corr_action_on'] != '00-00-0000')?$particulars['corr_action_on']:'&nbsp;'; ?></td>				
			</tr>
			<tr>
				<td colspan="6" class="content_bold" style="border-bottom:0px;">
					Preventive Action
				</td>
			</tr>
			<tr style="height:180px;vertical-align:top;">
				<td colspan="6">
					<?php print $particulars['prevAction']; ?>
				</td>
			</tr>
			<tr>
				<td class="content_bold" style="border-right:0px;">Analysis Method</td>
				<td>:<?php print $particulars['analysisMethod']; ?></td>
				<td class="content_bold" style="border-right:0px;">Prev. Action By</td>
				<td>:<?php print $particulars['prev_action_by']; ?></td>
				<td class="content_bold" style="border-right:0px;">On</td>
				<td>:<?php print ($particulars['prev_action_on'] != '00-00-0000')?$particulars['prev_action_on']:'&nbsp;'; ?></td>				
			</tr>
			<tr>
				<td colspan="6" class="content_bold" style="border-bottom:0px;">
					Closure
				</td>
			</tr>
			<tr style="height:180px;vertical-align:top;">
				<td colspan="6">
					<?php print $particulars['closureRemarks']; ?>
				</td>
			</tr>
			<tr>
				<td class="content_bold" style="border-right:0px;">Closed By</td>
				<td>:<?php print $particulars['closure_by']; ?></td>
				<td class="content_bold" style="border-right:0px;">Target</td>
				<td>:<?php print ($particulars['closure_target'] != '00-00-0000')?$particulars['closure_target']:'-'; ?></td>
				<td class="content_bold" style="border-right:0px;">Actual</td>
				<td>:<?php print ($particulars['closure_on'] != '00-00-0000')?$particulars['closure_on']:'&nbsp;'; ?></td>				
			</tr>
			<tr>
				<td colspan="6" class="content_bold" style="font-size:8px">
					Attachments
				</td>
			</tr>
			<tr>
				<td class="content_bold" style="border-right:0px;font-size:8px">Sketch of Issue</td>
				<td style="font-size:8px">:<?php print ($particulars['sketch_file_name'])?"<a href='".$particulars['filepath'].$particulars['sketch_file_name']."' target='_blank'>".$particulars['sketch_file_name']."</a>":"Not Available"; ?></td>
				<td class="content_bold" style="border-right:0px;font-size:8px">Root Cause Analysis Doc</td>
				<td style="font-size:8px">:<?php print ($particulars['rcadoc_file_name'])?"<a href='".$particulars['rcafilepath'].$particulars['rcadoc_file_name']."' target='_blank'>".$particulars['rcadoc_file_name']."</a>":"Not Available"; ?></td>
				<td class="content_bold" style="border-right:0px;font-size:8px">Closure Verification Doc</td>
				<td style="font-size:8px">:<?php print ($particulars['closeverify_file_name'])?"<a href='".$particulars['closeverifyfilepath'].$particulars['closeverify_file_name']."' target='_blank'>".$particulars['closeverify_file_name']."</a>":"Not Available"; ?></td>
			</tr>			
        </table>
		
    </body>
</html>