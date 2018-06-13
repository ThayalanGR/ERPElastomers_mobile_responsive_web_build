<?php

	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$mouldqual			=	@getMySQLData("select * from tbl_moulding_quality where qualityref='$invoice_id'");
	$particulars		=	$mouldqual['data'];
	$planRef			=	$particulars[0]['planref'];
	
	$cmpdiddata			=	@getMySQLData("select cmpdid from tbl_deflash_issue where defiss='$planRef'");
	$cmpdid				=	$cmpdiddata['data'][0]['cmpdid'];
	
	$componentDetData	=	@getMySQLData("select * from tbl_component where status>0 and cmpdId='$cmpdid'");
	$componentDetails	=	$componentDetData['data'][0];
	
	$componentDimData	=	@getMySQLData("select paramName, uom_short_name,paramTestMethod,cmpdDimSpec,cmpdDimLLimit,cmpdDimULimit from tbl_component_dim_param t1 
													left outer join tbl_param t3 on t3.sno = t1.cmpdDim
													left outer join tbl_uom t4 on t4.sno = t3.paramUOM 
													where t1.cmpdId='$cmpdid' and t3.status > 0");
	$componentDim		=	$componentDimData['data'];
	
	
	$planDimValData		=	@getMySQLData("select * from tbl_component_dim where planId='$planRef'");
	$planDimVal			=	$planDimValData['data'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $planRef; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td  align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="60px" />
                </td>
                <td colspan="8" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?>
                </td>
                <td class="content_center content_bold uppercase" >&nbsp;
                	
                </td>
            </tr>
            <tr>
            	<td colspan="10" class="content_center content_bold uppercase">
                	COMPONENT QUALITY MEMO
                </td>
            </tr>
            <tr>
                <td colspan="4" valign="top" style="border-right:0px;padding:0px;border-bottom:0px;" >
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Part Reference
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $componentDetails['cmpdName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Description
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print $componentDetails['cmpdRefNo']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Plan Id
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print $planRef; ?>
                            </td>
                        </tr>						
                    </table>
                </td>
                <td colspan="6" style="border-right:0px;padding:0px;border-bottom:0px;">
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;width:40%;">
                                Inspection Ref
                            </td>
                            <td colspan="2" id="invoice_reference">
                                : <?php print $invoice_id; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Inspection Date
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print date("d-M-Y",strtotime($particulars[0]['qualitydate'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="content_bold" style="border-right:0px;">
                                Dim. Check Date
                            </td>
                            <td colspan="2" id="invoice date">
                                : <?php print ($planDimVal[0]['dimDate'])?date("d-M-Y",strtotime($planDimVal[0]['dimDate'])):""; ?>
                            </td>
                        </tr>						
                    </table>
                </td>
            </tr>
            <tr>
            	<th width="10%" class="content_bold" >
                	Dim
                </th>
            	<th width="10%">
                	<?php ($componentDim[0]['paramName'])?(print $componentDim[0]['paramName']):(print " ");?>
                </th>
            	<th width="10%">
                	<?php ($componentDim[1]['paramName'])?(print $componentDim[1]['paramName']):(print " ");?>
                </th>
            	<th width="10%">
                	<?php ($componentDim[2]['paramName'])?(print $componentDim[2]['paramName']):(print " ");?>
                </th>
            	<th width="10%">
                	<?php ($componentDim[3]['paramName'])?(print $componentDim[3]['paramName']):(print " ");?>
                </th>
            	<th width="10%">
                	<?php ($componentDim[4]['paramName'])?(print $componentDim[4]['paramName']):(print " ");?>
                </th>
                <th width="10%">
                	<?php ($componentDim[5]['paramName'])?(print $componentDim[5]['paramName']):(print " ");?>
                </th>
                <th width="10%" >
                	<?php ($componentDim[6]['paramName'])?(print $componentDim[6]['paramName']):(print " ");?>
                </th>
                <th width="10%" >
                	<?php ($componentDim[7]['paramName'])?(print $componentDim[7]['paramName']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[8]['paramName'])?(print $componentDim[8]['paramName']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold" >
                	UOM
                </th>
            	<th>
                	<?php ($componentDim[0]['uom_short_name'])?(print $componentDim[0]['uom_short_name']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[1]['uom_short_name'])?(print $componentDim[1]['uom_short_name']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[2]['uom_short_name'])?(print $componentDim[2]['uom_short_name']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[3]['uom_short_name'])?(print $componentDim[3]['uom_short_name']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[4]['uom_short_name'])?(print $componentDim[4]['uom_short_name']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[5]['uom_short_name'])?(print $componentDim[5]['uom_short_name']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[6]['uom_short_name'])?(print $componentDim[6]['uom_short_name']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[7]['uom_short_name'])?(print $componentDim[7]['uom_short_name']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[8]['uom_short_name'])?(print $componentDim[8]['uom_short_name']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold">
                	Spec
                </th>
            	<th>
                	<?php ($componentDim[0]['cmpdDimSpec'])?(print $componentDim[0]['cmpdDimSpec']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[1]['cmpdDimSpec'])?(print $componentDim[1]['cmpdDimSpec']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[2]['cmpdDimSpec'])?(print $componentDim[2]['cmpdDimSpec']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[3]['cmpdDimSpec'])?(print $componentDim[3]['cmpdDimSpec']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[4]['cmpdDimSpec'])?(print $componentDim[4]['cmpdDimSpec']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[5]['cmpdDimSpec'])?(print $componentDim[5]['cmpdDimSpec']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[6]['cmpdDimSpec'])?(print $componentDim[6]['cmpdDimSpec']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[7]['cmpdDimSpec'])?(print $componentDim[7]['cmpdDimSpec']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[8]['cmpdDimSpec'])?(print $componentDim[8]['cmpdDimSpec']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold">
                	Min
                </th>
            	<th>
                	<?php ($componentDim[0]['cmpdDimLLimit'])?(print $componentDim[0]['cmpdDimLLimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[1]['cmpdDimLLimit'])?(print $componentDim[1]['cmpdDimLLimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[2]['cmpdDimLLimit'])?(print $componentDim[2]['cmpdDimLLimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[3]['cmpdDimLLimit'])?(print $componentDim[3]['cmpdDimLLimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[4]['cmpdDimLLimit'])?(print $componentDim[4]['cmpdDimLLimit']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[5]['cmpdDimLLimit'])?(print $componentDim[5]['cmpdDimLLimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[6]['cmpdDimLLimit'])?(print $componentDim[6]['cmpdDimLLimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[7]['cmpdDimLLimit'])?(print $componentDim[7]['cmpdDimLLimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[8]['cmpdDimLLimit'])?(print $componentDim[8]['cmpdDimLLimit']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold" >
                	Max
                </th>
            	<th>
                	<?php ($componentDim[0]['cmpdDimULimit'])?(print $componentDim[0]['cmpdDimULimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[1]['cmpdDimULimit'])?(print $componentDim[1]['cmpdDimULimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[2]['cmpdDimULimit'])?(print $componentDim[2]['cmpdDimULimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[3]['cmpdDimULimit'])?(print $componentDim[3]['cmpdDimULimit']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[4]['cmpdDimULimit'])?(print $componentDim[4]['cmpdDimULimit']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[5]['cmpdDimULimit'])?(print $componentDim[5]['cmpdDimULimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[6]['cmpdDimULimit'])?(print $componentDim[6]['cmpdDimULimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[7]['cmpdDimULimit'])?(print $componentDim[7]['cmpdDimULimit']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[8]['cmpdDimULimit'])?(print $componentDim[8]['cmpdDimULimit']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold" >
                	Test Method
                </th>
            	<th>
                	<?php ($componentDim[0]['paramTestMethod'])?(print $componentDim[0]['paramTestMethod']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[1]['paramTestMethod'])?(print $componentDim[1]['paramTestMethod']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[2]['paramTestMethod'])?(print $componentDim[2]['paramTestMethod']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[3]['paramTestMethod'])?(print $componentDim[3]['paramTestMethod']):(print " ");?>
                </th>
            	<th>
                	<?php ($componentDim[4]['paramTestMethod'])?(print $componentDim[4]['paramTestMethod']):(print " ");?>
                </th>
                <th>
                	<?php ($componentDim[5]['paramTestMethod'])?(print $componentDim[5]['paramTestMethod']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[6]['paramTestMethod'])?(print $componentDim[6]['paramTestMethod']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[7]['paramTestMethod'])?(print $componentDim[7]['paramTestMethod']):(print " ");?>
                </th>
                <th >
                	<?php ($componentDim[8]['paramTestMethod'])?(print $componentDim[8]['paramTestMethod']):(print " ");?>
                </th>
            </tr>
            <tr>
            	<th class="content_bold" >
                	No
                </th>
            	<th class="content_center" colspan="9" >
                	Observation
                </th>
            </tr>
            <?php
				$totsno		=	20;
				$sno		=	1;
				$tqty		=	0;
				for($p=0;$p<$totsno;$p++){
					$tqty	=	$tqty + $particulars[$p]['quantity'];
					?>
                    <tr height="10px">
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
                        <td >&nbsp;<?php print $planDimVal[0]['sample'.($p+1)]; ?></td>
                        <td >&nbsp;<?php print $planDimVal[1]['sample'.($p+1)]; ?></td>
                        <td >&nbsp;<?php print $planDimVal[2]['sample'.($p+1)]; ?></td>
                        <td >&nbsp;<?php print $planDimVal[3]['sample'.($p+1)]; ?></td>
                        <td >&nbsp;<?php print $planDimVal[4]['sample'.($p+1)]; ?></td>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                        <td >&nbsp;</td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="10" class="content_center content_bold" >
                	Rejection Details
                </td>
            </tr>
            <tr>
            	<td class="content_bold"   style="border-bottom:0px;" >Rej Type</td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[0]['rejcode'])?(print $particulars[0]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[1]['rejcode'])?(print $particulars[1]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[2]['rejcode'])?(print $particulars[2]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[3]['rejcode'])?(print $particulars[3]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[4]['rejcode'])?(print $particulars[4]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[5]['rejcode'])?(print $particulars[5]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[6]['rejcode'])?(print $particulars[6]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[7]['rejcode'])?(print $particulars[7]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[8]['rejcode'])?(print $particulars[8]['rejcode']):( print " ");  ?></td>
            </tr>
            <tr>
            	<td class="content_bold" >Rej Qty</td>
                <td class="content_center" ><?php ($particulars[0]['rejval'])?(print $particulars[0]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[1]['rejval'])?(print $particulars[1]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[2]['rejval'])?(print $particulars[2]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[3]['rejval'])?(print $particulars[3]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[4]['rejval'])?(print $particulars[4]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[5]['rejval'])?(print $particulars[5]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[6]['rejval'])?(print $particulars[6]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[7]['rejval'])?(print $particulars[7]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[8]['rejval'])?(print $particulars[8]['rejval']):( print " ");  ?></td>
            </tr>
            <tr>
            	<td class="content_bold" style="border-bottom:0px;" >Rej Type</td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[9]['rejcode'])?(print $particulars[9]['rejcode']):( print " ");  ?></td>				
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[10]['rejcode'])?(print $particulars[10]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[11]['rejcode'])?(print $particulars[11]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[12]['rejcode'])?(print $particulars[12]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[13]['rejcode'])?(print $particulars[13]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[14]['rejcode'])?(print $particulars[14]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[15]['rejcode'])?(print $particulars[15]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[16]['rejcode'])?(print $particulars[16]['rejcode']):( print " ");  ?></td>
                <td class="content_center" style="border-bottom:0px;" ><?php ($particulars[17]['rejcode'])?(print $particulars[17]['rejcode']):( print " ");  ?></td>
            </tr>
            <tr>
            	<td class="content_bold" >Rej Qty</td>
                <td class="content_center" ><?php ($particulars[9]['rejval'])?(print $particulars[9]['rejval']):( print " ");  ?></td>				
                <td class="content_center" ><?php ($particulars[10]['rejval'])?(print $particulars[10]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[11]['rejval'])?(print $particulars[11]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[12]['rejval'])?(print $particulars[12]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[13]['rejval'])?(print $particulars[13]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[14]['rejval'])?(print $particulars[14]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[15]['rejval'])?(print $particulars[15]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[16]['rejval'])?(print $particulars[16]['rejval']):( print " ");  ?></td>
                <td class="content_center" ><?php ($particulars[17]['rejval'])?(print $particulars[17]['rejval']):( print " ");  ?></td>
            </tr>
            <tr>
            	<td colspan="3" >
                	<?php  
						$rejectionsum	=	0;
						for( $i=0; $i<18; $i++){
							if(is_numeric($particulars[$i]['rejval'])){
								$rejectionsum	+=	$particulars[$i]['rejval'];
							}
						}
						$approvedqty	=	$particulars[0]['appqty']; 						
						$receivedqty	=	0;
						$receivedqty	=	$approvedqty + $rejectionsum;
					?>
                	Received Qty : <?php  print $receivedqty; ?>
                </td>
                <td colspan="4" >
                	Rejected Qty : <?php print $rejectionsum; ?>
                </td>
                <td colspan="3" >
                	Approved Qty : <?php print $approvedqty; ?>
                </td>
            </tr>
            <tr>
            	<td colspan="6" rowspan="2" class="content_left content_bold" style="height:80px;vertical-align:top;" >
                	Remarks : <span class="content_normal"><?php  print $cpdvalues['data'][0]['qanRemarks']; ?></span>
                </td>
            	<td colspan="4" valign="top" class="content_bold content_left" style="vertical-align:top;height:20px;" >
                	Result : <?php print "Accepted"; ?>
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