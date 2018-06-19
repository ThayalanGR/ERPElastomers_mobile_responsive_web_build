<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Flash Report List</strong>
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
					<td width="20%">
						Description                             
					</td>
					<td width="80%">
						<textarea style="width:90%;height:75px;" id="new_Desc" tabindex="1"></textarea>							
					</td> 
				</tr>	
				<tr>
					<td>
						Sketch (if available)
					</td>
					<td>
						<input id="new_Sketch" type="file" accept="image/jpeg,application/pdf" style="width:75%" tabindex="2" />
					</td>				
				</tr>	
			</table>
		</form>
	</div>
	<div id="create_dialog">
</div>
