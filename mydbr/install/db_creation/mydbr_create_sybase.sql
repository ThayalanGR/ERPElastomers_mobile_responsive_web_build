--  myDBR. Copyright mydbr.com 2010-2012

--  myDBR Tables

if object_id('dbo.fn_mydbr_column_exists') is not null 
drop function fn_mydbr_column_exists
go
create function dbo.fn_mydbr_column_exists( @table sysname, @column sysname )
returns tinyint
as
begin
declare @ret tinyint

if exists (
  select * 
  from dbo.syscolumns
  where id=object_id(@table) and name=@column)
  select @ret=1
else 
  select @ret=0

return @ret
end
go

if object_id('mydbr_reportgroups') is null 
EXECUTE("create table mydbr_reportgroups (
id int IDENTITY,
name varchar(128) NOT NULL,
sortorder int NOT NULL,
color char(6) NOT NULL,
primary key (id)
)
")
go

if (select count(*) from mydbr_reportgroups where id=1)=0 begin
  insert into mydbr_reportgroups (name, sortorder, color) 
  values ('#{MYDBR_AA_REPORTS}', 100, '00C322')
end
if (select count(*) from mydbr_reportgroups where id=-1)=0 begin

  insert into mydbr_reportgroups (id, name, sortorder, color) 
  values (-1, '#{MYDBR_AA_FAVOURITES}', 0, '00AAFF')
end
go

update mydbr_reportgroups 
set name = '#{MYDBR_AA_FAVOURITES}'
where id=-1
go

update mydbr_reportgroups
set name = '#{MYDBR_AA_REPORTS}'
where id=1 and name='Reports'
go

if object_id('mydbr_folders') is null
EXECUTE("create table mydbr_folders (
folder_id int,
mother_id int null,
name varchar(100) null,
invisible tinyint null,
reportgroup int not null references mydbr_reportgroups(id),
explanation varchar(255) null,
primary key (folder_id)
)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_folders' and  name='explanation')
alter table mydbr_folders add explanation varchar(255) null
go

if not exists (select * from mydbr_folders where folder_id=1)
insert into mydbr_folders values (1, null, '#{MYDBR_AMAIN_HOME}', 2, 1, null)
go
if not exists (select * from mydbr_folders where folder_id=2)
insert into mydbr_folders values (2,1,'Admin reports',2, 1, null)
go
if not exists (select * from mydbr_folders where folder_id=3)
insert into mydbr_folders values (3,2,'Drill reports',2, 1, null)
go

if object_id('mydbr_groups') is null
EXECUTE("create table mydbr_groups (
group_id int not null,
name varchar(100) null,
primary key (group_id)
)
")
go


if object_id('mydbr_groupsusers') is null
EXECUTE("create table mydbr_groupsusers (
group_id int not null,
username varchar(128) not null,
authentication int not null,
primary key (group_id,username,authentication)
)
")
go

/* 4.0 -> 4.2.1 user belonging to bogus 0 group  */
delete from mydbr_groupsusers where group_id = 0
go


if object_id('mydbr_param_queries') is null
EXECUTE("create table mydbr_param_queries (
name varchar(255) not null,
query varchar(3900) not null,
coltype tinyint not null,
optionss varchar(255) null,
primary key (name)
)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_param_queries' and  name='optionss')
alter table mydbr_param_queries add optionss varchar(255) null
go
update mydbr_param_queries set coltype=4, optionss = '{"scroll":true,"find":true}' where coltype=5
update mydbr_param_queries set coltype=4, optionss = '{"scroll":true}' where coltype=6
update mydbr_param_queries set coltype=4, optionss = '{"find":true}' where coltype=7
update mydbr_param_queries set coltype=4, optionss = '{"collapse":true}' where coltype=8
update mydbr_param_queries set coltype=4, optionss = '{"scroll":true,"find":true,"collapse":true}' where coltype=9
update mydbr_param_queries set coltype=4, optionss = '{"scroll":true,"collapse":true}' where coltype=10
update mydbr_param_queries set coltype=4, optionss = '{"find":true,"collapse":true}' where coltype=11
go



if (select length from dbo.syscolumns where id=object_id('mydbr_param_queries') and name='query')!=3900
EXECUTE("alter table mydbr_param_queries modify query varchar(3900) not null")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_param_queries') and name='name')!=255
EXECUTE("alter table mydbr_param_queries modify name varchar(255) not null")
go
if not exists (select * from mydbr_param_queries where name='MonthAgo')
insert into mydbr_param_queries values ('MonthAgo', 'select dateadd(mm, -1,getdate())', 3, null)
go
if not exists (select * from mydbr_param_queries where name='Now')
insert into mydbr_param_queries 
values ('Now', 'select getdate()', 3, null)
go
if not exists (select * from mydbr_param_queries where name='Steps_5-10-20-100')
insert into mydbr_param_queries 
values ('Steps_5-10-20-100', 'select 5,5 union select 10,10 union select 20,20 union select 50,50 union select 100,100', 0, null)
go
if not exists (select * from mydbr_param_queries where name='Yes No')
insert into mydbr_param_queries 
values ('Yes No', 'select 1, ''Yes'' union select 0, ''No''', 1, null)
go

