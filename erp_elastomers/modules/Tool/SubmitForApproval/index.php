<?php
	global $appsub_docs,$appsub_docs_cpd;
	$ppapcount	=	count($appsub_docs);
	$ppaplist	=	"";
	for($ppapNo = 0; $ppapcount > $ppapNo; $ppapNo++) 
	{
		$dark		=	($dark == 'content_rows_light')?'content_rows_dark':'content_rows_light';
		$ppaplist	.=	"<tr class='$dark' >
							<td>".($ppapNo+1)."</td>
							<td>".$appsub_docs[$ppapNo]."</td>
							<td><input id='file".$ppapNo."' name='file".$ppapNo."' type='file' accept='.pdf' style='width:80%' /></td>						
						</tr>";
	}	
	$ppapcount	=	count($appsub_docs_cpd);
	$ppaplist_cpd	=	"";
	for($ppapNo = 0; $ppapcount > $ppapNo; $ppapNo++) 
	{
		$dark		=	($dark == 'content_rows_light')?'content_rows_dark':'content_rows_light';
		$ppaplist_cpd	.=	"<tr class='$dark' >
							<td>".($ppapNo+1)."</td>
							<td>".$appsub_docs_cpd[$ppapNo]."</td>
							<td><input id='cpdfile".$ppapNo."' name='cpdfile".$ppapNo."' type='file' accept='.pdf' style='width:80%' /></td>						
						</tr>";
	}		
	
?>

<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Waiting for Submission</strong>
    </div>
     <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="submit_appdocs"  title="Submit Approval Documents" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:8px;display:none" id="raise_error"></div>
        <form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
			<div id="DocsList">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr class="content_rows_light">
						<td colspan="2">Part Number : <b><span id="partnum"></span></b></td>
						<td>Description : <b><span id="partdesc"></span></b></td>
					</tr>
					<tr class="content_rows_dark">
						<th width='10%'>S.No</th>
						<th width='30%'>Doc Name</th>
						<th>File To Upload</th>
					</tr>				
					<?php echo $ppaplist; ?>
				</table>
			</div>
			<br/>
			<b>No. of Samples:</b> <input type="text" id="num_samples" name="num_samples" style="width:10%;text-align:right;" value=0 />			
			<br/>
			<b>Remarks:</b> 
			<br/>
			<textarea id="remarks" rows="2" cols="80"></textarea>
        </form>
    </div>
    <div id="submit_appdocs_cpd"  title="Submit Compound Approval Documents" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:8px;display:none" id="raise_error_cpd"></div>
        <form id="formFileUpload_cpd" enctype="multipart/form-data" method="POST" onsubmit="return false;">
			<div id="DocsList_cpd">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr class="content_rows_light">
						<td colspan="2">Comound Name : <b><span id="cpdname"></span></b></td>
						<td>Compound Spec : <b><span id="compspec"></span></b>&nbsp;&nbsp; <b><span id="drawing"></span></b></td>
					</tr>					
					<tr class="content_rows_dark">
						<th width='10%'>S.No</th>
						<th width='30%'>Doc Name</th>
						<th>File To Upload</th>
					</tr>				
					<?php echo $ppaplist_cpd; ?>
				</table>
			</div>
	    </form>
    </div>	
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
