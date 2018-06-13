<div id="window_list_wrapper" style="padding-bottom:5px;">
    <div id="window_list_head">
        <strong>Layout Inspection</strong>
    </div>
     <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>

<div style="display:none">
    <div id="layout_insp_receipt" title="Sample Layout Inspection" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:8px;display:none" id="raise_error"></div>
        <form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
				<tr>
					<td align="left" width='15%'>Part Number :</td>
					<th align="left" width='20%' id="partnum"></th>
					<td align="left" width='15%'>Description :</td>
					<th align="left" width='20%' id="partdesc"></th>					
					<th align="right" ><span id="new_add_param">Add Dimension</span></th>
				</tr>				
			</table>
				
			<div class="supplier_list_head" style="margin-left:5px;margin-right:5px;">
				<table border="0" cellpadding="5" cellspacing="0">
					<tr>
						<th width="25%" align="left">Dimension</th>
						<th width="10%" align="left">Units</th>
						<th width="20%" align="left">Equipment</th>
						<th width="10%" align="left">Spec</th>
						<th width="10%" align="left">Min.</th>
						<th width="10%" align="left">Max.</th>
						<th width="10%" align="center">#</th>
						<th class="last1" align="center">&nbsp;</th>
					</tr>
				</table>
				<div class="supplier_list" id="ParameterList">
					<table border="0" cellpadding="5" cellspacing="0">
						<tr>
							<th width="25%" align="left">&nbsp;</th>
							<th width="10%" align="left">&nbsp;</th>
							<th width="20%" align="left">&nbsp;</th>
							<th width="10%" align="left">&nbsp;</th>
							<th width="10%" align="left">&nbsp;</th>
							<th width="10%" align="left">&nbsp;</th>
							<th width="10%" align="left">&nbsp;</th>
							<th align="center">&nbsp;</th>
						</tr>
					</table>
				</div>
			</div>
        </form>
    </div>
</div>
<div style="display:none"> 
	<div id="confirm_dialog"></div>
</div>
