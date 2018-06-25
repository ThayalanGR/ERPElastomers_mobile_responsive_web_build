<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Material Requirement & Planning</strong>
    </div>
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="left" width="15%">Select Category</th>
            <td style="padding-top:2px;padding-bottom:2px;" width="50%">
                <input type="radio" name="entry_opt" class="option" id="cpd" value="raw material" checked="checked"> <label for="cpd">Raw Material for</label>  
				<select id="rmClause" name="rmClause" tabindex="1" style="width:20%" onchange="getMaterialData()">
					<option value="1" selected>Compound Schedule</option>
					<option value="2" >Component Schedule</option>
					<option value="3" >What If Scenario</option>
				</select>
				&emsp;&emsp;
                <input type="radio" name="entry_opt" class="option" id="cnt" value="compound"> <label for="cnt">Compound</label>
            </td>			
            <th align="left">Month</th>
            <td style="padding-top:2px;padding-bottom:2px;"><input rel="datepicker" type="text" id="to_date" style="width:40%" value="<?php echo date("F Y"); ?>" /></td>
        </tr>
    </table>
</div>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>MRP List</strong>
    </div>
	<div id="content_head" style="padding-bottom:0px;">
		<div id="raw_material"> 
            <table border="0" cellpadding="5" cellspacing="0" width="100%;">
                <tr class="ram_rows_head">
                    <th width="5%" align="left">No.</th>
                    <th width="25%">Raw Material Name</th>
                    <th width="10%">Required Qty.<sup>Kg</sup></th>
                    <th width="10%">In-Stock Qty.<sup>Kg</sup></th>
                    <th width="10%">Shortage Qty.<sup>Kg</sup></th>
					<th width="15%">No of Bags<sup>Nos</sup></th>
                    <th> Used In</th>					
                </tr>
            </table>
        </div>
		<div id="compound" style="display:none;">
            <table border="0" cellpadding="5" cellspacing="0" width="100%;">
                <tr class="ram_rows_head">
                    <th width="5%" align="left">No.</th>
                    <th width="12.5%" align="left">Part Number</th>
                    <th width="12.5%" align="left">Part Description</th>
                    <th width="15%" align="right">Quantity</th>
					<th width="15%" align="right">Rate</th>
					<th width="15%" align="right">Value</th>
                    <th width="10%" align="right" title="Blank Weight">Blank Wt.<sup>gm</sup></th>
                    <th title="Compound Qty" align="right">Cmpd qty.<sup>Kg</sup></th>
                </tr>
            </table>
        </div>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
	<div id="content_foot">
		<div id="raw_material1">		
		<table border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr>
				<th width="30%" align="right" >Grand Total</th>
				<th width="10%" align="right"  id="req_total" >0</th>				
				<th width="10%"align="right"  id="instock_total" >0</th>
				<th width="10%" align="right"  id="shortage_total" >0</th>
				<th align="center">&nbsp;</th>
			</tr>
		</table>
		</div>
		<div id="compound1" style="display:none;">
		<table border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr>
				<th width="60%" align="right" >Grand Total</th>
				<th width="15%" align="right"  id="val_total" >0</th>
				<th align="right"  id="cpd_total" >0</th>				
			</tr>
		</table>	
		</div>
	</div>
	<form action="" onsubmit="return false;">
		<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
			<tr>				
				<td align="right">
					<button id="button_submit" type="submit">Print</button>
				</td>
			</tr>
		</table>
	</form>				
</div>

<div style="display:none">    
	<div id="print_item_form" title="Material Requirement Statement" style="visibility:hidden">
		<style>.content_table{
			width:100%;
			border-left:2px double black;
			border-top:2px double black;
			border-right:2px double black;
			border-bottom:2px double black;
		}
		.content_table th{
			border-right:2px solid black;
			border-bottom:2px solid black;
		}
		.content_table td{
			border-right:1px solid black;
			border-bottom:1px solid black;
		}
		</style>
		<p align="right">Form No:
			<?php 
				// Get Form Details.
				$formArray		=	@getFormDetails(9);
				print $formArray[0]; 
			?>
		</p>		
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="content_table">
			<tr>
				<td rowspan="2" width="100px" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="100px" />
				</td>
				<td align="center" height="80px">
					<div style="font-size:40px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;"  width="100px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:20px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:16px; border-bottom:0px;" ><b><span id="hdr_title"> </span> <?php print $formArray[1]; ?> </b>
				</td>
			<tr>
		</table>
		<div id="print_body"></div>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="content_table">
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
<div style="display:none"> 
    <div id="receive_dialog">
		<div style="text-align:right;padding-bottom:6px;">
			<span id="new_CPDButton">Add</span>
		</div>
		<div class="supplier_list_head" id="new_ItemList" style="margin-right:2px; margin-left:2px;">
			<table border="0" cellpadding="5" cellspacing="0" width="100%" >
				<tr>
					<th width="10%" align="Left">S.No</th>
					<th width="45%" align="center">Compound Name</th>
					<th width="15%" align="center">Polymer</th>
					<th width="18%" align="center" >Required Qty</th>
					<th align="center" >#</th>
				</tr>
			</table>
			<span id="error_msg" style="padding:7px;" />
		</div>
	</div>
</div>