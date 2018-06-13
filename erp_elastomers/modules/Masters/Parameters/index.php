<?php
	$sql		=	" select * from tbl_uom where status > 0 ";
	$resUOM		=	@getMySQLData($sql);
	$uom		=	$resUOM['data'];
	$uomlist	=	"";
	foreach($uom as $key=>$value){
		$uomlist	.=	"<option value='".$value['sno']."'>".$value['uom_short_name']."</option>";
	}
	
?>

<div id="window_list_wrapper">
    <div id="window_list_head">
        Parameter Master
        <span id="button_add">New</span>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="5%" align="left">Sno</th>
                <th width="20%" align="left">Parameter</th>
                <th width="20%" align="left">Std. Ref.</th>
                <th width="20%" align="left">UOM</th>
                <th width="20%" align="left">Test Method</th>				
                <th align="right" style="padding-right:10px;">#</th>
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

<div id="new_item_form" title="New Param" style="visibility:hidden">
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
	<form action="" onsubmit="return false;">
        <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
            <tr>
                <td style="width:40%">
                    Parameter Name
                </td>
                <td>
                    <input type="text" id="new_paramName" name="new_paramName" style="width:80%" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    Standard Reference
                </td>
                <td>
                    <input type="text" id="new_stdRef" name="new_stdRef" style="width:80%" tabindex="2" />
                </td>
            </tr>		
            <tr>
                <td style="width:40%">
                    UoM
                </td>
                <td>
					<select name="new_uom" id="new_uom" style="width:80%" tabindex="3">
						<option selected value='0'></option>
						<?php print $uomlist; ?>
					</select>					
                </td>
            </tr>
            <tr>
                <td>
                    Test Method
                </td>
                <td>
                    <input type="text" id="new_testMethod" name="new_testMethod" style="width:80%" tabindex="4" />
                </td>
            </tr>
        </table>
        <div class="novis_controls">
            <input type="submit" onclick="getSubmitButton('new_item_form');" />
        </div>
    </form>
</div>


<div id="edit_item_form" title="Edit UoM" style="visibility:hidden">
	<div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
	<form action="" onsubmit="return false;">
        <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
            <tr>
                <td style="width:40%">
                    Parameter Name
                </td>
                <td>
                    <input type="text" id="edit_paramName" name="edit_paramName" style="width:80%" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    Standard Reference
                </td>
                <td>
                    <input type="text" id="edit_stdRef" name="edit_stdRef" style="width:80%" tabindex="2" />
                </td>
            </tr>		
            <tr>
                <td style="width:40%">
                    UoM
                </td>
                <td>
					<select name="edit_uom" id="edit_uom" style="width:80%" tabindex="3">
						<option  value='0'></option>
						<?php print $uomlist; ?>
					</select>					
                </td>
            </tr>
            <tr>
                <td>
                    Test Method
                </td>
                <td>
                    <input type="text" id="edit_testMethod" name="edit_testMethod" style="width:80%" tabindex="4" />
                </td>
            </tr>
        </table>
        <div class="novis_controls">
            <input type="submit" onclick="getSubmitButton('edit_item_form');" />
        </div>
    </form>
</div>


<div id="del_item_form" title="Delete Parameter" style="visibility:hidden">
    Are you Sure to Delete ?
	<div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
</div>
