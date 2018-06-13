<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Complaint/Flash Report Closure</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="closure_popup" title="Complaint Closure" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr id="tool_comm_row">
					<td width="30%" style="font-size:11px;">
						Tooling Comments:                             
					</td>
					<td>
						<div id="tool_comments" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr id="cpd_comm_row">
					<td style="font-size:11px;">
						Compounding Comments:                             
					</td>
					<td>
						<div id="cpd_comments" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr id="prod_comm_row">
					<td style="font-size:11px;">
						Production Comments:                             
					</td>
					<td>
						<div id="prod_comments" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr id="pur_comm_row">
					<td style="font-size:11px;">
						Purchase Comments:                             
					</td>
					<td>
						<div id="purchase_comments" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr id="qual_comm_row">
					<td style="font-size:11px;">
						Quality Comments:                             
					</td>
					<td>
						<div id="quality_comments" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>		
				<tr>
					<td style="font-size:11px;">
						Corrective Action:                             
					</td>
					<td>
						<div id="corr_action" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr>
					<td style="font-size:11px;">
						Root Cause Analysis method:                             
					</td>
					<td>
						<span id="anal_method" style="font-size:11px;font-weight:bold"> </span>							
					</td>						
				</tr>				
				<tr>
					<td style="font-size:11px;">
						Preventive Action:                             
					</td>
					<td>
						<div id="prev_action" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr>
					<td style="font-size:11px;">
						Target Date:                             
					</td>
					<td>
						<div id="close_target" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>	
				<tr>
					<td style="font-size:11px;font-weight:bold">
						Closure Remarks:                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="close_remarks" name="close_remarks" tabindex="1"></textarea>							
					</td>					
				</tr>
				<tr>
					<td style="font-size:11px;font-weight:bold">
						Closure Verify Doc. (if any)
					</td>
					<td>
						<input id="cmpl_file" type="file" accept=".pdf" style="width:70%" tabindex="2"  /> 
					</td>					
				</tr>					
			</table>
        </form>
    </div>	
	<div id="confirm_dialog"></div>
</div>