if object_id('mydbr_params') is null
EXECUTE("create table mydbr_params (
proc_name sysname not null,
param sysname not null,
query_name varchar(255) null,
title varchar(255) null,
default_value varchar(255) null,
optional tinyint not null,
only_default tinyint not null,
suffix varchar(255) null,
optionss varchar(1024) null,
primary key (proc_name, param)
)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_params' and  name='suffix')
alter table mydbr_params add suffix varchar(255) null
go
if (select length from dbo.syscolumns where id=object_id('mydbr_params') and name='title')!=255
EXECUTE("alter table mydbr_params modify title varchar(255) null")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_params') and name='suffix')!=255
EXECUTE("alter table mydbr_params modify suffix varchar(255) null")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_params') and name='query_name')!=255
EXECUTE("alter table mydbr_params modify query_name varchar(255) null")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_params') and name='default_value')!=255
EXECUTE("alter table mydbr_params modify default_value varchar(255) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_params' and  name='only_default')
alter table mydbr_params add only_default tinyint null
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_params' and  name='optionss')
alter table mydbr_params add optionss varchar(1024) null
go


if not exists (select * from mydbr_params where proc_name='sp_DBR_StatisticsSummary' and param='inEndDate' )
insert into mydbr_params 
values ('sp_DBR_StatisticsSummary','inEndDate',null,'End date','Now',0,0, null, null)
go
if not exists (select * from mydbr_params where proc_name='sp_DBR_StatisticsSummary' and param='inRowCount' )
insert into mydbr_params 
values ('sp_DBR_StatisticsSummary','inRowCount','Steps_5-10-20-100','Row count',null,0,0, null, null)
go
if not exists (select * from mydbr_params where proc_name='sp_DBR_StatisticsSummary' and param='inStartDate' )
insert into mydbr_params 
values ('sp_DBR_StatisticsSummary','inStartDate',null,'Start date','MonthAgo',0,0, null, null)
go
if not exists (select * from mydbr_params where proc_name='sp_DBR_StatisticsReport' and param='inEndDate' )
insert into mydbr_params 
values ('sp_DBR_StatisticsReport','inEndDate',null,'End date','Now',0,0, null, null)
go
if not exists (select * from mydbr_params where proc_name='sp_DBR_StatisticsReport' and param='inStartDate' )
insert into mydbr_params 
values ('sp_DBR_StatisticsReport','inStartDate',null,'Start date','MonthAgo',0,0, null, null)
go


if object_id('mydbr_reports') is null
EXECUTE("create table mydbr_reports (
report_id int not null,
name varchar(150) not null,
proc_name sysname not null,
folder_id int not null,
explanation varchar(255) null,
reportgroup int not null,
sortorder int null,
runreport varchar(50) null,
autoexecute tinyint null,
parameter_help varchar(10000) null,
export varchar(10) null,
primary key (report_id)
)
")
go

if (dbo.fn_mydbr_column_exists('mydbr_reports', 'runreport')=0) begin 
  EXECUTE("alter table mydbr_reports add runreport varchar(50) null") 
end
go
if (dbo.fn_mydbr_column_exists('mydbr_reports', 'autoexecute')=0) begin 
  EXECUTE("alter table mydbr_reports add autoexecute tinyint null") 
end
go
if (dbo.fn_mydbr_column_exists('mydbr_reports', 'parameter_help')=0) begin 
  EXECUTE("alter table mydbr_reports add parameter_help varchar(10000) null") 
end
go
if (dbo.fn_mydbr_column_exists('mydbr_reports', 'export')=0) begin 
  EXECUTE("alter table mydbr_reports add export varchar(10) null") 
end
go
if (select length from dbo.syscolumns where id=object_id('mydbr_reports') and name='parameter_help')!=10000
  EXECUTE("alter table mydbr_reports modify parameter_help varchar(10000) null")
go



if object_id('mydbr_report_extensions') is null
EXECUTE("create table mydbr_report_extensions (
proc_name varchar(100) not null,
extension varchar(100) not null
)
create unique clustered index mydbr_report_extensions_ind on mydbr_report_extensions(proc_name, extension)
")
go

if not exists (select * from mydbr_reports where report_id=1 )
insert into mydbr_reports (report_id, name, proc_name, folder_id, explanation, reportgroup)
values (1,'Statistics summary','sp_DBR_StatisticsSummary',2,'', 1)
go
if not exists (select * from mydbr_reports where report_id=2 )
insert into mydbr_reports (report_id, name, proc_name, folder_id, explanation, reportgroup)
values (2,'Statistics for a report','sp_DBR_StatisticsReport',3,'', 1)
go

if object_id('mydbr_reports_priv') is null
EXECUTE("create table mydbr_reports_priv (
report_id int not null,
username varchar(128) not null,
group_id int not null,
authentication int
)
create unique clustered index mydbr_reports_priv_ind on mydbr_reports_priv(report_id,username,group_id,authentication)
")
go

if object_id('mydbr_folders_priv') is null
EXECUTE("create table mydbr_folders_priv (
folder_id int not null,
username varchar(128) not null,
group_id int not null,
authentication int
)
create unique clustered index mydbr_folders_priv_ind on mydbr_folders_priv(folder_id,username,group_id,authentication)
")
go

if (select count(*) from mydbr_folders_priv)=0 begin
  insert into mydbr_folders_priv
  select folder_id, 'PUBLIC', 0, 0
  from mydbr_folders
  where invisible = 0

  /* We'll take invisible out of use */
  update mydbr_folders
  set invisible = 2
  where invisible = 0
end
go

if not exists (select * from mydbr_folders_priv where folder_id=1 and username='PUBLIC')
insert into mydbr_folders_priv values (1, 'PUBLIC', 0, 0)
go

if object_id('mydbr_statistics') is null
EXECUTE("create table mydbr_statistics (
id int not null,
proc_name varchar(100) not null,
username varchar(128) not null,
authentication int not null,
start_time datetime not null,
end_time datetime null,
query varchar(6000) not null,
ip_address varchar(255) null,
user_agent_hash varchar(50) null
)
create unique clustered index mydbr_statistics_ind on mydbr_statistics(id)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_statistics' and  name='ip_address')
EXECUTE("alter table mydbr_statistics add ip_address varchar(255) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_statistics' and  name='user_agent_hash')
EXECUTE("alter table mydbr_statistics add user_agent_hash varchar(50) null")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_statistics') and name='query')!=6000
EXECUTE("alter table mydbr_statistics modify query varchar(6000) not null")
go


if object_id('mydbr_user_agents') is null
EXECUTE("create table mydbr_user_agents (
hashvalue varchar(50) NULL,
user_agent varchar(6000) NULL
)
create unique clustered index mydbr_user_agents_ind on mydbr_user_agents(hashvalue)
")
go


-- We'll create table under name dbo to keep it compatible with both ASE and SQL Anywhere
if object_id('dbo.mydbr_styles') is null
EXECUTE("create table dbo.mydbr_styles (
name varchar(30) not null,
colstyle tinyint not null,
definition varchar(400) not null,
primary key (name)
)
")
go

if not exists (select * from mydbr_styles where name='3 decimals' )
insert into mydbr_styles 
values ('3 decimals',0,'%.3f')
go
if not exists (select * from mydbr_styles where name='Bold' )
insert into mydbr_styles 
values ('Bold',0,'[font-weight: bold;]')
go
if not exists (select * from mydbr_styles where name='$US' )
insert into mydbr_styles 
values ('$US',0,'$ %.2f')
go


if object_id('mydbr_userlogin') is null
EXECUTE("create table mydbr_userlogin (
username varchar(128) not null,
password varchar(255) null,
name varchar(128) null,
admin tinyint not null,
passworddate datetime null,
email varchar(100) null,
telephone varchar(100) NULL,
authentication int null,
ask_pw_change int null,
unique clustered (username, authentication)
)
")
go

if (select length from dbo.syscolumns where id=object_id('mydbr_userlogin') and name='password')!=255
EXECUTE("alter table mydbr_userlogin modify password varchar(255) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_userlogin' and  name='ask_pw_change')
EXECUTE("alter table mydbr_userlogin add ask_pw_change int null")
go


update mydbr_userlogin
set authentication=2
where authentication is null
go

if object_id('mydbr_password_reset') is null
EXECUTE("create table mydbr_password_reset (
username nvarchar(128) not null,
perishable_token varchar(128) not null,
request_time datetime not null,
ip_address varchar(255) null,
primary key (username)
)
")
go


if not exists (select * from mydbr_userlogin where username='dba' )
if not exists (select * from mydbr_userlogin where admin=1 )
insert into mydbr_userlogin ( username, password, name, admin, passworddate, email, authentication, telephone)
values ('dba','d693c4871a99d7acf43c4b1112da0c6e','myDBR Administrator',1, getdate(), null, 2, null)
go

if object_id('mydbr_notifications') is null
EXECUTE("create table mydbr_notifications (
id int not null,
notification varchar(2048) null
)
create unique clustered index mydbr_notifications_ind on mydbr_notifications(id)
")
go

if object_id('mydbr_authentication') is null
EXECUTE("create table mydbr_authentication (
module varchar(20) not null,
mask int not null,
name varchar(30)
)
create unique clustered index mydbr_authentication_ind on mydbr_authentication(module)
")
go


if object_id('mydbr_licenses') is null
EXECUTE("create table mydbr_licenses (
id int not null,
owner varchar(255) not null,
email varchar(255) not null,
company varchar(255) not null,
host varchar(255) not null,
license_key varchar(80) not null,
db varchar(20) not null,
expiration datetime not null,
type varchar(255) null,
version varchar(255) null,
primary key (id)
)
")
go

if object_id('mydbr_version') is not null
drop table mydbr_version
go
create table mydbr_version (
mydbr_version varchar(10)
)
go

if object_id('mydbr_update') is not null
drop table mydbr_update
go
create table mydbr_update (
latest_version varchar(10),
next_check int,
download_link varchar(200),
info_link varchar(200),
last_successful_check int,
signature varchar(50)
)
create unique clustered index mydbr_update_ind on mydbr_update(latest_version)
go

if object_id('mydbr_log') is null
EXECUTE("create table mydbr_log (
id int identity,
username varchar(128) not null,
log_time datetime,
log_ip varchar(40) null,
log_title varchar(30) null,
log_message varchar(2000)
)
create unique clustered index mydbr_log_ind on mydbr_log(id)
")
go

delete from mydbr_authentication
go

insert into mydbr_authentication values ('db', 1, 'Database login')
insert into mydbr_authentication values ('mydbr', 2, 'myDBR user')
insert into mydbr_authentication values ('ext', 4, 'Single Sign-On')
insert into mydbr_authentication values ('ldap', 8, 'LDAP')
insert into mydbr_authentication values ('custom', 16, 'Custom')
go

update mydbr_authentication set name='Single Sign-On' where module='ext'
go


if object_id('mydbr_options') is null
EXECUTE("create table mydbr_options (
username varchar(128) not null,
authentication int not null,
name varchar(30) not null,
value varchar(512) not null,
primary key (username, authentication, name)
)
")
go
if (select length from dbo.syscolumns where id=object_id('mydbr_options') and name='value')!=512
EXECUTE("alter table mydbr_options modify value varchar(512) not null")
go


if object_id('mydbr_favourite_reports') is null
EXECUTE("create table mydbr_favourite_reports (
id int identity,
username varchar(128),
authentication int NOT NULL,
report_id int NOT NULL,
url varchar(512) NULL,
primary key (id),
foreign key (report_id) references mydbr_reports (report_id),
foreign key (username, authentication) references mydbr_userlogin (username, authentication)
)
create index mydbr_ffav_user on mydbr_favourite_reports ( username, authentication )
")
go

-- myDBR internal key_column_usage for databases which do not have FOREIGN keys defined
if object_id('mydbr_key_column_usage') is null
EXECUTE("create table mydbr_key_column_usage(
table_schema sysname,
table_name sysname,
column_name sysname,
referenced_table_schema sysname,
referenced_table_name sysname,
referenced_column_name sysname,
primary key (table_schema, table_name, column_name)
)
")
go

if object_id('mydbr_languages') is null
EXECUTE("create table mydbr_languages(
lang_locale char(5),
language varchar(30),
date_format varchar(10) null,
time_format varchar(10) null,
thousand_separator varchar(2) null,
decimal_separator varchar(2) null,
primary key (lang_locale)
)
")
go

if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_languages' and  name='date_format')
EXECUTE("alter table mydbr_languages add date_format varchar(10) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_languages' and  name='time_format')
EXECUTE("alter table mydbr_languages add time_format varchar(10) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_languages' and  name='thousand_separator')
EXECUTE("alter table mydbr_languages add thousand_separator varchar(2) null")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_languages' and  name='decimal_separator')
EXECUTE("alter table mydbr_languages add decimal_separator varchar(2) null")
go


/* We'll use temp table for mass insert. Kind of insert ignore */
create table #mydbr_languages_tmp (
lang_locale char(5),
language varchar(30),
date_format varchar(10) null,
time_format varchar(10) null,
thousand_separator varchar(2) null,
decimal_separator varchar(2) null
)
go

insert into #mydbr_languages_tmp (language, lang_locale) values('Arabic', 'ar_SA')
insert into #mydbr_languages_tmp (language, lang_locale) values('Bulgarian', 'bg_BG')
insert into #mydbr_languages_tmp (language, lang_locale) values('Chinese', 'zh_CN')
insert into #mydbr_languages_tmp (language, lang_locale) values('Croatian', 'hr_HR')
insert into #mydbr_languages_tmp (language, lang_locale) values('Czech', 'cs_CZ')
insert into #mydbr_languages_tmp (language, lang_locale) values('Danish', 'da_DK')
insert into #mydbr_languages_tmp (language, lang_locale) values('Dutch', 'nl_NL')
insert into #mydbr_languages_tmp (language, lang_locale) values('English', 'en_US')
insert into #mydbr_languages_tmp (language, lang_locale) values('British English', 'en_GB')
insert into #mydbr_languages_tmp (language, lang_locale) values('Estonian', 'et_EE')
insert into #mydbr_languages_tmp (language, lang_locale) values('Finnish', 'fi_FI')
insert into #mydbr_languages_tmp (language, lang_locale) values('French', 'fr_FR')
insert into #mydbr_languages_tmp (language, lang_locale) values('German', 'de_DE')
insert into #mydbr_languages_tmp (language, lang_locale) values('Greek', 'el_GR')
insert into #mydbr_languages_tmp (language, lang_locale) values('Hungarian', 'hu_HU')
insert into #mydbr_languages_tmp (language, lang_locale) values('Icelandic', 'is_IS')
insert into #mydbr_languages_tmp (language, lang_locale) values('Italian', 'it_IT')
insert into #mydbr_languages_tmp (language, lang_locale) values('Japanese', 'ja_JP')
insert into #mydbr_languages_tmp (language, lang_locale) values('Korean', 'ko_KR')
insert into #mydbr_languages_tmp (language, lang_locale) values('Latvian', 'lv_LV')
insert into #mydbr_languages_tmp (language, lang_locale) values('Lithuanian', 'lt_LT')
insert into #mydbr_languages_tmp (language, lang_locale) values('Norwegian', 'no_NO')
insert into #mydbr_languages_tmp (language, lang_locale) values('Polish', 'pl_PL')
insert into #mydbr_languages_tmp (language, lang_locale) values('Portuguese', 'pt_PT')
insert into #mydbr_languages_tmp (language, lang_locale) values('Romanian', 'ro_RO')
insert into #mydbr_languages_tmp (language, lang_locale) values('Russian', 'ru_RU')
insert into #mydbr_languages_tmp (language, lang_locale) values('Slovak', 'sk_SK')
insert into #mydbr_languages_tmp (language, lang_locale) values('Slovenian', 'sl_SI')
insert into #mydbr_languages_tmp (language, lang_locale) values('Spanish', 'es_ES')
insert into #mydbr_languages_tmp (language, lang_locale) values('Swedish', 'sv_SE')
insert into #mydbr_languages_tmp (language, lang_locale) values('Turkish', 'tr_TR')
go

update #mydbr_languages_tmp 
set date_format = 'm/d/Y', time_format = 'h:i:s a', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('en_US')
go

update #mydbr_languages_tmp 
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('en_GB')
go

update #mydbr_languages_tmp 
set date_format = 'Y-m-d', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('zh_CN')
go

update #mydbr_languages_tmp 
set date_format = 'Y-m-d', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('sv_SE', 'lt_LT')
go

update #mydbr_languages_tmp 
set date_format = 'Y.m.d', time_format = 'h:i:s a', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('ko_KR')
go

update #mydbr_languages_tmp 
set date_format = 'Y.m.d', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('hu_HU')
go

update #mydbr_languages_tmp 
set date_format = 'd.m.Y', time_format = 'H.i.s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('fi_FI', 'el_GR')
go

update #mydbr_languages_tmp 
set date_format = 'd.m.Y', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('cs_CZ', 'el_GR', 'bg_BG', 'et_EE', 'lv_LV', 'no_NO', 'pl_PL', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'hr_HR')
go

update #mydbr_languages_tmp 
set date_format = 'd.m.Y', time_format = 'H:i:s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('de_DE', 'is_IS', 'tr_TR')
go

update #mydbr_languages_tmp 
set date_format = 'd-m-Y', time_format = 'H:i:s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('nl_NL')
go

update #mydbr_languages_tmp
set date_format = 'd/m/Y', time_format = 'H.i.s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('it_IT', 'da_DK', 'pt_PT')
go

update #mydbr_languages_tmp
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('fr_FR', 'es_ES', 'ar_SA')
go

update #mydbr_languages_tmp
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('ja_JP')
go

delete from mydbr_languages
where lang_locale not in (
  select lang_locale
  from #mydbr_languages_tmp
)
go

update mydbr_languages
set 
  mydbr_languages.date_format = t.date_format,
  mydbr_languages.time_format = t.time_format,
  mydbr_languages.thousand_separator = t.thousand_separator,
  mydbr_languages.decimal_separator = t.decimal_separator
from #mydbr_languages_tmp t
where mydbr_languages.lang_locale=t.lang_locale
go

insert into mydbr_languages (lang_locale, language, date_format, time_format, thousand_separator, decimal_separator)
select lang_locale, language, date_format, time_format, thousand_separator, decimal_separator
from #mydbr_languages_tmp
where lang_locale not in (
  select lang_locale
  from mydbr_languages
)
go

update mydbr_languages
set date_format = t.date_format, time_format = t.time_format, 
thousand_separator = t.thousand_separator, decimal_separator = t.decimal_separator
from #mydbr_languages_tmp t
where mydbr_languages.lang_locale = t.lang_locale
go

drop table #mydbr_languages_tmp
go


if object_id('mydbr_localization') is null
EXECUTE("create table mydbr_localization (
lang_locale char(5),
keyword varchar(50),
translation varchar(1024),
primary key (lang_locale, keyword),
creation_date datetime null,
foreign key (lang_locale) REFERENCES mydbr_languages(lang_locale)
)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_localization' and  name='creation_date') begin
EXECUTE("alter table mydbr_localization add creation_date datetime null")
end
go

if object_id('mydbr_remote_servers') is null
EXECUTE("create table mydbr_remote_servers (
id int identity,
server varchar(128) not null,
url varchar(255) not null,
hash varchar(40) not null,
username varchar(128) not null,
password varchar(128) not null,
primary key(id)
)
")
go


if object_id('mydbr_templates') is null
EXECUTE("create table mydbr_templates (
id int identity,
name varchar(128) not null,
header varchar(3900) null,
row varchar(3900) null,
footer varchar(3900) null,
folder_id int null,
creation_date datetime null,
primary key(id)
)
")
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_templates' and  name='folder_id') begin
EXECUTE("alter table mydbr_templates add folder_id int null")
EXECUTE("update mydbr_templates set folder_id = 1")
end
go
if not exists (select * from dbo.syscolumns where object_name(id)='mydbr_templates' and  name='creation_date') begin
EXECUTE("alter table mydbr_templates add creation_date datetime null")
end
go


if object_id('mydbr_template_folders') is null
EXECUTE("create table mydbr_template_folders (
id int identity,
name varchar(128) null,
parent_id int null,
primary key(id)
)
")
go

if not exists (select * from mydbr_template_folders) begin
insert into mydbr_template_folders (name, parent_id) values ('Main', null)
end
go

if object_id('sp_MyDBR_OptionInit') is not null
drop procedure sp_MyDBR_OptionInit
go
create procedure sp_MyDBR_OptionInit (  
@inName varchar(30), 
@inValue varchar(512) 
)
as
begin
declare @vCnt int

select  @vCnt = count(*)
from mydbr_options 
where username = '' and authentication=0 and name = @inName

if (@vCnt=0) begin
  insert into mydbr_options (name, value, authentication, username) 
  values ( @inName, @inValue, 0, '' )
end
end
go

exec sp_MyDBR_OptionInit 'avgprefix', 's:3:"avg";'
go
exec sp_MyDBR_OptionInit 'countprefix', 's:1:"#";'
go
exec sp_MyDBR_OptionInit 'dateformat', 's:5:"Y-m-d";'
go
exec sp_MyDBR_OptionInit 'datetimeformat', 's:13:"Y-m-d h:i:s a";'
go
exec sp_MyDBR_OptionInit 'dbrreportprefix', 's:6:"sp_DBR";'
go
exec sp_MyDBR_OptionInit 'decimal_separator', 's:1:".";'
go
exec sp_MyDBR_OptionInit 'def_password', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'def_username', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'flashchart', 'b:1;'
go
exec sp_MyDBR_OptionInit 'graphvizchart', 'b:0;'
go
exec sp_MyDBR_OptionInit 'imagechart', 'b:0;'
go
exec sp_MyDBR_OptionInit 'image_preferred', 'b:0;'
go
exec sp_MyDBR_OptionInit 'maxprefix', 's:3:"max";'
go
exec sp_MyDBR_OptionInit 'minprefix', 's:3:"min";'
go
exec sp_MyDBR_OptionInit 'sumprefix', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'theme', 's:7:"default";'
go
exec sp_MyDBR_OptionInit 'thousand_separator', 's:1:",";'
go
exec sp_MyDBR_OptionInit 'timeformat', 's:7:"h:i:s a";'
go
exec sp_MyDBR_OptionInit 'password_expiration', 'i:0;'
go
exec sp_MyDBR_OptionInit 'password_length', 'i:0;'
go
exec sp_MyDBR_OptionInit 'password_letter', 'b:0;'
go
exec sp_MyDBR_OptionInit 'password_number', 'b:0;'
go
exec sp_MyDBR_OptionInit 'password_special', 'b:0;'
go
exec sp_MyDBR_OptionInit 'php_include_path', 's:0:"";'    
go
exec sp_MyDBR_OptionInit 'authentication', 'i:2;'
go
exec sp_MyDBR_OptionInit 'sso_server_url', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'sso_google_client_id', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'sso_google_client_secret', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'sso_google_hosted_domain', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'sso_type', 's:1:"0";'
go
exec sp_MyDBR_OptionInit 'sso_token', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'proxy_server', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'session_lifetime', 'i:1;'
go
exec sp_MyDBR_OptionInit 'password_reset_enabled', 'b:0;'
go
exec sp_MyDBR_OptionInit 'password_reset_email_username', 'i:0;'
go
exec sp_MyDBR_OptionInit 'password_reset_admin_change', 'b:0;'
go
exec sp_MyDBR_OptionInit 'password_reset_mail_validity', 'i:15;'
go
exec sp_MyDBR_OptionInit 'password_reset_show_login_fail', 'b:0;'
go
exec sp_MyDBR_OptionInit 'languages','s:47:"en_US|de_DE|fi_FI|sv_SE|nl_NL|it_IT|es_ES|el_GR";'
go
exec sp_MyDBR_OptionInit 'oem_application_name', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'oem_header_disable','b:0;'
go
exec sp_MyDBR_OptionInit 'oem_footer_disable','b:0;'
go
exec sp_MyDBR_OptionInit 'oem_footer', 's:0:"";'
go
exec sp_MyDBR_OptionInit 'oem_info', 's:0:"";'
go

update mydbr_options
set value = 's:7:"default";'
where name='theme' and value='s:7:"taikala";'
go

/* In order to use same procs with ASE & ASA, we'll create a fake table for ASE */
if object_id('SYS.SYSPROCPARMS') is null and object_id('SYSPROCPARMS') is null
EXECUTE("create table SYSPROCPARMS(
procname varchar(1), 
parmtype int,
parmname varchar(1), 
parmdomain varchar(1),
user_type varchar(1),
length int, 
parm_id int
)
")
go

if object_id('mydbr_snippets') is null
EXECUTE("create table mydbr_snippets(
id int IDENTITY,
name varchar(30) null,
code varchar(2000) null,
shortcut varchar(20) null,
cright int null,
cdown int null,
primary key(id)
)
")
go

create table #mydbr_snippets (
name varchar(30) null,
code varchar(2000) null,
shortcut varchar(20) null,
cright int null,
cdown int null
)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('select-clause', 'select '+char(10)+'from '+char(10)+'where ', 'Ctrl-Alt-S', 7, 0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('if-clause', 'if () begin'+char(10)+'end', 'Ctrl-I', 4, 0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('if-else-clause', 'if () begin'+char(10)+'end else begin'+char(10)+'end', 'Ctrl-Alt-I', 4, 0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('while-clause', 'while () begin'+char(10)+'end', 'Ctrl-Alt-W', 7, 0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('create procedure', 'create procedure sp_DBR()'+char(10)+'as'+char(10)+'begin'+char(10)+''+char(10)+'end', 'Ctrl-P', 23, 0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('create function', 'create function fn_() '+char(10)+'returns varchar(255)'+char(10)+'begin'+char(10)+''+char(10)+'declare @v_ret varchar(255)'+char(10)+''+char(10)+'return @v_ret'+char(10)+''+char(10)+'end'+char(10)+'go', '', 19,0)
go
insert into #mydbr_snippets (name, code, shortcut, cright, cdown) 
values ('cursor', 'declare c_cursor cursor for'+char(10)+
'select '+char(10)+
'from '+char(10)+char(10)+
'open c_cursor'+char(10)+char(10)+
'fetch c_cursor into @'+char(10)+char(10)+
'while (@@sqlstatus != 2) begin'+char(10)+
'  -- Do something'+char(10)+
'  fetch c_cursor into @'+char(10)+
'end'+char(10)+char(10)+
'close c_cursor', '', 7, 1)
go

if not exists (select * from mydbr_snippets) begin
insert into mydbr_snippets (name, code, shortcut, cright, cdown) 
select name, code, shortcut, cright, cdown
from #mydbr_snippets
end
go

/* For the primary key change. Drop can be removed June 2017 */
if object_id('mydbr_sync_exclude') is not null
EXECUTE("drop table mydbr_sync_exclude")
go

if object_id('mydbr_sync_exclude') is null
EXECUTE("create table mydbr_sync_exclude (
username varchar(128) NOT NULL,
authentication int NOT NULL,
proc_name sysname NOT NULL,
type varchar(20) NULL,
primary key (username, authentication, proc_name)
)
")
go


if object_id('mydbr_scheduled_tasks') is null 
EXECUTE("create table mydbr_scheduled_tasks (
id int IDENTITY,
description varchar(2028) null,
url varchar(2028) null,
timing varchar(255) not null,
last_run datetime null,
disabled int,
created_at datetime not null,
primary key (id)
)
")
go


-- myDBR triggers

if object_id('mydbr_reports_dtrig') is not null
drop trigger mydbr_reports_dtrig 
go
create trigger mydbr_reports_dtrig 
on mydbr_reports for delete
as
begin
delete mydbr_reports_priv
from deleted
where mydbr_reports_priv.report_id = deleted.report_id

delete mydbr_params
from deleted
where mydbr_params.proc_name = deleted.proc_name

delete mydbr_report_extensions
from deleted
where mydbr_report_extensions.proc_name = deleted.proc_name

delete mydbr_favourite_reports
from deleted
where mydbr_favourite_reports.report_id = deleted.report_id
    
end
go


if object_id('mydbr_userlogin_dtrig') is not null
drop trigger mydbr_userlogin_dtrig
go
create trigger mydbr_userlogin_dtrig 
on mydbr_userlogin for delete
as
begin
delete mydbr_reports_priv 
from deleted    
where mydbr_reports_priv.username = deleted.username and mydbr_reports_priv.authentication=deleted.authentication

delete mydbr_folders_priv 
from deleted    
where mydbr_folders_priv.username = deleted.username and mydbr_folders_priv.authentication=deleted.authentication

delete mydbr_groupsusers 
from deleted    
where mydbr_groupsusers.username = deleted.username and mydbr_groupsusers.authentication=deleted.authentication

delete mydbr_options 
from deleted    
where mydbr_options.username = deleted.username and mydbr_options.authentication=deleted.authentication

delete mydbr_favourite_reports
from deleted
where mydbr_favourite_reports.username = deleted.username and mydbr_favourite_reports.authentication=deleted.authentication

end
go


if object_id('mydbr_groups_dtrig') is not null
drop trigger mydbr_groups_dtrig 
go
create trigger mydbr_groups_dtrig 
on mydbr_groups for delete
as
begin
delete mydbr_reports_priv 
from deleted    
where mydbr_reports_priv.group_id = deleted.group_id

delete mydbr_folders_priv 
from deleted    
where mydbr_folders_priv.group_id = deleted.group_id

delete mydbr_groupsusers 
from deleted    
where mydbr_groupsusers.group_id = deleted.group_id
end
go


if object_id('mydbr_folders_dtrig') is not null
drop trigger mydbr_folders_dtrig
go
create trigger mydbr_folders_dtrig
on mydbr_folders for delete
as
begin

delete mydbr_folders_priv 
from deleted    
where mydbr_folders_priv.folder_id = deleted.folder_id

end
go


-- myDBR functions
-- We'll create function under name dbo to keep it compatible with both ASE and SQL Anywhere
if object_id('dbo.mydbr_style') is not null
drop function mydbr_style
go
create function dbo.mydbr_style( @inStyle varchar(150) )
returns varchar(400) 
as
begin
declare @vRet varchar(400)

select @vRet = definition
from dbo.mydbr_styles
where name = @inStyle

return @vRet
end
go

if object_id('dbo.fn_EndOfDay') is not null
drop function fn_EndOfDay
go
create function dbo.fn_EndOfDay(@inDate datetime)
returns datetime
as
begin
declare @ret datetime

set @ret=dateadd(ms,-5,dateadd(day,1,dateadd(ms,-datepart(ms,@inDate),dateadd(ss,-datepart(ss,@inDate),
    dateadd(mi,-datepart(mi,@inDate),dateadd(hh,-datepart(hh,@inDate),@inDate))))))
return @ret
end
go

if object_id('dbo.fn_BegOfDay') is not null
drop function fn_BegOfDay
go
create function dbo.fn_BegOfDay(@inDate datetime)
returns datetime
as
begin
return convert( date, @inDate)
end
go

/* Go around the diff in ASE & ASA */
if object_id('dbo.fn_YYYMMDD_us') is not null
drop function fn_YYYMMDD_us
go
create function dbo.fn_YYYMMDD_us(@inDate datetime)
returns varchar(10)
as
begin
return cast(datepart( year, @inDate ) as varchar)+'-'+
  right('0'+cast(datepart( month, @inDate) as varchar),2)+'-'+
  right('0'+cast(datepart( day, @inDate) as varchar),2)
end
go


-- myDBR procedures


if object_id('sp_MyDBR_LicensesGet') is not null
drop procedure sp_MyDBR_LicensesGet
go
create procedure sp_MyDBR_LicensesGet
as
begin
select id, owner, email, company, host, license_key, db, dbo.fn_YYYMMDD_us(expiration), type, version
from mydbr_licenses
order by type desc, expiration desc
end
go

if object_id('sp_MyDBR_LicensesAdd') is not null
drop procedure sp_MyDBR_LicensesAdd
go
create procedure sp_MyDBR_LicensesAdd
@Owner varchar(255), 
@Email varchar(255), 
@Company varchar(255), 
@Host varchar(255), 
@License_key varchar(80), 
@DB varchar(20), 
@Expiration datetime,
@Type varchar(255),
@Version varchar(255)
as
begin
declare @Cnt int
declare @ID int

select @Cnt = count(*)
from mydbr_licenses
where license_key = @License_key

if (@Cnt=0) begin
  select @ID = max(id)+1
  from mydbr_licenses

  select @ID = isnull(@ID, 1)

  insert into mydbr_licenses ( id, owner, email, company, host, license_key, db, expiration, type, version )
  values ( @ID, @Owner, @Email, @Company, @Host, @License_key, @DB, @Expiration, @Type, @Version)
end
end
go

if object_id('sp_MyDBR_LicensesDel') is not null
drop procedure sp_MyDBR_LicensesDel
go
create procedure sp_MyDBR_LicensesDel
@ID int
as
begin
delete 
from mydbr_licenses
where id=@ID
end
go


if object_id('sp_MyDBR_AmIAdminOut') is not null
drop procedure sp_MyDBR_AmIAdminOut
go
create procedure sp_MyDBR_AmIAdminOut
@inUsername varchar(128), 
@inAuth int,
@outAdmin int output
as
begin
declare @vAdmin int

select @vAdmin = admin
from mydbr_userlogin
where username = @inUsername and authentication=@inAuth

if (@vAdmin=1) begin
  set @outAdmin = 1
end else begin
  set @outAdmin = 0
end
end
go


if object_id('sp_MyDBR_AmIAdmin') is not null
drop procedure sp_MyDBR_AmIAdmin
go
create procedure sp_MyDBR_AmIAdmin(
@inUsername varchar(128),
@inAuth int
)
as
begin
declare @vAdmin int
declare @vName varchar(60)

exec sp_MyDBR_AmIAdminOut @inUsername, @inAuth, @vAdmin output

select @vName = name 
from mydbr_userlogin
where username = @inUsername and authentication=@inAuth

select @vAdmin, @vName
end
go


if object_id('sp_MyDBR_FolderDel') is not null
drop procedure sp_MyDBR_FolderDel
go
create procedure sp_MyDBR_FolderDel
@inFolderID int
as
begin
declare @vReportCnt int
declare @vFolderCnt int
declare @vFolderName varchar(100)

select @vFolderName = name
from mydbr_folders
where folder_id = @inFolderID

select  @vReportCnt = count(*) 
from mydbr_reports
where folder_id = @inFolderID

select @vFolderCnt = count(*)  
from mydbr_folders
where mother_id = @inFolderID

if (@vReportCnt+ @vFolderCnt >0) 
  select 'Folder "'+@vFolderName+'" is not empty. Cannot delete it.'
else begin
  delete 
  from mydbr_folders
  where folder_id = @inFolderID and folder_id not in (
    select folder_id from mydbr_reports
  )
  select 'OK'
end
end
go

if object_id('sp_MyDBR_FolderInfoGet') is not null
drop procedure sp_MyDBR_FolderInfoGet
go
create procedure sp_MyDBR_FolderInfoGet
@inFolderID int
as
begin
select name, reportgroup, explanation
from mydbr_folders
where folder_id=@inFolderID
end
go


if object_id('sp_MyDBR_FolderInfoSet') is not null
drop procedure sp_MyDBR_FolderInfoSet
go
create procedure sp_MyDBR_FolderInfoSet
@inFolderID int, 
@inFname varchar(100), 
@inReportgroup int,
@inExplanation varchar(255)
as
begin

update mydbr_folders
set name = @inFname, reportgroup = @inReportgroup, explanation=@inExplanation
where folder_id= @inFolderID

select 'OK'
end
go


if object_id('sp_MyDBR_FolderMove') is not null
drop procedure sp_MyDBR_FolderMove
go
create procedure sp_MyDBR_FolderMove
@vID int, 
@vFolder int
as
begin
declare @vMother int
declare @vMoveOK int

select @vMoveOK = 1, @vMother = @vFolder

while (@vMoveOK = 1 and @vMother is not null) begin
  select @vMother = mother_id
  from mydbr_folders
  where folder_id = @vMother
    
  if (@vMother = @vID or @vID = @vFolder) begin
    select @vMoveOK = 0
    select 'Cannot move folder into itself!'
  end
end

if (@vMoveOK=1) begin
  update mydbr_folders
  set mother_id=@vFolder
  where folder_id = @vID
end

end
go



if object_id('sp_MyDBR_FolderNew') is not null
drop procedure sp_MyDBR_FolderNew
go
create procedure sp_MyDBR_FolderNew
@inLevel int, 
@inFolder varchar(150), 
@inHiddenFolder int,
@inReportgroup int,
@inExplanation varchar(255)
as
begin

declare @folder_id int

select @folder_id = isnull(max(folder_id)+1,1)
from mydbr_folders

insert into mydbr_folders ( folder_id, mother_id, name, invisible, reportgroup, explanation )
select @folder_id, @inLevel, @inFolder, 2, @inReportgroup, @inExplanation

select 'OK', @folder_id

end
go


if object_id('sp_MyDBR_GroupAdd') is not null
drop procedure sp_MyDBR_GroupAdd
go
create procedure sp_MyDBR_GroupAdd
@inName varchar(100)
as
begin

declare @vCnt int
declare @vGroupID int

select @vCnt = count(*)
from mydbr_groups 
where name = @inName

if (@vCnt=0) begin
  select @vGroupID = max(group_id)
  from mydbr_groups

  insert into mydbr_groups ( group_id, name )
  select isnull(@vGroupID+1,1), @inName

  select 'OK', null
end
else begin
  select 'Error', 'Group "'+@inName+'" already exists'
end
end
go


if object_id('sp_MyDBR_GroupDel') is not null
drop procedure sp_MyDBR_GroupDel
go
create procedure sp_MyDBR_GroupDel
@inGroupID int
as
begin

declare @vCnt int

select @vCnt=count(*)
from mydbr_groupsusers 
where group_id = @inGroupID

if (@vCnt>0) begin
  select 'Error', '#{MYDBR_GROUP_CANNNOT_REM}'
end else begin
  delete from mydbr_groups
  where group_id = @inGroupID

  select 'OK', null
end

end
go


if object_id('sp_MyDBR_GroupGet') is not null
drop procedure sp_MyDBR_GroupGet
go
create procedure sp_MyDBR_GroupGet
as
begin
select group_id, name
from mydbr_groups
order by name
end
go


if object_id('sp_MyDBR_GroupLevelGet') is not null
drop procedure sp_MyDBR_GroupLevelGet
go
create procedure sp_MyDBR_GroupLevelGet
@inLevel int,
@isAdmin int,
@inUsername varchar(80), 
@inAuth int
as
begin
declare @vMother_id int
declare @vName varchar(100)
declare @vLevel_order int
declare @vLevelExists int

create table #TempTable ( 
folder_id int, 
name varchar(100),
level_order int,
no_priv tinyint
)

select @vLevelExists=count(*)
from mydbr_folders
where folder_id = @inLevel

if (@vLevelExists>0) begin
  select @vLevel_order = 1

  while( @inLevel is not null ) begin
    select @vMother_id = mother_id, @vName = name
    from mydbr_folders
    where folder_id = @inLevel

    insert into #TempTable values (@inLevel, @vName, @vLevel_order, 0)

    select @inLevel = @vMother_id
    select @vLevel_order = @vLevel_order + 1
  end
end else begin
  insert into #TempTable
  select 1, name, 1, 0
  from mydbr_folders
  where folder_id = 1
end

if (@isAdmin=0) begin
  update #TempTable
  set no_priv = 1
  where folder_id not in (
    select p.folder_id
    from #TempTable t, mydbr_folders_priv p
    where p.folder_id = t.folder_id and 
      ( ((p.username = @inUsername and p.authentication=@inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
      and p.group_id = 0 )
      or p.group_id in (
        select u.group_id
        from mydbr_groupsusers u
        where u.username = @inUsername and u.authentication= @inAuth
      )
  )
end

select folder_id, name, no_priv
from #TempTable
order by level_order desc

drop table #TempTable 
end
go


if object_id('sp_MyDBR_GroupNewUserAdd') is not null
drop procedure sp_MyDBR_GroupNewUserAdd
go
create procedure sp_MyDBR_GroupNewUserAdd
@inGroupID int, 
@inNameSearch varchar(128),
@inAuth int
as
begin
declare @vCnt int

select  @vCnt = count(*)
from mydbr_groupsusers m
where group_id = @inGroupID and username = @inNameSearch and authentication = @inAuth

if (@vCnt=0) begin
  insert into mydbr_groupsusers (group_id, username, authentication)
  values (@inGroupID, @inNameSearch, @inAuth)
end
end
go


if object_id('sp_MyDBR_GroupNewUserGet') is not null
drop procedure sp_MyDBR_GroupNewUserGet
go
create procedure sp_MyDBR_GroupNewUserGet
@inGroupID int,
@inNameSearch varchar(128),
@inAuth int
as
begin
declare @vAuth_DB int
declare @vAuth_myDBR int
declare @vAuth_SSO int
declare @vAuth_LDAP int

select @vAuth_DB = (@inAuth & 1)
select @vAuth_myDBR = (@inAuth & 2)
select @vAuth_SSO = (@inAuth & 4)
select @vAuth_LDAP = (@inAuth & 8)


create table #Users_tmp (
username varchar(128) not null,
name varchar(128) null,
auth_source int
)


if (@vAuth_DB > 0) begin
  insert into #Users_tmp ( username, name, auth_source )
  select u.name, suser_name(u.suid), @vAuth_DB
  from dbo.sysusers u
  where lower(u.name) like '%'+lower(@inNameSearch)+'%'
  and u.name not in (
    select username
    from mydbr_groupsusers
    where group_id = @inGroupID and authentication= @vAuth_DB
  )
end

if (@vAuth_myDBR > 0 or @vAuth_SSO > 0 or @vAuth_LDAP > 0 ) begin
  insert into #Users_tmp ( username, name, auth_source )
  select u.username, u.name, u.authentication
  from mydbr_userlogin u
  where (lower(u.username) like '%'+lower(@inNameSearch)+'%' or lower(u.name) like '%'+lower(@inNameSearch)+'%')
  and u.authentication in (2, 4, 8)
  and not exists (
    select *
    from mydbr_groupsusers m
    where m.username = u.username and m.authentication=u.authentication
    and group_id = @inGroupID
  )
end

select t.username, t.name, a.name, t.auth_source
from #Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask

drop table #Users_tmp
end
go

if object_id('sp_MyDBR_GroupUpdate') is not null
drop procedure sp_MyDBR_GroupUpdate
go
create procedure sp_MyDBR_GroupUpdate
@inGroupID int, 
@inName varchar(100)
as
begin
update mydbr_groups 
set name = @inName
where group_id = @inGroupID
end
go

if object_id('sp_MyDBR_GroupUsersDel') is not null
drop procedure sp_MyDBR_GroupUsersDel
go
create procedure sp_MyDBR_GroupUsersDel
@inGroupID int, 
@inUsername varchar(128),
@inAuth int
as
begin
delete 
from mydbr_groupsusers
where group_id = @inGroupID and username = @inUsername and authentication = @inAuth
end
go

if object_id('sp_MyDBR_GroupUsersDelUser') is not null
drop procedure sp_MyDBR_GroupUsersDelUser
go
CREATE PROCEDURE sp_MyDBR_GroupUsersDelUser
@inUsername varchar(128), 
@inAuth int
as
BEGIN

delete 
from mydbr_groupsusers
where username = @inUsername and authentication = @inAuth

END 
go


if object_id('sp_MyDBR_GroupUsersGet') is not null
drop procedure sp_MyDBR_GroupUsersGet
go
create procedure sp_MyDBR_GroupUsersGet
@inGroupID int, 
@inAuth int
as
begin
declare @vAuth_DB int
declare @vAuth_myDBR int
declare @vAuth_SSO int
declare @vAuth_LDAP int

select @vAuth_DB = (@inAuth & 1)
select @vAuth_myDBR = (@inAuth & 2)
select @vAuth_SSO = (@inAuth & 4)
select @vAuth_LDAP = (@inAuth & 8)

create table #Users_tmp (
username varchar(30) not null,
name varchar(60) null,
auth_source int
)

if (@vAuth_DB > 0) begin
  insert into #Users_tmp ( username, name, auth_source )
  select u.username, suser_name(s.suid), @vAuth_DB
  from mydbr_groupsusers u, dbo.sysusers s
  where group_id = @inGroupID and u.username=s.name and u.authentication=@vAuth_DB
end

if (@vAuth_myDBR > 0 or @vAuth_SSO > 0 or @vAuth_LDAP > 0) begin
  insert into #Users_tmp ( username, name, auth_source )
  select u.username, i.name, i.authentication
  from mydbr_groupsusers u, mydbr_userlogin i
  where u.username = i.username and u.group_id = @inGroupID and u.authentication=i.authentication
end

select t.username, t.name, a.name, t.auth_source
from #Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask

drop table #Users_tmp

end
go


if object_id('sp_MyDBR_ParamClear') is not null
drop procedure sp_MyDBR_ParamClear
go
create procedure sp_MyDBR_ParamClear
@procname sysname
as
begin
delete from mydbr_params
where proc_name=@procname
end
go

if object_id('sp_MyDBR_ParamDefaultGet') is not null
drop procedure sp_MyDBR_ParamDefaultGet
go
create procedure sp_MyDBR_ParamDefaultGet
@inProcname varchar(100)
as
begin
select p.param, m.query
from mydbr_params p, mydbr_param_queries m
where p.proc_name=@inProcname and p.default_value=m.name and p.default_value is not null
end
go


if object_id('sp_MyDBR_ParamDefaultsGet') is not null
drop procedure sp_MyDBR_ParamDefaultsGet
go
create procedure sp_MyDBR_ParamDefaultsGet
as
begin
select name, query, coltype
from mydbr_param_queries
where coltype = 3
order by name
end
go


if object_id('sp_MyDBR_ParamGet') is not null
drop procedure sp_MyDBR_ParamGet
go
create procedure sp_MyDBR_ParamGet
@procname sysname
as
begin
select param, query_name, title, default_value, isnull(optional,0), isnull(only_default,0), suffix, optionss
from mydbr_params
where proc_name=@procname
end
go

if object_id('sp_MyDBR_param_in_use') is not null
drop procedure sp_MyDBR_param_in_use
go
CREATE PROCEDURE sp_MyDBR_param_in_use(
@inName varchar(255)
)
as
begin

select r.report_id, p.proc_name, r.name, r.folder_id
from mydbr_params p
  join mydbr_reports r on r.proc_name=p.proc_name
where query_name=@inName or default_value=@inName

end
go

if object_id('sp_MyDBR_ParamQueriesGet') is not null
drop procedure sp_MyDBR_ParamQueriesGet
go
create procedure sp_MyDBR_ParamQueriesGet
@inAll tinyint
as
begin

if (@inAll=100) begin
  select q.name, q.query, q.coltype, q.optionss, count(distinct p.proc_name) as 'count'
  from mydbr_param_queries q
    left join mydbr_params p on q.coltype=3 and p.default_value=q.name or q.coltype!=3 and p.query_name = q.name
  where q.coltype < @inAll or (@inAll=3 and q.coltype>3)
  group by q.name, q.query, q.coltype, q.optionss
  order by q.name
end else begin
  select name, query, coltype, optionss
  from mydbr_param_queries
  where coltype < @inAll or (@inAll=3 and coltype>3)
  order by name
end

end
go


if object_id('sp_MyDBR_ParamQueryAdd') is not null
drop procedure sp_MyDBR_ParamQueryAdd
go
create procedure sp_MyDBR_ParamQueryAdd
@inName varchar(255), 
@inQuery varchar(3900),
@inColType tinyint,
@inOptions varchar(255)
as
begin
declare @vCnt int

select @vCnt = count(*) 
from mydbr_param_queries
where name = @inName

if (@vCnt=0) begin
  insert into mydbr_param_queries ( name, query, coltype, optionss )
  values ( @inName, @inQuery, @inColType, @inOptions )

  select 'OK', null
end 
else begin
  select 'Error', 'Parameter query named "'+@inName+'" already exists.'
end
end
go

if object_id('sp_MyDBR_ParamQueryDel') is not null
drop procedure sp_MyDBR_ParamQueryDel
go
create procedure sp_MyDBR_ParamQueryDel
@inName nvarchar(255)
as
begin
declare @vCnt int
declare @v_type int

select @v_type = coltype
from mydbr_param_queries
where name = @inName

if (@v_type=3) begin
  select @vCnt = count(*)
  from mydbr_params
  where default_value = @inName
end else begin
  select @vCnt = count(*)
  from mydbr_params
  where query_name = @inName
end
  
if (@vCnt = 0) begin
  delete from mydbr_param_queries
  where name = @inName

  select 'OK', null
end else begin
  select 'ERROR', 'Cannot delete parameter query in use!'
end
end
go


if object_id('sp_MyDBR_ParamQueryUpdate') is not null
drop procedure sp_MyDBR_ParamQueryUpdate
go
create procedure sp_MyDBR_ParamQueryUpdate
@inName varchar(255), 
@inQuery varchar(3900),
@inColType tinyint,
@inOptions varchar(255)
as
begin

update mydbr_param_queries
set query = @inQuery, coltype=@inColType, optionss=@inOptions
where name = @inName

select 'OK'
end
go

if object_id('sp_MyDBR_ParamSet') is not null
drop procedure sp_MyDBR_ParamSet
go
create procedure sp_MyDBR_ParamSet
@procname sysname,
@param sysname,
@query varchar(255),
@title varchar(80),
@inDefault varchar(255),
@inOptional tinyint,
@inOnly_default tinyint,
@inSuffix varchar(255),
@inOptions varchar(1024)
as
begin
insert into mydbr_params (proc_name, param, query_name, title, default_value, optional, only_default, suffix, optionss)
values (@procname, @param, @query, @title, @inDefault, @inOptional, @inOnly_default, @inSuffix, @inOptions)
end
go

if object_id('sp_MyDBR_ReportGetIDByName') is not null
drop procedure sp_MyDBR_ReportGetIDByName
go
create procedure sp_MyDBR_ReportGetIDByName
@procname sysname
as
begin
select report_id, name
from mydbr_reports
where proc_name = @procname
end
go

if object_id('sp_MyDBR_ProcedureParams') is not null
drop procedure sp_MyDBR_ProcedureParams
go
create procedure sp_MyDBR_ProcedureParams
@inProcName sysname
as
begin

declare @charsize int

create table #ReturnValuesTmp (
param_name varchar(128),
type varchar(30),
datalen smallint,
param_order smallint
)

insert into #ReturnValuesTmp
select substring(c.name,2, 128), t.name, c.length, c.colid
from dbo.syscolumns c, dbo.systypes t
where object_name(id) = @inProcName and c.usertype=t.usertype
union
/* For ASA. user_type is for datetime  */
select substring(p.parmname,2,128), isnull(p.user_type, p.parmdomain), p.length, p.parm_id
from SYSPROCPARMS p
where procname = @inProcName and parmtype=0 

update #ReturnValuesTmp set datalen=datalen/2
where lower(type) in ('unichar', 'univarchar')

/* Check if SQL anywhere */
select @charsize = count(*)
from dbo.sysobjects
where name='ISYSSPATIALREFERENCESYSTEM'

if (@charsize>0) begin
  select @charsize = 1
end else begin
  select @charsize = @@ncharsize
end

update #ReturnValuesTmp set datalen=datalen/@charsize
where lower(type) in ('nchar', 'nvarchar')

update #ReturnValuesTmp set type='string'
where lower(type) in ('char', 'nchar',  'ntext',  'nvarchar', 'sysname', 'text', 'timestamp', 'varchar', 'unichar', 'univarchar')

update #ReturnValuesTmp set type='integer'
where lower(type) in ('name', 'bigint', 'bit', 'int', 'smallint', 'tinyint')

update #ReturnValuesTmp set type='float'
where lower(type) in ('decimal', 'float', 'money', 'numeric', 'real', 'smallmoney')

update #ReturnValuesTmp set type='datetime'
where lower(type) in ('smalldatetime', 'datetimeoffset', 'datetime2')

select param_name, type, datalen
from #ReturnValuesTmp
order by param_order

end
go

if object_id('sp_MyDBR_ProcParams') is not null
drop procedure sp_MyDBR_ProcParams
go
create procedure sp_MyDBR_ProcParams
@inProc_id int,
@UseConverted tinyint
as
begin
declare @vProc_name varchar(128)
declare @charsize int

select @vProc_name = proc_name
from mydbr_reports
where report_id = @inProc_id

if (@vProc_name is null) begin
  select 'No such procedure' as 'param_name', 'error' as 'type', 0 as 'datalen'
  return
end

create table #ReturnValuesTmp (
param_name varchar(128),
type varchar(30),
datalen smallint,
param_order smallint
)

insert into #ReturnValuesTmp
/* For ASE */
select substring(c.name,2, 128), t.name, c.length, c.colid
from dbo.syscolumns c, dbo.systypes t
where object_name(id) = @vProc_name and c.usertype=t.usertype
union
/* For ASA. user_type is for datetime  */
select substring(p.parmname,2,128), isnull(p.user_type, p.parmdomain), p.length, p.parm_id
from SYSPROCPARMS p
where procname = @vProc_name and parmtype=0 

update #ReturnValuesTmp set datalen=datalen/2
where lower(type) in ('unichar', 'univarchar')

/* Check if SQL anywhere */
select @charsize = count(*)
from dbo.sysobjects
where name='ISYSSPATIALREFERENCESYSTEM'

if (@charsize>0) begin
  select @charsize = 1
end else begin
  select @charsize = @@ncharsize
end

update #ReturnValuesTmp set datalen=datalen/@charsize
where lower(type) in ('nchar', 'nvarchar')

update #ReturnValuesTmp set type='string'
where lower(type) in ('char', 'nchar',  'ntext',  'nvarchar', 'sysname', 'text', 'timestamp', 'varchar', 'unichar', 'univarchar')

update #ReturnValuesTmp set type='integer'
where lower(type) in ('name', 'bigint', 'bit', 'int', 'smallint', 'tinyint')

update #ReturnValuesTmp set type='float'
where lower(type) in ('decimal', 'float', 'money', 'numeric', 'real', 'smallmoney')

update #ReturnValuesTmp set type='datetime'
where lower(type) in ('smalldatetime', 'datetimeoffset', 'datetime2')

update #ReturnValuesTmp set type='date'
where lower(type) = 'datetime' and param_name like '%[_]todate'

if (@UseConverted=0) begin
  select param_name, type, datalen
  from #ReturnValuesTmp
  order by param_order
end 
else begin
  select t.param_name, t.type, t.datalen, p.title, p.query_name, t.param_order, isnull(p.optional,0), isnull(p.only_default,0), p.suffix, p.optionss
  from #ReturnValuesTmp t, mydbr_params p 
  where t.param_name = p.param and p.proc_name=@vProc_name
  union
  select t.param_name, t.type, t.datalen, null, null, t.param_order, 0, 0, null, null
  from #ReturnValuesTmp t
  where t.param_name not in (
   select p.param
   from mydbr_params p
   where p.proc_name=@vProc_name
  )
  order by 6
end

drop table #ReturnValuesTmp
end
go

if object_id('sp_MyDBR_ProcParamsName') is not null
drop procedure sp_MyDBR_ProcParamsName
go
create procedure sp_MyDBR_ProcParamsName
@inProcName sysname,
@UseConverted tinyint
as
begin

declare @vProc_id int
declare @vProcName varchar(150)

select @vProc_id = report_id, @vProcName = name
from mydbr_reports
where proc_name = @inProcName
 
if (@vProc_id is null) begin
  select 'No such procedure' as 'param_name', 'error' as 'type', 0 as 'datalen'
end
else begin
  select @vProcName

  exec sp_MyDBR_ProcParams @vProc_id, @UseConverted
end
end
go



if object_id('sp_MyDBR_ReportDel') is not null
drop procedure sp_MyDBR_ReportDel
go
create procedure sp_MyDBR_ReportDel
@inReportID int
as
begin
delete 
from mydbr_reports
where report_id = @inReportID
end
go


if object_id('sp_MyDBR_ReportInfoGet') is not null
drop procedure sp_MyDBR_ReportInfoGet
go
create procedure sp_MyDBR_ReportInfoGet
@inReportID int
as
begin
select name, proc_name, explanation, reportgroup, sortorder, runreport, autoexecute, parameter_help, export
from mydbr_reports
where report_id = @inReportID
end
go


if object_id('sp_MyDBR_ReportInfoSet') is not null
drop procedure sp_MyDBR_ReportInfoSet
go
create procedure sp_MyDBR_ReportInfoSet
@inReportID int,
@inReportName varchar(150), 
@inExplanation varchar(255),
@inReportgroup int,
@inSortorder int,
@inRunreport nvarchar(50),
@inAutoexecute tinyint,
@inParameter_help varchar(10000),
@inExport varchar(10)
as
begin
update mydbr_reports
set 
  name = @inReportName, explanation=@inExplanation, reportgroup=@inReportgroup, sortorder = @inSortorder, 
  runreport=@inRunreport, autoexecute=@inAutoexecute, parameter_help=@inParameter_help, export=@inExport
where report_id = @inReportID

select 'OK'
end
go

if object_id('sp_MyDBR_ReportIsValidForMe') is not null
drop procedure sp_MyDBR_ReportIsValidForMe
go
create procedure sp_MyDBR_ReportIsValidForMe
@inSPreport varchar(128),
@inUsername varchar(128),
@inAuth int
as
begin
declare @vIAmAdmin int

exec sp_MyDBR_AmIAdminOut @inUsername, @inAuth, @vIAmAdmin output

select 'OK', r.report_id, r.name
from mydbr_reports r
where r.proc_name = @inSPreport and (@vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = @inUsername  and p.authentication=@inAuth) or (p.username in ('PUBLIC', 'MYDBR_WEB') and p.authentication=0))
    and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.username = @inUsername and u.authentication=@inAuth and p.group_id != 0
))

end
go


if object_id('sp_MyDBR_ReportMove') is not null
drop procedure sp_MyDBR_ReportMove
go
create procedure sp_MyDBR_ReportMove
@vID int, 
@vFolder int
as
begin

update mydbr_reports
set folder_id=@vFolder
where report_id = @vID

end
go


if object_id('sp_MyDBR_ReportNameGet') is not null
drop procedure sp_MyDBR_ReportNameGet
go
create procedure sp_MyDBR_ReportNameGet
@InReport_id int,
@inUsername varchar(80),
@inAuth int,
@inProc_name varchar(128)
as
begin
declare @vProc_Name varchar(128)
declare @vReportName varchar(150)
declare @vHasPriv int
declare @vRunbutton varchar(50)
declare @vIAmAdmin int
declare @vFolder_id int
declare @vReport_id int
declare @vAutoexecute tinyint
declare @vParameter_help varchar(10000)
declare @vExport varchar(10)

exec sp_MyDBR_AmIAdminOut @inUsername, @inAuth, @vIAmAdmin output

select
  @vReportName = r.name, @vProc_Name = r.proc_name, @vFolder_id = r.folder_id, @vReport_id=r.report_id, 
  @vRunbutton=r.runreport, @vAutoexecute=r.autoexecute, @vParameter_help=r.parameter_help, @vExport=r.export
from mydbr_reports r
where (r.report_id=@InReport_id or r.proc_name=@inProc_name) and (@vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = @inUsername and p.authentication=@inAuth) or (p.username in ('PUBLIC', 'MYDBR_WEB') and p.authentication=0))
  and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.username = @inUsername and u.authentication=@inAuth and p.group_id != 0
))

