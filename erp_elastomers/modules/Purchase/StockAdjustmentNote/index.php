<style>
.grn_list{width:100%;height:180px;margin-top:10px;border:1px solid #ccc;}
.grn_list table tr td{cursor:pointer;border-bottom:1px solid #efefef;}
</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Purchase - Stock Adjustment Note</strong>
        <span id="add_stkadjnote" class="button_add">Add</span>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="10%" align="left" title="Stock Adjustment Note Reference">SAN. Ref.</th>
                <th width="15%" align="left" title="Stock Adjustment Note Date">SAN. Date</th>
                <th width="15%" align="left" title="Key Reference">RAM Ref.</th>
                <th width="20%" align="left" title="Date">RAM Name</th>
                <th width="20%" align="left" title="Date">RAM Grade</th>
                <th align="right">#</th>
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

<div style="display:none">
    <div id="new_san_dialog">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                <tr>
                    <td width="20%">Raw Material</th>
                    <td><input type="text" style="width:70%" class="ram_auto_list" /></th>
					<td align="right" width="20%">&nbsp;</th>
                </tr>
            </table>
            <div class="supplier_list_head">
                <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                    <tr>
                        <th width="20%" align="left">GRN</th>
                        <th width="21.6%" align="left">Date</th>
                        <th width="6.8%" align="left">UoM</th>
                        <th width="16.6%" align="right">Book Qty.</th>
                        <th width="16.6%" align="right">Physical Qty.</th>
                        <th align="right">Excess/Shortage</th>
                        <th class="last">&nbsp;</th>
                    </tr>
                    <tr>
                    	<td colspan="7" style="padding:0px;">
                            <div class="supplier_list">
                                <table border="0" cellspacing="0" cellpadding="2" style="width:100%" class="key_list">
                                    <tr>
                                        <th width="20.6%">&nbsp;</th>
                                        <th width="22%">&nbsp;</th>
                                        <th width="7%">&nbsp;</th>
                                        <th width="17%">&nbsp;</th>
                                        <th width="17%">&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<th colspan="3" align="right" style="border-top:1px solid #ccc;border-bottom:0px;">
                        	Total
                        </th>
                        <th align="right" style="border-top:1px solid #ccc;border-bottom:0px;" class="tbook_qty">0.000</th>
                        <th align="right" style="border-top:1px solid #ccc;border-bottom:0px;" class="tphy_qty">0.000</th>
                        <th align="right" style="border-top:1px solid #ccc;border-bottom:0px;" class="texcshrt_qty">0.000</th>
                    </tr>
                </table>
            </div>
            <br />
            <div class="supplier_list_head">
            	<div class="head">Remarks:</div>
                <textarea id="san_remarks" style="height:50px;"></textarea>
            </div>
            <input type="submit" onclick="getSubmitButton('new_san_dialog');" style="visibility:hidden;width:1px;height:1px;" />
        </form>
    </div>
    
    <div id="view_san_dialog">
    	<div class="supplier_list_head" style="margin-top:10px;">
        	<table border="0" cellspacing="0" cellpadding="5">
            	<tr>
                	<th align="left" width="20%">GRN Ref</th>
                	<th align="left" width="20%">GRN Date</th>
                	<th align="left">UoM</th>
                	<th align="right" width="15%">Book Qty.</th>
                	<th align="right" width="15%">Physical Qty.</th>
                	<th align="right" width="15%">Exc. / Shrt.</th>
                    <th align="center" class="last1"></th>
                </tr>
            </table>
        	<div class="supplier_list" id="ViewSANList">
                <table border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <th width="20%"></th>
                        <th width="20%"></th>
                        <th></th>
                        <th width="15%"></th>
                        <th width="15%">.</th>
                        <th width="15%"></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
