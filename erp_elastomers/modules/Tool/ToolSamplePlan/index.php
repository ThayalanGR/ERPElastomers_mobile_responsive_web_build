<?php
	$sql			=	" select fullName from tbl_users where status>0 and userType = 'Sub-Contractor' ";
	$jobworkdat		=	@getMySQLData($sql);
	if($jobworkdat['count'] > 0 )
	{
		$jobworker		=	$jobworkdat['data'];
		$jobworklist	=	"";
		foreach($jobworker as $key=>$value){
			$jobworklist	.=	"<option value='".$value['fullName']."'>".$value['fullName']."</option>";
		}	
	}
	$sql			=	" select cpdId, cpdName from tbl_compound order by cpdName ";
	$polydat		=	@getMySQLData($sql);
	$cpdlist		=	"<option></option>";	
	if($polydat['count'] > 0 )
	{
		$cpd		=	$polydat['data'];
		foreach($cpd as $key=>$value){
			$cpdlist	.=	"<option value='".$value['cpdId']."'>".$value['cpdName']."</option>";
		}	
	}	
?>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Tool Sample Plan</strong>
    </div>
     <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="sample_plan" title="Tool Sample Plan" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Part Number</th>
                    <td id="partnum"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%;height:20px;">Description</th>
                    <td id="partdesc"></td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Location</th>
                    <td>
						<select name="operator" id="operator">
							<option value="In-House" selected>In-House</option>
							<?php echo $jobworklist;?>	
						</select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Plan Date</th>
                    <td>
						<input type="date" name="plandate" id="plandate" style="width:50%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>" tabindex="3" />
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Compound</th>
                    <td align='left' >
						<select id="complist" name="complist" tabindex="4" > 
							<?php echo $cpdlist; ?>
						</select>
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Adv. Blank Weight(gm)</th>
 					<td >
						<input type="text" id="blankwgt" name="blankwgt" class="invisible_text" style="width:20%;text-align:right;" tabindex="5"  value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/> 
					</td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Strip Dimensions (lXbXth in mm)</th>
                    <td nowrap>
                        <input type="text" id="length" style="width:10%;text-align:right" tabindex="6" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />X<input type="text" id="breath" style="width:10%;text-align:right" tabindex="6" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />X<input type="text" id="thickness" style="width:10%;text-align:right" tabindex="6" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>				
                <tr>
                    <th style="text-align:left;width:40%">Strip Weight (gm)</th>
                    <td>
                        <input type="text" id="stripwgt" name="stripwgt" class="invisible_text" style="width:20%;text-align:right;" tabindex="7"  value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/> 
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Strips / Lift</th>
                    <td>
						<input id="stripslift" type="text" name="stripslift" class="invisible_text" style="width:20%;text-align:right;" tabindex="8"  value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">Adv. Temperature (&deg;C)</th>
                    <td>
						<input type="text" id="temperature" name="temperature" class="invisible_text" style="width:20%;text-align:right;" tabindex="9"  value="180" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
                    </td>
                </tr>				
				<tr>
					<th style="text-align:left;width:40%">Adv. Curing Time (Sec)</th>
					<td>
						<input type="text" id="curetime" name="curetime" class="invisible_text" style="width:20%;text-align:right;" tabindex="10"  value="120" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
					</td>
				</tr>				
                <tr>
                    <th style="text-align:left;width:40%">Pressure (Kg/cm<sup>2</sup>)</th>
                    <td>
                        <input type="text" id="pressure" name="pressure" style="width:20%;text-align:right;" value="150" class="invisible_text" tabindex="11" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />&nbsp;
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;width:40%">No of Lifts</th>
                    <td>
                        <input type="text" id="lifts" name="lifts" style="width:20%;text-align:right;" value="10" class="invisible_text" tabindex="12" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />&nbsp;
                    </td>
                </tr>				
            </table>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('sample_plan');" />
            </div>
        </form>
    </div>
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
