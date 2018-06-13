<?php

	global $tlvalidfiles_upload_dir;
	
	$invoice_id					=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	
	//finds Tool Reference
	$sql_tool		= "select *, DATE_FORMAT(intro_date ,'%d-%b-%y') as introDate,ifnull(sum(actualLifts),0)as lifts_run,cmpdRefNo 
						from tbl_tool tt
						left join tbl_moulding_receive tmr on tt.tool_ref = tmr.toolRef
						left join tbl_component	tc on tt.compId = tc.cmpdId
						where tool_ref='$invoice_id'";
	$out_tool		= @getMySQLData($sql_tool);
	
	extract($out_tool['data'][0]);
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $comp_part_ref; ?></title>
        <style>
			body{
				font-family:Verdana;
				font-size:12px;
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
			.pgraph{
				height:80px;
				vertical-align:middle;
			}
			.tool_details{height:15px;}
		</style>
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(27);
				print $formArray[0]; 
			?>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td colspan="2" rowspan='2' align="center" style="padding:10px" >
                	<img src="/images/company_logo.png" width="100px" />
                </td>
                <td colspan="6" class="content_bold cellpadding content_center" height="80px" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
					website : <?php echo @getCompanyDetails('website'); ?>
                </td>
                <td colspan="1" rowspan='2' class="content_center content_bold" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
                </td>
            </tr>
            <tr>
            	<td colspan="6" class="content_center content_bold">
                	<div style="font-size:14px;"><?php print $formArray[1]; ?></div>																														
                </td>
            </tr>
            <tr>
				<td  colspan='2' class="content_bold tool_details" style="border-right:0px;">
					Mould Reference
				</td>
				<td  style="border-right:0px;">
					: <?php print $tool_ref; ?>
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					No.of Cavities
				</td>
				<td style="border-right:0px;">
					: <?php print $no_of_cavities; ?>
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					Date of Introduction
				</td>
				<td  style="border-right:0px;">
					: <?php print $introDate; ?>
				</td>
			   <td  class="content_bold tool_details" style="border-right:0px;">
					Lifts Run
				</td>	
				<td>
					 : <?php print $lifts_run; ?>&nbsp;
				</td>
			</tr>
			<tr>
				<td  colspan='2' class="content_bold tool_details" style="border-right:0px;">
					Component Name
				</td>
				<td style="border-right:0px;">
					 : <?php print $cmpdRefNo; ?>&nbsp;
				</td>
			   <td  class="content_bold tool_details" style="border-right:0px;">
					No. of Active Cavities
				</td>
				<td style="border-right:0px;">
					 : <?php print $no_of_active_cavities; ?>&nbsp;
				</td>										
 				<td  class="content_bold tool_details"  style="border-right:0px;">
					Mould Life
				</td>
				<td style="border-right:0px;">
					: <?php print $tool_life; ?>
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					Next Validation
				</td>
				<td>
					: <?php print ($status1==1)?$next_validation:"Tool Inactive" ; ?>
				</td>
			</tr>
			<tr>
				<td  colspan='2' class="content_bold tool_details" style="border-right:0px;">
					Component Reference
				</td>
				<td  style="border-right:0px;">
					: <?php print $comp_part_ref ; ?>
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					Location
				</td>
				<td style="border-right:0px;">
					 : <?php print $rack; ?>&nbsp;
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					Prev Lifts (If Any)
				</td>
				<td style="border-right:0px;">
					 : <?php print $prev_lifts_run; ?>&nbsp;
				</td>
				<td  class="content_bold tool_details" style="border-right:0px;">
					Report Date
				</td>
				<td>
					 : <?php print date('d-M-Y') ?>&nbsp;
				</td>				
			</tr>
			<tr>
				<td width='5%' class="content_bold tool_details content_center">
					S No.
				</td>
				<td  class="content_bold tool_details content_center">
					Valid. Date
				</td>
				<td  class="content_bold tool_details content_center">
					Complaint
				</td>
				<td colspan="2" class="content_bold tool_details content_center">
					Condition of Mould During Inspection
				</td>
				<td  class="content_bold tool_details content_center">
					Corrective Action
				</td>
				<td  class="content_bold tool_details content_center">
					Cumm. Lifts
				</td>
				<td  class="content_bold tool_details content_center">
					Remarks
				</td>
				<td  class="content_bold tool_details content_center">
					Signature
				</td>				
			</tr>	
            <?php
			
				$sql_tool_valid		= 	"select *, DATE_FORMAT(tvn_date ,'%d-%b-%y') as tvn_date1 from tbl_tool_validation	where tool_ref='$invoice_id' and status=1 order by tvn_date";
				$particulars		= 	@getMySQLData($sql_tool_valid);
				$uploadPath 		= 	$_SESSION['app']['iso_dir'].$tlvalidfiles_upload_dir;				
				for($p=0;$p<$particulars['count'];$p++){
					$linksForDocs	=	"";
					$validTxnNo		=	$particulars['data'][$p]['tvn_ref'];
					$filesArr		=	glob($uploadPath.$validTxnNo."/*.*");
					$docPath		=	array();
					for($fcount	= 0; $fcount < count($filesArr); $fcount++) {	
						$docName		=	substr(substr(strrchr($filesArr[$fcount],'/'),1),0);
						$linksForDocs	.=	"<div><a href='/".$tlvalidfiles_upload_dir.$validTxnNo."/".$docName."' target='_blank'>". $docName."</a></div>";
					}
			?>			
            <tr>
            	<td class="pgraph content_center" >
					<?php print $p+1; ?> 
                </td>
            	<td  class="pgraph content_center">
					 <?php print $particulars['data'][$p]['tvn_date1']; ?>
                </td>
             	<td  class="pgraph">
					<?php print $particulars['data'][$p]['complaint']; ?>
                </td>
             	<td colspan="2" class="pgraph">
					<?php print $particulars['data'][$p]['observation']; ?>
                </td>
				<td  class="pgraph">
					<?php print $particulars['data'][$p]['action_taken']; ?>
                </td>
            	<td  class="pgraph content_right">
					<?php print $particulars['data'][$p]['lifts_run']; ?>
                </td>
             	<td  class="pgraph">
					<?php print $particulars['data'][$p]['remarks'].$linksForDocs; ?>					
                </td>
             	<td  class="pgraph">
					&nbsp;
                </td>				
            </tr>
			<?php } ?>
            <tr height="30px">
            	<td colspan="5" class="content_bold" valign="top">
                	Prepared :
                </td>
                <td colspan="4" class="content_bold" valign="top">
                	Approved :
                </td>
            </tr>

        </table>
    </body>
</html>