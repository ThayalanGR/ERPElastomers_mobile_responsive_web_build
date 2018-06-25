<?php 
	global $despValidDays;
	$sql_list	=	"select porate,di_desc,tc.cusId,substring(cusname,1,5) as cusname,partnum,partdesc,di_qty,sum(invqty) as invqty,group_concat(ifnull(dispinvid,'-')) as dispinvid, cmpdAwtMldRec, cmpdAwtDefIss, cmpdAwtDefRec ,cmpdAwtQualRec, cmpdTotStock, cmpdStdPckQty, tccpr.cmpdid
						from (select dispinvid, ifnull(cmpdid,invcode) as cmpdid,ifnull(cusid,invcusid) as cusid,partnum,partdesc,invqty,ifnull(qty - despQty,0) as di_qty,di_desc from (select ici.invName as partnum, sum(ici.invqty) as invqty, ici.invDesc as partdesc, ici.invRate, invcode,invcusid,invdate,tic.invid as dispinvid
										from tbl_invoice_sales tic 										
											inner join tbl_invoice_sales_items ici on tic.invId=ici.invId  
										where tic.invdate = CURDATE() and tic.status > 0 and ici.invtype = 'cmpd'
										group by tic.invId,ici.invcode) tblA
									left outer join 
									(select cusid,cmpdid,group_concat(if(di_desc != '',di_desc,'-')) as di_desc,sum(qty) as qty , sum(despQty) as despQty
											from (select tcd.cusid,tcd.cmpdid,di_desc,qty, ifnull(sum(invQty),0) as despQty  from tbl_component_di tcd
													left join ( select invDespId, invQty, invCusId, invCode
																	from tbl_invoice_sales_items tici 
																		inner join tbl_invoice_sales  tic on tici.invId = tic.invId and tic.status = 1 and tic.invDate > DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day) and tic.invDate != CURDATE()) tbli on di_desc = invDespId  and cusId = invCusId and cmpdId = invCode 
													where tcd.status  = 1 and di_date > DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day) group by tcd.cusid,tcd.cmpdid,tcd.di_desc)tt1 
										where (qty - despQty) > 0	group by cusid,cmpdid) tcd0 on cmpdid = tblA.invcode and cusid = tblA.invcusid 
								UNION ALL
									select dispinvid, ifnull(tcomp.cmpdid,invcode) as cmpdid,ifnull(cusid,invcusid) as cusid,cmpdName as partnum,cmpdRefNo as partdesc,ifnull(invqty,0) as invqty,ifnull(qty - despQty,0) as di_qty,di_desc from (select tic.invid as dispinvid,ici.invName as partnum, sum(ici.invqty) as invqty, ici.invDesc as partdesc, ici.invRate, invcode,invcusid,invdate
										from tbl_invoice_sales tic 										
											inner join tbl_invoice_sales_items ici on tic.invId=ici.invId  
										where tic.invdate = CURDATE() and tic.status > 0 and ici.invtype = 'cmpd'
										group by tic.invId,ici.invcode) tblB
									right outer join (select cusid,cmpdid,group_concat(if(di_desc != '',di_desc,'-')) as di_desc,sum(qty) as qty , sum(despQty) as despQty
											from (select tcd.cusid,tcd.cmpdid,di_desc,qty, ifnull(sum(invQty),0) as despQty  from tbl_component_di tcd
													left join ( select invDespId, invQty, invCusId, invCode
																	from tbl_invoice_sales_items tici 
																		inner join tbl_invoice_sales  tic on tici.invId = tic.invId and tic.status = 1 and tic.invDate > DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day) and tic.invDate != CURDATE()) tbli on di_desc = invDespId  and cusId = invCusId and cmpdId = invCode 
													where tcd.status  = 1 and di_date > DATE_ADD(CURDATE(), INTERVAL -".$despValidDays." day) group by tcd.cusid,tcd.cmpdid,tcd.di_desc)tt1 
										where (qty - despQty) > 0	group by cusid,cmpdid) tcd1 on tcd1.cmpdid = tblB.invcode and tcd1.cusid = tblB.invcusid 
									inner join tbl_component tcomp on tcd1.cmpdid = tcomp.cmpdid
									Where tblB.invcode is NULL and tblB.invcusid is NULL and tblB.invdate is NULL) table1
						inner join tbl_customer tc on table1.cusid = tc.cusId 
						inner join (select * from (select * from tbl_customer_cmpd_po_rate order by update_on desc)tabl1 group by cusid,cmpdid) tccpr on table1.cusid = tccpr.cusId and table1.cmpdid = tccpr.cmpdid
						left join (select cmpdid, sum(sumIssued) as cmpdAwtMldRec, sum(sumMoldQty) as cmpdAwtDefIss, sum(sumDefIssued) as cmpdAwtDefRec ,sum(sumAwtQual) as cmpdAwtQualRec, sum(stockinHand) as cmpdTotStock  
												from ( 
													select cmpdid, quantity as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_moulding_receive tmr inner join tbl_invoice_mould_plan timp on tmr.planref = timp.planid where tmr.status = 2 group by tmr.planref
													UNION ALL
													select cmpdid, 0 as sumIssued, sum(mouldQty) as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_moulding_receive tmr inner join tbl_invoice_mould_plan timp on tmr.planref = timp.planid where tmr.status = 3 group by tmr.planref
													UNION ALL 
													select cmpdid, 0 as sumIssued, quantity as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_rework where status = 1 
													UNION ALL 
													select cmpdid, 0 as sumIssued, 0 as sumMoldQty, (issqty - recvQty) as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from (select di.cmpdid, di.issqty, if(sum(currrec) > 0,sum(currrec),0) as recvQty from tbl_deflash_issue di left outer join tbl_deflash_reciept dr on dr.defissref=di.sno where di.status = 1 group by dr.defissref) tdi
													UNION ALL 
													select cmpdid,0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,(currrec - ifnull(mq.receiptqty,0)) as sumAwtQual, 0 as stockinHand from tbl_deflash_reciept dr inner join tbl_deflash_issue di on di.sno = dr.defissref left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 0 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = dr.sno where dr.status = 1
													UNION ALL 
													select cmpdid,0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,(recvqty - ifnull(mq.receiptqty,0)) as sumAwtQual, 0 as stockinHand from tbl_component_recv cr left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 1 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = cr.sno where cr.status = 1																	
													UNION ALL 
													select cmpdid, 0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, avlQty as stockinHand from tbl_mould_store where status = 1 and avlQty > 0 
												)table1
												group by cmpdid) tcs  on tcs.cmpdid = tccpr.cmpdid
						inner join tbl_component tcmpd on tcmpd.cmpdid = tccpr.cmpdid
					group by table1.cmpdid,table1.cusid
					order by tc.cusname,table1.partnum";
	$out_list	=	@getMySQLData($sql_list);
	$status		=	$out_list['status'];
	$list		=	$out_list['data'];
