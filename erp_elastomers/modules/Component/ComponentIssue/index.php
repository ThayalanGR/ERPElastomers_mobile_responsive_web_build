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
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Outward']; ?>"></i> Outward</div>
    <div class="col-12 text-center ">Compound Issue</div>
    <div class="col-12 mt-2">
        <form action="" onsubmit="return false;">
            <label for="planDate" class="">Plan Date:</label>
            <input type="date" class="rounded form-control form-control-sm " name="planDate" id="planDate" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d"), date("Y"))); ?>" onchange="createAutoComplete();" />
            <label for="operator" class="">Location:</label>
            <select name="operator" class="rounded form-control form-control-sm" id="operator" onChange="createAutoComplete();" >
                    <option selected >In-House</option>
                    <?php print $userlist; ?>
                </select>
        </form>
    </div>
    <div class="col-12 text-center">
        <label for="details" class="mt-3">Awaiting Issue List</label>
        <div class="window_error text-danger" id="issue_item_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body">
        </div>
    </div>
</div>
<div class="row mt-5 mb-5 text-primary"  >
    <div id="errorId"></div>
    <div class="col-6 "><button class="btn btn-primary btn-sm" id="button_submit" type="submit">Create DC </button></div>
    <div class="col-6"><button class="btn btn-danger btn-sm" id="button_cancel">Clear</button> </div>
    </form>
</div>


<div style="display:none">
    <div id="create_dialog" >
    </div>
    <div id="delete_dialog" >
    </div>
    <div id="issue_dialog">
    </div>	
    <div id="clear_dialog" >
    </div>	
</div>

