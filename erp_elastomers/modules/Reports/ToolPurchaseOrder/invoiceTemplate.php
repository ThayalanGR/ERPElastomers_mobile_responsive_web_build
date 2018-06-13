<?php
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
	$formArray		=	@getFormDetails(4);
	for($invCount=0;$invCount<count($invIDs);$invCount++)
	{
		$invoice_id		=	$invIDs[$invCount];	
		$sql_bill		=	"select supId,purId,purDate,supQuoteRef,supQuoteDate,part_number,part_description,toolSize,toolCavities,moldMaterial,moldProcess,moldType,poValue,shrinkage,cavEngravement,expRecvDate,ttp.remarks
								from tbl_tool_purchase ttp
									inner join tbl_develop_request  tdr on rfqid = sno
								where purId='".$invoice_id."' and ttp.status != 0";
		//echo $sql_bill . "<br/>"; 
		$out_bill		=	@getMySQLData($sql_bill);		
		$data			=	$out_bill['data'][0];
		$sql_supin		=	"select *  from tbl_supplier where supId='".$data['supId']."'";
		$out_supin		=	@getMySQLData($sql_supin);	
		$supin			=	$out_supin['data'][0];
		$supAddr		=	@preg_replace("/[\n]/", "<br />", $supin['supAddress1']);
		$supAddr		=	trim(@preg_replace("/<br \/>/", ", ", @preg_replace("/[,]/", ", ", $supAddr)));
		$supplier		=	$supin['supName']."<br>".$supAddr.(($supin['supContName'] != '')?"<br> Kind Attn:".$supin['supContName']:"");
		$settings		=	@getMySQLData("select name,value from tbl_settings where name in ('gstn','pan') order by name");
		$settings		=	$settings['data'];			
		
?>
		<p align="right">Form No: <?php print $formArray[0]; ?>	</p>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="6" class="content_bold cellpadding content_center" >
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
            	<td colspan="8" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
			<tr>
                <td colspan="4" rowspan="6" valign="top" style="font-size:14px;">
                    <div style="padding-bottom:5px;">To:<br /></div>
                    <b><?php print $supplier ?></b><br />
                </td>
				<td colspan="2"  style="border-right:0px;width:40%;">
					P.O. Reference
				</td>
				<td colspan="2" class="content_bold" >
					: <?php  print $data['purId']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					P.O. Date
				</td>
				<td colspan="2"class="content_bold" >
					: <?php print date("d-M-Y", strtotime($data['purDate'])); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					Freight
				</td>
				<td colspan="2" class="content_bold" >
					 : As Applicable
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					Payment Terms
				</td>
				<td colspan="2" class="content_bold" >
					 : <?php print ($supin['supCreditDays'])?$supin['supCreditDays']." Days":'30 Days'; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					GST No.
				</td>
				<td colspan="2"  class="content_bold">
					: <?php print $settings[0]['value']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">
					PAN
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print $settings[1]['value']; ?>
				</td>
            </tr>  
            <tr>
				<td colspan="8" >
					We are pleased to release P.O. as Below (refer your Quote : <?php print $data['supQuoteRef'] ?> dated <?php print ($data['supQuoteDate'] == '0000-00-00')?'&ndash;':date("d-M-Y", strtotime($data['supQuoteDate']));?>)
				</td>				
			</tr>
            <tr>
            	<td width="10%" align="center">
                	Part Number
                </td>
            	<td width="15%" align="center">
                	Description
                </td>
            	<td width="15%" align="center">
                	Tool Size
                </td>
            	<td width="8%" align="center">
                	Cavities
                </td>
            	<td width="11%" align="center">
                	Material
                </td>
            	<td width="12%" align="center">
                	Process
                </td>
            	<td width="12%" align="center">
                	Type
                </td>
            	<td align="center">
                	Value(Rs)
                </td>
            </tr>
			  <tr height="40px" class="content_bold" style="font-size:14px;">
				<td align="center">
					<?php print ($data['part_number'])?$data['part_number']:'&nbsp;'; ?>
				</td>
				<td align="center">
					<?php print ($data['part_description'])?$data['part_description']:'&nbsp;'; ?>
				</td>
				<td align="center">
					<?php print ($data['toolSize'])?$data['toolSize']:'&nbsp;'; ?>
				</td>
				<td align="center">
					<?php print ($data['toolCavities'])?$data['toolCavities']:'&nbsp;'; ?>
				</td>
				<td align="center">
					<?php print ($data['moldMaterial'])?$data['moldMaterial']:'&nbsp;'; ?>
				</td>
				<td align="center">
					<?php print ($data['moldProcess'])?$data['moldProcess']:'&nbsp;'; ?>
				</td>
				<td align="center" >
					<?php print ($data['moldType'])?$data['moldType']:'&nbsp;'; ?>
				</td>
				<td align="right" >
					<?php print ($data['poValue'])?@number_format($data['poValue'], 2):'&nbsp;'; ?>
				</td>
			</tr>
             <tr>
				<td rowspan="4" colspan="5" valign="top">
					<div class="content_bold" style="padding-bottom:3px;">
						Amount in Words:  
					</div>
					<?php print number2Word($data['poValue']); ?> only
				</td>
            </tr>
            <tr>
                <td colspan="2">
                	Shrinkage (%)
                </td>
                <td  class="content_bold content_right" >
                	<?php print @number_format(($data['shrinkage'])?$data['shrinkage']:0, 2); ?>
                </td>
            </tr>			
            <tr>
                <td colspan="2">
                	Cavity Engravement
                </td>
                <td  class="content_bold" align="center" >
                	<?php print ($data['cavEngravement'] == 1)?'Yes':'No'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                	Exp. Completion Date
                </td>
                <td class="content_bold" align="center" >
                	<?php print ($data['expRecvDate'] != '0000-00-00')? date("d-M-Y", strtotime($data['expRecvDate'])):date("d-M-Y", strtotime($data['purDate']." + 7 DAYS")); ?>
                </td>
            </tr>			
            <tr>
            	<td colspan="8" valign="top" height="100px">
                	<div class="content_bold" style="padding-bottom:3px;">
                		Other Technical / Quality References:
                    </div>
					<?php print ($data['remarks'])?$data['remarks']:'&nbsp;'; ?>
                </td>
            </tr>
            <tr>
            	<td colspan="8" valign="top">
                    <div style="padding-bottom:5px;"><b>Terms & Conditions</b><br /></div>
                	<div style="padding:5px 20px 20px 10px;text-align:justify">
                    	<ol style="padding-left:20px;">
                        	<li style="margin-bottom:5px;">GST levies as applicable.</li>
                            <li style="margin-bottom:5px;">Tool shall be provisionally received and are subject to quality approval.</li>
                            <li style="margin-bottom:5px;">Kindly sign and return us a copy as a sign of acceptance of the PO.</li>
                            <li style="margin-bottom:5px;">Kindly mention the PO reference in all the DC / Invoice.
                            <li style="margin-bottom:5px;">Any discrepancy observed shall be intimated within 3 working days from the date of PO, else the contents shall be considered as correct.</li>
                            <li style="margin-bottom:5px;">Tool delivered beyond one week from the date of Expected Completion, unless specified otherwise shall not be accepted.</li>
                        </ol>
                    </div>
                </td>
            </tr>
            <tr>
            	<td colspan="4" style="border-bottom:0px;" valign="bottom">
					<span class="content_bold float_left" >E & O.E</span>
                </td>
            	<td colspan="4" style="border-bottom:0px;" align="right">
                    For <?php  echo $_SESSION['app']['comp_name'];?>
                    <br /><br /><br /><br /><br />
                    Authorised Signatory
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