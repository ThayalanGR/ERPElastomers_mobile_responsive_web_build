<?php
	// Get Invoice No.
	$codeArray		=	@getSettingsData("toolvalidationnote");
	$codeNo			=	@getRegisterNo($codeArray[0], $codeArray[1]);
?>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Tool Validation Note</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body" style="padding:10px;">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td width="20%">
                            TVN Reference
                        </td>
                        <td id="new_TVNRef" height="22px" style="width:30%">
                            <?php echo $codeNo; ?>
                        </td>
                        <td width="20%">
                            TVN Date
                        </td>
                        <td> 
							<input type="date" tabindex="1" id="new_TVNDate" value="<?php echo date("Y-m-d"); ?>" />                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tool Ref
                        </td>
                        <td>
                            <input type="text" tabindex="2" id="new_ToolRef" name="new_ToolRef" style="width:50%;" />
                        </td>
                        <td>
                            Lifts Run
                        </td>
                        <td>
                            <label id="new_Lift" name="new_Lift"></label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="padding-top:10px;">
                            Complaint
                        </td>
                        <td>
                            <textarea id="new_Complaint" name="new_Complaint" tabindex="3" rows="3" cols="50"></textarea>
                        </td>
                        <td valign="top" style="padding-top:10px;">
                            Observation
                        </td>
                        <td>
                            <textarea id="new_Observation"  name="new_Observation" tabindex="4" rows="3" cols="50"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="padding-top:10px;">
                            Action Taken
                        </td>
                        <td>
                            <textarea id="new_Action" name="new_Action" tabindex="5" rows="3" cols="50"></textarea>
                        </td>
                        <td valign="top" style="padding-top:10px;">
                            Remarks
                        </td>
                        <td>
                            <textarea id="new_Remark" name="new_Remark" tabindex="6" rows="3" cols="50">Pokayoke Checked</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Next Validation
                        </td>
                        <td>
                            <input type="text" id="new_Nextvalid" name="new_NextValid" tabindex="7" style="width:50%" class="invisible_text" value="5000" onfocus="FieldHiddenValue(this, 'in', '5000')" onblur="FieldHiddenValue(this, 'out', '5000')" />
                        </td>
						<td>
							Validation Doc(s)/Image(s)
						</td>
						<td>
							<input id="tl_file" name="tl_file[]" type="file" accept="image/jpeg,application/pdf" style="width:80%" tabindex="8" multiple />
						</td>						
                     </tr>
                    <tr>
                        <td colspan="3" ><div id="error_msg" style="padding:7px;"></div></td>
                        <td align="right">
                            <button id="button_add" type="submit">Create</button>
                            <button id="button_cancel">Clear</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
</div>
