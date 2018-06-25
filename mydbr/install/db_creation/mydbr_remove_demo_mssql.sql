if object_id('sp_demo_drop_report','P') is not null
DROP PROCEDURE sp_demo_drop_report
go
CREATE PROCEDURE sp_demo_drop_report
@inReportName varchar(150)
AS
BEGIN
	DECLARE @id INT

	SELECT @id = report_id 
	FROM mydbr_reports 
	WHERE proc_name = @inReportName;
	
	if (@id is not null) EXEC sp_MyDBR_ReportDel @id 
END
go

sp_demo_drop_report 'sp_DBR_demo_continents'
go
sp_demo_drop_report 'sp_DBR_demo_continent'
go
sp_demo_drop_report 'sp_DBR_demo_countries'
go
sp_demo_drop_report 'sp_DBR_demo_country'
go
sp_demo_drop_report 'sp_DBR_demo_city'
go
sp_demo_drop_report 'sp_DBR_demo_crosstab'
go
sp_demo_drop_report 'sp_DBR_demo_subtotals'
go
sp_demo_drop_report 'sp_DBR_demo_chart_examples'
go
sp_demo_drop_report 'sp_DBR_demo_chart_transpose'
go
sp_demo_drop_report 'sp_DBR_demo_pictures'
go
sp_demo_drop_report 'sp_DBR_demo_objects'
go
sp_demo_drop_report 'sp_DBR_demo_googlemaps_paris1'
go
sp_demo_drop_report 'sp_DBR_demo_googlemaps_paris'
go
sp_demo_drop_report 'sp_DBR_demo_gv_simple'
go
sp_demo_drop_report 'sp_DBR_demo_gv_options'
go
sp_demo_drop_report 'sp_DBR_demo_gv_formatting'
go
sp_demo_drop_report 'sp_DBR_demo_gv_edge'
go
sp_demo_drop_report 'sp_DBR_demo_gv_cluster'
go
sp_demo_drop_report 'sp_DBR_demo_gv_molecule'
go
sp_demo_drop_report 'sp_DBR_demo_gv_smartphone'
go
sp_demo_drop_report 'sp_DBR_demo_source'
go
sp_demo_drop_report 'sp_DBR_demo_showprocedure'
go
sp_demo_drop_report 'sp_DBR_demo_show_source'
go

-- remove get cities autocomplete
sp_MyDBR_ParamQueryDel 'demo_get_cities'
go

-- add continents radiobuttons
sp_MyDBR_ParamQueryDel 'demo_get_continents'
go

-- remove country popup
sp_MyDBR_ParamQueryDel 'demo_get_countries' 
go

-- remove report popup
sp_MyDBR_ParamClear 'sp_DBR_demo_show_source'
go
sp_MyDBR_ParamQueryDel 'demo_get_demo_procedures' 
go


-- Remove google map extensions
sp_MyDBR_ReportExtClean 'sp_DBR_demo_country'
go
sp_MyDBR_ReportExtClean 'sp_DBR_demo_city'
go
sp_MyDBR_ReportExtClean 'sp_DBR_demo_show_source'
go

if object_id('sp_demo_drop_report','P') is not null
DROP PROCEDURE sp_demo_drop_report
go

sp_MyDBR_FolderDel -8 
go
sp_MyDBR_FolderDel -7 
go
sp_MyDBR_FolderDel -6 
go
sp_MyDBR_FolderDel -5 
go
sp_MyDBR_FolderDel -4 
go
sp_MyDBR_FolderDel -3 
go
sp_MyDBR_FolderDel -2 
go
sp_MyDBR_FolderDel -1 
go

if object_id('demo_city','U') is not null DROP TABLE demo_city
go
if object_id('demo_country','U') is not null DROP TABLE demo_country
go
if object_id('demo_atom','U') is not null DROP TABLE demo_atom
go
if object_id('demo_molecule','U') is not null DROP TABLE demo_molecule
go
if object_id('demo_production','U') is not null DROP TABLE demo_production
go
if object_id('demo_oem_cluster','U') is not null DROP TABLE demo_oem_cluster
go
if object_id('demo_platform','U') is not null DROP TABLE demo_platform
go
if object_id('demo_oem_platform','U') is not null DROP TABLE demo_oem_platform
go
if object_id('demo_platform_tech','U') is not null DROP TABLE demo_platform_tech
go
if object_id('demo_tech','U') is not null DROP TABLE demo_tech
go
if object_id('demo_platform_cluster','U') is not null DROP TABLE demo_platform_cluster
go
if object_id('demo_corporate','U') is not null DROP TABLE demo_corporate
go

if object_id('sp_DBR_demo_continents','P') is not null DROP PROCEDURE sp_DBR_demo_continents 
go
if object_id('sp_DBR_demo_continent','P') is not null DROP PROCEDURE sp_DBR_demo_continent
go
if object_id('sp_DBR_demo_countries','P') is not null DROP PROCEDURE sp_DBR_demo_countries
go
if object_id('sp_DBR_demo_country','P') is not null DROP PROCEDURE sp_DBR_demo_country
go
if object_id('sp_DBR_demo_city','P') is not null DROP PROCEDURE sp_DBR_demo_city
go
if object_id('sp_DBR_demo_crosstab','P') is not null DROP PROCEDURE sp_DBR_demo_crosstab
go
if object_id('sp_DBR_demo_subtotals','P') is not null DROP PROCEDURE sp_DBR_demo_subtotals
go
if object_id('sp_DBR_demo_chart_examples','P') is not null DROP PROCEDURE sp_DBR_demo_chart_examples
go
if object_id('sp_DBR_demo_chart_transpose','P') is not null DROP PROCEDURE sp_DBR_demo_chart_transpose
go
if object_id('sp_DBR_demo_pictures','P') is not null DROP PROCEDURE sp_DBR_demo_pictures
go
if object_id('sp_DBR_demo_objects','P') is not null DROP PROCEDURE sp_DBR_demo_objects
go
if object_id('sp_DBR_demo_googlemaps_paris1','P') is not null DROP PROCEDURE sp_DBR_demo_googlemaps_paris1
go
if object_id('sp_DBR_demo_googlemaps_paris','P') is not null DROP PROCEDURE sp_DBR_demo_googlemaps_paris
go
if object_id('sp_DBR_demo_gv_simple','P') is not null DROP PROCEDURE sp_DBR_demo_gv_simple
go
if object_id('sp_DBR_demo_gv_options','P') is not null DROP PROCEDURE sp_DBR_demo_gv_options
go
if object_id('sp_DBR_demo_gv_formatting','P') is not null DROP PROCEDURE sp_DBR_demo_gv_formatting
go
if object_id('sp_DBR_demo_gv_edge','P') is not null DROP PROCEDURE sp_DBR_demo_gv_edge
go
if object_id('sp_DBR_demo_gv_cluster','P') is not null DROP PROCEDURE sp_DBR_demo_gv_cluster
go
if object_id('sp_DBR_demo_gv_molecule','P') is not null DROP PROCEDURE sp_DBR_demo_gv_molecule
go
if object_id('sp_DBR_demo_gv_smartphone','P') is not null DROP PROCEDURE sp_DBR_demo_gv_smartphone
go
if object_id('sp_DBR_demo_showprocedure','P') is not null DROP PROCEDURE sp_DBR_demo_showprocedure
go
if object_id('sp_DBR_demo_show_source','P') is not null DROP PROCEDURE sp_DBR_demo_show_source
go
if object_id('sp_demo_autocomplete_cities','P') is not null DROP PROCEDURE sp_demo_autocomplete_cities
go
