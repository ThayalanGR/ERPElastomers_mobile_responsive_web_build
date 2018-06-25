<style>input{font-size:11px;}</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:10px">
    <div id="window_list_head">
        <strong>Compound Stock</strong>
		<span id="button_add"><button id="button_submit_psvs" type="submit" >Print Compound Stock Statement</button></span>		
    </div>
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin:5px 0 0 10px;">
        <tr>
            <th align="left" width="15%">From</th>
            <td style="padding-top:2px;padding-bottom:2px;" width="35%"><input type="text" rel="datepicker" id="from_date" style="width:50%" /></td>
            <th align="left" width="15%">Opening Stock</th>
            <td id="open_stock" width="15%" align="right">0.000</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th align="left">To</th>
            <td style="padding-top:2px;padding-bottom:2px;"><input type="text" rel="datepicker" id="to_date" style="width:50%" /></td>
            <th align="left">Receipts</th>
            <td id="receipts" align="right">0.000</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th align="left">Item Code</th>
            <td style="padding-top:2px;padding-bottom:2px;"><input type="text" id="item_code" style="width:70%" /></td>
            <th align="left">Issue</th>
            <td id="issue" align="right">0.000</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th align="left">Unit Of Measurement</th>
            <td style="padding-top:7px;padding-bottom:7px;" id="item_unit">Kg</td>
            <th align="left">Closing Balance</th>
            <td id="close_stock" align="right">0.000</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div id="window_list_head">
        <strong>Compound Stock List</strong>
    </div>
    <div id="window_list">
        <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
	</div>
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td colspan="7">&nbsp;</td>
            <td align="right">
				<button id="button_submit_val" type="submit" style="visibility:hidden" >Print Stock Transaction Value</button> &nbsp;
				<button id="button_submit" type="submit" style="visibility:hidden" >Print Stock Ledger</button>
			</td>
        </tr>
    </table>
	</form>		
</div>

<div style="display:none">    
	<div id="print_item_form" title="Compound Stock Ledger" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(45);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="15%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="70%" align="center" height="45px">
					<div style="font-size:20px;"><b><?php print $formArray[1]; ?></b></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;"  width="70px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:12px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
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
