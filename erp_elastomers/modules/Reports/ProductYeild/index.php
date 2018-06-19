<script language="javascript" src="<?php echo ISO_BASE_URL; ?>_bin/.run" ></script>
<style>input{font-size:11px;}</style>
<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px; padding-bottom:10px;">
    <div id="window_list_head">
        <strong>Blank Weight Vs Product Weight Yeild</strong>
    </div>
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="right" width="30%">
					For Items Moulded From:
				</th>
				<th align="left" width="15%">
					<input type="date"  tabindex="1" id="from_date" style="width:75%" value="<?php echo date('Y-m-d',strtotime("first day of last month")); ?>" onchange="getData();" />
				</th>
				<th align="right" width="10%">
					To:
				</th>
				<th align="left" width="15%">
					<input type="date" tabindex="2" id="to_date" style="width:75%" value="<?php echo date("Y-m-d",strtotime("last day of last month")); ?>" onchange="getData();" />
				</th>
				<th align="right" width="10%">
					Group By:
				</th>
				<th align="left" >
					<select tabindex="3" id="group_by" onchange="getData();" >
						<option value="cusGroup">Customer Group</option>
						<option value="cmpdCpdName">Compound</option>
					</select>
				</th>				
			</tr>			
		</table>
</div>

<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Component List</strong>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
 	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
			<td colspan="7">&nbsp;</td>
            <td align="right">
					<button id="button_submit" type="submit" style="visibility:hidden" >Print </button>					
			</td>
        </tr>
    </table>
	</form>			
</div>

<div style="display:none">    
	<div id="print_item_form" title="Yeild Statement" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(48);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="15%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="70%" align="center" height="45px">
					<div style="font-size:20px;font-weight:bold"><?php print $formArray[1]; ?></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;"  width="70px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:16px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:16px; border-bottom:0px;" >
					<b> <span id="hdr_title"> </span> </b>
				</td>
			<tr>
		</table>
		<div id="print_body"></div>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_bottom">
			<tr>
				<td width="50%" style="border-bottom:0px;" valign="top">
					<b>Remarks:</b>
					<br /><br />
				</td>
				<td width="25%" style="border-bottom:0px;" valign="top">
					<b>Prepared:</b>
				</td>
				<td style="border-bottom:0px;" valign="top" align="left">
					<b>Approved:</b>
				</td>
			</tr>
		</table>		
    </div>	
</div>
