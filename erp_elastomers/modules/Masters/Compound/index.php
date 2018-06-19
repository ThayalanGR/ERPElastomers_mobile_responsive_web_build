<?php
	$sql		=	"select sno, uom_short_name from tbl_uom where status>0  ";
	$resTx		=	@getMySQLData($sql);
	$uomData	=	$resTx['data'];
	$uomList	=	"<option></option>";
	foreach($uomData as $key=>$value){
		$uomList	.=	"<option value='".$value['sno']."'>".$value['uom_short_name']."</option>";
	}
	
	$sql		=	"select polyname from tbl_polymer_order  ";
	$resTx		=	@getMySQLData($sql);
	$polyData	=	$resTx['data'];
	$polyList	=	"<option></option>";
	foreach($polyData as $key=>$value){
		$polyList	.=	"<option>".$value['polyname']."</option>";
	}	
	
?>


<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Compound Master</strong>
        <span id="button_add">New</span>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="2%">&nbsp;</th>
                <th width="8.3%" align="left">Code</th>
                <th width="24.8%" align="left">Name</th>
                <th width="9%" align="left">Shrinkage</th>
                <th width="21%" align="left">Polymer</th>
                <th width="9%" align="right">Standard Batch</th>
                <th title="Select for Printing"><input id="input_select_all" type="checkbox" value="1"></input></th>
            </tr>
        </table>
	</div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
	<div >
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
				<input onclick="submitPrint();" type="button" value="Print Selected Compound Spec(s)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
			</td>
        </tr>		
    </table>
     </div>			
</div>

