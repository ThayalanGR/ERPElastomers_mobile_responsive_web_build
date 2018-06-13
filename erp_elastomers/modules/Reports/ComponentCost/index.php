<div id="window_list_wrapper" class="filter-table">
    <div id="window_list_head">
        <strong>Enter Moulding Rates</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="40%">
					Compound Conversion Rate/Kg (Rs)
				</th>
				<th align="left" width="5%">
					<input type="text"  tabindex="1" id="conv_rate" style="width:90%;text-align:right" value="30.00" />
				</th>
				<th align="right" width="15%">
					Insert Process Cost/Piece (Rs)
				</th>
				<th align="right" width="5%">
					<input type="text" tabindex="2" id="insproc_rate" style="width:90%;text-align:right" value="0.10" />
				</th>				
				<th align="left">
					<button id="button_submit" type="submit">Recalculate</button>
				</th>				
			</tr>			
		</table>
	</form>
    <div id="window_list_head">
        <strong>Component Cost<sup> - Calculated without post curing or any special processing or inspection or admin cost(s) </sup></strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body" >
		</div>
    </div>
</div>

