<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>RFQ List</strong>
        <span id="button_add">New</span>
    </div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
</div>
<div style="display:none">
    <div id="new_item_form" class="window" title="New Request" style="visibility:hidden;">
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none;" id="error_msg"></div>
		<form action="" onsubmit="return false;">
			<table border="0" cellspacing="0" cellpadding="3" class="new_form_table">
				<tr>
					<td width='20%'>
						Request Type
					</td>
					<td width='30%'>
						<input type="radio" name='req_type_opt' tabindex="1" class="type_option" id="type_new" value="1" checked="checked"> <label for="type_new">New</label> 
						<input type="radio" name='req_type_opt' tabindex="1" class="type_option" id="type_exist" value="0"> <label for="type_exist">Existing</label>
					</td>
					<td width='20%'>
						Comp ID (if Existing)
					</td>
					<td>
						<span class="comp_exist">
							<input type="text" id="cmpdid_val" name="cmpdid_val" style="width:60%;" tabindex="3" onblur="getComponentDetails()"/>
						</span>
					</td>
				</tr>			
				<tr>
					<td width='20%'>
						Customer Name
					</td>
					<td width='30%'>
						<input type="text" id="new_CustID" name="new_CustID" style="width:75%;" tabindex="4" onblur="getCustomerDetails()" />
					</td>
					<td width='20%'>
						Target Price 
					</td>
					<td>
						<input type="text" id="new_TargetPrice" name="new_TargetPrice" style="width:20%;text-align:right;" tabindex="9" value=0.0 />                          
					</td>
				</tr>
				<tr>
					<td valign="top">
						Customer Address
					</td>
					<td>
						<textarea id="new_CustAddress" name="new_CustAddress" style="width:75%;height:75px;" readonly></textarea>
					</td>
					<td valign="top">
						Compound Specification
					</td>
					<td>
						<textarea id="new_CompoundSpec" name="new_CompoundSpec" style="width:75%;height:75px;" tabindex="10"></textarea>
					</td>
				</tr>
				<tr>
					<td>
						Part Number
					</td>
					<td>
						<input type="text" id="new_PartNum" name="new_PartNum" style="width:40%;" tabindex="5" />
					</td>
					<td>
						Application
					</td>
					<td>
						<input type="text" id="new_App" name="new_App" style="width:80%;" tabindex="11" />
					</td>
				</tr>
				<tr>
					<td>
						Part Description 
					</td>
					<td>
						<input type="text" id="new_PartDesc" name="new_PartDesc" style="width:80%;" tabindex="6"  />                          
					</td>
					<td>
						End Customer                             
					</td>
					<td>
						<input type="text" id="new_EndCust" name="new_EndCust" style="width:80%;" tabindex="12" />							
					</td>						
				</tr>
				<tr>
					<td>
						Average Monthly Requirement 
					</td>
					<td>
						<input type="text" id="new_AMR" name="new_AMR" style="width:30%;text-align:right;" tabindex="7" value=0 />                          
					</td>
					 <td>
						RFQ Last Date
					</td>
					<td>
						<input type="date" name="new_TargetDate" id="new_TargetDate" tabindex="13" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"))); ?>"  />
					</td>			
				</tr>	
				<tr>
					<td>
						Product Drawing
					</td>
					<td>
						<input id="new_ProdDrawing" name="new_ProdDrawing" type="file" accept=".pdf" style="width:80%" tabindex="8" /><br/><br/>
						Revision: <input id="new_ProdDrawingRev" name="new_ProdDrawingRev" type="text" style="width:30%" tabindex="8" /> Date: <input type="date" name="new_ProdDrawingDate" id="new_ProdDrawingDate" tabindex="8" style="width:30%" />
					</td>
					<td>
						Remarks                             
					</td>
					<td>
						<textarea style="width:75%;height:75px;" id="new_Remarks" tabindex="14"></textarea>							
					</td>						
				</tr>							
			</table>
		</form>
	</div>
    <div id="update_remarks_form" title="Update Remarks" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="update_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr>
                    <th style="text-align:left;width:20%;vertical-align:top;padding-top:8px;" valign="top">Remarks</th>
                    <td>
                        <textarea id="update_remarks" style="width:90%;height:80px;max-height:80px;" tabindex="1"></textarea>
                    </td>
                </tr>
			</table>	
        </form>
    </div>
    <div id="abandon_rfq_form" title="Abandon RFQ" >
        <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="abandon_error"></div>
        <form action="" onsubmit="return false;">
            <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr>
                    <th style="text-align:left;width:20%;vertical-align:top;padding-top:8px;" valign="top">Remarks</th>
                    <td>
                        <textarea id="abandon_remarks" style="width:90%;height:80px;max-height:80px;" tabindex="1"></textarea>
                    </td>
                </tr>
			</table>	
        </form>
    </div>		
	<div id="confirm_dialog">
	<div id="print_item_form" title="New Request List" style="visibility:hidden">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="print_table_top">
			<tr>
				<td width="15%" rowspan="2" align="center" style="padding:10px; border-top:0px; border-bottom:0px;" >
					<img id="imgpath" width="70px" />
				</td>
				<td width="70%" align="center" height="45px">
					<div style="font-size:20px;"><b><?php  echo $_SESSION['app']['comp_name'];?></b></div>
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
