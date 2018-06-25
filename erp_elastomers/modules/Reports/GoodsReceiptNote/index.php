<div id="window_list_wrapper" class="filter-table" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Good Receipt Note</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="15%">
					From:
				</th>
				<th align="left" width="25%">
					<input type="date"  tabindex="1" id="from_date" style="width:40%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="5%">
					To:
				</th>
				<th align="left" width="25%">
					<input type="date" tabindex="2" id="to_date" style="width:40%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>	
				<th align="left" width="5%">
					<input onclick="submitPrint();" type="button" value="Email Selected GRN(s)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
				</th>
				<th align="right">
					<a class="email_grndetails_button link">email GRN Report</a> 
				</th>				
			</tr>
			<tr height="20px">
				<td colspan="6" ><span id="new_msg_row">&nbsp;</span></td>
			</tr>
		</table>
	</form>
    <div id="window_list_head">
        <strong>GRN List</strong>
    </div>
	<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body">
		</div>
	</div>	
	<table border="0" cellpadding="6" cellspacing="0" width="100%">
		<tr>
			<th align="right"  width="59%">Grand Total</th>
			<th align="right" width="10%" id="ord_qty_total">0.00</th>
			<th width="10%">&nbsp;</th>
			<th align="right" width="10%" id="val_total">0.00</th>
			<th>&nbsp;</th>
		</tr>
	</table>
</div>
