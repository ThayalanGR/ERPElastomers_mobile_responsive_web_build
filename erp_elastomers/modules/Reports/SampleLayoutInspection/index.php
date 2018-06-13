<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Sample Inspection Layout Report</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="25%">
					From:
				</th>
				<th align="left" width="25%">
					<input type="date"  tabindex="1" id="from_date" style="width:40%" value="<?php echo date('Y-m-d',mktime(0, 0, 0, 1  , 1, date("Y"))); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="right" width="5%">
					To:
				</th>
				<th align="left">
					<input type="date" tabindex="2" id="to_date" style="width:22%" value="<?php echo date("Y-m-d"); ?>" onchange="updatePageBehaviour();" />
				</th>				
			</tr>			
		</table>
	</form>
    <div id="window_list_head">
        <strong>Product List</strong>
    </div>
	<div class="window_error">
		<div class="loading_txt"><span>Loading Data . . .</span></div>		
	</div>
	<div id="window_list">
		<div id="content_body">
		</div>	
	</div>	
</div>