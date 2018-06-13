<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Corrective Action</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="corr_action_popup" title="Add Corrective Action" >
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
						Target Date:                             
					</td>
					<td>
						<div id="corr_target" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>				
				<tr>
					<td style="font-size:11px;font-weight:bold">
						Corrective Action:                             
					</td>
					<td>
						<textarea style="width:95%;height:75px;" id="corr_action" name="corr_action" tabindex="1"></textarea>							
					</td>						
				</tr>				
				<tr>
					<td style="font-size:11px;font-weight:bold" >
						Root Cause Analysis method:                             
					</td>
					<td align="left">
						<select id="rca_method" tabindex="2">
							<option></option>
							<option>5W2H</option>							
							<option>Fishbone</option>
							<option>Kaizen</option>
							<option>Others</option>
						</select>
						&nbsp;&nbsp;
						<span class="download_button link">Download Form</span>
					</td>						
				</tr>							
			</table>
        </form>
    </div>	
	<div id="confirm_dialog"></div>
</div>
