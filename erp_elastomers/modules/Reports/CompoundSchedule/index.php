<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Compound Schedule</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" width="100%" class="new_form_table">
			<tr>
				<th align="right" width="45%">
					Select Month:
				</th>
				<th align="left">
					<input type="text" rel="datepicker" class="monthOnly" tabindex="3" id="to_date" style="width:33%" value="<?php echo date("F Y"); ?>" />
				</th>
			</tr>
		</table>
		<br />
		<div id="window_list_head">
			<strong>Schedule List</strong>
		</div>		
		<div id="content_head">
			<table border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
				  <th width="20%" align="center">Schedule No</th>
				  <th width="20%" align="center">Schedule Date</th>
				  <th width="20%" align="center">Customer</th>
				  <th width="20%" align="center" title="Expected Batch Weight">Total Weight</th>
				  <th align="right">#</th>
				</tr>
			</table>
		</div>
		<div id="window_list">
			<div class="window_error">
				<div class="loading_txt"><span>Loading Data . . .</span></div>
			</div>
			<div id="content_body">
			</div>
		</div>
		<div id="content_foot">
			<table border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
					<th align="right" style="width:60%;">Grand Total</th>
					<th align="center" style="width:20%;" id="qty_total" >0.000</th>
					<th align="right">&nbsp;</th>
				</tr>
			</table>
		</div>
	</form>
</div>

<div style="display:none">    
    <div id="del_item_form" title="Delete Schedule" style="visibility:hidden">
        Are you sure to delete the entire schedule? 
        <div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
    </div>
</div>
