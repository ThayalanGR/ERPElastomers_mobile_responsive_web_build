<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        Rejection Master
        <span id="button_add">New</span>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="5%" align="left">Sno</th>
                <th width="20%" align="left">Rejection Type</th>    
                <th width="20%" align="left">Short Name</th>
                <th align="right">#&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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


<div id="new_item_form" title="New Rejection" style="visibility:hidden">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
        <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
            <tr>
                <td style="width:40%">
                    Rejection Type
                </td>
                <td>
                    <input type="text" id="new_RejType" name="new_RejType" style="width:80%" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    Short Name
                </td>
                <td>
                    <input type="text" id="new_ShortName" name="new_ShortName" style="width:80%" tabindex="2" />
                </td>
            </tr>
        </table>
        <div class="novis_controls">
            <input type="submit" onclick="getSubmitButton('new_item_form');" />
        </div>
    </form>
</div>


<div id="edit_item_form" title="Edit Rejection" style="visibility:hidden">
	<div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
	<form action="" onsubmit="return false;">
        <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
            <tr>
                <td style="width:40%">
                    Rejection Type
                </td>
                <td>
                    <input type="text" id="edit_RejType" name="edit_RejType" style="width:80%" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    Short Name
                </td>
                <td>
                    <input type="text" id="edit_ShortName" name="edit_ShortName" style="width:80%" tabindex="2" />
                </td>
            </tr>
        </table>
        <div class="novis_controls">
            <input type="submit" onclick="getSubmitButton('edit_item_form');" />
        </div>
    </form>
</div>


<div id="del_item_form" title="Delete Raw Material" style="visibility:hidden">
    Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>

