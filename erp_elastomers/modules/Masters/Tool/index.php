<style>.strip_col3{padding-right:20px;} .strip_col5{padding-right:20px;}</style>
<div id="window_list_wrapper" >
    <div id="window_list_head">
        <strong>Tool Master</strong>
        <span id="button_add">New</span>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div id="new_item_form" class="window" title="New Tool" style="visibility:hidden;">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
    	<div id="new_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Tool Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Component Part Reference
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_ComPartRef" name="new_ComPartRef" style="width:90%;" />
                        </td>
                        <td class="row2_head">
                            Nature
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="new_Owned" name="new_Nature" value="Owned" checked="checked"/> <label for="new_Owned">Owned</label> &nbsp; <input type="radio" id="new_Customer" name="new_Nature" value="Customer"/> <label for="new_Customer">Customer</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            No.of cavities / active cavities
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_NoOfCav" name="new_NoOfCav" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
							/ <input type="text" id="new_NoOfActiveCav" name="new_NoOfActiveCav" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
                        <td class="row2_head">
                            Standard Lifts
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_stdLifts" name="new_stdLifts" style="width:40%;text-align:right" value="120" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '120')" onblur="FieldHiddenValue(this, 'out', '120')" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Molding Process
                        </td>
                        <td class="row1_cont">
							<select id="new_moldProc" name="new_moldProc" >
								<option></option>
								<option>Compression</option>
								<option>Transfer</option>							
							</select>
                        </td>
                        <td class="row2_head">
                            Mold Type
                        </td>
                        <td class="row2_cont">
                            <select id="new_moldType" name="new_moldType">
								<option></option>
								<option>Hinged</option>
								<option>Regular</option>							
							</select>
                        </td>
                    </tr>					
                    <tr>
                        <td class="row1_head">
                            Tool Life
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_ToolLife" name="new_ToolLife" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"  />
                        </td>
                       <td class="row2_head">
                            Manufacturer
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Manufact" name="new_Manufact" style="width:90%;"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Next Validation
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_NextValid" name="new_NextValid" style="width:40%;text-align:right" value="5000" class="invisible_text" readonly="true"  />
                        </td>
                        <td class="row2_head">
                            Rack
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Rack" name="new_Rack" style="width:90%" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="row1_head">
                            Tool Introduction Date
                        </td>
                        <td class="row1_cont">
                            <input type="date" id="new_IntroDate" name="new_IntroDate" style="width:60%" />
                        </td>
                        <td class="row2_head">
                           Prev Lifts Run (if Any)
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_PrevLifts" name="new_PrevLifts" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
					</tr>					
                    <tr>
                    	<td class="row1_head">
                            Strips Per Lift
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_StripsPerLift" name="new_StripsPerLift" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
                        <td class="row2_head">
                           Status
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="new_Active" name="new_Status" value="Active" checked="checked"/> <label for="new_Active">Active</label> &nbsp;<input type="radio" id="new_Inactive" name="new_Status" value="Inactive"/> <label for="new_Inactive">Inactive</label>
                        </td>
					</tr>
                    <tr>
                    	<td class="row1_head">
                            Lift / Trim Rate (Rs.)
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_LiftRate" name="new_LiftRate" style="width:30%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" /> / 
							<input type="text" id="new_TrimRate" name="new_TrimRate" style="width:30%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                        </td>
                        <td class="row2_head">
                            Blanking Method 
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="new_Autocut" name="new_BlankMethod" value="Autocut" checked="checked"/> <label for="new_Autocut">Autocut</label> &nbsp; <input type="radio" id="new_Manual" name="new_BlankMethod" value="Manual"/> <label for="new_Manual">Manual</label>
                        </td>
					</tr>					
                    <tr>
                       <td class="row1_head">
                            Blanking Type
                        </td>
                        <td class="row1_cont">
                            <input type="text" style="width:90%" id="new_BlankType" name="new_BlankType"/>
                        </td>
                       <td class="row2_head">
                            Strip Profile
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_StripProf" style="width:90%;" name="new_StripProf" />
                        </td>
                   </tr>
                    <tr>
                       <td class="row1_head">
                            Tool Remarks
                        </td>
                        <td class="row2_cont" colspan="3">
                            <textarea id="new_Remarks" name="new_Remarks" onkeydown="openAccordion(this, event, 'new', 1)" style="width:97%" ></textarea>
                        </td>
                    </tr>				   
                </table>				
            </span>
            <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                Strip Details
            </div>
            <span class="accord_content">
            	<table border="0" cellspacing="0" cellpadding="3" class="new_form_table" width="100%">
                    <tr>
                        <td width="10%" class="strip_col1">
                            Strip Weight
                        </td>
                        <td class="strip_col2" width="20%"> 
                            <input type="text" id="new_StripWeightSpec" name="new_StripWeightSpec" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                        <td width="15%" class="strip_col3" align="right">
                         	Min
                        </td>
                        <td width="20%" class="strip_col4">
                            <input type="text" id="new_StripWeightMin" name="new_StripWeightMin" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                        <td width="15%" align="right" class="strip_col5">
                            Max
                        </td>
                        <td class="strip_col6" width="20%">
                            <input type="text" id="new_StripWeightMax" name="new_StripWeightMax" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                    </tr>
                    <tr>
                        <td class="strip_col1">
                            Strip Dim1 (Thickness)
                        </td>
                        <td class="strip_col2">
                            <input type="text" id="new_StripDim1Spec" name="new_StripDim1Spec" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td style="padding-left:0px;" class="strip_col3" align="right">
                            Min
                        </td>
                        <td class="strip_col4">
                            <input type="text" id="new_StripDim1Min" name="new_StripDim1Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td class="strip_col5" align="right">
                            Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="new_StripDim1Max" name="new_StripDim1Max" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                    <tr>
                        <td class="strip_col1">
                            Strip Dim2 (Length)
                        </td>
                        <td class="strip_col2">
                            <input type="text" id="new_StripDim2Spec" name="new_StripDim2Spec" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td align="right" class="strip_col3">
                            Min
                        </td>
                        <td class="strip_col4">
                            <input type="text" id="new_StripDim2Min" name="new_StripDim2Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                    	 <td class="strip_col5" align="right">
                            Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="new_StripDim2Max" name="new_StripDim2Max" style="width:70%;text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                   	<tr>
                       <td class="strip_col1">
                            Strip Dim3 (Width)
                        </td>
                        <td class="strip_col2">
                            <input type="text" id="new_StripDim3Spec" name="new_StripDim3Spec" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td align="right" class="strip_col3">
                            Min
                        </td>
                        <td class="strip_clo4">
                            <input type="text" id="new_StripDim3Min" name="new_StripDim3Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td align="right" class="strip_col5">
                             Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="new_StripDim3Max" name="new_StripDim3Max" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                    </table>
            </span>
          </div>
        <input type="submit" onclick="getSubmitButton('new_item_form');" style="visibility:hidden;width:1px;height:1px;" />
    </form>
