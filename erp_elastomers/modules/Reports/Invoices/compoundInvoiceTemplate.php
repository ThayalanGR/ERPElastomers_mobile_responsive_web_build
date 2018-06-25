<?php
	$invoice_id				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);	
	$dcItemType				=	(ISO_IS_REWRITE)?trim($_VAR['invType']):trim($_GET['invType']);	
	
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php print $invoice_id; ?></title>
			<link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
		</head>
		<body>	
	
	<?php
	$dcUOM			=	"Kgs";
	$isCmpd			=	false;
	if ($dcItemType == 'cmpd')
	{
		$dcUOM		=	"Nos";
		$isCmpd		=	true;
	}

	$sql_particulars	=	"select *, CONCAT( SUBSTRING_INDEX( invPlanRef,  '_', 1 ) ,'-', SUBSTRING_INDEX( invPlanRef,  '-' , -1 ) ) AS planId,(select DATE_FORMAT(batFinalDate,'%d-%b-%Y')as batDate from tbl_mixing where batId=tisi.invPlanRef)as batdate from tbl_invoice_sales_items tisi where tisi.invId='$invoice_id'";
	$out_particulars	=	@getMySQLData($sql_particulars);
	$particulars		=	$out_particulars['data'];
	$noofBatches		=	count($particulars);

?>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
         	<tr>
            	<td colspan="2" align="center" style="padding:3px" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="100px" />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" >
                	<div style="font-size:18px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
					<?php echo @getCompanyDetails('address'); ?><br/>
					Ph: <?php echo @getCompanyDetails('phone'); ?>, email: <?php echo @getCompanyDetails('email'); ?>,<br/>
                    website : <?php echo @getCompanyDetails('website'); ?><br/>
					CIN : <?php echo @getCompanyDetails('cin'); ?>
                </td>
                <td colspan="1" width="100px" class="content_center content_bold uppercase" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/iso_logo.jpg" width="100px" />
                </td>
            </tr>
            <tr>
            	<td colspan="7" class="content_center content_bold">
                	Annexure for Invoice Id:<?php print $invoice_id;?>
                </td>
            </tr>
            <tr>
            	<th width="3%">
                	No
                </th>
            	<th width="12%" style="border-right:0px;">
                	&nbsp;
                </th>
            	<th width="15%" align='left'>
                	Item Name
                </th>
				<th width="15%">
					HSN Code
                </th>
            	<th width="25%">
                	Description
                </th>
            	<th width="15%">
                	Ref. ID
                </th>
            	<th width="15%">
                	Qty (<?php print $dcUOM ?>)
                </th>
             </tr>
            <?php
				$totsno		=	40;
				$pgBrk		=	45;
				if ($noofBatches > $totsno)
				{
					$totsno = $noofBatches;
				}								
				$sno		=	1;
				$tqty		=	0;
				for($p=0;$p<$totsno;$p++){
					$invQty	=	$particulars[$p]['invQty'];
					$tqty	=	$tqty + $invQty;
					if($p % $pgBrk === 0 && $p > 0 )
					{ ?>		
						<tr>
							<td colspan="7" class="content_right content_bold" >
								P.T.O
							</td>
						</tr>					
						</table>
						<div class="page_break" />
						<br />
						<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
						<tr>
							<td colspan="7" class="content_left content_bold" >
								Cont ....
							</td>
						</tr>							
						<tr>
							<th width="3%">
								No
							</th>
							<th width="12%" style="border-right:0px;">
								&nbsp;
							</th>
							<th width="15%" align='left'>
								Item Name
							</th>
							<th width="15%">
								HSN Code
							</th>
							<th width="25%">
								Description
							</th>
							<th width="15%">
								Ref. ID
							</th>
							<th width="15%">
								Qty (<?php print $dcUOM ?>)
							</th>			
						 </tr>							
					<?php	
						}
					?>	
                    <tr>
                        <td align="center">
                            <?php print ($p+1); ?>
                        </td>
						<td colspan="2">
                            <?php print ($particulars[$p]['invName'])?$particulars[$p]['invName']:'&nbsp;'; ?>
                        </td>
						<td>
                            <?php 
							if( $p < $noofBatches )
							{
								if ($HSN[$dcItemType])								
									print $HSN[$dcItemType];
								else if ($dcItemType == 'ram')
								{
									$ramData	=	@getMySQLData("select invHSNCode from tbl_invoice_grn where grnId = '".$particulars[$p]['invPlanRef']."'");									
									print $ramData['data'][0]['invHSNCode'];
								}
							}							
							?>
							&nbsp;
                        </td>						
                        <td>
                            <?php print ($particulars[$p]['invDesc'])?$particulars[$p]['invDesc']:'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php							
							if($isCmpd)
							{
								print $particulars[$p]['planId'];
							}							
							else
							{
								$partBatId	=	"";
								if($particulars[$p]['batdate'] != "" && $particulars[$p]['batdate'] != "0000-00-00") {
									$mixDate			= date("d-m-Y", strtotime($particulars[$p]['batdate']));
									list($d, $m, $y) 	= explode('-', $mixDate);
									$partBatId			=	"/".$d . $cpdMonthCode[$m+0];	
								}
								$itemId		=	$particulars[$p]['invPlanRef'];
								print ($itemId)?(strpos($itemId,'_')!== false)?substr(strrchr($itemId, "_"),1).$partBatId:$itemId.$partBatId :'&nbsp;';
							}
							?>
                        </td>
                        <td class="content_right">
                            <?php print ($invQty)?($isCmpd)?@number_format($invQty, 0):@number_format($invQty, 3):'&nbsp;'; ?>
                        </td>
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="6" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_right">
                	<?php print ($isCmpd)?@number_format($tqty, 0):@number_format($tqty, 3); ?>
                </td>
           </tr>
        </table>
    </body>
</html>