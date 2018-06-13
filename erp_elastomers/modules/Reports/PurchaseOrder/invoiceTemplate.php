<?php
	global $cpd_grp_email, $grn_customers, $grn_role, $grn_emailadd;

	$invoice_id		=	trim((ISO_IS_REWRITE)?$_VAR['invID']:$_GET['invID']);
	$today			=	date("d-m-Y");

	if($_REQUEST["type"] == "RUNJOB" ) 
	{
		if( empty($cpd_grp_email) == false )
		{
			// close & send the result to user & then send email									
			closeConnForAsyncProcess("");
			// send email
			$aEmail = new AsyncCreatePDFAndEmail("Compound/PORegister","stocklist", $cpd_grp_email,"","Stock Report for:".$today,"Dear Sir/Madam,\n Please find the attached file for the Stock Report for :".$today);									
			$aEmail->start();
			exit();					
		}
		exit();
	}
	else if ( $invoice_id == "stocklist")
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/Compound/PurchaseOrder/?type=stocklist"</script>';
		exit();
	}		
	
	$sql_bill			=	"select * from tbl_invoice_purchase tic where tic.purId='".$invoice_id."'";
	$out_bill			=	@getMySQLData($sql_bill);
	$data				=	$out_bill['data'][0];
	
	/*print '<pre>';
	print_r($data);
	print '</pre>';*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invoice_id; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		<script>
		</script>		
    </head>

    <body>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(10);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="2" align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="1" width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
                </td>
            </tr>
            <tr>
            	<td colspan="7" class="content_center content_bold">
                	<?php print $formArray[1]; ?> 
                </td>
            </tr>
            <tr>
                <td colspan="3" valign="top" rowspan="4" >
                    <div style="padding-bottom:5px;">To:<br /></div>
                    <b style="font-size:12px;"><?php print $data['supName']."<br/>".$data['purTo']; ?><br /></b>
                </td>
				<td colspan="2"  style="border-right:0px;">
					P.O. Reference
				</td>
				<td colspan="2" class="content_bold" style="font-size:12px;">
					: <?php  print $data['purId']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					P.O. Date
				</td>
				<td colspan="2" class="content_bold">
					: <?php print date("d-M-Y", strtotime($data['purDate'])); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"  style="border-right:0px;">
					Vendor Code
				</td>
				<td colspan="2" class="content_bold" >
					: <?php print $data['supId']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"  style="border-right:0px;">
					Your Quote Ref.
				</td>
				<td colspan="2" class="content_bold" >
					: <?php print $data['yourQuote']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" rowspan="4" valign="top">
                    <div style="padding-bottom:5px;">Consignee:<br /></div>
                	<b style="font-size:12px;"><?php print $data['purShipTo']; ?></b>
                </td>				
				<td colspan="2" style="border-right:0px;">
					Freight
				</td>
				<td colspan="2" class="content_bold">
					 : As Applicable
				</td>
			</tr>
			<tr>              		
				<td colspan="2"  style="border-right:0px;">
					Payment Terms
				</td>
				<td colspan="2" class="content_bold">
					 : <?php print ($data['purPaymentTerms'])?$data['purPaymentTerms']." Days":''; ?>&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2"  style="border-right:0px;">
					Our GST No.
				</td>
				<td colspan="2" class="content_bold" style="font-size:12px;">
					: <?php print $data['purOurGSTN']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"  style="border-right:0px;">
					Our PAN
				</td>
				<td colspan="2" class="content_bold">
					: <?php print $data['purOurPAN']; ?>
				</td>
			</tr>
            <tr>
            	<td width="4%">
                	S.No
                </td>
            	<td width="16%">
                	Description
                </td>
            	<td width="25%">
                	Grade
                </td>
            	<td width="10%">
                	UOM
                </td>
            	<td width="15%">
                	Quantity
                </td>
            	<td width="15%">
                	Rate
                </td>
            	<td width="15%">
                	Value(Rs)
                </td>
            </tr>
			  <tr height="40px" style="font-size:14px;">
				<th align="center">
					1
				</th>
				<th align="center">
					<?php print ($data['description'])?$data['description']:'&nbsp;'; ?>
				</th>
				<th align="center">
					<?php print ($data['grade'])?$data['grade']:'&nbsp;'; ?>
				</th>
				<th align="center">
					<?php print ($data['uom'])?$data['uom']:'&nbsp;'; ?>
				</th>
				<th class="content_right">
					<?php print ($data['quantity'])?@number_format($data['quantity'], 3):'&nbsp;'; ?>
				</th>
				<th class="content_right" >
					<?php print ($data['rate'])?@number_format($data['rate'], 2):'&nbsp;'; ?>
				</th>
				<th class="content_right" >
					<?php print ($data['purTotal'])?@number_format($data['purTotal'], 2):'&nbsp;'; ?>
				</th>
			</tr>
            <tr>
				<td rowspan="3" colspan="5" valign="top">
					<div style="padding-bottom:3px;">
						Amount in Words:
					</div>
					<b style="font-size:14px;"><?php echo number2Word($data['purGrandTotal'])?> only</b>
				</td>	
                 <td class="content_bold" >
                	Freight
                </td>
                <td class="content_right content_bold" >
                	<?php print @number_format(($data['purFreightVal'])?$data['purFreightVal']:0, 2); ?>
                </td>
            </tr>			
            <tr>
                <td class="content_bold" >
                	Insurance
                </td>
                 <td class="content_right content_bold" >
                	<?php print @number_format(($data['purInsurance'])?$data['purInsurance']:0, 2); ?>
                </td>
            </tr>
			<tr>
                <td class="content_bold" style="font-size:14px" >
                	Grand Total
                </td>
                <td class="content_bold content_right" style="font-size:14px;">
                	<?php print @number_format(($data['purGrandTotal'])?$data['purGrandTotal']:0, 2); ?>
                </td>
            </tr>
            <tr>
            	<td colspan="7" valign="top" height="100px">
                	<div style="padding-bottom:3px;">
                		Other Technical / Quality References:
                    </div>
					<b><?php print ($data['remarks'])?$data['remarks']:'&nbsp;'; ?></b>
                </td>
            </tr>
            <tr>
            	<td colspan="7" valign="top">
                    <div style="padding-bottom:5px;"><b>Terms & Conditions</b><br /></div>
                	<div style="padding:5px 20px 20px 10px;text-align:justify">
                    	<ol style="padding-left:20px;">
                        	<li style="margin-bottom:5px;">Duties, Taxes and other government levies as applicable.</li>
                        	<li style="margin-bottom:5px;">Material to be accompanied by test certificate issued by the manufacturer.</li>
                            <li style="margin-bottom:5px;">Please specify your GST number/Our GST Number/HSN Code in the invoice</li>
                            <li style="margin-bottom:5px;">Goods shall be provisionally received and are subject to quality approval.</li>
                            <li style="margin-bottom:5px;">Kindly mention the PO reference and vendor code in the Invoice.
                            <li style="margin-bottom:5px;">Any discrepancy observed shall be intimated within 3 working days from the date of PO, else the contents shall be considered as correct.</li>
                            <li style="margin-bottom:5px;">Materials delivered beyond one week from the date of purchase order, unless specified otherwise shall not be accepted.</li>
                        </ol>
                    </div>
                </td>
            </tr>
            <tr>
            	<td colspan="4" style="border-bottom:0px;" valign="bottom">
					<span class="content_bold float_left" >E & O.E</span>
                </td>
            	<td colspan="3" style="border-bottom:0px;" align="right">
                    For <?php  echo $_SESSION['app']['comp_name'];?>
                    <br /><br /><br /><br /><br />
                    Authorised Signatory
                </td>
            </tr>
        </table>
    </body>
</html>