<?php
	global $stateList;
	$cusStateItems	=	"<option value=''></option>";
	foreach($stateList as $key=>$value){
		$cusStateItems	.=	"<option value='".$key."'>".$value."</option>";
	}		
	
?>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        Supplier Master
        <span id="button_add">New</span>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr class="ram_rows_head">
              <th width="8%" align="left">Code</th>
              <th width="10%" align="left">Reference No.</th>
              <th width="25%" align="left">Supplier Name</th>
              <th width="20%" align="left">Contact Person</th>
              <th width="20%" align="left">Email</th>
              <th width="8%" align="left">Mobile</th>
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

<div id="new_item_form" class="window" title="New Supplier" style="visibility:hidden;">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
    	<div id="new_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Suppler Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                	<tr>
                        <td class="row1_head">
                            Reference No.
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_RefNo" name="new_RefNo" style="width:95%;" tabindex="1" />
                        </td>
                        <td class="row2_head">
                            Contact Person
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_ContName" name="new_ContName" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Supplier Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_SupName" name="new_SupName" style="width:95%" tabindex="2" />
                        </td>
                        <td class="row2_head">
                            Mobile
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Mobile" name="new_Mobile" style="width:95%;" tabindex="5" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head" rowspan="3" valign="top">
                            Address
                        </td>
                        <td class="row1_cont" rowspan="3" valign="top">
                            <textarea id="new_SupAddr1" name="new_SupAddr1" style="width: 95%; height: 85px; max-height: 85px; " tabindex="3"></textarea>
                        </td>
                        <td class="row2_head">
                            Phone
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Phone" name="new_Phone" style="width:95%;" tabindex="6" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row2_head">
                            Fax
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Fax" name="new_Fax" style="width:95%;" tabindex="7" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row2_head">
                            Email
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Email" name="new_Email" style="width:95%;" tabindex="8" onkeydown="openAccordion(this, event, 'new', 1)" /> 
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
                            <input type="text" id="new_BankName" name="new_BankName" style="width:95%" tabindex="9" />
                        </td>
                        <td class="row2_head">
                            Bank Account No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_BankNo" name="new_BankNo" style="width:95%" tabindex="10" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            IFSC Code
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_IFSCCode" name="new_IFSCCode" style="width:95%" tabindex="11" />
                        </td>
                        <td class="row2_head">
                            Credit Days
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_Credit" name="new_Credit" style="width:20%" tabindex="12" onkeydown="openAccordion(this, event, 'new', 2)" /> Days
                        </td>
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Statutory Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td class="row1_head">
                            PAN No
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="new_PANNo" name="new_PANNo" style="width:75%" tabindex="13" />
                        </td>
                        <td class="row2_head" style="padding-left:0px;">
                            GST No
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="new_GSTNo" name="new_GSTNo" style="width:95%" tabindex="14" />
                        </td>
                    </tr>
					<tr>
						<td class="row1_head">
							GST Supply Place
						</td>
						<td class="row1_cont">
							<input type="text" id="new_SupplyPlace" name="new_SupplyPlace" style="width:75%" tabindex="15"  />
						</td>
						<td class="row2_head" style="padding-left:0px;">
							GST Supply State
						</td>
						<td class="row2_cont">
							<select id="new_SupplyState" name="new_SupplyState" tabindex="16" onkeydown="openAccordion(this, event, 'new', 3)" />
								<?php echo $cusStateItems; ?>
							</select>							
						</td>
					</tr>							
                </table>
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
                            <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" id="new_AppUser" name="new_AppUser" style="width:90%" tabindex="19"  readonly="readonly" />
                        </td>
                        <td class="row2_head">
                            Approved Date
                        </td>
                        <td class="row2_cont">
                            <input type="text" value="<?php echo date("d/m/Y"); ?>" id="new_AppDate" name="new_AppDate" tabindex="20"  style="width:90%" readonly="readonly"  />
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


