<?php
	global $grn_role;	

	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_grn			=	"select trnId,DATE_FORMAT(trnDate,'%d-%b-%Y') as trnDate, ifNull(supName,'NA as Existing Tool') as supName,supAddress1, concat(part_number,'(',part_description, ')') as partdetails,invoiceId,DATE_FORMAT(invoiceDate,'%d-%b-%Y') as invoicedate,toolSize,toolCavities,moldprocess,moldtype,moldmaterial,tt.remarks
								from tbl_trn tt
								inner join tbl_develop_request tdr on tt.rfqid=tdr.sno
								left join tbl_supplier ts on ts.supId=tt.supId
							where tt.status>0 and trnId = '$invoice_id'";
	
	$grn				=	@getMySQLData($sql_grn);
	$grnref				=	$grn['data'][0]['trnId'];
	$supAddr			=	@preg_replace("/[\n]/", "<br />", $grn['data'][0]['supAddress1']);
	$supAddr			=	trim(@preg_replace("/<br \/>/", ", ", @preg_replace("/[,]/", ", ", $supAddr)));		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $grnref; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(5);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="print_out">
			<tr>
            	<td align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
				<td width="100px" valign="top" align="left">
					<b>TRN No:</b> <br /> <div style="font-size:14px;"><b><?php print $grnref; ?></b>&nbsp;</div>
					<br /> <br /><br /><hr noshade size=1 width="100%"></hr><b>GRN Date:</b> <br /><div style="font-size:14px;"><b> <?php print $grn['data'][0]['trnDate']; ?></b>&nbsp;</div>
				</td>
			</tr>
            <tr>
            	<td colspan="6" class="content_center content_bold font_16">
                    <div style="font-size:18px;"><?php print $formArray[1]; ?></div>
                </td>
          	</tr>
			<tr height="90px">
				<td  colspan="4" valign="top">
					Recieved From:<div style="font-size:14px;"><b><?php echo $grn['data'][0]['supName'];?> <br/><?php echo $supAddr;?></b></div>					
				</td>
				<td colspan="2" align="left" valign="top">
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;">
						<tr height="30px">
							<td colspan="2" valign="top" style="border-right:0px;border-bottom:0px;" >
								Document Details
							</td>
						</tr>					
						<tr height="30px" style="font-size:14px;">
							<td style="width:40%;border-right:0px;border-bottom:0px;">
								Invoice No
							</td>
							<td class="content_bold" style="border-right:0px;border-bottom:0px;">
								: <?php  print $grn['data'][0]['invoiceId']; ?>
							</td>
						</tr>
						<tr height="30px" style="font-size:14px;">
							<td style="border-right:0px;border-bottom:0px;">
								Invoice Date
							</td>
							<td class="content_bold" style="border-right:0px;border-bottom:0px;">
								: <?php print $grn['data'][0]['invoicedate']; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="20%" class="content_center">
					Tool For					
				</td>
				<td width="20%" class="content_center">
					Tool Size					
				</td>
				<td width="10%" class="content_center">
					Cavity					
				</td>
				<td width="15%" class="content_center">
					Material					
				</td>
				<td width="15%" class="content_center">
					Moulding Process					
				</td>
				<td class="content_center content_bold">
					Moulding Type					
				</td>							
			</tr>
			<tr style="font-size:14px;" height="70px">
				<td class="content_center content_bold "  valign="center">
					<?php print $grn['data'][0]['partdetails']; ?>				
				</td>
				<td  class="content_center content_bold"  valign="center">
					<?php print $grn['data'][0]['toolSize']; ?>
				</td>
				<td  class="content_center content_bold"  valign="center">
					<?php print ($grn['data'][0]['toolCavities'] > 0)?@number_format($grn['data'][0]['toolCavities'], 0):'&mdash;'; ?>	</div>
				</td>
				<td class="content_center content_bold" valign="center">
					<?php print $grn['data'][0]['moldmaterial']; ?>
				</td>
				<td class="content_center content_bold"  valign="center">
					<?php print $grn['data'][0]['moldprocess']; ?>				
				</td>
				<td class="content_center content_bold"  valign="center">
					<?php print $grn['data'][0]['moldtype']; ?>					
				</td>							
			</tr>
            <tr height="90px">
				<td colspan="2" valign="top">
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;">
						<tr height="30px">
							<td style="border-right:0px;border-bottom:0px;" >
								Remarks:
							</td>
						</tr>					
						<tr height="30px" style="font-size:14px;">
							<td class="content_bold" style="border-right:0px;border-bottom:0px;">
								<?php print $grn['data'][0]['remarks']; ?>&nbsp;
							</td>
						</tr>
						<tr height="30px" >
							<td class="content_bold" style="border-right:0px;border-bottom:0px;" align="right">
								E & O.E.
							</td>
						</tr>
					</table>				
				</td>
				<td colspan="2" valign="top">
					Approved:
				</td>
				<td colspan="2" valign="top">
					Recieved By:
				</td>
			</tr>
 		</table>
     </body>
</html>