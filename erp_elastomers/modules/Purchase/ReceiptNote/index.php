<?php
	global $grn_customers,$grn_role,$homeState;
	$recvlist	=	"";
	$noofitems	=	0;
	$options	=	"";
	for($ct=0;$ct<count($grn_customers);$ct++){
			$noofitems++;
			$options	.=	"<option role='".$grn_role[$grn_customers[$ct]]."'>".$grn_customers[$ct]."</option>";
	}
	if($noofitems > 1)
		$recvlist	=	"<option selected></option>";
	$recvlist	.=	$options;
	
	$vendorlist	=	"";
	$noofitems	=	0;
	$options	=	"";
	for($ct=0;$ct<count($grn_customers);$ct++){
		if($grn_role[$grn_customers[$ct]] == 'vendor')
		{
			$noofitems++;
			$options	.=	"<option>".$grn_customers[$ct]."</option>";
		}
	}
	if($noofitems > 1)
		$vendorlist	=	"<option selected></option>";
	$vendorlist	.=	$options;

	
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Upload Vendor GRN</strong>		
    </div>
	<form action="" onsubmit="return false;">
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
			<th width="20%" align="right">
				Vendor Name:
			</th>
			<th width="10%" align="left">
				<select id="new_VendID" tabindex="2" >							
					<?php echo $vendorlist;?>
				</select>					
			</th>
			<th align="left">
				<input id="file" name="file" type="file" accept=".csv" style="width:40%" tabindex="2" /> &nbsp; &nbsp;<input id="grn_submit" type="submit" value="Upload GRN" />
			</th>					
		</tr>
	</table>
	</form>			
