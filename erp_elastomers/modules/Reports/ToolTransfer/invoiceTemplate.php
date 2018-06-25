<?php
	global	$gstChangeCutoff;
	$invoice_id					=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	//finds Tool Reference
	$sql_ttn		= "select *, DATE_FORMAT(itt.ttn_date,'%d-%b-%y') as ttnDate,
							if(ROUND(((select sum(mouldQty)as mldQty from (select modRecRef, toolRef, mouldQty from tbl_moulding_receive where toolRef!='' group by modRecRef) as tool where toolRef=itt.tool_code group by toolRef)/itt.no_of_cavities), 0)>0, ROUND(((select sum(mouldQty)as mldQty from (select modRecRef, toolRef, mouldQty from tbl_moulding_receive where toolRef!='' group by modRecRef) as tool where toolRef=itt.tool_code group by toolRef)/itt.no_of_cavities), 0),0)as lifts_run
							, (select DATE_FORMAT(tvn_date,'%d-%b-%y')as last_valid from tbl_tool_validation where tool_ref=itt.tool_code order by tvn_date desc limit 1)as last_validation
						, itt.status as toolStatus from tbl_invoice_tool_transfer itt
							left outer join tbl_moulding_receive mr on itt.tool_code=mr.toolRef
						where invoice_id='$invoice_id' and status1=1 group by itt.invoice_id";
	
	$out_ttn		= @getMySQLData($sql_ttn);
	extract($out_ttn['data'][0]);
