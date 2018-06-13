<?php
	$sql		=	"select sno, uom_short_name from tbl_uom where status>0  ";
	$resTx		=	@getMySQLData($sql);
	$uomData	=	$resTx['data'];
	$uomList	=	"<option></option>";
	foreach($uomData as $key=>$value){
		$uomList	.=	"<option value='".$value['sno']."'>".$value['uom_short_name']."</option>";
	}
	
	$sql		=	"select sno, class_short_name from tbl_class where status>0  ";
	$resTx		=	@getMySQLData($sql);
	$classData	=	$resTx['data'];
	$classList	=	"<option></option>";
	foreach($classData as $key=>$value){
		$classList	.=	"<option value='".$value['sno']."'>".$value['class_short_name']."</option>";
	}
	
?>
<style>
.accord_content{padding-bottom:12px;}
#new_item_form .row1_cont, #edit_item_form .row1_cont{width:28%;}
</style>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Raw Material Master</strong>
        <span id="button_add">New</span>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr class="ram_rows_head">
                <th width="2%" align="left" title="Select for Printing"><input id="input_select_all" type="checkbox" value="1"></input></th>			
                <th width="8%" align="left">Code</th>
                <th width="20%" align="left">Name</th>    
                <th width="15%" align="left">Grade</th>
                <th width="15%" align="left">Class</th>
                <th width="10%" align="right">Manufacturer</th>
                <th width="10%" align="right">Reorder Qty.</th>
                <th width="5%">Unit</th>
                <th>#</th>
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
					<input onclick="submitPrint();" type="button" value="Print Selected Raw Material Spec(s)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
			</td>
        </tr>		
    </table>
     </div>			
</div>

