<?php
	$sql			=	"select cpdId, cpdName from tbl_compound where status>0;";
	$resTx			=	@getMySQLData($sql);
	$cpdData		=	$resTx['data'];
	$cpdList		=	"<option></option>";
	foreach($cpdData as $key=>$value){
		$cpdList	.=	"<option value='".$value['cpdId']."'>".$value['cpdName']."</option>";
	}

	$sql			=	"select prodType, typeAbbr from tbl_product_group";
	$resTx			=	@getMySQLData($sql);
	$prodGroupData	=	$resTx['data'];	
	$prodGroupList	=	"<option></option>";
	foreach($prodGroupData as $prodGroup){
		$value			=	$prodGroup['prodType'];
		$key			=	$prodGroup['typeAbbr'];
		$prodGroupList	.=	"<option value='$value'>$value - $key</option>";
	}	
	
	$hsnList		=	"<option value=''></option>
						<option value='4016'>4016</option>";
?>

<style media="all">
#new_item_form .three_row_table .row1_cont, .three_row_table .row2_cont{width:20% !important;}
#new_item_form .three_row_table .row3_cont{width:auto;}
</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Component Master</strong>
        <span id="button_add">New</span>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="new_item_form" class="window" title="New Component" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <div id="new_item_accord" class="accordion">
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Component Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Description
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_RefNo" name="new_RefNo" tabindex="1" style="width:95%;" />
                            </td>
                            <td class="row2_head">
                                Part Number
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CompName" name="new_CompName" tabindex="1" style="width:95%;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Compound Ref.
                            </td>
                            <td class="row1_head">
                                <select id="new_CpdRef" name="new_CpdRef" tabindex="1" style="width:95%">
									<?php print $cpdList; ?>
								</select>							
                            </td>
                            <td class="row2_head">
                                Drawing Ref./Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_DrawRef" name="new_DrawRef" tabindex="1" style="width:45%;" />
								<input type="text" rel="datepicker" style="width:45%" class="invisible_text" tabindex="1" value="DD/MM/YYYY" id="new_DrawDate" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Product/Blank Wgt(gm)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_ProdWgt" name="new_ProdWgt" tabindex="1" style="width:45%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
								<input type="text" id="new_BlankWeight" name="new_BlankWeight" tabindex="1" style="width:45%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                            </td>
                            <td class="row2_head">
                                Revision
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_DrawRev" name="new_DrawRev" tabindex="1" style="width:60%;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Inspection Rate (Rs)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_InspRate" name="new_InspRate" tabindex="1" style="width:60%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                            </td>
                            <td class="row2_head">
                                Shelf Life (Years)
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_ShelfLife" name="new_ShelfLife" tabindex="1" style="width:60%;text-align:right;"  value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Molding In-House Only?
                            </td>
                            <td class="row1_head">
								<select id="new_moldControl" name="new_moldControl" tabindex="1" >
									<option value="0">No</option>
									<option value="1">Yes</option>							
								</select>                               						
                            </td>
                            <td class="row2_head">
                                Deflashing In-House Only?
                            </td>
                            <td class="row2_cont">
								<select id="new_deflashControl" name="new_deflashControl" tabindex="1" >
									<option value="0">No</option>
									<option value="1">Yes</option>							
								</select> 
                            </td>
                        </tr>								
                        <tr>
                            <td class="row1_head">
                                Rack No.
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_RackNo" name="new_RackNo" tabindex="1" style="width:60%;" />
                            </td>					
                            <td class="row2_head">
                                Standard Packing Qty
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_StdPckQty" name="new_StdPckQty" tabindex="1" style="width:60%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Average Monthly Req.
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_AMReq" name="new_AMReq" tabindex="1" style="width:60%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>					
                            <td class="row2_head">
                                Product Group
                            </td>
                            <td class="row2_cont">
                                <select id="new_ProdGroup" name="new_ProdGroup" tabindex="1" >
									<?php print $prodGroupList; ?>
								</select>							
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Application
                            </td>
                            <td class="row1_head">
                                <input type="text" id="new_App" name="new_App" tabindex="1" style="width:95%;" />						
                            </td>
                            <td class="row2_head">
                                Sub Assembly
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_SubAss" name="new_SubAss" tabindex="1" style="width:95%;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Offs/Assembly
                            </td>
                            <td class="row1_head">
								<input type="text" style="width:25%;text-align:right;" tabindex="1" value="0" id="new_Off" name="new_Off" />                                						
                            </td>
                            <td class="row2_head">
                                Allowed Rejection %
                            </td>
                            <td class="row2_cont">
								<input type="text" style="width:25%;text-align:right;" tabindex="1" value="0" id="new_RejPer" name="new_RejPer" />
                            </td>
                        </tr>						
                        <tr>
                            <td class="row1_head">
                                HSN Code
                            </td>
                            <td class="row1_cont" colspan="3">
                                <select id="new_HSNCode" name="new_HSNCode" tabindex="1" style="width:20%" onkeydown="openAccordion(this, event, 'new', 1)">
									<?php print $hsnList; ?>
								</select>
                            </td>
                        </tr>
					
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Moulding Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Curing Time Spec (Sec)
                            </td>
                            <td class="row1_cont">
                            	<input type="text" value="0" name="new_CurTime" id="new_CurTime" tabindex="2" class="invisible_text" style="width:50%;text-align:right;" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                            <td class="row2_head">
                                Curing time min (Sec)
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CurTimeMin" name="new_CurTimeMin" tabindex="2" value='0' style="width:50%;text-align:right" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                            <td class="row3_head">
                                Curing time max (Sec)
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="new_CurTimeMax" name="new_CurTimeMax" tabindex="2" style="width:92%;text-align:right" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                Curing Temp Spec (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_Temperature" name="new_Temperature" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                            <td>
                                Curing Temp Min (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_CurTempMin" name="new_CurTempMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                            <td>
                                Curing Temp Max (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_CurTempMax" name="new_CurTempMax" style="width:92%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Curing Pres Spec (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="new_Pressure" name="new_Pressure" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                Curing Pres Min (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="new_CurPresMin" name="new_CurPresMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                Curing Pres Max (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="new_CurPresMax" name="new_CurPresMax" style="width:92%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PostCur Time (Sec)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTime" name="new_PostCurTime" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Time Min (Sec)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTimeMin" name="new_PostCurTimeMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Time Max (Sec)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTimeMax" name="new_PostCurTimeMax" style="width:92%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PostCur Temp (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTemp" name="new_PostCurTemp" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Temp Min (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTempMin" name="new_PostCurTempMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Temp Max (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="new_PostCurTempMax" name="new_PostCurTempMax" style="width:92%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" onkeydown="openAccordion(this, event, 'new', 5)" />
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Dimensional Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="new_DimBtn">Add</span>
                    </div>
                    <div class="supplier_list_head">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="20%">Dimension</th>
                                <th align="left" width="10%">UoM</th>
                                <th align="left" width="15%">Test Meth.</th>
                                <th align="left" width="12%">Spec.</th>
                                <th align="left" width="12%">Min.</th>
                                <th align="left" width="12%">Max.</th>
                                <th align="left" width="13%">Sampling Plan</th>
                                <th>#</th>
                            </tr>
                        </table>
						<div class="supplier_list">
							<table border="0" cellspacing="0" cellpadding="5" id="new_CmpdDim" style="width:100%;">
								<tr style="border-bottom:1px solid black;">
									<th align="left" width="20%">&nbsp;</th>
									<th align="left" width="10%">&nbsp;</th>
									<th align="left" width="15%">&nbsp;</th>
									<th align="left" width="12%">&nbsp;</th>
									<th align="left" width="12%">&nbsp;</th>
									<th align="left" width="12%">&nbsp;</th>
									<th align="left" width="13%">&nbsp;</th>
									<th>#</th>
								</tr>
							</table>
						</div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Rejection Type
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="new_RejBtn">Add</span>
                    </div>
                    <div class="supplier_list_head">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="45%">Rejection Type</th>
                                <th align="left" width="45%">Short Name</th>
                                <th>#</th>
                            </tr>
                        </table>
						<div class="supplier_list">
							<table border="0" cellspacing="0" cellpadding="5" id="new_RejTypes" style="width:100%;">
								<tr style="border-bottom:1px solid black;">
									<th align="left" width="45%">&nbsp;</th>
									<th align="left" width="45%">&nbsp;</th>
									<th>#</th>
								</tr>
							</table>
						</div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Insert Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="new_InsBtn">Add</span>
                    </div>
					<div class="supplier_list_head">
						<table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
							<tr style="border-bottom:1px solid black;">
								<th align="left" width="95%">Insert Name</th>
								<th>#</th>
							</tr>
						</table>
						<div class="supplier_list">
							<table border="0" cellspacing="0" cellpadding="5" id="new_InsTypes" style="width:100%;">
								<tr style="border-bottom:1px solid black;">
									<th align="left" width="95%">&nbsp;</th>
									<th>#</th>
								</tr>
							</table>
						</div>
                    </div>
                </span>				
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approval Details
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
                                <input type="text" value="<?php echo date("d/m/Y"); ?>" id="new_AppDate" name="new_AppDate" style="width:90%" tabindex="20" readonly="readonly" />
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
                <input type="clear" tabindex="23" onclick="getSubmitButton('new_item_form');" />
            </div>
        </form>
    </div>
    
    <div id="edit_item_form" class="window" title="Edit Component" style="visibility:hidden">
        <div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
        <form action="" onsubmit="return false;">
            <div id="edit_item_accord" class="accordion">
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Component Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Description
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_RefNo" name="edit_RefNo" tabindex="1" style="width:95%;" />
                            </td>
                            <td class="row2_head">
                                Part Number
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CompName" name="edit_CompName" tabindex="1" style="width:95%;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Compound Ref.
                            </td>
                            <td id="edit_CpdRef_Sel" class="row1_head">
                                <select id="edit_CpdRef" name="new_CpdRef" tabindex="1" style="width:95%">
									<?php print $cpdList; ?>
								</select>							
                            </td>
                            <td class="row2_head">
                                Drawing Ref./Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_DrawRef" name="edit_DrawRef" tabindex="1" style="width:45%;" />
								<input type="text" rel="datepicker" style="width:45%" class="invisible_text" tabindex="1" value="DD/MM/YYYY" id="edit_DrawDate" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Product/Blank Wgt(gm)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_ProdWgt" name="edit_ProdWgt" tabindex="1" style="width:45%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
								<input type="text" id="edit_BlankWeight" name="edit_BlankWeight" tabindex="1" style="width:45%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                            </td>
                            <td class="row2_head">
                                Revision
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_DrawRev" name="edit_DrawRev" tabindex="1" style="width:60%;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Inspection Rate (Rs)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_InspRate" name="edit_InspRate" tabindex="1" style="width:60%;text-align:right" value="0.00" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                            </td>
                            <td class="row2_head">
                                Shelf Life (Years)
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_ShelfLife" name="edit_ShelfLife" tabindex="1" style="width:60%;text-align:right;"  value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Molding In-House Only?
                            </td>
                            <td class="row1_head">
								<select id="edit_moldControl" name="edit_moldControl" tabindex="1" >
									<option value="0">No</option>
									<option value="1">Yes</option>							
								</select>                               						
                            </td>
                            <td class="row2_head">
                                Deflashing In-House Only?
                            </td>
                            <td class="row2_cont">
								<select id="edit_deflashControl" name="edit_deflashControl" tabindex="1" >
									<option value="0">No</option>
									<option value="1">Yes</option>							
								</select> 
                            </td>
                        </tr>								
                        <tr>
                            <td class="row1_head">
                                Rack No.
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_RackNo" name="edit_RackNo" tabindex="1" style="width:60%;" />
                            </td>					
                            <td class="row2_head">
                                Standard Packing Qty
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_StdPckQty" name="edit_StdPckQty" tabindex="1" style="width:60%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Average Monthly Req.
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_AMReq" name="edit_AMReq" tabindex="1" style="width:60%;text-align:right" value="0" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>					
                            <td class="row2_head">
                                Product Group
                            </td>
                            <td class="row2_cont">
                                <select id="edit_ProdGroup" name="edit_ProdGroup" tabindex="1" >
									<?php print $prodGroupList; ?>
								</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Application
                            </td>
                            <td class="row1_head">
                                <input type="text" id="edit_App" name="edit_App" tabindex="1" style="width:95%;" />						
                            </td>
                            <td class="row2_head">
                                Sub Assembly
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_SubAss" name="edit_SubAss" tabindex="1" style="width:95%;" />
                            </td>
                        </tr>	
                        <tr>
                            <td class="row1_head">
                                Offs/Assembly
                            </td>
                            <td class="row1_head">
								<input type="text" style="width:25%;text-align:right;" tabindex="1" value="0" id="edit_Off" name="edit_Off" />                                						
                            </td>
                            <td class="row2_head">
                                Allowed Rejection %
                            </td>
                            <td class="row2_cont">
								<input type="text" style="width:25%;text-align:right;" tabindex="1" value="0" id="edit_RejPer" name="edit_RejPer" />
                            </td>
                        </tr>								
                        <tr>
                            <td class="row1_head">
                                HSN Code
                            </td>
                            <td class="row1_cont" colspan="3">
                                <select id="edit_HSNCode" name="edit_HSNCode" tabindex="1"  style="width:20%" onkeydown="openAccordion(this, event, 'edit', 1)">
									<?php print $hsnList; ?>
								</select>								
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Moulding Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="three_row_table">
                        <tr>
                            <td class="row1_head">
                                Curing Time Spec (Sec)
                            </td>
                            <td class="row1_cont">
                            	<input type="text" value="0" id="edit_CurTime" tabindex="2" class="invisible_text" style="width:50%;text-align:right;" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                            <td class="row2_head">
                                Curing time min (Sec)
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CurTimeMin" name="edit_CurTimeMin" tabindex="2" value='0' style="width:50%;text-align:right" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                            <td class="row3_head">
                                Curing time max (Sec)
                            </td>
                            <td class="row3_cont">
                                <input type="text" id="edit_CurTimeMax" name="edit_CurTimeMax" tabindex="2" style="width:50%;text-align:right" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                            </td>
                        </tr>
                        <tr>    
                            <td>
                                Curing Temp Spec (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_Temperature" name="edit_Temperature" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                            <td>
                                Curing Temp Min (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_CurTempMin" name="edit_CurTempMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                            <td>
                                Curing Temp Max (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_CurTempMax" name="edit_CurTempMax" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Curing Pres Spec (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="edit_Pressure" name="edit_Pressure" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                Curing Pres Min (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="edit_CurPresMin" name="edit_CurPresMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                Curing Pres Max (Kg/cm<sup>2</sup>)
                            </td>
                            <td>
                                <input type="text" id="edit_CurPresMax" name="edit_CurPresMax" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PostCur Time (Sec)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTime" name="edit_PostCurTime" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Time Min (Sec)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTimeMin" name="edit_PostCurTimeMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Time Max (Sec)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTimeMax" name="edit_PostCurTimeMax" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PostCur Temp (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTemp" name="edit_PostCurTemp" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Temp Min (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTempMin" name="edit_PostCurTempMin" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
                            </td>
                             <td>
                                PostCur Temp Max (&deg;C)
                            </td>
                            <td>
                                <input type="text" id="edit_PostCurTempMax" name="edit_PostCurTempMax" style="width:50%;text-align:right" tabindex="2" value='0' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"  onkeydown="openAccordion(this, event, 'edit', 5)" />
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Dimensional Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_DimBtn">Add</span>
                    </div>
                    <div class="supplier_list_head">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="20%">Dimension</th>
                                <th align="left" width="10%">UoM</th>
                                <th align="left" width="15%">Test Meth.</th>
                                <th align="left" width="12%">Spec.</th>
                                <th align="left" width="12%">Min.</th>
                                <th align="left" width="12%">Max.</th>
                                <th align="left" width="15%">Sampling Plan</th>
                                <th>#</th>
                            </tr>
                        </table>
                        <div class="supplier_list">
                        <table border="0" cellspacing="0" cellpadding="5" id="edit_CmpdDim" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="20%">&nbsp;</th>
                                <th align="left" width="10%">&nbsp;</th>
                                <th align="left" width="15%">&nbsp;</th>
                                <th align="left" width="12%">&nbsp;</th>
                                <th align="left" width="12%">&nbsp;</th>
                                <th align="left" width="12%">&nbsp;</th>
                                <th align="left" width="15%">&nbsp;</th>
                                <th>#</th>
                            </tr>
                        </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Rejection Type
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_RejBtn">Add</span>
                    </div>
                    <div class="supplier_list_head">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="45%">Rejection Type</th>
                                <th align="left" width="45%">Short Name</th>
                                <th>#</th>
                            </tr>
                        </table>
                        <div class="supplier_list">
                        <table border="0" cellspacing="0" cellpadding="5" id="edit_RejTypes" style="width:100%;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="45%">&nbsp;</th>
                                <th align="left" width="45%">&nbsp;</th>
                                <th>#</th>
                            </tr>
                        </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Insert Details
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_InsBtn">Add</span>
                    </div>
					<div class="supplier_list_head">
						<table border="0" cellspacing="0" cellpadding="5" style="width:100%;">
							<tr style="border-bottom:1px solid black;">
								<th align="left" width="95%">Insert Name</th>
								<th>#</th>
							</tr>
						</table>
						<div class="supplier_list">
							<table border="0" cellspacing="0" cellpadding="5" id="edit_InsTypes" style="width:100%;">
								<tr style="border-bottom:1px solid black;">
									<th align="left" width="95%">&nbsp;</th>
									<th>#</th>
								</tr>
							</table>
						</div>
                    </div>
                </span>								
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approval Details
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
                                <input type="text" value="" id="edit_AppDate" name="edit_AppDate" style="width:90%" tabindex="20" readonly="readonly"/>
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
	<div id="draw_upload_form" title="Upload Drawing">
		<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="draw_upload_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                <tr>
                    <td valign="top" style="width:35%">
                        Upload Drawing File:
                    </td>
                    <td>
						<input id="draw_file" name="draw_file" type="file" accept=".pdf" style="width:95%" /> 
                    </td> 
                </tr>
            </table>
        </form>
    </div>    
    <div id="del_item_form" title="Delete Component" style="visibility:hidden">
        Are you Sure to Delete ?
        <div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
    </div>
</div>

<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>