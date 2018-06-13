<?php
	global $grn_customers;
	$custlist	=	"";
	for($ct=0;$ct<count($grn_customers);$ct++){
		$custlist	.=	"<option>".$grn_customers[$ct]."</option>";
	}
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Final Plan</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width="40%">Final Date</th>
            <th align="left" width="15%"><input type="date" name="finalDate" id="finalDate"  value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?>"  /></th>
			<th align="right" width="10%">Shift</th>
            <th align="left"><select id="shift"><option>1</option><option>2</option></select></th>
        </tr>
    </table>
	</form>
</div>



<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Select the Batches for Final</strong>
        <!--<span id="button_add">New</span>-->
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th width="15%" align="left">Batch Code</th>
                <th width="20%" align="left">Compound Name</th>
                <th width="10%" align="left">Base Polymer</th>
				<th width="15%" align="left">Planned For</th>
                <th width="15%" align="center">Master Batch Wgt.</th>
                <th width="15%" align="center">Expected Batch Wgt.</th>
                <th align="center">#</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
     </div>
     <div >
 	<form action="" onsubmit="return false;">
	
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td align="center" colspan="6"><u>Number of Batches Selected</u> <b><div id="select_count"></div></b> </td>
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
    <div id="confirm_dialog"></div>
    <div id="new_cust_form" title="Update Customer">
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
                <tr>
                    <td valign="top" style="width:35%">
                        Select Customer:
                    </td>
                    <td>
						<select id="new_Customer" tabindex="1" style="width:50%" >
							<option selected></option>
							<?php echo $custlist;?>
						</select>
                    </td> 
                </tr>
            </table>
        </form>
    </div>	
</div>

