<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Delivery Challan</strong>
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
					DC Type:
				</th>
				<th align="left" width="10%">
					<select tabindex="3" id="dctype" style="width:80%" onchange="updatePageBehaviour();" >
						<option value='' selected>All</option>
						<option value='cmpd'>Component</option>
						<option value='cpd'>Compound</option>
						<option value='ram'>Raw Material</option>						
					</select>
				</th>				
				<th align="right">
					<a class="email_cpddetails_button link">email Compound Details Report</a>					
				</th>
			</tr>			
		</table>
	</div>
    <div id="window_list_head">
        <strong>DC List</strong>
    </div>
		<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body"></div>
	</div>
	<div >
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
					<input onclick="submitPrint();" type="button" value="Print Selected DC(s)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
			</td>
        </tr>		
    </table>
     </div>		
</div>