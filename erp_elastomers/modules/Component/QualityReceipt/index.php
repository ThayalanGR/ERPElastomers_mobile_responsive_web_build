
 <div class="row justify-content-center text-primary" style="padding-top: 65px;" >
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inspection Entry']; ?>"></i> Inspection Entry</div>
<<<<<<< HEAD
    <!-- <div class="col text-right"><span id="button_view" style="font-size:10px;  padding:0px;" class="btn btn-primary btn-sm text-white text-right">Today's Plan</span></div> -->
    <div class="col-12 text-center ">Quality Entry</div>


    <form action="" onsubmit="return false;">
			<!-- <label for="invRef" class="">Invoice/DC Reference</label> -->
			<div class="clearfix row mx-auto">
				<input class="col float-left rounded" name="invRef" id="invRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" placeholder="use scanner/click icon ">
				<button type="button" onClick="" class="float-right btn btn-primary btn-sm text-dark"><i class="fas fa-qrcode fa-1x"></i></button>
			</div>
    </form>

    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
    <div class="col-12 text-center">
        <!-- <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div> -->
        <div id="content_body"></div>
    </div>
</div>
<div class="row text-primary justify-content-center " id="new_item_error" style="color:black; background:red; border-radius:2px; display: none;"></div>

=======
    <div class="col text-right"><span id="button_view" style="font-size:10px;  padding:0px;" class="btn btn-primary btn-sm text-white text-right">Today's Plan</span></div>
    <div class="col-12 text-center ">Quality Entry</div>
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
    <div class="col-12 text-center">
        <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647

<div style="display:none">
    <div id="issue_dialog"></div>
	<div id="confirm_dialog"></div>
</div>

<<<<<<< HEAD


=======
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
