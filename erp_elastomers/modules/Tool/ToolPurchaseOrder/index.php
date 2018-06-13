<?php
	global $tq_std_toolsize;
	$toolstdsizelist	=	"";
	for($ct=0;$ct<count($tq_std_toolsize);$ct++){
		$toolstdsizelist	.=	"<option>".$tq_std_toolsize[$ct]."</option>";
	}
	$sql			=	"SELECT tbls.supName, tbls.supId 
						FROM tbl_supplier tbls
						INNER JOIN tbl_rawmaterial_sup tblrs ON tbls.supId = tblrs.supId
						INNER JOIN tbl_rawmaterial tblr on tblr.ramId = tblrs.ramId
						WHERE tblr.ramName = 'TOOL' AND tbls.status !=0  AND tblr.status !=0";
	$toolsupdat		=	@getMySQLData($sql);
	if($toolsupdat['count'] > 0 )
	{
		$toolsupplier		=	$toolsupdat['data'];
		$toolsuplist	=	"<option></option>";
		foreach($toolsupplier as $key=>$value){
			$toolsuplist	.=	"<option value='".$value['supId']."'>".$value['supName']."</option>";
		}	
	}
?>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Tool Purchase</strong>
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
                    <th style="text-align:left;width:40%">Tool Quote Date</th>
                    <td>
						<input type="date" name="tool_quotedate" id="tool_quotedate" style="width:35%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>" tabindex="1" />
                    </td>
                </tr>			
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Part Number</th>
                    <td id="raise_partnum"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Description</th>
                    <td id="raise_partdesc"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Tool Maker</th>
                    <td id="raise_supplier">
                        <select id="raise_supplierlist" tabindex="2">
							<?php echo $toolsuplist;?>
						</select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Supplier Quote Reference</th>
                    <td>
                        <input type="text" id="raise_quoteref" style="width:45%" tabindex="3" class="invisible_text" value="email" onfocus="FieldHiddenValue(this, 'in', 'email')" onblur="FieldHiddenValue(this, 'out', 'email')" /> dated:<input type="date" name="raise_quotedate" id="raise_quotedate" style="width:35%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>" tabindex="3" />
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Tool Size (mmXmm)</th>
                    <td align='left' >
						<select id="raise_toolsize" tabindex="4">
							<?php echo $toolstdsizelist;?>
							<option>Non-Standard</option>
						</select>
						<input hidden="hidden" type="text" id="raise_tool_size" name="raise_tool_size" style="width:40%;" tabindex="5" class="invisible_text" value='250X250' onfocus="FieldHiddenValue(this, 'in', '250mmX250mm')" onblur="FieldHiddenValue(this, 'out', '250mmX250mm')"/>						
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Tool Type</th>
 					<td nowrap>
						<input type="radio" name='tool_type_opt' tabindex="5" class="tl_option" id="tool_proto" value="1" checked="checked"> <label for="tool_proto">Prototype</label> 
						<input type="radio" name='tool_type_opt' tabindex="5" class="tl_option" id="tool_multi" value="0"> <label for="tool_multi">Multi-Cavity</label>
						<span class="invoice_heading tool_multi"> <b>No of Cavities: </b><input type="text" id="raise_cavities" name="raise_cavities" class="invisible_text" style="width:10%;text-align:right;" tabindex="5" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> </span>						
					</td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Shrinkage (%)</th>
                    <td>
                        <input type="text" id="raise_shrinkage" style="width:20%;text-align:right" tabindex="6" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Moulding Process</th>
                    <td>
                        <select id="raise_moldprocess" tabindex="7">
							<option></option>
							<option>Compression</option>
							<option>Transfer</option>							
						</select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Mould Type</th>
                    <td>
                        <select id="raise_moldtype" tabindex="8">
							<option></option>
							<option>Hinged</option>
							<option>Regular</option>							
						</select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Mould Material</th>
                    <td>
                        <select id="raise_moldmatl" tabindex="9">
							<option></option>
							<option>OHNS</option>
							<option>P20</option>							
						</select>
                    </td>
                </tr>				
				<tr>
					<th style="text-align:left;width:40%">Cavity Engravement</th>
					<td>
						<input type="radio" name='cav_engrave_opt' tabindex="10" id="engrave_yes" value="1" > <label for="engrave_yes">Yes</label> 
						<input type="radio" name='cav_engrave_opt' tabindex="10" id="engrave_no" value="0" checked="checked"> <label for="engrave_no">No</label>
					</td>
				</tr>
                <tr>
                    <th style="text-align:left;width:40%">Value</th>
                    <td>
                        <input type="text" id="raise_poval" style="width:50%;text-align:right;" value="0.00" class="invisible_text" tabindex="11" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />&nbsp;
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Exp. Completion</th>
                    <td>
						<input type="date" name="raise_compdate" id="raise_compdate" style="width:35%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))); ?>" tabindex="12" />
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%;vertical-align:top;padding-top:8px;" valign="top" id="lbl_remarks">Remarks</th>
                    <td>
                        <textarea id="raise_remarks" style="width:90%;height:80px;max-height:80px;" tabindex="13"></textarea>
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
</div>