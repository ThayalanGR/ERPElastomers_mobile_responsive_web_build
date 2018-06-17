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
<form action="" onsubmit="return false;">
    <div class="row justify-content-center text-primary" style="padding-top: 65px;" >
        <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Invoice']; ?>"></i> Invoice</div>
        <div class="col-12 text-center ">Customer</div>
        <div class="col-12 mt-2">
            <div class="container-fluid shadow" style="font-size:15px;">
                <div class="row bg-light ">
                    <div class="col" > Invoice Reference</div>
                    <div class="col" id="new_InvRef" >
                        <?php echo $codeNo; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col"> Invoice Date</div>
                    <div class="col text-success" id="new_InvDate">
                        <?php echo date("d/m/Y"); ?>
                    </div>
                </div>

                <div class="row bg-light">
                    <div class="col"> Customer Name</div>
                    <div class="col text-success">
                    <input type="text" id="new_CustID" name="new_CustID" onchange="getCustomerDetails()" />
                    </div>
                </div>
                <div class="row">
                    <div class="col"> Vendor Code</div>
                    <div class="col text-success" id="new_VendorCode">-</div>
                </div>
                <div class="row bg-light">
                    <div class="col"> Shipment Address </div>
                    <div class="col text-success">
                    <textarea id="new_ShipAddr" name="new_ShipAddr" style="height:75px;" readonly></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col"> Billing Address</div>
                    <div class="col text-success">
                    <textarea id="new_BillAddr" name="new_BillAddr" style="height:75px;" tabindex="2" readonly></textarea>
                    </div>
                </div>
                <div class="row bg-light mt-2">
                    <div class="col"> Payment Terms</div>
                    <div class="col text-success"> 
                        <input type="text" id="new_PayTerms" name="new_PayTerms" value="-" />
                    </div>
                </div>
                <div class="row  mt-2">
                    <div class="col">  Shipment Date</div>
                    <div class="col text-success"> 
                    <input type="text" rel="datepicker" name="new_ShipDate" id="new_ShipDate" value="<?php echo date("d/m/Y"); ?>" style="width:50%" tabindex="3" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                    </div>
                </div>
                <div class="row bg-light mt-2">
                    <div class="col">Invoice for </div>
                    <div class="col text-success">
                        <select name="new_Billfor" id="new_Billfor" onchange="changeLayout();">
                            <?php print $invTypeItems; ?>
                        </select> 
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-12 mt-2">
            <div class="container-fluid">
                <div class="row justify-content-center"> Particulars </div>
                <div class="row">
                    <div style="overflow-x:auto;">
                        <div class="supplier_list_head" style="margin-right:5px;">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <th   align="center">No</th>
                                    <th  align="left">Item</th>
                                    <th  align="left">PO. Ref.</th>
                                    <th  align="left">PO. Date</th>
                                    <th  align="left">Item Ref.</th>
                                    <th  align="right">Avl. Qty</th>
                                    <th  align="right">Apl. Qty</th>
                                    <th  align="right">Rate</th>
                                    <th align="right">Value</th>
                                </tr>
                            </table>
                            <div class="supplier_list" id="new_Particulars" style="height:auto">
                                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                    <tr>
                                        <th  align="center" >&nbsp;</th>
                                        <th  align="left"  >&nbsp;</th>
                                        <th  align="left"  >&nbsp;</th>
                                        <th  align="left">&nbsp;</th>
                                        <th  align="left">&nbsp;</th>
                                        <th  align="right">&nbsp;</th>
                                        <th  align="right">&nbsp;</th>
                                        <th  align="right">&nbsp;</th>
                                        <th align="right">&nbsp;</th>
                                    </tr>
                                </table>
                            </div>
                            <div id='toolDesc'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="container-fluid shadow text-success" style="font-size:15px;">
                <div class="row justify-content-center text-primary">Others</div>
                <div class="row bg-light ">
                    <div class="col" > Total</div>
                    <div class="col" id="total_field" >0.00</div>
                </div>
                <div class="row  ">
                    <div class="col" >Taxable Value</div>
                    <div class="col" id="taxableval_out">0.00</div>
                </div>
                <div class="row bg-light ">
                    <div class="col" > CGST&nbsp;(<span id="cgst">0</span>)</div>
                    <div class="col" id="cgst_out" >0.00</div>
                </div>
                <div class="row bg-">
                    <div class="col" > SGST&nbsp;(<span id="sgst">0</span>)</div>
                    <div class="col" id="sgst_out" >0.00</div>
                </div>
                <div class="row bg-light ">
                    <div class="col" > IGST&nbsp;(<span id="igst">0</span>)</div>
                    <div class="col" id="igst_out" >0.00</div>
                </div>
                <div class="row bg-dark text-primary ">
                    <div class="col" > Grand Total</div>
                    <div class="col" id="grandtotal_out" >0.00</div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2 ">
            <div class="container-fluid bg-light shadow">
                <div class="row justify-content-center">Remarks:</div>
                <div class="row justify-content-center"> <textarea style="height:50px width:100%;" id="new_Remarks"></textarea></div>
                <div class="row">&nbsp;</div>
            </div>
        </div>
        <div id="error_msg" class="col-12 mt-1 text-center text-danger" style="diplay:none" >&nbsp;</td>
    </div>

    <div class="row justify-content-center mt-5 mb-5 text-primary" onsubmit="return false;">
        <div class="col-5 mr-2"><button class="btn  btn-primary btn-sm" id="button_add" type="submit">Create </button></div>
        <div class="col-5 mb-3"><button class="btn btn-danger btn-sm" id="button_cancel">Clear</button> </div>
    </div>
</form>

<div style="display:none">
    <div id="create_dialog"></div>
	<div id="receive_dialog"></div>
</div>