</div>

<div id="edit_item_form" class="window" title="Edit Component For Tool Reference : <span id='edit_ToolRef'></span>" style="visibility:hidden">
	<div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
	<form action="" onsubmit="return false;">
    	<div id="edit_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Tool Details 
           	</div>
            <span class="accord_content">
            	<table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Component Part Reference
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_ComPartRef" name="edit_ComPartRef" style="width:90%;" />
                        </td>
                        <td class="row2_head">
                            Nature
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="edit_Owned" name="edit_Nature" value="Owned" /> <label for="edit_Owned">Owned</label> &nbsp; <input type="radio" id="edit_Customer" name="edit_Nature" value="Customer"/> <label for="edit_Customer">Customer</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            No.of cavities / active cavities
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_NoOfCav" name="edit_NoOfCav" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
							 / <input type="text" id="edit_NoOfActiveCav" name="edit_NoOfActiveCav" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
                        <td class="row2_head">
                            Standard Lifts
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_stdLifts" name="edit_stdLifts" style="width:40%;text-align:right" value="120" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '120')" onblur="FieldHiddenValue(this, 'out', '120')" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Molding Process
                        </td>
                        <td class="row1_cont">
							<select id="edit_moldProc" name="edit_moldProc" >
								<option></option>
								<option>Compression</option>
								<option>Transfer</option>							
							</select>
                        </td>
                        <td class="row2_head">
                            Mold Type
                        </td>
                        <td class="row2_cont">
                            <select id="edit_moldType" name="edit_moldType">
								<option></option>
								<option>Hinged</option>
								<option>Regular</option>							
							</select>
                        </td>
                    </tr>									
                    <tr>
                        <td class="row1_head">
                            Tool Life
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_ToolLife" name="edit_ToolLife" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"  />
                        </td>
                       <td class="row2_head">
                            Manufacturer
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Manufact" name="edit_Manufact" style="width:90%;"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Next Validation
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_NextValid" name="edit_NextValid" style="width:40%;text-align:right" value="0" class="invisible_text" readonly="true"  />
                        </td>
                        <td class="row2_head">
                            Rack
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Rack" name="edit_Rack" style="width:90%" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="row1_head">
                            Tool Introduction Date
                        </td>
                        <td class="row1_cont">
                            <input type="date" id="edit_IntroDate" name="edit_IntroDate" style="width:60%" />
                        </td>
                        <td class="row2_head">
                           Prev Lifts Run (if Any)
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_PrevLifts" name="edit_PrevLifts" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
					</tr>					
                    <tr>
                    	<td class="row1_head">
                            Strips Per Lift
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_StripsPerLift" name="edit_StripsPerLift" style="width:40%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
                        <td class="row2_head">
                           Status
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="edit_Active" name="edit_Status" value="Active"/> <label for="edit_Active">Active</label> &nbsp; <input type="radio" id="edit_Inactive" name="edit_Status" value="Inactive"/> <label for="edit_Inactive">Inactive</label>
                        </td>
					</tr>
                    <tr>
                    	<td class="row1_head">
                            Lift / Trim Rate (Rs.)
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_LiftRate" name="edit_LiftRate" style="width:30%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" /> / 
							<input type="text" id="edit_TrimRate" name="edit_TrimRate" style="width:30%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                        </td>
                        <td class="row2_head">
                           Blanking Method
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="edit_Autocut" name="edit_BlankMethod" value="Autocut" checked="checked"/> <label for="edit_Autocut">Autocut</label> &nbsp; <input type="radio" id="edit_Manual" name="edit_BlankMethod" value="Manual"/> <label for="edit_Manual">Manual</label>
                        </td>
					</tr>						
                    <tr>
                       <td class="row1_head">
                            Blanking Type
                        </td>
                        <td class="row1_cont">
                            <input type="text" style="width:90%" id="edit_BlankType" name="edit_BlankType"/>
                        </td>
                       <td class="row2_head">
                            Strip Profile
                        </td>
                        <td class="row2_cont">
                            <input type="text" style="width:90%" id="edit_StripProf" name="edit_StripProf" />
                        </td>
                   </tr>
                    <tr>
                       <td class="row1_head">
                            Tool Remarks
                        </td>
                        <td class="row2_cont" colspan="3">
                            <textarea id="edit_Remarks" name="edit_Remarks" onkeydown="openAccordion(this, event, 'edit', 1)" style="width:97%" ></textarea>
                        </td>
                    </tr>						   
			 </table>
            </span>
            <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                Strip Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td width="10%" class="strip_col1">
                            Strip Weight 
                        </td>
                        <td class="strip_col2" width="20%"> 
                            <input type="text" id="edit_StripWeightSpec" name="edit_StripWeightSpec" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                        <td width="15%" class="strip_col3" align="right">
                         	Min
                        </td>
                        <td width="20%" class="strip_col4">
                            <input type="text" id="edit_StripWeightMin" name="edit_StripWeightMin" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                        <td width="15%" align="right" class="strip_col5">
                            Max
                        </td>
                        <td class="strip_col6">
                            <input type="text" id="edit_StripWeightMax" name="edit_StripWeightMax" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />&nbsp;(gm)
                        </td>
                    </tr>
                    <tr>
                        <td width="10%" class="strip_col1">
                            Strip Dim1 (Thickness)
                        </td>
                        <td width="20%" class="strip_col2">
                            <input type="text" id="edit_StripDim1Spec" name="edit_StripDim1Spec" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td width="15%" style="padding-left:0px;" class="strip_col3" align="right">
                            Min
                        </td>
                        <td width="20%" class="strip_col4">
                            <input type="text" id="edit_StripDim1Min" name="edit_StripDim1Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td width="15%" class="strip_col5" align="right">
                            Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="edit_StripDim1Max" name="edit_StripDim1Max" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                    <tr>
                        <td width="10%" class="strip_col1">
                            Strip Dim2 (Length)
                        </td>
                        <td width="20%" class="strip_col2">
                            <input type="text" id="edit_StripDim2Spec" name="edit_StripDim2Spec" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td width="15%" align="right" class="strip_col3">
                            Min
                        </td>
                        <td width="20%" class="strip_col4">
                            <input type="text" id="edit_StripDim2Min" name="edit_StripDim2Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                    	 <td width="15%" class="strip_col5" align="right">
                            Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="edit_StripDim2Max" name="edit_StripDim2Max" style="width:70%;text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                   	<tr>
                       <td width="10%" class="strip_col1">
                            Strip Dim3 (Width)
                        </td>
                        <td width="20%" class="strip_col2">
                            <input type="text" id="edit_StripDim3Spec" name="edit_StripDim3Spec" style="width:70%; text-align:right"  value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td width="15%" align="right" class="strip_col3">
                            Min
                        </td>
                        <td width="20%" class="strip_clo4">
                            <input type="text" id="edit_StripDim3Min" name="edit_StripDim3Min" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
                        </td>
                        <td width="15%" align="right" class="strip_col5">
                             Max
                        </td>
                        <td class="strip_col6">
                            	<input type="text" id="edit_StripDim3Max" name="edit_StripDim3Max" style="width:70%; text-align:right" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />(mm)
						</td>
                    </tr>
                    </table>
            </span>
          </div>
        <input type="submit" onclick="getSubmitButton('edit_item_form');" style="visibility:hidden;width:1px;height:1px;" />
    </form>
</div>

<div id="del_item_form" title="Delete Component" style="visibility:hidden">
	Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>
