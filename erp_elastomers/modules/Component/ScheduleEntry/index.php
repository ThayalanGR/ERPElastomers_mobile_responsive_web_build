<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
#window_list{padding-left:7px;padding-top:5px;}
</style>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Upload Schedule</strong>
    </div>
	<form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width='20%'>Schedule for the Month:</th>
            <th align="left" width='20%'>
				<input type="text" rel="datepicker" class="monthOnly" tabindex="1" id="schMonth" style="width:90%" value="<?php echo date("F Y"); ?>" />
			</th>
            <th align="right" width='20%'>Choose a file to upload :</th>
            <th align="left" width='20%'>
				<input id="file" name="file" type="file" accept=".csv" style="width:90%" tabindex="1" /> 
			</th>
            <th align="right" ><input id="sch_submit" type="submit" value="Upload Schedule" /></th>
		</tr>				
    </table>
	
	</form>
	<br/>
    <div id="window_list_head">
        <strong>Schedule Entry</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td>
                            Customer Name
                        </td>
                        <td>
                            <input type="text" id="new_CustID" tabindex="2" name="new_CustID" style="width:75%;" />
                        </td>
                        <td>
                            Month
                        </td>
                        <td>
                            <input type="text" rel="datepicker" class="monthOnly" tabindex="3" id="to_date" style="width:40%" value="<?php echo date("F Y"); ?>" />
                        </td>
                    </tr>
                </table>
                <br />
                <div class="supplier_list_head" style="margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                       <tr class="cmpd" >
                            <th width="10%" align="left">CNT Code</th>
                            <th width="15%" align="left">CNT Desc.</th>
                            <th width="10%" align="right">Py. Month</th>
                            <th width="10%" align="left">Sch No</th>
                            <th width="20%" align="left">Sch Dt.</th>
                            <th width="10%" align="right">Sch Qty</th>
                            <th width="10%" align="right">Rate</th>
                            <th align="right">Value</th>
                            <th class="last1">&nbsp;</th>
                        </tr>					
                        <tr class="cmpd" >
                        	<td colspan="9" style="padding:0px;">
                                <div class="supplier_list" id="new_Particulars1">
                                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                        <tr>
                                            <th width="10%" align="left">&nbsp;</th>
                                            <th width="15%" align="left">&nbsp;</th>
                                            <th width="10%" align="right">&nbsp;</th>
                                            <th width="10%" align="left">&nbsp;</th>
                                            <th width="20%" align="left">&nbsp;</th>
                                            <th width="10%" align="right">&nbsp;</th>
                                            <th width="10%" align="right">&nbsp;</th>
                                            <th align="right">&nbsp;</th>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr class="cmpd" >
                        	<th colspan="5" style="text-align:right;border-top:1px solid #ccc;">Total Value</th>
                            <th id="tot_schQty1" style="text-align:right;border-top:1px solid #ccc;">0</th>
                            <th id="tot_rate1" style="text-align:right;border-top:1px solid #ccc;">0</th>
                            <th id="tot_val1" style="text-align:right;border-top:1px solid #ccc;">0</th>
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
                    <button id="button_cancel">Clear</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
</div>