if (@vProc_Name is null)
  select 0, 'No access privileges', null, 1, 0, null, null, null
else
  select 1, @vReportName, @vProc_Name, @vFolder_id, @vReport_id, @vRunbutton, @vAutoexecute, @vParameter_help, @vExport
end
go





if object_id('sp_MyDBR_ReportNew') is not null
drop procedure sp_MyDBR_ReportNew
go
create procedure sp_MyDBR_ReportNew
@inLevel int, 
@inReportName varchar(150), 
@inStored_proc sysname,
@inExplanation varchar(255),
@inReportgroup int,
@inSortorder int,
@inRunreport varchar(50)
as
begin
declare @vReport_id int

select @vReport_id = report_id 
from mydbr_reports
where proc_name = @inStored_proc and folder_id = @inLevel

if (@vReport_id>0) begin
  select 'OK', @vReport_id
end
else begin
  select @vReport_id = isnull(max(report_id)+1, 1)
  from mydbr_reports

  insert into mydbr_reports (report_id, name, proc_name, folder_id, explanation, reportgroup, sortorder, runreport )
  select @vReport_id, @inReportName, name, @inLevel, @inExplanation, @inReportgroup, @inSortorder, @inRunreport
  from dbo.sysobjects
  where lower(name)=lower(@inStored_proc)

  if (@@rowcount = 1)
    select 'OK', @vReport_id
  else
    select 'Procedure "'+@inStored_proc+'" does not exist in the database '+db_name(), 0
