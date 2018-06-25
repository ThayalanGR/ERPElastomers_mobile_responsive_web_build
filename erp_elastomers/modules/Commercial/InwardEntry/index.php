
<div class="row justify-content-center text-primary mt-5" style="padding-top: 65px;" >
<<<<<<< HEAD
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inward Entry']; ?>"></i> InwardEntry</div>
=======
<div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inward Entry']; ?>"></i> InwardEntry</div>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
    <div class="col-12 mt-2">
        <form action="" onsubmit="return false;">
            <label for="dcRef" class="">Enter DC no.</label>
            <div class="clearfix row mx-auto">
<<<<<<< HEAD
                <input class="col float-left rounded" name="dcRef" id="dcRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" placeholder="use scanner/click icon "/>
                <button type="button" onClick="" class="float-right btn btn-primary btn-sm text-dark"><i class="fas fa-qrcode fa-1x"></i></button>
            </div>
        </form>
    </div>
=======
                <input class="col float-left rounded" name="dcRef" id="dcRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" placeholder="use scanner/click icon ">
                <button type="button" onClick="" class="float-right btn btn-primary btn-sm text-dark"><i class="fas fa-qrcode fa-1x"></i></button>
            </div>
        </form>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
    <div class="col-12 text-center mt-2">
        <label for="details" class="mt-2">Enter DC Receipt Values</label>
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
        <div id="window_list">
            <div class="window_error">
                <div class="loading_txt"><span>Loading Data . . .</span></div>
            </div>
<<<<<<< HEAD
        </div>
        <div class="row" id="content_body"></div>
    </div>
</div>
      
<div class="row mt-5 mb-5 text-primary justify-content-center " >
    <div class="col mt-2 text-center">
        <button class="btn btn-primary btn-sm" id="button_submit" type="submit">Update</button>
        <button class="btn btn-danger btn-sm" id="button_cancel" >Clear</button>
    </div>
</div>

<div>
<div id="update_dialog" ></div>		 
</div>
=======
        <div class="row" id="content_body">
        </div>
    </div>
</div>
    
    <div class="row mt-5 mb-5 text-primary justify-content-center " >
        <div class="col mt-2 text-center">
            <button class="btn btn-primary btn-sm" id="button_submit" type="submit">Update</button>
            <button class="btn btn-danger btn-sm" id="button_cancel" >Clear</button>
        </div>
    </div>

    <div id="update_dialog" ></div>		 
    </div>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647
