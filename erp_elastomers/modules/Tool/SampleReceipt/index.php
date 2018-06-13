<?php
	global $default_rejections;
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
	$sql			=	" select fullName from tbl_users where status>0 and userDesignation = 'Inspector' ";
	$inspdat		=	@getMySQLData($sql);
	if($inspdat['count'] > 0 )
	{
		$inspector		=	$inspdat['data'];
		$insplist	=	"";
		foreach($inspector as $key=>$value){
			$insplist	.=	"<option value='".$value['fullName']."'>".$value['fullName']."</option>";
		}	
	}
	$sql		=	"select rej_short_name, rej_type from tbl_rejection where rej_short_name in ".$default_rejections ." order by sno ";
	$rejdat		=	@getMySQLData($sql);
	$rejcount	=	$rejdat['count'];
	if($rejcount > 0)
	{
		$rejdat['data'][$rejcount]['rej_type']		= 	"Shortage";
		$rejdat['data'][$rejcount]['rej_short_name']	=	"SHORT";
		$rejcount++;
	}
	$rejlist	=	"";
	for($rejNo = 0; $rejcount > $rejNo; $rejNo = $rejNo+2) 
	{
		$dark		=	($dark == 'content_rows_dark')?'content_rows_light':'content_rows_dark';
		$rejType1	=	($rejdat['data'][$rejNo]['rej_type'])?$rejdat['data'][$rejNo]['rej_type']:'&nbsp;';
		$rejShName1	=	($rejdat['data'][$rejNo]['rej_short_name'])?"<input type='text' id='".$rejdat['data'][$rejNo]['rej_short_name']."' class='rejection invisible_text' style='width:30%;text-align:right;' tabindex='18' value='0' onkeyup=calcApproved() onkeydown=numbersOnly(event) onfocus= ".'"FieldHiddenValue(this, '."'in'".", '0'".')"'." onblur=".'"FieldHiddenValue(this, '."'out'".', '."'0'".')" />':'&nbsp;';
		$rejType2	=	($rejdat['data'][$rejNo+1]['rej_type'])?$rejdat['data'][$rejNo+1]['rej_type']:'&nbsp;';
		$rejShName2	=	($rejdat['data'][$rejNo+1]['rej_short_name'])?"<input type='text' id='".$rejdat['data'][$rejNo+1]['rej_short_name']."' class='rejection  invisible_text' style='width:30%;text-align:right;' tabindex='18' value='0' onkeyup='calcApproved()' onkeydown='numbersOnly(event)' onfocus= ".'"FieldHiddenValue(this, '."'in'".", '0'".')"'." onblur=".'"FieldHiddenValue(this, '."'out'".', '."'0'".')" />':'&nbsp;';
		$rejlist	.=	"<tr class='$dark' >
							<th align='left'>$rejType1</th>
							<td>$rejShName1</td>
							<th align='left'>$rejType2</th>
							<td>$rejShName2</td>							
						</tr>";
	}	
	
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Tool Sample Receipt</strong>
    </div>
     <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="sample_receipt" title="Sample Receipt" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:8px;display:none" id="raise_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr class='content_rows_light'>
                    <th style="text-align:left;height:20px;">Plan Id</th>
                    <td id="planid"></td>
                    <th style="text-align:left;height:20px;">Plan Date</th>
                    <td id="plandate"></td>
                </tr>
				<tr class='content_rows_dark' >
                    <th colspan="4" style="font-size:14px" >Moulding</th>
				</tr>
                <tr class='content_rows_light' >
                    <th width="25%" align="left">Location</th>
                    <td width="25%">
						<select name="operator" id="operator" tabindex="1" style="width:75%">
							<option value="In-House" selected>In-House</option>
							<?php echo $jobworklist;?>	
						</select>
                    </td>
                    <th width="25%" align="left">Compound</th>
                    <td width="25%" align='left' >
						<select id="complist" name="complist" tabindex="2" style="width:75%"> 
							<?php echo $cpdlist; ?>
						</select>
                    </td>
                </tr>				
                <tr class='content_rows_dark' >
                    <th align="left">Blank Weight(gm)</th>
 					<td >
						<input type="text" id="blankwgt" name="blankwgt" class="invisible_text" style="width:30%;text-align:right;" tabindex="3"  value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/> 
					</td>
                    <th align="left">Strip Dimensions (lXbXth in mm)</th>
                    <td nowrap>
                        <input type="text" id="length" style="width:20%;text-align:right" tabindex="4" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />X<input type="text" id="breath" style="width:20%;text-align:right" tabindex="5" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />X<input type="text" id="thickness" style="width:20%;text-align:right" tabindex="6" class="invisible_text" value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')" />
                    </td>
                </tr>				
                <tr class='content_rows_light' >
                    <th align="left">Strip Weight (gm)</th>
                    <td>
                        <input type="text" id="stripwgt" name="stripwgt" class="invisible_text" style="width:30%;text-align:right;" tabindex="7"  value="0.00" onfocus="FieldHiddenValue(this, 'in', '0.00')" onblur="FieldHiddenValue(this, 'out', '0.00')"/> 
                    </td>
                    <th align="left">Strips / Lift</th>
                    <td>
						<input id="stripslift" type="text" name="stripslift" class="invisible_text" style="width:30%;text-align:right;" tabindex="8"  value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
                    </td>
                </tr>
                <tr class='content_rows_dark' >
                    <th align="left">Cure Temp. (&deg;C)</th>
                    <td>
						<input type="text" id="temperature" name="temperature" class="invisible_text" style="width:30%;text-align:right;" tabindex="9"  value="180" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
                    </td>
					<th align="left">Curing Time (Sec)</th>
					<td>
						<input type="text" id="curetime" name="curetime" class="invisible_text" style="width:30%;text-align:right;" tabindex="10"  value="120" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')"/> 
					</td>
				</tr>				
                <tr class='content_rows_light' >
                    <th align="left">Cure Press. (Kg/cm<sup>2</sup>)</th>
                    <td>
                        <input type="text" id="pressure" name="pressure" style="width:30%;text-align:right;" value="150" class="invisible_text" tabindex="11" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />&nbsp;
                    </td>
                    <th align="left">Act. Lifts</th>
                    <td>
                        <input type="text" id="lifts" name="lifts" style="width:30%;text-align:right;" value="10" class="invisible_text" tabindex="12" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />&nbsp;
                    </td>
                </tr>
                <tr class='content_rows_dark' >
                    <th align="left">Mould. Remarks</th>
                    <td colspan="3">
                        <textarea id="mold_remarks" tabindex="13" rows="3" cols="80"></textarea>
                    </td>
                </tr>
				<tr class='content_rows_light' >
					<th colspan="4" style="font-size:14px">Post Curing</th>
				</tr>				
				<tr class='content_rows_dark'>
					<th align="left">Post Curing?</th>
					<td>
						<input type="radio" name='post_cure_opt' tabindex="13" id="post_cure_yes" value="1" > <label for="post_cure_yes">Yes</label> 
						<input type="radio" name='post_cure_opt' tabindex="13" id="post_cure_no" value="0" checked="checked"> <label for="post_cure_no">No</label>
					</td>
                    <th align="left">Time (hours) / Temp. (&deg;C)</th>
                    <td>
                        <input type="text" id="post_cure_time" style="width:20%;text-align:right;" value="0.0" class="invisible_text" tabindex="13" onfocus="FieldHiddenValue(this, 'in', '0.0')" onblur="FieldHiddenValue(this, 'out', '0.0')" /> @ <input type="text" id="post_cure_temp" style="width:20%;text-align:right;" value="0" class="invisible_text" tabindex="13" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" />
                    </td>
                </tr>				
				<tr class='content_rows_light' >
					<th colspan="4" style="font-size:14px">Trimming</th>
				</tr>
                <tr class='content_rows_dark' >
                    <th align="left">Operator</th>
                    <td>
						<select name="trim_operator" id="trim_operator" tabindex="14" style="width:75%">
							<option value="In-House" selected>In-House</option>
							<?php echo $jobworklist;?>	
						</select>
                    </td>
                    <th align="left">Trim. Output</th>
                    <td>
                        <input type="text" id="trim_output" style="width:30%;text-align:right" tabindex="15" class="invisible_text" value="0" onfocus="FieldHiddenValue(this, 'in', '0')" onblur="FieldHiddenValue(this, 'out', '0')" onkeyup="calcApproved()" />
                    </td>
                </tr>
                <tr class='content_rows_light' >
                    <th align="left">Trim. Remarks</th>
                    <td colspan="3">
                        <textarea id="trim_remarks" tabindex="16" rows="3" cols="80"></textarea>
                    </td>
                </tr>
				<tr class='content_rows_dark' >
					<th colspan='4'style="font-size:14px">Inspection</th>
				</tr>				
                <tr class='content_rows_light' >
                    <th style="text-align:left;">Inspector</th>
                    <td colspan="3">
						<select name="inspector" id="inspector" tabindex="17" style="width:25%">
							<?php echo $insplist;?>	
						</select>
                    </td>
                </tr>
				<?php echo $rejlist;?>
                <tr class='content_rows_light' >
                    <th align="left">App. Quantity</th>
                    <td colspan='3' id='appqty'></td>
                </tr>				
                <tr class='content_rows_dark' >
                    <th align="left">Insp. Remarks</th>
                    <td colspan="3">
                        <textarea id="insp_remarks" tabindex="20" rows="3" cols="80"></textarea>
                    </td>
                </tr>					
			</table>			
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('sample_receipt');" />
            </div>
        </form>
    </div>
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
