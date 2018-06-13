<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
.supplier_list{overflow:auto;}
#content_body{margin-left:7px;margin-top:5px;}
</style>
<?php
	$codeNo		=	@getRegisterData("dccode");
?>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Delivery Challan</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body">
                <div class="invoice_heading">
                    Customer
                </div>
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td  width="20%" >
                             DC Type
                        </td>
                        <td height="22px" style="width:30%" >
							<select tabindex="1" id="new_DCType" style="width:30%" onchange="getCustomerDetails();">
								<option value='cmpd'>Component</option>
								<option value='cpd' selected>Compound</option>
								<option value='ram'>Raw Material</option>				
							</select>                           
                        </td>
                        <td width="20%">
                            Customer Name
                        </td>
                        <td>
                            <input type="text" id="new_CustID" name="new_CustID" style="width:75%;" tabindex="2" onchange="getCustomerDetails()" />
							<input type="hidden" id="new_CustEmail" name="new_CustEmail" />
                        </td>						
                    </tr>					
                    <tr>
                        <td>
                            DC Reference							
                        </td>
                        <td id="new_DCRef" height="22px" >
                            <?php echo $codeNo; ?>							
                        </td>
                        <td>
                            DC Date
                        </td>
                        <td id="new_DCDate">
							<?php echo date("d-m-Y"); ?>                    
                        </td>
                    </tr>					
                    <tr>
                        <td rowspan='2'>
                            DC Remarks							
                        </td>
                        <td rowspan='2'>
                            <textarea id="new_DCRemarks" name="new_DCRemarks" tabindex="3" style="width:75%;height:75px;" ></textarea>						
                        </td>
                        <td>
                            Shipment Date
                        </td>
                        <td>
                            <input type="date" name="new_ShipDate" id="new_ShipDate" value="<?php echo date("Y-m-d"); ?>" style="width:35%" tabindex="4"  />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Include Master Batches?
                        </td>
                        <td>
                            <input type="checkbox" id="new_IncMaster" name="new_IncMaster" tabindex="5" onchange="getCustomerDetails();"/>
                        </td>
                    </tr>					
                </table>
                <div class="invoice_heading">
                    Particulars
                </div>
				<div id="test" />
                <div class="supplier_list_head" style="margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <th width="10%"  align="center">No</th>
                            <th width="30%" align="left">Item Code</th>
                            <th width="35%" align="left">Item Ref</th>
                            <th width="10%" align="right">Avl. Qty</th>
                            <th width="10%" align="right">Apl. Qty</th>
							<th title="Select for Invoicing"><input id="input_select_all" type="checkbox" value="1"></input></th>
                        </tr>
                    </table>
                    <div class="supplier_list" id="new_Particulars" style="height:auto">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="10%"  align="center">&nbsp;</th>
                                <th width="30%" align="left">&nbsp;</th>
                                <th width="35%" align="left">&nbsp;</th>
                                <th width="10%" align="right">&nbsp;</th>
								<th width="10%" align="right">&nbsp;</th>
                                <th align="right">&nbsp;</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <br />
                <table width="100%" border="0" cellpadding="5" cellspacing="0" class="new_form_table" style="padding-right:6px;">
                    <tr>
                        <th align="right" width="85%" style="font-size:12px;">
                            Total
                        </th>
                        <th id="total_field" width="10%" style="font-family:arial;font-size:18px;text-align:right;border-top:2px double #ccc;border-bottom:2px solid #ccc;">
                            0.000
                        </th>
						<th align="right">&nbsp;</th>
                    </tr>
                </table>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="7" width="100%">
            <tr>
                <td id="error_msg" style="padding:7px;">&nbsp;
                </td>
                <td width="40%" align="right">
                    <button id="button_add" type="submit">Create</button>
					<button id="button_adddc" type="submit">Create & Add to Moulding Compound Store</button>
                    <button id="button_cancel">Clear</button>
                </td>
            </tr>
        </table>
    </form>
</div>
