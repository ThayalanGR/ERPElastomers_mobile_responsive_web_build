<?php 
	$tool_id	=	trim((ISO_IS_REWRITE)?$_VAR['toolId']:$_GET['toolId']);
	
	$sql_tool	=	"select *, DATE_FORMAT(intro_date, '%d-%b-%Y')as intro_date from tbl_tool where tool_ref='".$tool_id."'";
	$out_tool	=	@getMySQLData($sql_tool);
	$data		=	$out_tool['data'][0];
	
	$sql_list	=	"select *, DATE_FORMAT(ttn_date, '%d-%b-%Y')as date from tbl_tool_transfer where tool_ref='".$tool_id."' order by ttn_ref desc";
	$out_list	=	@getMySQLData($sql_list);
	$status		=	$out_list['status'];
	$list		=	$out_list['data'];
?>
<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Tool Details</strong>
    </div>
    <table class="content_only" border="0" cellpadding="7" cellspacing="0" style="margin:5px 10px 0;">
        <tr>
            <th align="left" width="25%">Tool Reference</th>
            <td width="35%"><?php echo $data['tool_ref']?></td>
            <th align="left" width="15%">Component</th>
            <td align="right"><?php echo $data['comp_part_ref']?></td>
        </tr>
        <tr>
            <th align="left">No. Of Cavities</th>
            <td><?php echo $data['no_of_cavities']; ?></td>
            <th align="left">No. Of Active Cavities</th>
            <td align="right"><?php echo $data['no_of_active_cavities']; ?></td>
        </tr>
        <tr>
            <th align="left">Nature</th>
            <td><?php echo $data['nature']; ?></td>
            <th align="left">Date Of Introduction</th>
            <td align="right"><?php echo $data['intro_date']; ?></td>
        </tr>
    </table>
</div>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Tool Location History</strong>
    </div>
    <div id="content_head">
        <table border="0" cellpadding="5" cellspacing="0" width="100%;">
            <tr class="ram_rows_head">
                <th width="10%" align="left">No</th>
                <th width="15%" align="left">Transfer Note</th>
                <th width="15%" align="left">Date</th>
                <th width="30%" align="left">Tranferer</th>
                <th align="left">Transferee</th>
            </tr>
        </table>
    </div>
    <div id="window_list">
        <div id="content_body" class="content_stock">
            <table border="0" cellpadding="5" cellspacing="0" width="100%;">
                <?php if(count($list) > 0): ?>
                    <?php
                        for($i=0; $i<count($list); $i++):
                            $class = ($i%2==0)?'content_rows_light':'content_rows_dark';
                            $no = $i+1;
                            $ttn_ref = $list[$i]['ttn_ref']; 
                            $date	 = $list[$i]['date'];
                            $transferer = $list[$i]['transferor_userName'];
                            $transferee = $list[$i]['transferee_userName'];
                    ?>
                    <tr class="<?php echo $class?>">
                        <td width="10%" align="left"><?php echo $no; ?></td>
                        <td width="15%" align="left"><?php echo $ttn_ref; ?></td>
                        <td width="15%" align="left"><?php echo $date; ?></td>
                        <td width="30%" align="left"><?php echo $transferer; ?></td>
                        <td align="left"><?php echo $transferee; ?></td>
                    </tr>
                    <?php endfor; ?>
                <?php elseif($status != "success"): ?>
                    <div class="window_error"><div class="warning_txt"><span>Error Fetching Data . . . Err No: <?php echo $status; ?></span></div></div>
                <?php else: ?>
                    <div class="window_error"><div class="warning_txt"><span>No Data Available . . .</span></div></div>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