end
end
go


if object_id('sp_MyDBR_ReportNewGet') is not null
drop procedure sp_MyDBR_ReportNewGet
go
create procedure sp_MyDBR_ReportNewGet
@inProcname varchar(30),
@inProcPrefix varchar(10)
as
begin

if (@inProcname=' ') select @inProcname = null

select p.name 
from dbo.sysobjects p 
where p.name not like 'sp_MyDBR_%' and p.name like @inProcPrefix+'%' and p.name like '%'+@inProcname+'%' 
and p.name not in (
  select proc_name
  from mydbr_reports
)
order by crdate desc
end
go


if object_id('sp_MyDBR_ReportPrivAdd') is not null
drop procedure sp_MyDBR_ReportPrivAdd
go
create procedure sp_MyDBR_ReportPrivAdd
@inReportID int, 
@inUsername sysname,
@inAuth int,
@inGroupID int
as
begin

declare @vCnt int

select @vCnt = count(*)
from mydbr_reports_priv
where report_id=@inReportID and username=@inUsername and group_id= @inGroupID and authentication=@inAuth

if (@vCnt=0)
  insert into mydbr_reports_priv (report_id, username, authentication, group_id)
  values ( @inReportID, @inUsername, @inAuth, @inGroupID )
end
go

if object_id('sp_MyDBR_FolderPrivAdd') is not null
drop procedure sp_MyDBR_FolderPrivAdd
go
create procedure sp_MyDBR_FolderPrivAdd
@inFolderID int, 
@inUsername sysname,
@inAuth int,
@inGroupID int
as
begin

declare @vCnt int

select @vCnt = count(*)
from mydbr_folders_priv
where folder_id=@inFolderID and username=@inUsername and group_id= @inGroupID and authentication=@inAuth

if (@vCnt=0)
  insert into mydbr_folders_priv (folder_id, username, authentication, group_id)
  values ( @inFolderID, @inUsername, @inAuth, @inGroupID )
end
go


if object_id('sp_MyDBR_ReportPrivDel') is not null
drop procedure sp_MyDBR_ReportPrivDel
go
create procedure sp_MyDBR_ReportPrivDel
@inReportID int, 
@inUsername sysname,
@inAuth int,
@inGroupID int
as
begin

delete from mydbr_reports_priv
where report_id=@inReportID and username=@inUsername and group_id=@inGroupID and authentication=@inAuth

end
go

if object_id('sp_MyDBR_FolderPrivDel') is not null
drop procedure sp_MyDBR_FolderPrivDel
go
create procedure sp_MyDBR_FolderPrivDel
@inFolderID int, 
@inUsername sysname,
@inAuth int,
@inGroupID int
as
begin

delete from mydbr_folders_priv
where folder_id=@inFolderID and username=@inUsername and group_id=@inGroupID and authentication=@inAuth

end
go

if object_id('sp_MyDBR_ReportPrivsGroupGet') is not null
drop procedure sp_MyDBR_ReportPrivsGroupGet
go
create procedure sp_MyDBR_ReportPrivsGroupGet
@inReportID int
as
begin
select p.group_id, g.name, 1
from mydbr_reports_priv p, mydbr_groups g
where p.group_id>0 and p.report_id = @inReportID and p.group_id=g.group_id
union
select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select p.group_id
  from mydbr_reports_priv p
  where p.group_id>0 and p.report_id = @inReportID
)
order by 3 desc, 2
end
go

if object_id('sp_MyDBR_FolderPrivsGroupGet') is not null
drop procedure sp_MyDBR_FolderPrivsGroupGet
go
create procedure sp_MyDBR_FolderPrivsGroupGet
@inFolderID int
as
begin
select p.group_id, g.name, 1
from mydbr_folders_priv p, mydbr_groups g
where p.group_id>0 and p.folder_id = @inFolderID and p.group_id=g.group_id
union
select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select p.group_id
  from mydbr_folders_priv p
  where p.group_id>0 and p.folder_id = @inFolderID
)
order by 3 desc, 2
end
go

if object_id('sp_MyDBR_ReportPrivGroupNewGet') is not null
drop procedure sp_MyDBR_ReportPrivGroupNewGet
go
create procedure sp_MyDBR_ReportPrivGroupNewGet
@inReportID int
as
begin

select g.group_id, g.name
from mydbr_groups g
where g.group_id not in (
  select p.group_id 
  from mydbr_reports_priv p
  where p.report_id = @inReportID
)

end
go

if object_id('sp_MyDBR_ReportPrivsUserGet') is not null
drop procedure sp_MyDBR_ReportPrivsUserGet
go
create procedure sp_MyDBR_ReportPrivsUserGet
@inReportID int, 
@inAuth int,
@inSearch varchar(30)
as
begin
declare @vAuth_DB int
declare @vAuth_myDBR int
declare @vAuth_SSO int
declare @vAuth_LDAP int
declare @vAuth_Custom int

select @vAuth_DB = (@inAuth & 1)
select @vAuth_myDBR = (@inAuth & 2)
select @vAuth_SSO = (@inAuth & 4)
select @vAuth_LDAP = (@inAuth & 8)
select @vAuth_Custom = (@inAuth & 16)

create table #Users_tmp (
username varchar(30) not null,
name varchar(60) null,
auth_source int,
haspriv int
)

if (@vAuth_DB > 0) begin
  insert into #Users_tmp ( username, name, auth_source, haspriv )
  select p.username, '', @vAuth_DB, 1
  from dbo.sysusers u, mydbr_reports_priv p
  where p.username!='' and p.username = u.name and p.report_id = @inReportID and p.authentication = @vAuth_DB
    
  if (@inSearch!='') begin
    set rowcount 20
    insert into #Users_tmp ( username, name, auth_source, haspriv )
    select u.name, '', @vAuth_DB, 0
    from dbo.sysusers u
    where lower(u.name) like '%'+lower(@inSearch)+'%' and not exists (
      select * 
      from mydbr_reports_priv p
      where p.username = u.name and p.report_id = @inReportID and p.authentication= @vAuth_DB
    )
    set rowcount 0
  end  
end

if (@vAuth_myDBR > 0 or @vAuth_SSO > 0 or @vAuth_LDAP > 0 or @vAuth_Custom > 0) begin
  insert into #Users_tmp ( username, name, auth_source, haspriv )
  select p.username, u.name, u.authentication, 1
  from mydbr_userlogin u, mydbr_reports_priv p
  where p.username!='' and p.username = u.username and p.report_id = @inReportID and p.authentication=u.authentication
    and p.authentication in (2,4,8,16)
    
  if (@inSearch!='') begin
    set rowcount 20
    insert into #Users_tmp ( username, name, auth_source, haspriv )
    select u.username, u.name, u.authentication, 0
    from mydbr_userlogin u
    where (lower(u.username) like '%'+lower(@inSearch)+'%' or lower(u.name) like '%'+lower(@inSearch)+'%') and not exists (
      select * 
      from mydbr_reports_priv p
      where p.username = u.username and p.report_id = @inReportID and p.authentication= u.authentication
        and p.authentication in (2,4,8,16)
      )
    set rowcount 0
  end
end

select t.username, t.name, a.name, t.auth_source, t.haspriv
from #Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask
union
select p.username, null, null, 0, 1
from mydbr_reports_priv p
where p.report_id = @inReportID and p.authentication=0 and p.username in ('PUBLIC', 'MYDBR_WEB')
order by 5 desc, 1

drop table #Users_tmp
end
go

if object_id('sp_MyDBR_FolderPrivsUserGet') is not null
drop procedure sp_MyDBR_FolderPrivsUserGet
go
create procedure sp_MyDBR_FolderPrivsUserGet
@inFolderID int, 
@inAuth int,
@inSearch nvarchar(30)
as
begin
declare @vAuth_DB int
declare @vAuth_myDBR int
declare @vAuth_SSO int
declare @vAuth_LDAP int
declare @vAuth_Custom int

select @vAuth_DB = (@inAuth & 1)
select @vAuth_myDBR = (@inAuth & 2)
select @vAuth_SSO = (@inAuth & 4)
select @vAuth_LDAP = (@inAuth & 8)
select @vAuth_Custom = (@inAuth & 16)

create table #Users_tmp (
username nvarchar(30) not null,
name nvarchar(60) default null,
auth_source int,
haspriv int
)

if (@vAuth_DB > 0) begin
  insert into #Users_tmp ( username, name, auth_source, haspriv )
  select p.username, '', @vAuth_DB, 1
  from dbo.sysusers u, mydbr_folders_priv p
  where p.username!='' and p.username = u.name and p.folder_id = @inFolderID and p.authentication = @vAuth_DB

  if (@inSearch!='') begin
    set rowcount 20
    insert into #Users_tmp ( username, name, auth_source, haspriv )
    select u.name, '', @vAuth_DB, 0
    from dbo.sysusers u
    where lower(u.name) like '%'+lower(@inSearch)+'%' and not exists (
      select * 
      from mydbr_folders_priv p
      where p.username = u.name and p.folder_id = @inFolderID and p.authentication= @vAuth_DB
    )
  set rowcount 0
  end
end

if (@vAuth_myDBR > 0 or @vAuth_SSO > 0 or @vAuth_LDAP > 0 or @vAuth_Custom > 0) begin
  insert into #Users_tmp ( username, name, auth_source, haspriv )
  select p.username, u.name, u.authentication, 1
  from mydbr_userlogin u, mydbr_folders_priv p
  where p.username!='' and p.username = u.username and p.folder_id = @inFolderID and p.authentication=u.authentication
    and p.authentication in (2,4,8,16)

  if (@inSearch!='') begin
    set rowcount 20
    insert into #Users_tmp ( username, name, auth_source, haspriv )
    select u.username, u.name, u.authentication, 0
    from mydbr_userlogin u
    where (lower(u.username) like '%'+lower(@inSearch)+'%' or lower(u.name) like '%'+lower(@inSearch)+'%') and not exists (
      select * 
      from mydbr_folders_priv p
      where p.username = u.username and p.folder_id = @inFolderID and p.authentication= u.authentication
        and p.authentication in (2,4,8,16)
    )
    set rowcount 0
  end
end

select t.username, t.name, a.name, t.auth_source, t.haspriv
from #Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask
union
select p.username, null, null, 0, 1
from mydbr_folders_priv p
where p.folder_id = @inFolderID and p.authentication=0 and p.username in ('PUBLIC')
order by 5 desc, 1

drop table #Users_tmp
end
go

if object_id('sp_MyDBR_FolderHavePrivs') is not null
drop procedure sp_MyDBR_FolderHavePrivs
go
create procedure sp_MyDBR_FolderHavePrivs
@inLevel int, 
@inUsername nvarchar(80), 
@inAuth int
as
begin
declare @vIAmAdmin int

exec sp_MyDBR_AmIAdminOut @inUsername, @inAuth, @vIAmAdmin output

if (@vIAmAdmin = 1) begin
  select 1
end else begin
  select count(*)
  from mydbr_folders_priv p
  where p.folder_id = @inLevel and 
    ( ((p.username = @inUsername and p.authentication=@inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
    and p.group_id = 0 )
    or p.group_id in (
      select u.group_id
      from mydbr_groupsusers u
      where u.username = @inUsername and u.authentication= @inAuth
    )
end

end
go

if object_id('sp_MyDBR_ReportsShow') is not null
drop procedure sp_MyDBR_ReportsShow
go
create procedure sp_MyDBR_ReportsShow
@inLevel int, 
@inUsername varchar(80), 
@inAuth int
as
begin
declare @vIAmAdmin int

exec sp_MyDBR_AmIAdminOut @inUsername, @inAuth, @vIAmAdmin output

select  
  f.folder_id as 'folderID', 
  null as 'report_id', 
  f.name as 'name', 
  0 as 'hasgrant', 
  f.explanation, 
  1 as 'isReport',
  f.reportgroup as 'reportgroup', 
  g.sortorder,
  g.name as 'gname',
  g.color as 'color',
  '',
  0 as 'rsortorder',
  null as 'directurl',
  0 as 'notinuse',
  null as 'export'
from mydbr_folders f, mydbr_reportgroups g
where f.reportgroup=g.id and f.mother_id=@inLevel and (@vIAmAdmin = 1 or f.folder_id in (
  select p.folder_id
  from mydbr_folders_priv p
  where (p.group_id = 0 and 
    ((p.username = @inUsername and p.authentication=@inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
  )
  or p.group_id in (
    select u.group_id
    from mydbr_groupsusers u
    where u.username = @inUsername and u.authentication= @inAuth
  )
))
union
select
  null, 
  r.report_id, 
  r.name, 
  0, 
  r.explanation, 
  0,
  r.reportgroup, 
  g.sortorder, 
  g.name, 
  g.color,
  r.proc_name,
  r.sortorder,
  null,
  0,
  r.export
from mydbr_reports r, mydbr_reportgroups g
where r.reportgroup=g.id and r.folder_id = @inLevel and (@vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = @inUsername and p.authentication=@inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
  and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.username = @inUsername and u.authentication=@inAuth and p.group_id != 0
))
union
select
  null, 
  r.report_id, 
  r.name, 
  0, 
  r.explanation, 
  0,
  g.id, 
  g.sortorder, 
  g.name, 
  g.color,
  r.proc_name,
  r.sortorder,
  f.url,
  0,
  r.export
from mydbr_reports r, mydbr_reportgroups g, mydbr_favourite_reports f
where f.username=@inUsername and f.authentication=@inAuth and f.report_id=r.report_id
and g.id=-1 and @inLevel=1
and (@vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = @inUsername and p.authentication=@inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
  and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.username = @inUsername and u.authentication=@inAuth and p.group_id != 0
))
order by 8, 7, 6, 12, 3

end
go

if object_id('sp_MyDBR_ReportsShow_Privs') is not null
drop procedure sp_MyDBR_ReportsShow_Privs
go
create procedure sp_MyDBR_ReportsShow_Privs(
@inLevel int, 
@inUsername nvarchar(80), 
@inAuth int
)
as
begin

create table #tmp_report_ids (
report_id int
)

create table #tmp_report_result (
report_id int,
type varchar(20),
name varchar(128)
)

create table #tmp_folder_result (
folder_id int,
type varchar(20),
name nvarchar(128)
)

insert into #tmp_report_ids (report_id)
select r.report_id
from mydbr_reports r
where r.folder_id = @inLevel 
union
select r.report_id
from mydbr_reports r, mydbr_reportgroups g, mydbr_favourite_reports f
where f.username=@inUsername and f.authentication=@inAuth and f.report_id=r.report_id
and g.id=-1 and @inLevel=1

