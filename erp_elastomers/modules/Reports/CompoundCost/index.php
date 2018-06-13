<?php 	
	global $grn_customers;
	$recvlist	=	"";
	$noofitems	=	0;
	$options	=	"";
	for($ct=0;$ct<count($grn_customers);$ct++){
			$noofitems++;
			$options	.=	"<option>".$grn_customers[$ct]."</option>";
	}
	if($noofitems > 1)
		$recvlist	=	"<option selected>All</option>";
	$recvlist	.=	$options;
?>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Compound Rate</strong>
    </div>
	<div id="raw_material">
		<div id="content_head" style="padding-bottom:0px;">
			<table border="0" cellpadding="6" cellspacing="0" width="100%;">
				<tr>
					<th colspan="3" align="right">
						For
					</th>
					<th>
						<select id="customer" tabindex="1" onchange="getData()">
							<?php echo $recvlist;?>
						</select>
					</th>
					<th align="right">
						Add Mixing Rate/Wastage
					</th>
					<th colspan="3" align="left">
						<select tabindex="1" id="calcCpdRate" onchange="getData()"><option>No</option><option>Yes</option></select>
					</th>				
				</tr>					
				<tr>
					<th width="2%">&nbsp;</th>
					<th width="8.3%" align="left">Code</th>
					<th width="24.8%" align="left">Name</th>
					<th width="9%" align="left">Shrinkage</th>
					<th width="21%" align="left">Polymer</th>
					<th width="9%" align="right">&nbsp;</th>
					<th width="9%" align="right">&nbsp;</th>
					<th style="text-align:right;padding-right:45px;">Rate/Kg</th>
				</tr>
				<tr><td colspan="8" ><span id="errmsg"></span></td></tr>
			</table>	
		</div>
	</div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body" style="height:auto;" ></div>
    </div>
</div>
</div>
    
