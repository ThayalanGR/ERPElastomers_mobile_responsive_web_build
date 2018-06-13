<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
.supplier_list{overflow:auto;}
#content_body{margin-left:7px;margin-top:5px;}
</style>
<?php
	global $invoiceTypes;
	// Get Invoice No.
	$codeNo		=	@getRegisterData("cmpdInv");
	$codeNo		=	explode("-", $codeNo);
	$codeNo		=	$codeNo[count($codeNo)-1];
	
	$invTypeItems	=	"<option></option>";
	foreach($invoiceTypes as $key=>$value){
		if( $key != 'cmpd')
			$invTypeItems	.=	"<option value='".$key."'>".$value."</option>";
		else
			$invTypeItems	.=	"<option value='".$key."'>Sample</option>";
	}	
?>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Invoice</strong>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body">
                <div class="invoice_heading">
                    Customer
                </div>
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                    <tr>
                        <td width="20%">
                            Invoice Reference
                        </td>
                        <td id="new_InvRef" height="22px" style="width:30%">
                            <?php echo $codeNo; ?>
                        </td>
                        <td width="20%">
                            Invoice Date
                        </td>
                        <td id="new_InvDate" height="22px">
                            <?php echo date("d/m/Y"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Customer Name
                        </td>
                        <td>
                            <input type="text" id="new_CustID" name="new_CustID" style="width:75%;" tabindex="1" onchange="getCustomerDetails()" />
                        </td>
                        <td>
                            Vendor Code
                        </td>
                        <td id="new_VendorCode" height="22px">
                            -
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="padding-top:10px;">
                            Shipment Address
                        </td>
                        <td>
                            <textarea id="new_ShipAddr" name="new_ShipAddr" style="width:75%;height:75px;" tabindex="2" readonly></textarea>
                        </td>
                        <td valign="top" style="padding-top:10px;">
                            Billing Address
                        </td>
                        <td height="22px">
                            <textarea id="new_BillAddr" name="new_BillAddr" style="width:75%;height:75px;" tabindex="2" readonly></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Payment Terms
                        </td>
                        <td height="22px">
                            <input type="text" id="new_PayTerms" name="new_PayTerms" style="width:40%;" tabindex="3" value="-" />
                        </td>
                        <td>
                            Shipment Date
                        </td>
                        <td>
                            <input type="text" rel="datepicker" name="new_ShipDate" id="new_ShipDate" value="<?php echo date("d/m/Y"); ?>" style="width:50%" tabindex="3" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0px 5px 4px">
                            Invoice for 
                        </td>
                        <td height="22px" colspan="3">
							<select name="new_Billfor" id="new_Billfor" onchange="changeLayout();" tabindex="4">
								<?php print $invTypeItems; ?>
							</select>                            
                        </td>
                    </tr>					
                </table>
                <br /><br />
				
                <div class="invoice_heading">
                    Particulars 
                </div>
                <div class="supplier_list_head" style="margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <th width="5%"  align="center">No</th>
                            <th width="20%" align="left">Item</th>
                            <th width="12%" align="left">PO. Ref.</th>
                            <th width="13%" align="left">PO. Date</th>
                            <th width="10%" align="left">Item Ref.</th>
                            <th width="10%" align="right">Avl. Qty</th>
                            <th width="10%" align="right">Apl. Qty</th>
                            <th width="10%" align="right">Rate</th>
                            <th align="right">Value</th>
                        </tr>
                    </table>
                    <div class="supplier_list" id="new_Particulars" style="height:auto">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th width="5%"  align="center">&nbsp;</th>
                                <th width="20%" align="left">&nbsp;</th>
								<th width="12%" align="left">&nbsp;</th>
                                <th width="13%" align="left">&nbsp;</th>
                                <th width="10%" align="left">&nbsp;</th>
                                <th width="10%" align="right">&nbsp;</th>
                                <th width="10%" align="right">&nbsp;</th>
                                <th width="10%" align="right">&nbsp;</th>
                                <th align="right">&nbsp;</th>
                            </tr>
                        </table>
                    </div>
					<div id='toolDesc'></div>
					
                </div>
                <br />
                <table border="0" cellpadding="5" cellspacing="0" class="new_form_table" style="padding-right:6px;">
                    <tr>
                        <th align="right" style="padding-right:6%;font-size:12px;">
                            Total
                        </th>
                        <th id="total_field" style="font-family:arial;font-size:18px;width:15%;text-align:right;border-top:2px double #ccc;border-bottom:2px solid #ccc;">
                            0.00
                        </th>
                    </tr>
                </table>
                <br /><br />
                <div class="invoice_heading">
                    Others
                </div>
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                        <td align="right">
                            <table border="0" cellpadding="3" cellspacing="0" width="50%">
                                <tr height="28px">
                                    <td>
                                        Taxable Value
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td style="text-align:right;border-top:1px double #ccc;border-bottom:1px solid #ccc;">
                                        <span id="taxableval_out">0.00</span>
                                    </td>
                                </tr> 							
                                <tr height="28px">
                                    <td width="50%">
                                        CGST
                                    </td>
                                    <td width="20%">
                                        <span id="cgst">0</span> %
                                    </td>
                                    <td align="right">
                                        <span id="cgst_out">0.00</span>
                                    </td>
                                </tr>
                                <tr height="28px">
                                    <td>
                                        SGST
                                    </td>
                                    <td>
                                        <span id="sgst">0</span> %
                                    </td>
                                    <td align="right">
                                        <span id="sgst_out">0.00</span>
                                    </td>
                                </tr>
                                <tr height="28px">
                                    <td>
                                        IGST
                                    </td>
                                    <td>
                                        <span id="igst">0</span> %
                                    </td>
                                    <td align="right">
                                        <span id="igst_out">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" align="right" style="padding-right:10%;font-size:12px;">
                                        Grand Total
                                    </th>
                                    <th id="grandtotal_out" style="font-family:arial;font-size:18px;text-align:right;border-top:2px double #ccc;border-bottom:2px solid #ccc;">
                                        0.00
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br />
                <div class="invoice_heading">
                    Remarks
                </div>
                <textarea style="width:99%;height:100px" id="new_Remarks" tabindex="8"></textarea>
                <br /><br />
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="7" width="100%">
            <tr>
                <td id="error_msg" style="padding:7px;">&nbsp;</td>
                <td width="20%" align="right">
                    <button id="button_add" type="submit">Create</button>
                    <button id="button_cancel">Clear</button>
                </td>
            </tr>
        </table>
    </form>
	<div id="create_dialog">
	<div id="receive_dialog">
</div>
