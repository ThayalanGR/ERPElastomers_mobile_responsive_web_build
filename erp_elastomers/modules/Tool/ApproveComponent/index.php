<?php

	$sql			=	"select prodType, typeAbbr from tbl_product_group";
	$resTx			=	@getMySQLData($sql);
	$prodGroupData	=	$resTx['data'];	
	$prodGroupList	=	"<option></option>";
	foreach($prodGroupData as $prodGroup){
		$value			=	$prodGroup['prodType'];
		$key			=	$prodGroup['typeAbbr'];
		$prodGroupList	.=	"<option value='$value'>$value - $key</option>";
	}	
	
	$hsnList		=	"<option value=''></option>
						<option value='4016'>4016</option>";
?>

<div id="window_list_wrapper" >
    <div id="window_list_head">
        <strong>Quoted Component List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>
	</div>
	<div id="content_body">
	</div>	
</div>
<div style="display:none">
    <div id="appr_comp_popup" title="Approve Component" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="5" width="100%" class="new_form_table">
				<tr>
					<th colspan="2">
						Purchase Order Details
					</th>
				</tr>			
				<tr>
					<td width="50%">
						PO Reference 
					</td>
					<td>
						<input type="text" id="po_ref" name="po_ref" style="width:80%;" tabindex="1" />						
					</td>					
				</tr>				
				<tr>
					<td>
						PO Date
					</td>
					<td>
						<input type="date" id="po_date" name="po_date" style="width:70%;" tabindex="2" value="<?php echo date("Y-m-d"); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						PO Rate 
					</td>
					<td>
						<input type="text" id="po_rate" name="po_rate" style="width:50%;text-align:right;" class="invisible_text" tabindex="3" value="0.00" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/>
					</td>					
				</tr>
				<tr>
					<td>
						PO Quantity (if Applicable) 
					</td>
					<td>
						<input type="text" id="po_qty" name="po_qty" style="width:50%;text-align:right;" class="invisible_text" tabindex="4" value="0" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/>
					</td>					
				</tr>	
				<tr>
					<td>
						Insert Value (if Applicable)
					</td>
					<td>
						<input type="text" id="po_insval" name="po_insval" style="width:50%;text-align:right;" class="invisible_text" tabindex="5" value="0" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/>
					</td>					
				</tr>
				<tr>
					<th colspan="2">
						Component Details
					</th>	
				</tr>
				<tr>
					<td>
						Product Group
					</td>
					<td>
						<select id="cmpd_prod_group" name="cmpd_prod_group" tabindex="6" >
							<?php print $prodGroupList; ?>
						</select>							
					</td>
				</tr>
				<tr>
					<td>
						HSN Code
					</td>
					<td>
						<select id="cmpd_hsn" name="cmpd_hsn" tabindex="6" >
							<?php print $hsnList; ?>
						</select>
					</td>				
				</tr>
				<tr>
					<td>
						Application 
					</td>
					<td >
						<input type="text" id="cmpd_app" name="cmpd_app" style="width:80%;" class="invisible_text" tabindex="6" onfocus="FieldHiddenValue(this, 'in', ' ')" onblur="FieldHiddenValue(this, 'out', ' ')"/>						
					</td>
				</tr>
				<tr>
					<td>
						Sub Assembly 
					</td>
					<td>
						<input type="text" id="cmpd_subass" name="cmpd_subass" style="width:80%;" tabindex="7" />						
					</td>					
				</tr>				
				<tr>
					<td>
						Offs/Assembly
					</td>
					<td>
						<input type="text" id="cmpd_offs" name="cmpd_offs" style="width:50%;text-align:right;" class="invisible_text" tabindex="8" value="1" onkeydown="numbersOnly(event);" onfocus="FieldHiddenValue(this, 'in', '1')" onblur="FieldHiddenValue(this, 'out', '1')"/>
					</td>
				</tr>
			</table>
			<div id="insert_used"></div>
        </form>
    </div>	
	<div id="confirm_dialog"></div>
</div>

<div style="display:none">
	<div id="insert_list" title="Inserts List" style="padding-top:10px;" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="insertlist_error"></div>
        <div class="supplier_list_head">
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <th align="left" width="20px">&nbsp;</th>
                    <th align="left">Available Insert's</th>
                </tr>
            </table>
            <div class="supplier_list" id="InsertList" style="height:130px">
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th width="30px">&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </table>
            </div>
        </div>
        <input type="hidden" id="mat_id" value="" />
        <input type="hidden" id="mat_type" value="" />
    </div>
</div>