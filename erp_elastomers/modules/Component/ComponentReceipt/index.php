<?php
	$sql		=	" select fullName from tbl_users where status = 1 and isBranch = 1 ";
	$resUser	=	@getMySQLData($sql);
	$user		=	$resUser['data'];
	$userlist	=	"";
	foreach($user as $key=>$value){
		$userlist	.=	"<option>".$value['fullName']."</option>";
	}	
?>

<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Upload Component DC</strong>
    </div>
	<form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
		<tr>
            <th align="right" width='40%'>Location</th>
            <th align="left" >
				<select name="operator" id="operator" >
					<?php print $userlist; ?>
				</select>
			</th>
		</tr>	
        <tr>
            <th align="right" width='35%'>Choose a file to upload :</th>
            <th align="left" width='30%'>
				<input id="file" name="file" type="file" accept=".csv" style="width:90%" tabindex="1" /> 
			</th>
            <th align="left" ><input id="sch_submit" type="submit" value="Upload" /></th>
		</tr>				
    </table>	
	</form>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
	<br/>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
</div>
