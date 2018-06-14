
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



		<div class="row mt-5 mb-5 text-primary " >
		<div id="new_item_error" style="color:black; background:red; border-radius:2px;"></div>
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
		
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>


