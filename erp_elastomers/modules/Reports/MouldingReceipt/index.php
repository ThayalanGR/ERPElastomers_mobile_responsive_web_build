<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Moulding Receipt</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="45%">
					Select Month:
				</th>
				<th align="left">
					<input type="text" rel="datepicker" class="monthOnly" tabindex="1" id="to_date" style="width:33%" value="<?php echo date("F Y"); ?>" />
				</th>
			</tr>
		</table>
	</form>
    <div id="window_list_head">
        <strong>Moulding Receipt List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>
	</div>
	<div id="content_tbl"></div>
    <div id="content_foot">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th align="right" style="width:39.2%;font-weight:bold">Grand Total</th>
                <th align="right" id="plnd_lifts_total" style="width:7%;font-weight:bold">0.00</th>
                <th align="right" id="act_lifts_total" style="width:7%;font-weight:bold">0.00</th>
                <th align="right" id="plnd_qty_total" style="width:7%;font-weight:bold">0.00</th>
                <th align="right" id="act_qty_total" style="width:7%;font-weight:bold">0.00</th>
                <th align="right" id="iss_qty_total" style="width:7%;font-weight:bold">0.00</th>
                <th align="right" id="used_qty_total" style="width:7%;font-weight:bold">0.00</th>				
                <th align="right" >&nbsp;</th>
            </tr>
        </table>
    </div>
 	<form id="exportform" name="exportform" action=""  method="post" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td colspan="7">&nbsp;</td>
            <td align="right">
				<input id="type" name="type" type="hidden"  />
				<input id="startDate" name="startDate" type="hidden"  />
				<input id="endDate" name="endDate" type="hidden"  />				
				<button id="button_submit" name="button_submit" type="submit" onclick="exportPlanDetailList();" >Export As CSV</button>
			</td>
        </tr>
    </table>
	</form>	
</div>
