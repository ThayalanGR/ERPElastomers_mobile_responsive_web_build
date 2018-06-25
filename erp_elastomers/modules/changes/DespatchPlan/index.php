<div id="window_list_wrapper" class="filter-table" style="padding-top:65px;">
	 <div class="row ">
	 	<div class="col-9 text-primary">
		 <strong>Despatch Plan</strong>
		</div>
		<div class="col-2" style="text-align:right;">
			<button id="button_add" class="btn-primary btn-sm" style="font-size:10px; padding:0px;">Add DI</button>	
		</div>		
    </div>
   <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body" style=""></div>
    </div>
   <div id="content_foot">
   <form action="" onsubmit="return false;">
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_error_msg"></div>
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:5px;">
        <tr>
            <td align="right">
				<button id="btn_availability" class="btn-sm btn-primary" type="submit" style="font-size:10px; padding:0px;">Print Availability Report</button>
				<button id="btn_submit" class="btn-sm btn-primary" type="submit" style="font-size:10px; padding:0px;">Print Despatch Plan</button>
				<button id="btn_multi_invoice" class="btn-sm btn-primary" type="submit" style="font-size:10px; padding:0px;">Create Multi-Item Invoice</button>
			</td>			
        </tr>
    </table>
	</form>	
    </div> 	
 	
</div>

<div class="window" id="new_item_form" style="visibility:hidden;">
	<div class="row justify-content-center text-primary">
		<b>ADD DI</b>
	</div>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
	<form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">		
		<div class="row">
			<div class="col" style="font-size: 12px;">
				Customer
			</div>
			<div class="col">
				<input type="text" class="form-control-sm" tabindex="1" style="font-size:10px; height: 25px; width:150px;" id="di_cust" onchange="removeAllList();" />
			</div>
		</div>	
		<div class="row">
			<div class="col" style="font-size: 12px;">
				Date:
			</div>
			<div class="col">
				<input class="form-control-sm" type="date"  tabindex="1" id="di_date" style="font-size:10px; height: 25px; width:150px;" value="<?php echo date("Y-m-d"); ?>" />
			</div>
		</div>
		<div style="text-align:center;padding-bottom:6px;"><input id="file" name="file" type="file" accept=".csv" style="font-size:10px; height: 25px; width:150px;" tabindex="1" />&nbsp;&nbsp;<span id="di_submit">Upload DI</span> &nbsp; <span id="new_RMButton">Add Row</span></div>
		<div class="supplier_list_head" style="margin-right:2px; margin-left:2px;">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
					<tr>
						<th width="39.8%" align="center">Part Number</th>
						<th width="29.8%" align="center">DI No.</th>
						<th width="19.8%" align="center">Qty</th>
						<th width="4.8%" align="center">#</th>
						<th align="center">&nbsp;</th>
					</tr>
				</table>
				<div class="supplier_list" id="new_ItemList">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<tr>
							<th width="44%" align="left">&nbsp;</th>
							<th width="30%" align="left">&nbsp;</th>
							<th width="20%" align="right">&nbsp;</th>
							<th width="5%" align="center">&nbsp;</th>
							<th align="center">&nbsp;</th>										
						</tr>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>	
<div style="display:none">
	<div id="dichange_dialog"></div>
	<div id="stock_dialog"></div>
	<div id="invoice_dialog"></div>
	<div id="confirm_dialog"></div>	
	<div id="print_item_form" title="Despatch Plan" style="visibility:hidden">
		<div id="styleRef"></div>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(37);
				print $formArray[0]; 
			?>
		</p>		
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="print_table_top">
			<tr>
				<td rowspan="2" align="center" width="70px" >
					<img id="imgpath" width="70px" />
				</td>
				<td align="center" height="45px" >
					<div style="font-size:20px;"><?php print $formArray[1]; ?></div>
				</td>
				<td rowspan="2" width="70px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:14px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:12px;" ><b><span id="hdr_title"> </span> </b>
				</td>
			<tr>
		</table>
		<div id="print_body"></div>
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="print_table">
		    <tr style="font-size:8;" id="reportTot">
                <th align="right" style="width:42%;font-weight:bold">Grand Total</th>
                <th align="right" id="di_qty_total1" style="width:7%;font-weight:bold">0</th>
				<th align="right" id="di_val_total1" style="width:7%;font-weight:bold">0</th>
                <th align="right" id="inv_qty_total1" style="width:7%;font-weight:bold">0</th>
				<th align="right" id="inv_val_total1" style="width:7%;font-weight:bold">0</th>
				<th style="width:12%;font-weight:bold">&nbsp;</th>
                <th align="right" id="pend_qty_total1" style="width:7%;font-weight:bold">0</th>
				<th align="right" id="pend_val_total1" style="width:7%;font-weight:bold">0</th>
				<th>&nbsp;</th>
            </tr>
        </table>		
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="print_table_bottom">
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