insert into #tmp_report_result 
select p.report_id, 'user', u.name
from mydbr_reports_priv p, mydbr_userlogin u
where p.username = u.username and p.authentication=u.authentication
and p.report_id in (
  select report_id
  from #tmp_report_ids
)

insert into #tmp_folder_result 
select p.folder_id, 'user', u.name
from mydbr_folders_priv p, mydbr_userlogin u, mydbr_folders f
where p.username = u.username and p.authentication=u.authentication
and p.folder_id = f.folder_id and f.mother_id=@inLevel

insert into #tmp_report_result 
select p.report_id, 'group', g.name
from mydbr_reports_priv p, mydbr_groups g
where p.group_id = g.group_id
and p.report_id in (
  select report_id
  from #tmp_report_ids
)

insert into #tmp_folder_result 
select p.folder_id, 'group', g.name
from mydbr_folders_priv p, mydbr_groups g, mydbr_folders f
where p.group_id = g.group_id
and p.folder_id = f.folder_id and f.mother_id=@inLevel

insert into #tmp_report_result 
select p.report_id, 'public', p.username
from mydbr_reports_priv p
where p.username in  ('PUBLIC', 'MYDBR_WEB') and p.authentication=0
and p.report_id in (
  select report_id
  from #tmp_report_ids
)

insert into #tmp_folder_result 
select p.folder_id, 'public', p.username
from mydbr_folders_priv p, mydbr_folders f
where p.username in  ('PUBLIC') and p.authentication=0
and p.folder_id = f.folder_id and f.mother_id=@inLevel

create table #tmp_sort (
type varchar(10),
sort_order int
)

insert into #tmp_sort values ('user', 1)
insert into #tmp_sort values ('group', 2)
insert into #tmp_sort values ('public', 3)


select 'report', t.report_id, t.type, t.name, s.sort_order
from #tmp_report_result t
  left outer join #tmp_sort s on s.type=t.type
union
select 'folder', t.folder_id, t.type, t.name, s.sort_order
from #tmp_folder_result t
  left outer join #tmp_sort s on s.type=t.type
order by 1,2,5,4

drop table #tmp_report_result
drop table #tmp_report_ids
drop table #tmp_folder_result 

end
go

if object_id('sp_MyDBR_StatReportIDGet') is not null
drop procedure sp_MyDBR_StatReportIDGet
go
create procedure sp_MyDBR_StatReportIDGet
as
begin

select report_id 
from mydbr_reports
where proc_name = 'sp_DBR_StatisticsReport'

end
go


if object_id('sp_MyDBR_Stat_AddEnd') is not null
drop procedure sp_MyDBR_Stat_AddEnd
go
create procedure sp_MyDBR_Stat_AddEnd 
@inID int
as
begin

update mydbr_statistics
set end_time = getdate()
where id = @inID

end
go



if object_id('sp_MyDBR_Stat_AddStart') is not null
drop procedure sp_MyDBR_Stat_AddStart
go
create procedure sp_MyDBR_Stat_AddStart
@inProc_name varchar(100),
@inUsername varchar(128),
@inAuthentication int,
@inQuery varchar(6000),
@inIPAddress varchar(255), 
@inUserAgent varchar(6000)
as
begin
declare @vCnt int
declare @vUserAgentHash varchar(50)

declare @vStart_time datetime
declare @id int

select @vUserAgentHash = hash(@inUserAgent, 'md5')

select @vCnt = count(*)
from mydbr_user_agents
where hashvalue = @vUserAgentHash

if (@vCnt=0) begin
  insert into mydbr_user_agents ( hashvalue, user_agent)
  values (@vUserAgentHash, @inUserAgent)
end

select @vStart_time = getdate()

select @id = max(id)
from mydbr_statistics

insert into mydbr_statistics ( id, proc_name, username, authentication, start_time, query, ip_address, user_agent_hash )
values (isnull(@id+1,1), @inProc_name, @inUsername, @inAuthentication, @vStart_time, @inQuery, @inIPAddress, @vUserAgentHash )

select isnull(@id+1,1)
end
go



if object_id('sp_MyDBR_StyleAdd') is not null
drop procedure sp_MyDBR_StyleAdd
go
create procedure sp_MyDBR_StyleAdd
@inName varchar(30), 
@inType varchar(20), 
@inDef varchar(255)
as
begin
declare @vCnt int
declare @vColType int

select @vCnt = count(*) 
from mydbr_styles
where name = @inName

if (@vCnt=0) begin
  if (@inType='column')
    select @vColType = 0
  else
    select @vColType = 1

  insert into mydbr_styles ( name, colstyle, definition )
  values ( @inName, @vColType, @inDef )

  select 'OK', null
end 
else begin
  select 'Error', 'Style "'+@inName+'" already exists.'
end
end
go


if object_id('sp_MyDBR_StyleDel') is not null
drop procedure sp_MyDBR_StyleDel
go
create procedure sp_MyDBR_StyleDel
@inName varchar(100)
as
begin

delete from mydbr_styles
where name = @inName

select 'OK', null

end
go


if object_id('sp_MyDBR_StyleGet') is not null
drop procedure sp_MyDBR_StyleGet
go
create procedure sp_MyDBR_StyleGet
as
begin
select 
  name, 
  case 
    when colstyle = 0 then 'column' 
    else 'row'
  end, 
  definition
from mydbr_styles
end
go


if object_id('sp_MyDBR_StyleUpdate') is not null
drop procedure sp_MyDBR_StyleUpdate
go
create procedure sp_MyDBR_StyleUpdate
@inName varchar(30), 
@inType varchar(20), 
@inDef varchar(255)
as
begin
declare @vColType int

if (@inType='column')
  select @vColType = 0
else
  select @vColType = 1

update mydbr_styles
set colstyle=@vColType, definition = @inDef
where name = @inName

select 'OK'
end
go



if object_id('sp_MyDBR_Usage') is not null
drop procedure sp_MyDBR_Usage
go
create procedure sp_MyDBR_Usage
as
begin

declare @dummy int
/*
Not supported for now. Would require parsing the code from syscomments which is not fun

select c.ROUTINE_DEFINITION, c.ROUTINE_TYPE, r.proc_name, r.name
from mydbr_reports r, information_schema.routines c
where c.SPECIFIC_NAME=r.proc_name and c.ROUTINE_CATALOG=DB_NAME()
*/
end
go


if object_id('sp_MyDBR_UserDel') is not null
drop procedure sp_MyDBR_UserDel
go
create procedure sp_MyDBR_UserDel
@inUser varchar(128),
@inAuth int
as
begin
delete 
from mydbr_userlogin 
where username = @inUser and authentication=@inAuth
end
go


if object_id('sp_MyDBR_UserLogins') is not null
drop procedure sp_MyDBR_UserLogins
go
create procedure sp_MyDBR_UserLogins( @inDays int )
as
begin
select u.username, u.name, u.admin, u.email, u.telephone, dateadd( day, @inDays, u.passworddate ), a.name, u.authentication
from mydbr_userlogin u, mydbr_authentication a
where u.authentication=a.mask
order by u.name
end
go

if object_id('sp_MyDBR_UserLoginsAuth') is not null
drop procedure sp_MyDBR_UserLoginsAuth
go
create procedure sp_MyDBR_UserLoginsAuth( @inAuth int )
as
begin
select u.username, u.name
from mydbr_userlogin u
where u.authentication=@inAuth
order by u.name
end
go

if object_id('sp_MyDBR_UserInfo') is not null
drop procedure sp_MyDBR_UserInfo
go
create procedure sp_MyDBR_UserInfo ( 
@inUsername nvarchar(128), 
@inAuth int 
)
as
begin

select u.name, u.email, u.telephone
from mydbr_userlogin u
where u.username = @inUsername and u.authentication = @inAuth

end 
go

if object_id('sp_MyDBR_UserInfoSet') is not null
drop procedure sp_MyDBR_UserInfoSet
go
create procedure sp_MyDBR_UserInfoSet ( 
@inUsername nvarchar(128), 
@inAuth int,
@inName varchar(60),
@inEmail varchar(100),
@inTelephone varchar(100)
)
as
begin

update mydbr_userlogin
set name = @inName, email = @inEmail, telephone = @inTelephone
where username = @inUsername and authentication = @inAuth

end 
go


if object_id('sp_MyDBR_UserNew') is not null
drop procedure sp_MyDBR_UserNew
go
create procedure sp_MyDBR_UserNew
@inUser varchar(128),
@inName varchar(60), 
@inPassword varchar(255), 
@inAdmin tinyint,
@inEmail varchar(100),
@inTelephone varchar(100),
@inAuth int,
@inAskPwChange int
as
begin
declare @vExists int

select @vExists = count(*)
from mydbr_userlogin
where username = @inUser and authentication=@inAuth

if (@vExists=0) begin
  insert into mydbr_userlogin ( username, password, name, admin, passworddate, email, telephone, authentication, ask_pw_change )
  values ( @inUser, @inPassword, @inName, @inAdmin, getdate(), @inEmail, @inTelephone, @inAuth, @inAskPwChange )

  select 'OK'
end else begin
  select 'User "'+@inUser+'" already exists'
end

end
go

if object_id('sp_MyDBR_user_groups') is not null
drop procedure sp_MyDBR_user_groups
go
CREATE PROCEDURE sp_MyDBR_user_groups( 
@inUser varchar(128), 
@inAuth int
)
as
begin

select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select gu.group_id
  from mydbr_groupsusers gu
  where gu.username=@inUser and gu.authentication=@inAuth
)
union
select g.group_id, g.name, 1
from mydbr_groups g
  join mydbr_groupsusers gu on g.group_id=gu.group_id
where gu.username=@inUser and gu.authentication=@inAuth
order by 2

end
go

if object_id('sp_MyDBR_sso_user') is not null
drop procedure sp_MyDBR_sso_user
go
if object_id('sp_MyDBR_ext_user') is not null
drop procedure sp_MyDBR_ext_user
go
create procedure sp_MyDBR_ext_user
@inUser varchar(128), 
@inName varchar(60),
@inEmail varchar(100),
@inTelephone varchar(100),
@inAdmin int,
@inAuth int
as
begin

declare @vExists int
declare @vName varchar(60)
declare @vEmail varchar(100)
declare @vAdmin int
declare @vTelephone varchar(100)

select @vExists = 1, @vName = name, @vEmail=email, @vTelephone=telephone, @vAdmin=admin
from mydbr_userlogin
where username = @inUser and authentication=@inAuth
    
if ( @vExists = 1 ) begin
  /* email & admin can be null */
  select @inEmail = isnull( @inEmail, @vEmail )
  select @inTelephone = isnull( @inTelephone, @vTelephone )
  select @inAdmin = isnull( @inAdmin, @vAdmin )

  if (@vName!=@inName or isnull(@inEmail,'')!=isnull(@vEmail,'') or isnull(@inTelephone,'')!=isnull(@vTelephone,'') or @inAdmin!=@vAdmin) begin
    update mydbr_userlogin
    set name = @inName, email=@inEmail, telephone=@inTelephone, admin=@inAdmin
    where username = @inUser and authentication=@inAuth
    end
end
else begin
  select @inAdmin = isnull( @inAdmin, 0 )
  exec sp_MyDBR_UserNew @inUser, @inName, 'no_direct_access', @inAdmin, @inEmail, @inTelephone, @inAuth, 0
end

end
go


if object_id('sp_MyDBR_sso_user_group_clear') is not null
drop procedure sp_MyDBR_sso_user_group_clear
go
if object_id('sp_MyDBR_ext_user_group_clear') is not null
drop procedure sp_MyDBR_ext_user_group_clear
go
create procedure sp_MyDBR_ext_user_group_clear
@inUser varchar(128),
@inAuth int
as
begin

delete from mydbr_groupsusers
where username=@inUser and authentication=@inAuth

end
go

if object_id('sp_MyDBR_sso_user_group') is not null
drop procedure sp_MyDBR_sso_user_group
go
if object_id('sp_MyDBR_ext_user_group') is not null
drop procedure sp_MyDBR_ext_user_group
go
create procedure sp_MyDBR_ext_user_group 
@inUser varchar(128),
@inGroup varchar(100),
@inClear int,
@inAuth int
as
begin

declare @vExists int
declare @vGroupID int

if (@inClear=1) begin
  exec sp_MyDBR_ext_user_group_clear @inUser, @inAuth
end

select @vExists = count(*)
from mydbr_groups
where name = @inGroup

if (@vExists = 0) begin
  select @vGroupID = max(group_id)
  from mydbr_groups

  insert into mydbr_groups ( group_id, name )
  select isnull(@vGroupID+1,1), @inGroup
end

insert into mydbr_groupsusers ( group_id, username, authentication )
select group_id, @inUser, @inAuth
from mydbr_groups
where name = @inGroup

end
go


if object_id('sp_MyDBR_UserPassword') is not null
drop procedure sp_MyDBR_UserPassword
go
create procedure sp_MyDBR_UserPassword
@inUsername varchar(128),
@inExpiration int
as
begin

select username, convert(varchar(255), password), dateadd( day, @inExpiration, isnull(passworddate, getdate()) ), ask_pw_change
from mydbr_userlogin 
where username= @inUsername and authentication=2

end
go

if object_id('sp_MyDBR_UserUpd') is not null
drop procedure sp_MyDBR_UserUpd
go
create procedure sp_MyDBR_UserUpd
@inUser varchar(30), 
@inName varchar(60), 
@inPassword varchar(255),
@inAdmin tinyint,
@inEmail varchar(100),
@inTelephone varchar(100),
@inAuth int,
@inAskPwChange int
as
begin

declare @vPass varchar(255), @passdate datetime

if (@inPassword is not null) begin
  set @vPass = @inPassword
  select @passdate = getdate()
end


update mydbr_userlogin
set 
  password = isnull( @vPass, password ), 
  passworddate = isnull( @passdate , passworddate ),
  name = isnull( @inName, name), 
  admin=isnull(@inAdmin , admin ),
  email = isnull( @inEmail, email ),
  telephone = isnull( @inTelephone, telephone),
  ask_pw_change = isnull( @inAskPwChange, ask_pw_change )
where username = @inUser and authentication=@inAuth

if (@vPass is not null and @inAuth=2) begin
  delete 
  from mydbr_password_reset
  where user = @inUser
end

end
go

if object_id('sp_MyDBR_UserUpdUser') is not null
drop procedure sp_MyDBR_UserUpdUser
go
create procedure sp_MyDBR_UserUpdUser
@inUser varchar(30), 
@inPassword varchar(255),
@inAuth int
as
begin

update mydbr_userlogin
set 
  password = @inPassword, 
  passworddate = getdate(),
  ask_pw_change = 0
where username = @inUser and authentication=@inAuth

if (@inAuth=2) begin
  delete 
  from mydbr_password_reset
  where username=@inUser
end

end
go

if object_id('sp_MyDBR_LinkedReport') is not null
drop procedure sp_MyDBR_LinkedReport
go
create procedure sp_MyDBR_LinkedReport
@Name varchar(30)
as
begin

set rowcount 20

select proc_name, name
from mydbr_reports 
where name like '%'+@Name+'%' or proc_name like '%'+@Name+'%'

set rowcount 0

end
go


if object_id('sp_MyDBR_NotificationGet') is not null
drop procedure sp_MyDBR_NotificationGet
go
create procedure sp_MyDBR_NotificationGet ( @inID int )
as
begin
select notification
from mydbr_notifications
where id=@inID
end
go


if object_id('sp_MyDBR_NotificationSet') is not null
drop procedure sp_MyDBR_NotificationSet
go
create procedure sp_MyDBR_NotificationSet (
@inID int, 
@inNotification varchar(2048) 
)
as
begin

update mydbr_notifications 
set notification = @inNotification
where id=@inID

if (@@rowcount = 0) begin
  insert into mydbr_notifications (id, notification)
  values (@inID, @inNotification)
end

end
go


if object_id('sp_MyDBR_ReportExtClean') is not null
drop procedure sp_MyDBR_ReportExtClean
go
create procedure sp_MyDBR_ReportExtClean( @inProcName varchar(100) )
as
begin
delete 
from mydbr_report_extensions
where proc_name=@inProcName
end
go

if object_id('sp_MyDBR_ReportExtAdd') is not null
drop procedure sp_MyDBR_ReportExtAdd
go
create procedure sp_MyDBR_ReportExtAdd (
@inProcName varchar(100), 
@inExtension varchar(100) 
)
as
begin
insert into mydbr_report_extensions (proc_name, extension)
values (@inProcName, @inExtension)
end
go

if object_id('sp_MyDBR_ReportExtGet') is not null
drop procedure sp_MyDBR_ReportExtGet
go
create procedure sp_MyDBR_ReportExtGet( @inProcName varchar(100) )
as
begin
select extension
from mydbr_report_extensions
where proc_name=@inProcName
end
go

if object_id('sp_MyDBR_ReportExtGetByID') is not null
drop procedure sp_MyDBR_ReportExtGetByID
go
create procedure sp_MyDBR_ReportExtGetByID( @inReportID int )
as
begin

select e.extension
from mydbr_reports r, mydbr_report_extensions e
where r.proc_name=e.proc_name and r.report_id=@inReportID

end
go

if object_id('sp_MyDBR_MyReportCnt') is not null
drop procedure sp_MyDBR_MyReportCnt
go
create procedure sp_MyDBR_MyReportCnt( @outReport int output)
as
begin
select @outReport=count(*)
from mydbr_reports 
where proc_name not in ('sp_DBR_StatisticsSummary', 'sp_DBR_StatisticsReport')
end
go


if object_id('sp_MyDBR_MyReportCount') is not null
drop procedure sp_MyDBR_MyReportCount
go
create procedure sp_MyDBR_MyReportCount
as
begin
declare @vReportCount int

exec sp_MyDBR_MyReportCnt @vReportCount output

select @vReportCount
end
go


-- checks for demo data, return values:
--  -1 : User has created own reports, don't show create demo link
--   0 : No demo data exists, show "Create demo link"
--   1 : Demo data exists, show "Remove demo link"
if object_id('sp_MyDBR_checkDemo') is not null
drop procedure sp_MyDBR_checkDemo
go
create procedure sp_MyDBR_checkDemo( @inShowCreate int )
as
begin
declare @vReportCount int
declare @vDemoCount int

select @vDemoCount=count(*) 
from mydbr_reports 
where proc_name like 'sp_DBR_demo_%'

if ( @vDemoCount > 0) begin
  select 1
end 
else begin 
  if (@inShowCreate=1) begin
    select 0
  end 
  else begin 
    exec sp_MyDBR_MyReportCnt @vReportCount output

    if ( @vReportCount > 0 )
      select -1
    else 
      select 0
  end
end

end
go


if object_id('sp_MyDBR_GetLatestVersion') is not null
drop procedure sp_MyDBR_GetLatestVersion
go
create procedure sp_MyDBR_GetLatestVersion
as
begin

select latest_version, next_check, download_link, info_link, last_successful_check, signature
from mydbr_update

end
go

if object_id('sp_MyDBR_SetLatestVersion') is not null
drop procedure sp_MyDBR_SetLatestVersion
go
create procedure sp_MyDBR_SetLatestVersion(
@inLatestVersion varchar(10), 
@inNextCheck int, 
@inDownloadLink varchar(200), 
@inInfoLink varchar(200),
@inLast_successful_check int,
@inSignature varchar(50)
)
as
begin

delete from mydbr_update

insert into mydbr_update (latest_version, next_check, download_link, info_link, last_successful_check, signature) 
values( @inLatestVersion, @inNextCheck, @inDownloadLink, @inInfoLink, @inLast_successful_check, @inSignature )

end
go


if object_id('sp_MyDBR_Log') is not null
drop procedure sp_MyDBR_Log
go
create procedure sp_MyDBR_Log( @inUser varchar(128), @inIP varchar(40), @inTitle varchar(30), @inMsg varchar(2000) )
as
begin
insert into mydbr_log ( username, log_ip, log_time, log_title, log_message )
values (  @inUser, @inIP, getdate(), @inTitle, @inMsg )
end
go


if object_id('sp_MyDBR_GetOptions') is not null
drop procedure sp_MyDBR_GetOptions
go
create procedure sp_MyDBR_GetOptions ( 
@inUsername varchar(128), 
@inAuthentication int
)
as
begin

