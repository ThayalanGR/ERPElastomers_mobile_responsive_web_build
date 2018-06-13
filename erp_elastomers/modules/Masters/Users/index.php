<div id="window_list_wrapper">
    <div id="window_list_head">
        <span id="button_add">New</span>
        Users Master
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="8%" align="left">Code</th>
                <th width="8%" align="left">Username</th>
                <th width="20%" align="left">Name</th>
                <th width="20%" align="left">Designation</th>
                <th width="8%" align="left">Mobile</th>
                <th width="15%" align="left">Email</th>
                <th width="12%" align="left">Type</th>
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
</div>

<div id="new_item_form" class="window" title="New Operator" style="visibility:hidden;">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
    	<div id="new_item_accord">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Contact Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Operator Type
                        </td>
                        <td class="row1_cont">
                            <input type="radio" id="new_OptTypeEmployee" name="new_OptType" value="Employee" tabindex="1" checked="checked" />&nbsp;<label for="new_OptTypeEmployee">Employee</label>&nbsp;
                            <input type="radio" id="new_OptTypeSubCont" name="new_OptType" value="Sub-Contractor" tabindex="1" />&nbsp;<label for="new_OptTypeSubCont">Sub-Contractor</label>
                        </td>
                        <td class="row2_head">
                            Name
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_OptName" name="new_OptName" style="width:95%;" tabindex="2" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Designation
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_OptDesign" name="new_OptDesign" style="width:95%;" tabindex="3" />
                        </td>
                        <td class="row2_head">
                            Address
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_OptAdd1" name="new_OptAdd1" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Phone
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_OptPhone" name="new_OptPhone" style="width:95%;" tabindex="5" />
                        </td>
                        <td class="row2_head">&nbsp;
                            
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_OptAdd2" name="new_OptAdd2" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Mobile
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_OptMob" name="new_OptMob" style="width:95%;" tabindex="6" />
                        </td>
                        <td class="row2_head">&nbsp;
                            
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_OptAdd3" name="new_OptAdd3" style="width:60%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Email
                        </td>
                        <td class="row1_cont" >
                            <input type="text" id="new_OptEml" name="new_OptEml" style="width:95%;" tabindex="7"  />
                        </td>
                        <td class="row2_head">
                            Password
                        </td>
                        <td class="row2_cont">
                            <input type="password" id="new_Password" name="new_Password" style="width:95%;" tabindex="8" onkeydown="openAccordion(this, event, 'new', 1)" />
                        </td>						
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                Commercial Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Bank Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_BankName" name="new_BankName" style="width:95%;" tabindex="5" />
                        </td>
                        <td class="row2_head">
                            Account No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_AccNo" name="new_AccNo" style="width:95%" tabindex="6" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Bank IFSC Code
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_IFSCCode" name="new_IFSCCode" style="width:95%;" tabindex="7" />
                        </td>
                        <td class="row2_head">
                            PAN No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_PANNo" name="new_PANNo" style="width:95%;" tabindex="7" onkeydown="openAccordion(this, event, 'new', 4)" />
                        </td>
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Operation Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="5" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row2_head" style="width:10%;">
                            Moulding:
                        </td>
                        <td class="row2_cont" style="width:25%">
                            <input type="radio" id="new_MouldYes" name="new_Moulding" tabindex="13" value="true"  onclick="chkMouldingTrimming('moulding', true)" />&nbsp;<label for="new_MouldYes">Yes</label>&nbsp;
                            <input type="radio" id="new_MouldNo" name="new_Moulding" tabindex="13" value="false" checked="checked" onclick="chkMouldingTrimming('moulding', false)" />&nbsp;<label for="new_MouldNo">No</label>
                        </td>
                        <td class="row2_head" style="width:10%;">
                            Trimming:
                        </td>
                        <td class="row2_cont">
                            <input type="radio" id="new_TrimYes" name="new_Trimming" tabindex="13" value="true" onclick="chkMouldingTrimming('trimming', true)" />&nbsp;<label for="new_TrimYes">Yes</label>&nbsp;
                            <input type="radio" id="new_TrimNo" name="new_Trimming" tabindex="13" value="false" checked="checked" onclick="chkMouldingTrimming('trimming', false)" />&nbsp;<label for="new_TrimNo">No</label>
                        </td>
                        <td align="right">
                            <span id="new_CpdBtn">Add</span>
                        </td>
                    </tr>
				</table>
                <div class="supplier_list_head" style="border:1px solid #ccc;">
                    <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                        <tr>
                            <th align="left" width="55%">Component / Compound</th>
                            <th align="left" width="20%">Moulding Rate</th>
                            <th align="left" width="20%">Trimming Rate</th>
                            <th>#</th>
                            <th class="last">&nbsp;</th>
                        </tr>
                    </table>
                    <div class="supplier_list">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%" id="new_CpdDtls" style="border:1px solid black;">
                            <tr style="border-bottom:1px solid black;">
                                <th align="left" width="55%">&nbsp;</th>
                                <th align="left" width="20%">&nbsp;</th>
                                <th align="left" width="20%">&nbsp;</th>
                                <th>#</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Access Details
            </div>
            <span class="accord_content">
                <div class="supplier_list" style="height:250px;width:auto;overflow:auto;border:1px solid #ccc;" id="new_OptAccess">
                    <table border="1" cellspacing="0" cellpadding="5" style="width:100%" id="new_OptAccess_body">
                    </table>
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
                            <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" id="new_AppUser" name="new_AppUser" style="width:90%" tabindex="19" readonly="readonly" />
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

