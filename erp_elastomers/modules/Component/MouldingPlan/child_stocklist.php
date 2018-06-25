<?php 

	$sql_list	=	"select *, (schqty - despatchqty - cmpdawtplan) as awtPlan from 
											(select tc.cmpdId,tc.cmpdRefNo as cmpdpartdesc,tc.cmpdname,IFNULL(cmpdawtcpdiss,0) as cmpdawtcpdiss,IFNULL(cmpdawtmldrec,0) as cmpdawtmldrec,IFNULL(cmpdawtdefiss,0) as cmpdawtdefiss,IFNULL(cmpdawtdefrec,0) as cmpdawtdefrec,IFNULL(cmpdawtqualrec,0) as cmpdawtqualrec,cmpdcpdname,cmpdblankwgt,
												IFNULL((select schQty from tbl_scheduling where status>0 and schOrder = 'component' and schMonth = DATE_FORMAT(CURRENT_DATE,'%M %Y') and cpdId_cmpdId = tcs.cmpdId  order by entry_on desc limit 1),0) as schQty, 
												IFNULL((select poRate from tbl_customer_cmpd_po_rate where status>0 and cmpdId = tcs.cmpdId  order by update_on desc limit 1),0) as cmpdRate ,IFNULL(cmpdtotstock,0) as cmpdStock, 
												(IFNULL(cmpdawtcpdiss,0)+IFNULL(cmpdawtmldrec,0)+IFNULL(cmpdawtdefiss,0)+IFNULL(cmpdawtdefrec,0)+IFNULL(cmpdawtqualrec,0)+IFNULL(cmpdtotstock,0)) as cmpdAwtPlan, 
												IFNULL((select sum(invQty) from tbl_invoice_sales_items tici left join tbl_invoice_sales tic on tic.invId = tici.invId where status>0 and DATE_FORMAT(invDate,'%m-%Y') = DATE_FORMAT(CURRENT_DATE,'%m-%Y') and tici.invCode = tcs.cmpdId and tici.invtype = 'cmpd' group by tici.invCode),0) as despatchQty,
												ifnull(cpdStockQty,0) as cpdStock
												from(
							select cmpdid, sum(sumPlanned) as cmpdAwtCpdIss,sum(sumIssued) as cmpdAwtMldRec, sum(sumMoldQty) as cmpdAwtDefIss, sum(sumDefIssued) as cmpdAwtDefRec ,sum(sumAwtQual) as cmpdAwtQualRec, sum(stockinHand) as cmpdTotStock  from 
							( 	
							select cmpdid, quantity as sumPlanned, 0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_moulding_receive tmr inner join tbl_invoice_mould_plan timp on tmr.planref = timp.planid	where tmr.status = 1 group by tmr.planref
							UNION ALL
							select cmpdid, 0 as sumPlanned, sum(plannedLifts * no_of_active_cavities) as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_moulding_receive tmr inner join tbl_invoice_mould_plan timp on tmr.planref = timp.planid where tmr.status = 2 group by tmr.planref
							UNION ALL
							select cmpdid, 0 as sumPlanned,0 as sumIssued,sum(mouldQty) as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_moulding_receive tmr inner join tbl_invoice_mould_plan timp on tmr.planref = timp.planid where tmr.status = 3 group by tmr.planref
							UNION ALL 
							select cmpdid,0 as sumPlanned,0 as sumIssued, quantity as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from tbl_rework where status = 1 
							UNION ALL 
							select cmpdid,0 as sumPlanned,0 as sumIssued, 0 as sumMoldQty, (issqty - recvQty) as sumDefIssued,0 as sumAwtQual, 0 as stockinHand from (select di.cmpdid, di.issqty, if(sum(currrec) > 0,sum(currrec),0) as recvQty from tbl_deflash_issue di left outer join tbl_deflash_reciept dr on dr.defissref=di.sno where di.status = 1 group by di.defissref) tdi
							UNION ALL 
							select cmpdid,0 as sumPlanned,0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,(currrec - ifnull(mq.receiptqty,0)) as sumAwtQual, 0 as stockinHand from tbl_deflash_reciept dr inner join tbl_deflash_issue di on di.sno = dr.defissref left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 0 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = dr.sno where dr.status = 1
							UNION ALL 
							select cmpdid,0 as sumPlanned,0 as sumIssued, 0 as sumMoldQty, 0 as sumDefIssued,(recvqty - ifnull(mq.receiptqty,0)) as sumAwtQual, 0 as stockinHand from tbl_component_recv cr left join ( select mdlrref, sum(receiptqty) as receiptqty from (select mdlrref, receiptqty from tbl_moulding_quality where status > 0 and isExternal = 1 group by qualityref)tmq group by mdlrref) mq on mq.mdlrref = cr.sno where cr.status = 1
							UNION ALL 
							select cmpdid, 0 as sumPlanned, 0 as sumIssued,0 as sumMoldQty, 0 as sumDefIssued,0 as sumAwtQual, avlQty as stockinHand from tbl_mould_store where status = 1 and avlQty > 0 
							)table1
							group by cmpdid) tcs  
							right outer join tbl_component tc on tc.cmpdId=tcs.cmpdId 
							left outer join (select cpdId, ifnull(sum(recvQty - issuedQty),0) as cpdStockQty from tbl_component_cpd_recv where status = 1 and (recvQty - issuedQty) > 0 group by cpdId)tccr on tc.cmpdCpdId = tccr.cpdId										 
						where tc.status=1)	resTbl 
						order by ROUND(awtPlan * cmpdRate) desc";
	$out_list	=	@getMySQLData($sql_list);
	$status		=	$out_list['status'];
	$list		=	$out_list['data'];
