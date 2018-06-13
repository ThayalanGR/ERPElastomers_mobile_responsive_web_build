<?php

	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_bill				=	"select * from tbl_invoice_sales where invId='".$invoice_id."'";
	$sql_items				=	"select * from tbl_invoice_sales_items where invId='".$invoice_id."'";
	$sql_particulars		=	"select * from tbl_invoice_component_pdir where invId='".$invoice_id."'";
	$out_bill				=	@getMySQLData($sql_bill);
	$out_items				=	@getMySQLData($sql_items);
	$out_particulars		=	@getMySQLData($sql_particulars);
	$invQty					=	0;
	
	for($iq=0; $iq<$out_items['count']; $iq++){
		$invQty				=	$invQty + $out_items['data'][$iq]['invQty'];
	}
	
	$data					=	$out_bill['data'][0];
	$items					=	$out_items['data'][0];
	$particulars			=	$out_particulars['data'];
	$component				=	@getMySQLData("select * from tbl_component where cmpdId='".$data['invCmpdId']."'");
	
	$data['invCmpdDate']	=	date("d-M-Y", strtotime($data['invCmpdDate']));
	$data['invDate']		=	date("d-M-Y", strtotime($data['invDate']));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invoice_id; ?> - PDIR</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="2" align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="13" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?>
                </td>
                <td colspan="4" width="100px" class="content_center content_bold uppercase" >&nbsp;
                	
                </td>
            </tr>
            <tr>
            	<td colspan="19" class="content_center content_bold uppercase">
                	PRE DELIVERY INSPECTION REPORT
                </td>
            </tr>
            <tr>
            	<td colspan="19" valign="top" style="padding:0px;border-bottom:0px;border-right:0px;">
                	<table border="0" cellspacing="0" cellpadding="3" style="width:100%;">
                    	<tr>
                        	<td width="14%" style="border-right:0px;">Invoice Reference</td>
                            <td width="18%">:  <?php echo $data['invId']; ?></td>
                        	<td width="14%" style="border-right:0px;">Drawing Reference</td>
                            <td width="18%">: <?php echo $data['invCmpdDrawRef']; ?></td>
                        	<td width="14%" style="border-right:0px;">Cust. Receipt Ref.</td>
                            <td>: &nbsp;</td>
                        </tr>
                    	<tr>
                        	<td style="border-right:0px;">Invoice Date</td>
                            <td>: <?php echo ($data['invDate'] == '01-Jan-1970')?'':$data['invDate']; ?></td>
                        	<td style="border-right:0px;">Revision</td>
                            <td>:  <?php echo $data['invCmpdRevision']; ?></td>
                        	<td style="border-right:0px;">Cust. Receipt Date</td>
                            <td>: &nbsp;</td>
                        </tr>
                    	<tr>
                        	<td style="border-right:0px;">Part Reference</td>
                            <td>: <?php echo $data['invCmpdPartRef']; ?></td>
                        	<td style="border-right:0px;">Date</td>
                            <td>:  <?php echo ($data['invCmpdDate'] == '01-Jan-1970')?'':$data['invCmpdDate']; ?></td>
                        	<td style="border-right:0px;">Result</td>
                            <td>: Acc / Con Acc /Rej / RW / Seg</td>
                        </tr>
                    	<tr>
                        	<td style="border-right:0px;">Part Description</td>
                            <td>:  <?php echo $data['invCmpdPartDesc']; ?></td>
                        	<td style="border-right:0px;">Invoice Quantity</td>
                            <td>: <?php echo $invQty; ?></td>
                        	<td style="border-right:0px;">Accepted Quantity</td>
                            <td>: &nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<th width="2%">No</th>
            	<th>Param / Meth</th>
            	<th width="8%">Specification</th>
            	<th width="5%">Obs</th>
            	<th width="5%">1</th>
            	<th width="5%">2</th>
            	<th width="5%">3</th>
            	<th width="5%">4</th>
            	<th width="5%">5</th>
            	<th width="5%">6</th>
            	<th width="5%">7</th>
            	<th width="5%">8</th>
            	<th width="5%">9</th>
            	<th width="5%">10</th>
            	<th width="5%">11</th>
            	<th width="5%">12</th>
            	<th width="5%">13</th>
            	<th width="5%">14</th>
            	<th width="5%">15</th>
            </tr>
            <?php
				$totsno		=	10;
				$sno		=	1;
				$tqty		=	0;
				//for($p=0;$p<count($particulars);$p++){
				for($p=0;$p<$totsno;$p++){
					$tqty		=	$tqty + $particulars[$p]['invQty'];
					?>
                    <tr style="background:#D4D4D4;">
                        <td align="center" rowspan="2" style="background:#fff;"><?php print ($p+1); ?></td>
                        <td align="center" rowspan="2" style="background:#fff;"><?php print ($particulars[$p]['invParam'])?$particulars[$p]['invParam']:'&nbsp;'; ?><?php print ($particulars[$p]['invMethod'])?" / ".$particulars[$p]['invMethod']:'&nbsp;'; ?></td>
                        <td align="center" rowspan="2" style="background:#fff;"><?php print ($particulars[$p]['invSpec'])
																									?$particulars[$p]['invSpec']." ".
																										(($particulars[$p]['invUoM'])?"(".$particulars[$p]['invUoM'].")":'')
																									:'&nbsp;'; ?></td>
                        <td align="center" style="background:#fff;">WEPL</td>
                        <td align="center"><?php print ($particulars[$p]['invSample1'] != "" && $particulars[$p]['invSample1'] != "0.000")?$particulars[$p]['invSample1']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample2'] != "" && $particulars[$p]['invSample2'] != "0.000")?$particulars[$p]['invSample2']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample3'] != "" && $particulars[$p]['invSample3'] != "0.000")?$particulars[$p]['invSample3']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample4'] != "" && $particulars[$p]['invSample4'] != "0.000")?$particulars[$p]['invSample4']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample5'] != "" && $particulars[$p]['invSample5'] != "0.000")?$particulars[$p]['invSample5']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample6'] != "" && $particulars[$p]['invSample6'] != "0.000")?$particulars[$p]['invSample6']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample7'] != "" && $particulars[$p]['invSample7'] != "0.000")?$particulars[$p]['invSample7']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample8'] != "" && $particulars[$p]['invSample8'] != "0.000")?$particulars[$p]['invSample8']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample9'] != "" && $particulars[$p]['invSample9'] != "0.000")?$particulars[$p]['invSample9']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample10'] != "" && $particulars[$p]['invSample10'] != "0.000")?$particulars[$p]['invSample10']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample11'] != "" && $particulars[$p]['invSample11'] != "0.000")?$particulars[$p]['invSample11']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample12'] != "" && $particulars[$p]['invSample12'] != "0.000")?$particulars[$p]['invSample12']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample13'] != "" && $particulars[$p]['invSample13'] != "0.000")?$particulars[$p]['invSample13']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample14'] != "" && $particulars[$p]['invSample14'] != "0.000")?$particulars[$p]['invSample14']:'-'; ?></td>
                        <td align="center"><?php print ($particulars[$p]['invSample15'] != "" && $particulars[$p]['invSample15'] != "0.000")?$particulars[$p]['invSample15']:'-'; ?></td>
                    </tr>
                    <tr>
                        <td align="center">CUST</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                    </tr>
                    <?php
				}
			?>
            <tr>
            	<td colspan="19" style="padding:0px;border-bottom:0px;">
                    <table cellspacing="0" cellpadding="3" width="100%" height="75px">
                        <tr>
                            <td style="line-height:1.5em;width:50%;border-bottom:0px;" valign="top">
                                <b><?php echo @getCompanyDetails('abbrv'); ?> QC Remarks:</b><br />
                                <?php echo $data['invRemarks']; ?>
                            </td>
                            <td style="line-height:1.5em;width:50%;border-bottom:0px;" valign="top">
                                <b>Customer QC Remarks:</b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>