<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Compound Approval</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="30%">
					From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" style="width:75%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="10%">
					To:
				</th>
				<th align="left" >
					<input type="date" tabindex="2" id="to_date" style="width:25%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>
			</tr>			
		</table>
	</form>
	<br />
	<div id="window_list_head">
		<strong>Compound Approval List</strong>
		<div style="float:right;" >
			<strong>Show Quality Report for : </strong>
			<select id="batidlist" >
			</select>
			<input  type="button" value="Go" onClick="showReport();" />
		</div>					
	</div>
	<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body"></div>
	</div>
	<table border="0" cellpadding="6" cellspacing="0" width="100%">
		<tr>
			<th align="right"  width="15%"><span cpdid="ALL" cpdname="ALL" class="view_button link">Grand Total</span></th>
			<th align="right" width="5%" id="tot_qty">0</th>
			<th align="right" width="5%" id="hard_qty">0</th>
			<th align="right" width="20%" id="spgr_qty">0</th>
			<th align="right" width="20%" id="ts2_qty">0</th>
			<th align="right" width="20%" id="t90_qty">0</th>
			<th align="right">&nbsp;</th>
		</tr>
	</table>
 	<form action="" onsubmit="return false;">
		<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
			<tr>
				<td align="right">
					<button id="button_submit_pss" type="submit" >Print</button>
				</td>
			</tr>
		</table>
	</form>			
	<div id="show_plan_form" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body">
		</div>
    </div>		
</div>
<div style="display:none">    
	<div id="print_item_form" title="Compound Test" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(31);
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