select o1.name, o1.value 
from mydbr_options o1
where o1.username = @inUsername and o1.authentication = @inAuthentication or ( o1.username = '' and o1.authentication = 0 
  and not exists ( 
    select * 
    from mydbr_options o2 
    where o2.name = o1.name and o2.username = @inUsername and o2.authentication = @inAuthentication 
  )
)
order by o1.name

end
go


if object_id('sp_MyDBR_SetOption') is not null
drop procedure sp_MyDBR_SetOption
go
create procedure sp_MyDBR_SetOption (
@inUsername varchar(128),
@inAuthentication int, 
@inName varchar(30), 
@inValue varchar(512)
)
as
begin

delete 
from mydbr_options 
where username = @inUsername and authentication = @inAuthentication and name = @inName

insert into mydbr_options (username, authentication, name, value) 
values ( @inUsername, @inAuthentication, @inName, @inValue )

end
go

if object_id('sp_MyDBR_options_reset') is not null
drop procedure sp_MyDBR_options_reset
go
create procedure sp_MyDBR_options_reset (
@inUsername varchar(128),
@inAuthentication int
)
as
begin

delete 
from mydbr_options 
where username = @inUsername and authentication = @inAuthentication

end
go

if object_id('sp_MyDBR_sproc_exists') is not null
drop procedure sp_MyDBR_sproc_exists
go
CREATE PROCEDURE sp_MyDBR_sproc_exists(
@inProcName sysname
)
as
begin

select isnull(object_id(@inProcName), 0)

end
go


if object_id('sp_MyDBR_IsWebReport') is not null
drop procedure sp_MyDBR_IsWebReport
go
create procedure sp_MyDBR_IsWebReport (
@inReportID int
)
as
begin

select count(*)
from mydbr_reports_priv p
where p.report_id = @inReportID and p.authentication=0 and p.username='MYDBR_WEB'

end
go

if object_id('sp_MyDBR_IsWebReportName') is not null
drop procedure sp_MyDBR_IsWebReportName
go
create procedure sp_MyDBR_IsWebReportName (
@vName sysname
)
as
begin

select count(*)
from mydbr_reports_priv p
  join mydbr_reports r on p.report_id=r.report_id
where (r.proc_name = @vName or hash(cast(r.proc_name as varchar(100)), 'md5')=cast(@vName as varchar(100)))
  and p.authentication=0 and p.username='MYDBR_WEB'
end
go

if object_id('sp_MyDBR_report_from_hash') is not null
drop procedure sp_MyDBR_report_from_hash
go
create procedure sp_MyDBR_report_from_hash (
@vHash varchar(100)
)
as
begin

select r.proc_name
from mydbr_reports r
where hash(r.proc_name, 'md5')=@vHash

end
go


if object_id('sp_MyDBR_Reportgroups') is not null
drop procedure sp_MyDBR_Reportgroups
go
create procedure sp_MyDBR_Reportgroups
as
begin

select id, name, sortorder, color
from mydbr_reportgroups
order by sortorder

end
go

if object_id('sp_MyDBR_Reportgroup_set') is not null
drop procedure sp_MyDBR_Reportgroup_set
go
create procedure sp_MyDBR_Reportgroup_set
@inId int, 
@inName varchar(128), 
@inSortorder int, 
@inColor char(6)
as
begin

if (@inId<-1) begin
  insert into mydbr_reportgroups (name, sortorder, color) 
  values (@inName, @inSortorder, @inColor)
end 
else begin
  update mydbr_reportgroups
  set 
    name=@inName,
    sortorder=@inSortorder,
    color=@inColor
    where id=@inId
end

end
go

if object_id('sp_MyDBR_Reportgroup_del') is not null
drop procedure sp_MyDBR_Reportgroup_del
go
create procedure sp_MyDBR_Reportgroup_del
@inId int
as
begin

delete 
from mydbr_reportgroups
where id=@inId and @inId>1

if (@@error!=0 or @inId<=1) begin
  select 'Cannot delete category in use!'
end

end
go


if object_id('sp_MyDBR_db_dbs') is not null
drop procedure sp_MyDBR_db_dbs
go
create procedure sp_MyDBR_db_dbs
as
begin

select name 
from master..sysdatabases
where name not in ('tempdb', 'model')

end
go

if object_id('sp_MyDBR_db_objects') is not null
drop procedure sp_MyDBR_db_objects
go
create procedure sp_MyDBR_db_objects (
@inDB sysname
)
as
begin
declare @sql varchar(1000)

select @sql = 'select name, ''T'', 1'
select @sql = @sql+' from '+@inDB+'..sysobjects'
select @sql = @sql+' where type in (''U'', ''V'')'
select @sql = @sql+' union'
select @sql = @sql+' select name, substring(type, 1,1), 2'
select @sql = @sql+' from '+@inDB+'..sysobjects'
select @sql = @sql+' where type in (''P'', ''FN'')'
select @sql = @sql+' order by 3, 2 desc, 1'

execute(@sql)

end
go

if object_id('sp_MyDBR_db_objects_anywhere') is not null
drop procedure sp_MyDBR_db_objects_anywhere
go
create procedure sp_MyDBR_db_objects_anywhere (
@inDB sysname
)
as
begin

select name, 'T', 1
from dbo.sysobjects
where type in ('U', 'V')
union
select name, substring(type, 1,1), 2
from dbo.sysobjects
where type in ('P', 'FN')
order by 3, 2 desc, 1

end
go


if object_id('sp_MyDBR_db_columns') is not null
drop procedure sp_MyDBR_db_columns
go
create procedure sp_MyDBR_db_columns(
@inDB sysname,
@inTable sysname
)
as
begin

declare @sql varchar(1000)

select @sql =
"select c.name, t.name+"+ 
  "case when t.name in ('numeric', 'decimal') then '('+cast(c.prec as varchar)+','+cast(c.scale as varchar)+')' else null end+"+
  "case when right(t.name, 4) = 'char' then  '('+cast(c.length as varchar)+')' else null end "+
"from "+@inDB+"..syscolumns c, "+@inDB+"..systypes t "+
"where c.usertype=t.usertype "+
"and c.id=object_id('"+@inTable+"')"

execute(@sql)
end
go

if object_id('sp_MyDBR_db_columns_anywhere') is not null
drop procedure sp_MyDBR_db_columns_anywhere
go
create procedure sp_MyDBR_db_columns_anywhere(
@inDB sysname,
@inTable sysname
)
as
begin

select c.name, t.name+
  case when t.name in ('numeric', 'decimal') then '('+cast(c.prec as varchar)+','+cast(c.scale as varchar)+')' else null end+
  case when right(t.name, 4) = 'char' then  '('+cast(c.length as varchar)+')' else null end
from dbo.syscolumns c, dbo.systypes t
where c.usertype=t.usertype
and c.id=object_id(@inTable)

end
go


if object_id('sp_MyDBR_table_reference') is not null
drop procedure sp_MyDBR_table_reference
go
create procedure sp_MyDBR_table_reference(
@inDB sysname,
@inTable sysname
)
as
begin
declare @sql varchar(1000)

/*
We'll skip this for now. Rewrite for system tables required
*/
end
go

if object_id('sp_MyDBR_report_info') is not null
drop procedure sp_MyDBR_report_info
go
create procedure sp_MyDBR_report_info(
@inProcName sysname
)
as
begin

declare @vName varchar(150)
declare @vExplanation varchar(255)
declare @vFolderID int
declare @vFolderIDPrev int
declare @vFName varchar(100)
declare @vPath varchar(1000)
declare @vSep varchar(6)
declare @vStop int
declare @vID int

select @vName = r.name, @vExplanation = r.explanation, @vFolderID = r.folder_id, @vID = r.report_id
from mydbr_reports r
where r.proc_name=@inProcName

if (@vName is not null) begin
  select @vPath = ''
  select @vSep = ''
  select @vStop = 0
  while( @vFolderID is not null and @vStop!=100 ) begin
    select @vFolderIDPrev = @vFolderID

    select @vFName = name, @vFolderID = mother_id
    from mydbr_folders
    where folder_id=@vFolderID

    select @vPath = '<a href="index.php?m='+convert(varchar(10), @vFolderIDPrev )+'">'+@vFName+'</a>'+@vSep+@vPath
    select @vStop = @vStop +1
    select @vSep = ' &gt; '
  end

  select @vName, @vExplanation, @vPath, @vID
end
end
go

if object_id('sp_MyDBR_locale_formats') is not null
drop procedure sp_MyDBR_locale_formats
go
create procedure sp_MyDBR_locale_formats( 
@inLocale char(5) 
)
as
begin

select date_format, time_format, thousand_separator, decimal_separator
from mydbr_languages
where lower(lang_locale) = lower(@inLocale)

end
go

if object_id('sp_MyDBR_languages') is not null
drop procedure sp_MyDBR_languages
go
create procedure sp_MyDBR_languages
as
begin
select lang_locale, language
from mydbr_languages
order by language
end
go

if object_id('sp_MyDBR_localization') is not null
drop procedure sp_MyDBR_localization
go
create procedure sp_MyDBR_localization (
@in_lang_locales varchar(255) 
)
as
begin
select lang_locale, keyword, translation
from mydbr_localization
where @in_lang_locales like '%'+lang_locale+'%'
end
go

if object_id('sp_MyDBR_localization_get') is not null
drop procedure sp_MyDBR_localization_get
go
create procedure sp_MyDBR_localization_get (
@inKeyword varchar(50)
)
as
begin
select lang_locale, translation
from mydbr_localization
where keyword = @inKeyword
end
go

if object_id('sp_MyDBR_localization_set') is not null
drop procedure sp_MyDBR_localization_set
go
create procedure sp_MyDBR_localization_set (
@inKeyword varchar(50),
@inLangLocale varchar(50),
@inTranslation varchar(1024)
)
as
begin
declare @vCnt int

if (@inTranslation='') begin
  delete from mydbr_localization
  where keyword = @inKeyword and lang_locale = @inLangLocale
end 
else begin
  select @vCnt = count(*)
  from mydbr_localization
  where keyword = @inKeyword and lang_locale = @inLangLocale

  if (@vCnt=0) begin
    insert into mydbr_localization ( keyword, lang_locale, translation, creation_date )
    values (@inKeyword, @inLangLocale, @inTranslation, getdate() )
  end else begin 
    update mydbr_localization
    set translation = @inTranslation, creation_date = getdate()
    where keyword = @inKeyword and lang_locale =@inLangLocale
  end
end
end
go

if object_id('sp_MyDBR_localization_cnt') is not null
drop procedure sp_MyDBR_localization_cnt
go
create procedure sp_MyDBR_localization_cnt
as
begin
select keyword, count(*)
from mydbr_localization
group by keyword
end
go


if object_id('sp_MyDBR_report_copy') is not null
drop procedure sp_MyDBR_report_copy
go
create procedure sp_MyDBR_report_copy(
@inOriginal varchar(100),
@inNew varchar(100)
)
as
begin

declare @vOriginalID int
declare @vNewID int
declare @vName varchar(150)
declare @vCnt int
declare @vCntAll int

select @vOriginalID=report_id, @vName=name
from mydbr_reports
where proc_name = @inOriginal

select @vNewID = report_id
from mydbr_reports
where proc_name = @inNew

select @vCnt = count(*)
from dbo.sysobjects c 
where name=@inNew and type='P'

select @vCntAll = 0

/* Do we have both procedures? */
if (@vCnt!=1 or @vNewID is not null or @vOriginalID is null) begin
    select @vCntAll = -1
end

/* Make sure the new one is really a new one */
if (@vCntAll=0) begin
  select @vCnt = count(*)
  from mydbr_reports
  where proc_name = @inNew

  select @vCntAll = @vCntAll+@vCnt

  select @vCnt = count(*)
  from mydbr_params
  where proc_name = @inNew

  select @vCntAll = @vCntAll + @vCnt

  select @vCnt = count(*)
  from mydbr_report_extensions
  where proc_name = @inNew

  select @vCntAll = @vCntAll + @vCnt

  select @vCnt = count(*)
  from mydbr_reports_priv
  where report_id = @vNewID

  set @vCntAll = @vCntAll + @vCnt
end

if (@vCntAll=0) begin
  begin tran
    select @vNewID = isnull(max(report_id)+1, 1)
    from mydbr_reports
    
    insert into mydbr_reports ( report_id, name, proc_name, folder_id, explanation, reportgroup, sortorder, 
        autoexecute, parameter_help, export )
    select @vNewID, name+' +', @inNew, folder_id, explanation, reportgroup, sortorder, autoexecute, parameter_help, export
    from mydbr_reports
    where report_id = @vOriginalID
    
    insert into mydbr_params ( proc_name, param, query_name, title, default_value, optional, only_default, suffix, optionss )
    select @inNew, param, query_name, title, default_value, optional, only_default, suffix, optionss
    from mydbr_params
    where proc_name = @inOriginal

    insert into mydbr_report_extensions( proc_name, extension )
    select @inNew, extension
    from mydbr_report_extensions
    where proc_name = @inOriginal

    insert into mydbr_reports_priv( report_id, username, group_id, authentication )
    select @vNewID, username, group_id, authentication
    from mydbr_reports_priv
    where report_id = @vOriginalID
  commit tran

  select @vNewID
end else begin
  select 0
end
end
go


if object_id('sp_MyDBR_favourites') is not null
drop procedure sp_MyDBR_favourites
go
CREATE PROCEDURE sp_MyDBR_favourites (
@inUser varchar(128),
@inAuthentication int
)
as
BEGIN

select f.report_id, r.name, f.url, f.id, r.explanation
from mydbr_favourite_reports f, mydbr_reports r
where f.report_id=r.report_id
  and f.username=@inUser and f.authentication=@inAuthentication

END
go

if object_id('sp_MyDBR_favourite_del') is not null
drop procedure sp_MyDBR_favourite_del
go
CREATE PROCEDURE sp_MyDBR_favourite_del (
@inUser varchar(128),
@inAuthentication int,
@inFavID int
)
as
BEGIN

delete from mydbr_favourite_reports
where username = @inUser and authentication=@inAuthentication and id=@inFavID

select 'not_set'
END
go


if object_id('sp_MyDBR_favourite_set') is not null
drop procedure sp_MyDBR_favourite_set
go
CREATE PROCEDURE sp_MyDBR_favourite_set (
@inUser varchar(128),
@inAuthentication int,
@inReportID int,
@inUrl varchar(512)
)
as
BEGIN
declare @vRet varchar(10)
declare @vCnt int

select @vCnt = count(*)
from mydbr_favourite_reports
where username = @inUser and authentication=@inAuthentication and report_id = @inReportID and isnull(url, '') = isnull(@inUrl, '')

if (@vCnt>0) begin
  set @vRet = 'not_set'

  delete from mydbr_favourite_reports
  where username = @inUser and authentication=@inAuthentication and report_id = @inReportID and isnull(url, '') = isnull(@inUrl, '')
end 
else begin
  set @vRet = 'set'

  insert into mydbr_favourite_reports ( username, authentication, report_id, url )
  values (@inUser, @inAuthentication, @inReportID, @inUrl)
end

select @vRet
END
go

if object_id('sp_MyDBR_remote_srv_ins') is not null
drop procedure sp_MyDBR_remote_srv_ins
go
create procedure sp_MyDBR_remote_srv_ins(
@inId int,
@inServer varchar(128),
@inUrl varchar(255),
@inHash varchar(40),
@inUsername varchar(128),
@inPassword varchar(128)
)
as
begin
declare @vCnt int

select @vCnt = count(*)
from mydbr_remote_servers
where server = @inServer

if (@vCnt=0) begin
  insert into mydbr_remote_servers ( server, url, hash, username, password )
  values ( @inServer, @inUrl, @inHash, @inUsername, @inPassword )
end

end
go

if object_id('sp_MyDBR_remote_srv_del') is not null
drop procedure sp_MyDBR_remote_srv_del
go
create procedure sp_MyDBR_remote_srv_del(
@inId int
)
as
begin

delete 
from mydbr_remote_servers
where id=@inId

end
go

if object_id('sp_MyDBR_remote_srv_upd') is not null
drop procedure sp_MyDBR_remote_srv_upd
go
create procedure sp_MyDBR_remote_srv_upd(
@inId int,
@inServer varchar(128),
@inUrl varchar(255),
@inHash varchar(40),
@inUsername varchar(128),
@inPassword varchar(128)
)
as
begin

update mydbr_remote_servers
set 
  server=@inServer,
  url=@inUrl,
  hash=@inHash,
  username=@inUsername,
  password=@inPassword
where id=@inId

end
go

if object_id('sp_MyDBR_remote_srv_sel_all') is not null
drop procedure sp_MyDBR_remote_srv_sel_all
go
create procedure sp_MyDBR_remote_srv_sel_all
as
begin

select id, server, url, hash, username, password
from mydbr_remote_servers

end
go


if object_id('sp_MyDBR_has_unattached_report') is not null
drop procedure sp_MyDBR_has_unattached_report
go
create procedure sp_MyDBR_has_unattached_report(
@in_proc varchar(128)
)
as
begin

declare @vCnt int

select @vCnt = count(*)
from mydbr_reports
where proc_name=@in_proc

if (@vCnt=0) begin
  select @vCnt = count(*)
  from dbo.sysobjects c 
  where name=@in_proc and type='P'

  if (@vCnt>0) begin
    select 1
  end else begin
    select 0
  end
end else begin
  select 0
end

end
go

if object_id('sp_MyDBR_template_folder') is not null
drop procedure sp_MyDBR_template_folder
go
create procedure sp_MyDBR_template_folder(
@inId int
)
as
begin
declare @v_order int
declare @v_cnt int

create table #folders_tmp (
id int,
dorder int
)

set @v_order=0

while( @inId>0 ) begin
  insert into #folders_tmp values ( @inId, @v_order )

  set @v_order = @v_order + 1

  select @inId = parent_id
  from mydbr_template_folders
  where id =  @inId

  select @v_cnt = count(*)
  from #folders_tmp
  where id = @inId

  if (@v_cnt>0) begin
    set @inId = -1
  end
end

select f.id, f.name, t.dorder
from mydbr_template_folders f 
  join #folders_tmp t on t.id=f.id
order by t.dorder

end
go

if object_id('sp_MyDBR_template_set_sync') is not null
drop procedure sp_MyDBR_template_set_sync
go
create procedure sp_MyDBR_template_set_sync(
@inName varchar(128),
@inHeader varchar(3900),
@inRow varchar(3900),
@inFooter varchar(3900)
)
as
begin

declare @vCnt int

select @vCnt = count(*)
from mydbr_templates
where name = @inName

if (@vCnt=0) begin
  insert into mydbr_templates ( name, header, row, footer, folder_id, creation_date )
  values ( @inName, @inHeader, @inRow, @inFooter, 1, getdate() )
end else begin
  update mydbr_templates
  set 
    header = @inHeader,
    row = @inRow,
    footer = @inFooter,
    creation_date = getdate()
  where name = @inName
end

end
go

if object_id('sp_MyDBR_template_set') is not null
drop procedure sp_MyDBR_template_set
go
create procedure sp_MyDBR_template_set(
@inId int,
@inName varchar(128),
@inHeader varchar(3900),
@inRow varchar(3900),
@inFooter varchar(3900),
@inFolder_id int
)
as
begin

declare @vCnt int

select @vCnt=count(*)
from mydbr_templates
where id != @inId and name = @inName

if (@vCnt>0) begin
  select 0
  return
end

select @vCnt=count(*)
from mydbr_templates
where id = @inId and isnull(@inId,0)!=0

if (@vCnt=0) begin
  insert into mydbr_templates ( name, header, row, footer, folder_id, creation_date )
  values ( @inName, @inHeader, @inRow, @inFooter, @inFolder_id, getdate() )
end else begin
  update mydbr_templates
  set 
    name = @inName, 
    header = @inHeader,
    row = @inRow,
    footer = @inFooter,
    folder_id = @inFolder_id,
    creation_date = getdate()
  where id = @inId
end

select 1

end
go


if object_id('sp_MyDBR_templates_get') is not null
drop procedure sp_MyDBR_templates_get
go
create procedure sp_MyDBR_templates_get(@inID int)
as
begin

select p.id, p.name, 'folder_up', 1
from mydbr_template_folders f
  join mydbr_template_folders p on p.id=f.parent_id
