<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
#window_list{padding-left:7px;padding-top:5px;}
</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Schedule Entry</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body">
                <div class="invoice_heading">
                    Customer
                </div>
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td>
                            Customer Name
                        </td>
                        <td>
                            <input type="text" id="new_CustID" tabindex="1" name="new_CustID" style="width:75%;" />
                        </td>
                    </tr>
					<tr >
                        <td>
                            Select Required Date
                        </td>
                        <td>
                            <input type="date" name="schdate" id="schdate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?>"  /> 
                        </td>
                    </tr>
					<tr>
                        <td>
                            Choose a file to upload
                        </td>
                        <td>
                            <input id="file" name="file" type="file" accept=".csv" style="width:30%" tabindex="1" /> &nbsp; &nbsp;<input id="sch_submit" type="submit" value="Upload Schedule" />
                        </td>					
					</tr>
                </table>
                <br />
                <div class="invoice_heading">
                    Compound
                </div>
                <div class="supplier_list_head" style="margin-right:5px;" >
                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <th width="20%" align="center">CPD Code</th>
							<th width="20%" align="center">CPD Remarks</th>
                            <th width="20%" align="center">CPD Desc.</th>
                            <th width="10%" align="center">Sch Qty</th>
                            <th width="15%" align="center">Rate</th>
                            <th align="center">Value</th>
                            <th class="last1">&nbsp;</th>
                        </tr>
                         <tr>
                        	<td colspan="9" style="padding:0px;">
                                <div class="supplier_list" id="new_Particulars" style="height: 320px;">
                                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                        <tr>
                                            <th width="20%" align="left">&nbsp;</th>
                                            <th width="20%" align="left">&nbsp;</th>											
                                            <th width="20%" align="center">&nbsp;</th>
                                            <th width="10%" align="right">&nbsp;</th>
                                            <th width="15%" align="right">&nbsp;</th>
                                            <th align="right">&nbsp;</th>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<th colspan="3" style="text-align:right;border-top:1px solid #ccc;">Total Value</th>
                            <th id="tot_schQty" style="text-align:right;border-top:1px solid #ccc;">0</th>
                            <th id="tot_rate" style="text-align:right;border-top:1px solid #ccc;">0</th>
                            <th id="tot_val" style="text-align:right;border-top:1px solid #ccc;">0</th>
                            <th class="last1" style="text-align:right;border-top:1px solid #ccc;">&nbsp;</th>
                        </tr>	
                    </table>
                </div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="7" width="100%" style="margin-top:5px;">
            <tr>
                <td id="error_msg">&nbsp;
                </td>
                <td width="20%" align="right">
                    <button id="button_add" type="submit">Create</button>
					<button id="button_addandprint" type="submit">Create & Print</button>
                    <button id="button_cancel">Clear</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
</div>
