<div class="row justify-content-center text-primary" style="padding-top: 65px;" >
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inward']; ?>"></i> Inward</div>
    <div class="col-12 text-center ">Awaiting Deflashing Issue</div>
    <div class="col-12 text-center">
        <label for="details" class="mt-3">Awaiting List</label>
        <div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
        </div>
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
    </div>
</div>


<form action="" class="row justify-content-center mt-5 mb-5 text-primary" onsubmit="return false;">
    
    <div class="col-5 mr-2"><button class="btn btn-primary  btn-sm" id="button_submit" type="submit">Create DC </button></div>
    <div class="col-5 mb-3"><button class="btn btn-danger btn-sm" id="button_cancel">Clear</button> </div>
</form>



<div style="display:none">
    <div id="issue_dialog" >
    </div>
	<div id="confirm_dialog" >
    </div>	
</div>



<!-- <div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Awaiting Deflashing Issue</strong>
    </div>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
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
<div style="display:none">
    <div id="issue_dialog" >
    </div>
	<div id="confirm_dialog" >
    </div>	
</div> -->
