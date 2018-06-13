<div id="window_list_wrapper" >
    <div id="window_list_head">
        <strong>Final Plan</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" width="100%" class="new_form_table">
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
				<th align="left">
					<input type="date" tabindex="2" id="to_date" style="width:25%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")+1  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
			</tr>
		</table>
		<br />
		<div id="window_list_head">
			<strong>Batch List</strong> 			
		</div>	
		<div id="window_list">
			<div class="window_error">
				<div class="loading_txt"><span>Loading Data . . .</span></div>
			</div>
			<div id="content_body">
			</div>
		</div>
	</form>
		<table border="0" cellpadding="6" cellspacing="0" width="100%">
			<tr>
				<th align="right" style="width:25%;">Grand Total</th>
				<th align="right" style="width:20%;" id="master_total" >0</th>
				<th align="right" style="width:20%;" id="final_total">0</th>
				<th align="right">&nbsp;</th>
			</tr>		
		</table>	
	<div style="text-align:right;" >
		<strong>Show Plan Vs Actual Mixing Report for last 
			<select tabindex="4" id="no_months" style="width:5%" > 
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
		<input type="button" value="Go" onclick="openReport();" />
	</div>			
	
</div>