where f.id=@inID
union
select id, name, 'folder', 2
from mydbr_template_folders
where parent_id=@inID
union
select id, name, 'template', 3
from mydbr_templates
where folder_id=@inID
order by 4, 2

end
go

if object_id('sp_MyDBR_template_get') is not null
drop procedure sp_MyDBR_template_get
go
create procedure sp_MyDBR_template_get(
@inId int
)
as
begin

select header, row, footer
from mydbr_templates
where id=@inId

end
go


if object_id('sp_MyDBR_template_get_name') is not null
drop procedure sp_MyDBR_template_get_name
go
create procedure sp_MyDBR_template_get_name(
@inName varchar(128)
)
as
begin

select header, row, footer
from mydbr_templates
where name=@inName

end
go



if object_id('sp_MyDBR_template_del') is not null
drop procedure sp_MyDBR_template_del
go
create procedure sp_MyDBR_template_del(
@inId int
)
as
begin

delete 
from mydbr_templates
where id=@inId

end
go

if object_id('sp_MyDBR_template_folder_del') is not null
drop procedure sp_MyDBR_template_folder_del
go
create procedure sp_MyDBR_template_folder_del(
@inId int
)
as
begin

declare @v_cnt int

select @v_cnt=count(*)
from mydbr_template_folders
where parent_id=@inId

delete 
from mydbr_template_folders
where id=@inId and @v_cnt=0 and id not in (
  select folder_id
  from mydbr_templates
)

end
go

if object_id('sp_MyDBR_template_move') is not null
drop procedure sp_MyDBR_template_move
go
create procedure sp_MyDBR_template_move(
@in_id int,
@in_folder_id int
)
as
begin

update mydbr_templates
set folder_id = @in_folder_id
where id=@in_id

end
go

if object_id('sp_MyDBR_template_folder_move') is not null
drop procedure sp_MyDBR_template_folder_move
go
create procedure sp_MyDBR_template_folder_move(
@in_id int,
@in_folder_id int
)
as
begin

update mydbr_template_folders
set parent_id = @in_folder_id
where id=@in_id

end
go

if object_id('sp_MyDBR_template_folder_new') is not null
drop procedure sp_MyDBR_template_folder_new
go
create procedure sp_MyDBR_template_folder_new(
@in_parent_id int,
@in_name varchar(128)
)
as
begin

insert into mydbr_template_folders (name, parent_id)
select @in_name, id
from mydbr_template_folders
where id=@in_parent_id

end
go

if object_id('sp_MyDBR_template_folder_ren') is not null
drop procedure sp_MyDBR_template_folder_ren
go
create procedure sp_MyDBR_template_folder_ren(
@in_id int,
@in_name varchar(128)
)
as
begin

update mydbr_template_folders 
set name = @in_name
where id = @in_id

end
go

if object_id('sp_MyDBR_pw_reset_options_get') is not null
drop procedure sp_MyDBR_pw_reset_options_get
go
create procedure sp_MyDBR_pw_reset_options_get
as
begin

select name, value
from  mydbr_options 
where name like 'password_reset%' or name like 'mail_%'

end
go


if object_id('sp_MyDBR_user_find') is not null
drop procedure sp_MyDBR_user_find
go
create procedure sp_MyDBR_user_find(
@inNameSearch varchar(100),
@inExpiration int
)
as
begin

set rowcount 40

select u.username, u.name, u.authentication, a.name
from mydbr_userlogin u
  join mydbr_authentication a on u.authentication=a.mask
where (lower(u.username) like '%'+lower(@inNameSearch)+'%' or lower(u.name) like '%'+lower(@inNameSearch)+'%')
and (@inExpiration=0 or dateadd( day, @inExpiration, u.passworddate ) >= getdate() )
and u.admin=0

set rowcount 0
end
go

if object_id('sp_MyDBR_password_reset_token') is not null
drop procedure sp_MyDBR_password_reset_token
go
create procedure sp_MyDBR_password_reset_token( 
@in_user varchar(128),
@in_email varchar(128),
@in_allow_admin_change int,
@in_ip_address varchar(255)
)
as
begin

declare @v_cnt int
declare @v_user varchar(128)
declare @v_email varchar(128)
declare @v_perishable varchar(20)

select @v_cnt = count(*)
from mydbr_userlogin 
where username = isnull(@in_user, username) and isnull(email, '')=isnull(@in_email, isnull(email, '')) and authentication = 2 and (@in_allow_admin_change=1 or admin=0)


if (@v_cnt>1) begin
  select 'multiemail', null, null
end else begin
  select @v_user = username, @v_email = email
  from mydbr_userlogin 
  where username = isnull(@in_user, username) and isnull(email, '')=isnull(@in_email, isnull(email, '')) and authentication = 2 and (@in_allow_admin_change=1 or admin=0)

  if (@v_user is null) begin
    select null, null, null
  end else begin
    if (@v_email='' or @v_email is null) begin
      select 'noemail', null, null
    end else begin
      select @v_perishable=substring(hash(convert(varchar(30), rand())+convert(varchar(30), getdate(), 109)+@v_user, 'sha1' ), 1, 20)

      delete from mydbr_password_reset where username = @v_user

      insert into mydbr_password_reset ( username, perishable_token, request_time, ip_address ) 
      values ( @v_user, @v_perishable, getdate(), @in_ip_address )

      select @v_perishable, name, email
      from mydbr_userlogin 
      where username = @v_user and authentication = 2
    end
  end
end

end
go


if object_id('sp_MyDBR_pw_reset_user_get') is not null
drop procedure sp_MyDBR_pw_reset_user_get
go
create procedure sp_MyDBR_pw_reset_user_get( 
@in_token varchar(255), 
@in_timeout int 
)
as
begin

declare @v_user_id nvarchar(128)
declare @v_cnt int

select @v_user_id = max(username), @v_cnt = count(*)
from mydbr_password_reset
where perishable_token = @in_token and dateadd( minute, @in_timeout, request_time ) > getdate()

if (@v_cnt=1) begin
  select pr.username, u.email
  from mydbr_password_reset pr
    join mydbr_userlogin u on u.username=pr.username and u.authentication=2
  where perishable_token = @in_token
end

-- Clean old requests
delete
from mydbr_password_reset
where dateadd( minute, @in_timeout, request_time ) < getdate()

end
go



IF object_id('sp_MyDBR_editor_hint') IS NOT NULL
DROP PROCEDURE sp_MyDBR_editor_hint
GO
create procedure sp_MyDBR_editor_hint( 
@in_db varchar(512)
)
as
begin
declare @sql varchar(512)
declare @db varchar(512)

create table #dbs (
name sysname
)

SET @sql = 'insert into #dbs select name from master..sysdatabases where name in ('+@in_db+')'

execute(@sql)

create table #db_columns (
db_name sysname,
table_name sysname,
column_name sysname
)

declare db_cursor cursor for 
select name 
FROM #dbs

open db_cursor

fetch next db_cursor 
into @db

while @@fetch_status = 0 begin

set @sql = "insert into #db_columns select '"+@db+"', o.name, c.name "+
  "from ["+@db+"]..syscolumns c "+
  "  join ["+@db+"]..sysobjects o on o.id=c.id "+
  "where o.type in ('U', 'V') "+
  "order by o.name"

  execute(@sql)
  
  fetch next db_cursor into @db
end

select db_name, table_name, column_name
from #db_columns

end
go

IF object_id('sp_MyDBR_editor_hint_anywhere') IS NOT NULL
DROP PROCEDURE sp_MyDBR_editor_hint_anywhere
GO
create procedure sp_MyDBR_editor_hint_anywhere( 
@in_db varchar(512)
)
as
begin

select db_name(), o.name, c.name 
from dbo.syscolumns c
 join dbo.sysobjects o on o.id=c.id
where o.type in ('U', 'V')
order by o.name,  c.colid

end
go


if object_id('sp_MyDBR_snippets_get') IS NOT NULL
drop procedure sp_MyDBR_snippets_get
go
create procedure sp_MyDBR_snippets_get
as
begin

select id, name, code, shortcut, cright, cdown
from mydbr_snippets

end
go

if object_id('sp_MyDBR_snippets_save') IS NOT NULL
drop procedure sp_MyDBR_snippets_save
go
create procedure sp_MyDBR_snippets_save( 
@in_id int,
@in_name varchar(30),
@in_code varchar(2000)
)
as
begin

declare @v_cnt int
declare @id int

select @v_cnt=count(*)
from mydbr_snippets
where id = @in_id

if (@v_cnt>0) begin
  update mydbr_snippets
  set name = @in_name, code = @in_code
  where id = @in_id
end else begin
  insert into mydbr_snippets (name, code, cright, cdown)
  values (@in_name, @in_code, 0, 0)
  
  select max(id)
  from mydbr_snippets
end

end
go

if object_id('sp_MyDBR_snippets_delete') IS NOT NULL
drop procedure sp_MyDBR_snippets_delete
go
create procedure sp_MyDBR_snippets_delete( 
@in_id int
)
as
begin

delete 
from mydbr_snippets
where id = @in_id

end
go

if object_id('sp_MyDBR_snippets_shortcut') IS NOT NULL
drop procedure sp_MyDBR_snippets_shortcut
go
create procedure sp_MyDBR_snippets_shortcut( 
@in_id int,
@in_shortcut varchar(20)
)
as
begin

update mydbr_snippets
set shortcut = @in_shortcut
where id = @in_id

end
go

if object_id('sp_MyDBR_snippets_save_move') IS NOT NULL
drop procedure sp_MyDBR_snippets_save_move
go
create procedure sp_MyDBR_snippets_save_move( 
@in_id int,
@in_right int,
@in_down int
)
as
begin

update mydbr_snippets
set cright = @in_right, cdown = @in_down
where id = @in_id

end
go


if object_id('sp_MyDBR_sync_latest_reports') is not null
drop procedure sp_MyDBR_sync_latest_reports
go
CREATE PROCEDURE sp_MyDBR_sync_latest_reports( 
@inUser varchar(128),
@inAuthentication int,
@in_date date,
@in_source_folder int,
@in_no_excluded int,
@in_sp_single varchar(128)
)
as
begin

create table #procs_tmp( 
name sysname,
type varchar(20)
)

create table #routines_tmp (
name sysname
)


if (@in_sp_single is not null) begin
  insert into #procs_tmp values (@in_sp_single, 'PROCEDURE')
end else begin
  if (@in_source_folder is not null) begin
    insert into #procs_tmp 
    select o.name, case when type='P' then 'PROCEDURE' else 'FUNCTION' end
    from dbo.sysobjects o
      join mydbr_reports fr on fr.proc_name = o.name 
    where 
      fr.folder_id=@in_source_folder and 
      o.type in ('P','SF') and
      o.name not like 'sp_MyDBR%' and
      o.name not like 'sp_DBR_demo_%' and
      o.name not in ('sp_DBR_StatisticsReport', 'sp_DBR_StatisticsSummary', 'fn_mydbr_column_exists', 'fn_BegOfDay', 'fn_EndOfDay', 'mydbr_style', 'fn_YYYMMDD_us')
      and o.name not in (
        select proc_name
        from mydbr_sync_exclude
        where username = @inUser and authentication=@inAuthentication and @in_no_excluded=1
      )
  end else begin
    insert into #procs_tmp 
    select name, case when type='P' then 'PROCEDURE' else 'FUNCTION' end
    from dbo.sysobjects 
    where type in ('P','SF') and crdate >= @in_date and 
      name not like 'sp_MyDBR%' and
      name not like 'sp_DBR_demo_%' and
      name not in ('sp_DBR_StatisticsReport', 'sp_DBR_StatisticsSummary', 'fn_mydbr_column_exists', 'fn_BegOfDay', 'fn_EndOfDay', 'mydbr_style', 'fn_YYYMMDD_us')
      and name not in (
        select proc_name
        from mydbr_sync_exclude
        where username = @inUser and authentication=@inAuthentication and @in_no_excluded=1
      )
  end
end

insert into #routines_tmp
select t.name
from #procs_tmp t
  join mydbr_reports r on t.name=r.proc_name


insert into #routines_tmp
/* Additional procs & functions */
select t.name
from #procs_tmp t
  left join mydbr_reports r on t.name=r.proc_name 
where r.proc_name  is null
  and t.name not in (
    select proc_name
    from mydbr_sync_exclude
    where username = @inUser and authentication=@inAuthentication and @in_no_excluded=1 and type='routine'
  ) and @in_sp_single is null

/* PARAMETER QUERIES */
insert into #routines_tmp
select ro.name as 'name'
from dbo.sysobjects ro
  join mydbr_param_queries q on q.query=ro.name
  join mydbr_params p on p.query_name=q.name
  join #procs_tmp t on t.name = p.proc_name and t.name!=q.query
where t.type = 'PROCEDURE'


select 'routines' as 'MYDBRTYPE'

select distinct name 
from #routines_tmp
order by name

select 'table' as 'MYDBRTYPE', 'mydbr_templates' as 'table_name', 'name'

select name, header, row, footer
from mydbr_templates
where creation_date >= @in_date
and name not in (
  select proc_name
  from mydbr_sync_exclude
  where username = @inUser and authentication=@inAuthentication and @in_no_excluded=1 and type='template'
) and @in_sp_single is null
order by name

select 'table' as 'MYDBRTYPE', 'mydbr_localization' as 'table_name', 'lang_locale', 'keyword'

select *
from mydbr_localization
where creation_date >= @in_date
and keyword not in (
  select proc_name
  from mydbr_sync_exclude
  where username = @inUser and authentication=@inAuthentication and @in_no_excluded=1 and type='localization'
) and @in_sp_single is null

select 'table' as 'MYDBRTYPE', 'mydbr_params' as 'table_name', 'proc_name', 'params'

select p.*
from mydbr_params p
  join #procs_tmp t on t.name = p.proc_name 
where t.type='PROCEDURE'


select 'table' as 'MYDBRTYPE', 'mydbr_param_queries' as 'table_name', 'name'

select q.*
from mydbr_param_queries q
  join mydbr_params p on p.query_name=q.name or p.default_value=q.name
  join #procs_tmp t on t.name = p.proc_name 
where t.type='PROCEDURE'


select 'table' as 'MYDBRTYPE', 'mydbr_report_extensions' as 'table_name', 'proc_name', 'extension'

select e.*
from mydbr_report_extensions e
  join #procs_tmp t on t.name=e.proc_name
where t.type='PROCEDURE'


select 'table' as 'MYDBRTYPE', 'mydbr_reports' as 'table_name', 'report_id'

select r.*, rg.name as 'rgname', rg.sortorder as 'rgsortorder', rg.color as 'rgcolor'
from #procs_tmp t
  join mydbr_reports r on t.name=r.proc_name 
  join mydbr_reportgroups rg on rg.id=r.reportgroup 

drop table #procs_tmp
drop table #routines_tmp

end
go



if object_id('sp_MyDBR_table_columns') is not null
drop procedure sp_MyDBR_table_columns
go
CREATE PROCEDURE sp_MyDBR_table_columns( 
@in_table varchar(150) 
)
as
begin

select c.name, t.name
from dbo.syscolumns c
  join dbo.systypes t on t.usertype=c.usertype
where object_name(id) = @in_table

end
go

if object_id('sp_MyDBR_sync_mydbr_reports') is not null
drop procedure sp_MyDBR_sync_mydbr_reports
go
create procedure sp_MyDBR_sync_mydbr_reports( 
@in_sync_folder_name nvarchar(150),
@in_name nvarchar(150),
@in_proc_name sysname,
@in_explanation nvarchar(4000),
@in_sortorder int,
@in_runreport nvarchar(50),
@in_autoexecute tinyint,
@in_parameter_help varchar(10000),
@in_export varchar(10),
@in_reportgroup varchar(128),
@in_rgsortorder int,
@in_rgcolor char(6)
)
as
begin

declare @v_cnt int
declare @v_folder_id int
declare @v_report_id int
declare @v_report_group_id int
declare @v_folder_rg_id int

select @v_report_group_id = id
from mydbr_reportgroups
where name = @in_reportgroup
  
if (@v_report_group_id is null) begin
  insert into mydbr_reportgroups ( name, sortorder, color )
  values ( @in_reportgroup, @in_rgsortorder, @in_rgcolor )

  select @v_report_group_id = @@identity
end

select  @v_cnt = count(*)
from mydbr_reports
where proc_name = @in_proc_name

if (@v_cnt=0) begin
  select @v_folder_id = folder_id
  from mydbr_folders
  where name = @in_sync_folder_name and mother_id=1

  select @v_folder_rg_id = min(id)
  from mydbr_reportgroups
  where id>0
  
  if (@v_folder_id is null) begin
    
    select @v_folder_id = max(folder_id)+1
    from mydbr_folders

    insert into mydbr_folders ( folder_id, mother_id, name, invisible, reportgroup, explanation )
    values ( @v_folder_id, 1, @in_sync_folder_name, 2, @v_folder_rg_id, 'Temporary folder for new myDBR sync reports')
  end
  
  select @v_report_id = max(report_id)+1
  from mydbr_reports
  
  insert into mydbr_reports (report_id, name, proc_name, folder_id, explanation, sortorder, runreport, autoexecute, parameter_help, export, reportgroup )
  values (@v_report_id, @in_name, @in_proc_name, @v_folder_id, @in_explanation, @in_sortorder, @in_runreport, @in_autoexecute, @in_parameter_help, @in_export, @v_report_group_id)
end else begin
  update mydbr_reports
  set
    name = @in_name,
    proc_name = @in_proc_name,
    explanation = @in_explanation,
    sortorder = @in_sortorder,
    runreport = @in_runreport,
    autoexecute = @in_autoexecute,
    parameter_help = @in_parameter_help,
    export = @in_export,
    reportgroup = @v_report_group_id
  where proc_name = @in_proc_name
end

end
go


if object_id('sp_MyDBR_sync_exclude_toggle') is not null
drop procedure sp_MyDBR_sync_exclude_toggle
go
CREATE PROCEDURE sp_MyDBR_sync_exclude_toggle(
@inUser nvarchar(128),
@inAuthentication int,
@inProcName sysname,
@inType varchar(20)
)
as
begin

delete 
from mydbr_sync_exclude
where username = @inUser and authentication=@inAuthentication and proc_name=@inProcName

if (@@rowcount = 0) begin
  insert into mydbr_sync_exclude ( username, authentication, proc_name, type )
  values ( @inUser, @inAuthentication, @inProcName, @inType )
end

end
go

if object_id('sp_MyDBR_sync_exclude_get') is not null
drop procedure sp_MyDBR_sync_exclude_get
go
CREATE PROCEDURE sp_MyDBR_sync_exclude_get(
@inUser nvarchar(128),
@inAuthentication int
)
as
begin

select proc_name, type
from mydbr_sync_exclude
where username = @inUser and authentication=@inAuthentication

end
go

if object_id('sp_MyDBR_path_to_folder_id') is not null
drop procedure sp_MyDBR_path_to_folder_id
go
create procedure sp_MyDBR_path_to_folder_id(
@in_path varchar(16384),
@in_delim varchar(10),
@in_parent_child varchar(10),
@out_folder_id int output,
@out_folder_name varchar(100) output
)
as
begin
declare @v_path varchar(16384)
declare @v_path2 varchar(16384)
declare @v_folder varchar(16384)
declare @v_pos int
declare @v_mother_id int
declare @v_folder_id int
declare @v_incorrect int

set @v_mother_id = null
set @v_path = @in_path

select @v_pos = charindex(@in_delim, @v_path)
set @v_incorrect = 0

while( @v_pos > 0 and @v_incorrect = 0) begin
  select @v_folder = substring( @v_path, 1, @v_pos-1 )
  select @v_path2 = substring( @v_path, @v_pos+len(@in_delim), 16384 )

  select @v_pos = charindex(@in_delim, @v_path)

  set @v_folder_id = null

  select @v_folder_id = folder_id
  from mydbr_folders
  where isnull(mother_id, 0) = isnull(@v_mother_id, 0) and name = @v_folder

  if (@v_folder_id is null) begin
    set @v_incorrect = 1
  end

  set @v_path = @v_path2
  select @v_pos = charindex(@in_delim, @v_path)

  set @v_mother_id = @v_folder_id
end

set @out_folder_id = null

