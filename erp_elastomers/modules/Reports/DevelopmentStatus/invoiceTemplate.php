

<?php
	global $tq_std_toolsize,$mgmt_grp_email,$dev_grp_email;
	$invoice_id		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	if($_REQUEST["type"] == "RUNJOB") 
	{
		$today					=	date("Y-m-d");		
		$fromdate 				= 	date( 'Y-m-d', strtotime( 'previous saturday' ) );
		$todate 				= 	date( 'Y-m-d', strtotime( 'friday this week' ) );
		$output					=	'<html><body><table class="print_table" border="0" cellpadding="6" cellspacing="0" width="100%">
											<tr style="font-size:8px;">
												<th width="10%">&nbsp;</th>
												<th width="9%">Created</th> 
												<th width="9%">Not-Feasible</th>
												<th width="9%">Quote Submitted</th>
												<th width="9%">Price Approved</th>
												<th width="9%">Comm. Pending</th>
												<th width="9%">Tool Ordered</th>    
												<th width="9%">Tool Received</th>
												<th width="9%">Tool Approved</th>
												<th width="9%">Tool Pending</th>
												<th width="9%">Abandoned</th>
											</tr>';
		for($rownum=0;$rownum<2;$rownum++){
			$sql_invoice	=	"select sum(newrfqraised) as newrfqraised, sum(notfeasible) as notfeasible, sum(quotesubmitted) as quotesubmitted, sum(abandonedrfqs) as abandonedrfqs, sum(pendingrfqs) as pendingrfqs, sum(priceapproved) as priceapproved, sum(toolordered) as toolordered, sum(toolreceived) as toolreceived, sum(devcompleted) as devcompleted, sum(pendingtools) as pendingtools
									 from (	select if(tdr.sno > 0 ".(($rownum == 0)?" and tdr.entry_on between '$fromdate' and '$todate'":"").", 1,0) as newrfqraised,
												if(tdr.status = 2 and tdf.approval_status = 0 ,1,0) as notfeasible,
												if(tiq.status = 1,1,0) as quotesubmitted, 
												if(tdr.status > 3".(($rownum == 0)?" and tdr.approval_date between '$fromdate' and '$todate'":"").",1,0) as priceapproved,
												if((tdr.status = 0 ) ".(($rownum == 0)?" and tdr.entry_on between '$fromdate' and '$todate'":"").",1,0) as abandonedrfqs,
												if((tdr.status = 1 or (tdr.status = 2 and tdf.approval_status = 1)) ".(($rownum == 0)?" and tdr.entry_on between '$fromdate' and '$todate'":"").",1,0) as pendingrfqs,
												if (ttp.status > 0,1,0) as toolordered,
												if (ttp.status = 2 ".(($rownum == 0)?" and ttn.trnDate between '$fromdate' and '$todate'":"").",1,0) as toolreceived,
												if (tdr.status = 5 ".(($rownum == 0)?" and ttn.tool_appr_date between '$fromdate' and '$todate'":"").",1,0) as devcompleted,
												if(tdr.status > 0 and ttp.status  is null ".(($rownum == 0)?" and tdr.entry_on between '$fromdate' and '$todate'":"").", 1,0) as pendingtools
											from  tbl_develop_request tdr 
											left join tbl_invoice_quote tiq on tiq.rfqid = tdr.drId ".(($rownum == 0)?" and tiq.quotedate between '$fromdate' and '$todate' ":"")."
											left join  tbl_develop_feasibility tdf on tdf.prod_ref = tdr.sno ".(($rownum == 0)?" and (tdf.tl_entry_on between '$fromdate' and '$todate' or tdf.cpd_entry_on between '$fromdate' and '$todate') ":"")."
											left join ( select  * from (select rfqid, status from tbl_tool_purchase where isproto = 0 and status > 0 ".(($rownum == 0)?" and purDate between '$fromdate' and '$todate'":"")." order by status desc)t1 group by rfqid)ttp on ttp.rfqid = tdr.sno
											left join ( select  * from (select rfqid, status,trnDate,tool_appr_date from tbl_trn where isproto = 0 and status > 0  order by trnDate desc)t1 group by rfqid)ttn on ttn.rfqid = tdr.sno) finaltable"; 
		//echo $sql_invoice; exit();
		
			$querydata		=	@getMySQLData($sql_invoice);
			$devdata		=	$querydata['data'][0];
			$output			.=	"<tr><td>".(($rownum == 0)?"This Week":"Till Date")."</td>";
			$output			.=	"<td>".$devdata['newrfqraised']."</td>";
			$output			.=	"<td>".$devdata['notfeasible']."</td>";
			$output			.=	"<td>".$devdata['quotesubmitted']."</td>";
			$output			.=	"<td>".$devdata['priceapproved']."</td>";
			$output			.=	"<td>".$devdata['pendingrfqs']."</td>";
			$output			.=	"<td>".$devdata['toolordered']."</td>";	
			$output			.=	"<td>".$devdata['toolreceived']."</td>";			
			$output			.=	"<td>".$devdata['devcompleted']."</td>";
			$output			.=	"<td>".$devdata['pendingtools']."</td>";
			$output			.=	"<td>".$devdata['abandonedrfqs']."</td>";
			$output			.=	"</tr>";
		}	
		$output					.=	"</table></body></html>";
		// close & send the result to user & then send email									
		closeConnForAsyncProcess("");
		// send email
		$aEmail = new AsyncCreatePDFAndEmail("NPD/NPDRegister","rfqlist", $mgmt_grp_email,$dev_grp_email,"Development Data for the week ending :".date("d-m-Y"),$output);									
		$aEmail->start();
		exit();					
	} 
	else if ( $invoice_id == "rfqlist")
	{
		echo '<script>window.location.href = "http://'.$_SERVER['SERVER_NAME'].'/NPD/DevelopmentRequest/?type=rfqlist"</script>';
		exit();
	}	
	$inv_comp		=	@getMySQLData("select sno,drId, isNew, tdr.cmpdId, part_number,part_description,tc.cusName,compound_spec,ave_monthly_req,target_price,DATE_FORMAT(tdr.entry_on, '%d-%b-%Y') as enquiry_date,tdr.remarks,ifnull(tl_toolref,'') as tl_status,ifnull(cpd_cpdid,'') as cpd_status,ifnull(prod_lift_rate,0) as prod_status, tdr.status
										from tbl_develop_request tdr
											inner join tbl_customer tc on tc.cusId=tdr.cusId
											left join tbl_component tcomp on tcomp.cmpdId=tdr.cmpdId
											left join tbl_develop_feasibility tdf on tdf.prod_ref=tdr.sno 
										where tdr.drId = '$invoice_id' ");
	$particulars	=	$inv_comp['data'][0];
	$rfid			=	$particulars['sno'];
	$inv_feas		=	@getMySQLData("select * , (select fullName from tbl_users where userId = tdf.tl_entry_by) as tl_user,(select fullName from tbl_users where userId = tdf.cpd_entry_by) as cpd_user,(select fullName from tbl_users where userId = tdf.prod_entry_by) as prod_user,(select fullName from tbl_users where userId = tdf.approval_by) as approval_user
										from tbl_develop_feasibility tdf
											left join tbl_compound tc on tc.cpdId=tdf.cpd_cpdid
											left join tbl_tool tt on tt.tool_ref=tdf.tl_toolref													
										where tdf.prod_ref = '$rfid' ");
	$feasRec		=	$inv_feas['data'][0];
	

	/*print '<pre>';
	print_r($inv_comp_qc);
	print_r($inv_comp_qc_param);
	print '</pre>';*/


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
				$formArray		=	@getFormDetails(1);
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
								<font size="6"><?php print $formArray[1]; ?></font>
                            </td>
                            <td width="100px" class="content_center content_bold uppercase" style="border-right:0px;" >&nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" valign="top" style="border-right:0px;padding:0px;border-bottom:0px;" >
                	<table cellpadding="3" cellspacing="0" border="0" style="width:100%;border-right:0px;">
                        <tr>
                            <td class="content_bold" style="border-right:0px;width:12%;">
                                Request ID 
                            </td>
                            <td style="width:38%;">
                                : <?php print $particulars['drId']; ?>
                            </td>
                            <td class="content_bold" style="border-right:0px;width:12%;">
                                Req. Date
                            </td>
                            <td>
                                : <?php print $particulars['enquiry_date']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td  class="content_bold" style="border-right:0px;">
                                Part Number
                            </td>
                            <td>
                                : <?php print $particulars['part_number']; ?>
                            </td>
                            <td class="content_bold" style="border-right:0px;">
                                Desc.
                            </td>
                            <td>
                                : <?php print $particulars['part_description']; ?>
                            </td>
                        </tr>						
                        <tr>
                            <td class="content_bold" style="border-right:0px;">
                                Customer
                            </td>
                            <td>
                                : <?php print $particulars['cusName']; ?>
                            </td>
                            <td class="content_bold" style="border-right:0px;">
                                Request For
                            </td>
                            <td>
                                : <?php print ($particulars['isNew'] == 1)?"New":"Existing"; ?> Part
                            </td>
                        </tr>
                        <tr>
                            <td class="content_bold" style="border-right:0px;">
                                AMR 
                            </td>
                            <td>
                                : <?php print $particulars['ave_monthly_req']; ?>
                            </td>
                            <td class="content_bold" style="border-right:0px;">
                                Target price
                            </td>
                            <td>
                                : <?php print $particulars['target_price']; ?>
                            </td>
                        </tr>						
						<tr style="height:40px;vertical-align:top;">
							<td valign="top" class="content_bold" style="border-right:0px;" >
								Matl. Spec 
							</td>
							<td>
								: <?php print $particulars['compound_spec']; ?>
							</td>
							<td class="content_bold" style="border-right:0px;">
								Req. Remarks
							</td>
							<td>
								: <?php print $particulars['remarks']; ?>
							</td>
						</tr>	
                    </table>
                </td>
			</tr>
            <tr>
				<th width="5%">
                	No
                </th>
            	<th width="30%">
                	Item
                </th>
                <th width="30%" >
                	Value
                </th>
                <th>
                	#
                </th>
            </tr>
			<?php if($particulars['tl_status'] != '') { ?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Engineering Sepcification / Tool Feasibility Checklist 
                </td>
            </tr>			
			<tr height="10px">
				<td align="center">
					1
				</td>
				<td>
					<?php print ($particulars['isNew'] == 1)?"Tool Available ?":"Development For"; ?>
				</td>
				<td class="content_left content_bold">
					<?php print ($particulars['isNew'] == 1)?(($feasRec['tl_toolref'] == 'NA')?'No':'Yes'):(($feasRec['tl_toolref'] == 'NA')?'New Tool':'Rework Tool'); ?>
				</td>
				<td align="center" >
					<?php print ($feasRec['tl_toolref'] == 'NA')?'':$feasRec['tl_toolref']; ?>
				</td>
			</tr>
			<tr>
				<td align="center">
					2
				</td>
				<td>
					Platen Available?
				</td>
				<td class="content_left content_bold">
					<?php print ($feasRec['tl_platten_avail'] == 1)?'Yes':'No'; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					3
				</td>
				<td>
					Suggested Tool Size (lXb in mm)
				</td>
				<td class="content_left content_bold">
					<?php print (in_array($feasRec['tl_tool_size'],$tq_std_toolsize)?'Standard':'Non-Standard'); ?>
				</td>
				<td align="center" >
					<?php print $feasRec['tl_tool_size']; ?>
				</td>
			</tr>			
			<tr>
				<td align="center">
					4
				</td>
				<td>
					Suggested Cavities
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['tl_cavs']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					5
				</td>
				<td>
					Est. Product Weight (gm)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['tl_prod_wgt']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					6
				</td>
				<td>
					Est. Blank Weight(gm)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['tl_blank_wgt']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					7
				</td>
				<td>
					Moulding Process
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['tl_mould_proc']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					8
				</td>
				<td>
					Mould Type
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['tl_mould_type']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					9
				</td>
				<td>
					Insert Used?
				</td>
				<td class="content_left content_bold">
					<?php print ($feasRec['tl_insert_used'] == 1)?'Yes':'No'; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>				
            <tr>
            	<td colspan="3" rowspan="3" class="content_left content_bold" style="vertical-align:top;" >
                	Remarks : <span class="content_normal"><?php print $feasRec['tl_remarks']; ?>&nbsp;</span>
                </td>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Result : <b><?php print ($feasRec['tl_status'] == 1)?"Feasible":"Not Feasible"; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked By : <b><?php print $feasRec['tl_user']; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked On : <b><?php print date("d-M-Y", strtotime($feasRec['tl_entry_on'])); ?></b>
                </td>
            </tr>
			<?php } 			
			if($particulars['cpd_status'] != '') { ?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Material / Compound Feasibility Checklist 
                </td>
            </tr>			
				<td align="center">
					1
				</td>
				<td>
					Existing Compound Suitable?
				</td>
				<td class="content_left content_bold">
					<?php print ($feasRec['cpd_cpdid'] == 'NA')?'No':'Yes'; ?>
				</td>
				<td align="center" >
					<?php print ($feasRec['cpd_cpdid'] == 'NA')?'':$feasRec['cpdName']; ?>
				</td>
			</tr>
			<tr>
				<td align="center">
					2
				</td>
				<td>
					Suggested Base Polymer
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['cpd_base_polymer']?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					3
				</td>
				<td>
					In-House Testing?
				</td>
				<td class="content_left content_bold">
					<?php print ($feasRec['cpd_inhouse_test'] == 1)?'Yes':'No'; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>			
			<tr>
				<td align="center">
					4
				</td>
				<td>
					Sugg. Curing Temp (&deg;C)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['cpd_cure_temp']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					5
				</td>
				<td>
					Sugg. Curing Time (Sec)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['cpd_cure_time']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					6
				</td>
				<td>
					Sugg. Curing Pressure (kg/cm<sup>2</sup>)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['cpd_cure_press']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
            <tr>
            	<td colspan="3" rowspan="3" class="content_left content_bold" style="vertical-align:top;" >
                	Remarks : <span class="content_normal"><?php print $feasRec['cpd_remarks']; ?>&nbsp;</span>
                </td>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Result : <b><?php print ($feasRec['cpd_status'] == 1)?"Feasible":"Not Feasible"; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked By : <b><?php print $feasRec['cpd_user']; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked On : <b><?php print date("d-M-Y", strtotime($feasRec['cpd_entry_on'])); ?></b>
                </td>
            </tr>
			<?php } 
			if($particulars['prod_status'] != 0) { ?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Production Feasibility Checklist 
                </td>
            </tr>			
			<tr>
				<td align="center">
					1
				</td>
				<td>
					Sugg. Lift Rate (Rs)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['prod_lift_rate']?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					2
				</td>
				<td>
					Sugg. Trim Rate (Rs)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['prod_trim_rate']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					3
				</td>
				<td>
					Sugg. Inspection Rate (Rs)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['prod_insp_rate']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
			<tr>
				<td align="center">
					4
				</td>
				<td>
					Sugg. Rejection Rate(%)
				</td>
				<td class="content_left content_bold">
					<?php print $feasRec['prod_rej_rate']; ?>
				</td>
				<td align="center" >
					&nbsp;
				</td>
			</tr>
            <tr>
            	<td colspan="3" rowspan="3" class="content_left content_bold" style="vertical-align:top;" >
                	Remarks : <span class="content_normal"><?php print $feasRec['prod_remarks']; ?>&nbsp;</span>
                </td>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Result : <b><?php print ($feasRec['prod_status'] == 1)?"Feasible":"Not Feasible"; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked By : <b><?php print $feasRec['prod_user']; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Checked On : <b><?php print date("d-M-Y", strtotime($feasRec['prod_entry_on'])); ?></b>
                </td>
            </tr>
			<?php } 
			if($particulars['status'] != 1) { ?>
            <tr>
            	<td colspan="4" class="content_center content_bold uppercase"  border="1">
                	Development Approval 
                </td>
            </tr>			
            <tr>
            	<td colspan="3" rowspan="3" class="content_left content_bold" style="vertical-align:top;" >
                	Remarks : <span class="content_normal"><?php print $feasRec['approval_remarks']; ?>&nbsp;</span>
                </td>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Result : <b><?php print ($feasRec['approval_status'] == 1)?"Approved":"Rejected"; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Approved By : <b><?php print $feasRec['approval_user']; ?></b>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="content_left" style="vertical-align:top;" >
                	Approved On : <b><?php print date("d-M-Y", strtotime($feasRec['approval_on'])); ?></b>
                </td>
            </tr>
			<?php }				
			?>		
        </table>
    </body>
</html>