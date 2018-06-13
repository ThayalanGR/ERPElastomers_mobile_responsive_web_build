<?php
	$sql		=	" select * from tbl_users where status = 1 and userType = 'Sub-Contractor' ";
	$resUser	=	@getMySQLData($sql);
	$user		=	$resUser['data'];
	$userlist	=	"";
	foreach($user as $key=>$value){
		$userlist	.=	"<option>".$value['fullName']."</option>";
	}
	
?>

<div id="window_list_wrapper">	
    <div id="window_list_head">
        <strong>Pending Items</strong>
    </div>
    <form action="" onsubmit="return false;">
		<table border="0" cellspacing="0" cellpadding="0" class="new_form_table">
			<tr>
				<th align="right" width="35%">
					Items pending with
				</th>
				<th align="left" width="10%">
					<select name="operator1" id="operator1" onChange="fieldChange();" >
						<option selected >ALL</option>
						<option>In-House</option>
						<?php print $userlist; ?>
					</select>
				</th>	
				<th align="left" width="2%">
					 For :
				</th>			
				<th align="left" width="12%" >
					<select name="phasefield"  style="width:95%" id="phasefield" onChange="fieldChange();" >
						<option Selected value = 'CPDISSGRP'>Compound Issue</option>
						<option value = 'MLDRCPTGRP'>Moulding Receipt</option>
						<option value = 'DEFISSGRP'>Deflashing Issue</option>
						<option value = 'DEFRECGRP'>Deflashing Receipt</option>
						<option value = 'QUALENTGRP'>Quality Receipt</option>
					</select>
				</th>			
				<th align="left" width="6%">
					for more than
				</th>
				<th align="left">
					<select name="datefield"  style="width:20%" id="datefield" onChange="fieldChange();" >
						<option value = '1'>1 day</option>
						<option value = '2'>2 days</option>
						<option Selected value = '3'>3 days</option>
						<option value = '4'>4 days</option>
						<option value = '5'>5 days</option>
						<option value = '6'>6 days</option>
						<option value = '7'>7 days</option>
						<option value = '10'>10 days</option>
						<option value = '14'>14 days</option>
						<option value = '15'>15 days</option>
						<option value = '21'>21 days</option>
						<option value = '30'>30 days</option>						
					</select>
				</th>
			</tr>
		</table>
		<br />
	</form>
	<div id="window_list_head" >
		<strong>Items List</strong>
	</div>	
		<div id="window_list">
			<div class="window_error">
				<div class="loading_txt"><span>Loading Data . . .</span></div>
			</div>
			<div id="content_body">
			</div>		
		</div>
 	<form id="exportform" name="exportform" action=""  method="post" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td colspan="7">&nbsp;</td>
            <td align="right">
				<input id="type" name="type" type="hidden"  />
				<input id="fetchdate" name="fetchdate" type="hidden"  />
				<input id="operator" name="operator" type="hidden"  />			
				<button id="button_submit" name="button_submit" type="submit" onclick="exportPlanDetailList();" />Export As CSV</button>
			</td>
        </tr>
    </table>
	</form>			
	<div id="show_plan_form" title="Show Pending Keys" style="visibility:hidden">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
				<tr>
					<th width="3%" align="center">S.No</th>
					<th width="8%" align="center">Key No</th>
					<th width="9%" align="center">Plan Date</th>
					<th width="10%" align="center">Pending From</th>
					<th width="10%" align="center">Operator</th>
					<th width="10%" align="center">Tot. Lifts</th>
					<th width="10%" align="center">Full Qty</th>
					<th width="10%" align="center">Full Val.</th>
					<th width="10%" align="center">Pend. Lifts</th>
					<th width="10%" align="center">Pend. Qty</th>
					<th align="center">Pend. Val.</th>
				</tr>
				<tr>
				   <td colspan="11" style="padding:0px;">
						<div id="new_PlanList">
							<table width='100%'>
                                 <tr>
									<th width="3%" align="center">&nbsp;</th>
									<th width="8%" align="center">&nbsp;</th>
									<th width="9%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th width="10%" align="center">&nbsp;</th>
									<th align="center">&nbsp;</th>
								</tr>
							</table>
						</div>
					</td>				
				</tr>
				<tr>
					<th class="last" colspan="5">Total</th>
					<th class="last" align="right" id="plan_tot_lifts">0</th>					
					<th class="last" align="right" id="plan_tot_qty">0</th>
					<th class="last" align="right" id="plan_tot_val">0.00</th>
					<th class="last" align="right" id="iss_tot_lifts">0</th>
					<th class="last" align="right" id="iss_tot_qty">0</th>					
					<th class="last" align="right" id="iss_tot_val">0.00</th>					
				</tr>
            </table>
        </form>
    </div>		
</div>

