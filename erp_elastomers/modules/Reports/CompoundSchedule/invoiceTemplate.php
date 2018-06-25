<?php

	$schRef				=	(ISO_IS_REWRITE)?trim($_VAR['invID']):trim($_GET['invID']);
	$sql_schInfo			=	"select schDate,cusName,cpdName_cmpdName,remarks,cpdDesc_cmpdDesc,schQty,despQty,rate,value, disporder from tbl_scheduling t1 
								inner join tbl_customer t2 on t2.cusId = t1.cusId 
								inner join tbl_polymer_order t3 on t3.polyname = t1.cpdDesc_cmpdDesc 
								where schRef = '".$schRef."' and t1.status > 0  
								order by disporder,cpdName_cmpdName asc";	
	$schInfo				=	@getMySQLData($sql_schInfo);
	$particulars			=	$schInfo['data'];
	$noofComps				=	count($particulars);
	$schCusName				= 	$particulars[0]['cusName'];
	$schDate				= 	date_format(new DateTime($particulars[0]['schDate']),'d-m-Y');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php print $schRef; ?></title>
        <link rel="stylesheet" href="<?php echo ISO_BASE_URL; ?>_bin/invoiceTemplate.css" media="all" />
    </head>
    <body>
		<p align="right">Form No:
			<?php 
				$formArray		=	@getFormDetails(42);
				print $formArray[0]; 
			?>
		</p>	
    	<table cellpadding="3" cellspacing="0" border="0" id="print_out" >
        	<tr>
            	<td rowspan="2" colspan="2" align="center" >
                	<img src="<?php echo (ISO_IS_REWRITE)?'/':'../../../' ?>images/company_logo.png" height='80px' width='165px' />
                </td>
                <td colspan="4" class="content_bold cellpadding content_center" height='60px' >
                	<div style="font-size:20px;"><?php print $formArray[1]; ?> for <?php echo $schCusName; ?> </div>
                </td>
                <td rowspan="2" colspan="2" class="content_left content_bold uppercase" >
					<div style="font-size:14px;">Sch No: <br><?php echo $schRef; ?></br></div>
					<div style="font-size:30px;">&nbsp; &nbsp; &nbsp; &nbsp;</div>
					<div style="font-size:14px;">Sch Date: <br><?php echo $schDate; ?></div>
                </td>
            </tr>
			<tr>
				<td colspan='4' align="center" style="font-size:20px;"><b>No of Compounds: <?php echo $noofComps; ?></b>
				</td>
			<tr>
			<tr>
            	<th width="5%">
                	No
                </th>
            	<th width="15%">
                	Polymer
                </th>
            	<th width="15%">
                	Comp Name
                </th>
            	<th width="15%">
                	Schedule Qty (Kgs)
                </th>
            	<th width="15%">
                	Desp. Qty (Kgs)
                </th>
            	<th width="15%">
                	Rate (Rs)
                </th>
            	<th width="10%">
                	Value (Rs)
                </th>
            	<th width="10%">
                	Remarks
                </th>
             </tr>
            <?php
				$totsno		=	30;
				if ($noofComps > 30)
				{
					$totsno = $noofComps;
				}				
				$sno		=	1;
				$tSchqty	=	0;
				$tDespqty = 	0;
				$tRateqty	=	0;
				$tValueqty	=	0;
				$polymerName = '';
				for($p=0;$p<$totsno;$p++){
					$tSchqty  = 	$tSchqty + $particulars[$p]['schQty'] ;
					$tDespqty  = 	$tDespqty + $particulars[$p]['despQty'] ;
					$tRateqty  = 	$tRateqty + $particulars[$p]['rate'] ;
					$tValueqty  = 	$tValueqty + $particulars[$p]['value'] ;
					?>
	                <tr>
                        <td align="center" height="19px">
                            <?php print ($p+1); ?>
                        </td>
                        <td align="center">
							<?php
							if ( strcasecmp($polymerName,$particulars[$p]['cpdDesc_cmpdDesc']) == 0)	
							{						
								print '&nbsp;';
							}
							else
							{
								$polymerName = $particulars[$p]['cpdDesc_cmpdDesc'];
								print $polymerName;
							}
							?>								
                            
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['cpdName_cmpdName'])?$particulars[$p]['cpdName_cmpdName']:'&nbsp;';?>
                        </td>						
                        <td align="center">
                            <?php print ($particulars[$p]['schQty'])?@number_format($particulars[$p]['schQty'],3):'&nbsp;'; ?>
                        </td>
                       <td align="center">
							<?php print ($particulars[$p]['despQty'])?@number_format($particulars[$p]['despQty'],3):'&nbsp;';?>					   
                        </td>								
                        <td align="center">
                            <?php print ($particulars[$p]['rate'])?@number_format($particulars[$p]['rate'],2):'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['value'])?@number_format($particulars[$p]['value'],2):'&nbsp;'; ?>
                        </td>
                        <td align="center">
                            <?php print ($particulars[$p]['remarks'])?$particulars[$p]['remarks']:'&nbsp;'; ?>
                        </td>						
                    </tr>
                    <?php
				}
			?>	
            <tr>
            	<td colspan="3" class="content_center content_bold" >
                	Total
                </td>
                <td class="content_bold content_center">
                	<?php print @number_format($tSchqty, 3); ?>
                </td>
				<td class="content_center content_bold" >
                	<?php print @number_format($tDespqty, 3)?>
                </td>
                <td class="content_bold content_center">
                	<?php print @number_format($tRateqty/$noofComps, 2); ?>
                </td>
				<td class="content_bold content_center">
                	<?php print @number_format($tValueqty, 2); ?>
                </td>
				<td class="content_center content_bold" >
                	&nbsp;
                </td>						
             </tr>
            <tr>
            	<td colspan="8" class="content_left content_bold" >
                	Remarks/Observations/Complaints: <BR></BR><BR></BR>
                </td>
             </tr>	
			<tr>
            	<td colspan="4" class="content_left content_bold" >
                	Prepared:
                </td>
				<td colspan="4" class="content_left content_bold" >
                	Approved:
                </td>	
             </tr>			 
        </table>
    </body>
</html>