//checkOutputArray($out_ttn);			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $ttn_ref; ?></title>
        <style>
			body{
				font-family:Verdana;
				font-size:10px;
			}
						
			.content_bold{
				font-weight:bold;
			}
			
			.cellpadding{
				padding:0px 0px 0px 0px;
			}
			
			.content_center{
				text-align:center;
			}
			
			.content_left{
				text-align:left;
			}
						
			.content_right{
				text-align:right;
			}
			
			.uppercase{
				text-transform:uppercase;
			}

			#print_out{
				width:100%;
				border-left:2px double black;
				border-top:2px double black;
				border-right:2px double black;
				border-bottom:2px double black;
			}
			
			#print_out td, #print_out th{
				border-right:1px solid black;
				border-bottom:1px solid black;
			}
			
			.content_normal{
				font-weight:normal;
			}
			
			.float_right{
				float:right;
			}
			.float_left{
				float:left;
			}
		</style>
    </head>
    <body>
    <?php 
		$repeat_no	=	2;
		for($r=0; $r<$repeat_no; $r++){
	?>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(26);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td  align="center" style="padding:10px" >
                	<img src="/images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
					website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
					<?php echo '<img src="'.$qr_genrate_url.'?id=inv~'.$ttn_ref.'" />'; ?>
                </td>
            </tr>
            <tr>
            	<td colspan="6" class="content_center content_bold">
                	<?php print $formArray[1]; ?>																														
                </td>
            </tr>
            <?php if($ttn_date > $gstChangeCutoff)
			{ ?>
            <tr>
				<td colspan="3" rowspan="4" valign="top" >
					<label>Contractor Name & Address : </label><br />
					<b> <?php $address = split("\|", $contractor_detail); echo $address[0]."<br/>".$address[1]."<br />".$address[2]."<br />".$address[3];?>&nbsp;</b>
				</td>
				<td  height="15px" style="border-right:0px;">
					TTN Reference.
				</td>
				<td colspan="2" class="content_bold">
					 : <?php print $ttn_ref; ?>
				</td>						
			</tr>
			<tr>
				<td height="15px" style="border-right:0px;">
					TTN Date
				</td>
				<td colspan="2" class="content_bold">
					 : <?php print $ttnDate; ?>&nbsp;
				</td>					
            </tr>
			<tr>
				<td height="15px" style="border-right:0px;">
					GST No.
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print $gstn; ?>&nbsp;
				</td>					
            </tr>
			<tr>
				<td height="15px" style="border-right:0px;">
					PAN No.
				</td>
				<td colspan="2"  class="content_bold">
					 : <?php print $pan; ?>&nbsp;
				</td>					
            </tr>			
			<?php } 
			else 
			{ ?>
			<tr>
            	<td colspan="6" valign="top" style="padding:0px;">
                    <table cellpadding="3" height="90px" cellspacing="0" border="0" style="width:100%;border:0px;">
                        <tr>
                            <td valign="top" width="50%" style="padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border:0px;">
                                    <tr>
                                        <td style="padding:0px;border-right:0px;border-bottom:0px;">
                                            <table border=0 cellspacing=0 cellpadding=5 style="width:100%;border:0px;">
                                                <tr>
                                                    <td colspan="5" valign="top" height="78px"style="border-right:0px;width:60%;">
                                                        <label>Contractor Name & Address : </label><br />
                                                         <b><?php $address = split("\|", $contractor_detail); echo $address[0]."<br/>".$address[1]."<br />".$address[2]."<br />".$address[3];?>&nbsp;</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" valign="top" style="border-right:0px;padding-bottom:4px;">
                                                        <label>TTN Reference</label>
                                                         <p class="content_bold" style="display:inline;padding-left:50px;">: <?php print $ttn_ref; ?></p>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" valign="top" style="border-right:0px;border-bottom:0px;">
                                                        <label>TTN Date</label>
                                                        <p  class="content_bold" style="display:inline;padding-left:80px;">: <?php print $ttnDate; ?></p>&nbsp;
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border-right:0px;padding:0px;border-bottom:0px;">
                                <table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;width:40%;">
                                            Excise Control Code
                                        </td>
                                        <td  class="content_bold" colspan="2" style="border-right:0px;">
                                            : <?php print $excise_control_code; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;">
                                            Excise Range
                                        </td>
                                        <td colspan="2" class="content_bold" style="border-right:0px;">
                                            : <?php print $excise_range; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;">
                                            Division
                                        </td>
                                        <td colspan="2"  class="content_bold" style="border-right:0px;">
                                            : <?php print $division; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;">
                                            Comissionerate
                                        </td>
                                        <td colspan="2" class="content_bold" style="border-right:0px;">
                                             : <?php print $comissionerate; ?>&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;">
                                            TIN No.
                                        </td>
                                        <td colspan="2" class="content_bold" style="border-right:0px;">
                                             : <?php print $gstn; ?>&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="15px" style="border-right:0px;border-bottom:0px;">
                                            CST No./ Date
                                        </td>
                                        <td colspan="2" class="content_bold" style="border-right:0px;border-bottom:0px;">
                                             : <?php print $pan;?>&nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
			<?php
			}
			?>
            <tr>
            	<td colspan="6" class="content_bold" valign="top">
                	Please receive the following tool for jobwork and return:
                </td>
            </tr>
            <tr>
				<td style="border-right:0px;" height="10px;" width="14%">
					Tool Code 
				 </td>
				 <td width="20%" class="content_bold"> 
					: <?php print $tool_code; ?>&nbsp;
				</td>
				<td style="border-right:0px;" width="22%" height="10px;">
					No. Of Cavities
				</td>
				<td width="13%" class="content_bold"> 
						: <?php print $no_of_cavities; ?>&nbsp;
				</td>
				<td style="border-right:0px;" width="16%" height="10px;">
					Lifts Run
				</td>
				<td class="content_bold">
					 : <?php print $lifts_run; ?>&nbsp;
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;" height="10px;">
					 Part Number 
				 </td>
				 <td class="content_bold"> 
					: <?php print $part_name; ?>&nbsp;
				</td>
				<td style="border-right:0px;" height="10px;">
					No. Of Active Cavities
				</td>
				<td class="content_bold"> 
					: <?php print $no_of_active_cavities; ?>&nbsp;
				</td>
				<td style="border-right:0px;" height="10px;">
					Last Validation
				</td>
				<td class="content_bold"> 
					: <?php print $last_validation; ?>&nbsp;
				</td>
			</tr>
			<tr>
				<td style="border-right:0px;" height="10px;">
					Part Name 
				</td>
				<td class="content_bold"> 
					: <?php print $part_no; ?>&nbsp;
				</td>
				<td style="border-right:0px;" height="10px;">
					Status
				</td>
				<td class="content_bold">
					: <?php print $toolStatus; ?>&nbsp;
				</td>
				<td style="border-right:0px;" height="10px;">
					Next Validation
				</td>
				<td class="content_bold"> 
					: <?php print $next_validation; ?>&nbsp;
				</td>
             </tr>
            <tr>
            	<td colspan="9" height="45px;" valign="top">
                	Remarks:
                </td>
            </tr>
            <tr>
            	<td colspan="2" class="content_bold" valign="bottom" height="50px">
                	E & O.E.
                </td>
                <td colspan="2" valign="top">
                	Receiver's Signature & Seal
                </td>
                <td colspan="2" width="100px" valign="top">
                	For <?php  echo $_SESSION['app']['comp_name'];?>
                   	<p align="right" style="padding-bottom:0px; margin-bottom:0px; margin-top:30px;">Authorised Signatory</p>
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