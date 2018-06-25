<<<<<<< HEAD
<div class="row justify-content-center  text-center text-primary" style="padding-top: 65px;" >
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
	<div class="col-12" style="overflow-x:auto;">
		<div id="window_list_head" class="text-center">
			<strong>Invoice/DC Details</strong> 
		</div>
		<div id="content_head" style="padding-bottom:0px;">
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
		</div>
		<div id="window_list">
			<div id="content_body"></div>		
		</div>
	</div>
</div>
	<div class="row mt-5 mb-5 text-primary justify-content-center " id="new_item_error" style="color:black; background:red; border-radius:2px;"></div>

	<div class="row mt-5 mb-5 text-primary justify-content-center " >
		<form action="" class="jumbotron" style="padding-left:30px; padding-right:30px;" onsubmit="return false;">
			<label for="new_PickedBy">Picked by: </label>
			<input class="col rounded" type="text" id="new_PickedBy" name="new_PickedBy" placeholder="Enter name">
			<label for="new_VehicleNum" class="mt-2">Vehicle/Mobile no: </label>
			<input class="col rounded" type="text" id="new_VehicleNum" name="new_VehicleNum"placeholder="enter mob/veh no">
			<div class="col mt-2">
				<button class="btn btn-primary btn-sm" id="button_submit" type="submit">Update</button>
				<button class="btn btn-danger btn-sm" id="button_cancel" >Clear</button>
			</div>
		</form>
	</div>
=======

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
				<div class="row" id="content_body">
					<!-- <div class="col-12 mt-2">
						<div class="container shadow text-left">
								<div class="row ">
									<div class="col-5 bg-dark" >S.No</div>
									<div class="col-7 bg-dark">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Inv. Ref</div>
									<div class="col-7 text-success">#asdasdasda</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Inv. Date</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Consignee</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Part Number</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Total Qty</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">Inv. Amount</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row border-bottom">
									<div class="col-5">No. of Packets</div>
									<div class="col-7 text-success">#</div>
								</div>
								<div class="row">
									<div class="col-5">Remove</div>
									<div class="col-7 "><i class="text-danger fa fa-window-close"></i></div>
								</div>
						</div> 
					</div>
					<div class="col-12 mt-2">
						<div class="container shadow text-left">
							<div class="row ">
								<div class="col-5 bg-dark" >S.No</div>
								<div class="col-7 bg-dark">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Inv. Ref</div>
								<div class="col-7 text-success">#asdasdasda</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Inv. Date</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Consignee</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Part Number</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Total Qty</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">Inv. Amount</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row border-bottom">
								<div class="col-5">No. of Packets</div>
								<div class="col-7 text-success">#</div>
							</div>
							<div class="row">
								<div class="col-5">Remove</div>
								<div class="col-7 "><i class="text-danger fa fa-window-close"></i></div>
							</div>
						</div> 
					</div> -->
				</div>		
			</div>
		</div>

	<div class="row mt-5 mb-5 text-primary justify-content-center " id="new_item_error" style="color:black; background:red; border-radius:2px;"></div>

		<div class="row mt-5 mb-5 text-primary justify-content-center " >
	
			<form action="" class="jumbotron" onsubmit="return false;">
				<label for="new_PickedBy">Picked by: </label>
				<input class="col rounded" type="text" id="new_PickedBy" name="new_PickedBy" placeholder="Enter name">
				<label for="new_VehicleNum" class="mt-2">Vehicle/Mobile no: </label>
				<input class="col rounded" type="text" id="new_VehicleNum" name="new_VehicleNum"placeholder="enter mob/veh no">
				<div class="col mt-2">
					<button class="btn btn-primary btn-sm" id="button_submit" type="submit">Update</button>
					<button class="btn btn-danger btn-sm" id="button_cancel" >Clear</button>
				</div>
				
			</form>
		</div>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
		
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>

<<<<<<< HEAD
=======

>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
