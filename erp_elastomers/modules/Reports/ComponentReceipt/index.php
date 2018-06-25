<div id="window_list_wrapper" class="filter-table" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Component Receipt</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" width="100%" class="new_form_table">
			<tr>
				<th align="right" width="15%">
					From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="15%">
					To:
				</th>
				<th align="left" >
					<input type="date" tabindex="2" id="to_date"  value="<?php echo date("Y-m-d",mktime(0, 0, 0, date("m")+1  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="left" width="6%">
					Display:
				</th>
				<th align="left">
					<select name="grpfield"  style="width:35%" id="grpfield" onChange="updatePageBehaviour();" >
						<option value = 'INVGRP'>Invoice Wise</option>
						<option value = 'CMPDGRP'>Component Wise</option>
					</select>
				</th>				
			</tr>
		</table>
		<br />
		<div id="window_list_head">
			<strong>Receipt List</strong>
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
				<th align="right">&nbsp;</th>			
					<th align="center" style="width:20%;">Grand Total</th>
				    <th align="right" style="width:20%;" id="bat_total" >0</th>			
					<th align="right" style="width:20%;" id="inv_total" >0</th>
					<th align="right">&nbsp;</th>
				</tr>
			</table>
		</div>
	</form>
</div>

<div id="show_key_form" title="Show Key Details" style="visibility:hidden">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
				<tr>
					<th width="5%" align="center">S.No</th>
					<th width="15%" align="center">Invoice Ref</th>
					<th width="15%" align="center">Invoice Date</th>					
					<th width="20%" align="center">Component Ref</th>
					<th width="15%" align="center">Key ID</th>
					<th align="right">ReceivedQty</th>
				</tr>
				<tr>
				   <td colspan="8" style="padding:0px;">
						<div id="new_BatchList">
							<table width='100%'>
                                 <tr>
									<th width="5%" align="center">&nbsp;</th>
									<th width="15%" align="center">&nbsp;</th>
									<th width="15%" align="center">&nbsp;</th>									
									<th width="20%" align="center">&nbsp;</th>
									<th width="15%" align="center">&nbsp;</th>
									<th align="left">&nbsp;</th>
								</tr>
							</table>
						</div>
					</td>				
				</tr>
				<tr>
					<th class="last" colspan="5">Total</th>			
					<th class="last" align="right" id="recv_tot_qty">0</th>
				</tr>
            </table>
        </form>
    </div>		
</div>
