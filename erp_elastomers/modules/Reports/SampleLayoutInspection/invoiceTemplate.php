<?php

	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	$sql_particulars	=	"select paramName,paramTestMethod,uom_short_name,dimSpec,dimULimit,dimLLimit from tbl_sample_layout_dim tsld											
											inner join tbl_param p on p.sno = tsld.dimref
											inner join tbl_uom tu on  tu.sno = p.paramUOM 
								where rfqId='".$invoice_id."'";
	$out_particulars	=	@getMySQLData($sql_particulars);

	$particulars		=	$out_particulars['data'];
	$component			=	@getMySQLData("select part_number,part_description, cusName, drawing_revision, date_format(drawing_date,'%d-%b-%Y') as drawing_date 
												from tbl_develop_request tdr
													inner join tbl_customer tc on tdr.cusId = tc.cusId
												where sno='".$invoice_id."'");
	$partNum			=	$component['data'][0]['part_number'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $partNum; ?> - SIR</title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(6);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="2" align="center" style="padding:10px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="80px" />
                </td>
                <td colspan="12" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="2" class="content_center content_bold uppercase" >&nbsp;
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="80px" />
                </td>
            </tr>
            <tr>
            	<td colspan="16" class="content_center content_bold">
                	<?php print $formArray[1]; ?>
                </td>
            </tr>
            <tr>
				<td colspan="2" style="border-right:0px;">Part Number</td>
				<td colspan="3">:  <?php echo $partNum; ?></td>
				<td colspan="3" style="border-right:0px;">Drawing Revision</td>
				<td colspan="3">: <?php echo $component['data'][0]['drawing_revision']; ?></td>
				<td colspan="2" style="border-right:0px;">Report Date</td>
				<td colspan="3">: &nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" style="border-right:0px;">Description</td>
				<td colspan="3">: <?php echo $component['data'][0]['part_description']; ?></td>
				<td colspan="3" style="border-right:0px;">Drawing date</td>
				<td colspan="3">:  <?php echo ($component['data'][0]['drawing_date'] == '01-Jan-1970')?'-':$component['data'][0]['drawing_date']; ?></td>
				<td colspan="2" style="border-right:0px;">Result</td>
				<td colspan="3">: Acc / Con Acc /Rej / RW / Seg</td>
			</tr>
			<tr>
				<td colspan="2"style="border-right:0px;">Customer</td>
				<td colspan="3">: <?php echo $component['data'][0]['cusName']; ?></td>
				<td colspan="3" style="border-right:0px;">Sample Size</td>
				<td colspan="3">:  &nbsp;</td>
				<td colspan="2"style="border-right:0px;">Submitted Qty</td>
				<td colspan="3">:  &nbsp;</td>
            </tr>
            <tr style="font-size:8px">
            	<th width="2%">No</th>
            	<th width="8%">Dim.</th>            	
				<th width="12%">Test Method.</th>
				<th width="5%">UOM</th>
				<th width="8%">Spec.</th>
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
            	<th>OK/Not OK</th>
            </tr>
            <?php
				$totsno		=	20;
				$pgBrk		=	20;
				if (count($particulars) > 20)
				{
					$totsno 	= count($particulars);
				}								
				$sno		=	1;
				for($p=0;$p<$totsno;$p++){
				
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="16" class="content_left content_bold" >
								Remarks/Observations/Complaints: <BR /><BR /><BR /><BR />
							</td>
						</tr>
						<tr>
							<td colspan="16" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="16" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr style="font-size:8px">
							<th width="2%">No</th>
							<th width="8%">Dim.</th>            	
							<th width="12%">Test Method.</th>
							<th width="5%">UOM</th>
							<th width="8%">Spec.</th>
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
							<th>OK/Not OK</th>
						</tr>									
					<?php	
						}
					?>					
                    <tr style="font-size:8px">
                        <td align="center"><?php print ($p+1); ?></td>
                        <td align="left"><?php print ($particulars[$p]['paramName'])?$particulars[$p]['paramName']:'&nbsp;'; ?></td>
						<td align="left"><?php print ($particulars[$p]['paramTestMethod'])?$particulars[$p]['paramTestMethod']:'&nbsp;'; ?></td>
						<td align="center"><?php print ($particulars[$p]['uom_short_name'])?$particulars[$p]['uom_short_name']:'&nbsp;'; ?></td>	
						<?php 
							$spec		= 	$particulars[$p]['dimSpec'];
							$ulimit		=	$particulars[$p]['dimULimit'];
							$llimit		=	$particulars[$p]['dimLLimit'];							
						?>
                        <td align="center"><?php print ($spec)?$spec:'&nbsp;'; ?><span class='supsub'><sup><?php print ($ulimit)?' +'.@number_format(($ulimit - $spec),3):'&nbsp;'; ?></sup><sub><?php print ($llimit)?' -'.@number_format(($spec - $llimit),3):'&nbsp;'; ?></sub></span></td>	
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
            	<td colspan="12" class="content_left content_bold" style="vertical-align:top;" >
                	Remarks :
                </td>
                <td colspan="4" valign="top"  class="content_bold content_left" style="vertical-align:top;"  >
                	For <?php  echo $_SESSION['app']['comp_name'];?> : <span style="float:right;vertical-align:bottom;padding-top:50px;" > Authorised Signatory </span>
                </td>
            </tr>
        </table>
    </body>
</html>