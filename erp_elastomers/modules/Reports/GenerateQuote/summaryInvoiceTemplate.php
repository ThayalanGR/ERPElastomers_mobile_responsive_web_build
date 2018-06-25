<?php
	global $HSN,$taxRate;
	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php print $invoice_id; ?></title>
			<link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		</head>
		<body>	
	
	<?php
	$invIDs				=	"";
	if (strpos($invoice_id,',') !== false) {
		$invIDs			=	explode(",", $invoice_id);
	}
	else
	{
		$invIDs			=	array($invoice_id);	
	}
	// Get Form Details.
	$formArray		=	@getFormDetails(3);
	for($invCount=0;$invCount<count($invIDs);$invCount++)
	{
		$invoice_id		=	$invIDs[$invCount];	
		$sql_bill		=	"select * ,DATE_FORMAT(quotedate, '%d-%b-%Y') as quote_date, DATE_FORMAT(tdr.entry_on, '%d-%b-%Y') as enquiry_date, DATE_FORMAT(tdr.drawing_date, '%d-%b-%Y') as drawing_date
								from tbl_invoice_quote tiq
									inner join tbl_develop_request  tdr on tiq.rfqid = tdr.drId
									inner join tbl_develop_feasibility tdf on tdr.sno = tdf.prod_ref
								where quoteref='".$invoice_id."'";
		$out_bill		=	@getMySQLData($sql_bill);		
		$data			=	$out_bill['data'][0];
		$sql_custin		=	"select *  from tbl_customer where cusId='".$data['cusId']."'";
		$out_custin		=	@getMySQLData($sql_custin);	
		$custin			=	$out_custin['data'][0];
		$cusAddr		=	$custin['cusAdd1']. "<br />".	((trim($custin['cusAdd2']) != '')?$custin['cusAdd2']. "<br />":"").$custin['cusPlace']. " - " .	$custin['cusPincode']	;
		$buyer			=	$custin['cusName']."<br>".$cusAddr.(($custin['cusContName'] != '')?"<br> Kind Attn:".$custin['cusContName']:"");
		$settings		=	@getMySQLData("select value from tbl_settings where name = 'gstn'");
		$settings		=	$settings['data'];			
		
?>
		<p align="right">Form No: <?php print $formArray[0]; ?>	</p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="2" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
                </td>
            </tr>
            <tr>
            	<td colspan="4" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr>
            	<td width="25%">
                	Quote Reference
                </td>
            	<td width="25%" class="content_bold">
                	<?php print $data['quoteref']; ?>
                </td>
            	<td width="25%">
                	Enquiry Reference
                </td>
            	<td class="content_bold">
                	<?php print $data['rfqid']; ?>
                </td>
             </tr>
            <tr>
            	<td>
                	Quote Date
                </td>
            	<td class="content_bold">
                	<?php print $data['quote_date']; ?>
                </td>
            	<td>
                	Enquiry Date
                </td>
            	<td class="content_bold">
                	<?php print $data['enquiry_date']; ?>
                </td>
             </tr>				
            <tr height="100px">
            	<td colspan="2" valign="top" >
					 <div style="padding-bottom:5px;">To:<br /></div>
					<b><?php print $buyer; ?></b><br /><br />We are pleased to quote as below.
				 </td>
				<td colspan="2" >
					<div style="padding-bottom:5px;">Average Monthly Requirement:&nbsp;&nbsp;<b><?php print ($data['ave_monthly_req']>0)?$data['ave_monthly_req']." Nos":''; ?> </b></div>
					<div style="padding-bottom:5px;">Frieght: &nbsp;<b>Yours &nbsp;<font size=4>&#9744;</font>&nbsp;&nbsp;Ours &nbsp;<font size=4>&#9746;</b></font></div>
					<div style="padding-bottom:5px;">Credit Terms:&nbsp;&nbsp;<b><?php print ($custin['cusCreditDays']>0)?$custin['cusCreditDays']." days from Invoice":""; ?> </b></div>
					<div style="padding-bottom:5px;">Quote Validity:&nbsp;&nbsp;<b>30 days</b></div>
				</td>
            </tr>
            <tr>
            	<td align="center">
                	Part Reference
                </td>
            	<td align="center">
                	Description
                </td>
            	<td align="center">
                	Material
                </td>
            	<td align="center">
                	Rate Per Unit
                </td>
             </tr>
			<tr style="font-size:16px;" height="50px">
				<th>
					<?php print ($data['part_number'])?$data['part_number']:'&nbsp;';?>
				</th>
				<th>
					<?php print ($data['part_description'])?$data['part_description']:'&nbsp;'; ?>
				</th>
				<th>
					<?php print ($data['cpd_base_polymer'])?$data['cpd_base_polymer']:'&nbsp;'; ?>
				</th>
				<th class="content_right">
					<?php print ($data['finalcost'])?@number_format($data['finalcost'], 2):'&nbsp;'; ?>
				</th>
			</tr>
            <tr>
            	<td colspan="2" align="center">
                	Drawing Details
                </td>
            	<td>
                	Our Vendor Code
                </td>
            	<td class="content_bold">
                	<?php print $custin['cusRefNo']; ?>
                </td>
             </tr>			
            <tr>
            	<td>
                	Drawing No.
                </td>
            	<td class="content_bold">
                	<?php print $data['part_number']; ?>
                </td>
            	<td>
                	GST
                </td>
            	<td class="content_bold">
                	<?php print $taxRate['cmpd']?>%
                </td>
             </tr>
            <tr>
            	<td>
                	Revision
                </td>
            	<td class="content_bold">
                	<?php print $data['drawing_revision']; ?>
                </td>
            	<td>
                	Our GST No.
                </td>
            	<td class="content_bold">
                	<?php print $settings[0]['value']; ?>
                </td>
             </tr>
            <tr>
            	<td>
                	Date
                </td>
            	<td class="content_bold">
                	<?php print ($data['drawing_date'] != '00-00-0000')?$data['drawing_date']:''; ?>
                </td>
            	<td>
                	HSN Code
                </td>
            	<td class="content_bold">
                	<?php print $HSN['cmpd']; ?>
                </td>
             </tr>
            <tr>
            	<td colspan="2" align="center">
                	Insert / Fabric
                </td>
            	<td colspan="2" rowspan="2" valign="top">
                	Metal<br/>
					&nbsp;&nbsp; <b>Supplied by You <font size=4><?php print ($data['insertopt'] == 'Supplied By Customer')?'&#9746;':'&#9744;'; ?></font> &nbsp;&nbsp;Sourced By Us <font size=4><?php print ($data['insertopt'] == 'Supplied By Us')?'&#9746;':'&#9744;'; ?></font> &nbsp;&nbsp;N.A <font size=4><?php print ($data['insertopt'] == 'Not Applicable')?'&#9746;':'&#9744;'; ?></font></b>
                </td>
            </tr>
            <tr>
            	<td>
                	Drawing No
                </td>
            	<td>
                	&nbsp;
                </td>
            </tr>
            <tr>
            	<td>
                	Revision
                </td>
            	<td>
                	&nbsp;
                </td>
            	<td colspan="2" rowspan="2" valign="top">
                	Fabric<br/>
					&nbsp;&nbsp; <b>Supplied by You <font size=4>&#9744;</font> &nbsp;&nbsp;Sourced By Us <font size=4>&#9744;</font> &nbsp;&nbsp;N.A <font size=4>&#9744;</font></b>
                </td>						
            </tr>	
            <tr>
            	<td>
                	Date
                </td>
            	<td>
                	&nbsp;
                </td>
            </tr>
            <tr>
            	<td align="center">
                	Cost Breakup
                </td>
            	<td align="center">
                	Amount (Rs)
                </td>
            	<td colspan="2" rowspan="2" valign="top">
                	Cavity Engravement<br/>
					&nbsp;&nbsp; <b>Applicable <font size=4>&#9744;</font> &nbsp;&nbsp;Not Applicable <font size=4>&#9744;</font></b>
                </td>				
            </tr>
			<tr>
            	<td>
                	Material Cost
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format($data['matlcost'],2); ?>
                </td>
            </tr>
			<tr>
            	<td>
                	Conversion Cost
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format($data['manucost'],2); ?>
                </td>
            	<td colspan="2" rowspan="2" valign="top">
					Surface Treatment<br/>
					&nbsp;&nbsp;<b> Applicable <font size=4>&#9744;</font> &nbsp;&nbsp;Not Applicable <font size=4>&#9744;</font></b>
                </td>				
            </tr>
			<tr>
            	<td>
                	Other Overheads
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format(($data['inventcost'] + $data['freightcost'] + $data['rejcost'] + $data['admincost']),2); ?>
                </td>  		
            </tr>
			<tr>
            	<td>
                	Margin @ <?php print $data['profitper']; ?> %
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format($data['profitcost'],2); ?>
                </td>
            	<td colspan="2" rowspan="<?php print ($data['amortcost'] > 0)?"3":"2"; ?>" valign="top" class="content_bold">
                	Tool Cost <?php print ($data['tooldevopt'] != 'Our Account')?'(Rs '.@number_format($data['toolcost']).' + '.$taxRate['tool'].'% GST for '.$data['toolcavs'].' Cavity Tool.)':''; ?><br/>
					&nbsp;&nbsp; Your Account <font size=4><?php print ($data['tooldevopt'] == 'Your Account')?'&#9746;':'&#9744;'; ?></font> &nbsp;&nbsp;Our Account <font size=4><?php print ($data['tooldevopt'] == 'Our Account')?'&#9746;':'&#9744;'; ?></font> &nbsp;&nbsp;Amortised <font size=4><?php print ($data['tooldevopt'] == 'Amortised')?'&#9746;':'&#9744;'; ?></font>
                </td>  				
            </tr>
			<?php if($data['amortcost'] > 0)
			{ ?>
			<tr>
            	<td>
                	Tool Amortisation Cost
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format($data['amortcost'],2); ?>
                </td>
            </tr>
			<?php } ?>		
			<tr>
            	<td>
                	Total
                </td>
            	<td align="right" class="content_bold">
                	<?php print @number_format($data['finalcost'],2); ?>
                </td>
            </tr>
            <tr>
            	<td align="center">
                	Development Schedule
                </td>
            	<td align="center" colspan="3">
                	Commitment
                </td>
            </tr>
			<tr>
            	<td>
                	Material Sample
                </td>
            	<td colspan="3" class="content_bold">
                	<?php print $data['matlsample']; ?>
                </td>
            </tr>
            <tr>
            	<td>
                	Component Sample
                </td>
            	<td colspan="3" class="content_bold">
                	<?php print $data['compsample']; ?>
                </td>
            </tr>
            <tr>
            	<td>
                	Pilot Lot
                </td>
            	<td colspan="3" class="content_bold">
                	<?php print $data['pilotlot']; ?>
                </td>
            </tr>
			<tr>
            	<td>
                	Regular Supply
                </td>
            	<td colspan="3" class="content_bold">
                	<?php print $data['regsupply']; ?>
                </td>
            </tr>	
			<tr height="75px">
				<td colspan="4" valign="top">
					Note:<br/>
					<b><pre><?php print $data['quoteremarks']; ?></pre></b>
				</td>
			</tr>		
			<tr height="60px">
				<td colspan="2" valign="top">
					Prepared By
				</td>
				<td valign="top">
					Reviewed By
				</td>
				<td  valign="top">
					Approved By
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