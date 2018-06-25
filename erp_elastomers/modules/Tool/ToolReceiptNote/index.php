<?php
	global $tq_std_toolsize;
	$toolstdsizelist	=	"";
	for($ct=0;$ct<count($tq_std_toolsize);$ct++){
		$toolstdsizelist	.=	"<option>".$tq_std_toolsize[$ct]."</option>";
	}
?>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Tool Receipt Note</strong>
    </div>
     <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="grn_popup" title="GRN Issue" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%">
				<tr class='content_rows_light'>
					<td align="center" width="35%">Supplier Name</td>
					<td align="center" width="20%">Part Number</td>					
					<td align="center">Description</td>			
				</tr>
				<tr class='content_rows_dark'>
					<th align="left" id="grn_supname"></th>
					<th align="left" id="grn_partnum"></th>					
					<th align="left" id="grn_partdesc"></th>				
				</tr>
				<tr class='content_rows_light'>
					<td align="left">P.O reference</td>
					<th align="left" id="po_purid"></th>
					<th align="left" id="po_purdate"></th>
				</tr>
				<tr class='content_rows_dark'>
					<td>Invoice</td>
					<td align="left"><input type="text" id="grn_invref" style="width:95%" tabindex="1" /></td>
					<td><input type="date" name="grn_invdate" tabindex="2" id="grn_invdate" style="width:50%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>"  /></td>
				</tr>					
				<tr class='content_rows_light'>
					<th>Item</th>
					<th>P.O Advised Value</th>
					<th>Actual Value</th>
				</tr>
				<tr class='content_rows_dark'>
					<td>Tool Cost</td>
					<th id="po_toolamount" align="right"></td>
					<td><input type="text" id="grn_toolamount" style="width:40%;text-align:right;"  value='0.00' tabindex="3" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" /></td>					
				</tr>
				<tr class='content_rows_light'>
					<td>Invoice Amount</td>
					<td align="right">&mdash;</td>
					<td><input type="text" id="grn_invamount" style="width:40%;text-align:right;"  value='0.00' tabindex="4" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" /></td>					
				</tr>			
				<tr class='content_rows_dark'>
					<td>Received Date</td>
					<th align="left" id="po_expdate"></th>				
					<td>
						<input type="date" tabindex="5" name="grn_recvdate" id="grn_recvdate" style="width:50%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>"  />
					</td>
				</tr>			
				<tr class='content_rows_light'>
					<td>Tool Size (mmXmm)</td>
					<th align="left" id="po_toolsize"></th>
					<td>
						<select id="grn_toolsize" tabindex="6">
							<?php echo $toolstdsizelist;?>
							<option>Non-Standard</option>
						</select>
						<input hidden="hidden" type="text" id="grn_tool_size" name="grn_tool_size" style="width:40%;" tabindex="6" class="invisible_text" value='250X250' onfocus="FieldHiddenValue(this, 'in', '250mmX250mm')" onblur="FieldHiddenValue(this, 'out', '250mmX250mm')"/>
					</td>				
				</tr>	
				<tr class='content_rows_dark'>
					<td>Cavities</td>
					<th id="po_cavities" align="right"></th>
					<td>
						<input type="text" id="grn_cavities" style="width:20%;text-align:right;" tabindex="7"   class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/>
					</td>			
				</tr>				
				<tr class='content_rows_light'>
					<td>Mold Material</td>
					<th id="po_moldmatl" align="left"></th>
					<td>
                        <select id="grn_moldmatl" tabindex="8">
							<option></option>
							<option>OHNS</option>
							<option>P20</option>							
						</select>
					</td>					
				</tr>
				<tr class='content_rows_dark'>
					<td>Moulding Process</td>
					<th id="po_moldproc" align="left"></th>
					<td>
                        <select id="grn_moldproc" tabindex="9">
							<option></option>
							<option>Compression</option>
							<option>Transfer</option>							
						</select>
					</td>				
				</tr>
				<tr class='content_rows_light'>
					<td>Moulding Type</td>
					<th id="po_moldtype" align="left"></th>
					<td>
                        <select id="grn_moldtype" tabindex="10">
							<option></option>
							<option>Hinged</option>
							<option>Regular</option>							
						</select>
					</td>			
				</tr>			
				<tr class='content_rows_dark'>
					<td>Cavity Engravement</td>
					<th id="po_cavengrave" align="left"></th>
					<td>
						<input type="radio" name='cav_engrave_opt' tabindex="11" id="engrave_yes" value="1" > <label for="engrave_yes">Yes</label> 
						<input type="radio" name='cav_engrave_opt' tabindex="11" id="engrave_no" value="0" checked="checked"> <label for="engrave_no">No</label>
					</td>		
				</tr>
				<tr class='content_rows_light'>
					<td>Remarks</td>
					<th id="po_remarks" align="left"></th>
					<td><textarea id="grn_remarks" tabindex="12"></textarea></td>					
				</tr>
			</table>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('grn_popup');" />
            </div>			
        </form>
    </div>
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
