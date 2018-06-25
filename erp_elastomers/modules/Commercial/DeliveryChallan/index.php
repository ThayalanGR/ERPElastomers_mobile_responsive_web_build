<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
.supplier_list{overflow:auto;}
#content_body{margin-left:7px;margin-top:5px;}
</style>
<?php
	$codeNo		=	@getRegisterData("dccode");
?>

<form action="" onsubmit="return false;">
    <div class="row justify-content-center text-primary" style="padding-top: 65px;" >
        <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Delivery Challan']; ?>"></i> Delivery Challan</div>
        <div class="col-12 text-center ">Customer</div>
        <div class="col-12 mt-2">
            <div class="container-fluid shadow" style="font-size:15px;">
                <div class="row bg-light ">
                    <div class="col" >  DC Type</div>
                    <div class="col" >
                        <select tabindex="1" id="new_DCType"  onchange="getCustomerDetails();">
                            <option value='cmpd'>Component</option>
                            <option value='cpd' selected>Compound</option>
                            <option value='ram'>Raw Material</option>				
                        </select>       
                    </div>
                </div>
                <div class="row bg-light">
                    <div class="col"> Customer Name</div>
                    <div class="col text-success">
                        <input type="text" id="new_CustID" name="new_CustID"   onchange="getCustomerDetails()" />
                        <input type="hidden" id="new_CustEmail" name="new_CustEmail" />
                    </div>
                </div>
                <div class="row">
                    <div class="col"> DC Reference	</div>
                    <div class="col text-success" id="new_DCRef">
                        <?php echo $codeNo; ?>							
                    </div>
                </div>
                <div class="row bg-light">
                    <div class="col"> DC Date </div>
                    <div class="col text-success" id="new_DCDate">
                        <?php echo date("d-m-Y"); ?>                    
                    </div>
                </div>
                <div class="row">
                    <div class="col"> DC Remarks</div>
                    <div class="col text-success">
                        <textarea id="new_DCRemarks" name="new_DCRemarks" style="height:75px;" ></textarea>						
                    </div>
                </div>
                <div class="row bg-light mt-2">
                    <div class="col"> Shipment Date</div>
                    <div class="col text-success"> 
                        <input type="date" name="new_ShipDate" id="new_ShipDate" value="<?php echo date("Y-m-d"); ?>"  />
                    </div>
                </div>
                <div class="row  mt-2">
                    <div class="col-8"> Include Master Batches?</div>
                    <div class="col text-success"> 
                        <input type="checkbox" id="new_IncMaster" name="new_IncMaster" onchange="getCustomerDetails();"/>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-12 mt-2">
            <div class="container-fluid">
                <div class="row justify-content-center"> Particulars </div>
                <div class="row" id="test"></div>
                <div class="row">
                    <div style="overflow-x:auto;">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="container-fluid shadow text-success" style="font-size:15px;">
                <div class="row justify-content-center text-primary bg-dark ">Total</div>
                <div class="row bg-light ">
                    <div class="col" > Total</div>
                    <div class="col" id="total_field" >0.00</div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-1 text-center " style="diplay:none; font-size:10px;" >
            <div   class="container-fluid shadow text-success" >
               <div class="row justify-content-center text-danger" id="error_msg" style="font-size:10px;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-2 mb-5 text-primary"  onsubmit="return false;">
        <div class="col mb-1"><button class="btn  btn-success btn-sm" id="button_add" style="font-size:10px; padding:0px;" type="submit">Create </button></div>
        <div class="col mb-1"><button class="btn  btn-primary btn-sm"  id="button_adddc" style="font-size:10px; padding:0px;" type="submit">Create & Add to Moulding <br> Compound Store</button></div>
        <div class="col mb-1"><button class="btn btn-danger btn-sm" id="button_cancel" style="font-size:10px;  padding:0px;">Clear</button> </div>
    </div>
</form>