?>


 <div class="row justify-content-center text-primary" style="padding-top: 65px;" >
    <div class="col-12 text-center h6"><i class="<?php echo $_SESSION['Inspection Entry']; ?>"></i> Inspection Entry</div>
    <div class="col-12 text-center ">Today's Plan</div>
    <div style="padding: 5px 7px 7px .7em;margin-bottom:10px;font-size:11px;display:none" id="raise_error"></div>
    <div class="col-12 text-center">
	<div id="content_body">
		<div style="overflow-x:auto;">
			<table id="resultTable" class="table table-sm table-light text-success " border="0" cellpadding="6" cellspacing="0" >
				<thead>
				<tr>
					<th align="left">S.No</th>
					<th align="left">Customer Name</th>    
					<th align="left" >Part Number</th> 
					<th align="left" >Part Desc.</th>
					<th align="left"  >Di. Ref.</th>					
					<th align="right"> Di. Qty</th>
					<th align="right"> Pend. Qty</th>
					<th align="right"> Store Qty</th>
					<th align="right"> Pend. Insp. Qty</th>
					<th align="right">Await. Trim. Recv. Qty</th>
					<th align="right">Await. Trim. Iss. Qty</th>
					<th align="right">To Be Inspected Qty</th>
				</tr>
				</thead>
				<tbody>
				<?php if(count($list) > 0): ?>
					<?php
						$cmpdtoinspect_tot	=	0;
						for($i=0; $i<count($list); $i++):
							$cmpdid = $list[$i]['cmpdid'];
							$cusname = $list[$i]['cusname'];
							$cmpdname = $list[$i]['partnum'];
							$cmpdpartdesc = $list[$i]['partdesc']; 
							$didesc = $list[$i]['di_desc'];
							$diqty = $list[$i]['di_qty'];
							$pendqty = $list[$i]['di_qty'] - $list[$i]['invqty'];
							$storeqty = $list[$i]['cmpdTotStock']; 
							$cmpdawtqualrec = $list[$i]['cmpdAwtQualRec'];
							$cmpdawtdefrec = $list[$i]['cmpdAwtDefRec'];
							$cmpdawtdefiss = $list[$i]['cmpdAwtDefIss'];						
							$cmpdtoinspect = (($pendqty - $storeqty) > 0)?($pendqty - $storeqty):0;	
							$cmpdtoinspect_tot	+= $cmpdtoinspect;
					?>
					<tr>
						<td><?php echo $i+1 ?></td>
						<td><?php echo $cusname; ?></td>
						<td><?php echo $cmpdname; ?></td>
						<td><?php echo $cmpdpartdesc; ?></td>
						<td><?php echo $didesc; ?></td>	
						<td align='right'><?php echo @number_format($diqty,0); ?></td>
						<td align='right'><a href="#" onclick="getStock('<?php echo $cmpdid; ?>','<?php echo $pendqty; ?>');"/><?php echo @number_format($pendqty,0); ?></a></td>
						<td align="right"><?php echo @number_format($storeqty,0); ?></td>
						<td align="right"><?php echo @number_format($cmpdawtqualrec,0); ?></td>					
						<td align="right"><?php echo @number_format($cmpdawtdefrec,0); ?></td>
						<td align="right"><?php echo @number_format($cmpdawtdefiss,0); ?></td>
						<td align="right"><?php echo @number_format($cmpdtoinspect,0); ?></td>
					</tr>
					<?php endfor; ?>
				<?php elseif($status != "success"): ?>
					<div class="window_error"><div class="warning_txt text-danger"><span>Error Fetching Data . . . Err No: <?php echo $status; ?></span></div></div>
				<?php else: ?>
					<div class="window_error"><div class="warning_txt text-danger"><span>No Data Available . . .</span></div></div>
				<?php endif; ?>
				</tbody>
				<tr><td colspan="11" align="center"><b>Total</b></td><td align='right'><?php echo $cmpdtoinspect_tot; ?></td></tr>
			</table>
			<table></table>
		</div>
		</div>
    </div>
