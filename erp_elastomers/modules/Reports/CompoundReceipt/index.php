<div id="window_list_wrapper" class="filter-table" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Compound Receipt</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" width="100%" class="new_form_table">
			<tr>
				<th align="right" width="40%">
					Select Month:
				</th>
				<th align="left" width="12%">
					<input type="text" rel="datepicker" class="monthOnly" tabindex="3" id="to_date" style="width:66%" value="<?php echo date("F Y"); ?>" />
				</th>
				<th align="left" width="6%">
					Display:
				</th>
				<th align="left">
					<select name="grpfield"  style="width:25%" id="grpfield" onChange="updatePageBehaviour();" >
						<option value = 'INVGRP'>Invoice Wise</option>
						<option value = 'CPDGRP'>Compound Wise</option>
					</select>
				</th>				
			</tr>
		</table>
		<br />
		<div id="window_list_head">
			<strong>Receipt List</strong>
		</div>		
		<div id="content_head">
			<table border="0" cellpadding="6" cellspacing="0" width="100%">
				<tr>
				  <th width="20%" align="center">Invoice/Compound Ref</th>
				  <th width="20%" align="center">Invoice Date</th>
				  <th width="15%" align="center">No. Of Batches</th>
				  <th width="15%" align="center">Total Invoice Weight</th>
				  <th width="15%" align="center">Total Received Weight</th>
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
					<th align="right" style="width:40%;">Grand Total</th>
					<th align="right" style="width:15%;" id="bat_total" >0</th>
					<th align="right" style="width:15%;" id="inv_total" >0.000</th>					
					<th align="right" style="width:15%;" id="qty_total" >0.000</th>
					<th align="right">&nbsp;</th>
				</tr>
			</table>
		</div>
	</form>
</div>

<div id="show_bat_form" title="Show Batch Details" style="visibility:hidden">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
				<tr>
					<th width="5%" align="center">S.No</th>
					<th width="15%" align="center">Invoice Ref</th>
					<th width="15%" align="center">Invoice Date</th>					
					<th width="20%" align="center">Compound Ref</th>
					<th width="15%" align="center">Batch ID</th>
					<th width="10%" align="center">Invoice Qty</th>
					<th width="10%" align="center">Receive Qty</th>
					<th align="center">Avail. Qty</th>
				</tr>
				<tr>
				   <td colspan="8" style="padding:0px;">
						<div id="new_BatchList">
							<table width='100%'>
                                 <tr>
									<th width="5%" align="center">&nbsp;</th>
									<th width="15%" align="left">&nbsp;</th>
									<th width="15%" align="center">&nbsp;</th>									
									<th width="20%" align="left">&nbsp;</th>
									<th width="15%" align="left">&nbsp;</th>
									<th width="10%" align="right">&nbsp;</th>
									<th width="10%" align="right">&nbsp;</th>
									<th align="right">&nbsp;</th>
								</tr>
							</table>
						</div>
					</td>				
				</tr>
				<tr>
					<th class="last" colspan="5">Total</th>
					<th class="last" align="right" id="inv_tot_qty">0</th>					
					<th class="last" align="right" id="recv_tot_qty">0</th>
					<th class="last" align="right" id="avail_tot_qty">0</th>
				</tr>
            </table>
        </form>
    </div>		
</div>
