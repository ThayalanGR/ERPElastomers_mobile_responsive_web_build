DELIMITER $$

DROP PROCEDURE IF EXISTS sp_demo_drop_report$$
CREATE PROCEDURE sp_demo_drop_report( inReportName varchar(150) ) 
BEGIN
	DECLARE vID INT;
	SELECT report_id INTO vID FROM mydbr_reports WHERE proc_name = inReportName;

	IF (vID IS NOT NULL) THEN
		CALL sp_MyDBR_ReportDel( vID );
	END IF;
END $$

DELIMITER ;

-- remove reports
CALL sp_demo_drop_report ( 'sp_DBR_demo_continents' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_continent' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_countries' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_country' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_city' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_crosstab' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_subtotals' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_chart_examples' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_chart_transpose' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_pictures' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_objects' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_googlemaps_paris1' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_googlemaps_paris' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_simple' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_options' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_formatting' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_edge' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_cluster' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_molecule' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_gv_smartphone' );
CALL sp_demo_drop_report ( 'sp_DBR_demo_show_source' );

-- remove get cities autocomplete
CALL sp_MyDBR_ParamQueryDel('demo_get_cities');

-- add continents radiobuttons
CALL sp_MyDBR_ParamQueryDel('demo_get_continents');

-- remove country popup
CALL sp_MyDBR_ParamQueryDel( 'demo_get_countries' );

-- remove report popup
CALL sp_MyDBR_ParamQueryDel( 'demo_get_demo_procedures' );

-- remove smartphone scenario
CALL sp_MyDBR_ParamQueryDel( 'demo_scenario' );

-- remove layout engine
CALL sp_MyDBR_ParamQueryDel( 'demo_gv_engine' );


-- Remove google map extensions
CALL sp_MyDBR_ReportExtClean('sp_DBR_demo_country');
CALL sp_MyDBR_ReportExtClean('sp_DBR_demo_city');
call sp_MyDBR_ReportExtClean('sp_DBR_demo_show_source');


DROP PROCEDURE IF EXISTS sp_demo_drop_report;

CALL sp_MyDBR_FolderDel( -8 );
CALL sp_MyDBR_FolderDel( -7 );
CALL sp_MyDBR_FolderDel( -6 );
CALL sp_MyDBR_FolderDel( -5 );
CALL sp_MyDBR_FolderDel( -4 );
CALL sp_MyDBR_FolderDel( -3 );
CALL sp_MyDBR_FolderDel( -2 );
CALL sp_MyDBR_FolderDel( -1 );

DROP TABLE IF EXISTS demo_city;
DROP TABLE IF EXISTS demo_country;
DROP TABLE IF EXISTS demo_atom;
DROP TABLE IF EXISTS demo_molecule;
DROP TABLE IF EXISTS demo_production;
DROP TABLE IF EXISTS demo_oem_cluster;
DROP TABLE IF EXISTS demo_oem_platform;
DROP TABLE IF EXISTS demo_platform;
DROP TABLE IF EXISTS demo_platform_tech;
DROP TABLE IF EXISTS demo_platform_cluster;
DROP TABLE IF EXISTS demo_tech;
DROP TABLE IF EXISTS demo_corporate;

DROP PROCEDURE IF EXISTS sp_DBR_demo_continents;
DROP PROCEDURE IF EXISTS sp_DBR_demo_continent;
DROP PROCEDURE IF EXISTS sp_DBR_demo_countries;
DROP PROCEDURE IF EXISTS sp_DBR_demo_country;
DROP PROCEDURE IF EXISTS sp_DBR_demo_city;
DROP PROCEDURE IF EXISTS sp_DBR_demo_crosstab;
DROP PROCEDURE IF EXISTS sp_DBR_demo_subtotals;
DROP PROCEDURE IF EXISTS sp_DBR_demo_chart_examples;
DROP PROCEDURE IF EXISTS sp_DBR_demo_chart_transpose;
DROP PROCEDURE IF EXISTS sp_DBR_demo_pictures;
DROP PROCEDURE IF EXISTS sp_DBR_demo_objects;
DROP PROCEDURE IF EXISTS sp_DBR_demo_googlemaps_paris1;
DROP PROCEDURE IF EXISTS sp_DBR_demo_googlemaps_paris;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_simple;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_options;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_formatting;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_edge;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_cluster;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_molecule;
DROP PROCEDURE IF EXISTS sp_DBR_demo_gv_smartphone;
DROP PROCEDURE IF EXISTS sp_DBR_demo_showprocedure;
DROP PROCEDURE IF EXISTS sp_DBR_demo_show_source;
DROP PROCEDURE IF EXISTS sp_DBR_demo_city;
DROP PROCEDURE IF EXISTS sp_DBR_demo_country;
DROP PROCEDURE IF EXISTS sp_DBR_demo_countries;
DROP PROCEDURE IF EXISTS sp_DBR_demo_continent;
DROP PROCEDURE IF EXISTS sp_DBR_demo_continents;
DROP PROCEDURE IF EXISTS sp_demo_source;
DROP PROCEDURE IF EXISTS sp_demo_autocomplete_cities;
