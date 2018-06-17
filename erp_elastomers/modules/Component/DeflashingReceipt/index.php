<?php
	$sql		=	" select * from tbl_users where status = 1 and userType = 'Sub-Contractor' ";
	$resUser	=	@getMySQLData($sql);
	$user		=	$resUser['data'];
	$userlist	=	"";
	foreach($user as $key=>$value){
		$userlist	.=	"<option>".$value['fullName']."</option>";
	}
	
?>


<div class="row justify-content-center text-primary" style="padding-top: 65px;" >
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inward']; ?>"></i> Inward</div>
    <div class="col-12 text-center ">Deflashing Reciept</div>
    <div class="col-12 mt-2">
        <form action="" onsubmit="return false;">
            <label for="operator" class="">Location:</label>
            <select name="operator" class="rounded form-control form-control-sm" id="operator" onChange="createAutoComplete();" >
                    <option>All</option>
                    <option selected >In-House</option>
                    <?php print $userlist; ?>
                </select>
        </form>
    </div>
    <div class="col-12 text-center">
        <label for="details" class="mt-3">Awaiting Deflashing Receipt</label>
        <div class="window_error text-danger" id="issue_item_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body">
        </div>
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
    </div>
</div>

<form action="" class="row justify-content-center mt-5 mb-5 text-primary" onsubmit="return false;">   
    <div class="col-5 mr-2"><button class="btn btn-primary  btn-sm" id="button_submit" type="submit">Update</button></div>
    <div class="col-5 mb-3"><button class="btn btn-danger btn-sm" id="button_cancel">Clear</button> </div>
</form>

   
<div style="display:none">
    <div id="update_dialog" ></div>
</div>