</div>
<div style="display:none">
		<div id="stock_dialog"></div>
<<<<<<< HEAD
</div>
=======
	</div>
>>>>>>> 6f41397474f73f0cd7d747b51cbbe2dd88756647

<script>
	tableFilters.sort_config		=	{ sort_types:['us','string','string','string', 'string','us','us','us','us','us','us','us'] };
	TF_01							=	setFilterGrid("resultTable",tableFilters);
	function getStock(cmpdId,penQty)
	{	
		param			=	"selecttype=" + "GETCMPDSTOCK&cmpdId=" + cmpdId ;
		var XMLContent	=	postback("/modules/Commercial/DespatchPlan/_bin/.action",param,"POST","XML");
		xmlData			=	parseXMLdoc(XMLContent);
		confirmCont 	=	'<table width="100%" border="1" >' +
								'<tr>' +
									'<th>Plan Id</th>' +
									'<th>Operator</th>' + 
									'<th>Qty</th>' +
								'</tr>' +
								'<tr>' +
									'<td colspan="2" align="center"> Total Pending Qty</td>' +
									'<td align="right"><b>'+ penQty +'</b></td>' +
								'</tr>' ;									
		currDesc		=	"";
	totQty			=	0;
	grdTot			=	0;
	$(xmlData).find("row").each(function(index, element) {
		avlQty	=	element.childNodes[2].firstChild.nodeValue;
		grdTot	+=	avlQty.toNumber();	
		if(currDesc != "" && currDesc != element.childNodes[3].firstChild.nodeValue)
		{
			confirmCont +=	'<tr>' +
								'<td colspan="2" align="center"> Total '+ currDesc + '</td>' +
								'<td align="right"><b>'+ totQty +'</b></td>' +
							'</tr>' ;				
			currDesc	=	element.childNodes[3].firstChild.nodeValue;
			totQty		=	0;
		}
		else if(currDesc == "")
		{
			currDesc = element.childNodes[3].firstChild.nodeValue;
		}
		totQty	+= avlQty.toNumber();
		
		confirmCont +='<tr>' +
						'<td>'+ element.childNodes[0].firstChild.nodeValue + '</td>' +
						'<td>'+ element.childNodes[1].firstChild.nodeValue + '</td>' +
						'<td align="right"><b>'+ avlQty +'</b></td>' +
					 '</tr>' ;				
	});
	confirmCont 	+=	'<tr>' +
							'<td colspan="2" align="center"> Total '+ currDesc + '</td>' +
							'<td align="right"><b>'+ totQty +'</b></td>' +
						'</tr>' +
						'<tr>' +
							'<td colspan="2" align="center"> Shortage Qty</td>' +
							'<td align="right"><b>'+ (penQty - grdTot) +'</b></td>' +
						'</tr>' +
						'</table>' ;		
	$("#stock_dialog").html(confirmCont).dialog({
											title:'Component Stock',
											width:450,
											height:'auto',
											resizable:false,
											modal:true,
											buttons: [
												{
													text: "Ok",
													click: function(){
														$(this).dialog("close");		
													}
												}
											],
											open: function() {
												jQuery('.ui-widget-overlay').bind('click', function() {
													jQuery('#stock_dialog').dialog('close');
												})
											},
											close: function(event, ui) {
													$(this).dialog("destroy");
												} 
											});

	}
</script>