<div class="window" id="new_item_form" title="New Raw Material" style="visibility:hidden">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
        <div id="new_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Raw Material
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Raw Material Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_RMName" name="new_RMName" style="width:90%" tabindex="1" />
                        </td>
                        <td class="row2_head">
                            Rack No
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_RackNo" name="new_RackNo" style="width:50%;" tabindex="2"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            UoM
                        </td>
                        <td class="row1_cont" style="height:25px;" id="new_units_row">
                            <div id="new_units_text">&nbsp;</div><input type="hidden" id="new_Units" value="" />
                        </td>
                        <td class="row2_head">
                            HSN Code
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_HSNCode" name="new_HSNCode" style="width:50%;" tabindex="3"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Class
                        </td>
                        <td class="row1_cont" style="height:25px;" id="new_class_row">
                            <div id="new_class_text">&nbsp;</div><input type="hidden" id="new_Class" value="" />
                        </td>
                        <td valign="top" class="row2_head">
                            Suppliers
                        </td>
                        <td class="row2_cont" rowspan="5">
                            <div class="supplier_list_head">
                                <div class="supplier_list_selector">
                                    <input type="text" id="new_Suppliers" name="new_Suppliers" tabindex="6" />
                                    <span id="new_SupplierButton">Add</span>
                                </div>
                                <div class="supplier_list" id="new_SuppliersList" style="height:100px;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Grade
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_Grade" name="new_Grade" style="width:90%" tabindex="5" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Min. Stock
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_MinStock" name="new_MinStock" class="invisible_text" style="width:65%;text-align:right;" tabindex="8" value="1.000" onfocus="FieldHiddenValue(this, 'in', '1.000')" onblur="FieldHiddenValue(this, 'out', '1.000')" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Shelf Life
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_ShelfLife" name="new_ShelfLife" class="invisible_text" style="width:65%;text-align:right;" tabindex="9" value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"  />
                        </td>
					</tr>
                    <tr>
                        <td class="row1_head">
                            Manufacturer
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_Manufacturer" name="new_Manufacturer" style="width:90%;" tabindex="10" value=""   />
                        </td>              					
					</tr>	
                    <tr>
                        <td class="row1_head">
                            Chemical Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_ChemName" name="new_ChemName" style="width:90%" tabindex="11" />
                        </td>
                        <td class="row2_head">
                            Primary Composition
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_PriComp" name="new_PriComp" style="width:90%;" tabindex="12"/>
                        </td>
                    </tr>				
                   <tr>
                        <td class="row1_head">
                            Approved Rate
                        </td>
                        <td class="row1_cont" >
                            <input type="text" id="new_AppRate" name="new_AppRate" class="invisible_text" style="width:65%;text-align:right;" tabindex="13" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                        </td>
                        <td class="row2_head">
                            Std Packaging Quantity
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_StdPack" name="new_StdPack" class="invisible_text" style="width:65%;text-align:right;" tabindex="14" value="0.000" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" onkeydown="openAccordion(this, event, 'new', 2)" />
                        </td>
                    </tr>					
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Parameters
            </div>
            <span class="accord_content">
                <div style="text-align:right;margin-bottom:10px;padding-right:3px;">
                    <span id="new_add_param">Add</span>
                </div>
                <div class="supplier_list_head" style="margin-left:5px;margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0">
                        <tr>
                            <th width="16%" align="left">Parameter</th>
                            <th width="16%" align="left">Std. Ref.</th>
                            <th width="8%" align="left">Units</th>
                            <th width="16%" align="left">Test Meth.</th>
                            <th width="9%" align="left">Spec</th>
                            <th width="9%" align="left">Min.</th>
                            <th width="9%" align="left">Max.</th>
                            <th width="12%" align="left">Sample Plan</th>
                            <th align="center">#</th>
                            <th class="last1" align="center">&nbsp;</th>
                        </tr>
                    </table>
                    <div class="supplier_list" id="new_ParameterList">
                        <table border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="9%" align="left">&nbsp;</th>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="12%" align="left">&nbsp;</th>
                                <th align="center">&nbsp;</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                   Approval
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Approved By
                        </td>
                        <td class="row1_cont">
                            <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" readonly="readonly" id="new_AppUser" name="new_AppUser" style="width:90%" tabindex="19"  />
                        </td>
                        <td class="row2_head">
                            Approved Date
                        </td>
                        <td class="row2_cont">
                            <input type="text" value="<?php echo date("d/m/Y"); ?>" readonly="readonly" id="new_AppDate" name="new_AppDate" style="width:90%" tabindex="20"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head" valign="top" style="padding-top:5px;">
                            Remarks
                        </td>
                        <td colspan="3">
                            <textarea id="new_Remarks" name="new_Remarks" style="width:96%;height:80px;" tabindex="21"></textarea>
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
<div id="add_base_ram_form" title="New Base Raw Material">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_base_item_error"></div>
	<form action="" onsubmit="return false;">
        <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
            <tr>
                <td style="width:40%">
                    Raw Material Name
                </td>
                <td>
                    <input type="text" id="new_BaseRMName" name="new_BaseRMName" style="width:97%" tabindex="100" />
                </td>
            </tr>
            <tr>
                <td>
                    UoM
                </td>
                <td style="height:25px;">
                	<select id="new_BaseUnits" style="width:30%" tabindex="101"><?php print $uomList; ?></select>
                </td>
            </tr>
            <tr>
                <td>
                    Class
                </td>
                <td style="height:25px;">
                	<select id="new_BaseClass" style="width:60%" tabindex="102"><?php print $classList; ?></select>
                </td>
            </tr>
        </table>
        <div class="novis_controls">
            <input type="submit" onclick="getSubmitButton('add_base_ram_form');" />
        </div>
    </form>
</div>

