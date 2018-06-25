<?php
	global $grn_customers,$grn_role;
	$recvlist	=	"";
	$recvopts	=	"";
	$noofrecvs	=	0;
	for($ct=0;$ct<count($grn_customers);$ct++){
		if($grn_role[$grn_customers[$ct]] == 'self' || $grn_role[$grn_customers[$ct]] == 'vendor')
		{
			$noofrecvs++;
			$recvopts	.=	"<option>".$grn_customers[$ct]."</option>";
		}
	}
	if($noofrecvs > 1)
		$recvlist	=	"<option selected></option>";	
	$recvlist	.=	$recvopts;
?>

<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Raw Material List</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
        	<div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="dialog_box" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Raw Material</th>
                    <td id="raise_rmid"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Grade</th>
                    <td id="raise_rmgrade"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Supplier</th>
                    <td id="raise_supplier">
                        <select id="supplierlist" style="width:150px"></select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Previous GRN Ref</th>
					<td id="raise_lastgrn"></td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Quote Reference</th>
                    <td>
                        <input type="text" id="raise_quoteref" style="width:80%" tabindex="2" class="invisible_text" value="Verbal" onfocus="FieldHiddenValue(this, 'in', 'Verbal')" onblur="FieldHiddenValue(this, 'out', 'Verbal')" />
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Quote Date</th>
                    <td>
						<input type="date" name="raise_quotedate" id="raise_quotedate" style="width:50%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>"  />
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Delivery At</th>
                    <td>
                        <select id="deliverat" tabindex="1" >							
							<?php echo $recvlist;?>
						</select>
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Standard Packing Quantity</th>
                    <td align='left'>
                        <span id="stdpack_quantity"></span> &nbsp;
                        <span id="uom1"></span>
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Quantity</th>
                    <td>
                        <input type="text" id="raise_quantity" style="width:50%;text-align:right;" value="0.00" class="invisible_text" tabindex="5" onfocus="FieldHiddenValue(this, 'in', minStock)" onblur="FieldHiddenValue(this, 'out', minStock)" />&nbsp;
                        <span id="uom"></span>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Rate</th>
                    <td>
                        <input type="text" id="raise_rate" style="width:50%;text-align:right;" value="0.00" class="invisible_text" tabindex="6" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />&nbsp;
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Value</th>
                    <td>
						<div id="raise_value" class="input_timer" style="width:50%;text-align:right;">0.00</div>
                        
                    </td>
                </tr> 					
                <tr>
                    <th style="text-align:left;width:40%">Insurance</th>
                    <td>
                        <input type="text" id="raise_insurance" style="width:50%;text-align:right;" value="0.00" class="invisible_text" tabindex="14" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />&nbsp;
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Freight Value</th>
                    <td>
                        <input type="text" id="raise_freight" style="width:50%;text-align:right;" value="0.00" class="invisible_text" tabindex="15" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />&nbsp;
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Order Value</th>
                    <td>
                        <div id="raise_gross" class="input_timer" style="width:75%;text-align:right;">0.00</div>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%;vertical-align:top;padding-top:8px;" valign="top">Remarks</th>
                    <td>
                        <textarea id="raise_remarks" style="width:90%;height:80px;max-height:80px;" tabindex="16"></textarea>
                    </td>
                </tr>
            </table>
            <div id="hiddenMsg">
            </div>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('dialog_box');" />
            </div>
        </form>
    </div>	
</div>

<div style="display:none"> 
	<div id="confirm_dialog"></div>
	<div id="print_item_form" title="Raw material Stocklist" style="visibility:hidden">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="15%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="70%" align="center" height="45px">
					<div style="font-size:20px;"><b><?php  echo $_SESSION['app']['comp_name'];?></b></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;"  width="70px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:16px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:16px; border-bottom:0px;" ><b><span id="hdr_title"> </span> </b>
				</td>
			<tr>
		</table>
		<div id="print_body"></div>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_bottom">
			<tr>
				<td width="50%" style="border-bottom:0px;" valign="top">
					<b>Remarks:</b>
					<br /><br />
				</td>
				<td width="25%" style="border-bottom:0px;" valign="top">
					<b>Prepared:</b>
				</td>
				<td style="border-bottom:0px;" valign="top" align="left">
					<b>Approved:</b>
				</td>
			</tr>
		</table>		
    </div>	
</div>