<div style="display:none">
    <div id="grade_list" title="Grade List" style="padding-top:10px;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="gradelist_error"></div>
        <div class="supplier_list_head">
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <th align="left" width="20px"><input type="checkbox" id="select_all_grade" onchange="selectAllGrades()" /></th>
                    <th align="left" onclick="CheckBox('select_all_grade', selectAllGrades)">Available Grade's</th>
                </tr>
            </table>
            <div class="supplier_list" id="GradeList" style="height:130px">
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th width="30px">&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </table>
            </div>
        </div>
        <input type="hidden" id="mat_id" value="" />
        <input type="hidden" id="mat_type" value="" />
    </div>
    <div class="window" id="new_item_form" title="New Compound" style="visibility:hidden">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <div id="new_item_accord" class="accordion">
                <div class="live_screen_patient_rows">
                    Compound Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Compound Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CompoundName" name="new_CompoundName" style="width:90%" tabindex="1" />
								<br/>Compound Blend? <input type="checkbox" id="new_IsBlend" name="new_IsBlend" tabindex="1" /> Is Colour? <input type="checkbox" id="new_IsColor" name="new_IsColor" tabindex="1" />
                            </td>
                            <td class="row2_head">
                                Shelf Life
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_ShelfLife" name="new_ShelfLife" style="width:50%;text-align:right" value="0" tabindex="2" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Units
                            </td>
                            <td class="row1_cont">
								<select id="new_Units" name="new_Units" style="width:30%" tabindex="3" > 
									<?php print $uomList; ?>
								</select>
								
                            </td>
                            <td class="row2_head">
                                Rack No.
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_RackNo" name="new_RackNo" style="width:70%" tabindex="4"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Polymer
                            </td>
                            <td class="row1_cont">
								<select id="new_Polymer" name="new_Polymer" style="width:60%" tabindex="5" > 
									<?php print $polyList; ?>
								</select>								
                            </td>
                            <td class="row2_head">
                                Standard Packing Qty
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_StdPckQty" name="new_StdPckQty" style="width:50%;text-align:right" tabindex="6" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Standard Batch
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MinStock" name="new_MinStock" style="width:50%;text-align:right" value="0.000" class="invisible_text" tabindex="9" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Shrinkage
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Shrinkage" name="new_Shrinkage" style="width:50%;text-align:right" tabindex="8" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                HSN Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_HSNCode" name="new_HSNCode" style="width:50%;text-align:right" tabindex="11" />
                            </td>
                            <td class="row2_head">
								Batches for Full Test
                            </td>
                            <td class="row2_cont">
								<input type="text" id="new_FullTestCount" name="new_new_FullTestCount" style="width:50%;text-align:right" tabindex="12" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" onkeydown="openAccordion(this, event, 'new', 2)" />
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows">
                    Formulation Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:6px;">
                        <span id="new_RMButton">Add</span>
                    </div>
                    <div class="supplier_list_head" style="margin-right:2px; margin-left:2px;">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th width="6.8%" align="center">Item</th>
                                    <th width="34.1%" align="left">Material</th>
                                    <th width="15.3%" align="left">Class</th>
                                    <th width="9.5%" align="center">Grade</th>
                                    <th align="right">Parts</th>
									<th width="9%"align="center">Final Chemical?</th>
                                    <th width="4.7%" align="center">#</th>
                                    <th class="last1">&nbsp;</th>
                                </tr>
                            </table>
                            <div class="supplier_list" id="new_ItemList">
                                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                    <tr>
                                        <th width="7%" align="center">&nbsp;</th>
                                        <th width="35%" align="left">&nbsp;</th>
                                        <th width="15%" align="left">&nbsp;</th>
                                        <th width="10%" align="center">&nbsp;</th>
                                        <th align="right">&nbsp;</th>
										<th width="10%"align="center">&nbsp;</th>										
                                        <th width="5%" align="right">&nbsp;</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                </span>
                <div class="live_screen_patient_rows">
                    Kneading Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Mastication Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MastTime" name="new_MastTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MastTimeMin" name="new_MastTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MastTimeMax" name="new_MastTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mastication Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MastTemp" name="new_MastTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MastTempMin" name="new_MastTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MastTempMax" name="new_MastTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mastication Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MastPres" name="new_MastPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MastPresMin" name="new_MastPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MastPresMax" name="new_MastPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_BlendTime" name="new_BlendTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_BlendTimeMin" name="new_BlendTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_BlendTimeMax" name="new_BlendTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_BlendTemp" name="new_BlendTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_BlendTempMin" name="new_BlendTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_BlendTempMax" name="new_BlendTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_BlendPres" name="new_BlendPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_BlendPresMin" name="new_BlendPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_BlendPresMax" name="new_BlendPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CBlendTime" name="new_CBlendTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CBlendTimeMin" name="new_CBlendTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_CBlendTimeMax" name="new_CBlendTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CBlendTemp" name="new_CBlendTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CBlendTempMin" name="new_CBlendTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_CBlendTempMax" name="new_CBlendTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CBlendPres" name="new_CBlendPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CBlendPresMin" name="new_CBlendPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_CBlendPresMax" name="new_CBlendPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>						
                        <tr>
                            <td class="row1_head">
                                Kneading Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_KneadTime" name="new_KneadTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_KneadTimeMin" name="new_KneadTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_KneadTimeMax" name="new_KneadTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Kneading Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_KneadTemp" name="new_KneadTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_KneadTempMin" name="new_KneadTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_KneadTempMax" name="new_KneadTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Kneading Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_KneadPres" name="new_KneadPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_KneadPresMin" name="new_KneadPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_KneadPresMax" name="new_KneadPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" onkeydown="openAccordion(this, event, 'new', 3)" />
                            </td>
                        </tr>
                   </table>
                </span>
                <div class="live_screen_patient_rows">
                    Mixing Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Mill Rolling Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MillRollTime" name="new_MillRollTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mill Rolling Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MillRollTimeMin" name="new_MillRollTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mill Rolling Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MillRollTimeMax" name="new_MillRollTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mill Rolling Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MillRollTemp" name="new_MillRollTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mill Rolling Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MillRollTempMin" name="new_MillRollTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mill Rolling Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MillRollTempMax" name="new_MillRollTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Finaling Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MixFinalTime" name="new_MixFinalTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Finaling Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MixFinalTimeMin" name="new_MixFinalTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Finaling Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MixFinalTimeMax" name="new_MixFinalTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Finaling Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MixFinalTemp" name="new_MixFinalTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Finaling Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MixFinalTempMin" name="new_MixFinalTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Finaling Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MixFinalTempMax" name="new_MixFinalTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Sheeting Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MixSheetTime" name="new_MixSheetTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Sheeting Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MixSheetTimeMin" name="new_MixSheetTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Sheeting Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MixSheetTimeMax" name="new_MixSheetTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Sheeting Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_MixSheetTemp" name="new_MixSheetTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Sheeting Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_MixSheetTempMin" name="new_MixSheetTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Sheeting Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_MixSheetTempMax" name="new_MixSheetTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" onkeydown="openAccordion(this, event, 'new', 6)" />
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows">
                    Parameter
                </div>
                <span class="accord_content">
                    <div style="text-align:right;margin-bottom:10px;margin-right:2px;">
                        <span id="add_Param">Add</span>
                    </div>
                    <div class="supplier_list_head" style="margin-left:2px;margin-right:2px;">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="20%" align="left">Parameter</th>
                                <th width="14%" align="left">Std. Ref.</th>
                                <th width="8%" align="left">Units</th>
                                <th width="14%" align="left">Test Meth.</th>
                                <th width="9%" align="left">Spec</th>
                                <th width="9%" align="left">Min.</th>
                                <th width="9%" align="left">Max.</th>
                                <th align="left">Sample Plan</th>
                                <th class="last1" align="center">&nbsp;</th>
                            </tr>
                        </table>
                        <div class="supplier_list" id="new_ParameterList">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th width="20%" align="left">&nbsp;</th>
                                    <th width="14%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="14%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th align="center">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows">
                    Customer Compound Spec
                </div>
                <span class="accord_content">
                    <div style="text-align:right;margin-bottom:10px;margin-right:2px;">
                        <span id="add_Spec">Add</span>
                    </div>
                    <div class="supplier_list_head" style="margin-left:2px;margin-right:2px;">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="30%" align="left">Spec Ref</th>
                                <th width="25%" align="left">Customer</th>
                                <th width="40%" align="left">Remarks</th>
                                <th class="last1" align="center">&nbsp;</th>
                            </tr>
                        </table>
                        <div class="supplier_list" id="new_SpecList">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th width="30%" align="left">&nbsp;</th>
                                    <th width="25%" align="left">&nbsp;</th>
                                    <th width="40%" align="left">&nbsp;</th>
                                    <th align="center">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>				
                <div class="live_screen_patient_rows">
                       Approval
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Approved By
                            </td>
                            <td class="row1_cont">
                                <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" id="new_AppUser" name="new_AppUser" style="width:90%" tabindex="19" readonly="readonly"  />
                            </td>
                            <td class="row2_head">
                                Approved Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" value="<?php echo date("d/m/Y"); ?>" id="new_AppDate" name="new_AppDate" style="width:90%" tabindex="20" readonly="readonly"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Remarks
                            </td>
                            <td colspan="3">
                                <textarea id="new_Remarks" name="new_Remarks" value="" style="width:96%;height:80px;" tabindex="21"></textarea>
                            </td>
                        </tr>
                    </table>
                </span>
            </div>
            <div class="novis_controls">
                <input type="submit" tabindex="22" onclick="getSubmitButton('new_item_form');" />
            </div>
        </form>
    </div>
    
    
    <div id="edit_item_form" title="Edit Compound" style="visibility:hidden">
        <div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
        <form action="" onsubmit="return false;">
			<input type="hidden" id="edit_CompoundId" name="edit_CompoundId" />
            <div id="edit_item_accord" class="accordion">
                <div class="live_screen_patient_rows">
                    Compound Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Compound Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CompoundName" name="edit_CompoundName" style="width:90%" tabindex="1" />
								<br/>Compound Blend? <input type="checkbox" id="edit_IsBlend" name="edit_IsBlend" tabindex="1" /> Is Colour? <input type="checkbox" id="edit_IsColor" name="edit_IsColor" tabindex="1" />
                            </td>
                            <td class="row2_head">
                                Shelf Life
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_ShelfLife" name="edit_ShelfLife" style="width:50%;text-align:right" value="0" tabindex="2" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Units
                            </td>
                            <td>
								<select id="edit_Units" name="edit_Units" style="width:30%" tabindex="3" > 
									<?php print $uomList; ?>
								</select>								
                            </td>
                            <td class="row2_head">
                                Rack No.
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_RackNo" name="edit_RackNo" style="width:70%" tabindex="4"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Polymer
                            </td>
                            <td>
								<select id="edit_Polymer" name="edit_Polymer" style="width:60%" tabindex="5" > 
									<?php print $polyList; ?>
								</select>
                            </td>
                            <td class="row2_head">
                                Standard Packing Qty
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_StdPckQty" name="edit_StdPckQty" style="width:50%;text-align:right" tabindex="6" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Standard Batch
                            </td>
                            <td>
                                <input type="text" id="edit_MinStock" name="edit_MinStock" style="width:50%;text-align:right" value="0.000" class="invisible_text" tabindex="9" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Shrinkage
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Shrinkage" name="edit_Shrinkage" style="width:50%;text-align:right" tabindex="8" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                HSN Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_HSNCode" name="edit_HSNCode" style="width:50%;text-align:right" tabindex="10" />
                            </td>
                            <td class="row2_head">
								Batches for Full Test 
                            </td>
                            <td class="row2_cont">
								<input type="text" id="edit_FullTestCount" name="edit_FullTestCount" style="width:50%;text-align:right" tabindex="11" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" onkeydown="openAccordion(this, event, 'edit', 2)"/>
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows">
                    Formulation Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_RMButton">Add</span>
                    </div>
                    <div class="supplier_list_head">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="6.8%" align="center">Item</th>
                                <th width="34.1%" align="left">Material</th>
                                <th width="15.3%" align="left">Class</th>
                                <th width="9.5%" align="center">Grade</th>
                                <th align="right">Parts</th>
								<th width="9%"align="center">Final Chemical?</th>								
                                <th width="4.7%" align="center">#</th>
                                <th class="last1">&nbsp;</th>
                            </tr>
                        </table>
                        <div class="supplier_list" id="edit_ItemList">
                            <table border="0" cellpadding="3" cellspacing="0" width="100%">
                                <tr>
                                    <th width="7%" align="center">&nbsp;</th>
                                    <th width="35%" align="left">&nbsp;</th>
                                    <th width="15%" align="left">&nbsp;</th>
                                    <th width="10%" align="center">&nbsp;</th>
                                    <th align="right">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
                                    <th width="5%" align="right">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows">
                    Kneading Details
                </div>
                <span class="accord_content"  id="kneading">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Mastication Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MastTime" name="edit_MastTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MastTimeMin" name="edit_MastTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MastTimeMax" name="edit_MastTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mastication Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MastTemp" name="edit_MastTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MastTempMin" name="edit_MastTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MastTempMax" name="edit_MastTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mastication Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MastPres" name="edit_MastPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mastication Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MastPresMin" name="edit_MastPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mastication Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MastPresMax" name="edit_MastPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_BlendTime" name="edit_BlendTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_BlendTimeMin" name="edit_BlendTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_BlendTimeMax" name="edit_BlendTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_BlendTemp" name="edit_BlendTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_BlendTempMin" name="edit_BlendTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_BlendTempMax" name="edit_BlendTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Chemical Blending Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_BlendPres" name="edit_BlendPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Chemical Blending Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_BlendPresMin" name="edit_BlendPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Chemical Blending Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_BlendPresMax" name="edit_BlendPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CBlendTime" name="edit_CBlendTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CBlendTimeMin" name="edit_CBlendTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_CBlendTimeMax" name="edit_CBlendTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CBlendTemp" name="edit_CBlendTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CBlendTempMin" name="edit_CBlendTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_CBlendTempMax" name="edit_CBlendTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Carbon Blending Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CBlendPres" name="edit_CBlendPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Carbon Blending Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CBlendPresMin" name="edit_CBlendPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Carbon Blending Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_CBlendPresMax" name="edit_CBlendPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Kneading Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_KneadTime" name="edit_KneadTime" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_KneadTimeMin" name="edit_KneadTimeMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_KneadTimeMax" name="edit_KneadTimeMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Kneading Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_KneadTemp" name="edit_KneadTemp" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_KneadTempMin" name="edit_KneadTempMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_KneadTempMax" name="edit_KneadTempMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Kneading Pres
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_KneadPres" name="edit_KneadPres" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Kneading Pres Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_KneadPresMin" name="edit_KneadPresMin" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Kneading Pres Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_KneadPresMax" name="edit_KneadPresMax" style="width:80%;text-align:right" tabindex="12" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')"  onkeydown="openAccordion(this, event, 'edit', 3)"/>
                            </td>
                        </tr>
                   </table>
                </span>
                <div class="live_screen_patient_rows">
                    Mixing Details
                </div>
                <span class="accord_content" id="mixing">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Mill Rolling Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MillRollTime" name="edit_MillRollTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mill Rolling Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MillRollTimeMin" name="edit_MillRollTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mill Rolling Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MillRollTimeMax" name="edit_MillRollTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mill Rolling Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MillRollTemp" name="edit_MillRollTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mill Rolling Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MillRollTempMin" name="edit_MillRollTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mill Rolling Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MillRollTempMax" name="edit_MillRollTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Finaling Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MixFinalTime" name="edit_MixFinalTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Finaling Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MixFinalTimeMin" name="edit_MixFinalTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Finaling Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MixFinalTimeMax" name="edit_MixFinalTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Finaling Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MixFinalTemp" name="edit_MixFinalTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Finaling Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MixFinalTempMin" name="edit_MixFinalTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Finaling Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MixFinalTempMax" name="edit_MixFinalTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Sheeting Time
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MixSheetTime" name="edit_MixSheetTime" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Sheeting Time Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MixSheetTimeMin" name="edit_MixSheetTimeMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Sheeting Time Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MixSheetTimeMax" name="edit_MixSheetTimeMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Mix Sheeting Temp
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_MixSheetTemp" name="edit_MixSheetTemp" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row2_head">
                                Mix Sheeting Temp Min
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_MixSheetTempMin" name="edit_MixSheetTempMin" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" />
                            </td>
                            <td class="row3_head">
                                Mix Sheeting Temp Max
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_MixSheetTempMax" name="edit_MixSheetTempMax" style="width:80%;text-align:right" tabindex="13" value="0.000" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" onkeydown="openAccordion(this, event, 'edit', 6)" />
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows">
                    Parameter
                </div>
                <span class="accord_content">
                    <div style="text-align:right;margin-bottom:10px;margin-right:2px;">
                        <span id="edit_Param">Add</span>
                    </div>
                    <div class="supplier_list_head" style="margin-left:2px;margin-right:2px;">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="20%" align="left">Parameter</th>
                                <th width="14%" align="left">Std. Ref.</th>
                                <th width="8%" align="left">Units</th>
                                <th width="14%" align="left">Test Meth.</th>
                                <th width="9%" align="left">Spec</th>
                                <th width="9%" align="left">Min.</th>
                                <th width="9%" align="left">Max.</th>
                                <th align="left">Sample Plan</th>
                                <th class="last1" align="center">&nbsp;</th>
                            </tr>
                        </table>
                        <div class="supplier_list" id="edit_ParameterList">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th width="20%" align="left">&nbsp;</th>
                                    <th width="14%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="14%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th width="9%" align="left">&nbsp;</th>
                                    <th align="center">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows">
                    Customer Compound Spec
                </div>
                <span class="accord_content">
                    <div style="text-align:right;margin-bottom:10px;margin-right:2px;">
                        <span id="edit_Spec">Add</span>
                    </div>
                    <div class="supplier_list_head" style="margin-left:2px;margin-right:2px;">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="30%" align="left">Spec Ref</th>
                                <th width="25%" align="left">Customer</th>
                                <th width="40%" align="left">Remarks</th>
                                <th class="last1" align="center">&nbsp;</th>
                            </tr>
                        </table>
                        <div class="supplier_list" id="edit_SpecList">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th width="30%" align="left">&nbsp;</th>
                                    <th width="25%" align="left">&nbsp;</th>
                                    <th width="40%" align="left">&nbsp;</th>
                                    <th align="center">&nbsp;</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>					
                <div class="live_screen_patient_rows">
                       Approval
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Approved By
                            </td>
                            <td class="row1_cont">
                                <input type="text" value="" id="edit_AppUser" name="edit_AppUser" style="width:90%" tabindex="19" readonly="readonly" />
                            </td>
                            <td class="row2_head">
                                Approved Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" value="" id="edit_AppDate" name="edit_AppDate" style="width:90%" tabindex="20" readonly="readonly" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Remarks
                            </td>
                            <td colspan="3">
                                <textarea id="edit_Remarks" name="edit_Remarks" value="" style="width:96%;height:80px;" tabindex="21"></textarea>
                            </td>
                        </tr>
                    </table>
                </span>
            </div>
            <div class="novis_controls">
                <input type="submit" tabindex="22"  onclick="getSubmitButton('edit_item_form');" />
            </div>
        </form>
    </div>
    
    <div id="del_item_form" title="Delete Compound" style="visibility:hidden">
        Are you Sure to Delete ?
        <div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
    </div>
</div>