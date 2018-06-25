<div id="window_list_wrapper" class="filter-table" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Deflashing Receipt Pending</strong>
    </div>
	<div>
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="30%">
					For Items Moulded From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" style="width:75%" value="<?php echo date('Y-m-d',strtotime("first day of last month")); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="10%">
					To:
				</th>
				<th align="left" >
					<input type="date" tabindex="2" id="to_date" style="width:25%" value="<?php echo date("Y-m-d",strtotime("last day of last month")); ?>" onchange="updatePageBehaviour();" />
				</th>
			</tr>			
		</table>
	</div>
    <div id="window_list_head">
        <strong>Result List</strong>
    </div>
	<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body">
		</div>
	</div>

	<table border="0" class="print_table" cellpadding="6" cellspacing="0" width="100%">
		<tr>
		  <th width="50%" align="center"><span cusgroup="ALL" operator="" class="view_button link">Total</span></th>
		  <th width="25%" align="right" id="val_total1">0</th>
		  <th align="right" id="val_total2">0</th>
		</tr>				
	</table>	
	<div >
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
				<input id="submitPrint" type="button" value="Print" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
			</td>
        </tr>		
    </table>
    </div>	
	<div id="show_plan_form1" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body1">
		</div>
    </div>	
	<div id="show_plan_form2" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body2">
		</div>
    </div>
	<div id="show_plan_form3" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body3">
		</div>
    </div>		
</div>



<div style="display:none">    
	<div id="print_item_form" title="Deflashing Pend. Summary" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(24);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="10%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="65%" align="center" height="45px">
					<div style="font-size:20px;"><b><?php print $formArray[1]; ?></b></div>
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
		<table border="0" class="print_table" cellpadding="6" cellspacing="0" width="100%">
			<tr>
			  <th width="50%" align="center">Total</th>
			  <th width="25%" align="right" id="val_total11">0</th>
			  <th align="right" id="val_total12">0</th>
			</tr>				
		</table>		
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