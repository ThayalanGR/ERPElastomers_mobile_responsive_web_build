<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Choose Invoice</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width='50%'>Invoice Reference</th>
            <th align="left" >
				<input name="dcRef" id="dcRef" tabindex="1" >
				<input  type="button" value="Go" tabindex="2" onclick="createAutoComplete();" />
			</th>
		</tr>
    </table>
	</form>
	<br/>
    <div id="window_list_head">
        <strong>Invoice Details</strong>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="8%" align="left" title="Invoice Number">Inv Id</th>
                <th width="8%" align="left" title="Invoice date">Inv Date</th>
                <th width="20%" align="left">Customer</th>
                <th width="10%" align="left" >Part Number</th>
                <th width="15%" align="left" >Description</th>
                <th width="10%" align="right" >Qty</th>
                <th width="10%" align="right" >Value</th>
				<th width="10%" align="left" >Created By</th>
				<th align="right" >#</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
	<div id="delete_dialog">
</div>
	



