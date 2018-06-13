<?php 
	$tool_id	=	trim((ISO_IS_REWRITE)?$_VAR['toolId']:$_GET['toolId']);
	global	$custappdocs_upload_dir;	
	$uploadPath 	= 	$_SESSION['app']['iso_dir'].$custappdocs_upload_dir.$tool_id;
	$filesArr		=	glob("$uploadPath/*.*");	
	$sql_list		=	"select subId, DATE_FORMAT(tas.subDate, '%d-%m-%Y')as subDate 
							  from tbl_approval_submit tas
							  inner join tbl_trn tt on tas.toolRef = tt.trnId
							where tt.toolId='".$tool_id."' and tas.status = 1 order by tas.subDate desc";
	$out_list		=	@getMySQLData($sql_list);
	$status			=	$out_list['status'];
	$list			=	$out_list['data'];
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Tool Approval Docs</strong>
    </div>
		<table border="0" cellpadding="5" cellspacing="0" width="100%;">
			<?php if(count($filesArr) > 0): ?>
				<?php
					for($i=0; $i<count($filesArr); $i++){
						$class = ($i%2==0)?'content_rows_light':'content_rows_dark';
						$no = $i+1;
						$fileRef = basename($filesArr[$i]); 
				?>
				<tr class="<?php echo $class?>">
					<td width="10%" align="left"><?php echo $no; ?></td>
					<td align="left"><?php echo "<a href='/".$custappdocs_upload_dir.$tool_id."/".$fileRef."' target='_blank'>".$fileRef."</a>";?> </td>
				</tr>
				<?php } ?>
			<?php else: ?>
				<div class="window_error"><div class="warning_txt"><span>No Data Available . . .</span></div></div>
			<?php endif; ?>
		</table>
</div>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Tool Approval Submission History</strong>
    </div>
    <div id="content_head" style="padding-bottom:0px;">
        <table border="0" cellpadding="5" cellspacing="0" width="100%;">
            <tr class="ram_rows_head">
                <th width="10%" align="left">No</th>
                <th width="25%" align="left">Submission Id</th>
                <th width="25%" align="left">Submit Date</th>
                <th align="right">#</th>
            </tr>
        </table>
    </div>
    <div id="window_list" style="max-height:325px;">
        <div id="content_body" class="content_stock">
            <table border="0" cellpadding="5" cellspacing="0" width="100%;">
                <?php if(count($list) > 0): ?>
					<?php
                        for($i=0; $i<count($list); $i++){
                            $class = ($i%2==0)?'content_rows_light':'content_rows_dark';
                            $no = $i+1;
                            $keyRef = $list[$i]['subId']; 
                            $keyDate = $list[$i]['subDate'];
							$getUrl	=	"/NPD/ApprovalSubmissions/page=invoice/invID=".$keyRef;
                    ?>
                    <tr class="<?php echo $class?>">
                        <td width="10%" align="left"><?php echo $no; ?></td>
                        <td width="25%" align="left"><?php echo $keyRef; ?></td>
                        <td width="25%" align="left"><?php echo $keyDate; ?></td>
                        <td align="left"><?php echo "<a href='$getUrl' target='_blank'>View</a>";?></td>
                    </tr>
                    <?php } ?>
                <?php elseif($status != "success"): ?>
                    <div class="window_error"><div class="warning_txt"><span>Error Fetching Data . . . Err No: <?php echo $status; ?></span></div></div>
                <?php else: ?>
                    <div class="window_error"><div class="warning_txt"><span>No Data Available . . .</span></div></div>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