<div class="window" id="edit_item_form" title="Edit Raw Material" style="visibility:hidden">
	<div style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none" id="edit_item_error"></div>
	<form action="" onsubmit="return false;">
        <div id="edit_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Raw Material
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Raw Material Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_RMName" name="edit_RMName" style="width:90%" tabindex="1" />
                        </td>
                        <td class="row2_head">
                            Rack No
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_RackNo" name="edit_RackNo" style="width:50%;" tabindex="2"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            UoM
                        </td>
                        <td class="row1_cont" style="height:25px;" id="edit_units_row">
                            <div id="edit_units_text">&nbsp;</div><input type="hidden" id="edit_Units" value="" />
                        </td>
                        <td class="row2_head">
                            HSN Code
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_HSNCode" name="edit_HSNCode" style="width:50%;" tabindex="3"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Class
                        </td>
                        <td class="row1_cont" style="height:25px;" id="edit_class_row">
                            <div id="edit_class_text">&nbsp;</div><input type="hidden" id="edit_Class" value="" />
                        </td>
						<td class="row2_head">
                            In Regular Use?
                        </td>
                        <td class="row2_cont">
							<input type="checkbox" id="edit_Status" name="edit_Status" tabindex="5" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Grade
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_Grade" name="edit_Grade" style="width:90%" tabindex="5" />
                        </td>
                        <td valign="top" class="row2_head" style="padding-top:7px;">
                            Suppliers
                        </td>
                        <td class="row2_cont" rowspan="4" valign="top">
                            <div class="supplier_list_head">
                                <div class="supplier_list_selector">
                                    <input type="text" id="edit_Suppliers" name="edit_Suppliers" tabindex="6" />
                                    <span id="edit_SupplierButton">Add</span>
                                </div>
                                <div class="supplier_list" id="edit_SuppliersList" style="height:100px;"></div>
                            </div>
                        </td>						
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Min. Stock
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_MinStock" name="edit_MinStock" class="invisible_text" style="width:60%;text-align:right;" tabindex="8" value="1.000" onfocus="FieldHiddenValue(this, 'in', '1.000')" onblur="FieldHiddenValue(this, 'out', '1.000')" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Shelf Life
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_ShelfLife" name="edit_ShelfLife" class="invisible_text" style="width:60%;text-align:right;" tabindex="9" value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                        </td>
                   </tr>
                    <tr>
                        <td class="row1_head">
                            Manufacturer
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_Manufacturer" name="edit_Manufacturer" style="width:90%;" tabindex="10" value=""   />
                        </td>
					</tr>	
                    <tr>
                        <td class="row1_head">
                            Chemical Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_ChemName" name="edit_ChemName" style="width:90%" tabindex="11" />
                        </td>
                        <td class="row2_head">
                            Primary Composition
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_PriComp" name="edit_PriComp" style="width:90%;" tabindex="12"/>
                        </td>
                    </tr>						   
                  <tr>
                        <td class="row1_head">
                            Approved Rate
                        </td>
                        <td class="row1_cont" >
                            <input type="text" id="edit_AppRate" name="edit_AppRate" class="invisible_text" style="width:65%;text-align:right;" tabindex="13"  onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                        </td>
                        <td class="row2_head">
                            Std Packaging Quantity
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_StdPack" name="edit_StdPack" class="invisible_text" style="width:65%;text-align:right;" tabindex="14" onfocus="FieldHiddenValue(this, 'in', '0.000')" onblur="FieldHiddenValue(this, 'out', '0.000')" onkeydown="openAccordion(this, event, 'edit', 2)" />
                        </td>
                    </tr>					   
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Parameters
            </div>
            <span class="accord_content">
                <div style="text-align:right;margin-bottom:10px;padding-right:3px;">
                    <span id="edit_add_param">Add</span>
                </div>
                <div class="supplier_list_head" style="margin-left:5px;margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0">
                        <tr>
                            <th width="16%" align="left">Parameter</th>
                            <th width="16%" align="left">Std. Ref.</th>
                            <th width="8%" align="left">Units</th>
                            <th width="16%" align="left">Test Meth.</th>
                            <th width="9%" align="left">Spec</th>
                            <th width="9%" align="left">Min.</th>
                            <th width="9%" align="left">Max.</th>
                            <th width="12%" align="left">Sample Plan</th>
                            <th align="center">#</th>
                            <th class="last1" align="center">&nbsp;</th>
                        </tr>
                    </table>
                    <div class="supplier_list" id="edit_ParameterList">
                        <table border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="9%" align="left">&nbsp;</th>
                                <th width="16%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="9.5%" align="left">&nbsp;</th>
                                <th width="12%" align="left">&nbsp;</th>
                                <th align="center">#</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                   Approval
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Approved By
                        </td>
                        <td class="row1_cont">
                            <input type="text" value="" id="edit_AppUser" name="edit_AppUser" readonly="readonly" style="width:90%" tabindex="19"  />
                        </td>
                        <td class="row2_head">
                            Approved Date
                        </td>
                        <td class="row2_cont">
                            <input type="text" value="" id="edit_AppDate" name="edit_AppDate" readonly="readonly" style="width:90%" tabindex="20" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head" valign="top" style="padding-top:5px;">
                            Remarks
                        </td>
                        <td colspan="3">
                            <textarea id="edit_Remarks" name="edit_Remarks" style="width:96%;height:80px;" tabindex="21"></textarea>
                        </td>
                    </tr>
                </table>
            </span>
        </div>
        <div class="novis_controls">
            <input type="submit" tabindex="22" onclick="getSubmitButton('edit_item_form');" />
        </div>
    </form>
</div>

<div id="del_item_form" title="Delete Raw Material" style="visibility:hidden">
    Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>
