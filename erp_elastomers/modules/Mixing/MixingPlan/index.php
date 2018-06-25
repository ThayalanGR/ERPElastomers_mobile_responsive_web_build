<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Mixing Plan</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width="40%">Plan Date</th>
            <th align="left" width="15%"><input type="date" name="planDate" id="planDate"  value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?>"  /></th>
			<th align="right" width="10%">Shift</th>
            <th align="left"><select id="shift"><option>1</option><option>2</option></select></th>
        </tr>
    </table>
	</form>
</div>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Stock List</strong>
    </div>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
	<div id="window_list">
    	<div class="window_error">			
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>
<table width="98.5%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th width="15%" align="center">Total</th>
		<th width="3%" align="right" id="val_total1">0</th>
		<th width="5%" align="right" id="val_total2">0.000</th>					
		<th width="3%" align="right" id="val_total3">0</th>
		<th width="5%" align="right" id="val_total4">0.000</th>
		<th width="3%" align="right" id="val_total5">0</th>
		<th width="5%" align="right" id="val_total6">0.000</th>
		<th width="3%" align="right" id="val_total7">0</th>
		<th width="5%" align="right" id="val_total8">0.000</th>
		<th width="3%" align="right" id="val_total9">0</th>
		<th width="5%" align="right" id="val_total10">0.000</th>
		<th width="3%" align="right" id="val_total11">0</th>
		<th width="5%" align="right" id="val_total12">0.000</th>
		<th width="3%" align="right" id="val_total13">0</th>
		<th width="5%" align="right" id="val_total14">0.000</th>
		<th width="3%" align="center">&nbsp;</th>
		<th width="6%" align="center">&nbsp;</th>
		<th width="6%" align="center">&nbsp;</th>
		<th width="8%" align="center" >&nbsp;</th>
		<th align="center" id="val_total15">0</th>
	</tr>
</table>
<div >
<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
	<tr>
		<td align="right">
			<input id="submitData" type="button" value="Create Plan" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
		</td>
	</tr>		
</table>
</div>

<div style="display:none">
 	<div id="confirm_dialog"></div>
</div>
