<?php
	$sql			=	" select fullName from tbl_users where status>0 and userType = 'Sub-Contractor' ";
	$jobworkdat		=	@getMySQLData($sql);
	if($jobworkdat['count'] > 0 )
	{
		$jobworker		=	$jobworkdat['data'];
		$jobworklist	=	"";
		foreach($jobworker as $key=>$value){
			$jobworklist	.=	"<option value='".$value['fullName']."'>".$value['fullName']."</option>";
		}	
	}
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Moulding Plan</strong>
		<span id="button_view_tl" style="float:right;margin-top:2px;">Tool List</span>
		<span id="button_view" style="float:right;margin-top:2px;">View Component Stock Level</span>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right" width="20%">Date : </td>
            <td align="left" width="30%">
				<input type="date" name="planDate" id="planDate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?>" />
			</td>
			<td align="left" width="30%"> Location : 
				<select name="operator" id="operator" onchange="updatePageData()">
					<option value="In-House" selected>In-House</option>
					<?php echo $jobworklist;?>	
				</select>
			</td>
        </tr>
    </table>
	</form>
    <div id="window_list_head">
        <strong>Plan Details</strong> 
		<span id="button_add">Add Item</span>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
				<th width="5%" align="center">S.No</th>			
				<th width="18%" align="center">Comp Ref</th>
                <th width="12%" align="center">CPD Ref</th>                
                <th width="10%" align="center">Tool Ref</th>
                <th width="10%" align="center">Act. Cavities</th>
				<th width="15%" align="center">Plan. Lifts</th>
				<th width="10%" align="center">Exp. Output</th>
				<th width="10%" align="center">Cpd Req.</th>
				<th width="5%" align="center">No. of Shifts</th>
				<th align="center">Remove</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body">
		</div>		
     </div>
     <div >
	 <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;">
        <tr>
            <td align="right">
				<button id="button_submit" type="submit">Create</button>
				<button id="button_cancel">Clear</button>
			</td>
        </tr>
    </table>
	</form>
     </div>		 
</div>
<div style="display:none"> 
	<div id="stock_dialog"></div>
</div>
<div style="display:none"> 
	<div id="tool_dialog"></div>
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
