<?php 
	global $invoiceTypes;
	$invTypeItems	=	"<option value='' selected>All</option>";
	foreach($invoiceTypes as $key=>$value){
		$invTypeItems	.=	"<option value='".$key."'>".$value."</option>";
	}	
?>

<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Select Invoice Date Range</strong>
    </div>
	<div>
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="20%">
					From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" style="width:75%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="10%">
					To:
				</th>
				<th align="left" width="15%">
					<input type="date" tabindex="2" id="to_date" style="width:75%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="10%">
					Invoice Type:
				</th>
				<th align="left" width="10%">
					<select tabindex="3" id="invoicetype" style="width:80%" onchange="updatePageBehaviour();" >
						<?php print $invTypeItems; ?>
					</select>
				</th>				
				<th align="right">
					<a class="email_coverpage_button link">email Sales Report Cover Page</a> <br/>
					<a class="email_fulldetails_button link">email Detailed Sales Report</a> <br/>
					<a class="email_cpddetails_button link">email Compound Details Report</a>					
				</th>
			</tr>			
		</table>
	</div>
    <div id="window_list_head">
        <strong>Invoice List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>
	</div>
	<div id="window_list">
		<div id="content_body"></div>
    </div>
	<div >
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
            <tr>
                <th align="right" style="width:59.4%;font-weight:bold">Grand Total</th>
                <th align="right" id="qty_total" style="width:3.9%;font-weight:bold">0</th>
                <td align="right" style="width:5.9%">&nbsp;</td>
                <th align="right" id="val_total" style="width:7.9%;font-weight:bold">0.00</th>
                <th align="right" >&nbsp;</th>
            </tr>
        </table>
		<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
			<tr>
				<td align="right">
					<input onclick="submitPrint();" type="button" value="Print Selected Invoice(s)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
				</td>
			</tr>		
		</table>
     </div>	
   <div id="view_dialog" >
    </div>		
</div>