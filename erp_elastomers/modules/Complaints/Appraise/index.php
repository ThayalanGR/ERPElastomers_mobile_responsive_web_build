<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Appraise</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="app_popup" title="Appraise probable cause with respect to" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error_app"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<th width="30%" align="left">
						<div id="label_id" style="font-size:11px;"> </div>                             
					</th>
					<th align="left">
						<textarea style="width:95%;height:75px;" id="new_remarks" name="new_remarks" tabindex="1"></textarea>							
					</th>						
				</tr>
			</table>
			<div id="fr_options_table">
				<table border="0" cellspacing="0" cellpadding="5" width="100%" >
					<tr>
						<th width="30%" align="left">
							Not Related                             
						</th>
						<th align="left">
							<input type="checkbox" name='cb_not_related' tabindex="2" id="cb_not_related" >							
						</th>						
					</tr>
					<tr>
						<th align="left">
							Responsibility Taken Up                             
						</th>
						<th align="left">
							<input type="checkbox" name='cb_resp_takenup' tabindex="3" id="cb_resp_takenup" >							
						</th>						
					</tr>
					<tr>
						<th align="left">
							Corrective Action Target                            
						</th>
						<th align="left">
							<input type="date" name="corr_act_target" id="corr_act_target" tabindex="8" style="width:30%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"))); ?>" />							
						</th>						
					</tr>
					<tr>
						<th align="left">
							Closure Target                             
						</th>
						<th align="left">
							<input type="date" name="close_target" id="close_target" tabindex="8" style="width:30%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))); ?>" />							
						</th>						
					</tr>				
				</table>
			</div>
        </form>
    </div>
	<div id="confirm_dialog"></div>
</div>
