<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Customer Confirmation</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="cust_conf_popup" title="Add Customer Confirmation" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="30%" style="font-size:11px;">
						Complaint Description:                             
					</td>
					<td>
						<div id="description" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr>
					<td style="font-size:11px;">
						Corrective Action:                             
					</td>
					<td>
						<div id="corraction" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr>
					<td style="font-size:11px;">
						Preventative Action:                             
					</td>
					<td>
						<div id="prevaction" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>
				<tr>
					<td style="font-size:11px;">
						Closure Remarks:                             
					</td>
					<td>
						<div id="closureremarks" style="font-size:11px;font-weight:bold"> </div>							
					</td>						
				</tr>				
				<tr>
					<td style="font-size:11px;font-weight:bold">
						Customer Confirmation:                             
					</td>
					<td>
						<textarea style="width:95%;height:75px;" id="cust_conf" name="cust_conf" tabindex="1"></textarea>							
					</td>						
				</tr>				
			</table>
        </form>
    </div>	
	<div id="confirm_dialog"></div>
</div>
