<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Complaint List</strong>
		<span id="button_add">New</span>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>
<div style="display:none">
    <div id="new_item_form" class="window" title="New Request" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="error_msg"></div>	
		<form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="3" class="new_form_table" width="100%">
				<tr>
					<td width='20%'>
						Customer Name
					</td>
					<td width='30%'>
						<input type="text" id="new_CustID" style="width:80%;" tabindex="1" onchange="getComponents()" />
					</td>
					<td width='20%'>
						Part Number                             
					</td>
					<td>
						<select id="new_PartNum" style="width:70%;" tabindex="2">
						</select>							
					</td> 
				</tr>
				<tr>
					<td>
						Description                             
					</td>
					<td>
						<textarea style="width:95%;height:75px;" id="new_Desc" tabindex="3"></textarea>							
					</td> 
					<td>
						Classification
					</td>
					<td>
						<select id="new_Class" style="width:40%;" tabindex="4">
							<option></option>
							<option>Quality</option>
							<option>Supply</option>
							<option>Others</option>
						</select>
					</td>
				</tr>	
				<tr>
					<td>
						Nature
					</td>
					<td>
						<select id="new_Nature" style="width:50%;" tabindex="5">
							<option></option>
							<option>Compound Related</option>
							<option>Process Related</option>
							<option>Tool Related</option>
							<option>Others</option>
						</select>
					</td>
					<td>
						Mode of Receipt
					</td>
					<td>
						<select id="new_Mode" style="width:40%;" tabindex="6">
							<option></option>
							<option>Phone</option>
							<option>Email</option>
							<option>Direct</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Sketch (if available)
					</td>
					<td>
						<input id="new_Sketch" type="file" accept="image/jpeg,application/pdf" style="width:95%" tabindex="7" />
					</td>				
					<td>
						Responsibility
					</td>
					<td>
						<input type="text" style="width:60%" id="new_Responsibility" tabindex="8" />
					</td>			
				</tr>	
				<tr>
					<td>
						Customer Complaint Ref
					</td>
					<td>
						<input id="new_CusCompRef" type="text" style="width:60%" tabindex="9" />
					</td>				
					<td>
						Customer Complaint Date
					</td>
					<td>
						<input type="date" id="new_CusCompRefDate" tabindex="10" value="<?php echo date('Y-m-d'); ?>"  />
					</td>			
				</tr>
				<tr>
					<td>
						Returned Qty/Ref
					</td>
					<td>
						<input id="new_RetQty" type="text" style="width:20%" tabindex="11" value=0 /> &nbsp; <input id="new_RetRef" type="text" style="width:60%" tabindex="12" />
					</td>				
					<td>
						Returned Date
					</td>
					<td>
						<input type="date" id="new_RetDate" tabindex="13" value="<?php echo date('Y-m-d'); ?>"  />
					</td>			
				</tr>				
				<tr>
					<td>
						Corrective Action Target
					</td>
					<td>
						<input type="date" id="new_CorrTargetDate" tabindex="14" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"))); ?>"  />
					</td>				
					<td>
						Closure Target
					</td>
					<td>
						<input type="date" id="new_PrevTargetDate" tabindex="15" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))); ?>"  />
					</td>			
				</tr>				
			</table>
		</form>
	</div>
	<div id="create_dialog">
</div>
