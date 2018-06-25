<?php
	global $tq_mixing_cost,$tq_tool_life,$tq_mould_lab_cost_hr,$tq_trim_lab_cost_hr,$tq_insp_lab_cost_hr,$tq_reject_percent,$tq_admin_percent,$tq_profit_percent	
?>

<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Generate Quote</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
     <div id="quote_popup" title="Generate Quote" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="30%">
						Sugg. Compound 
					</td>
					<td width="20%" id="gq_sugg_compound">
						&nbsp;
					</td>					
					<td width="30%">
						Sugg. Polymer
					</td>
					<td id="gq_sugg_polymer">
						&nbsp;
					</td>						
				</tr>			
				<tr>
					<td>
						Compound Cost /Kg (<span id="gq_cpd_cost_act" style="font-weight:bold">0.00</span>)
					</td>
					<td>
						<input type="text" id="gq_comp_cost" name="gq_comp_cost" style="width:50%;text-align:right;" tabindex="1" value=0.00 onkeyup="calculateCost('matl');"/>						
					</td>					
					<td>
						Mixing Cost /Kg 
					</td>
					<td>
						<input type="text" id="gq_mix_cost" name="gq_mix_cost" style="width:50%;text-align:right;" tabindex="2" value=<?php echo $tq_mixing_cost ?> onkeyup="calculateCost('matl');"/>						
					</td>					
				</tr>
				<tr>
					<td>
						Weight/Product (Kg) (<span id="gq_sugg_prod_wgt" style="font-weight:bold">0.0000</span>)
					</td>
					<td>
						<input type="text" id="gq_prod_wgt" name="gq_prod_wgt" style="width:50%;text-align:right;" tabindex="3" value=0.000 />
					</td>
					<td>
						Blank Weight/Product (Kg) (<span id="gq_sugg_blank_wgt" style="font-weight:bold">0.0000</span>)
					</td>
					<td>
						<input type="text" id="gq_blank_wgt" name="gq_blank_wgt" style="width:50%;text-align:right;" tabindex="4" value=0.000 onkeyup="calculateCost('matl');"/>
					</td>
				</tr>
				<tr>
					<td>
						Insert Applicable? (<span id="gq_insert_used" style="font-weight:bold">No</span>)
					</td>
					<td>
						<select name='gq_insert_opt' id='gq_insert_opt' style="width:85%;" tabindex="5" onchange="calculateCost('matl');calculateCost('manu');">
							<option value="0">Not Applicable</option>						
							<option value="1">Supplied By Customer</option> 
							<option value="2">Supplied By Us</option>
						</select>
					</td>					
					<td>
						Insert Cost/Product (Rs)
					</td>
					<td>
						<input type="text" id="gq_ins_cost" name="gq_ins_cost" style="width:50%;text-align:right;" tabindex="6" value=0.00 onkeyup="calculateCost('matl');"/>						
					</td>	
				</tr>			
				<tr>					
					<td colspan="3" align="right">
						<b>Net Material Cost/Product (A)</b>
					</td>
					<td id="gq_matl_cost" align="right" style="font-size:12px;font-weight: bold;">
						0.00
					</td>
				</tr>
				<tr class="content_rows_dark">
					<td align="center"><b>Operation (Sugg. Cost)</b></td>
					<td align="center"><b>Output/Hour (Nos)</b></td>
					<td align="center"><b>Cost/Hour (Rs)</b></td>
					<td align="center"><b>Cost/Unit (Rs)</b></td>
				</tr>
				<tr>
					<td>Moulding (<span id="gq_sugg_mold_cost" style="font-weight:bold">0.00</span>) Lifts/Hr : <input type="text" id="gq_mold_hr_lifts" name="gq_mold_hr_lifts" style="width:10%;text-align:right;" tabindex="7" value=0 onkeyup="calculateMoldOutput();" /></td>
					<td><input type="text" id="gq_mold_output" name="gq_mold_output" style="width:50%;text-align:right;" value=0 readonly='true'/></td>
					<td align="center"><input type="text" id="gq_mold_hr_cost" name="gq_mold_hr_cost" style="width:30%;text-align:right;" tabindex="8" value=<?php print @number_format($tq_mould_lab_cost_hr,2) ?> onkeyup="calculateCost('manu');" /></td>
					<td id="gq_mold_cost" align="right">0.00</td>
				</tr>
				<tr>
					<td>Trimming (<span id="gq_sugg_trim_cost" style="font-weight:bold">0.00</span>)</td>
					<td><input type="text" id="gq_trim_output" name="gq_trim_output" style="width:50%;text-align:right;" tabindex="9" value=0 onkeyup="calculateCost('manu');" /></td>
					<td align="center"><input type="text" id="gq_trim_hr_cost" name="gq_trim_hr_cost" style="width:30%;text-align:right;" tabindex="10" value=<?php print @number_format($tq_trim_lab_cost_hr,2) ?>  onkeyup="calculateCost('manu');" /></td>
					<td id="gq_trim_cost" align="right">0.00</td>
				</tr>
				<tr>
					<td>Inspection (<span id="gq_sugg_insp_cost" style="font-weight:bold">0.00</span>)</td>
					<td><input type="text" id="gq_insp_output" name="gq_insp_output" style="width:50%;text-align:right;" tabindex="11" value=0 onkeyup="calculateCost('manu');" /></td>
					<td align="center"><input type="text" id="gq_insp_hr_cost" name="gq_insp_hr_cost" style="width:30%;text-align:right;" tabindex="12" value=<?php print @number_format($tq_insp_lab_cost_hr,2) ?>  onkeyup="calculateCost('manu');" /></td>
					<td id="gq_insp_cost" align="right" >0.00</td>
				</tr>
				<tr>
					<td>Insert Preparation</td>
					<td><input type="text" id="gq_ins_prep_output" name="gq_ins_prep_output" style="width:50%;text-align:right;" tabindex="13" value=0 onkeyup="calculateCost('manu');" /></td>
					<td align="center"><input type="text" id="gq_ins_prep_hr_cost" name="gq_ins_prep_hr_cost" style="width:30%;text-align:right;" tabindex="14" value=<?php print @number_format($tq_insp_lab_cost_hr,2) ?>  onkeyup="calculateCost('manu');" /></td>
					<td id="gq_ins_prep_cost" align="right" >0.00</td>
				</tr>
				<tr>
					<td>Adhesive Application</td>
					<td><input type="text" id="gq_adh_output" name="gq_adh_output" style="width:50%;text-align:right;" tabindex="15" value=0 onkeyup="calculateCost('manu');" /></td>
					<td align="center"><input type="text" id="gq_adh_hr_cost" name="gq_adh_hr_cost" style="width:30%;text-align:right;" tabindex="16" value=<?php print @number_format($tq_insp_lab_cost_hr,2) ?>  onkeyup="calculateCost('manu');" /></td>
					<td id="gq_adh_cost" align="right" >0.00</td>
				</tr>				
				<tr>
					<td colspan="3" align="right">
						<b>Net Conversion Cost (B)</b>
					</td>
					<td  id="gq_manu_cost" align="right" style="font-size:12px;font-weight: bold;">
						0.00
					</td>
				</tr>
				<tr>
					<td>
						Inventory Carrying Cost 
					</td>
					<td colspan="2">
						<input type="text" id="gq_invent_per" name="gq_invent_per" style="width:20%;text-align:right;" tabindex="17" value=2.00 onkeyup="calculateCost('misc');" /> % of (A)
					</td>					
					<td  id="gq_invent_cost" align="right">
						0.00
					</td>					
				</tr>				
				<tr>
					<td>
						Rejection (<span id="gq_sugg_rej_per" style="font-weight:bold">0.0</span>) 
					</td>
					<td colspan="2">
						<input type="text" id="gq_rej_per" name="gq_rej_per" style="width:20%;text-align:right;" tabindex="18" value=<?php print @number_format($tq_reject_percent,2) ?> onkeyup="calculateCost('misc');" /> % of (A+B)
					</td>					
					<td  id="gq_rej_cost" align="right">
						0.00
					</td>					
				</tr>	
				<tr>
					<td>
						Packing &amp; Forwarding
					</td>
					<td colspan="2">
						<input type="text" id="gq_admin_per" name="gq_admin_per" style="width:20%;text-align:right;" tabindex="19" value=2.50 onkeyup="calculateCost('misc');" /> % of (A+B)
					</td>
					<td id="gq_admin_cost" align="right">
						0.00
					</td>					
				</tr>	
				<tr>
					<td>
						Freight/Unit
					</td>
					<td colspan="3" align="right">
						<input type="text" id="gq_freight_cost" name="gq_freight_per" style="width:10%;text-align:right;" tabindex="20" value=0.00 onkeyup="calculateCost('misc');" /> 
					</td>	
				</tr>				
				<tr>
					<td>
						Profit &amp; Other Overheads  
					</td>
					<td colspan="2">
						<input type="text" id="gq_profit_per" name="gq_profit_per" style="width:20%;text-align:right;" tabindex="21" value=<?php print @number_format($tq_profit_percent,2) ?> onkeyup="calculateCost('misc');" /> % of (A+B)
					</td>
					<td id="gq_profit_cost" align="right">
						0.00
					</td>
				</tr>
				<tr>					
					<td colspan="3" align="right">
						<b>Net Miscellanous Cost (C)</b>
					</td>
					<td  id="gq_misc_cost" align="right" style="font-size:12px;font-weight: bold;">
						0.00
					</td>
				</tr>
				<tr>
					<td>
						Tool Cost Charged To:
					</td>
					<td>
						<select name='gq_tooldev_opt' id='gq_tooldev_opt' style="width:85%;" tabindex="22" onchange="calculateCost('amor');">
							<option value="0">Your Account</option> 
							<option value="1">Our Account</option>
							<option value="2">Amortised</option>
						</select>					
					</td>
					<td>
						Tool Cost (Rs)
					</td>
					<td>
						<input type="text" id="gq_tool_cost" name="gq_tool_cost" style="width:50%;text-align:right;" tabindex="23" value=0.00 onkeyup="calculateCost('amor');"/> 
					</td>					
				</tr>
				<tr>
					<td>
						No. of Cavities (<span id="gq_sugg_tool_cavs" style="font-weight:bold">0</span>)
					</td>
					<td>
						<input type="text" id="gq_tool_cavs" name="gq_tool_cavs" style="width:50%;text-align:right;" tabindex="24" value=0 onkeyup="calculateCost('amor');"/> 
					</td>					
					<td>
						Tool Amortisation Period Life (Lifts)
					</td>
					<td>
						<input type="text" id="gq_tool_life" name="gq_tool_life" style="width:50%;text-align:right;" tabindex="25" value=0 onkeyup="calculateCost('amor');"/> 
					</td>
				</tr>				
				<tr>					
					<td colspan="3" align="right">
						<b>Amortization Cost (D)</b> 
					</td>
					<td id="gq_amort_cost" align="right" style="font-size:12px;font-weight: bold;">
						0.00
					</td>
				</tr>				
				<tr>
					<td colspan="3" align="right">
						<b>Total Cost (A+B+C+D)</b>
					</td>
					<td  id="gq_final_cost" align="right" style="font-size:12px;font-weight: bold;">
						0.00
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="content_rows_dark">
						<b>Development Schedule &amp; Commitment</b>
					</td>
				</tr>	
				<tr>				
					<td>
						Material Sample
					</td>
					<td>
						<input type="text" id="gq_matl_sample" name="gq_matl_sample" style="width:90%;" tabindex="26" value="One Week After Receipt of Your PO" />
					</td>
					<td>
						Component Sample
					</td>
					<td>
						<input type="text" id="gq_comp_sample" name="gq_comp_sample" style="width:90%;" tabindex="26" value="One Week After Receipt of Your PO" />
					</td>
				</tr>
				<tr>				
					<td>
						Pilot Lot
					</td>
					<td>
						<input type="text" id="gq_pilot_lot" name="gq_pilot_lot" style="width:90%;" tabindex="26" value="10 Days After Approval of Sample" />
					</td>
					<td>
						Regular Supply
					</td>
					<td>
						<input type="text" id="gq_reg_supply" name="gq_reg_supply" style="width:90%;" tabindex="26" value="10 Days After Approval of Pilot Lot" />
					</td>
				</tr>						
				<tr>
					<td>
						Remarks                             
					</td>
					<td colspan="3">
						<textarea style="width:95%;height:40px;" id="gq_remarks" name="gq_remarks" tabindex="27"></textarea>							
					</td>						
				</tr>							
			</table>
        </form>
    </div>	
	<div id="confirm_dialog"></div>
</div>
