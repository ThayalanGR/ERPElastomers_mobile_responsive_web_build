<?php
	$sql		=	" select * from tbl_users where status = 1 and userType = 'Sub-Contractor' ";
	$resUser	=	@getMySQLData($sql);
	$user		=	$resUser['data'];
	$userlist	=	"";
	foreach($user as $key=>$value){
		$userlist	.=	"<option>".$value['fullName']."</option>";
	}	
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Compound Issue</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right" width="30%">Plan Date : </td>
            <td align="left" width="15%">
				<input type="date" name="planDate" id="planDate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>" onchange="createAutoComplete();" />
			</td>
            <th align="right" width='10%'>Location</th>
            <th align="left" >
				<select name="operator" id="operator" onChange="createAutoComplete();" >
					<option selected >In-House</option>
					<?php print $userlist; ?>
				</select>
			</th>			
        </tr>
    </table>
	</form>
</div>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Awaiting Issue List</strong>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="6%" align="left" title="Key Reference">Key Ref.</th>
                <th width="10%" align="left">Plan Date</th>
                <th width="10%" align="left" title="Part Number">Part No</th>
                <th width="12%" align="left" title="Part Description">Part Desc.</th>
                <th width="6%" align="right" title="Planned Lifts">Plnd Lif.</th>
                <th width="10%" align="right" title="Planned Qty">Plnd Qty</th>
				<th width="10%" align="center" title="Blank Weight">Blk Wgt(gm)</th>
                <th width="10%" align="right" title="Adviced Qty">Adv. Qty(kg)</th>
				<th width="15%" align="right" title="Issued Qty">Iss. Qty(kg)</th>
                <th align="right" style="padding-right:20px;">#</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
    	<div class="window_error" id="issue_item_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
     <div >
 	<form action="" onsubmit="return false;">
	
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
					<button id="button_submit" type="submit">Create DC</button>
					<button id="button_cancel">Clear</button>
			</td>
        </tr>		
    </table>
	</form>
     </div>	
</div>

<div style="display:none">
    <div id="create_dialog" >
    </div>
    <div id="delete_dialog" >
    </div>
    <div id="issue_dialog" >
    </div>	
    <div id="clear_dialog" >
    </div>	
</div>
