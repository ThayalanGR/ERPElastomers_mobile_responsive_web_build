<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Moulding Issue</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
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
			<strong>DC List</strong>
		</div>	
		<div id="content_head">
			<table border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
				  <th width="20%" align="center">DC No</th>
				  <th width="20%" align="center">Issue Date</th>
				  <th width="20%" align="center">Operator</th>
				  <th align="center">#</th>
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
	</form>
    <div id="view_dialog" >
    </div>		
</div>
