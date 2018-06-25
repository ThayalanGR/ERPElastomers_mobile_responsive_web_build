<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Store Receipt</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="10%" align="left">Batch ID</th>
                <th width="10%" align="left">QC Date</th>
                <th width="20%" align="left">Compound Name</th>
                <th width="15%" align="left">Base Polymer</th>
                <th width="12%" align="right">Base Polymer Wt</th>
                <th width="12%" align="right">Expected Wt</th>
                <th width="12%" align="right">Mixed Wt</th>
                <th align="right">Yield</th>
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
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td colspan="8">&nbsp;</td>
            <td align="right">
					<button id="button_submit" type="submit">Update</button>
			</td>
        </tr>
    </table>
	</form>
     </div>		 	
</div>

<div style="display:none">
    <div id="update_item_form" title="Update Mixing Plan" style="visibility:hidden">
        Are you sure to inward all Items ?
        <div id="update_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
    </div>		
</div>
