<?php

	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_getram				=	" select *, DATE_FORMAT(sanDate,'%d-%b-%Y')as invDate, DATE_FORMAT(grnDate,'%d-%b-%Y')as grnDate from tbl_purchase_san where sanId='$invoice_id' ";
		
	$getram	     			=	@getMySQLData($sql_getram);

	$ram_name				=	$getram['data'][0]['ramName'];
	$ram_grade				=	$getram['data'][0]['ramGrade'];
	$sanid					=	$getram['data'][0]['sanId'];
	$sandate				=	$getram['data'][0]['invDate'];
	$remark					=	$getram['data'][0]['description'];
	$invref					=	$getram['data'][0]['invoiceId'];
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
		
			$repeat_no	=	2;
			for($r=0; $r<$repeat_no; $r++){
			
		?>
    	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="print_out">
        	<tr>
            	<td>
                    <table cellpadding="3" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td colspan="3" class="content_center content_bold uppercase font_16" height="20px" style="border-bottom:0px;border-right:0px;">
                                RAW MATERIAL STOCK ADJUSTMENT NOTE
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
                            	<b>Raw Material Name</b>
                            </td>
                            <td width="25%">
								: <?php print $ram_name; ?>&nbsp;
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
                            	<b>Raw Material Grade</b>
                            </td>
                            <td>
								: <?php print $ram_grade; ?>&nbsp;
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
                            	GRN Reference
                            </th>
                        	<th width="18%" align="left">
                            	GRN Date
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
                        </tr>
						<?php
                            $totsno			=	10;
                            $sno			=	1;
                            $totbookqty		=	0;
                            $totphyqty		=	0;
							$totexshrt		=	0;
							$particulars	=	$getram['data'];
							
							for($p=0;$p<$totsno;$p++){
						?>
                        <tr>
                            <td align="center">
                            	<?php echo $p+1; ?>
                            </td>
                            <td align="left">
                                <?php print ($particulars[$p]['grnId'])?$particulars[$p]['grnId']:'&nbsp;'; ?>
                            </td>
                            <td align="left">
                                <?php print ($particulars[$p]['grnDate'])?$particulars[$p]['grnDate']:'&nbsp;'; ?>
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