if (@v_incorrect=0) begin
  if (@in_parent_child = 'mother') begin
    set @out_folder_id = @v_folder_id
    set @out_folder_name = @v_path
  end else begin

    set @v_mother_id = @v_folder_id

    set @v_folder_id = null

    select @v_folder_id = folder_id
    from mydbr_folders
    where isnull(mother_id, 0) = isnull(@v_mother_id, 0) and name = @v_path

    set @out_folder_id = @v_folder_id
  end
end

end
go

if object_id('sp_MyDBR_sync_report_path_get') is not null
drop procedure sp_MyDBR_sync_report_path_get
go
create procedure sp_MyDBR_sync_report_path_get(
@in_report sysname,
@in_delimiter varchar(10)
)
as
begin
declare @v_path varchar(16384)
declare @v_path2 varchar(16384)
declare @v_folder varchar(16384)
declare @v_mother_id int
declare @v_mother_id2 int

select @v_path = f.name,  @v_mother_id = f.mother_id
from mydbr_reports r
  join mydbr_folders f on f.folder_id = r.folder_id
where r.proc_name = @in_report

set @v_path2 = @v_path
set @v_mother_id2 = @v_mother_id

while ( isnull(@v_mother_id2,0) !=0 ) begin
  select @v_folder = name, @v_mother_id = mother_id
  from mydbr_folders
  where folder_id = @v_mother_id2
  
  set @v_mother_id2 = @v_mother_id
  
  set @v_path2 = @v_folder+@in_delimiter+@v_path2
end

select @v_path2

end
go

if object_id('sp_MyDBR_sync_report_path_set') is not null
drop procedure sp_MyDBR_sync_report_path_set
go
create procedure sp_MyDBR_sync_report_path_set(
@in_report sysname,
@in_path varchar(16384),
@in_delim varchar(10)
)
as
begin

declare @v_folder_id int
declare @v_folder_name varchar(100)

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'child', @v_folder_id output, @v_folder_name output

if (@v_folder_id is not null) begin
  update mydbr_reports
  set folder_id = @v_folder_id
  where proc_name = @in_report
end

end
go

if object_id('sp_MyDBR_sync_report_priv_get') is not null
drop procedure sp_MyDBR_sync_report_priv_get
go
CREATE PROCEDURE sp_MyDBR_sync_report_priv_get(
@in_report sysname
)
as
begin


select p.username, g.name, p.authentication
from mydbr_reports_priv p 
  join mydbr_reports r on r.report_id=p.report_id
  left join mydbr_groups g on g.group_id = p.group_id
where r.proc_name=@in_report

end
go

if object_id('sp_MyDBR_sync_report_priv_rst') is not null
drop procedure sp_MyDBR_sync_report_priv_rst
go
CREATE PROCEDURE sp_MyDBR_sync_report_priv_rst(
@in_report sysname
)
as
begin

delete 
from mydbr_reports_priv
where report_id in (
  select report_id
  from mydbr_reports
  where proc_name=@in_report
)

end
go

if object_id('sp_MyDBR_sync_check_priv') is not null
drop procedure sp_MyDBR_sync_check_priv
go
create procedure sp_MyDBR_sync_check_priv(
@in_username varchar(128),
@in_group varchar(100),
@in_authentication int,
@out_ok int output,
@out_group_id int output
)
as
begin
declare @v_cnt int

set @out_group_id = 0
set @out_ok = 0

if (@in_username='' or @in_username=' ') begin
  set @in_username = null
end

if (@in_username is not null) begin
  if (@in_username in ('MYDBR_WEB', 'PUBLIC') and @in_authentication=0) begin
    set @out_ok = 1
  end else begin
    select @v_cnt = count(*)
    from mydbr_userlogin
    where username = @in_username and authentication = @in_authentication
    
    if (@v_cnt>0) begin
      set @out_ok = 1
    end
  end
end else begin 
  select @out_group_id = group_id
  from mydbr_groups
  where name = @in_group
  
  if (@out_group_id != 0) begin
    set @out_ok = 1
  end
end

end
go


if object_id('sp_MyDBR_sync_report_priv_set') is not null
drop procedure sp_MyDBR_sync_report_priv_set
go
create procedure sp_MyDBR_sync_report_priv_set(
@in_report sysname,
@in_username nvarchar(128),
@in_group nvarchar(100),
@in_authentication int
)
as
begin

declare @v_report_id int
declare @v_ok int
declare @v_cnt int
declare @v_group_id int

select @v_report_id = report_id
from mydbr_reports
where proc_name = @in_report

set @v_group_id = 0
set @v_ok = 0

if (@v_report_id is not null) begin
  exec sp_MyDBR_sync_check_priv @in_username, @in_group, @in_authentication, @v_ok output, @v_group_id output
end

if (@v_ok=1) begin
  select @v_cnt = count(*)
  from mydbr_reports_priv
  where report_id = @v_report_id and isnull(username, '') = isnull(@in_username, '')
      and isnull(group_id, 0) = isnull(@v_group_id, 0) and authentication = @in_authentication
      
  if (@v_cnt = 0) begin
    insert into mydbr_reports_priv( report_id, username, group_id, authentication )
    values ( @v_report_id, isnull(@in_username, ''), @v_group_id, @in_authentication )
  end
end

end
go

if object_id('sp_MyDBR_sync_reportgrp_check') is not null
drop procedure sp_MyDBR_sync_reportgrp_check
go
create procedure sp_MyDBR_sync_reportgrp_check(
@in_report_group_id int output,
@in_reportgroup varchar(128),
@in_rgsortorder int,
@in_rgcolor char(6)
)
as
begin

set @in_report_group_id = null

select @in_report_group_id = id
from mydbr_reportgroups
where name = @in_reportgroup

if (@in_report_group_id is null) begin
  insert into mydbr_reportgroups ( name, sortorder, color )
  values ( @in_reportgroup, @in_rgsortorder, @in_rgcolor )

  select @in_report_group_id = @@identity
end

end
go

if object_id('sp_MyDBR_sync_folder_info') is not null
drop procedure sp_MyDBR_sync_folder_info
go
CREATE PROCEDURE sp_MyDBR_sync_folder_info (
@in_path varchar(16384),
@in_delim varchar(10)
)
as
begin
declare @v_folder_id int
declare @v_folder_name varchar(100)

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'child', @v_folder_id output, @v_folder_name output

select f.explanation, g.name, g.sortorder, g.color
from mydbr_folders f
  join mydbr_reportgroups g on g.id = f.reportgroup
where f.folder_id = @v_folder_id

end
go

if object_id('sp_MyDBR_sync_check_for_folder') is not null
drop procedure sp_MyDBR_sync_check_for_folder
go
create procedure sp_MyDBR_sync_check_for_folder(
@in_path varchar(16384),
@in_delim varchar(10),
@in_explanation varchar(16384),
@in_reportgroup varchar(128),
@in_rgsortorder int,
@in_rgcolor char(6)
)
as
begin
declare @v_mother_id int
declare @v_folder_name varchar(100)
declare @v_cnt int
declare @v_report_group_id int
declare @folder_id int

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'mother', @v_mother_id output, @v_folder_name output

select @v_cnt = count(*)
from mydbr_folders
where mother_id = @v_mother_id and name = @v_folder_name

if (@v_cnt=0) begin
  exec sp_MyDBR_sync_reportgrp_check @v_report_group_id output, @in_reportgroup, @in_rgsortorder, @in_rgcolor

  select @folder_id = isnull(max(folder_id)+1,1)
  from mydbr_folders

  insert into mydbr_folders ( folder_id, mother_id, name, invisible, reportgroup, explanation )
  values ( @folder_id, @v_mother_id, @v_folder_name, 2, @v_report_group_id, @in_explanation)
end

end
go

if object_id('sp_MyDBR_sync_folder_priv_get') is not null
drop procedure sp_MyDBR_sync_folder_priv_get
go
create procedure sp_MyDBR_sync_folder_priv_get(
@in_path varchar(16384),
@in_delim varchar(10)
)
as
begin

declare @v_folder_id int
declare @v_folder_name varchar(100)

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'child', @v_folder_id output, @v_folder_name output

select p.username, g.name, p.authentication
from mydbr_folders_priv p 
  left join mydbr_groups g on g.group_id = p.group_id
where p.folder_id=@v_folder_id

end
go

if object_id('sp_MyDBR_sync_folder_priv_rst') is not null
drop procedure sp_MyDBR_sync_folder_priv_rst
go
create procedure sp_MyDBR_sync_folder_priv_rst(
@in_path varchar(16384),
@in_delim varchar(10)
)
as
begin
declare @v_folder_id int
declare @v_folder_name varchar(100)

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'child', @v_folder_id output, @v_folder_name output

delete 
from mydbr_folders_priv
where folder_id = @v_folder_id

end
go

if object_id('sp_MyDBR_sync_folder_priv_set') is not null
drop procedure sp_MyDBR_sync_folder_priv_set
go
CREATE PROCEDURE sp_MyDBR_sync_folder_priv_set(
@in_path varchar(100),
@in_delim varchar(10),
@in_username varchar(128),
@in_group varchar(100),
@in_authentication int
)
as
begin

declare @v_folder_id int
declare @v_folder_name varchar(100)
declare @v_ok int
declare @v_cnt int
declare @v_group_id int

exec sp_MyDBR_path_to_folder_id @in_path, @in_delim, 'child', @v_folder_id output, @v_folder_name output

set @v_ok = 0
set @v_group_id = 0

if (@v_folder_id is not null) begin
  exec sp_MyDBR_sync_check_priv @in_username, @in_group, @in_authentication, @v_ok output, @v_group_id output
end

if (@v_ok=1) begin
  select @v_cnt = count(*)
  from mydbr_folders_priv
  where folder_id=@v_folder_id and isnull(username, ' ')=isnull(@in_username,' ') and group_id=@v_group_id and authentication=@in_authentication

  if (@v_cnt=0) begin
    insert into mydbr_folders_priv( folder_id, username, group_id, authentication )
    values ( @v_folder_id, isnull(@in_username,' '), @v_group_id, @in_authentication )
  end
end

end
go


if object_id('sp_MyDBR_sync_drop_sync_folder') is not null
drop procedure sp_MyDBR_sync_drop_sync_folder
go
create procedure sp_MyDBR_sync_drop_sync_folder(
@in_sync_folder_name varchar(100)
)
as
begin

declare @v_folder_id int
declare @vReportCnt int
declare @vFolderCnt int

select @v_folder_id = folder_id
from mydbr_folders
where name = @in_sync_folder_name and mother_id=1

if (@v_folder_id is not null) begin

  select  @vReportCnt = count(*)
  from mydbr_reports
  where folder_id = @v_folder_id

  select @vFolderCnt = count(*) 
  from mydbr_folders
  where mother_id = @v_folder_id

  if ( @vReportCnt+@vFolderCnt = 0) begin
    delete 
    from mydbr_folders_priv 
    where folder_id = @v_folder_id
    
  	delete 
  	from mydbr_folders
  	where folder_id = @v_folder_id
  end
end

end
go

if object_id('sp_MyDBR_scheduled_tasks') is not null
drop procedure sp_MyDBR_scheduled_tasks
go
create procedure sp_MyDBR_scheduled_tasks
as
begin

select timing, description, url, last_run, disabled, id, case when last_run is not null then last_run else created_at end as 'last_run_calc'
from mydbr_scheduled_tasks
order by id

end
go

if object_id('sp_MyDBR_scheduled_task_set') is not null
drop procedure sp_MyDBR_scheduled_task_set
go
create procedure sp_MyDBR_scheduled_task_set(
@in_task_id int,
@in_timing varchar(255),
@in_url varchar(2048),
@in_description varchar(2048),
@in_disabled int
)
as
begin

if (@in_task_id is null) begin
  insert into mydbr_scheduled_tasks (description, url, timing, disabled, created_at)
  values (@in_description, @in_url, @in_timing, @in_disabled, getdate())
end else begin
  update mydbr_scheduled_tasks 
  set 
    description = @in_description,
    url = @in_url,
    timing = @in_timing,
    disabled = @in_disabled,
    last_run = case when url=@in_url and timing=@in_timing then last_run else null end,
    created_at = case when url=@in_url and timing=@in_timing then created_at else getdate() end
  where id = @in_task_id
end

end
go

if object_id('sp_MyDBR_scheduled_task_del') is not null
drop procedure sp_MyDBR_scheduled_task_del
go
create procedure sp_MyDBR_scheduled_task_del(
@in_task_id int
)
as
begin

delete from mydbr_scheduled_tasks 
where id = @in_task_id

end
go

if object_id('sp_MyDBR_scheduled_update_run') is not null
drop procedure sp_MyDBR_scheduled_update_run
go
create procedure sp_MyDBR_scheduled_update_run(
@in_task_id int,
@in_last_run datetime
)
as
begin

update mydbr_scheduled_tasks 
set last_run = @in_last_run
where id = @in_task_id

end
go

if object_id('sp_MyDBR_report_location') is not null
drop procedure sp_MyDBR_report_location
go
create procedure sp_MyDBR_report_location(
@in_id int
)
as
begin

select folder_id
from mydbr_reports
where report_id = @in_id

end
go

if object_id('sp_MyDBR_check_mydbr_username') is not null
drop procedure sp_MyDBR_check_mydbr_username
go
create procedure sp_MyDBR_check_mydbr_username(
@in_username sysname
)
as
begin

select count(*) 
from mydbr_userlogin u 
where username = @in_username and authentication = 2

end
go

if object_id('sp_MyDBR_scheduler_users') is not null
drop procedure sp_MyDBR_scheduler_users
go
create procedure sp_MyDBR_scheduler_users(
@in_search sysname
)
as
begin

select (username) as 'id', (username) as 'text'
from mydbr_userlogin
where authentication=2 and 
  (lower(username) like '%'+lower(username)+'%' or lower(name) like '%'+lower(@in_search)+'%')

end
go


-- myDBR Admin reports
if object_id('dbo.fn_seconds_to_time') is not null
drop function fn_seconds_to_time
go
create function dbo.fn_seconds_to_time ( @secs int )
returns datetime
as
begin
if (@secs is null) return null

return convert( datetime, CONVERT(varchar(6), @secs/3600)
  + ':' + RIGHT('0' + CONVERT(varchar(2), (@secs % 3600) / 60), 2)
  + ':' + RIGHT('0' + CONVERT(varchar(2), @secs % 60), 2) )
end
go


if object_id('sp_DBR_StatisticsReport') is not null
drop procedure sp_DBR_StatisticsReport
go
create procedure sp_DBR_StatisticsReport 
@inReportID int,
@inStartDate datetime,
@inEndDate datetime
as
begin
declare @vDay datetime
declare @vEndTime datetime
declare @vCnt int
declare @vDayCnt int
declare @vProcName sysname
declare @inProcName sysname

select @vProcName = proc_name
from mydbr_reports
where report_id = @inReportID

select @vEndTime = dbo.fn_EndOfDay(@inEndDate)

select @vDayCnt = datediff(day, @inStartDate, @vEndTime)

select 'dbr.pageview'

select 
  name as 'Report', 
  proc_name as 'Procedure', 
  convert(varchar(30),@inStartDate)+' - '+convert(varchar(30),@inEndDate) as 'Period'
from mydbr_reports
where proc_name = @vProcName

select @vCnt = count(*)
from mydbr_statistics s
where proc_name = @vProcName and s.start_time between @inStartDate and @vEndTime

if (@vDayCnt<0) begin
  select 'dbr.hideheader'
  select 'Check the dates!'
  return
end

if (@vCnt = 0 ) begin
  select 'dbr.hideheader'
  select 'Report has not been run during selected period!'
  return
end 
/* If the reporting period is short enough we'll print out the daily execution distribution */
if (@vDayCnt<32) begin
  create table #tmp_cnt (
  day date,
  cnt int
  )

  insert into #tmp_cnt ( day, cnt )
  select dbo.fn_BegOfDay(start_time), count(*)
  from mydbr_statistics
  where proc_name=@vProcName and start_time between @inStartDate and @vEndTime
  group by dbo.fn_BegOfDay(start_time)

  while (@vDayCnt >= 0) begin
    select @vDay = dbo.fn_BegOfDay(dateadd(day, -1, @vEndTime))
        
    insert into #tmp_cnt ( day, cnt )
    values (@vDay, 0)

    set @vDayCnt = @vDayCnt -1
  end

  select 'dbr.chart', 'bar'
  select 'dbr.chart.color', '0x0099CC'
    
  select day, sum(cnt)
  from #tmp_cnt
  group by day

  drop table #tmp_cnt
end

select 
  isnull(u.name , s.username) as 'User', 
  count(*) as 'Run count'
from mydbr_statistics s left join mydbr_userlogin u on s.username=u.username
where proc_name = @vProcName and s.start_time between @inStartDate and @vEndTime
group by isnull(u.name , s.username)
order by 2 desc

select  
  isnull(u.name , s.username) as 'User', 
  start_time as 'Report run', 
  convert( varchar(8), dbo.fn_seconds_to_time(datediff(second, s.start_time, s.end_time)),8) as 'Execution time',
  query as 'Query'
from mydbr_statistics s left join mydbr_userlogin u on s.username=u.username
where proc_name = @vProcName and s.start_time between @inStartDate and @vEndTime
order by start_time desc

end
go

delete from mydbr_update
go
delete from mydbr_version
go
insert into mydbr_version values ('5.0.2')
go


if object_id('sp_DBR_StatisticsSummary') is not null
drop procedure sp_DBR_StatisticsSummary
go
create procedure sp_DBR_StatisticsSummary
@inRowCount int,
@inStartDate datetime,
@inEndDate datetime
as
begin
declare @vEndTime datetime

select 'dbr.title', 'Statistics summary '+convert(varchar(30),@inStartDate)+' - '+convert(varchar(30),@inEndDate)

select @vEndTime = dbo.fn_EndOfDay(@inEndDate)
select 'dbr.hidecolumns', 1

select 'dbr.report', 'sp_DBR_userusage',  2, 'popup', 'inUser=4', 'inStartDate=-2', 'inEndDate=-3'

select 'dbr.subtitle', convert(varchar(5), @inRowCount) + ' Most active users'
select 'dbr.sum', 3

set rowcount @inRowCount

select 'dbr.rownum' as ' #', isnull(u.name , s.username) as 'Name', count(*) as 'RunCount', s.username
from mydbr_statistics s left join mydbr_userlogin u on s.username=u.username
where s.start_time between @inStartDate and @vEndTime
group by isnull(u.name , s.username), s.username
order by RunCount desc

select 'dbr.subtitle', convert(varchar(5), @inRowCount)+' Most used reports'

select 'dbr.report', 'sp_DBR_StatisticsReport', 2, 'inReportID=5', 'inStartDate=-2', 'inEndDate=-3'
select 'dbr.hidecolumns', 1
select 'dbr.sum', 4

select  'dbr.rownum' as ' #', r.name as 'Report', s.proc_name as 'StoredProcedure', count(*) as 'RunCount', r.report_id as 'ReportID'
from mydbr_statistics s, mydbr_reports r
where s.proc_name=r.proc_name and s.start_time between @inStartDate and @vEndTime
group by r.name, s.proc_name, r.report_id
order by RunCount desc

select 'dbr.subtitle', convert(varchar(5), @inRowCount) + ' Slowest reports'

select 'dbr.report', 'sp_DBR_StatisticsReport',2,'inReportID=7','inStartDate=-2','inEndDate=-3'
select 'dbr.hidecolumns', 1

select  
  'dbr.rownum' as ' #', 
  r.name as 'Report',
  r.proc_name as 'StoredProcedure',
  convert( varchar(8), dbo.fn_seconds_to_time(min(datediff(second, s.start_time, s.end_time))),8) as 'MinTime', 
  convert( varchar(8), dbo.fn_seconds_to_time(avg(datediff(second, s.start_time, s.end_time ))),8) as 'AvgTime', 
  convert( varchar(8), dbo.fn_seconds_to_time(max(datediff(second, s.start_time, s.end_time))),8) as 'MaxTime',
  r.report_id as 'ReportID'
from mydbr_statistics s, mydbr_reports r
where (s.proc_name = r.proc_name or s.proc_name = '['+r.proc_name+']')  and s.start_time between @inStartDate and @vEndTime
group by r.name, r.proc_name, r.report_id
order by AvgTime desc

set rowcount 0

end
go
