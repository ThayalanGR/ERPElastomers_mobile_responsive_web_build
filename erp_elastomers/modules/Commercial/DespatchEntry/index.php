<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Scan the Invoice/DC</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td width='40%' align="right">Invoice/DC Reference</td>
            <td align="left" >
				<input name="invRef" id="invRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" />
			</td>
		</tr>
    </table>
	</form>
    <div id="window_list_head">
        <strong>Invoice/DC Details</strong> 
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
				<th width="5%" align="center">S.No</th>			
				<th width="8%" align="center">Inv. Ref</th>
                <th width="8%" align="center">Inv. Date</th>                
                <th width="25%" align="center">Consignee</th>
                <th width="20%" align="center">Part Number</th>
				<th width="8%" align="center">Total Qty</th>
				<th width="12%" align="center">Inv. Amount</th>
				<th width="8%" align="center">No. of Packets</th>
				<th align="center">Remove</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
        <div id="content_body"></div>		
    </div>
	<hr/>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
		<form action="" onsubmit="return false;">
			<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;">
				<tr>
					<td width="35%" align="right">
						Picked By:
					</td>
					<td width="20%">
						<input type="text" id="new_PickedBy" name="new_PickedBy" style="width:60%;" tabindex="3" /> 
					</td>
					<td width="10%">
						Vehicle / Mob. No:
					</td>
					<td width="20%">
						<input type="text" id="new_VehicleNum" name="new_VehicleNum" style="width:60%;"  tabindex="4"/> 
					</td>
					<td align="right">
						<button id="button_submit" type="submit">Update</button>
						<button id="button_cancel">Clear</button>
					</td>
				</tr>
			</table>
		</form>
	</div>		 
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
