<style>
.invoice_heading{border-bottom:1px solid #999;padding:5px 5px 5px 15px;margin:0px 0px 20px 0px;font-weight:bold;}
.supplier_list{overflow:auto;}
#content_body{margin-left:7px;margin-top:5px;}
</style>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Upload Compound DC/Invoice</strong>
    </div>
	<form id="formFileUpload" enctype="multipart/form-data" method="POST" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width='45%'>Choose a file to upload :</th>
            <th align="left" width='30%'>
				<input id="file" name="file" type="file" accept=".csv" style="width:90%" tabindex="1" /> 
			</th>
            <th align="left" ><input id="sch_submit" type="submit" value="Upload Compound DC/Invoice" /></th>
		</tr>				
    </table>
	
	</form>
	<br/>
    <div id="window_list_head">
        <strong>Return Compound Receipt</strong>
		<span id="button_add_blend" style="float:right;margin-top:2px;">Add Blend</span>
    </div>
    <form action="" onsubmit="return false;">
        <div id="window_list">
            <div id="content_body">
                <table border="0" cellspacing="0" cellpadding="3" class="new_form_table" >
                    <tr>
                        <td width="50%" align="right">
                            <b>Return Date</b>
                        </td>
                        <td  height="22px">
                            <input type="date" name="new_InvDate" id="new_InvDate" style="width:20%" tabindex="2" onfocus="FieldHiddenValue(this, 'in', 'DD/MM/YYYY')" onblur="FieldHiddenValue(this, 'out', 'DD/MM/YYYY')" />
                        </td>
                    </tr>
                </table>
                <br />
                <div class="supplier_list_head" style="margin-right:5px;">
                    <table border="0" cellpadding="5" cellspacing="0">
                        <tr>
                            <th width="15%"  align="center">No</th>
							<th width="60%" align="center">Cpd. Desc</th>
                             <th align="center">Rec. Qty</th>
                        </tr>
                    </table>
                    <div class="supplier_list" id="new_Particulars" style="height:auto">
                        <table border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <th width="15%"  align="center">&nbsp;</th>
                                <th width="60%" align="left">&nbsp;</th>
                                <th align="right">&nbsp;</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <br />
                <table border="0" cellpadding="5" cellspacing="0" class="new_form_table" style="padding-right:6px;">
                    <tr>
                        <th align="right" style="padding-right:6%;font-size:12px;">
                            Total
                        </th>
                        <th id="total_field" style="font-family:arial;font-size:18px;width:25%;text-align:right;border-top:2px double #ccc;border-bottom:2px solid #ccc;">
                            0.000
                        </th>
                    </tr>
                </table>
                <br /><br />
                <br /><br />
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="7" width="100%">
            <tr>
                <td id="error_msg" style="padding:7px;">&nbsp;
                </td>
                <td width="20%" align="right">
                    <button id="button_add" type="submit">Add</button>
                    <button id="button_cancel">Clear</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<div style="display:none">
	<div id="confirm_dialog"></div>
    <div id="issue_dialog" ></div>		
    <div id="receive_dialog">
		<table border="0" cellspacing="0" cellpadding="6" class="new_form_table">	
			<tr>
				<th align="right" width="15%">
					Select Blend:
				</th>
				<th align="left" width="55%">
					<input type="text"  tabindex="1" style="width:60%" id="blend_list" onchange="removeAllList();" />
				</th>		
				<th align="right" width="10%">
					Date:
				</th>
				<th align="left" >
					<input type="date"  tabindex="1" id="blend_date" style="width:95%" value="<?php echo date("Y-m-d"); ?>" />
				</th>		
			</tr>	
			<tr>
                <td colspan="4">
					<div id="error_blend_msg" style="padding:7px;"></div>
				</td>                
            </tr>			
		</table>	
		<div class="supplier_list_head" >
			<table border="0" cellpadding="5" cellspacing="0" width="100%" >
				<tr>
					<th width="10%" align="Left">S.No</th>
					<th width="35%" align="center">Compound Name</th>
					<th width="15%" align="center">Polymer</th>
					<th width="35%" align="center" >Required Qty</th>
					<th align="right"><span id="new_CPDButton">Add</span></th>
				</tr>
			</table>
		</div>
		<div class="supplier_list" id="new_ItemList" style="margin-right:20px;">
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<th width="10%" align="left">&nbsp;</th>
					<th width="35%" align="left">&nbsp;</th>
					<th width="15%" align="right">&nbsp;</th>
					<th align="center">&nbsp;</th>										
				</tr>
			</table>
		</div>
		<br />
		<div style="margin-right:50px;">
		<table border="0" cellpadding="5" cellspacing="0" class="new_form_table" style="padding-right:6px;" >
			<tr>
				<th align="right" style="padding-right:6%;font-size:12px;">
					Total
				</th>
				<th id="total_issue" style="font-family:arial;font-size:18px;width:25%;text-align:right;border-top:2px double #ccc;border-bottom:2px solid #ccc;">
					0.000
				</th>
			</tr>
		</table>
		</div>
	</div>
</div>
