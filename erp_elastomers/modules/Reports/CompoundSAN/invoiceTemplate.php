<?php

	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_getcom				=	" select *, DATE_FORMAT(sanDate,'%d-%b-%Y')as invDate, DATE_FORMAT(batDate,'%d-%b-%Y')as batDate from tbl_mixing_san tms inner join tbl_compound tc on tc.cpdId = tms.cpdId where sanId='$invoice_id' ";
		
	$getcom	     			=	@getMySQLData($sql_getcom);
	
	$com_name				=	$getcom['data'][0]['cpdName'];	 
	$sanid					=	$getcom['data'][0]['sanId']; 
	$sandate				=	$getcom['data'][0]['invDate'];
	$remark					=	$getcom['data'][0]['description'];
	$invref					=	$getcom['data'][0]['invoiceId'];
	$com_poly				=	$getcom['data'][0]['cpdPolymer'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invref; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
    	<?php
			$formArray	=	@getFormDetails(51);
			$repeat_no	=	2;
			for($r=0; $r<$repeat_no; $r++){
			
		?>
		<p align="right">Form No:<?php print $formArray[0]; ?></p>
    	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="print_out">
        	<tr>
            	<td>
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td colspan="3" class="content_center content_bold font_16" height="20px" style="border-bottom:0px;border-right:0px;">
                                <?php print $formArray[1]; ?>
                            </td>
                        </tr>
                    </table>
        		</td>
            </tr>
            <tr>
            	<td style="border-bottom:0px;border-right:0px;">
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                        <tr>
                        	<td width="25%" style='border-right:0px;'>
                            	<b>Compound Code</b>
                            </td>
                            <td width="25%">
								: <?php print $com_name; ?>&nbsp;
                            </td>
                            <td width="25%" style='border-right:0px;'>
                            	<b>SAN Reference</b>
                            </td>
                            <td width="25%">
								: <?php print $sanid; ?>&nbsp;
							</td>
                        </tr>
                        <tr>
                        	<td style='border-right:0px;'>
                            	<b>Base Polymer</b>
                            </td>
                            <td>
								: <?php print $com_poly; ?>&nbsp;
                            </td>
                            <td style='border-right:0px;'>
                            	<b>SAN Date</b>
                            </td>
                            <td>
								: <?php print $sandate; ?>&nbsp;
							</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td style="border-bottom:0px;border-right:0px;">
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                        <tr>
                        	<th width="7%">
                            	No.
                            </th>
                        	<th width="25%" align="left">
                            	Batch Reference
                            </th>
                        	<th width="18%" align="left">
                            	Batch Date
                            </th>
                        	<th width="15%" align="right">
                            	Book Qty
                            </th>
                        	<th width="15%" align="right">
                            	Physical Qty
                            </th>
                        	<th align="right">
                            	Excess/(Shortage)
                            </th>
                        	<!--<th width="15%">
                            	Received Qty
                            </th>-->
                        </tr>
						<?php
							//$alphabet		=	"ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                            $totsno			=	10;
                            $sno			=	1;
                            $totbookqty		=	0;
                            $totphyqty		=	0;
							$totexshrt		=	0;
							$particulars	=	$getcom['data'];
							
							for($p=0;$p<$totsno;$p++){
						?>
                        <tr>
                            <td align="center">
                            	<?php echo $p+1; ?>
                            </td>
                            <td align="left">
                                <?php print ($particulars[$p]['batId'])?$particulars[$p]['batId']:'&nbsp;'; ?>
                            </td>
                            <td align="left">
                                <?php print ($particulars[$p]['batDate'])?$particulars[$p]['batDate']:'&nbsp;'; ?>
                            </td>
                            <td align="right">
                                <?php print ($particulars[$p]['bookQty'])?$particulars[$p]['bookQty']:'&nbsp;'; ?>
                            </td>
                            <td align="right">
                                <?php print ($particulars[$p]['physicalQty'])?$particulars[$p]['physicalQty']:'&nbsp;'; ?>
                            </td>
                            <td align="right">
                                <?php print ($particulars[$p]['exc_shrt'])?$particulars[$p]['exc_shrt']:'&nbsp;'; ?>
                            </td>
                            <!--<td class="content_right" >
                                <?php print ($particulars[$p]['invReceivedQty'])?@number_format($particulars[$p]['invReceivedQty'], 3):'&nbsp;'; ?>
                            </td>-->
                        </tr>
                        <?php
								$totbookqty	=	$totbookqty + $particulars[$p]['bookQty'];
								$totphyqty	=	$totphyqty 	+ $particulars[$p]['physicalQty'];
								$totexshrt	=	$totexshrt	+	$particulars[$p]['exc_shrt'];
							}
						?>
                        <tr>
                        	<th colspan="3">
                            	Total
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totbookqty, 3); ?>
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totphyqty, 3); ?>
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totexshrt, 3); ?>
                            </th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td style="border-bottom:0px;border-right:0px;">
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                    	<tr>
                        	<td style="border-bottom:0px;" valign="top" height="60px">
                            	<b>Remarks:</b><br/>
                                <p style="text-indent:20px;"><?php echo $remark; ?></p>
                            </td>
                       </tr>
                    	</table>
                </td>
            </tr>
		</table>
        <?php
        	if($r < ($repeat_no-1)){
		?>
        <br />
        <?php
				}
			}
		?>
    </body>
</html>