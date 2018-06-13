<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Quality Check Note</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="8%" align="left">GRN ID</th>
                <th width="10%" align="left">GRN Date</th>
                <th width="20%" align="left">Supplier Name</th>
                <th width="18%" align="left">RawMaterial Name</th>
                <th width="8%" align="left">Grade</th>
                <th width="8%" align="right">GRN Qty</th>
                <th width="10%" align="center">DoM/DoE</th>
				<th width="10%" align="center">DoM/DoE Date</th>
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
    <div id="qc_popup" title="Quality Check" >
		<div style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;" style="padding:0px;margin:8px 0 0 0;">
            <input type="hidden" class="ramid" value="" />
            <input type="hidden" class="grnid" value="" />
            <input type="hidden" class="purid" value="" />
            <div class="supplier_list_head">
                <table border="0" cellspacing="0" cellpadding="6">
                    <tr>
                        <th style="width:90px" align="left">Raw Material</th>
                        <th style="width:80px" align="left">Parameter</th>
                        <th style="width:60px" align="right">Spec</th>
                        <th style="width:90px" align="right">Lower Limit</th>
                        <th style="width:90px" align="right">Upper Limit</th>
                        <th style="width:90px" align="right">Sample Plan</th>
                        <th style="width:90px" align="right">Observation</th>
                        <th class="scroll">&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="8" style="padding:0px; width:100%">
                            <div class="supplier_list" id="quality_chk_comp" style="height:90px; width:100%">
                                <table border="0" cellspacing="0" cellpadding="6" style="width:100%;">
                                    <tr>
                                        <th style="width:90px" align="left">&nbsp;</th>
                                        <th style="width:80px" align="left">&nbsp;</th>
                                        <th style="width:60px" align="right">&nbsp;</th>
                                        <th style="width:90px" align="right">&nbsp;</th>
                                        <th style="width:90px" align="right">&nbsp;</th>
                                        <th style="width:90px" align="right">&nbsp;</th>
                                        <th style="width:90px"align="right">&nbsp;</th>
                                        <th align="right">&nbsp;</th>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>          
            <table border="0" cellspacing="0" cellpadding="6" style="width:100%;margin-top:5px;">
               <tr>
                   <th align="left" valign="top" width="30%">
                        Test Certificate Details:
                    </th>
                    <td>
                        <input type="text" id="tstcertdet" value="" style="width:75%;" />
                    </td>	
                </tr>			
                <tr>
                    <th align="left" valign="top" width="30%">
                        Remarks
                    </th>
                    <td>
                        <textarea id="remarks" style="width:100%;height:40px;"></textarea>
                    </td>
                </tr>
                <tr>
					<th align="left" valign="top" width="30%">
                        Raw Material:
                    </th>				
                    <td>
                        <div id="qty_status">
                            <input type="radio" name="qty_stat" id="approved" checked="checked" value="1" /><label for="approved">Approved</label>
                            <input type="radio" name="qty_stat" id="rejected" value="0" /><label for="rejected">Rejected</label>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
