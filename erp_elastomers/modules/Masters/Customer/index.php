<?php

	global $customerGroup,$stateList;	
	
	$isEmpty		=	false;	
	$cusgrpitems	=	"";
	foreach($customerGroup as $key=>$value){
		$cusgrpitems	.=	"<option>".$value."</option>";
	}
	if($cusgrpitems != "")
		$cusgrplist	=	"<option></option>".$cusgrpitems;
	else
		$isEmpty	=	true;

	$cusStateItems	=	"<option value=''></option>";
	foreach($stateList as $key=>$value){
		$cusStateItems	.=	"<option value='".$key."'>".$value."</option>";
	}	
	
?>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Customer Master</strong>
        <span id="button_add">New</span>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr class="ram_rows_head">
                <th align="left" width="8%">Code</th>
                <th align="left" width="8%">Reference No.</th>
                <th align="left" width="20%">Customer Name</th>
                <th align="left" width="15%">Contact Person</th>
                <th align="left" width="18%">Email</th>
                <th align="left" width="12%">Mobile</th>
                <th align="left" width="12%">Business Group</th>
                <th>#</th>
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
    <div id="new_item_form" class="window" title="New Customer" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <div id="new_item_accord" class="accordion">
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Customer Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Vendor Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_RefNo" name="new_RefNo" style="width:95%;" tabindex="1" />
                            </td>
                            <td class="row2_head">
                                Customer Name
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_CustName" name="new_CustName" style="width:95%;" tabindex="1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Address1
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CustAddr1" name="new_CustAddr1" style="width:95%;" tabindex="2"></input>
                            </td>
                            <td class="row2_head">
                                Phone
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Phone" name="new_Phone" style="width:95%;" tabindex="4" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Address2
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_CustAddr2" name="new_CustAddr2" style="width:95%;" tabindex="2"></input>
                            </td>
                            <td class="row2_head">
                                Fax
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Fax" name="new_Fax" style="width:95%;" tabindex="5" />
                            </td>
                        </tr>						
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
								Place
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_SupplyPlace" name="new_SupplyPlace" style="width:75%" tabindex="2"  />
                            </td>                            
                            <td class="row2_head">
                                Email
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Email" name="new_Email" style="width:95%;" tabindex="6" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Pin / Distance(Km)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_PinCode" name="new_PinCode" style="width:45%;" tabindex="2" />
								<input type="text" id="new_Distance" name="new_Distance" style="width:45%;" tabindex="2" />
                            </td>
                            <td class="row2_head">
                                Mobile
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Mobile" name="new_Mobile" style="width:95%;" tabindex="7" />
                            </td>
                        </tr>
                        <tr>
							<td class="row1_head">
                                Contact Person
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_ContName" name="new_ContName" style="width:95%;" tabindex="3" />
                            </td>
                            <td class="row2_head">
                                Business Group
                            </td>
                            <td class="row2_cont">
								<?php if ($isEmpty == true) { ?>
									<input type="text" id="new_Biz" name="new_Biz" style="width:95%" tabindex="8" onkeydown="openAccordion(this, event, 'new', 1)" />
								<?php } else { ?>
									<select id="new_Biz" name="new_Biz" tabindex="8" onkeydown="openAccordion(this, event, 'new', 1)" />
										<?php echo $cusgrplist; ?>
									</select>
								<?php }  ?>
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                    Commercial Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Bank Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_BankName" name="new_BankName" style="width:95%;" tabindex="9" />
                            </td>
                            <td class="row2_head">
                                Bank Account No.
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_BankNo" name="new_BankNo" style="width:95%" tabindex="10" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                IFSC Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_IFSCCode" name="new_IFSCCode" style="width:95%;" tabindex="11" />
                            </td>
                            <td class="row2_head">
                                Credit Terms
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_Credit" name="new_Credit" style="width:50%" tabindex="12" /> 
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Freight
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_Freight" name="new_Freight" style="width:95%;" tabindex="12"  />
                            </td>
                            <td class="row2_head">
                                Multi-Item Invoice
                            </td>
                            <td class="row2_cont">
                                <input type="checkbox" id="new_MultiInvoice" name="new_MultiInvoice" tabindex="12"  />
                            </td>							
                        </tr>
                       <tr>
                            <td class="row1_head">
                                Transporter Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_TransName" name="new_TransName" style="width:95%;" tabindex="12" />
                            </td>
                            <td class="row2_head">
                                Transporter Id
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_TransId" name="new_TransId" tabindex="12"  onkeydown="openAccordion(this, event, 'new', 2)"/>
                            </td>							
                        </tr>						
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Statutory Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                PAN No
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="new_PANNo" name="new_PANNo" style="width:75%" tabindex="13" />
                            </td>
                            <td class="row2_head" style="padding-left:0px;">
                                GST No
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="new_GSTNo" name="new_GSTNo" style="width:95%" tabindex="14"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                &nbsp;
                            </td>
                            <td class="row1_cont">
                                &nbsp;
                            </td>
                            <td class="row2_head" style="padding-left:0px;">
                                GST Supply State
                            </td>
                            <td class="row2_cont">
								<select id="new_SupplyState" name="new_SupplyState" tabindex="16" onkeydown="openAccordion(this, event, 'new', 5)" />
									<?php echo $cusStateItems; ?>
								</select>							
                            </td>
                        </tr>						
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approved Compounds
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="new_CompBtn">Add</span>
                    </div>
                    <div class="supplier_list_head" style="border:1px solid #ccc;">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                            <tr>
                                <th align="left" width="4.8%">Sno</th>
                                <th align="left" width="29.5%">Compound Name</th>
                                <th align="left" width="24.5%">P.O. Ref.</th>
                                <th align="left" width="19.6%">P.O. Date</th>
                                <th align="left" width="14.6%">Rate</th>
                                <th>#</th>
                                <th class="last1" />
                            </tr>
                        </table>
                        <div class="supplier_list">
                            <table border="0" cellspacing="0" cellpadding="5" style="width:100%" id="new_AppComp">
                                <tr>
                                    <th align="left" width="5%">&nbsp;</th>
                                    <th align="left" width="30%">&nbsp;</th>
                                    <th align="left" width="25%">&nbsp;</th>
                                    <th align="left" width="20%">&nbsp;</th>
                                    <th align="left" width="15%">&nbsp;</th>
                                    <th>#</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approved Components
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="new_CmpdBtn">Add</span>
                    </div>
                    <div class="supplier_list_head" style="border:1px solid #ccc;">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                            <tr>
                                <th align="left" width="4.8%">Sno</th>
                                <th align="left" width="22.5%">Component Name</th>
                                <th align="left" width="18.5%">P.O. Ref.</th>
                                <th align="left" width="15.6%">P.O. Date</th>
                                <th align="left" width="10.6%">Rate</th>
								<th align="left" width="10.6%">Qty</th>
								<th align="left" width="10.6%">Insert Value</th>
                                <th>#</th>
                                <th class="last1" />
                            </tr>
                        </table>
                        <div class="supplier_list">
                            <table border="0" cellspacing="0" cellpadding="3" style="width:100%" id="new_AppCmpd">
                                <tr>
                                    <th align="left" width="5%">&nbsp;</th>
                                    <th align="left" width="23%">&nbsp;</th>
                                    <th align="left" width="18%">&nbsp;</th>
                                    <th align="left" width="15%">&nbsp;</th>
                                    <th align="left" width="11%">&nbsp;</th>
                                    <th align="left" width="11%">&nbsp;</th>
									<th align="left" width="11%">&nbsp;</th>									
                                    <th>#</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approval
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Approved By
                            </td>
                            <td class="row1_cont">
                                <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" id="new_AppUser" name="new_AppUser" style="width:90%" tabindex="19" readonly="readonly" />
                            </td>
                            <td class="row2_head">
                                Approved Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" value="<?php echo date("d/m/Y"); ?>" id="new_AppDate" name="new_AppDate" style="width:90%" tabindex="20" readonly="readonly" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Remarks
                            </td>
                            <td colspan="3">
                                <textarea id="new_Remarks" name="new_Remarks" style="width:96%;height:80px;" tabindex="21"></textarea>
                            </td>
                        </tr>
                    </table>
                </span>
            </div>
            <div class="novis_controls">
                <input type="submit" tabindex="22" onclick="getSubmitButton('new_item_form');" />
                <input type="clear" tabindex="23" onclick="getSubmitButton('new_item_form');" />
            </div>
        </form>
    </div>
    
    <div id="edit_item_form" class="window" title="Edit Customer" style="visibility:hidden">
        <div id="edit_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
        <form action="" onsubmit="return false;">
            <div id="edit_item_accord" class="accordion">
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Customer Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Vendor Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_RefNo" name="edit_RefNo" style="width:95%;" tabindex="1" />
                            </td>
                            <td class="row2_head">
                                Customer Name
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_CustName" name="edit_CustName" style="width:95%;" tabindex="1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Address1
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CustAddr1" name="edit_CustAddr1" style="width:95%;" tabindex="2"></input>
                            </td>
                            <td class="row2_head" valign="top" style="padding-top:5px;">
								Phone
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Phone" name="edit_Phone" style="width:75%" tabindex="4"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Address2
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_CustAddr2" name="edit_CustAddr2" style="width:95%;" tabindex="2"></input>
                            </td>
                            <td class="row2_head" valign="top" style="padding-top:5px;">
                                Fax
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Fax" name="edit_Fax" style="width:75%;" tabindex="5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Place
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_SupplyPlace" name="edit_SupplyPlace" style="width:95%;" tabindex="2" />
                            </td>
                            <td class="row2_head">
                                Email
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Email" name="edit_Email" style="width:95%;" tabindex="6" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Pin / Distance(Km)
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_PinCode" name="edit_PinCode" style="width:45%;" tabindex="2" />
								<input type="text" id="edit_Distance" name="edit_Distance" style="width:45%;" tabindex="2" />
                            </td>
                            <td class="row2_head">
                                Mobile
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Mobile" name="edit_Mobile" style="width:95%;" tabindex="7" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Contact Person
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_ContName" name="edit_ContName" style="width:95%;" tabindex="3" />
                            </td>
                            <td class="row2_head">
                                Business Group
                            </td>
                            <td class="row2_cont">
								<?php if ($isEmpty == true) { ?>
									<input type="text" id="edit_Biz" name="edit_Biz" style="width:95%" tabindex="8" onkeydown="openAccordion(this, event, 'edit', 1)" />
								<?php } else { ?>
									<select id="edit_Biz" name="edit_Biz" tabindex="8" onkeydown="openAccordion(this, event, 'edit', 1)" />
										<?php echo $cusgrplist; ?>
									</select>
								<?php }  ?>							
                            </td>
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_dark live_screen_patient_rows">
                    Commercial Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Bank Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_BankName" name="edit_BankName" style="width:95%;" tabindex="9" />
                            </td>
                            <td class="row2_head">
                                Bank Account No.
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_BankNo" name="edit_BankNo" style="width:95%" tabindex="10" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                IFSC Code
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_IFSCCode" name="edit_IFSCCode" style="width:95%;" tabindex="11" />
                            </td>
                            <td class="row2_head">
                                Credit Term
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_Credit" name="edit_Credit" style="width:50%" tabindex="12" /> 
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                Freight
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_Freight" name="edit_Freight" style="width:95%;" tabindex="12" />
                            </td>
                            <td class="row2_head">
                                Multi-Item Invoice
                            </td>
                            <td class="row2_cont">
                                <input type="checkbox" id="edit_MultiInvoice" name="edit_MultiInvoice" tabindex="12"  />
                            </td>							
                        </tr>						
                        <tr>
                            <td class="row1_head">
                                Transporter Name
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_TransName" name="edit_TransName" style="width:95%;" tabindex="12" />
                            </td>
                            <td class="row2_head">
                                Transporter Id
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_TransId" name="edit_TransId" tabindex="12"  onkeydown="openAccordion(this, event, 'edit', 2)"/>
                            </td>							
                        </tr>
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Statutory Details
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                PAN No
                            </td>
                            <td class="row1_cont">
                                <input type="text" id="edit_PANNo" name="edit_PANNo" style="width:75%" tabindex="13" />
                            </td>
                            <td class="row2_head" style="padding-left:0px;">
                                GST No
                            </td>
                            <td class="row2_cont">
                                <input type="text" id="edit_GSTNo" name="edit_GSTNo" style="width:95%" tabindex="14"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head">
                                &nbsp;
                            </td>
                            <td class="row1_cont">
                                &nbsp;
                            </td>
                            <td class="row2_head" style="padding-left:0px;">
                                GST Supply State
                            </td>
                            <td class="row2_cont">
								<select id="edit_SupplyState" name="edit_SupplyState" tabindex="16" onkeydown="openAccordion(this, event, 'edit', 5)" />
									<?php echo $cusStateItems; ?>
								</select>							
                            </td>
                        </tr>		
                    </table>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approved Compounds
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_CompBtn">Add</span>
                    </div>
                    <div class="supplier_list_head" style="border:1px solid #ccc;">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                            <tr>
                                <th align="left" width="4.8%">Sno</th>
                                <th align="left" width="29.5%">Compound Name</th>
                                <th align="left" width="22.5%">P.O. Ref.</th>
                                <th align="left" width="14.6%">P.O. Date</th>
                                <th align="right" width="14.6%">Rate</th>
                                <th>#</th>
                                <th class="last1" />
                            </tr>
                        </table>
                        <div class="supplier_list">
                            <table border="0" cellspacing="0" cellpadding="5" style="width:100%" id="edit_AppComp">
                                <tr>
                                    <th align="left" width="5%">&nbsp;</th>
                                    <th align="left" width="30%">&nbsp;</th>
                                    <th align="left" width="23%">&nbsp;</th>
                                    <th align="left" width="15%">&nbsp;</th>
                                    <th align="right" width="15%">&nbsp;</th>
                                    <th>#</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approved Components
                </div>
                <span class="accord_content">
                    <div style="text-align:right;padding-bottom:5px;">
                        <span id="edit_CmpdBtn">Add</span>
                    </div>
                    <div class="supplier_list_head" style="border:1px solid #ccc;">
                        <table border="0" cellspacing="0" cellpadding="5" style="width:100%">
                            <tr>
                                <th align="left" width="4.8%">Sno</th>
                                <th align="left" width="22.5%">Component Name</th>
                                <th align="left" width="18.5%">P.O. Ref.</th>
                                <th align="left" width="15.6%">P.O. Date</th>
                                <th align="left" width="10.6%">Rate</th>
								<th align="left" width="10.6%">Qty</th>
								<th align="left" width="10.6%">Insert Value</th>
                                <th>#</th>
                                <th class="last1" />
                            </tr>
                        </table>
                        <div class="supplier_list">
                            <table border="0" cellspacing="0" cellpadding="5" style="width:100%" id="edit_AppCmpd">
                                <tr>
                                    <th align="left" width="5%">&nbsp;</th>
                                    <th align="left" width="23%">&nbsp;</th>
                                    <th align="left" width="18%">&nbsp;</th>
                                    <th align="left" width="15%">&nbsp;</th>
                                    <th align="left" width="11%">&nbsp;</th>
                                    <th align="left" width="11%">&nbsp;</th>
									<th align="left" width="11%">&nbsp;</th>									
                                    <th>#</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </span>
                <div class="live_screen_patient_rows_light live_screen_patient_rows">
                    Approval
                </div>
                <span class="accord_content">
                    <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                        <tr>
                            <td class="row1_head">
                                Approved By
                            </td>
                            <td class="row1_cont">
                                <input type="text" value="<?php echo $_SESSION['userdetails']['userName']; ?>" id="edit_AppUser" name="edit_AppUser" style="width:90%" tabindex="19" readonly="readonly" />
                            </td>
                            <td class="row2_head">
                                Approved Date
                            </td>
                            <td class="row2_cont">
                                <input type="text" value="<?php echo date("d/m/Y"); ?>" id="edit_AppDate" name="edit_AppDate" style="width:90%" tabindex="20" readonly="readonly" />
                            </td>
                        </tr>
                        <tr>
                            <td class="row1_head" valign="top" style="padding-top:5px;">
                                Remarks
                            </td>
                            <td colspan="3">
                                <textarea id="edit_Remarks" name="edit_Remarks" style="width:96%;height:80px;" tabindex="21"></textarea>
                            </td>
                        </tr>
                    </table>
                </span>
            </div>
            <div class="novis_controls">
                <input type="submit" tabindex="22" onclick="getSubmitButton('edit_item_form');" />
            </div>
        </form>
    </div>
    
    <div id="edit_app_comp_form" title="Edit Approved Compounds" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="edit_app_comp_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                <tr>
                    <td style="width:40%">
                        Compound Name
                    </td>
                    <td>
                        <input type="text" style="width:100%" id="edit_AppCompName" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        P.O. Reference
                    </td>
                    <td>
                        <input type="text" style="width:100%" id="edit_AppCompPORef" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        P.O. Date
                    </td>
                    <td>
                        <input type="text" rel="datepicker" style="width:75%" class="invisible_text" value="DD/MM/YYYY" id="edit_AppCompPODate" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        Rate
                    </td>
                    <td>
                        <input type="text" style="width:40%" id="edit_AppCompRate" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>
            </table>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('edit_app_comp_form');" />
                <input type="clear" onclick="getSubmitButton('new_app_comp_form');" />
            </div>
        </form>
    </div>
    
    <div id="edit_app_cmpd_form" title="Edit Approved Components" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="edit_app_cmpd_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                <tr>
                    <td style="width:40%">
                        Component Name
                    </td>
                    <td>
                        <input type="text" style="width:100%" id="edit_AppCmpdName" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        P.O. Reference
                    </td>
                    <td>
                        <input type="text" style="width:100%" id="edit_AppCmpdPORef" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        P.O. Date
                    </td>
                    <td>
                        <input type="text" rel="datepicker" style="width:75%" class="invisible_text" value="DD/MM/YYYY" id="edit_AppCmpdPODate" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        Rate
                    </td>
                    <td>
                        <input type="text" style="width:40%" id="edit_AppCmpdRate" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>
                <tr>
                    <td style="width:40%">
                        Quantity
                    </td>
                    <td>
                        <input type="text" style="width:40%" id="edit_AppCmpdQty" class="invisible_text" value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                    </td>
                </tr>	
                <tr>
                    <td style="width:40%">
                        Insert Value
                    </td>
                    <td>
                        <input type="text" style="width:40%" id="edit_AppCmpdInsVal" class="invisible_text" value="0" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>			
            </table>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('edit_app_cmpd_form');" />
                <input type="clear" onclick="getSubmitButton('new_app_cmpd_form');" />
            </div>
        </form>
    </div>
    
    <div id="del_item_form" title="Delete Customer" style="visibility:hidden">
        Are you Sure to Delete ?
        <div id="del_item_error" style="padding: 5px 7px 7px .7em;margin:10px 0px 0px 0px;font-size:11px;display:none"></div>
    </div>
</div>