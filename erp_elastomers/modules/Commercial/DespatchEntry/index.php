
		<div class="row justify-content-center text-primary" style="padding-top: 65px;" >
			<div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Despatch Entry']; ?>"></i>  DespatchEntry</div>
			
			<div class="col-12 mt-2">
				<form action="" onsubmit="return false;">
					<label for="invRef" class="">Invoice/DC Reference</label>
					<div class="clearfix row mx-auto">
						<input class="col float-left rounded" name="invRef" id="invRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" placeholder="use scanner/click icon ">
						<button type="button" onClick="" class="float-right btn btn-primary btn-sm text-dark"><i class="fas fa-qrcode fa-1x"></i></button>
					</div>

				</form>
			</div>
			<div class="col-12 text-center">
					<label for="details" class="mt-2">Invoice/DC Details</label>
					
					<div class="row" id="content_body" >
					
					</div>
			</div>
			<div class="col-12 mt-2">
				<div id="errorId"></div>
				<form action="" onsubmit="return false;">
				<div id="new_item_error" style="color:black; background:red; border-radius:2px;"></div>
					<label for="new_PickedBy">Picked by: </label>
					<input class="col rounded" type="text" id="new_PickedBy" name="new_PickedBy" placeholder="Enter name">
					<label for="new_VehicleNum" class="mt-2">Vehicle/Mobile no: </label>
					<input class="col rounded" type="text" id="new_VehicleNum" name="new_VehicleNum"placeholder="enter mob/veh no">
					
					<div class="row text-center mt-3 mb-5">
						<div class="col-8"><button class="btn btn-block btn-primary btn-sm" id="button_submit" type="submit">Update</button></div>
						<div class="col-4"><button class="btn btn-block btn-danger btn-sm" id="button_cancel" >Clear</button> </div>
					</div>
				</form>
			</div>
		</div>










<!-- <div id="window_list_wrapper " style="padding-top:100px; padding-bottom:5px;"> -->
    <!-- <div id="window_list_head">
        <strong>Scan the Invoice/DC</strong>
    </div> -->
	<!-- <form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td width='40%' align="right">Invoice/DC Reference</td>
            <td align="left" >
				<input name="invRef" id="invRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" />
			</td>
		</tr>
    </table>
	</form> -->
    <!-- <div id="window_list_head">
        <strong>Invoice/DC Details</strong> 
    </div> -->
    <!-- <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="6" cellspacing="0" width="100%">
            <tr>
				<th width="5%" align="center">S.No</th>			
				<th width="8%" align="center">Inv. Ref</th>
                <th width="8%" align="center">Inv. Date</th>                
                <th width="25%" align="center">Consignee</th>
                <th width="20%" align="center">Part Number</th>
				<th width="8%" align="center">Total Qty</th>
				<th width="12%" align="center">Inv. Amount</th>
				<th width="8%" align="center">No. of Packets</th>
				<th align="center">Remove</th>
            </tr>
        </table>
    </div> -->
    <!-- <div id="window_list">
        <div id="content_body"></div>		
    </div>
	<hr> -->
	<!-- <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="new_item_error"></div>
		<form action="" onsubmit="return false;">
			<table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;">
				<tr>
					<td width="35%" align="right">
						Picked By:
					</td>
					<td width="20%">
						<input type="text" id="new_PickedBy" name="new_PickedBy" style="width:60%;" tabindex="3" /> 
					</td>
					<td width="10%">
						Vehicle / Mob. No:
					</td>
					<td width="20%">
						<input type="text" id="new_VehicleNum" name="new_VehicleNum" style="width:60%;"  tabindex="4"/> 
					</td>
					<td align="right">
						<button id="button_submit" type="submit">Update</button>
						<button id="button_cancel">Clear</button>
					</td>
				</tr>
			</table>
		</form>
	</div>		  -->
<!-- </div>
 -->
