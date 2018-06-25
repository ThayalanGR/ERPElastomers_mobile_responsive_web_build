<?php
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Database Information |-----------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$db_host					=	"localhost";
		$db_user					=	"root";
		$db_pass					=	"wepl";
		$db_database				=	"db_elastomers";

/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Change Login UI |---------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
	// 0 => authentication with user number 
	// 1 => authentication with userid and password

	$changeLoginUi = 0;
	$name = 'changeLoginUi';
	setcookie($name , $changeLoginUi);




/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| FileSystem Information |---------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$max_upload_filesize		=	5000000;
		$cpdreceipt_upload_dir		=	"upload/cpdreceive/";
		$cmpdsched_upload_dir		=	"upload/cmpdschedule/";
		$cpdsched_upload_dir		=	"upload/cpdschedule/";
		$cmpddi_upload_dir			=	"upload/cmpddi/";
		$rfqdrawings_upload_dir		=	"upload/productdrawings/";
		$appsubdocs_upload_dir		=	"upload/appsubdocs/";
		$custappdocs_upload_dir		=	"upload/custappdocs/";
		$complaints_upload_dir		=	"upload/complaints/";
		$rca_upload_dir				=	"upload/rcadocs/";
		$closeverify_upload_dir		=	"upload/closeverifydocs/";
		$stdforms_upload_dir		=	"upload/standardforms/";
		$ramgrns_upload_dir			=	"upload/ramgrns/";	
		$tlvalidfiles_upload_dir	=	"upload/toolvalidfiles/";
		$cpdtc_upload_dir			=	"upload/cpdtc/";
		$cmpdreceipt_upload_dir		=	"upload/cmpdreceive/";
		$openstock_upload_dir		=	"upload/openstock/";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Direct Invoice Logon Information |-----------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$di_user					=	"webadmin";	
		$di_pass					=	"webadmin";
		$di_baseURL					=	"http://127.0.0.2/";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| QR Code Settings |-----------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$qr_genrate_url				=	"http://127.0.0.1/getqrcode";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Printer Settings |-----------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$label_printer				=	"\\\\sales-pc\\GC420t"; //"GC420T";//"TLP2844";	
		$packing_label_file			=	"labeltemplates\PackingLabel.txt";
		$specimen_label_file		=	"labeltemplates\SpecimenLabel.txt";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| eMail Settings |-----------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$sender_user				=	"lk@wriston.co.in";	
		$sender_add					=	"lk@wriston.co.in";
		$sender_pass				=	"madras#2";			
		$mgmt_grp_email				=	array("lk@wriston.co.in");				
		$dev_grp_email				=	array("lk@wriston.co.in");
		$cmpd_grp_email				=	array("lk@wriston.co.in");
		$cpd_grp_email				=	array("lk@wriston.co.in");
		$quality_grp_email			=	array("lk@wriston.co.in");
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Company Settings |---------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$company_add1				=	"Plot No.225/58, Veeramamunivar Road,";
		$company_add2				=	"Kandanchavadi,";
		$company_place				=	"Chennai";
		$company_pincode			=	"600096";
		$company_address			=	$company_add1. " ".$company_add2. " ".$company_place. " - ".$company_pincode;
		$company_phone				=	"+91 - 44 - 2656 079";
		$company_mobile				=	"+91 99400 28588";	
		$company_website			=	"www.wriston.co.in";
		$company_email				=	"lk@wriston.co.in";
		$company_abbrv				=	"MMPL";
		$company_cin				=	"U25190TN1985PTC012079";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Invoice Settings | --------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$despValidDays				=	3; 
		$maxItemsInInvoice			=	5;
		$eduCessAbolishCutOff		=	"2015-03-01";
		$gstChangeCutoff			=	"2017-06-26";	
		$invoiceTypes				=	array ('cmpd' => 'Component','cpd' => 'Compound','tool' => 'Tool','scrap' => 'Scrap','mix' => 'Mixing','ram' => 'Raw Material');
		$HSN['cmpd']				=	'4016';
		$HSN['tool']				=	'8480';	
		$HSN['cpd']					=	'4005';
		$HSN['scrap']				=	'4004';
		$HSN['mix']					=	'998851';
		$taxRate['cmpd']			=	'18';
		$taxRate['tool']			=	'18';
		$taxRate['cpd']				=	'18';
		$taxRate['scrap']			=	'18';
		$taxRate['mix']				=	'18';
		//if there is different HSN code for component add it to taxRate here eg:
		//$taxRate['4017']			=	'12';
		$stateList					=	array (
												 'AP' => '37-Andhra Pradesh',
												 'AR' => '12-Arunachal Pradesh',
												 'AS' => '18-Assam',
												 'BR' => '10-Bihar',
												 'CT' => '22-Chhattisgarh',
												 'GA' => '30-Goa',
												 'GJ' => '24-Gujarat',
												 'HR' => '06-Haryana',
												 'HP' => '02-Himachal Pradesh',
												 'JK' => '01-Jammu and Kashmir',
												 'JH' => '20-Jharkhand',
												 'KA' => '29-Karnataka',
												 'KL' => '32-Kerala',
												 'MP' => '23-Madhya Pradesh',
												 'MH' => '27-Maharashtra',
												 'MN' => '14-Manipur',
												 'ML' => '17-Meghalaya',
												 'MZ' => '15-Mizoram',
												 'NL' => '13-Nagaland',
												 'OR' => '21-Odisha',
												 'PB' => '03-Punjab',
												 'RJ' => '08-Rajasthan',
												 'SK' => '11-Sikkim',
												 'TN' => '33-Tamil Nadu',
												 'TS' => '36-Telangana',
												 'TR' => '16-Tripura',
												 'UK' => '05-Uttarakhand',
												 'UP' => '09-Uttar Pradesh',
												 'WB' => '19-West Bengal',
												 'AN' => '35-Andaman and Nicobar Islands',
												 'CH' => '04-Chandigarh',
												 'DN' => '26-Dadra and Nagar Haveli',
												 'DD' => '25-Daman and Diu',
												 'DL' => '07-Delhi',
												 'LD' => '31-Lakshadweep',
												 'PY' => '34-Puducherry',
												);
		$homeState					=	'TN';
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| eWaybill Settings |----------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$eWayCutoffDate				=	'2016-01-01';
		$eWayBillJsonVer			=	'1.0.0501';
		$eWayBillDocType			=	array ( 'inv' => 'INV','dc' => 'CHL','mold' => 'CHL','trim' => 'CHL');
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Number Code Settings |---------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/		
		$cpdMonthCode				=	array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I','J', 'K', 'L');
		$cmpdMonthCode				=	array('', 'J', 'K', 'L', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Customer Supplied Raw Material GRN Settings |------------------------------------------------------------------|
	|-----|//possible mixtypes 'Master' and/or 'Final'|------------------------------------------------------------------|	
	|-----|//possible roles 'self', 'client' or 'vendor'|------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$grn_customers				=	array("MMPL","WEPL","SSIPL");
		$grn_mixtype['MMPL']		=	array("Master","Final");
		$grn_mixtype['WEPL']		=	array("Final");
		$grn_mixtype['SSIPL']		=	array("Master");		
		$grn_emailadd['MMPL']		=	array("lk@wriston.co.in");
		$grn_emailadd['WEPL']		=	array("lk@wriston.co.in");
		$grn_emailadd['SSIPL']		=	array("lk@wriston.co.in");
		$grn_role['MMPL']			=	'self';
		$grn_role['WEPL']			=	'client';
		$grn_role['SSIPL']			=	'client';
		$grn_address['MMPL']		=	$company_address;
		$grn_address['WEPL']		=	"D3, Industrial Estate, Mogappair East, Chennai - 600 037.";
		$grn_address['SSIPL']		=	"D3, Industrial Estate, Mogappair East, Chennai - 600 037.";
		$grn_compname['MMPL']		=	$_SESSION['app']['comp_name'];
		$grn_compname['WEPL']		=	"Wriston Elastomers Private Limited";
		$grn_compname['SSIPL']		=	"Suja Shoei Industries Private Limited";
		$grn_rmQualCheckNotReq		=	array("SSIPL"); //vendor role need not be included		
		$grn_mixrate['WEPL']		=	15; //vendor/self role need not be included
		$grn_mixrate['SSIPL']		=	18;	
		$grn_wastage['MMPL']		=	1.5;
		$grn_wastage['WEPL']		=	1.5;
		$grn_wastage['SSIPL']		=	1.5;
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Grouping Settings |--------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$blanksGroup				=	array(3,10,25);									
		$customerGroup				=	array("UCAL","LUCAS","SAME","OTHERS");	//set "array()" if Customer group need not be controlled
		$schVsDespGroup["cuswise"]	=	"Customer Wise";
		$schVsDespGroup["cpdwise"]	=	"Compound Wise";
		$schVsDespGroup["contwise"]	=	"Contractor Wise";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Contribution Default Settings |--------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$defaultMixCost				=	30;	
		$defaultLiftCost			=	5;
		$defaultTrimCost			=	0.10;
		$defaultInspCost			=	0.05;
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Stock Ledger Settings |----------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$purCutoffDate			=	'2014-01-01';
		$mixCutoffDate			=	'2014-03-31';
		$moldCutoffDate			=	'2016-03-31';
		$inCpdCutoffDate		=	'2014-03-31';
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| "email mixing info to Vendor( Compound Formula Changes/ Schedule)" |-------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$vendor_grp_email			=	array("lk@wriston.co.in");		
		$numdays_tosend				=	5;
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Component Rejection Alert Settings |---------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$compRejPer					=	5;				
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| PDF Generation Settings |--------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$pdf_generator_path			=	"C:\\wkhtmltopdf\\bin\\wkhtmltopdf";	
		$pdf_options				=	"--javascript-delay 5000 --zoom 1.25 --quiet";
		$pdf_options_async			=	"--javascript-delay 15000 --zoom 1.25 --quiet";
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Quality Settings |---------------------------------------------------------------------------------------------|
	|-----|//Enter the reference No for Compound Tests Which Need to be Standard |-----------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$cpd_std_test_refnos		=	array(1,2,3,4);	
		$rheodb_path				=	"F:\\Stock1.mdb";
		$default_rejections			=	"('FL','TR','CR','TI','BF')";
		$appsub_docs				=	array("PPAP Checklist","Design Records","Engineering Change Documents","Customer Engineering approval",
											"Design FMEA","Process Flow Diagrams","Process FMEA","Dimensional Results",
											"Initial Process Study","Measurement System Analysis Studies",
											"Control Plan ","Part Submission Warrant","Appearance Approval Report",
											"Checking Aids","Records of Compliance With Customer Specific Requirements");
		$appsub_docs_cpd			=	array("Material, Performance Test Results",	"Qualified Laboratory Documentation");
		$appsub_min_docs			=	array(7,0);
/*	+----------------------------------------------------------------------------------------------------------------------+
	|-----| Tool Quote Settings |------------------------------------------------------------------------------------------|
	+----------------------------------------------------------------------------------------------------------------------+	*/
		$tq_std_toolsize			=	array("","150X180","180X180","180X200","200X200","200X230");
		$tq_mixing_cost				=	30.00;
		$tq_tool_life				=	25000;
		$tq_mould_lab_cost_hr		=	100.00;
		$tq_trim_lab_cost_hr		=	30.00;
		$tq_insp_lab_cost_hr		=	30.00;
		$tq_reject_percent			=	5;
		$tq_admin_percent			=	5;
		$tq_profit_percent			=	5;
/*	+----------------------------------------------------------------------------------------------------------------------+
	+----------------------------------------------------------------------------------------------------------------------+	*/	

?>