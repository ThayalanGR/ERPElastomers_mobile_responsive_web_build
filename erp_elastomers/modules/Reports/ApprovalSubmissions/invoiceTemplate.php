<?php
	global $appsub_docs, $appsub_docs_cpd, $appsubdocs_upload_dir;	
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$uploadPath 	= 	$_SESSION['app']['iso_dir'].$appsubdocs_upload_dir.$invoice_id;
	$filesArr		=	glob("$uploadPath/*.*");
	$subStatus		=	array();
	$approvalDocs	=	array_merge($appsub_docs, $appsub_docs_cpd);
	foreach($approvalDocs as $value) {		
		array_push($subStatus,"No");
	}	
	foreach($filesArr as $value) {
		$subStatus[array_search(substr(substr(strrchr($value,'/'),1),0,strrpos(substr(strrchr($value,'/'),1),'.')),$approvalDocs)] = "Yes";
	}
	$sql_particulars	=	"select subId,subDate,tas.remarks, cusName, part_number, part_description from tbl_approval_submit tas											
								inner join tbl_trn tt on tt.trnid = tas.toolRef
								inner join tbl_develop_request tdr on  tdr.sno = tt.rfqid 
								inner join tbl_customer tc on tc.cusId = tdr.cusId
							where subId='".$invoice_id."'";
	$out_particulars	=	@getMySQLData($sql_particulars);
	$particulars		=	$out_particulars['data'][0];
	$partNum			=	$particulars['part_number'];
	$cusName			=	$particulars['cusName'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $partNum; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(7);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="8" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="80px" />
                </td>
                <td class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td class="content_left" >
					<div style="font-size:12px;">Submission ID: <br /><b><?php echo $invoice_id; ?></b></div>
					<div style="font-size:10px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
					<div style="font-size:12px;">Date: <br /><b><?php echo date_format(new DateTime($particulars['subDate']),'d-m-Y'); ?></b></div>
                </td>				
             </tr>
            <tr>
            	<td colspan="3" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr>
				<td style="border-right:0px;">Part Number</td>
				<td>:  <b><?php echo $partNum; ?></b></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="border-right:0px;">Description</td>
				<td>: <b><?php echo $particulars['part_description']; ?></b></td>
				<td>&nbsp;</td>
			</tr>
            <tr style="font-size:10px">
            	<th width="20%">No</th>
            	<th width="60%">PAP requirements</th>            	
				<th>Submitted?</th>
            </tr>
            <?php
				$totsno		=	count($approvalDocs);
				$sno		=	1;
				for($p=0;$p<$totsno;$p++){ ?>				
					<tr style="font-size:12px">
                        <td align="center"><?php print ($p+1); ?></td>
                        <td align="left"><?php print($subStatus[$p] == "Yes")?"<a href='/".$appsubdocs_upload_dir.$invoice_id."/".$approvalDocs[$p].".pdf' target='_blank'>". $approvalDocs[$p]."</a>":$approvalDocs[$p]?></td>
						<td align="center"><?php print $subStatus[$p]; ?></td>
                    </tr>
                    <?php
				}
			?>
            <tr>
            	<td colspan="3" class="content_left content_bold" style="vertical-align:top;" >
                	DECLARATION:<br/>
					I hereby affirm that the above documents being submitted are in line with the PAP requirements of <?php echo $cusName;?>, The sample parts being submitted meet all the specified requirements as per the drawing / specifications provided by <?php echo $cusName;?>. 
                </td>
            </tr>			
            <tr>
                <td colspan="3" >
					<table width='100%' cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="60%" class="content_bold content_left" style="vertical-align:top;border-bottom:0px;">
								Remarks: <?php echo $particulars['remarks']; ?>
							</td>
							<td valign="top"  class="content_bold content_left" style="vertical-align:top;border-right:0px;border-bottom:0px;">
								For <?php  echo $_SESSION['app']['comp_name'];?> : <span style="float:right;vertical-align:bottom;padding-top:50px;" > Authorised Signatory </span>							
							</td>
						</tr>
					</table>                	
                </td>
            </tr>			
        </table>
    </body>
</html>