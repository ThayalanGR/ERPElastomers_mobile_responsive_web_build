<?php
	$sql		=	" select * from tbl_users where status = 1 and userType = 'Sub-Contractor' ";
	$resUser	=	@getMySQLData($sql);
	$user		=	$resUser['data'];
	$userlist	=	"";
	foreach($user as $key=>$value){
		$userlist	.=	"<option>".$value['fullName']."</option>";
	}
	
?>

<div id="window_list_wrapper" class="filter-table" style="overflow-x:auto; padding-top:65px;" >	
    <div id="window_list_head">
        <strong>Vendor Bill Passing</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="40%">
					For Month:
				</th>
				<th align="left">
					<input type="text" rel="datepicker" class="monthOnly" tabindex="1" id="to_date" style="width:15%" value="<?php echo date("F Y"); ?>" />
				</th>			
			</tr>
        <tr>
            <th align="right">Vendor</th>
            <th align="left" >
				<select name="operator" id="operator" >
					<option>All</option>
					<option>In-House</option>
					<?php print $userlist; ?>
				</select>
			</th>
		</tr>	
        <tr>
            <th align="right">Process</th>
            <th align="left" >
				<select name="process" id="process" >
					<option>All</option>
					<option>Moulding</option>
					<option>Trimming</option>
				</select>
			</th>
		</tr>
        <tr>
            <th align="right">Type</th>
            <th align="left" >
				<select name="reporttype" id="reporttype" >
					<option>All</option>
					<option>Summary</option>
					<option>Key-wise</option>
					<option>Vendor-wise</option>
				</select>
			</th>
		</tr>
       <tr>
			<td>
				&nbsp;
			</td>
            <td> 
				<button id="button_submit" >Get Bill(s)</button>
			</td>
        </tr>					
		</table>
		<br />
	</form>
</div>