</div>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Goods Receipt Note</strong>
		<span id="add_grn" class="button_add">Add</span>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="7%" align="left">PO.ID</th>
                <th width="10%" align="left">PO.Date</th>
                <th width="20%" align="left">Supplier Name</th>
                <th width="13%" align="left">RawMaterial Name</th>
                <th width="7%" align="left">Grade</th>
                <th width="10%" align="right">Ordered Qty</th>
                <th width="10%" align="right">Received Qty</th>
                <th width="10%" align="right">Pending Qty</th>
                <th>#</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
		<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="base_error"></div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="grn_popup" title="GRN Issue" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<input type="hidden" name="grn_homestate" id="grn_homestate" value="<?php echo $homeState ?>" />
			<table border="0" cellspacing="0" cellpadding="5" width="100%">
				<tr class='content_rows_light'>
					<td colspan="2">
						Recieved For
					</td>
					<td>
						: 
						<select id="recFrom" tabindex="1" >
							<?php echo $recvlist;?>
						</select>
					</td>				
					<td>
						Recieved On
					</td>					
					<td>
						: <input type="date" name="grn_date" id="grn_date" style="width:85%" tabindex="1" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>"  />
					</td>
				</tr>
				<tr class='content_rows_dark'>
					<td colspan="2">
						Supplier Name
					</td>					
					<td>
						: <input type="text" name="grn_supname"  id="grn_supname" style="width:85%" tabindex="1" readonly />
					</td>
					<td>
						Supplier GST No.
					</td>					
					<td>
						: <span id="grn_supgstn" style="font-weight:bold"></span>
					</td>			
				</tr>
				<tr class='content_rows_light'>
					<td colspan="2">
						Raw Material
					</td>	
					<td>
						: <input type="text" name="grn_rmgrade"  id="grn_rmgrade" style="width:85%" tabindex="1" readonly />
					</td>
					<td>
						HSN Code / Taxrate
					</td>					
					<td nowrap>
						: <input type="text" name="grn_hsncode" id="grn_hsncode" tabindex="1" style="width:50%" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
						<select id="grn_taxrate" tabindex="1">
							<option></option>
							<option>5</option>
							<option>12</option>
							<option>18</option>
							<option>28</option> 
						</select>
					</td>				
				</tr>			
				<tr class='content_rows_dark'>
					<td align="center" width="15%">
						PO/Invoice Ref
					</td>
					<td align="center" width="5%">
						Date
					</td>
					<td align="center"  width="30%">
						Quantity
					</td>
					<td align="center"  width="20%">
						Rate*
					</td>
					<td align="center">
						Value(Rs)
					</td>
				</tr>
				<tr class='content_rows_light' id='po_row'>
					<td align="center">
						<span id="grn_purid" style="font-weight:bold"></span>
					</td>
					<td align="center">
						<span id="grn_purdate" style="font-weight:bold"></span>
					</td align="right">
					<td align="right">
						<span id="grn_rmqty" style="font-weight:bold" >0.000</span> <span class="uom" ></span>
					</td>
					<td align="right">
						<span id="grn_purrate" style="font-weight:bold"></span>
					</td>
					<td align="right" >
						<span id="grn_purtotal" style="font-weight:bold"></span>
					</td>
				</tr>
				<tr class='content_rows_dark'>
					<td align="center">
						<input type="text" id="grn_invref" style="width:95%" tabindex="1" />
					</td>
					<td align="center">
						<input type="date" name="grn_invdate" id="grn_invdate" style="width:85%" tabindex="1" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>"  />
					</td>
					<td align="right">						
						<input type="text" id="grn_recqty" style="width:30%;text-align:right;" value='0.00' class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" tabindex="1" /> <span class="uom"></span>
					</td>
					<td align="right">
						<span id="grn_rate" style="font-weight:bold">0.00</span>
					</td>
					<td align="right">
						<span id="grn_total" style="font-weight:bold">0.00</span>
					</td>
				</tr>
			   <tr class='content_rows_light'>
					<td align="center" colspan="2">
						Item 
					</td>
					<td align="center">
						P.O Value (Rs)
					</td>
					<td align="center">
						Rate (%)
					</td>
					<td align="center">
						Value (Rs)
					</td>					
				</tr>	
				<tr class='content_rows_dark'>
					<td colspan="2">
						Item Amount
					</td>
					<td>
						<span id="grn_purtotal1" style="font-weight:bold"></span>
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<input type="text" id="grn_invAmount" style="width:95%;text-align:right;"  value='0.00' tabindex="1" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
					</td>					
				</tr>
				<tr class='content_rows_light'>
					<td colspan="2">
						Freight
					</td>
					<td align="right">
						<span id="grn_purfreight" style="font-weight:bold"></span>
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<input type="text" id="grn_freight" style="width:95%;text-align:right;"  value='0.00' tabindex="1" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
					</td>					
				</tr>			
				<tr class='content_rows_dark'>
					<td colspan="2">
						Insurance
					</td>
					<td align="right">						
						<span id="grn_purins" style="font-weight:bold"></span>
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<input type="text" id="grn_ins" style="width:95%;text-align:right;"  value='0.00' tabindex="1" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
					</td>					
				</tr>			
			   <tr class='content_rows_light' id='cgst_row'>
					<td colspan="2" >
						CGST
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<span id="grn_cgstrate" style="font-weight:bold">0.00</span>						
					</td>
					<td align="right">
						<input type="text" id="grn_cgstval" style="width:95%;text-align:right;" value='0.00' tabindex="1" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
					</td>					
				</tr>
				<tr class='content_rows_light' id='sgst_row'>
					<td colspan="2" >
						SGST
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<span id="grn_sgstrate"  style="font-weight:bold">0.00</span>
					</td>
					<td align="right">
						<input type="text" id="grn_sgstval" style="width:95%;text-align:right;" value='0.00' tabindex="1" class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
					</td>					
				</tr>				
				<tr class='content_rows_dark' id='igst_row'>
					<td colspan="2" >
						IGST
					</td>
					<td>
						&nbsp;
					</td>
					<td align="right">
						<span id="grn_igstrate" style="font-weight:bold">0.00</span>
					</td>
					<td align="right">
						<input type="text" id="grn_igstval" style="width:95%;text-align:right;" value='0.00'  tabindex="1"   class="invisible_text" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/>
					</td>			
				</tr>
				<tr class='content_rows_light'>
					<td colspan="2" >
						Grand Total
					</td>
					<td align="right" style="font-size:12px;font-weight:bold">
						<span id="grn_purgrandtotal" style="width:95%;text-align:right;"></span>
					</td>
					<td>
						&nbsp;
					</td>					
					<td align="right" style="font-size:12px;font-weight:bold">
						<span id="grn_grandtotal" style="width:95%;text-align:right;">0.00</span>
					</td>					
				</tr>
				<tr class='content_rows_dark'>
					<td colspan="2">Pending Qty: <span id="grn_penqty" style="font-weight:bold"></span> <span class="uom"></span></td>
					<td>
						Test Cert. Available?: 
						<input type="radio" name='entry_opt' tabindex="1" class="option" id="tst_yes" value="1" checked="checked"> <label for="tst_yes">Yes</label> 
						<input type="radio" name='entry_opt' tabindex="1" class="option" id="tst_no" value="0"> <label for="tst_no">No</label>
					</td>
					<td>
						<div class="invoice_heading tst_yes"> Test Cert. Details: <input type="text" id="grn_tstcertdetails" style="width:85%" tabindex="1" /> </div>						
						<div class="invoice_heading tst_no" style="display:none;"> &nbsp; </div>
					</td>
					<td>
						Date of Expiry: <input type="date" style="width:95%" name="grn_doedate" tabindex="1" id="grn_doedate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y")+1)); ?>"  />
					</td>					
				</tr>
            </table>
			<b>* - Includes Freight and Insurance</b>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('grn_popup');" />
            </div>			
        </form>
    </div>
</div>
<div style="display:none"> 
   	<div id="confirm_dialog"></div>
</div>
