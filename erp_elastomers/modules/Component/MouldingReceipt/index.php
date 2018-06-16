<div class="row justify-content-center text-primary" style="padding-top: 65px;" >
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inward']; ?>"></i> Inward</div>
    <div class="col-12 text-center ">Moulding Plan</div>
    <div class="col-12 mt-2">
        <form action="" onsubmit="return false;">
            <label for="planDate" class="">Production Date:</label>
            <input class="rounded form-control form-control-sm " type="date" name="prodDate" id="prodDate" onChange="updatePageBehaviour();" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>" />
            <label for="operator" class="">Location:</label>
            <select class="rounded form-control form-control-sm" name="operator" id="operator" onChange="updatePageBehaviour();" >
                <option Selected value = 'In-House'>In-House</option>
                <option value = 'Others'>Others</option>
            </select>
        </form>
    </div>
    <div class="col-12 text-center">
        <label for="details" class="mt-3"><b>List</b></label>
        <div id="content_body"></div>
        <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
    </div>
    <div class="col-12 mt-2">
        <div class="container shadow text-left">
            <div class="row bg-dark text-center">
                <div class="col">Grand Total</div>
            </div>
            <div class="row ">
                <div class="col-6 "> Plan. Lift</div>
                <div class="col-6 text-success" id="plan_lift_total">0.00</div>
            </div>
            <div class="row bg-light">
                <div class="col-6"> Act. Lift</div>
                <div class="col-6 text-success" id="act_lift_total">0.00</div>
            </div>
            <div class="row">
                <div class="col-6">Plan. Qty</div>
                <div class="col-6 text-success" id="ins_qty_total">0.00</div>
            </div>
            <div class="row bg-light">
                <div class="col-6">Act. Qty</div>
                <div class="col-6 text-success" id="act_qty_total">0.00</div>
            </div>
        </div> 
    </div>

</div>

<form action="" class="row justify-content-center mt-5 mb-5 text-primary" onsubmit="return false;">
    <div class="col-5 mr-2"><button class="btn btn-success btn-sm" id="button_submit" type="submit">Update </button></div>
    <div class="col-5 mb-3"><button class="btn btn-danger btn-sm" id="button_cancel">Clear</button> </div>
</form>


<div style="display:none">
    <div id="delete_dialog" >
    </div>
    <div id="update_dialog" >
    </div>	
    <div id="clear_dialog" >
    </div>		
</div>




<!-- <div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Moulding Plan</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width="30%">Production Date</th>
            <th align="left" width="20%"><input type="date" name="prodDate" id="prodDate" onChange="updatePageBehaviour();" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>"  /></th>
            <th align="right" width="15%">Location</th>
            <th align="left" >
				<select name="operator" id="operator" onChange="updatePageBehaviour();" >
					<option Selected value = 'In-House'>In-House</option>
					<option value = 'Others'>Others</option>
				</select>
			</th>			
        </tr>
    </table>
	</form>
</div>

<div id="window_list_wrapper">
    <div id="content_head">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr class="ram_rows_head">
                <th align="center" width="10%" >Key ID</th>
				<th align="center" width="10%" >Location</th>
                <th align="center" width="15%" title="Component Code">Part No.</th>
				<th align="center" width="10%">No of Cavities</th>
                <th align="center" width="10%"  title="Planned Lifts">Plan. Lift</th>
				<th align="center" width="15%"  title="Actual Lifts">Act. Lift</th>
                <th align="center" width="10%" >Plan. Qty</th>
				<th align="center" width="10%" >Act. Qty</th>
                <th align="center">#</th>
             </tr>

                Key ID
                Location
                Part No.
                No of Cavities
                Plan. Lift
                Act. Lift
                Plan. Qty
                Act. Qty



        </table>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
    <div id="content_foot">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
                <th align="center" style="width:45%;font-weight:bold">Grand Total</th>
                 <th align="right" id="plan_lift_total" style="width:10%;font-weight:bold">0.00</th>
                <th align="right" id="act_lift_total" style="width:15%;font-weight:bold">&nbsp;</th>
                <th align="right" id="ins_qty_total" style="width:10%;font-weight:bold">0.00</th>
                <th align="right" id="act_qty_total" style="width:10%;font-weight:bold">&nbsp;</th>
				<th align="right">&nbsp;</th>
            </tr>
        </table>
    </div>
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td width='75%'>&nbsp;</td>
            <td align="right">
					<button id="button_submit" type="submit" >Update</button>
					<button id="button_cancel" type="submit" >Clear</button>
			</td>
        </tr>
    </table>
	</form>	
    <div id="delete_dialog" >
    </div>
    <div id="update_dialog" >
    </div>	
    <div id="clear_dialog" >
    </div>		
</div>
 -->


