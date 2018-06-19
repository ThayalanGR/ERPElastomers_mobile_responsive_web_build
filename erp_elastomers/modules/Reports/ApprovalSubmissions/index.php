<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Tool Approval Submissions</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="25%">
					From:
				</th>
				<th align="left" width="25%">
					<input type="date"  tabindex="1" id="from_date" style="width:40%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, 1  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="5%">
					To:
				</th>
				<th align="left">
					<input type="date" tabindex="2" id="to_date" style="width:22%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>				
			</tr>			
		</table>
	</form>
    <div id="window_list_head">
        <strong>Product List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>		
	</div>
	<div id="window_list">
		<div id="content_body">
		</div>
	</div>
   <div id="view_dialog" title="Approval Submission" style="visibility:hidden">
		<div id="new_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
 		<table cellpadding="6" cellspacing="0" width="100%" border="0" >
			<tr>
				<td width="30%"  >
					Submission Type:
				</td>
				<td>
					<select id="submit_type">
						<option value=0>Available Documents</option>
						<option value=1>PPAP Submission</option>
					</select>					
				</td>
			</tr>
			<tr>
				<td>
					Email Address(s)*:
				</td>
				<td>
					<input type="text" id="submit_email" style="width:90%;" />
				</td>
			</tr>			
		</table> 
		<font size='1px'>*Separate Multiple Email Adresses with ","</font>
   </div>	
</div>