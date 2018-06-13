<?php
	global $grn_customers;
	$recvlist	=	"";
	$noofitems	=	0;
	for($ct=0;$ct<count($grn_customers);$ct++){
		$recvlist	.=	"<option>".$grn_customers[$ct]."</option>";
		$noofitems++;
	}
	if($noofitems > 1)
		$recvlist	=	"<option selected></option>".$recvlist;	
?>
<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
#window_list{padding-left:7px;padding-top:5px;}
</style>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Get List</strong>
    </div>
	<form id="exportform" name="exportform" action=""  method="post" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>		
            <th align="right" width='40%'>Select Type :</th>
            <th align="left" width='10%'>
				<select name="itemOpt" id="itemOpt" tabindex="1">
					<option>Purchase</option>
					<option>Mixing</option>
					<option>Moulding</option>
				</select>
			</th>
			<th align="left" >
				<input id="type" name="type" type="hidden"  />
				<input id="itemtype" name="itemtype" type="hidden"  />
				<button id="export_submit" type="submit" tabindex="2" >Get List</button>
			</th>
    </table>
	</form>
	<br/>
    <div id="window_list_head">
        <strong>Upload Opening Stock</strong>
    </div>
    <form action="" onsubmit="return false;">
	<form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>		
            <th align="right" width='15%'>Select Stock Type :</th>
            <th align="left" width='10%'>
				<select name="new_UploadType" id="new_UploadType" tabindex="3">
					<option></option>
					<option value="1">Purchase</option>
					<option value="2">Mixing</option>
					<option value="3">Moulding</option>
				</select>
			</th>
			<th align="left" width='10%'>
				For: 
				<select id="recFrom" tabindex="4" >
					<?php echo $recvlist;?>
				</select>
			</th>
            <th align="right" width='15%'>Choose a file to upload :</th>
            <th align="left" width='30%'>
				<input id="file" name="file" type="file" accept=".csv" style="width:95%" tabindex="5" /> 
			</th>
            <th align="right" ><input id="open_submit" type="submit" tabindex="6" value="Upload Stock" /></th>
		</tr>
		<tr>
            <td id="error_msg" colspan="5" style="padding:7px;">&nbsp;</td>
        </tr>
    </table>	
	</form>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
</div>
