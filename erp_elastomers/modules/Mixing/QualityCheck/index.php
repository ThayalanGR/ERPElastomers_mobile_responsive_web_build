<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Compound Approval</strong>
			<div style="float:right;" >
				<input  type="button" value="Rheo DB Update" onClick="runRheoDBUpdate();" />
			</div>			
    </div>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
    <div id="window_list">
        <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
  	</div>
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td width='75%' align="right">
					<input id="print_list" type="submit" value="Print Test Plan" />
			</td>
            <td align="right">
					<button id="button_submit" type="submit" >Update</button>
					<button id="button_cancel" type="submit" >Clear</button>
			</td>
        </tr>
    </table>
	</form>	
    <div id="confirm_dialog" >
    </div>		
</div>

<div style="display:none">
    <div id="qc_popup" title="Quality Check" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="qc_error"></div>
        <form action="" onsubmit="return false;">
            <div class="supplier_list_head">
                <table border="0" cellspacing="0" cellpadding="6">
                    <tr>
                        <th width="15%" align="left">Compound</th>
                        <th width="25%" align="left">Parameter</th>
                        <th width="12%" align="right">Spec</th>
                        <th width="15%" align="right">Lower Limit</th>
                        <th width="15%" align="right">Upper Limit</th>
                        <th width="15%" align="right">Observation</th>
                        <th class="scroll">&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="8" style="padding:0px;">
                            <div class="supplier_list" id="quality_chk_comp" style="height:120px;">
                                <table border="0" cellspacing="0" cellpadding="6">
                                    <tr>
                                        <th width="15%" align="left">&nbsp;</th>
                                        <th width="25%" align="left">&nbsp;</th>
                                        <th width="12%" align="right">&nbsp;</th>
                                        <th width="15%" align="right">&nbsp;</th>
                                        <th width="15%" align="right">&nbsp;</th>
                                        <th width="15%" align="right">&nbsp;</th>
                                        <th align="right">&nbsp;</th>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="novis_controls">
                <input type="submit" onclick="getSubmitButton('qc_popup');" />
            </div>
        </form>
    </div>
</div>

<div style="display:none">    
	<div id="print_item_form" title="Compound Approval" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(30);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="15%" align="center"  height="50px">
					<img id="imgpath" width="70px" />
				</td>
				<td width="70%" align="center" height="50px">
					<div style="font-size:20px;"><b><?php print $formArray[1]; ?></b></div>
				</td>
				<td valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:16px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
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