<div id="edit_item_form" class="window" title="Edit Supplier" style="visibility:hidden">
	<div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
	<form action="" onsubmit="return false;">
    	<div id="edit_item_accord" class="accordion">
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Suppler Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                	<tr>
                        <td class="row1_head">
                            Reference No.
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_RefNo" name="edit_RefNo" style="width:95%;" tabindex="1" />
                        </td>
                        <td class="row2_head">
                            Contact Person
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_ContName" name="edit_ContName" style="width:95%;" tabindex="4" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            Supplier Name
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_SupName" name="edit_SupName" style="width:95%" tabindex="2" />
                        </td>
                        <td class="row2_head">
                            Mobile
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Mobile" name="edit_Mobile" style="width:95%;" tabindex="5" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head" rowspan="3" valign="top">
                            Address
                        </td>
                        <td class="row1_cont" rowspan="3" valign="top">
                            <textarea id="edit_SupAddr1" name="edit_SupAddr1" style="width: 95%; height: 70px; max-height: 70px; " tabindex="3"></textarea>
                        </td>
                        <td class="row2_head">
                            Phone
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Phone" name="edit_Phone" style="width:95%;" tabindex="6" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row2_head">
                            Fax
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Fax" name="edit_Fax" style="width:95%;" tabindex="7" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row2_head">
                            Email
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Email" name="edit_Email" style="width:95%;" tabindex="8" onkeydown="openAccordion(this, event, 'edit', 1)" />
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
                            <input type="text" id="edit_BankName" name="edit_BankName" style="width:95%" tabindex="9" />
                        </td>
                        <td class="row2_head">
                            Bank Account No.
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_BankNo" name="edit_BankNo" style="width:95%" tabindex="10" />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head">
                            IFSC Code
                        </td>
                        <td class="row1_cont">
                            <input type="text" id="edit_IFSCCode" name="edit_IFSCCode" style="width:95%" tabindex="11" />
                        </td>
                        <td class="row2_head">
                            Credit Days
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_Credit" name="edit_Credit" style="width:50%" tabindex="12"  onkeydown="openAccordion(this, event, 'edit', 2)" /> Days
                        </td>
                    </tr>
                </table>
            </span>
            <div class="live_screen_patient_rows_light live_screen_patient_rows">
                Statutory Details
            </div>
            <span class="accord_content">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
				   <tr>
						<td class="row1_head">
							PAN No
						</td>
						<td class="row1_cont">
							<input type="text" id="edit_PANNo" name="edit_PANNo" style="width:75%" tabindex="13" />
						</td>
						<td class="row2_head" style="padding-left:0px;">
							GST No
						</td>
						<td class="row2_cont">
							<input type="text" id="edit_GSTNo" name="edit_GSTNo" style="width:95%" tabindex="14"  />
						</td>
					</tr>
					<tr>
						<td class="row1_head">
							GST Supply Place
						</td>
						<td class="row1_cont">
							<input type="text" id="edit_SupplyPlace" name="edit_SupplyPlace" style="width:75%" tabindex="15"  />
						</td>
						<td class="row2_head" style="padding-left:0px;">
							GST Supply State
						</td>
						<td class="row2_cont">
							<select id="edit_SupplyState" name="edit_SupplyState" tabindex="16" onkeydown="openAccordion(this, event, 'edit', 3)" />
								<?php echo $cusStateItems; ?>
							</select>							
						</td>
					</tr>		
                </table>
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
                            <input type="text" id="edit_AppUser" name="edit_AppUser" style="width:90%" tabindex="17" readonly="readonly"  />
                        </td>
                        <td class="row2_head">
                            Approved Date
                        </td>
                        <td class="row2_cont">
                            <input type="text" id="edit_AppDate" name="edit_AppDate" style="width:90%" tabindex="18" readonly="readonly"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="row1_head" valign="top" style="padding-top:5px;">
                            Remarks
                        </td>
                        <td colspan="3">
                            <textarea id="edit_Remarks" name="edit_Remarks" style="width:96%;height:100px;" tabindex="19"></textarea>
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

<div id="del_item_form" title="Delete Supplier" style="visibility:hidden">
	Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>

