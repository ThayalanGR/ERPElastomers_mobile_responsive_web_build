<?php
	global $tq_std_toolsize;
	$toolstdsizelist	=	"";
	for($ct=0;$ct<count($tq_std_toolsize);$ct++){
		$toolstdsizelist	.=	"<option>".$tq_std_toolsize[$ct]."</option>";
	}
	$sql			=	" select polyName from tbl_polymer_order order by polyName ";
	$polydat		=	@getMySQLData($sql);
	$polymerlist	=	"<option></option>";	
	if($polydat['count'] > 0 )
	{
		$polymer		=	$polydat['data'];
		foreach($polymer as $key=>$value){
			$polymerlist	.=	"<option>".$value['polyName']."</option>";
		}	
	}
?>

<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Check Feasibility</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="tl_popup" title="Tool Feasibility Check" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error_tl"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="30%">
						<span id="tool_label"></span>
					</td>
					<td nowrap>
						<input type="radio" name='tool_entry_opt' tabindex="1" class="tl_option" id="tool_yes" value="1" checked="checked"> <label for="tool_yes"><span id="lbl_tool_yes"></span></label> 
						<input type="radio" name='tool_entry_opt' tabindex="2" class="tl_option" id="tool_no" value="0"> <label for="tool_no"><span id="lbl_tool_no"></span></label>
						<span class="invoice_heading tool_yes"> <b>Select Tool: </b><input type="text" id="tl_toolref" name="tl_toolref" style="width:40%" tabindex="3" onblur="if(this.value==''){setToolDetails()}"/> </span>						
					</td>					
				</tr>
				<tr>
					<td>
						Platten Available?
					</td>
					<td>
						<input type="radio" name='platten_entry_opt' tabindex="4" id="platten_yes" value="1" checked="checked"> <label for="platten_yes">Yes</label> 
						<input type="radio" name='platten_entry_opt' tabindex="5" id="platten_no" value="0"> <label for="platten_no">No</label>
					</td>
				</tr>
				<tr>
					<td>
						Suggested Tool Size (len X br) (mm)
					</td>
                    <td align='left' >
						<select id="raise_toolsize" style="width:25%;" tabindex="6">
							<option></option>
							<?php echo $toolstdsizelist;?>
							<option>Non-Standard</option>
						</select>
						<input hidden="hidden" type="text" id="raise_tool_size" name="raise_tool_size" style="width:25%;" tabindex="7" class="invisible_text" value='250X250' onfocus="FieldHiddenValue(this, 'in', '250mmX250mm')" onblur="FieldHiddenValue(this, 'out', '250mmX250mm')"/>						
                    </td>					
				</tr>
				<tr>
					<td>
						Suggested No. of Cavities
					</td>
					<td>
						<input type="text" id="tool_cavs" name="tool_cavs" style="width:10%;text-align:right;" tabindex="8" value=0 /> 
					</td>
				</tr>
				<tr>
					<td>
						Est. Product Wgt (gm)
					</td>
					<td>
						<input type="text" id="tool_prodwgt" name="tool_prodwgt" style="width:15%;text-align:right;" tabindex="9" value=0 /> 
					</td>
				</tr>
				<tr>
					<td>
						Est. Blank Wgt (gm)
					</td>
					<td>
						<input type="text" id="tool_blankwgt" name="tool_blankwgt" style="width:15%;text-align:right;" tabindex="10" value=0 /> 
					</td>
				</tr>				
				<tr>
					<td>
						Moulding Process
					</td>
					<td>
						<select id="tool_mouldproc" name="tool_mouldproc" style="width:25%;" tabindex="11">
							<option></option>
							<option>Compression</option>
							<option>Transfer</option>
						</select> 
					</td>
				</tr>
				<tr>
					<td>
						Mould Type
					</td>
					<td>
                        <select id="tool_mouldtype" name="tool_mouldtype" tabindex="12" style="width:25%">
							<option></option>
							<option>Hinged</option>
							<option>Regular</option>							
						</select>						
					</td>
				</tr>
				<tr>
					<td>
						Insert Used?
					</td>
					<td>
						<input type="radio" name='insert_entry_opt' tabindex="4" id="insert_yes" value="1" > <label for="insert_yes">Yes</label> 
						<input type="radio" name='insert_entry_opt' tabindex="5" id="insert_no" value="0" checked="checked"> <label for="insert_no">No</label>
					</td>
				</tr>				
				<tr>
					<td>
						Remarks                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="tool_remarks" name="tool_remarks" tabindex="13"></textarea>							
					</td>						
				</tr>							
			</table>
        </form>
    </div>
    <div id="cpd_popup" title="Compound Feasibility Check">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error_cpd"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="30%">
						Existing Compound Suitable?
					</td>
					<td>
						<input type="radio" name='cpd_entry_opt' tabindex="1" class="cpd_option" id="cpd_yes" value="1" checked="checked"> <label for="cpd_yes">Yes</label> 
						<input type="radio" name='cpd_entry_opt' tabindex="2" class="cpd_option" id="cpd_no" value="0"> <label for="cpd_no">No</label>
						<span class="invoice_heading cpd_yes"> <b>Select Compound: </b><input type="text" id="cpd_cpdref" name="cpd_cpdref" style="width:30%" tabindex="3" onblur="if(this.value==''){setCpdDetails()}"/> </span>
					</td>					
				</tr>
				<tr>
					<td>
						Suggested Base Polymer
					</td>
					<td>
						<select id="cpd_polymer" name="cpd_polymer" tabindex="4" > 
							<?php echo $polymerlist; ?>
						</select>
					</td>
				</tr>				
				<tr>
					<td>
						In-House Testing?
					</td>
					<td>
						<input type="radio" name='test_entry_opt' tabindex="5" id="test_yes" value="1" checked="checked"> <label for="test_yes">Yes</label> 
						<input type="radio" name='test_entry_opt' tabindex="6" id="test_no" value="0"> <label for="test_no">No</label>
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Curing Temp (&deg;C)
					</td>
					<td>
						<input type="text" id="cpd_cure_temp" name="cpd_cure_temp" style="width:10%;text-align:right;" tabindex="7" value='160' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '160')" onblur="FieldHiddenValue(this, 'out', '160')" /> 
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Curing Time (Sec)
					</td>
					<td>
						<input type="text" id="cpd_cure_time" name="cpd_cure_time" style="width:10%;text-align:right;" tabindex="8" value='150' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '150')" onblur="FieldHiddenValue(this, 'out', '150')" /> 
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Curing Pressure (kg/cm<sup>2</sup>)
					</td>
					<td>
						<input type="text" id="cpd_cure_press" name="cpd_cure_press" style="width:10%;text-align:right;" tabindex="9" value='200' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '200')" onblur="FieldHiddenValue(this, 'out', '200')"/> 
					</td>
				</tr>					
				<tr>
					<td>
						Remarks                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="cpd_remarks" name="cpd_remarks" tabindex="10"></textarea>							
					</td>						
				</tr>							
			</table>
        </form>	
	</div>
    <div id="prod_popup" title="Production Feasibility Check">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error_prod"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr width="30%">
					<td>
						Sugg. Lift Rate (Rs)
					</td>
					<td>
						<input type="text" id="prod_lift_rate" name="prod_lift_rate" style="width:15%;text-align:right;" tabindex="1" value='0' /> 
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Trim Rate (Rs)
					</td>
					<td>
						<input type="text" id="prod_trim_rate" name="prod_trim_rate" style="width:15%;text-align:right;" tabindex="2" value='0' /> 
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Inspection Rate (Rs)
					</td>
					<td>
						<input type="text" id="prod_insp_rate" name="prod_insp_rate" style="width:15%;text-align:right;" tabindex="3" value='0' /> 
					</td>
				</tr>
				<tr>
					<td>
						Sugg. Rejection Rate(%)
					</td>
					<td>
						<input type="text" id="prod_rej_rate" name="prod_rej_rate" style="width:15%;text-align:right;" tabindex="4" value='0' /> 
					</td>
				</tr>					
				<tr>
					<td>
						Remarks                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="prod_remarks" name="prod_remarks" tabindex="5"></textarea>							
					</td>						
				</tr>							
			</table>
        </form>	
	</div>
    <div id="final_popup" title="Approve Feasibility">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error_final"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<td width="30%">
						Remarks                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="final_remarks" name="final_remarks" tabindex="1"></textarea>							
					</td>						
				</tr>							
			</table>
        </form>	
	</div>	
	<div id="confirm_dialog"></div>
</div>
