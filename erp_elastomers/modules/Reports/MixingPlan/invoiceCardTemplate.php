<?php

	$batchDate		=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$planDate		= 	date_format(new DateTime($batchDate),'d-m-Y');
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $planDate; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
	<br/>
<?php	
	// Get Form Details.
	$formArray		=	@getFormDetails(15);
	$batidArr		=	@getMySQLData(" select tim.batId, DATE_FORMAT(invDate, '%d-%b-%Y') as invdate, tim.cpdId, cpdName, mastTime,shift,  
										mastTemp, mastPres, blendTime, cblendTime, blendTemp, cblendTemp, blendPres, cblendPres, 
										kneadTime, kneadTemp, kneadPres, full_test_req
										from tbl_invoice_mixplan tim
                                        inner join tbl_mixing tm on tm.batId = tim.batId and tm.is_open_stock = 0
                                        where tim.status = 1 and tm.status > 0  and batdate ='$batchDate'");
	for($rowCount=0;$rowCount<$batidArr['count'];$rowCount++)
	{
		$cpdId			=	$batidArr['data'][$rowCount]['cpdId'];	
		$batId			=	$batidArr['data'][$rowCount]['batId'];
		$shift			=	$batidArr['data'][$rowCount]['shift'];
		$cpdName		=	$batidArr['data'][$rowCount]['cpdName'];
		$invDate		=	$batidArr['data'][$rowCount]['invdate'];
		$fullTestReq	=	$batidArr['data'][$rowCount]['full_test_req'];

		$paramName[0]	=	'Mast.    Spec';
		$paramTime[0]	=	@number_format($batidArr['data'][$rowCount]['mastTime']);
		$paramTemp[0]	=	@number_format($batidArr['data'][$rowCount]['mastTemp']);
		$paramPress[0]	=	@number_format($batidArr['data'][$rowCount]['mastPres']);
		$paramName[1]	=	'Observed';
		$paramName[2]	=	'Chemical Spec';
		$paramTime[2]	=	@number_format($batidArr['data'][$rowCount]['blendTime']);
		$paramTemp[2]	=	@number_format($batidArr['data'][$rowCount]['blendTemp']);
		$paramPress[2]	=	@number_format($batidArr['data'][$rowCount]['blendPres']);
		$paramName[3]	=	'Observed';
		$paramName[4]	=	'Carbon   Spec';
		$paramTime[4]	=	@number_format($batidArr['data'][$rowCount]['cblendTime']);
		$paramTemp[4]	=	@number_format($batidArr['data'][$rowCount]['cblendTemp']);
		$paramPress[4]	=	@number_format($batidArr['data'][$rowCount]['cblendPres']);
		$paramName[5]	=	'Observed';
		$paramName[6]	=	'Kneader  Spec';
		$paramTime[6]	=	@number_format($batidArr['data'][$rowCount]['kneadTime']);
		$paramTemp[6]	=	@number_format($batidArr['data'][$rowCount]['kneadTemp']);
		$paramPress[6]	=	@number_format($batidArr['data'][$rowCount]['kneadPres']);
		$paramName[7]	=	'Observed';
		$paramTime[9]	=	'<b>UoM</b>';
		$paramTemp[9]	=	'<b>Spec.</b>';
		$paramPress[9]	=	'<b>Obs.</b>';		
		if($fullTestReq == 1)
		{
			$paramName[10]	=	'Full Test Required';
			$paramTime[10]	=	'';
			$paramTemp[10]	=	'';
			$paramName[11]	=	'';
			$paramTime[11]	=	'';
			$paramTemp[11]	=	'';
			$paramName[12]	=	'';
			$paramTime[12]	=	'';	
			$paramTemp[12]	=	'';				
		}
		else
		{
			$paramName[10]	=	'Hardness';
			$paramTime[10]	=	'Sh.A&deg';
			$paramName[11]	=	'Sp. Gravity';
			$paramName[12]	=	'T90';
			$paramTime[12]	=	'Sec';			
			
			$sql_qparams	=	@getMySQLData("select paramName,cpdQanSpec from tbl_compound_qan_param
												inner join tbl_param on cpdQanParamRef = tbl_param.sno
												where cpdId='".$cpdId."' and paramName in( 'Hardness','Specific Gravity','T90') order by paramName");
			for($paramCount=0;$paramCount<$sql_qparams['count'];$paramCount++)
			{
				if($sql_qparams['data'][$paramCount]['paramName'] == 'Hardness')
					$paramTemp[10]	=	@number_format($sql_qparams['data'][$paramCount]['cpdQanSpec']);
				if($sql_qparams['data'][$paramCount]['paramName'] == 'Specific Gravity')
					$paramTemp[11]	=	@number_format($sql_qparams['data'][$paramCount]['cpdQanSpec'],3);
				if($sql_qparams['data'][$paramCount]['paramName'] == 'T90')
					$paramTemp[12]	=	@number_format($sql_qparams['data'][$paramCount]['cpdQanSpec']);
			}	
		}
		
		$sql_rm_items	=	@getMySQLData("select rmName, grade, UOM, planQty,is_final_chemical, ramClass, if(ramClass = 3 or ramClass = 2,1,0) as is_fill_oil  from tbl_invoice_mixplan_items timi 
											inner join tbl_compound_rm tcr on tcr.ramId = timi.ramId and cpdId = '$cpdId'
											inner join tbl_rawmaterial tr on tr.ramId = tcr.ramId
											where batId='".$batId."' order by is_final_chemical,is_fill_oil,ramClass,rmName");
		$rm_dtls		=	$sql_rm_items['data'];
	
		/*echo $sql_rm_items['count'];
		echo "<pre>";
		print_r($rm_dtls);
		echo "</pre>";	exit();*/
		?>
		<p align="right">Form No: <?php print $formArray[0]; ?>	</p>
    	<table cellpadding="6" cellspacing="0" border="0" width="100%" id="print_out" >
            <tr>
				<td colspan="2" >
					Compound Ref.: <b><?php echo $cpdName; ?></b>
				</td>
				<td colspan="3" class="content_center content_bold" style="font-size:14px;">
					<?php print $formArray[1]; ?>
				</td>
				<td  colspan="2">
					Batch Ref.: <b><?php print ($batId)?((strpos($batId,'_')!== false)?substr(strrchr($batId, "_"),1):$batId):'&nbsp;';?></b>
				</td>
				<td>
					Shift: <b><?php print $shift ?></b>
				</td>				
            </tr>
            <tr>
				<th width="17%">
					Material
				</th>
				<th width="18%">
					Grade
				</th>
				<th width="10%">
					Advised<sup>Kgs</sup>
				</th>
				<th width="10%">
					Actual<sup>(&plusmn;1%)Kgs</sup>
				</th>
				<th width="15%">
					Parameter
				</th>
				<th width="10%">
					Time<sup>Sec</sup>
				</th>
				<th width="10%">
					Temp.<sup>&degC</sup>
				</th>
				<th >
					Pres.<sup>PSI</sup>
				</th>
			</tr>

			<?php
				$tqty			=	0;
				$isFinalChem	=	0;
				$isKneadCheck	=	0;
				$totsno			=	15;
				if($sql_rm_items['count'] > 15)
					$totsno		=	$sql_rm_items['count'];
					
				for($p=0;$p<$totsno;$p++){	
					if($rm_dtls[$p]['is_fill_oil'] == 1 && $isFinalChem == 0)
					{
						$isFinalChem	=	1;
					?>
					<tr>
						<td colspan="2" class="content_bold content_center">
							Store Weightment Check
						</td>
						<td class="content_bold content_right">
							<?php print @number_format($tqty, 3); ?>
						</td>
						<td class="content_bold" >
							&nbsp;
						</td>
						<td><?php echo $paramName[$p]; ?></td>
						<td align="center"><?php echo  $paramTime[$p] ?></td>
						<td align="center"><?php echo  $paramTemp[$p] ?></td>
						<td align="center"><?php echo  $paramPress[$p] ?></td>	
					</tr>
					<?php 
					}																			
					else if($rm_dtls[$p]['is_final_chemical'] == 1 && $isFinalChem < 2)
					{
					?>
					<tr>
						<td class="content_bold" rowspan="2">
							<sup>Weighed:</sup>
						</td>
						<td class="content_bold content_center">
							Target Wt
						</td>
						<td class="content_bold content_right">
							<?php print @number_format($tqty, 3); ?>
						</td>
						<td class="content_bold" rowspan="2">
							&nbsp;
						</td>
						<td><?php echo $paramName[$p + $isFinalChem]; ?></td>
						<td align="center"><?php echo  $paramTime[$p + $isFinalChem] ?></td>
						<td align="center"><?php echo  $paramTemp[$p + $isFinalChem] ?></td>
						<td align="center"><?php echo  $paramPress[$p + $isFinalChem] ?></td>	
					</tr>
					<tr>
						<td colspan="2" class="content_center content_bold">
							Final Chemicals
						</td>
						<td><?php echo $paramName[$p + $isFinalChem +1]; ?></td>
						<td align="center"><?php echo  $paramTime[$p+$isFinalChem +1] ?></td>
						<td align="center"><?php echo  $paramTemp[$p+$isFinalChem +1] ?></td>
						<td align="center"><?php echo  $paramPress[$p+$isFinalChem +1] ?></td>	
					</tr>					
					<?php 
						if($isFinalChem == 0)
							$isFinalChem	=	2;
						else
							$isFinalChem	=	3;
					}
					$tqty	=	$tqty + $rm_dtls[$p]['planQty'];
					?>
					<tr>
						<td align="left">
							<?php print ($rm_dtls[$p]['rmName'])?$rm_dtls[$p]['rmName']:'&nbsp;'; ?>
						</td>
						<td align="left">
							<?php print ($rm_dtls[$p]['grade'])?$rm_dtls[$p]['grade']:'&nbsp;'; ?>
						</td>
						<td align="right">
							<?php print ($rm_dtls[$p]['planQty'])?$rm_dtls[$p]['planQty']:'&nbsp;'; ?>
						</td>
						<td class="content_right">
							&nbsp;
						</td>
						<td><?php echo $paramName[$p + $isFinalChem]; ?></td>
						<td align="center"><?php echo  $paramTime[$p + $isFinalChem] ?></td>
						<td align="center"><?php echo  $paramTemp[$p + $isFinalChem] ?></td>
						<td align="center"><?php echo  $paramPress[$p + $isFinalChem] ?></td>	
					</tr>
					<?php
				}
			?>	
			<tr height="45px">
				<td class="content_bold" >
					<sup>Weighed:</sup>
				</td>
				<td class="content_center content_bold" >
					Target Wt
				</td>				
				<td class="content_bold content_right">
					<?php print @number_format($tqty, 3); ?>
				</td>
				<td class="content_bold">&nbsp;</td>
				<td class="content_bold"><sup>Kneaded:</sup></td>
				<td colspan="2" class="content_bold"><?php echo ($isFinalChem)?'<sup>Final:</sup>':'&nbsp;'; ?></td>				
				<td class="content_bold"><sup>Quality:</sup></td>
			</tr>			
        </table>
		<?php
			if($rowCount<$batidArr['count']-1)
			{
		?>
			<div class="page_break" />
			<br />
		<?php 
			}
		} ?>
    </body>
</html>