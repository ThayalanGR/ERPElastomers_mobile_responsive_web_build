<?php

	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_getcnt				=	"SELECT tms.*, DATE_FORMAT(sanDate, '%d-%b-%Y') as sanDate, cmpdName, cmpdRefNo	FROM tbl_moulding_san tms
								inner join tbl_component tc on tc.cmpdid = tms.cmpdid
								where tms.sanid = '$invoice_id' ";	 
		
	$getcnt	     			=	@getMySQLData($sql_getcnt);

	$cnt_name				=	$getcnt['data'][0]['cmpdName']; 
	$part_desc				=	$getcnt['data'][0]['cmpdRefNo']; 
	$sanid					=	$getcnt['data'][0]['sanId']; 
	$sandate				=	$getcnt['data'][0]['sanDate'];
	$remark					=	$getcnt['data'][0]['description'];
	$invref					=	$getcnt['data'][0]['sanId'];
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
			$formArray	=	@getFormDetails(46);
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
                            	<b>Part Number</b>
                            </td>
                            <td width="25%">
								: <?php print $cnt_name; ?>&nbsp;
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
                            	<b>Part Description</b>
                            </td>
                            <td>
								: <?php print $part_desc; ?>&nbsp;
                            </td>
                            <td style='border-right:0px;'>
                            	<b>SAN Date</b>
                            </td>
                            <td>
								: <?php print $sandate; ?>&nbsp;
							</td>
                        </tr>
                        <!--<tr>
                        	<td style='border-right:0px;'>
                            	<b>Received with thanks from</b>
                            </td>
                            <td colspan="3">
                            	: <?php print $sup_details_arr[0]; ?>&nbsp;
                            </td>
                        </tr>-->
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
                        	<th width="30%" align="left">
                            	Key Reference
                            </th>
                        	<th width="20%" align="right">
                            	Book Qty
                            </th>
                        	<th width="20%" align="right">
                            	Physical Qty
                            </th>
                        	<th align="right">
                            	Excess/(Shortage)
                            </th>
                        </tr>
						<?php
							//$alphabet		=	"ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                            $totsno			=	10;
                            $sno			=	1;
                            $totbookqty		=	0;
                            $totphyqty		=	0;
							$totexshrt		=	0;
							$particulars	=	$getcnt['data'];
							
							for($p=0;$p<$totsno;$p++){
						?>
                        <tr>
                            <td align="center">
                            	<?php echo $p+1; ?>
                            </td>
                            <td align="left">
                                <?php print ($particulars[$p]['planId'])?$particulars[$p]['planId']:'&nbsp;'; ?>
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
                        </tr>
                        <?php
								$totbookqty	=	$totbookqty + $particulars[$p]['bookQty'];
								$totphyqty	=	$totphyqty 	+ $particulars[$p]['physicalQty'];
								$totexshrt	=	$totexshrt	+	$particulars[$p]['exc_shrt'];
							}
						?>
                        <tr>
                        	<th colspan="2">
                            	Total
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totbookqty, 0); ?>
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totphyqty, 0); ?>
                            </th>
                            <th align="right">
                            	<?php echo @number_format($totexshrt, 0); ?>
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