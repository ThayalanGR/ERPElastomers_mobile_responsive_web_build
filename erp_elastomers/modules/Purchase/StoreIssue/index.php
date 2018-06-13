<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Material Issue</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right" width="40%">Date : </td>
            <td align="left" >
				<input type="date" name="issueDate" id="issueDate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>" onchange="updatePageData()"  /> 
			</td>
        </tr>
    </table>
	</form>
</div>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Issue Details</strong> 
		<span style="position: absolute; right: 20px;" id="new_RMButton">Add</span>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
				<th width="10%" align="left">Raw Material ID</th>
                <th width="30%" align="left">Raw Material Name</th> 
				<th width="20%" align="left">Available Qty (Kgs)</th>
                <th width="20%" align="center">Advised Qty (Kgs)</th>
                <th align="center">Actual Qty (Kgs)</th>
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
	 <b>* Only Items which are 'In Stock' will be listed, Please add any Incoming Goods before updating this form</b>
 	<form action="" onsubmit="return false;">	
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:10px;">
        <tr>
			<td colspan="5" id="error_msg" style="padding:7px;">&nbsp;</td>
            <td align="right">
					<button id="button_submit" type="submit">Update</button>
					<button id="button_cancel">Clear</button>
			</td>
        </tr>
    </table>
	</form>
     </div>		 
</div>

<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
