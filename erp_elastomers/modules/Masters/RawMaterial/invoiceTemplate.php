<?php
	$invoice_id			=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$invIDs				=	"";
	$dispinvid			=	$invoice_id;
	if (strpos($invoice_id,',') !== false) {
		$invIDs			=	explode(",", $invoice_id);
	}
	else
	{
		$invIDs			=	array($invoice_id);	
	}
	// Get Form Details.
	$formArray		=	@getFormDetails(28);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $dispinvid ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>	
	
<?php 
	for($invCount=0;$invCount<count($invIDs);$invCount++)
	{
		$ramid		=	$invIDs[$invCount];
		$sql_ram	=	"select ramName,DATE_FORMAT(entry_on, '%d-%b-%Y') as entryOn,DATE_FORMAT(update_on, '%d-%b-%Y') as updateOn, ramGrade as ramGrade,class_name,	format(ramMinStock,3) as ramMinStock,format(ramShelfLife,0) as ramShelfLife,format(ramStdPacking,2) as ramStdPacking, ramManufacturer, ramChemName, ramComposition 
							from tbl_rawmaterial 
							inner join tbl_class on tbl_class.sno = tbl_rawmaterial.ramClass
						where ramid = '$ramid'";
		$out_ram	=	@getMySQLData($sql_ram);
		$data		=	$out_ram['data'][0];
	
?>

		<p align="right">Form No: <?php print $formArray[0]; ?>	</p>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" id="print_out_header">
			<tr >
				<td width="12%" align="center" >
					<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" width="70px" />
				</td>
				<td width="70%" class="content_bold cellpadding content_center" height="70px">
					<div style="font-size:16px;"><?php print $formArray[1]; ?> for</div> <div style="font-size:20px;"><?php echo $data['ramName']; ?>  - [ <?php echo ($data['ramGrade'])?$data['ramGrade']:"NA"; ?> ]</div>
				</td>
				<td class="content_left content_bold uppercase" valign="top" >
					<div style="font-size:12px;">Effective From: </div><div style="font-size:14px;" ><?php echo $data['entryOn'] ?></div>
					<div style="font-size:12px;" > </div>
				</td>				
			</tr>
		</table>
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
			<tr style="font-size:8px;" >
				<th width="5%">
					No
				</th>
				<th width="25%">
					Properties
				</th>
				<th width="10%">
					UOM
				</th>
				<th colspan ="2">
					Value
				</th>
				<th width="20%">
					Used In
				</th>
				<th>
					PHR
				</th>				
			</tr>
            <?php
				$sql_rmdetails		=	"SELECT cpdName, tcr.cpdId, (
											SELECT SUM( ramParts ) AS polyRamParts
												FROM (
													SELECT cpdid, ramParts, item_no
													FROM tbl_compound_rm t1
													INNER JOIN tbl_rawmaterial t2 ON t1.ramId = t2.ramId
													AND ramClass =1
													GROUP BY item_no, cpdid
													)tbl1
												WHERE tbl1.cpdid = tcr.cpdId
											) AS polyRamParts, ramParts
											FROM tbl_compound_rm tcr
											INNER JOIN tbl_compound tc ON tcr.cpdId = tc.cpdId and tc.status > 0
											INNER JOIN tbl_polymer_order tpo ON tpo.polyName = tc.cpdPolymer
										WHERE ramId =  '$ramid'
										ORDER BY dispOrder, cpdName";
				$out_rmdetails		=	@getMySQLData($sql_rmdetails);	
				$particulars		=	$out_rmdetails['data'];	
				$propArray			=	array("Name","Grade","Manufacturer","Chemical Name","Composition","Type","Shelf Life","Std. Packing Qty","Min. Stock");
				$uomArray			=	array("","","","","","","Years","Kgs","Kgs");
				$colArray			=	array("ramName","ramGrade","ramManufacturer","ramChemName","ramComposition","class_name","ramShelfLife","ramStdPacking","ramMinStock");
				$totsno				=	15;
				$sno				=	1;
				$tpartsqty			=	0;
				$tkneadqty			=	0;
				$pno				=	0;
				for($p=0;$p<$totsno;$p++){
					$polyRamParts	=	$particulars[$pno]['polyRamParts'];
					$ramPHR			=	($polyRamParts)?($particulars[$pno]['ramParts']/$polyRamParts) * 100:"";
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left" >
							<?php print ($propArray[$p])?$propArray[$p]:'&nbsp;';?>
                        </td>
                        <td align="left" >
							<?php print ($uomArray[$p])?$uomArray[$p]:'&nbsp;';?>
                        </td>						
                        <td align="<?php print ($uomArray[$p])?'right':'left';?>" colspan ="2">
							<?php print ($data[$colArray[$p]])?$data[$colArray[$p]]:'&nbsp;';?>
                        </td>
						<td align="left" >
							<?php print ($particulars[$pno]['cpdName'])?$particulars[$pno]['cpdName']:'&nbsp;';?>
						</td>
						<td align="right">
							<?php print ($ramPHR)?@number_format($ramPHR,2):'&nbsp;';?>
						</td>						
                    </tr>					
                    <?php
					$pno++;
				}
				$pno++; 
				$polyRamParts	=	$particulars[$pno]['polyRamParts'];
				$ramPHR			=	($polyRamParts)?($particulars[$pno]['ramParts']/$polyRamParts) * 100:"";;			
			?>
			<tr>
 				<td colspan="5" class="content_center content_bold" height="19px">
                	Verification Properties 
                </td>
				<td align="left">
					<?php print ($particulars[$pno]['cpdName'])?$particulars[$pno]['cpdName']:'&nbsp;';?>
				</td>
				<td align="right">
					<?php print ($ramPHR)?@number_format($ramPHR,2):'&nbsp;';?>
				</td>		
			</tr>
			<?php 
				$pno++; 
				$polyRamParts	=	$particulars[$pno]['polyRamParts'];
				$ramPHR			=	($polyRamParts)?($particulars[$pno]['ramParts']/$polyRamParts) * 100:"";				
			?>
			<tr style="font-size:8px;" height="19px">
				<th>
					No
				</th>
				<th>
					Properties
				</th>
				<th>
					UOM
				</th>
				<th width ="10%">
					Spec
				</th>
				<th width ="20%">
					Range
				</th>
				<td align="left" style="font-size:10px;">
					<?php print ($particulars[$pno]['cpdName'])?$particulars[$pno]['cpdName']:'&nbsp;';?>
				</td>
				<td align="right" style="font-size:10px;">
					<?php print ($ramPHR)?@number_format($ramPHR,2):'&nbsp;';?>
				</td>					
			</tr>
            <?php
				$pno++;
				$sql_qualdetails	=	"select t2.paramName,t3.uom_short_name,ramQanSpec,ramQanULimit,ramQanLLimit
										from tbl_rawmaterial_qan_param t1
											inner join tbl_param t2 on t2.sno = t1.ramQanParamRef
											inner join tbl_uom t3 on t3.sno = t2.paramUOM 
										where t1.ramId = '$ramid' order by t2.paramName";
				$out_qualdetails	=	@getMySQLData($sql_qualdetails);	
				$qual_particulars	=	$out_qualdetails['data'];
				$totsno		=	15;
				$sno		=	1;
				for($p=0;$p<$totsno;$p++){
					$polyRamParts	=	$particulars[$pno]['polyRamParts'];
					$ramPHR			=	($polyRamParts)?($particulars[$pno]['ramParts']/$polyRamParts) * 100:"";				
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="left" >
							<?php print ($qual_particulars[$p]['paramName'])?$qual_particulars[$p]['paramName']:'&nbsp;';?>
                        </td>
                        <td align="center">
                            <?php print ($qual_particulars[$p]['uom_short_name'])?$qual_particulars[$p]['uom_short_name']:'&nbsp;'; ?>
                        </td>
                        <td align="right">
                            <?php print ($qual_particulars[$p]['ramQanSpec'])?@number_format($qual_particulars[$p]['ramQanSpec'],2):'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($qual_particulars[$p]['ramQanSpec'])?@number_format($qual_particulars[$p]['ramQanLLimit'],2)." - ".@number_format($qual_particulars[$p]['ramQanULimit'],2):'&nbsp;'; ?>
                        </td>
                        <td align="left">
                            <?php print ($particulars[$pno]['cpdName'])?$particulars[$pno]['cpdName']:'&nbsp;';?>
                        </td>
                        <td align="right">
                            <?php print ($ramPHR)?@number_format($ramPHR,2):'&nbsp;';?>
                        </td>						
                    </tr>
                    <?php
					$pno++;
				}
			?>			
			<tr>
            	<td colspan="2" class="content_left content_bold" valign="top" height="19px">
                	Prepared:
                </td>
				<td colspan="2" class="content_left content_bold" valign="top">
                	Reviewed:
                </td>	
				<td colspan="2" class="content_left content_bold" valign="top">
                	Approved:
                </td>				
				<td class="content_left content_bold" >
					updated:<?php echo $data['updateOn']; ?>
                </td>	
            </tr>				
        </table>
		<?php 
			if($invCount+1<count($invIDs))
				echo "<div class='page_break'><br /></div>";
		}
		?>
		
    </body>
</html>