<?php
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$inv_comp		=	@getMySQLData("select trnId, toolId, DATE_FORMAT(tool_appr_date,'%d-%b-%Y') as toolAppDate, cusName,part_number,part_description,toolSize,toolCavities,moldProcess,moldType,
											cpdName, blank_weight, strip_dim1,strip_dim2, strip_dim3,strip_weight,tsr.strips_per_lift,cure_temperature,cure_time,cure_pressure,mold_remarks,
											post_cure, postcure_time, postcure_temp, trim_remarks, insp_remarks,blanking_type
											from tbl_trn tt
											inner join tbl_develop_request tdr on tt.rfqid=tdr.sno
											inner join tbl_customer tc on tdr.cusId=tc.cusId
											inner join (select * from (select planId, toolRef from tbl_sample_plan where status = 2 order by planDate desc)tbl1 group by toolRef ) tsp on tsp.toolRef = tt.trnId
											inner join tbl_sample_receipt tsr on tsr.planId = tsp.planId
											inner join tbl_compound tcpd on tcpd.cpdId = tsr.cpdId
											inner join tbl_tool ttl on ttl.tool_ref = tt.toolId
										where trnid = '$invoice_id' ");
	$particulars	=	$inv_comp['data'][0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $invoice_id; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(8);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="4" align="center" style="padding:0px;" >
                    <table cellpadding="3" cellspacing="0" border="0" id="print_out" style="border:0px;" >
                        <tr>
                            <td align="center" style="padding:10px;width:100px;" >
                                <img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                            </td>
                            <td class="content_bold cellpadding content_center" >
								<font size="4"><?php print $formArray[1]; ?></font>
                            </td>
                            <td width="100px" class="content_center content_bold uppercase" style="border-right:0px;" >
								
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
				<tr>
					<td style="border-right:0px;width:15%;">
						Tool ID 
					</td>
					<td class="content_bold" style="width:35%;">
						: <?php print $particulars['toolId']; ?>
					</td>
					<td  style="border-right:0px;width:15%;">
						Part Number
					</td>
					<td class="content_bold">
						: <?php print $particulars['part_number']; ?>
					</td>
				</tr>						
				<tr>
					<td style="border-right:0px;">
						Customer
					</td>
					<td class="content_bold">
						: <?php print $particulars['cusName']; ?>
					</td>
					<td style="border-right:0px;">
						Approval. Date
					</td>
					<td class="content_bold">
						: <?php print $particulars['toolAppDate']; ?>
					</td>
                </td>
			</tr>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Mould Details 
                </td>
            </tr>			
			<tr>
				<td>
					Dimension
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['toolSize']; ?>
				</td>
				<td>
					No. of Cavities
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['toolCavities']; ?>
				</td>
			</tr>
			<tr>
				<td>
					Moulding Process
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['moldProcess']; ?>
				</td>
				<td>
					Mould Type
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['moldType']; ?>
				</td>
			</tr>			
            <tr>
            	<td colspan="4" style="vertical-align:top;" height="60px">
                	Remarks
                </td>
            </tr>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Compound Details 
                </td>
            </tr>			
			<tr>
				<td>
					Cpd. Ref.
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['cpdName']; ?>
				</td>
				<td>
					Blank Wgt
				</td>
				<td class="content_center content_bold">
					<?php print number_format($particulars['blank_weight'],3); ?> gms
				</td>
			</tr>
			<tr>
				<td>
					Blank Type
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['blanking_type']; ?>
				</td>
				<td>
					Strip Dim. (lxbxth Ref. Only*)
				</td>
				<td class="content_center content_bold">
					<?php print (($particulars['strip_dim2']) && ($particulars['strip_dim2'] > 0))?number_format($particulars['strip_dim2'],2)." x":'- x '; ?>
					<?php print (($particulars['strip_dim3']) && ($particulars['strip_dim3'] > 0))?number_format($particulars['strip_dim3'],2)." x":'- x '; ?>
					<?php print (($particulars['strip_dim1']) && ($particulars['strip_dim1'] > 0))?number_format($particulars['strip_dim1'],2)." ":'-'; ?> mm
				</td>
			</tr>	
			<tr>
				<td>
					Strip Weight
				</td>
				<td class="content_center content_bold">
					<?php print number_format($particulars['strip_weight'],3); ?> gms
				</td>
				<td>
					Strips/Lift
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['strips_per_lift']; ?>
				</td>
			</tr>				
            <tr>
            	<td colspan="4" style="vertical-align:top;" height="60px">
                	Remarks  
                </td>
            </tr>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Moulding Process Details 
                </td>
            </tr>			
			<tr>
				<td>
					Time
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['cure_time']; ?> Secs
				</td>
				<td>
					Temperature
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['cure_temperature']; ?> &deg;C
				</td>
			</tr>
			<tr>
				<td>
					Pressure
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['cure_pressure']; ?> Kg/cm<sup>2</sup>
				</td>
				<td>
					Limit Sample
				</td>
				<td>
					&nbsp;
				</td>
			</tr>			
            <tr>
            	<td colspan="4" style="vertical-align:top;" height="60px">
                	Remarks  <br /><?php print $particulars['mold_remarks'] ?>
                </td>
            </tr>	
			<?php 
				if ($particulars['post_cure'] == 1)
				{
			?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Post Curing Process Details 
                </td>
            </tr>			
			<tr>
				<td>
					Time
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['postcure_time']; ?> Hrs
				</td>
				<td>
					Temperature
				</td>
				<td class="content_center content_bold">
					<?php print $particulars['postcure_temp']; ?> &deg;C
				</td>
			</tr>
			<?php } ?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Deflashing Details 
                </td>
            </tr>			
			<tr>
				<td>
					Method
				</td>
				<td class="content_center content_bold">
					&nbsp;
				</td>
				<td>
					Limit Sample
				</td>
				<td class="content_center content_bold">
					&nbsp;
				</td>
			</tr>
            <tr>
            	<td colspan="4" style="vertical-align:top;" height="60px">
                	Remarks  <br /><?php print $particulars['trim_remarks'] ?>
                </td>
            </tr>	
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Inspection Details 
                </td>
            </tr>			
			<tr>
				<td>
					Method
				</td>
				<td class="content_center content_bold">
					&nbsp;
				</td>
				<td>
					Limit Sample
				</td>
				<td class="content_center content_bold">
					&nbsp;
				</td>
			</tr>
            <tr>
            	<td colspan="4" style="vertical-align:top;" height="60px">
                	Remarks  <br /><?php print $particulars['insp_remarks'] ?>
                </td>
            </tr>					
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Approval Details
                </td>
            </tr>			
            <tr>
            	<td colspan="3" rowspan="4" class="content_left content_bold" style="vertical-align:top;" height="100px">
                	Remarks 
                </td>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Head NPD:
                </td>
            </tr>
            <tr>
				<td valign="top" class="content_left" style="vertical-align:top;" >
                	Head Compound:
                </td>
            </tr>
            <tr>
				<td valign="top" class="content_left" style="vertical-align:top;" >
                	Head QA:
                </td>
            </tr>
			<tr>
				<td valign="top" class="content_left" style="vertical-align:top;" >
                	Head Production:
                </td>
            </tr>			
        </table>
    </body>
</html>