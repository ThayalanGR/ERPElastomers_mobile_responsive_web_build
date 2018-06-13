<div id="window_list_wrapper">	
    <div id="window_list_head">
        <strong>Create eWayBill</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="0" class="new_form_table">
			<tr>
				<th align="right" width="35%">
					Document Type: 
				</th>
				<th align="left" width="10%">
					<select name="docType" id="docType" onChange="fieldChange();" >
						<option value='inv'>Invoice</option>
						<option value='dc'>Material DC</option>
						<option value='mold'>Molding DC</option>
						<option value='trim'>Trimming DC</option>						
					</select>
				</th>	
				<th align="right" width="10%">
					 For Customer: 
				</th>			
				<th align="left" >
					<input type="text" id="cusName" name="cusName" style="width:60%;" tabindex="1" onchange="updatePageBehaviour();" />
				</th>			
			</tr>
		</table>
		<br />
	</form>
	<div id="window_list_head" >
		<strong>Items List</strong>
	</div>	
		<div id="window_list">
			<div class="window_error">
				<div class="loading_txt"><span>Loading Data . . .</span></div>
			</div>
			<div id="content_body">
			</div>		
		</div>
 	<form id="exportform" name="exportform" action=""  method="post" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td width="5%" align="right" >Sup. Type: </td>
			<td width="5%">
				<select name="supType" id="supType" tabindex="2">
					<option selected></option>
					<option value=1>Supply</option>
					<option value=4>JobWork</option>
					<option value=5>For Own Use</option>
					<option value=6>Jobwork Returns</option>
				</select>
			</td>		
			<td width="5%" align="right" >Trans. Mode: </td>
			<td width="5%">
				<select name="transMode" id="transMode" tabindex="2">
					<option selected></option>
					<option value=1>Road</option>
					<option value=2>Rail</option>
					<option value=3>Air</option>
					<option value=4>Ship</option>						
				</select>
			</td>
			<td width="5%" align="right">Distance: </td>
			<td width="5%">
				<input type="text" id="transDistance" name="transDistance" style="width:95%;text-align:right" tabindex="2" />
			</td>	
			<td align="right" width="5%"> Trans. Id: </td>
			<td width="15%">
				<input type="text" id="transId" name="transId" style="width:95%;" tabindex="2" />
			</td>
			<td width="5%" align="right"> Trans. Name: </td>
			<td width="20%">
				<input type="text" id="transName" name="transName" style="width:95%;" tabindex="2" />
			</td>
			<td align="right" width="5%" >Veh. No: </td>
			<td width="10%">
				<input type="text" id="transVehNum" name="transVehNum" style="width:95%;" tabindex="2" />
			</td>					
            <td align="right">
				<input id="type" name="type" type="hidden"  />
				<input id="doctype" name="doctype" type="hidden"  />
				<input id="invids" name="invids" type="hidden"  />			
				<input id="button_submit" type="button" tabindex="2" value="Generate JSON" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" onclick="generateEwayBill();"/>
			</td>
        </tr>
    </table>
	<div id="error_msg"></div>
	</form>			
</div>

