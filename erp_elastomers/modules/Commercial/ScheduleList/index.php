<?php
	global $schVsDespGroup;
	
	$groupByItems	=	"";
	foreach($schVsDespGroup as $key=>$value){
		$groupByItems	.=	"<option value='".$key."'>".$value."</option>";
	}	
?>

<style>
#window_list{padding-left:7px;padding-top:5px;}
</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:5px; ">
    <div id="window_list_head">
        <strong>Schedule List</strong>
    </div>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="left" width="10%">Schedule List</th>
            <td style="padding-top:2px;padding-bottom:2px;" width="50%">
                <input type="radio" name="entry_opt" class="option" id="cpd" value="compound" checked="checked"> <label for='cpd'>Compound</label> &emsp;&emsp;
                <input type="radio" name="entry_opt" class="option" id="cnt" value="component"> <label for='cnt'>Component</label>
				&nbsp;&nbsp; Group Result By: 
				<select name='groupby' id='groupby' onchange="getScheduleData();">
					<?php print $groupByItems; ?>
				</select>
				For :
				<select name='comptype' id='comptype' onchange="getScheduleData();">
					<option value='0' selected>All</option>
					<option value='1'>Non-Colour</option>
					<option value='2'>Colour</option>
				</select>	
				Compounds
            </td>
            <th align="left" width="10%">Month</th>
            <td style="padding-top:2px;padding-bottom:2px;"><input rel="datepicker" type="text" id="to_date" style="width:50%" value="<?php echo date("F Y"); ?>" /></td>
        </tr>
    </table>
    <div id="window_list_head">
        <strong>Schedule List Entries</strong>
		<div style="float:right;" >
			<strong>Show Non-Moving Items for last 
				<select tabindex="4" id="no_months" > 
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option selected="true">6</option>
					<option>9</option>
					<option>12</option>				
				</select> 
				Months 	
			</strong>
			<input  type="button" value="Go" onclick="openReport();" />
		</div>		

    </div>
   <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
    <div id="content_foot">
        <table border="0" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <th align="left" width="3%">&nbsp;</th>
                <th align="right" id="grandAvgMonth" width="8%" >0</th>
                <th align="right" id="grandPreSchQty" width="8%" >0</th>
                <th align="right" id="grandPreSchVal" width="9%">0</th>
                <th align="right" id="grandPercent" width="3%" >0</th>
                <th align="center" width="18%" colspan="2">Grand Total</th>
                <th align="right" width="7%" id="grandSchQty" >0</th>
                <th align="right" width="9%" id="grandSchVal" >0</th>
                <th align="right" width="7%" id="grandDisQty" >0</th>
                <th align="right" width="9%" id="grandDisVal" >0</th>
                <th align="right" width="7%" id="grandPenQty">0</th>
                <th align="right" width="9%" id="grandPenVal" >0</th>
                <th align="right" id="grandComp">0</th>
            </tr>
        </table>
    </div>
	
 	<form action="" onsubmit="return false;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0" style="margin-right:10px;margin-top:5px;">
        <tr>
            <td colspan="2" align="right">
				<button id="button_submit_ps" type="submit" >Print Summary</button>&nbsp;&nbsp;<button id="button_submit" type="submit" >Print Details</button>
			</td>			
        </tr>
        <tr>
			<td width="10%"><b>Exception Reports:</b></td>
            <td align="right">				
				For Despatch Qty: 
				<select id="oper">
					<option selected="true" value="1"> &lt;=</option>
					<option value="2">&gt;=</option>
				</select>				
				<select id="percent">
					<option value="0.01">1%</option>
					<option value="0.05">5%</option>
					<option value="0.10">10%</option>
					<option value="0.20">20%</option>
					<option selected="true" value="0.30">30%</option>
					<option value="0.40">40%</option>
					<option value="0.50">50%</option>	
					<option value="0.60">60%</option>
					<option value="0.70">70%</option>
					<option value="0.75">75%</option>
					<option value="0.80">80%</option>
					<option value="0.90">90%</option>	
					<option value="1.00">100%</option>
				</select>
				Of Schedule Qty
				<select id="joincond">
					<option selected="true">and</option>
					<option>or</option>
				</select>
				Pending Value &gt;=
				<input id="penval" type="text" style="text-align:right;width:5%" value=0></input>				
				<button id="button_submit_ld" type="submit" >Print Summary</button>&nbsp;&nbsp;<button id="button_submit_ls" type="submit" >Print Details</button>
				<button id="button_submit_pm" type="submit" >Get Monthly Report</button>
				For Last:
				<select id="nummonth">
					<option>2</option>
					<option selected="true">3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>9</option>
					<option>12</option>
				</select>
				Months 				
			</td>			
        </tr>
    </table>
	</form>	
</div>

<div style="display:none">  	
	<div id="print_item_form" title="Schedule Vs Despatch" style="visibility:hidden">
		<div id="styleRef"></div>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(40);
				print $formArray[0]; 
			?>
		</p>		
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="print_table_top">
			<tr>
				<td rowspan="2" align="center" width="100px" >
					<img id="imgpath" width="70px" />
				</td>
				<td align="center" height="45px" >
					<div style="font-size:20px;"><span id="hdr_head"></span> - <?php print $formArray[1]; ?> </div>
				</td>
				<td rowspan="2" width="100px" valign="top" align="left">
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
			<tr style="font-size:10;">
				<th align="left" width="3%">&nbsp;</th>
				<th align="right" id="grdAvgMonth" width="8%" >0</th>
				<th align="right" id="grdPreSchQty" width="8%" >0</th>
				<th align="right" id="grdPreSchVal" width="9%">0</th>
				<th align="right" id="grdPercent" width="3%" >0</th>
				<th align="center" width="18%" colspan="2">Grand Total</th>
				<th align="right" width="7%" id="grdSchQty" >0</th>
				<th align="right" width="9%" id="grdSchVal" >0</th>
				<th align="right" width="7%" id="grdDisQty" >0</th>
				<th align="right" width="9%" id="grdDisVal" >0</th>
				<th align="right" width="7%" id="grdPenQty">0</th>
				<th align="right" width="9%" id="grdPenVal" >0</th>
				<th align="right" id="grdComp">0</th>
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
