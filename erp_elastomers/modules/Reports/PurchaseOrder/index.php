<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Purchase Order</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="25%">
					From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" style="width:75%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="15%">
					To:
				</th>
				<th align="left">
					<input type="date" tabindex="2" id="to_date" style="width:25%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
			</tr>			
		</table>
	</form>
    <div id="window_list_head">
        <strong>PO List</strong>
    </div>
	<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body">
		</div>
	</div>	
	 <table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr>
			<th align="right" style="width:58%;">Grand Total</th>
			<th align="right" id="ord_qty_total" style="width:10%;">0.00</th>
			<td align="right" style="width:10%">&nbsp;</td>
			<th align="right" id="val_total" style="width:10%;">0.00</th>
			<th align="right">&nbsp;</th>
		</tr>
	</table>
</div>



