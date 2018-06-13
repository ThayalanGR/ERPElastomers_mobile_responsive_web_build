<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Tool List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>		
	</div>
	<div id="content_body"></div>
	<div id="view_dialog" title="Tool Approval" style="visibility:hidden">
		<div id="new_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
        <form method="post" enctype="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="50%">
						Rack 
					</td>
					<td>
						<input type="text" id="tl_rack" name="tl_rack" style="width:50%;" tabindex="1" />						
					</td>					
				</tr>				
				<tr>
					<td>
						Blanking Type
					</td>
					<td>
						<select id="tl_blanktype" name="tl_blanktype" style="width:50%;" tabindex="2">
							<option>Strip</option>
							<option>Bit</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Blanking Method 
					</td>
					<td>
						<input type="radio" id="tl_autocut" name="tl_blankmethod" value="Autocut" checked="checked"/> <label for="tl_autocut">Autocut</label> &nbsp; <input type="radio" id="tl_manual" name="tl_blankmethod" value="Manual"/> <label for="tl_manual">Manual</label>
					</td>				
				</tr>
				<tr>
					<td>
						Tool Life 
					</td>
					<td>
						<input type="text" id="tl_toollife" name="tl_toollife" style="width:50%;text-align:right;" class="invisible_text" tabindex="3" value="60,000" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '60,000')" onblur="FieldHiddenValue(this, 'out', '60,000')"/>
					</td>					
				</tr>					
				<tr>
					<td>
						Next Validation 
					</td>
					<td>
						<input type="text" id="tl_nextvalid" name="tl_nextvalid" style="width:50%;text-align:right;" class="invisible_text" tabindex="3" value="6,000" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '6,000')" onblur="FieldHiddenValue(this, 'out', '6,000')"/>
					</td>					
				</tr>	
				<tr>
					<td>
						Customer Approval Documents
					</td>
					<td>
						<input id="tl_file" name="tl_file[]" type="file" accept=".pdf" style="width:90%" tabindex="4" multiple /> 
					</td>					
				</tr>				
			</table>
			<div id="tool_exists"></div>
        </form>
	</div>	
	<div id="confirm_dialog"></div>	
</div>