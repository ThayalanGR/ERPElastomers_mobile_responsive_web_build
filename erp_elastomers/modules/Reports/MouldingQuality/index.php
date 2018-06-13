<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Component Quality Memo</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="45%">
					For Month:
				</th>
				<th align="left">
					<input type="text" rel="datepicker" class="monthOnly" tabindex="3" id="to_date" style="width:33%" value="<?php echo date("F Y"); ?>" />
				</th>
			</tr>
		</table>
		<br />
		<div id="window_list_head">
			<strong>Memo List</strong>
			<!--<span id="button_add">New</span>-->
		</div>
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_tbl"></div>
		<div id="content_foot">
			<table border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
					<th align="right" style="width:40%;font-weight:bold">Grand Total</th>
					<th align="right" id="ins_qty_total" style="width:7%;font-weight:bold">0.00</th>
					<th align="right" id="app_qty_total" style="width:7%;font-weight:bold">0.00</th>
					<th align="right" id="rej_qty_total" style="width:7%;font-weight:bold">0.00</th>
					<th align="right" id="rew_qty_total" style="width:7%;font-weight:bold">0.00</th>
					<th align="right" id="rej_per_total" style="width:7%;font-weight:bold">0.00</th>
					<th align="right" id="rew_per_total" style="width:7%;font-weight:bold">0.00</th>				
					<th align="right" colspan="2">&nbsp;</th>
				</tr>
			</table>
		</div>
		<div style="text-align:right;" >
			<strong>Show Overall Quality Inspection Report for last 
				<select tabindex="4" id="no_months" style="width:5%" > 
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option selected="true">6</option>
					<option>9</option>
					<option>12</option>				
				</select> 
				Months 
			</strong>
			<input type="button" value="Go" onclick="openReport();" />	
		</div>			
	</form>			
</div>
