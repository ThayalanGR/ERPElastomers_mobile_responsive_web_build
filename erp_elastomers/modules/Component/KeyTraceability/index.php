<div id="window_list_wrapper" style="overflow-x:auto; padding-top:65px;">
    <div id="window_list_head">
        <strong>Key Traceability Report</strong>
    </div>
	<script type="text/javascript">
		function openReport(){
			openInvoice({invID:$("#keyRef").val()});
		}	
	</script>
	<form action="" onsubmit="return false;">
    <table width="95%" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px;margin-top:5px;">
        <tr>
            <th align="right" width='50%'>Enter Key Reference (Format Annnn_yy-x/y)</th>
            <th align="left" >
				<input name="keyRef" id="keyRef" tabindex="1" >
				<input  type="button" value="Go" tabindex="2" onclick="openReport();" />
			</th>
		</tr>
    </table>
	</form>

</div>
	