?>
<div id="window_list_wrapper">
    <div id="window_list_head">
        <strong>Component Stock Level</strong>
    </div>
    <div id="window_list">
        <div id="content_body">
        <table id="resultTable" border="0" cellpadding="6" cellspacing="0" width="100%">
			<thead>
            <tr>
                <th width='10%' align="left">Part Number</th>
                <th width='10%' align="left">Part Desc.</th>
				<th width='9%' align="left">Cpd. Name</th>
				<th width='6.5%' align="left"  title="Compound Stock Qty">CpdStk<sup>Kgs</sup></th>
                <th width='6.5%' align="right" title="Scheduled Qty (Current Month)">Sch. Qty</th>
				<th width='6.5%' align="right" title="Despatched Qty (Current Month)">Des. Qty </th>
                <th width='6.5%' align="right" title="Awaiting Planning">Awt. Plan</th>
                <th width='6.5%' align="right" title="Awaiting Compound Issue">Awt. CpdIss</th>
                <th width='6.5%' align="right" title="Awaiting Moulding Receipt">Awt. MldRec</th>
                <th width='6.5%' align="right" title="Awaiting Deflashing Issue">Awt. DefIss</th>
                <th width='6.5%' align="right" title="Awaiting Deflashing Receipt">Awt. DefRec</th>
                <th width='6.5%' align="right" title="Awaiting Quality Receipt">Awt. QtyRec</th>
                <th width='6.5%' align="right">Stock</th>
            </tr>
			</thead>
			<tbody>
			<?php if(count($list) > 0): ?>
				<?php
					for($i=0; $i<count($list); $i++):
						$class = ($i%2==0)?'content_rows_light':'content_rows_dark';
						$cmpdname = $list[$i]['cmpdname'];
						$cmpdpartdesc = $list[$i]['cmpdpartdesc']; 
						$cmpdcpdname = $list[$i]['cmpdcpdname'];
						$cpdstock = $list[$i]['cpdStock'];
						$schqty = $list[$i]['schQty'];
						$despatchqty = $list[$i]['despatchQty'];
						$awtplan = $list[$i]['awtPlan']; 
						$cmpdawtcpdiss = $list[$i]['cmpdawtcpdiss'];
						$cmpdawtmldrec = $list[$i]['cmpdawtmldrec'];
						$cmpdawtdefiss = $list[$i]['cmpdawtdefiss'];
						$cmpdawtdefrec = $list[$i]['cmpdawtdefrec'];
						$cmpdawtqualrec = $list[$i]['cmpdawtqualrec'];
						$cmpdstock = $list[$i]['cmpdStock'];						
				?>
				<tr class="<?php echo $class?>">
					<td><?php echo $cmpdname; ?></td>
					<td><?php echo $cmpdpartdesc; ?></td>
					<td><?php echo $cmpdcpdname; ?></td>	
					<td align='right'><?php echo @number_format($cpdstock,3); ?></td>			
					<td align='right'><?php echo @number_format($schqty,0); ?></td>
					<td align='right'><?php echo @number_format($despatchqty,0); ?></td>
					<td align="right"><?php echo @number_format($awtplan,0); ?></td>		
					<td align="right"><?php echo @number_format($cmpdawtcpdiss,0); ?></td>		
					<td align="right"><?php echo @number_format($cmpdawtmldrec,0); ?></td>
					<td align="right"><?php echo @number_format($cmpdawtdefiss,0); ?></td>
					<td align="right"><?php echo @number_format($cmpdawtdefrec,0); ?></td>
					<td align="right"><?php echo @number_format($cmpdawtqualrec,0); ?></td>
					<td align="right"><?php echo @number_format($cmpdstock,0); ?></td>
				</tr>
				<?php endfor; ?>
			<?php elseif($status != "success"): ?>
				<div class="window_error"><div class="warning_txt"><span>Error Fetching Data . . . Err No: <?php echo $status; ?></span></div></div>
			<?php else: ?>
				<div class="window_error"><div class="warning_txt"><span>No Data Available . . .</span></div></div>
			<?php endif; ?>
			</tbody>			
        </table>		
		</div>
	</div>
</div>
<script>
tableFilters.col_13			= 	"none";
tableFilters.sort_config	=	{ sort_types:['string','string','string', 'us','us','us','us','us','us','us','us','us','us', 'None'] };
TF_01						=	setFilterGrid("resultTable",tableFilters);
</script>