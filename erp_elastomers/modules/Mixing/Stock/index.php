<?php
	global $grn_customers;
	$recvlist	=	"";
	for($ct=0;$ct<count($grn_customers);$ct++){
		$recvlist	.=	"<option>".$grn_customers[$ct]."</option>";
	}
?>
<style>input{font-size:11px;}</style>
<div id="window_list_wrapper" style="padding-bottom:10px">
    <div id="window_list_head">
        <strong>Compound Stock</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin:5px 0 0 10px;">
        <tr>
            <th align="left" width="15%">Customer</th>
            <td style="padding-top:2px;padding-bottom:2px;" width="35%">				
				<select id="recvFrom" tabindex="1" style="width:20%" onchange="getStockData()">
					<option selected></option>
					<?php echo $recvlist;?>
				</select>
			</td>
            <th align="left" width="15%">Mixing Type</th>
            <td colspan="2">
				<select id="mixType" tabindex="2" style="width:40%" onchange="getStockData()">
					<option selected>Master</option>
					<option>Final</option>
				</select>
			</td>
        </tr>		
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
            <td style="padding-top:7px;padding-bottom:7px;" id="item_unit">&ndash;</td>
            <th align="left">Closing Balance</th>
            <td id="close_stock" align="right">0.000</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
<div id="window_list_wrapper">
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
					<button id="button_submit_psvs" type="submit" style="visibility:hidden" >Print Stock Verification Statement</button>
					&nbsp;&nbsp;<button id="button_submit_pssv" type="submit" style="visibility:hidden" >Print Stock Value Statement</button>
					&nbsp;&nbsp;<button id="button_submit" type="submit" style="visibility:hidden" >Print Stock Statement</button>

			</td>
        </tr>
    </table>
	</form>		
</div>

<div style="display:none">    
	<div id="print_item_form" title="Stock Statement" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(43);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td rowspan="2" width="15%" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td align="center" width= "70%" height="45px">
					<div style="font-size:20px;font-weight:bold;"><?php  echo $_SESSION['app']['comp_name'];?></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:16px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:16px; border-bottom:0px;" ><b><span id="hdr_type"> </span> <?php print $formArray[1]; ?> <span id="hdr_for"> </span> <span id="hdr_title"> </span> </b>
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
