<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Enter DC</strong>
    </div>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width='40%'>DC No.</th>
            <th align="left" >
				<input name="dcRef" id="dcRef" tabindex="1" autofocus="autofocus" onkeyup="waitAndCall();" />
			</th>
		</tr>	
    </table>
	</form>
	<br/>
    <div id="window_list_head">
        <strong>Enter DC Receipt Values</strong>
    </div>
	<div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="error_msg"></div>
    <div id="window_list">
    	<div class="window_error">
            <div class="loading_txt"><span>Loading Data . . .</span></div>
        </div>
        <div id="content_body"></div>
    </div>
     <div >
 	<form action="" onsubmit="return false;">
	
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <td align="right">
					<button tabindex='3' id="button_submit" type="submit">Update</button>
					<button tabindex='4' id="button_cancel">Clear</button>
			</td>
        </tr>		
    </table>
	</form>
     </div>
    <div id="update_dialog" >
    </div>		 
</div>
	



