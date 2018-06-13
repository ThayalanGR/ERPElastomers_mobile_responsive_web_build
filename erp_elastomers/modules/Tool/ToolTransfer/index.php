<?php
	// Get Invoice No.
	$codeArray		=	@getSettingsData("toolTransferNote");
	$codeNo			=	@getRegisterNo($codeArray[0], $codeArray[1]);
?>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Tool Transfer Note</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body" style="padding:10px;">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td width="20%">
                            TTN Reference
                        </td>
                        <td id="new_TTNRef" height="22px" style="width:30%">
                            <?php echo $codeNo; ?>
                        </td>
                        <td width="20%">
                            TTN Date
                        </td>
                        <td id="new_TTNDate" height="22px">
                            <?php echo date("d-M-Y"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tool Ref<label id="test"></label>
                        </td>
                        <td>
                            <input type="text" id="new_ToolRef" name="new_ToolID" style="width:50%;" />
                        </td>
                        <td>
                            Transferor
                        </td>
                        <td>
                            <label id="new_Transferor"></label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="padding-top:10px;">
                            Transferee
                        </td>
                        <td>
                            <input type="text" id="new_Transferee" name="new_Transferee" style="width:50%;" />
                        </td>
                        <td colspan="2" id="error_msg" style="padding:7px;">&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                        <td align="right">
                            <button id="button_add" type="submit">Create</button>
                            <button id="create_print" type="submit">Create & Print</button>
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