<div id="edit_item_form" class="window" title="Edit Operator" style="visibility:hidden">
	<div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
	<form action="" onsubmit="return false;">
    	<div id="edit_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Contact Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Operator Type
                        </td>
                        <td class="row1_cont">
                            <input type="radio" id="edit_OptTypeEmployee" name="edit_OptType" value="Employee" tabindex="1" checked="checked" />&nbsp;<label for="edit_OptTypeEmployee">Employee</label>&nbsp;
                            <input type="radio" id="edit_OptTypeSubCont" name="edit_OptType" value="Sub-Contractor" tabindex="1" />&nbsp;<label for="edit_OptTypeSubCont">Sub-Contractor</label>
                        </td>
                        <td class="row2_head">
                            Name
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_OptName" name="edit_OptName" style="width:95%;" tabindex="2" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Designation
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_OptDesign" name="edit_OptDesign" style="width:95%;" tabindex="3" />
                        </td>
                        <td class="row2_head">
                            Address
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_OptAdd1" name="edit_OptAdd1" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Phone
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_OptPhone" name="edit_OptPhone" style="width:95%;" tabindex="5" />
                        </td>
                        <td class="row2_head">&nbsp;
                            
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_OptAdd2" name="edit_OptAdd2" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Mobile
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_OptMob" name="edit_OptMob" style="width:95%;" tabindex="6" />
                        </td>
                        <td class="row2_head">&nbsp;
                            
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_OptAdd3" name="edit_OptAdd3" style="width:60%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Email
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_OptEml" name="edit_OptEml" style="width:95%;" tabindex="7" />
                        </td>
                        <td class="row2_head">
                            Password
                        </td>
                        <td class="row2_cont">
                            <input type="password" id="edit_Password" name="edit_Password" style="width:95%;" tabindex="7" onkeydown="openAccordion(this, event, 'edit', 1)" />
                        </td>
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                Commercial Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            Bank Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_BankName" name="edit_BankName" style="width:95%;" tabindex="5" />
                        </td>
                        <td class="row2_head">
                            Account No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_AccNo" name="edit_AccNo" style="width:95%" tabindex="6" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Bank IFSC Code
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_IFSCCode" name="edit_IFSCCode" style="width:95%;" tabindex="7" />
                        </td>
                        <td class="row2_head">
                            PAN No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_PANNo" name="edit_PANNo" style="width:95%;" tabindex="7" onkeydown="openAccordion(this, event, 'edit', 4)" />
                        </td>
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Operation Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="5" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row2_head" style="width:10%">
                            Moulding
                        </td>
                        <td class="row2_cont" style="width:25%">
                            <input type="radio" id="edit_MouldYes" name="edit_Moulding" tabindex="13" value="true" checked="checked" onclick="chkMouldingTrimming('moulding', true)" />&nbsp;<label for="edit_MouldYes">Yes</label>&nbsp;
                            <input type="radio" id="edit_MouldNo" name="edit_Moulding" tabindex="13" value="false" onclick="chkMouldingTrimming('moulding', false)" />&nbsp;<label for="edit_MouldNo">No</label>
                        </td>
                        <td class="row2_head" style="width:10%">
                            Trimming
                        </td>
                        <td class="row2_cont" style="width:25%">
                            <input type="radio" id="edit_TrimYes" name="edit_Trimming" tabindex="13" value="true" checked="checked" onclick="chkMouldingTrimming('trimming', true)" />&nbsp;<label for="edit_TrimYes">Yes</label>&nbsp;
                            <input type="radio" id="edit_TrimNo" name="edit_Trimming" tabindex="13" value="false" onclick="chkMouldingTrimming('trimming', false)" />&nbsp;<label for="edit_TrimNo">No</label>
                        </td>
                        <td align="right">
                            <span id="edit_CpdBtn">Add</span>
                        </td>
                    </tr>
				</table>
                <div class="supplier_list_head" style="border:1px solid #ccc;">
                    <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                        <tr>
                            <th align="left" width="55%">Component / Compound</th>
                            <th align="left" width="20%">Moulding Rate</th>
                            <th align="left" width="20%">Trimming Rate</th>
                            <th>#</th>
                        </tr>
                    </table>
                    <div class="supplier_list">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%" id="edit_CpdDtls">
                            <tr>
                                <th align="left" width="55%">&nbsp;</th>
                                <th align="left" width="20%">&nbsp;</th>
                                <th align="left" width="20%">&nbsp;</th>
                                <th>#</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Access Details
            </div>
            <span class="accord_content">
                <div class="supplier_list" style="height:250px;width:100%;overflow:auto;border:1px solid #ccc;" id="edit_OptAccess">
                    <table border="1" cellspacing="0" cellpadding="5" style="width:100%" style="border-right:1px solid #fcfcfc;width:auto;">
                    </table>
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
                            <input type="text" id="edit_AppUser" name="edit_AppUser" style="width:90%" tabindex="19" readonly="readonly" />
                        </td>
                        <td class="row2_head">
                            Approved Date
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_AppDate" name="edit_AppDate" style="width:90%" tabindex="20" readonly="readonly" />
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

<div id="del_item_form" title="Delete Operator" style="visibility:hidden">
	Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>
