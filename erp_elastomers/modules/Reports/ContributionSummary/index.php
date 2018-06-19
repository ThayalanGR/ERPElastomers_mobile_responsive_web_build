<?php
	global $blanksGroup;
	
	$sql			=	"select prodType from tbl_product_group";
	$resTx			=	@getMySQLData($sql);
	$prodGroupData	=	$resTx['data'];	
	$progrplist		=	"<option value='ALL'>Any</option>";
	foreach($prodGroupData as $prodGroup){
		$value		=	$prodGroup['prodType'];
		$progrplist	.=	"<option>".$value."</option>";
	}		
	
	$blnkgrplist	=	"<option value='ALL'>Any</option><option value='0'>Less Than ".($blanksGroup[0])." gms</option>";
	for($ct=0;$ct<count($blanksGroup);$ct++){
		if($ct < count($blanksGroup) - 1)
			$blnkgrplist	.=	"<option value='".$blanksGroup[$ct]."' >".$blanksGroup[$ct]." to Less Than ".$blanksGroup[$ct+1]." gms</option>";
		else
			$blnkgrplist	.=	"<option value='".$blanksGroup[$ct]."' > Greater Than or equal to ".$blanksGroup[$ct]." gms</option>";
	}
	
	
	$sql			=	" select distinct upper(cusgroup) as cusGroup from tbl_customer where status = 1 ";
	$custdata		=	@getMySQLData($sql);
	if($custdata['count'] > 0 )
	{
		$customer		=	$custdata['data'];
		$customerlist	=	"<option value='ALL'>Any</option>";
		foreach($customer as $key=>$value){
			$customerlist	.=	"<option>".$value['cusGroup']."</option>";
		}	
	}
?>

<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;" >
    <div id="window_list_head">
        <strong>Contribution Summary</strong>
    </div>
	<div>
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">
			<tr>
				<th align="left" width="15%">
					Customer Group:
				</th>
				<th align="left" width="20%">
					<select tabindex="1" id="cust_group" onchange="updatePageBehaviour();" >
						<?php echo $customerlist; ?>
					</select>
				</th>
				<th align="left" width="15%">
					Product Group:
				</th>
				<th align="left" width="20%">
					<select tabindex="2" id="prod_group" onchange="updatePageBehaviour();" >
						<?php echo $progrplist; ?>
					</select>					
				</th>
				<th align="left" width="15%">
					Blank Group:
				</th>
				<th align="left">
					<select tabindex="3" id="blank_group" onchange="updatePageBehaviour();" >
						<?php echo $blnkgrplist; ?>
					</select>					
				</th>
			</tr>				
			<tr>
				<th align="left">
					For Items Sold From:
				</th>
				<th align="left" >
					<input type="date"  tabindex="4" id="from_date" style="width:50%" value="<?php echo date('Y-m-d',strtotime("first day of last month")); ?>" onchange="updatePageBehaviour();" />
				</th>
				<th align="left" >
					To:
				</th>
				<th align="left" >
					<input type="date" tabindex="5" id="to_date" style="width:50%" value="<?php echo date("Y-m-d",strtotime("last day of last month")); ?>" onchange="updatePageBehaviour();" />
				</th>			
				<th align="left" >
					Group By:
				</th>
				<th align="left" >
					<select tabindex="6" id="group_by" onchange="updatePageBehaviour();" >
						<option value='cusGroup'>Customer Group</option>
						<option value='cmpdProdGroup'>Product Group</option>
						<option value='blankWgtGroup'>Blank Group</option>
					</select>
				</th>
			</tr>			
		</table>
	</div>
    <div id="window_list_head">
        <strong>Result List</strong>
    </div>
	<div id="window_list">
		<div class="window_error">
			<div class="loading_txt"><span>Loading Data . . .</span></div>
		</div>
		<div id="content_body">
		</div>
	</div>

	<table border="0" class="print_table" cellpadding="6" cellspacing="0" width="100%">
		<tr style="font-size:8px;">
			<th width="19%" align="center"><span step="1" class="view_button link">Total</span></th>
			<th width="8%" align="right" id="val_total1">Cpd. Qty Used<sup>Kg</sup></th>
			<th width="10%" align="right" id="val_total2">Cpd. Cost <sup>Rs</sup></th>
			<th width="8%" align="right" id="val_total3">Lifts<sup>No</sup></th>
			<th width="10%" align="right" id="val_total4">Mold. Cost <sup>Rs</sup></th>
			<th width="10%" align="right" id="val_total5">Trim. Cost <sup>Rs</sup></th>
			<th width="10%" align="right" id="val_total6">Insp. Cost <sup>Rs</sup></th>
			<th width="10%" align="right" id="val_total7">Sales Val. <sup>Rs</sup></th>
			<th width="10%" align="right" id="val_total8">Contribution <sup>Rs</sup></th>	
			<th align="right" id="val_total9">Contrib. % </th>						  
		</tr>				
	</table>	
	<div >
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
				<input id="submitPrint" type="button" value="Print" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover"/>
			</td>
        </tr>		
    </table>
    </div>	
	<div id="show_plan_form1" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body1">
		</div>
    </div>	
	<div id="show_plan_form2" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body2">
		</div>
    </div>
	<div id="show_plan_form3" title="Show Detailed List" style="visibility:hidden">
		<div id="detail_body3">
		</div>
    </div>		
</div>



<div style="display:none">    
	<div id="print_item_form" title="Contribution Summary" style="visibility:hidden">
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(47);
				print $formArray[0]; 
			?>
		</p>	
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="10%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="65%" align="center" height="45px">
					<div style="font-size:20px;"><b><?php print $formArray[1]; ?></b></div>
				</td>
				<td rowspan="2" style="border-bottom:0px;"  width="70px" valign="top" align="left">
					<b>Date:</b> <br /><div style="font-size:16px;"><b><span id="hdr_date"> </span></b>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:16px; border-bottom:0px;" ><b><span id="hdr_title"> </span> </b>
				</td>
			<tr>
		</table>
		<div id="print_body"></div>
		<table border="0" class="print_table" cellpadding="6" cellspacing="0" width="100%">
			<tr style="font-size:8px;">
				<th width="19%" align="center">Total</th>
				<th width="8%" align="right" id="val_total11">Cpd. Qty Used<sup>Kg</sup></th>
				<th width="10%" align="right" id="val_total21">Cpd. Cost <sup>Rs</sup></th>
				<th width="8%" align="right" id="val_total31">Lifts<sup>No</sup></th>
				<th width="10%" align="right" id="val_total41">Mold. Cost <sup>Rs</sup></th>
				<th width="10%" align="right" id="val_total51">Trim. Cost <sup>Rs</sup></th>
				<th width="10%" align="right" id="val_total61">Insp. Cost <sup>Rs</sup></th>
				<th width="10%" align="right" id="val_total71">Sales Val. <sup>Rs</sup></th>
				<th width="10%" align="right" id="val_total81">Contribution <sup>Rs</sup></th>	
				<th align="right" id="val_total91">Contrib. % </th>
			</tr>				
		</table>		
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