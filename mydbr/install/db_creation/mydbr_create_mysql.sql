/*  
    myDBR. Copyright mydbr.com 2008-2017
*/
delimiter $$

CREATE TABLE IF NOT EXISTS mydbr_reportgroups (
  `id` int NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `sortorder` int NOT NULL,
  `color` char(6) NOT NULL,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0
$$

INSERT IGNORE INTO mydbr_reportgroups (id, name, sortorder, color) VALUES (-1, '#{MYDBR_AA_FAVOURITES}', 0, '00AAFF')
$$
INSERT IGNORE INTO mydbr_reportgroups (id, name, sortorder, color) VALUES (1, '#{MYDBR_AA_REPORTS}', 100, '00C322')
$$

update mydbr_reportgroups 
set name = '#{MYDBR_AA_FAVOURITES}'
where id=-1
$$

update mydbr_reportgroups 
set name = '#{MYDBR_AA_REPORTS}'
where id=1 and name='Reports'
$$


delimiter ;

CREATE TABLE IF NOT EXISTS `mydbr_folders` (
  `folder_id` int(11) NOT NULL auto_increment,
  `mother_id` int(11) default NULL,
  `name` varchar(100) default NULL,
  `invisible` tinyint(4) default NULL,
  `reportgroup` int not null default 1,
  `explanation` varchar(4096) NULL,
  PRIMARY KEY USING BTREE (`folder_id`),
  FOREIGN KEY (reportgroup) REFERENCES mydbr_reportgroups (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  PRIMARY KEY USING BTREE (`group_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_groupsusers` (
  `group_id` int(11) NOT NULL,
  `user` varchar(128) NOT NULL,
  `authentication` int(11) NOT NULL,
  PRIMARY KEY USING BTREE (`group_id`,`user`,`authentication`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_param_queries` (
  `name` varchar(50) NOT NULL,
  `query` varchar(4096) NOT NULL,
  `coltype` tinyint(4) NOT NULL,
  `options` varchar(255) NULL,
  PRIMARY KEY USING BTREE (`name`)
) ENGINE=InnoDB;


INSERT IGNORE INTO `mydbr_param_queries` (name, query, coltype) VALUES 
('MonthAgo','select  cast(date_add(now(), interval -1 month) as date)\r\n',3),
('Now','select cast(now() as date)',3),
('Steps_5-10-20-100','select 5, 5\r\nunion\r\nselect 10, 10\r\nunion\r\nselect 20, 20\r\nunion\r\nselect 50, 50\r\nunion\r\nselect 100, 100\r\n\r\n',0),
('Yes No','select 1, \'Yes\' \r\nunion \r\nselect 0, \'No\'',1);

CREATE TABLE IF NOT EXISTS `mydbr_params` (
  `proc_name` varchar(100) NOT NULL,
  `param` varchar(100) NOT NULL,
  `query_name` varchar(50) default NULL,
  `title` varchar(255) default NULL,
  `default_value` varchar(50) default NULL,
  `optional` int not null default 0,
  `only_default` int not null default 0,
  `suffix` varchar(255) default NULL,
  `options` varchar(1024) NULL,
  PRIMARY KEY USING BTREE (`proc_name`,`param`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_reports` (
  `report_id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `proc_name` varchar(100) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `explanation` varchar(4096) NULL,
  `reportgroup` int not null default 1,
  `sortorder` int null,
  `runreport` varchar(50) NULL,
  `autoexecute` tinyint NULL,
  `parameter_help` varchar(10000) NULL,
  `export` varchar(10) NULL,
  PRIMARY KEY USING BTREE (`report_id`),
  FOREIGN KEY (reportgroup) REFERENCES mydbr_reportgroups (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_report_extensions` (
`proc_name` varchar(100) NOT NULL,
`extension` varchar(100) NOT NULL,
PRIMARY KEY USING BTREE (`proc_name`, `extension`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `mydbr_reports_priv` (
  `report_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `group_id` int(11) NOT NULL,
  `authentication` int(11) NOT NULL,
  PRIMARY KEY USING BTREE (`report_id`,`username`,`group_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_folders_priv` (
  `folder_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `group_id` int(11) NOT NULL,
  `authentication` int(11) NOT NULL,
  PRIMARY KEY USING BTREE (`folder_id`,`username`,`group_id`,`authentication`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `mydbr_statistics` (
  `proc_name` varchar(100) NOT NULL,
  `username` varchar(128) NOT NULL,
  `authentication` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime default NULL,
  `query` text NOT NULL,
  `ip_address` varchar(255) NULL,
  `user_agent_hash` varchar(50) NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `mydbr_user_agents` (
  `hash` varchar(50) NOT NULL,
  `user_agent` text NULL,
  PRIMARY KEY  (`hash`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `mydbr_styles` (
  `name` varchar(30) NOT NULL,
  `colstyle` tinyint(4) NOT NULL,
  `definition` varchar(400) NOT NULL,
  PRIMARY KEY USING BTREE (`name`)
) ENGINE=InnoDB;

DELIMITER $$

INSERT IGNORE INTO `mydbr_styles` VALUES ('3 decimals',0,'%.3f'),('Bold',0,'[font-weight: bold;]'),('$US',0,'$ %.2f')
$$

DELIMITER ;

CREATE TABLE IF NOT EXISTS `mydbr_userlogin` (
  `user` varchar(128) NOT NULL,
  `password` char(255) default NULL,
  `name` varchar(60) default NULL,
  `admin` tinyint(4) NOT NULL default '0',
  `passworddate` datetime NULL,
  `email` varchar(100) NULL,
  `telephone` varchar(100) NULL,
  `authentication` int NOT NULL DEFAULT 2,
  `ask_pw_change` int NOT NULL DEFAULT 0,
  PRIMARY KEY  (`user`, `authentication`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `mydbr_password_reset` (
  `user` varchar(128) NOT NULL,
  `perishable_token` varchar(128) NOT NULL,
  `request_time` datetime NOT NULL,
  `ip_address` varchar(255) NULL,
  PRIMARY KEY  (`user`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS mydbr_authentication (
  `module` varchar(20) not null,
  `mask` int not null,
  `name` varchar(30),
  PRIMARY KEY USING BTREE (`module`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mydbr_notifications (
  `id` int NOT NULL,
  `notification` text,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mydbr_licenses (
  `id` int NOT NULL auto_increment,
  `owner` varchar(255) not null,
  `email` varchar(255) not null,
  `company` varchar(255) not null,
  `host` varchar(255) not null,
  `license_key` varchar(80) not null,
  `db` varchar(10) not null,
  `expiration` date not null,
  `type` varchar(255) default null,
  `version` varchar(255) default null,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mydbr_version (
  `version` varchar(10)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS mydbr_update;
CREATE TABLE mydbr_update (
  `latest_version` varchar(10),
  `next_check` int,
  `download_link` varchar(200),
  `info_link` varchar(200),
  `last_successful_check` int,
  `signature` varchar(50)
) ENGINE=InnoDB;

insert IGNORE into mydbr_authentication values 
('db', 1, 'Database login'),
('mydbr', 2, 'myDBR user'),
('ext', 4, 'Single Sign-On'),
('ldap', 8, 'LDAP'),
('custom', 16, 'Custom');

update mydbr_authentication set name='Single Sign-On' where module='ext';

CREATE TABLE IF NOT EXISTS mydbr_log (
  `id` int NOT NULL auto_increment,
  `user` varchar(128) NOT NULL,
  `log_time` datetime,
  `log_ip` varchar(40) null,
  `log_title` varchar(30) null,
  `log_message` text,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `mydbr_options` (
  `user` varchar(128) DEFAULT '',
  `authentication` int(11) NOT NULL DEFAULT 0,
  `name` varchar(30) NOT NULL,
  `value` varchar(512) NOT NULL,
  PRIMARY KEY  ( `user`, `authentication`, `name` )
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `mydbr_favourites`;
CREATE TABLE IF NOT EXISTS `mydbr_favourite_reports` (
  `id` int not null auto_increment,
  `user` varchar(128),
  `authentication` int(11) NOT NULL,
  `report_id` int NOT NULL,
  `url` varchar(512) NULL,
  PRIMARY KEY USING BTREE (`id`),
  INDEX USING BTREE ( `user`, `authentication` ),
  FOREIGN KEY (`report_id`) REFERENCES mydbr_reports (`report_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user`, `authentication`) REFERENCES mydbr_userlogin (`user`, `authentication`) ON DELETE CASCADE
) ENGINE=InnoDB;

DELIMITER $$
INSERT IGNORE INTO `mydbr_options` (name, value) VALUES 
( 'avgprefix', 's:3:"avg";' ),
( 'countprefix', 's:1:"#";' ),
( 'dateformat', 's:5:"Y-m-d";' ),
( 'datetimeformat', 's:13:"Y-m-d h:i:s a";' ),
( 'dbrreportprefix', 's:6:"sp_DBR";' ),
( 'decimal_separator', 's:1:".";' ),
( 'def_password', 's:0:"";' ),
( 'def_username', 's:0:"";' ),
( 'image_preferred', 'b:0;' ),
( 'maxprefix', 's:3:"max";' ),
( 'minprefix', 's:3:"min";' ),
( 'sumprefix', 's:0:"";' ),
( 'theme', 's:7:"default";' ),
( 'thousand_separator', 's:1:",";' ),
( 'timeformat', 's:7:"h:i:s a";' ),
( 'password_expiration', 'i:0;' ),
( 'password_length', 'i:0;' ),
( 'password_letter', 'b:0;' ),
( 'password_number', 'b:0;' ),
( 'password_special', 'b:0;' ),
( 'php_include_path', 's:0:"";'),
( 'authentication', 'i:2;'),
( 'sso_server_url', 's:0:"";' ),
( 'sso_google_client_id', 's:0:"";' ),
( 'sso_google_client_secret', 's:0:"";' ),
( 'sso_google_hosted_domain', 's:0:"";' ),
( 'sso_type', 's:1:"0";' ),
( 'sso_token', 's:0:"";' ),
( 'proxy_server', 's:0:"";' ),
( 'session_lifetime', 'i:1;' ),
( 'languages','s:47:"en_US|de_DE|fi_FI|sv_SE|nl_NL|it_IT|es_ES|el_GR";'),
( 'password_reset_enabled','b:0;'),
( 'password_reset_email_username','i:0;'),
( 'password_reset_admin_change','b:0;'),
( 'password_reset_mail_validity','i:15;'),
( 'password_reset_show_login_fail','b:0;'),
( 'oem_licensee', 's:0:"";' ),
( 'oem_application_name', 's:0:"";' ),
( 'oem_header_disable','b:0;'),
( 'oem_footer_disable','b:0;'),
( 'oem_footer', 's:0:"";' ),
( 'oem_info', 's:0:"";' )
$$


update mydbr_options
set value = 's:7:"default";'
where name='theme' and value='s:7:"taikala";'
$$

CREATE TABLE IF NOT EXISTS mydbr_key_column_usage(
table_schema varchar(64) not null,
table_name varchar(64) not null,
column_name varchar(64) not null,
referenced_table_schema varchar(64),
referenced_table_name varchar(64),
referenced_column_name varchar(64),
PRIMARY KEY USING BTREE (table_schema, table_name, column_name)
) ENGINE=InnoDB
$$


create table if not exists mydbr_languages (
lang_locale char(5) not null,
language varchar(30),
date_format varchar(10),
time_format varchar(10),
thousand_separator varchar(2),
decimal_separator varchar(2),
PRIMARY KEY USING BTREE (lang_locale)
) ENGINE=InnoDB
$$

create table if not exists mydbr_localization (
lang_locale char(5) not null,
keyword varchar(50) not null,
translation varchar(1024),
creation_date datetime null,
PRIMARY KEY USING BTREE (lang_locale, keyword),
FOREIGN KEY (lang_locale) REFERENCES mydbr_languages (lang_locale)
) ENGINE=InnoDB
$$

CREATE TABLE IF NOT EXISTS mydbr_remote_servers (
  `id` int NOT NULL auto_increment,
  `server` varchar(128) NOT NULL,
  `url` varchar(255) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0
$$

CREATE TABLE IF NOT EXISTS mydbr_templates (
id int NOT NULL auto_increment,
name varchar(128) NOT NULL,
header text NULL,
row text NULL,
footer text NULL,
folder_id int NULL,
creation_date datetime null,
PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0
$$

CREATE TABLE IF NOT EXISTS mydbr_template_folders (
id int NOT NULL auto_increment,
name varchar(128) NULL,
parent_id int null,
PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0
$$

CREATE TABLE IF NOT EXISTS mydbr_snippets (
id int NOT NULL auto_increment,
name varchar(30) NULL,
code text null,
shortcut varchar(20) NULL,
cright int NULL,
cdown int NULL,
PRIMARY KEY USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0
$$

CREATE TABLE IF NOT EXISTS mydbr_sync_exclude (
username varchar(128) NOT NULL,
authentication int(11) NOT NULL,
proc_name varchar(100) NOT NULL,
type varchar(20) NOT NULL,
primary key (username, authentication, proc_name)
)
$$
alter table mydbr_sync_exclude drop primary key, add primary key(username, authentication, proc_name)
$$

CREATE TABLE IF NOT EXISTS mydbr_scheduled_tasks (
id int not null auto_increment,
description varchar(2028) null,
url varchar(2028) null,
timing varchar(255) not null,
last_run datetime null,
disabled int,
created_at datetime not null,
primary key using btree (id)
)
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_FixTables`
$$
CREATE PROCEDURE `sp_MyDBR_FixTables` ()
BEGIN
declare vCnt int;

select count(*) into vCnt
from mydbr_snippets;

if (vCnt=0) then
  insert into mydbr_snippets (name, code, shortcut, cright, cdown) 
  values
    ('select-clause', 'select \nfrom \nwhere ', 'Ctrl-Alt-S', 7, 0),
    ('if-clause', 'if () then\nend if;', 'Ctrl-I', 4, 0),
    ('if-else-clause', 'if () then\nelse\nend if;', 'Ctrl-Alt-I', 4, 0),
    ('while-clause', 'while () do\nend while;', 'Ctrl-Alt-W', 7, 0),
    ('create procedure', 'create procedure sp_DBR_()\nbegin\n\nend', 'Ctrl-P', 24, 0),
    ('create function', 'create function fn_() \nreturns varchar(255)\ndeterministic\nbegin\n\ndeclare v_ret varchar(255);\n\nreturn v_ret;\n\nend\n', '', 19,0),
    ('cursor', 'declare done int default 0;\n\ndeclare c_cursor cursor for\n  select \n  from \n  where \ndeclare continue handler for not found set done = 1;\n\nopen c_cursor;\nrepeat\n  fetch c_cursor into ;\n  if not done then\n  end if;\nuntil done end repeat;\n\nclose c_cursor;', '', 9, 3),
    ('case when', 'case \n  when  then \n  when  then \n  else \nend case', '', 5, 0);
    
    
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_param_queries' and column_name='options';

if (vCnt=0) then
  alter table mydbr_param_queries add options varchar(255) null;

  update mydbr_param_queries set coltype=4, options = '{"scroll":true,"find":true}' where coltype=5;
  update mydbr_param_queries set coltype=4, options = '{"scroll":true}' where coltype=6;
  update mydbr_param_queries set coltype=4, options = '{"find":true}' where coltype=7;
  update mydbr_param_queries set coltype=4, options = '{"collapse":true}' where coltype=8;
  update mydbr_param_queries set coltype=4, options = '{"scroll":true,"find":true,"collapse":true}' where coltype=9;
  update mydbr_param_queries set coltype=4, options = '{"scroll":true,"collapse":true}' where coltype=10;
  update mydbr_param_queries set coltype=4, options = '{"find":true,"collapse":true}' where coltype=11;
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_param_queries' and column_name='name';

if (vCnt!=50) then
  alter table mydbr_param_queries modify column name varchar(50) not null;
end if;


select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='password';

if (vCnt!=255) then
  alter table mydbr_userlogin modify column password varchar(255) null;
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='title';

if (vCnt!=255) then
  alter table mydbr_params modify column title varchar(255) null;
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='query_name';

if (vCnt!=50) then
  alter table mydbr_params modify column query_name varchar(50) null;
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='default_value';

if (vCnt!=50) then
  alter table mydbr_params modify column default_value varchar(50) null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_folders' and column_name='explanation';
if (vCnt=0) then
  alter table mydbr_folders add explanation varchar(4096) null;
end if;


select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_folders' and column_name='reportgroup';

if (vCnt=0) then
  alter table mydbr_folders add reportgroup int not null default 1;
  alter table mydbr_folders add FOREIGN KEY (reportgroup) REFERENCES mydbr_reportgroups (id);
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_folders' and column_name='explanation';

if (vCnt!=4096) then
  alter table mydbr_folders modify column explanation varchar(4096) null;
end if;


select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='explanation';

if (vCnt!=4096) then
  alter table mydbr_reports modify column explanation varchar(4096) null;
end if;


select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='authentication';

if (vCnt=0) then
  alter table mydbr_userlogin add authentication int not null default 2;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='ask_pw_change';

if (vCnt=0) then
  alter table mydbr_userlogin add ask_pw_change int not null default 0;
end if;


select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='reportgroup';

if (vCnt=0) then
  alter table mydbr_reports add reportgroup int not null default 1;
  alter table mydbr_reports add FOREIGN KEY (reportgroup) REFERENCES mydbr_reportgroups (id);
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='sortorder';

if (vCnt=0) then
  alter table mydbr_reports add sortorder int null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='runreport';

if (vCnt=0) then
  alter table mydbr_reports add runreport varchar(50) null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='autoexecute';

if (vCnt=0) then
  alter table mydbr_reports add autoexecute tinyint null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='parameter_help';

if (vCnt=0) then
  alter table mydbr_reports add parameter_help varchar(10000) null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_reports' and column_name='export';

if (vCnt=0) then
  alter table mydbr_reports add export varchar(10) null;
end if;


update mydbr_folders set name='#{MYDBR_AMAIN_HOME}' where folder_id=1;



ALTER TABLE mydbr_userlogin MODIFY COLUMN `user` varchar(128);
ALTER TABLE mydbr_reports_priv MODIFY COLUMN username varchar(128);
ALTER TABLE mydbr_statistics MODIFY COLUMN username varchar(128);
ALTER TABLE mydbr_groupsusers MODIFY COLUMN `user` varchar(128);
ALTER TABLE mydbr_log MODIFY COLUMN `user` varchar(128);
ALTER TABLE mydbr_options MODIFY COLUMN `user` varchar(128);
ALTER TABLE mydbr_param_queries MODIFY COLUMN `query` varchar(4096);
ALTER TABLE mydbr_reports  MODIFY COLUMN `parameter_help` varchar(10000);
ALTER TABLE mydbr_statistics MODIFY COLUMN `query` text;
ALTER TABLE mydbr_options MODIFY COLUMN `value` varchar(512) not null;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_statistics' and column_name='authentication';

if (vCnt=0) then
  alter table mydbr_statistics add authentication int not null default 2;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_statistics' and column_name='ip_address';

if (vCnt=0) then
  alter table mydbr_statistics add ip_address varchar(255) null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_statistics' and column_name='user_agent_hash';

if (vCnt=0) then
  alter table mydbr_statistics add user_agent_hash varchar(50) null;
end if;


select count(*) into vCnt
from information_schema.KEY_COLUMN_USAGE
where table_schema=database() and table_name='mydbr_userlogin';

if (vCnt<2) then
  ALTER TABLE mydbr_userlogin DROP PRIMARY KEY;
  ALTER TABLE mydbr_userlogin add primary key (`user`, `authentication`);
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='optional';

if (vCnt=0) then
  alter table mydbr_params add optional int not null default 0;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='only_default';

if (vCnt=0) then
  alter table mydbr_params add only_default int not null default 0;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='suffix';

if (vCnt=0) then
  alter table mydbr_params add suffix varchar(255) default NULL;
end if;

select CHARACTER_MAXIMUM_LENGTH into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='suffix';

if (vCnt!=255) then
  alter table mydbr_params modify column suffix varchar(255) null;
end if;


select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_params' and column_name='options';

if (vCnt=0) then
  alter table mydbr_params add options varchar(1024) null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='passworddate';

if (vCnt=0) then
  alter table mydbr_userlogin add passworddate datetime null;
  update mydbr_userlogin set passworddate = now();
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='email';

if (vCnt=0) then
  alter table mydbr_userlogin add email varchar(100) NULL;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_userlogin' and column_name='telephone';

if (vCnt=0) then
  alter table mydbr_userlogin add telephone varchar(100) NULL;
end if;


select count(*) into vCnt
from mydbr_userlogin
where admin=1;

if (vCnt=0) then
  INSERT IGNORE INTO `mydbr_userlogin` ( user, password, name, admin, passworddate, email, authentication, telephone)
  VALUES ('dba',md5('dba'),'myDBR Administrator',1, now(), null, 2, null);
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_licenses' and column_name='type';

if (vCnt=0) then
  alter table mydbr_licenses add `type` varchar(255) default null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_licenses' and column_name='version';

if (vCnt=0) then
  alter table mydbr_licenses add `version` varchar(255) default null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_languages' and column_name='date_format';

if (vCnt=0) then
  alter table mydbr_languages add `date_format` varchar(10) null;
  alter table mydbr_languages add `time_format` varchar(10) null;
  alter table mydbr_languages add `thousand_separator` varchar(2) null;
  alter table mydbr_languages add `decimal_separator` varchar(2) null;
end if;

select count(*) into vCnt
from mydbr_folders_priv;

if (vCnt=0) then
  insert into mydbr_folders_priv
  select folder_id, 'PUBLIC', 0, 0
  from mydbr_folders
  where invisible = 0;
  
  /* We'll take invisible out of use */
  update mydbr_folders
  set invisible = 2
  where invisible = 0;
end if;

select count(*) into vCnt
from mydbr_template_folders;

if (vCnt=0) then
  insert into mydbr_template_folders ( name, parent_id ) 
  values ('Main', null );
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_templates' and column_name='folder_id';

if (vCnt=0) then
  alter table mydbr_templates add folder_id int null;
  update mydbr_templates set folder_id = 1;  
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_templates' and column_name='creation_date';

if (vCnt=0) then
  alter table mydbr_templates add creation_date datetime null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_localization' and column_name='creation_date';

if (vCnt=0) then
  alter table mydbr_localization add creation_date datetime null;
end if;

select count(*) into vCnt
from information_schema.columns
where table_schema=database() and table_name='mydbr_sync_exclude' and column_name='type';

if (vCnt=0) then
  alter table mydbr_sync_exclude add type varchar(20) null;
end if;

/* 4.0 -> 4.2.1 user belonging to bogus 0 group  */
delete from mydbr_groupsusers where group_id = 0;

END
$$

call sp_MyDBR_FixTables()
$$

insert ignore into mydbr_languages (language, lang_locale) values('Arabic', 'ar_SA')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Bulgarian', 'bg_BG')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Chinese', 'zh_CN')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Croatian', 'hr_HR')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Czech', 'cs_CZ')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Danish', 'da_DK')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Dutch', 'nl_NL')
$$
insert ignore into mydbr_languages (language, lang_locale) values('English', 'en_US')
$$
insert ignore into mydbr_languages (language, lang_locale) values('British English', 'en_GB')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Estonian', 'et_EE')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Finnish', 'fi_FI')
$$
insert ignore into mydbr_languages (language, lang_locale) values('French', 'fr_FR')
$$
insert ignore into mydbr_languages (language, lang_locale) values('German', 'de_DE')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Greek', 'el_GR')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Hungarian', 'hu_HU')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Icelandic', 'is_IS')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Italian', 'it_IT')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Japanese', 'ja_JP')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Korean', 'ko_KR')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Latvian', 'lv_LV')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Lithuanian', 'lt_LT')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Norwegian', 'no_NO')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Polish', 'pl_PL')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Portuguese', 'pt_PT')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Romanian', 'ro_RO')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Russian', 'ru_RU')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Slovak', 'sk_SK')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Slovenian', 'sl_SI')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Spanish', 'es_ES')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Swedish', 'sv_SE')
$$
insert ignore into mydbr_languages (language, lang_locale) values('Turkish', 'tr_TR')
$$

update mydbr_languages 
set date_format = 'm/d/Y', time_format = 'h:i:s a', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('en_US')
$$

update mydbr_languages 
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('en_GB')
$$

update mydbr_languages 
set date_format = 'Y-m-d', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('zh_CN')
$$

update mydbr_languages 
set date_format = 'Y-m-d', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('sv_SE', 'lt_LT');
$$

update mydbr_languages 
set date_format = 'Y.m.d', time_format = 'h:i:s a', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('ko_KR');
$$

update mydbr_languages 
set date_format = 'Y.m.d', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('hu_HU');
$$

update mydbr_languages 
set date_format = 'd.m.Y', time_format = 'H.i.s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('fi_FI', 'el_GR');
$$

update mydbr_languages 
set date_format = 'd.m.Y', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('cs_CZ', 'el_GR', 'bg_BG', 'et_EE', 'lv_LV', 'no_NO', 'pl_PL', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'hr_HR');
$$

update mydbr_languages 
set date_format = 'd.m.Y', time_format = 'H:i:s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('de_DE', 'is_IS', 'tr_TR');
$$

update mydbr_languages 
set date_format = 'd-m-Y', time_format = 'H:i:s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('nl_NL');
$$

update mydbr_languages
set date_format = 'd/m/Y', time_format = 'H.i.s', thousand_separator = '.', decimal_separator = ','
where lang_locale in ('it_IT', 'da_DK', 'pt_PT');
$$

update mydbr_languages
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ' ', decimal_separator = ','
where lang_locale in ('fr_FR', 'es_ES', 'en_GB', 'ar_SA');
$$

update mydbr_languages
set date_format = 'd/m/Y', time_format = 'H:i:s', thousand_separator = ',', decimal_separator = '.'
where lang_locale in ('ja_JP');
$$



INSERT IGNORE INTO `mydbr_reports` (report_id, name, proc_name, folder_id, explanation, reportgroup)
VALUES 
(1,'Statistics summary','sp_DBR_StatisticsSummary',2,'', 1),
(2,'Statistics for a report','sp_DBR_StatisticsReport',3,'', 1)
$$

INSERT IGNORE INTO `mydbr_folders` VALUES 
(1,NULL,'#{MYDBR_AMAIN_HOME}',2, 1, null),
(2,1,'Admin reports',2,1, null),
(3,2,'Drill reports',2,1, null)
$$

INSERT IGNORE INTO mydbr_folders_priv VALUES (1, 'PUBLIC', 0, 0);
$$
update mydbr_folders set name = '#{MYDBR_AMAIN_HOME}' where folder_id=1;
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_FixTables
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_LicensesGet`
$$
CREATE PROCEDURE `sp_MyDBR_LicensesGet` ()
BEGIN
select id, owner, email, company, host, license_key, db, expiration, `type`, version
from mydbr_licenses
order by `type` desc, expiration desc;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_LicensesAdd`
$$
CREATE PROCEDURE `sp_MyDBR_LicensesAdd` (
inOwner varchar(255), 
inEmail varchar(255), 
inCompany varchar(255), 
inHost varchar(255), 
inLicense_key varchar(80), 
inDB varchar(10), 
inExpiration date,
inType varchar(255),
inVersion varchar(255)
)
BEGIN
declare vCnt int;

select count(*) into vCnt
from mydbr_licenses
where license_key = inLicense_key;

if (vCnt=0) then
  insert into mydbr_licenses ( owner, email, company, host, license_key, db, expiration, `type`, version )
  values (inOwner, inEmail, inCompany, inHost, inLicense_key, inDB, inExpiration, inType, inVersion );
end if;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_LicensesDel`
$$
CREATE PROCEDURE `sp_MyDBR_LicensesDel` (inID int)
BEGIN
delete 
from mydbr_licenses
where id=inID;
END
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_AmIAdminOut`
$$
CREATE PROCEDURE `sp_MyDBR_AmIAdminOut`( inUsername  varchar(128), inAuth int, out outAdmin int(11) )
BEGIN

declare vAdmin int;

if ((inUsername = 'root' or inUsername like 'root@%') and inAuth=1) then
  set outAdmin = 1;
else
  select admin into vAdmin
  from mydbr_userlogin
  where user = inUsername and authentication=inAuth;

  if (vAdmin=1) then
    set outAdmin = 1;
  else
    set outAdmin = 0;
  end if;
end if;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_AmIAdmin`
$$
CREATE PROCEDURE `sp_MyDBR_AmIAdmin`(inUsername varchar(60), inAuth int)
BEGIN
declare vAdmin int;
declare vMyName varchar(60);


call sp_MyDBR_AmIAdminOut(inUsername, inAuth, vAdmin);

select name into vMyName
from mydbr_userlogin
where user = inUsername and authentication=inAuth;

select vAdmin, vMyName;

END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderDel`
$$
CREATE PROCEDURE `sp_MyDBR_FolderDel`( inFolderID int )
BEGIN
  declare vReportCnt int;
  declare vFolderCnt int;
  declare vFolderName varchar(100);

  select name into vFolderName
  from mydbr_folders
  where folder_id = inFolderID;

  select count(*) into vReportCnt 
  from mydbr_reports
  where folder_id = inFolderID;

  select count(*) into vFolderCnt 
  from mydbr_folders
  where mother_id = inFolderID;
  
  if (vReportCnt+ vFolderCnt >0) then
    select concat("Folder '", vFolderName , "' is not empty. Cannot delete it.");
  else
    delete 
    from mydbr_folders
    where folder_id = inFolderID and folder_id not in (
      select folder_id from mydbr_reports
    );
    
    delete from mydbr_folders_priv where folder_id = inFolderID;
      
    select 'OK';
  end if;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderInfoGet` 
$$
CREATE PROCEDURE `sp_MyDBR_FolderInfoGet`(inFolderID int)
BEGIN
select name, reportgroup, explanation
from mydbr_folders
where folder_id=inFolderID;
END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderInfoSet` 
$$
CREATE PROCEDURE `sp_MyDBR_FolderInfoSet`(
inFolderID int, 
inFname varchar(100), 
inReportgroup int,
inExplanation varchar(4096)
)
BEGIN
update mydbr_folders
set name = inFname, reportgroup = inReportgroup, explanation = inExplanation
where folder_id= inFolderID;
select 'OK';
END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderMove`
$$
CREATE PROCEDURE `sp_MyDBR_FolderMove`(vID int, vFolder int )
BEGIN

declare vMother int;
declare vMoveOK int;

set vMoveOK = 1;
set vMother = vFolder;

repeat
  select mother_id into vMother
  from mydbr_folders
  where folder_id = vMother;
  
  if (vMother = vID or vID = vFolder) then
    set vMoveOK = 0;
    select 'Cannot move folder into itself!';
  end if;

until (vMoveOK = 0 or vMother is null)
end repeat;

if (vMoveOK=1) then
  update mydbr_folders
  set mother_id=vFolder
  where folder_id = vID;
end if;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderNew`
$$
CREATE PROCEDURE `sp_MyDBR_FolderNew`( 
inLevel int, 
inFolder varchar(150), 
inHiddenFolder int, 
inReportgroup int,
inExplanation varchar(4096)
)
BEGIN

insert into mydbr_folders ( mother_id, name, invisible, reportgroup, explanation )
values ( inLevel, inFolder, 2, inReportgroup, inExplanation);
  
select 'OK', last_insert_id();

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupAdd`
$$
CREATE PROCEDURE `sp_MyDBR_GroupAdd`(inName varchar(100))
BEGIN
declare vCnt int;

select count(*) into vCnt
from mydbr_groups 
where name = inName;

if (vCnt=0) then
  insert into mydbr_groups ( name )
  values ( inName );
  select 'OK', null;
else
  select 'Error', concat("Group '", inName, "' already exists");
end if;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupDel` 
$$
CREATE PROCEDURE `sp_MyDBR_GroupDel`(inGroupID int)
BEGIN

declare vCnt int;

select count(*) into vCnt
from mydbr_groupsusers 
where group_id = inGroupID;

if (vCnt>0) then
  select 'Error', '#{MYDBR_GROUP_CANNNOT_REM}';
else

  delete from mydbr_reports_priv where group_id = inGroupID;
  delete from mydbr_folders_priv where group_id = inGroupID;
  delete from mydbr_groupsusers where group_id = inGroupID;

  delete from mydbr_groups
  where group_id = inGroupID;
  
  select 'OK', null;
end if;
END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupGet`
$$
CREATE PROCEDURE `sp_MyDBR_GroupGet`()
BEGIN
select group_id, name
from mydbr_groups
order by name;
END
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupLevelGet` 
$$
CREATE PROCEDURE `sp_MyDBR_GroupLevelGet`(
inLevel int,
isAdmin int,
inUsername varchar(80), 
inAuth int
)
begin
declare vMother_id int;
declare vName varchar(100);
declare vLevel_order int;
declare vLevelExists int;

create temporary table TempTable ( 
folder_id int, 
name char(100),
level_order int,
no_priv int
) ENGINE=MEMORY;

create temporary table TempTableID ( 
folder_id int
) ENGINE=MEMORY;

select count(*) into vLevelExists
from mydbr_folders
where folder_id = inLevel;

if (vLevelExists>0) then
  set vLevel_order = 1;
  while isnull(inLevel)=0 do
    select mother_id, name into vMother_id, vName
    from mydbr_folders
    where folder_id = inLevel;

    insert into TempTable values (inLevel, vName, vLevel_order, 0);
    set inLevel = vMother_id;
    set vLevel_order = vLevel_order + 1;
  end while;
else 
  insert into TempTable 
  select 1, name, 1, 0
  from mydbr_folders
  where folder_id = 1;
end if;  

if (isAdmin=0) then
  insert into TempTableID 
  select folder_id
  from TempTable;

  update TempTable
  set no_priv = 1
  where folder_id not in (
    select p.folder_id
    from TempTableID t, mydbr_folders_priv p
    where p.folder_id = t.folder_id and 
      ( ((p.username = inUsername and p.authentication=inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
      and p.group_id = 0 )
      or p.group_id in (
        select u.group_id
        from mydbr_groupsusers u
        where u.user = inUsername and u.authentication=inAuth
      )
  );
end if;


select folder_id, name, no_priv
from TempTable
order by level_order desc;

drop temporary table TempTable;
drop temporary table TempTableID;
end 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupNewUserAdd`
$$
CREATE PROCEDURE `sp_MyDBR_GroupNewUserAdd`(inGroupID int, inNameSearch varchar(128), inAuth int)
BEGIN
declare vCnt int;
select count(*) into vCnt
from mydbr_groupsusers m
where group_id = inGroupID and user= inNameSearch and authentication = inAuth;

if (vCnt=0) then
  insert into mydbr_groupsusers (group_id, user, authentication)
  values (inGroupID, inNameSearch, inAuth);
end if;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupNewUserGet` 
$$
CREATE PROCEDURE `sp_MyDBR_GroupNewUserGet`( inGroupID int, inNameSearch varchar(128), inAuth int)
BEGIN
declare vAuth_DB int;
declare vAuth_myDBR int;
declare vAuth_SSO int;
declare vAuth_LDAP int;

select (inAuth & 1) into vAuth_DB;
select (inAuth & 2) into vAuth_myDBR;
select (inAuth & 4) into vAuth_SSO;
select (inAuth & 8) into vAuth_LDAP;

create temporary table Users_tmp (
user varchar(128) not null,
name varchar(60) default null,
auth_source int
) ENGINE=MEMORY;

select lower(inNameSearch) into inNameSearch;

if (vAuth_DB > 0) then
  insert into Users_tmp ( user, name, auth_source )
  select u.User, i.Full_name, vAuth_DB
  from mysql.user u left join mysql.user_info i on ( u.user = i.User )
  where lower(u.user) like concat('%', inNameSearch, '%') and u.user != 'root' and u.user not in (
    select m.user
    from mydbr_groupsusers m
    where group_id = inGroupID and m.authentication= vAuth_DB
  );
end if;

if (vAuth_myDBR > 0 or vAuth_SSO > 0 or vAuth_LDAP > 0) then
  insert into Users_tmp ( user, name, auth_source )
  select u.user, u.name, u.authentication
  from mydbr_userlogin u
  where (lower(u.user) like concat('%', inNameSearch, '%') or lower(u.name) like concat('%', inNameSearch, '%'))
  and u.user != 'root' 
  and u.authentication in (2, 4, 8)
  and not exists (
    select *
    from mydbr_groupsusers m
    where m.user=u.user and m.authentication=u.authentication
    and m.group_id = inGroupID
  );
end if;

select t.user, t.name, a.name, t.auth_source
from Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask;

drop temporary table Users_tmp;
END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupUpdate`
$$
CREATE PROCEDURE `sp_MyDBR_GroupUpdate`(inGroupID int, inName varchar(100))
BEGIN
update mydbr_groups 
set  name = inName
where group_id = inGroupID;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupUsersDel`
$$
CREATE PROCEDURE `sp_MyDBR_GroupUsersDel`( inGroupID int, inUsername varchar(128), inAuth int)
BEGIN
delete 
from mydbr_groupsusers
where group_id = inGroupID and user = inUsername and authentication = inAuth;
END 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupUsersDelUser`
$$
CREATE PROCEDURE `sp_MyDBR_GroupUsersDelUser`( inUsername varchar(128), inAuth int)
BEGIN

delete 
from mydbr_groupsusers
where user = inUsername and authentication = inAuth;

END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_GroupUsersGet`
$$
CREATE PROCEDURE `sp_MyDBR_GroupUsersGet`(inGroupID int, inAuth int)
BEGIN
declare vAuth_DB int;
declare vAuth_myDBR int;
declare vAuth_SSO int;
declare vAuth_LDAP int;
declare vAuth_Custom int;

select (inAuth & 1) into vAuth_DB;
select (inAuth & 2) into vAuth_myDBR;
select (inAuth & 4) into vAuth_SSO;
select (inAuth & 8) into vAuth_LDAP;
select (inAuth & 16) into vAuth_Custom;

create temporary table Users_tmp (
user varchar(128) not null,
name varchar(60) default null,
auth_source int
) ENGINE=MEMORY;

if (vAuth_DB > 0) then
  insert into Users_tmp ( user, name, auth_source )
  select u.user, i.Full_name, u.authentication
  from mydbr_groupsusers u left join mysql.user_info i on ( u.user = i.User )
  where u.group_id = inGroupID and u.authentication=vAuth_DB;
end if;

if (vAuth_myDBR > 0 or vAuth_SSO > 0 or vAuth_LDAP > 0 or vAuth_Custom > 0) then
  insert into Users_tmp ( user, name, auth_source )
  select u.user, i.name, i.authentication
  from mydbr_groupsusers u, mydbr_userlogin i
  where u.user = i.user and u.group_id = inGroupID and u.authentication=i.authentication and
  i.authentication in (2,4,8,16);
end if;

select t.user, t.name, a.name, t.auth_source
from Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask;

drop temporary table Users_tmp;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamClear`
$$
CREATE PROCEDURE `sp_MyDBR_ParamClear`(
inProcname varchar(100)
)
begin
delete from mydbr_params
where proc_name=inProcname;
end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamDefaultGet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamDefaultGet`(
inProcname varchar(100)
)
begin
select p.param, m.query
from mydbr_params p, mydbr_param_queries m
where p.proc_name=inProcname and default_value=m.name and p.default_value is not null;
end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamDefaultsGet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamDefaultsGet`()
BEGIN

select name, query, coltype
from mydbr_param_queries
where coltype = 3
order by name;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamGet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamGet`(
inProcname varchar(100)
)
begin
select param, query_name, title, default_value, ifnull(optional,0), ifnull(only_default,0), suffix, options
from mydbr_params
where proc_name=inProcname;
end 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_param_in_use`
$$
CREATE PROCEDURE `sp_MyDBR_param_in_use`(
inName varchar(50)
)
begin

select r.report_id, p.proc_name, r.name, r.folder_id
from mydbr_params p
  join mydbr_reports r on r.proc_name=p.proc_name
where query_name=inName or default_value=inName;

end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamQueriesGet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamQueriesGet`( inAll tinyint )
BEGIN

if (inAll=100) then
  select q.name, q.query, q.coltype, q.options, count(distinct p.proc_name) as 'count'
  from mydbr_param_queries q
    left join mydbr_params p on q.coltype=3 and p.default_value=q.name or q.coltype!=3 and p.query_name = q.name
  where q.coltype < inAll or (inAll=3 and q.coltype>3)
  group by q.name, q.query, q.coltype, q.options
  order by q.name;
else
  select name, query, coltype, options
  from mydbr_param_queries
  where coltype < inAll or (inAll=3 and coltype>3)
  order by name;
end if;

END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamQueryAdd`
$$
CREATE PROCEDURE `sp_MyDBR_ParamQueryAdd`(
inName varchar(50), 
inQuery varchar(4096), 
inColType tinyint,
inOptions varchar(255)
)
BEGIN
declare vCnt int;

select count(*) into vCnt
from mydbr_param_queries
where name = inName;

if (vCnt=0) then
  insert into mydbr_param_queries ( name, query, coltype, options )
  values ( inName, inQuery, inColType, inOptions );

  select 'OK', null;
else
  select 'Error', concat("Parameter query named '", inName, "' already exists.");
end if;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamQueryDel`
$$
CREATE PROCEDURE `sp_MyDBR_ParamQueryDel`(inName varchar(50))
BEGIN
declare vCnt int;
declare v_type int;

select coltype into v_type
from mydbr_param_queries
where name = inName;

if (v_type=3) then
  select count(*) into vCnt 
  from mydbr_params
  where default_value = inName;
else 
  select count(*) into vCnt 
  from mydbr_params
  where query_name = inName;
end if;

if (vCnt = 0) then
  delete 
  from mydbr_param_queries
  where name = inName;

  select 'OK', null;
else
  select 'ERROR', 'Cannot delete parameter query in use!';
end if;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamQueryGet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamQueryGet`(inName varchar(50))
BEGIN
select query, coltype, options
from mydbr_param_queries
where name=inName;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamQueryUpdate`
$$
CREATE PROCEDURE `sp_MyDBR_ParamQueryUpdate`(
inName varchar(50), 
inQuery varchar(4096), 
inColType tinyint,
inOptions varchar(255)
)
BEGIN
update mydbr_param_queries
set query = inQuery, coltype=inColType, options=inOptions
where name = inName;
select 'OK';
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ParamSet`
$$
CREATE PROCEDURE `sp_MyDBR_ParamSet`(
inProcname varchar(100),
inParam varchar(100),
inQuery varchar(50),
inTitle varchar(255),
inDefault varchar(50),
inOptional int,
inOnlyDefault int,
inSuffix varchar(255),
inOptions varchar(1024)
)
begin
insert into mydbr_params (proc_name, param, query_name, title, default_value, optional, only_default, suffix, options)
values (inProcname, inParam, inQuery, inTitle, inDefault, inOptional, inOnlyDefault, inSuffix, inOptions);
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportGetIDByName`
$$
CREATE PROCEDURE `sp_MyDBR_ReportGetIDByName`(inProc varchar(100))
begin
select report_id, name
from mydbr_reports
where  proc_name = inProc;
END
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ProcParams` 
$$
CREATE PROCEDURE `sp_MyDBR_ProcParams`(inProc_id int, inParam_list varchar(2500), inUseConverted tinyint )
begin
declare vParam_list varchar(2500);
declare vParam varchar(100);
declare vLength int;
declare vTypeDef varchar(100);
declare vProc_name varchar(100);
declare vNextComma int;
declare vRealComma int;
declare vParamOrder int;
declare v_extra_defs int;

set vParam_list = replace(inParam_list, char(9), ' ');

select proc_name into vProc_name
from mydbr_reports
where report_id = inProc_id;

if (vProc_name is null) then
  select 'No such procedure' as 'param_name', 'error' as 'type', 0 as 'strlength';
else

create temporary table ReturnValuesTmp (
param_order int,
param_name varchar(100),
type varchar(20),
strlength int
) ENGINE=MEMORY;


set vParamOrder = 1;

while(length(vParam_list)>0) do

  while (ascii(vParam_list) in (32, 10, 13, 9)) do
    set vParam_list = substring( vParam_list, 2);
  end while;
  
  set vLength = 0;
  
    select substring_index(vParam_list, ',', 1) into vParam;
  
  if (locate("(", vParam) > 0 and locate(")", vParam)=0 ) then
    set vNextComma = locate(",", vParam_list);
    set vRealComma = locate(",", substring(vParam_list, vNextComma+1));
    if (vRealComma>0) then
      set vParam =  substring(vParam_list, 1, vNextComma+vRealComma);
    else
      set vParam = vParam_list;
    end if;
  end if;
    select substring(vParam_list, length(vParam)+1) into vParam_list;
    if (length(vParam_list)>0) then
        if (left(vParam_list,1)=',') then
            select trim(substr(vParam_list,2)) into vParam_list;
        end if;
    end if;
    select trim(vParam) into vParam;
  
  while (ascii(vParam) in (10, 13, 9)) do
    set vParam = substring( vParam, 2);
  end while;
    if (vParam like "in %") then
        select trim(substr(vParam,4)) into vParam;
    end if;
    if (vParam like "out %") then
        select trim(substr(vParam,5)) into vParam;
    end if;
    if (vParam like "inout %") then
        select trim(substr(vParam,7)) into vParam;
    end if;
    if (vParam like '%(%)%' and vParam not like '%(%,%)%') then
        set vLength = substring(vParam,locate('(',vParam)+1,locate(')', vParam)-locate('(',vParam)-1);
    end if;
    set vTypeDef = substring(vParam, locate(' ',vParam)+1);
    if (vTypeDef like '%(%)%') then
        select substring( vTypeDef, 1,  locate('(', vTypeDef)-1) into vTypeDef;
    end if;
  set vTypeDef = replace( vTypeDef, char(10), '');
  set vTypeDef = replace( vTypeDef, char(13), '');
  set vTypeDef = replace( vTypeDef, char(9), '');
  set vTypeDef = trim(vTypeDef);
  
  /* Things like "text CHARACTER SET utf8" */
  select locate(' ', vTypeDef) into v_extra_defs;
  if (v_extra_defs>0) then
    set vTypeDef = substring(vTypeDef, 1, v_extra_defs-1);
  end if;
  
  insert into ReturnValuesTmp
  select vParamOrder, substring_index(vParam, ' ', 1), vTypeDef, vLength;
  set vParamOrder = vParamOrder + 1;
end while;

update ReturnValuesTmp 
set strlength= case lower(type) 
    when 'tinytext' then 255
    when 'text' then 65536
    when 'mediumtext' then 16777215
    when 'mediumblob' then 16777215
    when 'longtext' then 4294967295
    when 'longblob' then 4294967295
    else 255
    end
where lower(type) like '%text%';

update ReturnValuesTmp set type='string'
where lower(type) in ('char', 'varchar', 'blob') or lower(type) like '%text%' or lower(type) like '%blob%';

update ReturnValuesTmp set type='integer'
where lower(type) in ('tinyint', 'smallint', 'mediumint', 'int', 'integer', 'bigint', 'year');

update ReturnValuesTmp set type='float'
where lower(type) in ('float', 'decimal', 'double', 'double precision', 'real', 'dec', 'numeric', 'fixed');

update ReturnValuesTmp set type='datetime'
where lower(type) in ('timestamp');

if (inUseConverted=0) then
  select param_name, type, strlength
  from ReturnValuesTmp
  order by param_order;
else 
  select t.param_name, t.type, t.strlength, p.title, p.query_name, t.param_order, ifnull(p.optional,0), ifnull(p.only_default,0), p.suffix, p.options
  from ReturnValuesTmp t left join mydbr_params p on (t.param_name = p.param and p.proc_name=vProc_name)
  order by t.param_order;
end if;
drop temporary table ReturnValuesTmp;
end if;
end 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportDel` 
$$
CREATE PROCEDURE `sp_MyDBR_ReportDel`(inReportID int)
BEGIN
declare vProcName varchar(100); 

select proc_name into vProcName
from mydbr_reports
where report_id = inReportID;

delete from mydbr_favourite_reports where report_id = inReportID;
delete from mydbr_reports_priv where report_id = inReportID;
delete from mydbr_params where proc_name = vProcName;
delete from mydbr_report_extensions where proc_name = vProcName;

delete 
from mydbr_reports
where report_id = inReportID;

END 
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ProcedureParams`
$$
CREATE PROCEDURE `sp_MyDBR_ProcedureParams`( inParam_list varchar(2500) )
begin
declare vParam_list varchar(2500);
declare vParam varchar(100);
declare vLength int;
declare vTypeDef varchar(100);
declare vNextComma int;
declare vRealComma int;
declare vParamOrder int;

set vParam_list = replace(inParam_list, char(9), ' ');

create temporary table ReturnValuesTmp (
param_order int,
param_name varchar(100),
type varchar(20),
strlength bigint
) ENGINE=MEMORY;


set vParamOrder = 1;

while(length(vParam_list)>0) do

  while (ascii(vParam_list) in (32, 10, 13, 9)) do
    set vParam_list = substring( vParam_list, 2);
  end while;
  
  set vLength = 0;
  
    select substring_index(vParam_list, ',', 1) into vParam;
  
  if (locate("(", vParam) > 0 and locate(")", vParam)=0 ) then
    set vNextComma = locate(",", vParam_list);
    set vRealComma = locate(",", substring(vParam_list, vNextComma+1));
    if (vRealComma>0) then
      set vParam =  substring(vParam_list, 1, vNextComma+vRealComma);
    else
      set vParam = vParam_list;
    end if;
  end if;
    select substring(vParam_list, length(vParam)+1) into vParam_list;
    if (length(vParam_list)>0) then
        if (left(vParam_list,1)=',') then
            select trim(substr(vParam_list,2)) into vParam_list;
        end if;
    end if;
    select trim(vParam) into vParam;
  
  while (ascii(vParam) in (10, 13, 9)) do
    set vParam = substring( vParam, 2);
  end while;
    if (vParam like "in %") then
        select trim(substr(vParam,4)) into vParam;
    end if;
    if (vParam like "out %") then
        select trim(substr(vParam,5)) into vParam;
    end if;
    if (vParam like "inout %") then
        select trim(substr(vParam,7)) into vParam;
    end if;
    if (vParam like '%(%)%' and vParam not like '%(%,%)%') then
        set vLength = substring(vParam,locate('(',vParam)+1,locate(')', vParam)-locate('(',vParam)-1);
    end if;
    set vTypeDef = substring(vParam, locate(' ',vParam)+1);
    if (vTypeDef like '%(%)%') then
        select substring( vTypeDef, 1,  locate('(', vTypeDef)-1) into vTypeDef;
    end if;
  set vTypeDef = replace( vTypeDef, char(10), '');
  set vTypeDef = replace( vTypeDef, char(13), '');
  set vTypeDef = replace( vTypeDef, char(9), '');
  set vTypeDef = trim(vTypeDef);
    insert into ReturnValuesTmp
    select vParamOrder, substring_index(vParam, ' ', 1), vTypeDef, vLength;
  set vParamOrder = vParamOrder + 1;
end while;

update ReturnValuesTmp 
set strlength= case lower(type) 
    when 'tinytext' then 255
    when 'text' then 65536
    when 'mediumtext' then 16777215
    when 'mediumblob' then 16777215
    when 'longtext' then 4294967295
    when 'longblob' then 4294967295
    else 255
    end
where lower(type) like '%text' or lower(type) like '%blob';

update ReturnValuesTmp set type='string'
where lower(type) in ('char', 'varchar', 'blob') or lower(type) like '%text' or lower(type) like '%blob';

update ReturnValuesTmp set type='integer'
where lower(type) in ('tinyint', 'smallint', 'mediumint', 'int', 'integer', 'bigint', 'year');

update ReturnValuesTmp set type='float'
where lower(type) in ('float', 'decimal', 'double', 'double precision', 'real');

update ReturnValuesTmp set type='datetime'
where lower(type) in ('timestamp');

select param_name, type, strlength
from ReturnValuesTmp
order by param_order;

drop temporary table ReturnValuesTmp;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportDel`
$$
CREATE PROCEDURE `sp_MyDBR_ReportDel`(inReportID int)
BEGIN
declare vProcName varchar(100); 

select proc_name into vProcName
from mydbr_reports
where report_id = inReportID;

delete from mydbr_reports_priv where report_id = inReportID;
delete from mydbr_params where proc_name = vProcName;
delete from mydbr_report_extensions where proc_name = vProcName;

delete 
from mydbr_reports
where report_id = inReportID;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportInfoGet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportInfoGet`(inReportID int)
BEGIN
select name, proc_name, explanation, reportgroup, sortorder, runreport, autoexecute, parameter_help, export
from mydbr_reports
where report_id = inReportID;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportInfoSet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportInfoSet`(
inReportID int, 
inReportName varchar(150), 
inExplanation varchar(4096),
inReportgroup int,
inSortorder int,
inRunreport varchar(50),
inAutoexecute tinyint,
inParameter_help varchar(10000),
inExport varchar(10)
)
BEGIN
update mydbr_reports
set name = inReportName, explanation=inExplanation, reportgroup=inReportgroup, sortorder=inSortorder,
  runreport=inRunreport, autoexecute=inAutoexecute, parameter_help=inParameter_help, export=inExport
where report_id = inReportID;
select 'OK';
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportIsValidForMe`
$$
CREATE PROCEDURE `sp_MyDBR_ReportIsValidForMe`(inSPreport char(64), inUsername varchar(80), inAuth int)
BEGIN
declare vIAmAdmin int;

call sp_MyDBR_AmIAdminOut(inUsername, inAuth, vIAmAdmin);

select 'OK', r.report_id, r.name
from mydbr_reports r
where r.proc_name = inSPreport and (vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = inUsername and p.authentication=inAuth) or (p.username in ('PUBLIC', 'MYDBR_WEB')  and p.authentication=0))
    and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.user = inUsername and u.authentication=inAuth and p.group_id != 0
));

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportMove`
$$
CREATE PROCEDURE `sp_MyDBR_ReportMove`( vID int, vFolder int )
BEGIN

update mydbr_reports
set folder_id=vFolder
where report_id = vID;

END
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportNameGet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportNameGet`(
InReport_id int, 
inUsername varchar(80), 
inAuth int, 
inProcName varchar(150)
)
begin
declare vReportName varchar(150);
declare vProcName varchar(150);
declare vRunbutton varchar(50);
declare vHasPriv int;
declare vIAmAdmin int;
declare vFolder_id int;
declare vReportID int;
declare vAutoexecute tinyint;
declare vParameter_help varchar(10000);
declare vExport varchar(10);

call sp_MyDBR_AmIAdminOut(inUsername, inAuth, vIAmAdmin);

select r.name, r.proc_name, r.folder_id, r.report_id, r.runreport, r.autoexecute, r.parameter_help, r.export
  into vReportName, vProcName, vFolder_id, vReportID, vRunbutton, vAutoexecute, vParameter_help, vExport
from mydbr_reports r
where (r.report_id=InReport_id or r.proc_name=inProcName) and (vIAmAdmin = 1 or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p
  where ((p.username = inUsername and p.authentication=inAuth) or (p.username in ('PUBLIC', 'MYDBR_WEB') and p.authentication=0))
  and p.group_id = 0
) or r.report_id in (
  select p.report_id
  from mydbr_reports_priv p, mydbr_groupsusers u
  where p.group_id = u.group_id and u.user = inUsername and u.authentication=inAuth and p.group_id != 0
));

if (vProcName is null) then
    select 0, 'No access privileges', null, 1, 0, null;
else
    select 1, vReportName, vProcName, vFolder_id, vReportID, vRunbutton, vAutoexecute, vParameter_help, vExport;
end if;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportNew`
$$
CREATE PROCEDURE `sp_MyDBR_ReportNew`(
inLevel int, 
inReportName varchar(150), 
inStored_proc varchar(100), 
inExplanation varchar(4096),
inReportgroup int,
inSortorder int,
inRunreport varchar(50)
)
BEGIN
declare vReport_id int;

select report_id into vReport_id
from mydbr_reports
where proc_name = inStored_proc and folder_id = inLevel;

if (vReport_id>0) then
  select 'OK', vReport_id;
else
  insert into mydbr_reports (name, proc_name, folder_id, explanation, reportgroup, sortorder, runreport )
  values( inReportName, inStored_proc, inLevel, inExplanation, inReportgroup, inSortorder, inRunreport );

  select 'OK', last_insert_id();
end if;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportNewGet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportNewGet`(inProcname varchar(30), inProcPrefix varchar(10))
BEGIN

if (isnull(inProcname)) then
  set inProcname = '';
end if;

select p.ROUTINE_NAME 
from information_schema.ROUTINES p 
where routine_schema=database() and routine_type='PROCEDURE' and p.ROUTINE_NAME not like 'sp_MyDBR_%' 
and p.ROUTINE_NAME like concat( cast( inProcPrefix AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci, '%') 
and p.ROUTINE_NAME like concat('%', cast( inProcname  AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci,'%') 
and p.ROUTINE_NAME not in (
  select cast( proc_name AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci
  from mydbr_reports
)
order by p.LAST_ALTERED desc;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportPrivAdd`
$$
CREATE PROCEDURE `sp_MyDBR_ReportPrivAdd`( inReportID int, inUsername varchar(128), inAuth int, inGroupID int )
BEGIN

declare vCnt int;

select count(*) into vCnt
from mydbr_reports_priv
where report_id=inReportID and username=inUsername and group_id= inGroupID and authentication=inAuth;

if (vCnt=0) then
  insert into mydbr_reports_priv (report_id, username, authentication, group_id)
  values ( inReportID, inUsername, inAuth, inGroupID );
end if;

END
$$



DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderPrivAdd` 
$$
CREATE PROCEDURE `sp_MyDBR_FolderPrivAdd`( inFolderID int, inUsername varchar(128), inAuth int, inGroupID int )
BEGIN

declare vCnt int;

select count(*) into vCnt
from mydbr_folders_priv
where folder_id=inFolderID and username=inUsername and group_id= inGroupID and authentication=inAuth;

if (vCnt=0) then
  insert into mydbr_folders_priv (folder_id, username, authentication, group_id)
  values ( inFolderID, inUsername, inAuth, inGroupID );
end if;

END 
$$



DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportPrivDel` 
$$
CREATE PROCEDURE `sp_MyDBR_ReportPrivDel`( inReportID int, inUsername varchar(128), inAuth int, inGroupID int )
BEGIN

delete from mydbr_reports_priv
where report_id=inReportID and username=inUsername and group_id=inGroupID and authentication= inAuth;

END 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderPrivDel` 
$$
CREATE PROCEDURE `sp_MyDBR_FolderPrivDel`( inFolderID int, inUsername varchar(128), inAuth int, inGroupID int )
BEGIN

delete from mydbr_folders_priv
where folder_id=inFolderID and username=inUsername and group_id=inGroupID and authentication= inAuth;

END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportPrivsGroupGet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportPrivsGroupGet`( inReportID int )
BEGIN
select p.group_id, g.name, 1
from mydbr_reports_priv p, mydbr_groups g
where p.group_id>0 and p.report_id = inReportID and p.group_id=g.group_id
union
select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select p.group_id
  from mydbr_reports_priv p
  where p.group_id>0 and p.report_id = inReportID
)
order by 3 desc,2;
END 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderPrivsGroupGet` 
$$
CREATE PROCEDURE `sp_MyDBR_FolderPrivsGroupGet`( inFolderID int )
BEGIN
select p.group_id, g.name, 1
from mydbr_folders_priv p, mydbr_groups g
where p.group_id>0 and p.folder_id = inFolderID and p.group_id=g.group_id
union
select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select p.group_id
  from mydbr_folders_priv p
  where p.group_id>0 and p.folder_id = inFolderID
)
order by 3 desc, 2;
END 
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportPrivsUserGet`
$$
CREATE PROCEDURE `sp_MyDBR_ReportPrivsUserGet`(inReportID int, inAuth int, inSearch varchar(30))
BEGIN
declare vAuth_DB int;
declare vAuth_myDBR int;
declare vAuth_SSO int;
declare vAuth_LDAP int;
declare vAuth_Custom int;

select (inAuth & 1) into vAuth_DB;
select (inAuth & 2) into vAuth_myDBR;
select (inAuth & 4) into vAuth_SSO;
select (inAuth & 8) into vAuth_LDAP;
select (inAuth & 16) into vAuth_Custom;

create temporary table Users_tmp (
user varchar(128) not null,
name varchar(60) default null,
auth_source int,
haspriv int
) ENGINE=MEMORY;


if (vAuth_DB > 0) then
  insert into Users_tmp ( user, name, auth_source, haspriv )
  select p.username, i.Full_name, vAuth_DB, 1
  from mysql.user u, mydbr_reports_priv p left join mysql.user_info i on ( p.username = i.User )
  where p.username!='' and p.username = u.user and p.report_id = inReportID and p.authentication= vAuth_DB;

  if (inSearch!='') then
    insert into Users_tmp ( user, name, auth_source, haspriv )
    select u.user, i.Full_name, vAuth_DB, 0
    from mysql.user u, mydbr_reports_priv p left join mysql.user_info i on ( u.user = i.User )
    where u.user like concat('%', inSearch ,'%') and not exists (
      select * 
      from mydbr_reports_priv p
      where p.username = u.user and p.report_id = inReportID and p.authentication= vAuth_DB
    )
    LIMIT 20;
  end if;
end if;

if (vAuth_myDBR > 0 or vAuth_SSO > 0 or vAuth_LDAP > 0 or vAuth_Custom > 0) then
  insert into Users_tmp ( user, name, auth_source, haspriv )
  select p.username, u.name, u.authentication, 1
  from mydbr_userlogin u, mydbr_reports_priv p
  where p.username!='' and p.username = u.user and p.authentication=u.authentication
    and p.report_id = inReportID 
    and p.authentication in (2,4,8,16);

  if (inSearch!='') then
    insert into Users_tmp ( user, name, auth_source, haspriv )
    select u.user, u.name, u.authentication, 0
    from mydbr_userlogin u
    where (u.user like concat('%', inSearch ,'%') or u.name like concat('%', inSearch ,'%')) and not exists (
      select * 
      from mydbr_reports_priv p
      where p.username = u.user and p.authentication=u.authentication and 
      p.report_id = inReportID and 
      p.authentication in (2,4,8,16)
    )
    LIMIT 20;
  end if;
end if;


select t.user, t.name, a.name, t.auth_source, t.haspriv
from Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask
union
select p.username, null, null, 0, 1
from mydbr_reports_priv p
where p.report_id = inReportID and p.authentication=0 and p.username in ('PUBLIC', 'MYDBR_WEB')
order by 5 desc, 1;

drop temporary table Users_tmp;

END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_FolderPrivsUserGet`
$$
CREATE PROCEDURE `sp_MyDBR_FolderPrivsUserGet`(inFolderID int, inAuth int, inSearch varchar(200) )
BEGIN
declare vAuth_DB int;
declare vAuth_myDBR int;
declare vAuth_SSO int;
declare vAuth_LDAP int;
declare vAuth_Custom int;

select (inAuth & 1) into vAuth_DB;
select (inAuth & 2) into vAuth_myDBR;
select (inAuth & 4) into vAuth_SSO;
select (inAuth & 8) into vAuth_LDAP;
select (inAuth & 16) into vAuth_Custom;

create temporary table Users_tmp (
user varchar(128) not null,
name varchar(60) default null,
auth_source int,
haspriv int
) ENGINE=MEMORY;


if (vAuth_DB > 0) then
  insert into Users_tmp ( user, name, auth_source, haspriv )
  select p.username, i.Full_name, vAuth_DB, 1
  from mysql.user u, mydbr_folders_priv p left 
    join mysql.user_info i on ( p.username = i.User )
  where p.username!='' and p.username = u.user and p.folder_id = inFolderID and p.authentication= vAuth_DB;

  if (inSearch!='') then
    insert into Users_tmp ( user, name, auth_source, haspriv )
    select u.user, i.Full_name, vAuth_DB, 0
    from mysql.user u, mydbr_folders_priv p left join mysql.user_info i on ( u.user = i.User )
    where u.user like concat('%', inSearch ,'%') and not exists (
      select * 
      from mydbr_folders_priv p
      where p.username = u.user and p.folder_id = inFolderID and p.authentication= vAuth_DB
    )
    LIMIT 20;
  end if;
end if;

if (vAuth_myDBR > 0 or vAuth_SSO > 0 or vAuth_LDAP > 0 or vAuth_Custom>0) then
  insert into Users_tmp ( user, name, auth_source, haspriv )
  select p.username, u.name, u.authentication, 1
  from mydbr_userlogin u, mydbr_folders_priv p
  where p.username!='' and p.username = u.user and p.authentication=u.authentication
    and p.folder_id = inFolderID 
    and p.authentication in (2,4,8,16);

  if (inSearch!='') then
    insert into Users_tmp ( user, name, auth_source, haspriv )
    select u.user, u.name, u.authentication, 0
    from mydbr_userlogin u
    where (u.user like concat('%', inSearch ,'%') or u.name like concat('%', inSearch ,'%')) and not exists (
      select * 
      from mydbr_folders_priv p
      where p.username = u.user and p.authentication=u.authentication and 
      p.folder_id = inFolderID and 
      p.authentication in (2,4,8,16)
    )
    LIMIT 20;
  end if;
end if;


select t.user, t.name, a.name, t.auth_source, t.haspriv
from Users_tmp t, mydbr_authentication a
where t.auth_source = a.mask
union
select p.username, null, null, 0, 1
from mydbr_folders_priv p
where p.folder_id = inFolderID and p.authentication=0 and p.username in ('PUBLIC')
order by 5 desc, 1;

drop temporary table Users_tmp;

END
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_FolderHavePrivs 
$$
CREATE PROCEDURE sp_MyDBR_FolderHavePrivs(inLevel int, inUsername varchar(80), inAuth int)
begin
declare vIAmAdmin int;

call sp_MyDBR_AmIAdminOut(inUsername, inAuth, vIAmAdmin);

if (vIAmAdmin = 1) then 
  select 1;
else
  select count(*)
  from mydbr_folders_priv p
  where p.folder_id = inLevel and 
    ( ((p.username = inUsername and p.authentication=inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
    and p.group_id = 0 )
    or p.group_id in (
      select u.group_id
      from mydbr_groupsusers u
      where u.user = inUsername and u.authentication=inAuth
    );
end if;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportsShow` 
$$
CREATE PROCEDURE `sp_MyDBR_ReportsShow`(inLevel int, inUsername varchar(80), inAuth int)
begin
declare vIAmAdmin int;

call sp_MyDBR_AmIAdminOut(inUsername, inAuth, vIAmAdmin);

select  f.folder_id as 'folderID', 
        null as 'report_id', 
        f.name, 
        0 as 'hasgrant', 
        f.explanation, 
        1 as 'isReport', 
        f.reportgroup, 
        g.sortorder as 'gsortorder',
        g.name as 'gname',
        g.color as 'color',
        '',
        0  as 'rsortorder',
        '' as 'directurl',
        0 as 'notinuse',
        null as 'export'
from mydbr_folders f, mydbr_reportgroups g
where f.reportgroup=g.id and f.mother_id=inLevel and (vIAmAdmin = 1 or f.folder_id in (
    select p.folder_id
    from mydbr_folders_priv p
    where ((p.username = inUsername and p.authentication=inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
    and p.group_id = 0
    union
    select p.folder_id
    from mydbr_folders_priv p, mydbr_groupsusers u
    where p.group_id = u.group_id and u.user = inUsername and u.authentication=inAuth
))
union
select  null, 
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
        '' as 'directurl',
        0,
        r.export
from mydbr_reports r, mydbr_reportgroups g
where r.reportgroup=g.id and r.folder_id = inLevel and (vIAmAdmin = 1 or r.report_id in (
    select p.report_id
    from mydbr_reports_priv p
    where ((p.username = inUsername and p.authentication=inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
    and p.group_id = 0
    union
    select p.report_id
    from mydbr_reports_priv p, mydbr_groupsusers u
    where p.group_id = u.group_id and u.user = inUsername and u.authentication=inAuth and p.group_id!=0
))
union
select  null, 
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
        f.url as 'directurl',
        0,
        r.export
from mydbr_reports r, mydbr_reportgroups g, mydbr_favourite_reports f
where f.user=inUsername and f.authentication=inAuth and f.report_id=r.report_id
and g.id=-1 and inLevel=1
and (vIAmAdmin = 1 or r.report_id in (
    select p.report_id
    from mydbr_reports_priv p
    where ((p.username = inUsername and p.authentication=inAuth) or (p.username = 'PUBLIC' and p.authentication=0))
    and p.group_id = 0
    union
    select p.report_id
    from mydbr_reports_priv p, mydbr_groupsusers u
    where p.group_id = u.group_id and u.user = inUsername and u.authentication=inAuth and p.group_id!=0
))
order by gsortorder, reportgroup, isReport, rsortorder, 3;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportsShow_Privs` 
$$
CREATE PROCEDURE `sp_MyDBR_ReportsShow_Privs`(inLevel int, inUsername varchar(80), inAuth int)
begin

create temporary table tmp_report_ids (
report_id int
) ENGINE=MEMORY;

create temporary table tmp_report_result (
report_id int,
type varchar(20),
name varchar(128)
) ENGINE=MEMORY;

create temporary table tmp_folder_result (
folder_id int,
type varchar(20),
name varchar(128)
) ENGINE=MEMORY;


insert into tmp_report_ids (report_id)
select r.report_id
from mydbr_reports r
where r.folder_id = inLevel 
union
select   r.report_id
from mydbr_reports r, mydbr_reportgroups g, mydbr_favourite_reports f
where f.user=inUsername and f.authentication=inAuth and f.report_id=r.report_id
and g.id=-1 and inLevel=1;

insert into tmp_report_result 
select p.report_id, 'user', u.name
from mydbr_reports_priv p, mydbr_userlogin u
where p.username = u.user and p.authentication=u.authentication
and p.report_id in (
  select report_id
  from tmp_report_ids
);

insert into tmp_folder_result 
select p.folder_id, 'user', u.name
from mydbr_folders_priv p, mydbr_userlogin u, mydbr_folders f
where p.username = u.user and p.authentication=u.authentication
and p.folder_id = f.folder_id and f.mother_id= inLevel;


insert into tmp_report_result 
select p.report_id, 'group', g.name
from mydbr_reports_priv p, mydbr_groups g
where p.group_id = g.group_id
and p.report_id in (
  select report_id
  from tmp_report_ids
);

insert into tmp_folder_result 
select p.folder_id, 'group', g.name
from mydbr_folders_priv p, mydbr_groups g, mydbr_folders f
where p.group_id = g.group_id
and p.folder_id = f.folder_id and f.mother_id= inLevel;


insert into tmp_report_result 
select p.report_id, 'public', p.username
from mydbr_reports_priv p
where p.username in  ('PUBLIC', 'MYDBR_WEB') and p.authentication=0
and p.report_id in (
  select report_id
  from tmp_report_ids
);


insert into tmp_folder_result 
select p.folder_id, 'public', p.username
from mydbr_folders_priv p, mydbr_folders f
where p.username in  ('PUBLIC') and p.authentication=0
and p.folder_id = f.folder_id and f.mother_id= inLevel;


create temporary table tmp_sort (
type varchar(10),
sort_order int
) ENGINE=MEMORY;
create temporary table tmp_sort_f (
type varchar(10),
sort_order int
) ENGINE=MEMORY;

insert into tmp_sort values ('user', 1);
insert into tmp_sort values ('group', 2);
insert into tmp_sort values ('public', 3);
  
insert into tmp_sort_f values ('user', 1);
insert into tmp_sort_f values ('group', 2);
insert into tmp_sort_f values ('public', 3);

select 'report', t.report_id, t.type, t.name, s.sort_order
from tmp_report_result t
  left outer join tmp_sort s on s.type=t.type
union
select 'folder', t.folder_id, t.type, t.name, s.sort_order
from tmp_folder_result t
  left outer join tmp_sort_f s on s.type=t.type
order by 1,2,5,4;


drop temporary table tmp_report_result;
drop temporary table tmp_report_ids;
drop temporary table tmp_folder_result;

drop temporary table tmp_sort;
drop temporary table tmp_sort_f;
end;
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_StatReportIDGet`
$$
CREATE PROCEDURE `sp_MyDBR_StatReportIDGet`()
BEGIN

select report_id 
from mydbr_reports
where proc_name = 'sp_DBR_StatisticsReport';

END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_Stat_AddEnd`
$$
CREATE PROCEDURE `sp_MyDBR_Stat_AddEnd`(inID int)
BEGIN

update mydbr_statistics
set end_time = now()
where id = inID;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_Stat_AddStart`
$$
CREATE PROCEDURE `sp_MyDBR_Stat_AddStart`(
inProc_name varchar(100),
inUsername varchar(128),
inAuthentication int,
inQuery text,
inIPAddress varchar(255), 
inUserAgent text
)
BEGIN

declare vCnt int;
declare vStart_time datetime;
declare vUserAgentHash varchar(50);

select md5(inUserAgent) into vUserAgentHash;

select count(*) into vCnt
from mydbr_user_agents
where hash = vUserAgentHash;

if (vCnt=0) then
  insert into mydbr_user_agents ( hash, user_agent)
  values (vUserAgentHash, inUserAgent);
end if;

select now() into vStart_time;

insert into mydbr_statistics ( proc_name, username, authentication, start_time, query, ip_address, user_agent_hash )
values (inProc_name, inUsername, inAuthentication, vStart_time, inQuery, inIPAddress, vUserAgentHash );

select last_insert_id();
END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_StyleAdd`
$$
CREATE PROCEDURE `sp_MyDBR_StyleAdd`(
inName varchar(30), 
inType varchar(20), 
inDef varchar(400)
)
BEGIN
declare vCnt int;
declare vColType int;
select count(*) into vCnt
from mydbr_styles
where name = inName;
if (vCnt=0) then
  if (inType='column') then
    set vColType = 0;
  else
    set vColType = 1;
  end if;
  insert into mydbr_styles ( name, colstyle, definition )
  values ( inName, vColType, inDef );
  select 'OK', null;
else
  select 'Error', concat("Style '", inName, "' already exists.");
end if;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_StyleDel`
$$
CREATE PROCEDURE `sp_MyDBR_StyleDel`(inName varchar(100))
BEGIN
delete from mydbr_styles
where name = inName;
select 'OK', null;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_StyleGet`
$$
CREATE PROCEDURE `sp_MyDBR_StyleGet`()
BEGIN
select name, if (colstyle=0, 'column', 'row'), definition
from mydbr_styles;
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_StyleUpdate`
$$
CREATE PROCEDURE `sp_MyDBR_StyleUpdate`(inName varchar(30), inType varchar(20), inDef varchar(400))
BEGIN
declare vColType int;
if (inType='column') then
  set vColType = 0;
else
  set vColType = 1;
end if;
update mydbr_styles
set colstyle=vColType, definition = inDef
where name = inName;
select 'OK';
END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_Usage`
$$
CREATE PROCEDURE `sp_MyDBR_Usage`()
BEGIN

select p.routine_definition, p.routine_name, r.name
from information_schema.ROUTINES p, mydbr_reports r
where p.routine_name COLLATE utf8_general_ci=r.proc_name and routine_schema=database();

END 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_UserDel` 
$$
CREATE PROCEDURE `sp_MyDBR_UserDel`(inUser varchar(128), inAuth int)
BEGIN

delete from mydbr_favourite_reports where authentication=inAuth and user = inUser;
delete from mydbr_reports_priv where authentication=inAuth and username = inUser;
delete from mydbr_folders_priv where authentication=inAuth and username = inUser;
delete from mydbr_groupsusers where authentication=inAuth and user = inUser;
delete from mydbr_options where authentication=inAuth and user = inUser;

delete 
from mydbr_userlogin 
where user = inUser and authentication=inAuth;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_UserLogins`
$$
CREATE PROCEDURE `sp_MyDBR_UserLogins`( inDays int )
begin
select u.user, u.name, u.admin, u.email, u.telephone, date_add( u.passworddate, interval inDays day ), a.name, u.authentication
from mydbr_userlogin u, mydbr_authentication a
where u.authentication=a.mask
order by u.name;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_UserLoginsAuth`
$$
CREATE PROCEDURE `sp_MyDBR_UserLoginsAuth`( inAuth int )
begin
select u.user, u.name
from mydbr_userlogin u
where u.authentication=inAuth
order by u.name;
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_UserInfo`
$$
CREATE PROCEDURE `sp_MyDBR_UserInfo`( 
inUsername varchar(128), 
inAuth int 
)
begin
select u.name, u.email, u.telephone
from mydbr_userlogin u
where u.user = inUsername and u.authentication=inAuth;
end 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_UserInfoSet`
$$
CREATE PROCEDURE `sp_MyDBR_UserInfoSet`( 
inUsername varchar(128), 
inAuth int,
inName varchar(60),
inEmail varchar(100),
inTelephone varchar(100)
)
begin

update mydbr_userlogin
set name=inName, email=inEmail, telephone=inTelephone
where user = inUsername and authentication=inAuth;

end 
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_UserNew`
$$
CREATE PROCEDURE `sp_MyDBR_UserNew`(
inUser varchar(128), 
inName varchar(60), 
inPassword varchar(255), 
inAdmin tinyint,
inEmail varchar(100),
inTelephone varchar(100),
inAuth int,
inAskPwChange int
)
BEGIN

declare vExists int;

select count(*) into vExists 
from mydbr_userlogin
where user = inUser and authentication=inAuth;

if (vExists=0) then
  insert into mydbr_userlogin ( user, password, name, admin, passworddate, email, telephone, authentication, ask_pw_change )
  values ( inUser, inPassword, inName, inAdmin, now(), inEmail, inTelephone, inAuth, inAskPwChange );

  select 'OK';
else
  select concat("User '", inUser, "' already exists");
end if;

END 
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_user_groups` 
$$
CREATE PROCEDURE `sp_MyDBR_user_groups`( 
inUser varchar(128), 
inAuth int
)
begin

select g.group_id, g.name, 0
from mydbr_groups g
where g.group_id not in (
  select gu.group_id
  from mydbr_groupsusers gu
  where gu.user=inUser and gu.authentication=inAuth
)
union
select g.group_id, g.name, 1
from mydbr_groups g
  join mydbr_groupsusers gu on g.group_id=gu.group_id
where gu.user=inUser and gu.authentication=inAuth
order by 3 desc, 2;

end
$$

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_MyDBR_sso_user`
$$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ext_user`
$$
CREATE PROCEDURE `sp_MyDBR_ext_user`(
inUser varchar(128), 
inName varchar(60),
inEmail varchar(100),
inTelephone varchar(100),
inAdmin int,
inAuth int
)
BEGIN
  declare vExists int;
  declare vName varchar(60);
  declare vEmail varchar(100);
  declare vTelephone varchar(100);
  declare vAdmin int;

  select 1, name, email, telephone, admin into vExists, vName, vEmail, vTelephone, vAdmin
  from mydbr_userlogin
  where user = inUser and authentication=inAuth;
  
  if ( vExists = 1 ) then
    /* email & admin can be null */
    select ifnull( inEmail, vEmail ) into inEmail;
    select ifnull( inAdmin, vAdmin ) into inAdmin;
    select ifnull( inTelephone, vTelephone ) into inTelephone;
  
    if (vName!=inName or ifnull(vEmail,'')!=ifnull(inEmail,'') or ifnull(vTelephone,'')!=ifnull(inTelephone,'') or vAdmin!=inAdmin) then
      update mydbr_userlogin
      set name = inName, email=inEmail, telephone=inTelephone, admin=inAdmin
      where user = inUser and authentication=inAuth;
    end if;
  else 
    select ifnull( inAdmin, 0 ) into inAdmin;
    call sp_MyDBR_UserNew( inUser, inName, 'no_direct_access', inAdmin, inEmail, inTelephone, inAuth, 0);
  end if;
END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_sso_user_group_clear`
$$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ext_user_group_clear`
$$
CREATE PROCEDURE `sp_MyDBR_ext_user_group_clear`(
inUser varchar(128),
inAuth int
)
BEGIN

delete from mydbr_groupsusers
where user=inUser and authentication=inAuth;

END
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_sso_user_group`
$$
DROP PROCEDURE IF EXISTS `sp_MyDBR_ext_user_group`
$$
CREATE PROCEDURE `sp_MyDBR_ext_user_group`(
inUser varchar(128),
inGroup varchar(100),
inClear int,
inAuth int
)
BEGIN

declare vExists int;

if (inClear=1) then
  call sp_MyDBR_ext_user_group_clear( inUser, inAuth );
end if;

select count(*) into vExists
from mydbr_groups
where name = inGroup;

if (vExists = 0) then
  insert into mydbr_groups ( name )
  values ( inGroup );
end if;

insert into mydbr_groupsusers ( group_id, user, authentication )
select group_id, inUser, inAuth
from mydbr_groups
where name = inGroup;

END
$$


/* Explicit password check is done only for myDBR login */
DROP PROCEDURE IF EXISTS `sp_MyDBR_UserPassword`
$$
CREATE PROCEDURE `sp_MyDBR_UserPassword`(inUsername varchar(128), inExpiration int)
BEGIN

select user, password, date_add( ifnull( passworddate, now() ), interval inExpiration day ), ask_pw_change
from mydbr_userlogin 
where user= inUsername and authentication=2;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_UserUpd`
$$
CREATE PROCEDURE `sp_MyDBR_UserUpd`(
inUser varchar(128), 
inName varchar(60), 
inPassword varchar(255),
inAdmin tinyint,
inEmail varchar(100),
inTelephone varchar(100),
inAuth int,
inAskPwChange int
)
BEGIN

declare vPass varchar(255);
declare vPassDate datetime;

if (inPassword is not null) then
  set vPass = inPassword;
  select now() into vPassDate;
else
  set vPass = null;
   set vPassDate = null;
end if;

update mydbr_userlogin
set name = ifnull( inName, name ), 
  admin= ifnull( inAdmin, admin ),
  password = ifnull( vPass, password ), 
  passworddate = ifnull( vPassDate , passworddate ),
  email = ifnull( inEmail, email ),
  telephone = ifnull( inTelephone, telephone),
  ask_pw_change = ifnull( inAskPwChange, ask_pw_change )
where user = inUser and authentication=inAuth;

if (vPass is not null and inAuth=2) then
  delete 
  from mydbr_password_reset
  where user = inUser;
end if;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_UserUpdUser`
$$
CREATE PROCEDURE `sp_MyDBR_UserUpdUser`(
inUser varchar(128), 
inPassword varchar(255),
inAuth int
)
BEGIN

update mydbr_userlogin
set password = inPassword, 
  passworddate = now(),
  ask_pw_change = 0
where user = inUser and authentication=inAuth;

if (inAuth=2) then
  delete 
  from mydbr_password_reset
  where user = inUser;
end if;

END
$$



DROP PROCEDURE IF EXISTS `sp_MyDBR_LinkedReport`
$$
CREATE PROCEDURE `sp_MyDBR_LinkedReport` (vName varchar(30))
BEGIN

select proc_name, name
from mydbr_reports 
where name like concat('%', vName, '%') or proc_name like concat('%', vName, '%')
limit 0,20;

END
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_NotificationGet`
$$
CREATE PROCEDURE `sp_MyDBR_NotificationGet`( inID int )
begin
select notification
from mydbr_notifications
where id=inID;
end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_NotificationSet`$$
CREATE PROCEDURE `sp_MyDBR_NotificationSet` (inID int, inNotification text )
begin

declare vCnt int;

select count(*) into vCnt
from mydbr_notifications
where id=inID;

if (vCnt=0) then
  insert into mydbr_notifications (id, notification)
  values (inID, inNotification);
else
  update mydbr_notifications 
  set notification = inNotification
  where id=inID;
end if;
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportExtClean`$$
CREATE PROCEDURE `sp_MyDBR_ReportExtClean` (inProcName varchar(100) )
begin
delete 
from mydbr_report_extensions
where proc_name=inProcName;
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportExtAdd`$$
CREATE PROCEDURE `sp_MyDBR_ReportExtAdd` (inProcName varchar(100), inExtension varchar(100))
begin
insert into mydbr_report_extensions (proc_name, extension)
values (inProcName, inExtension);
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportExtGet`$$
CREATE PROCEDURE `sp_MyDBR_ReportExtGet` (inProcName varchar(100))
begin
select extension
from mydbr_report_extensions
where proc_name=inProcName;
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_ReportExtGetByID`$$
CREATE PROCEDURE `sp_MyDBR_ReportExtGetByID` (inReportID int)
begin

select e.extension
from mydbr_reports r, mydbr_report_extensions e
where r.proc_name=e.proc_name and r.report_id=inReportID;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_MyReportCnt`$$
CREATE PROCEDURE `sp_MyDBR_MyReportCnt`( out outReport int )
begin
select count(*) into outReport
from mydbr_reports 
where proc_name not in ('sp_DBR_StatisticsSummary', 'sp_DBR_StatisticsReport');
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_MyReportCount`$$
CREATE PROCEDURE `sp_MyDBR_MyReportCount`()
begin
declare vReportCount int;

call sp_MyDBR_MyReportCnt( vReportCount );

select vReportCount;
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_checkDemo`$$
CREATE PROCEDURE `sp_MyDBR_checkDemo` (inShowCreate int)
begin
declare vReportCount int;
declare vDemoCount int;

select count(*) into vDemoCount
from mydbr_reports 
where proc_name like 'sp_DBR_demo_%';

if ( vDemoCount > 0) then
  select 1;
else 
  if (inShowCreate=1) then
    select 0;
  else
    call sp_MyDBR_MyReportCnt( vReportCount );
    
    if ( vReportCount > 0 ) then
      select -1;
    else 
      select 0;
    end if;
  end if;
end if;
  
end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_GetLatestVersion`$$
CREATE PROCEDURE `sp_MyDBR_GetLatestVersion` ()
begin

select latest_version, next_check, download_link, info_link, last_successful_check, signature
from mydbr_update
limit 1;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_SetLatestVersion`$$
CREATE PROCEDURE `sp_MyDBR_SetLatestVersion` ( 
inLatestVersion varchar(10), 
inNextCheck int, 
inDownloadLink varchar(200), 
inInfoLink varchar(200),
inLast_successful_check int,
inSignature varchar(50)
)
begin

delete from mydbr_update;
  
insert into mydbr_update (latest_version, next_check, download_link, info_link, last_successful_check, signature ) 
values( inLatestVersion, inNextCheck, inDownloadLink, inInfoLink, inLast_successful_check, inSignature );

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_Log`$$
CREATE PROCEDURE `sp_MyDBR_Log` ( inUser varchar(128), inIP varchar(40), inTitle varchar(30), inMsg text )
begin
insert into mydbr_log ( user, log_ip, log_time, log_title, log_message )
values (  inUser, inIP, now(), inTitle, inMsg );
end;
$$



DROP PROCEDURE IF EXISTS `sp_MyDBR_GetOptions`$$
CREATE PROCEDURE `sp_MyDBR_GetOptions` ( inUser varchar(128), inAuthentication int )
begin

select o1.name, o1.value 
from mydbr_options o1
where o1.user = inUser and o1.authentication = inAuthentication or ( o1.user = '' and o1.authentication = 0 
  and not exists ( 
    select * 
    from mydbr_options o2 
    where o2.name = o1.name and o2.user = inUser and o2.authentication = inAuthentication 
  )
)
order by o1.name;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_SetOption`$$
CREATE PROCEDURE `sp_MyDBR_SetOption` ( 
inUser varchar(128), 
inAuthentication int, 
inName varchar(30), 
inValue varchar(512) 
)
begin

delete 
from mydbr_options 
where user = inUser and authentication = inAuthentication and name = inName;
  
insert into mydbr_options (user, authentication, name, value) 
values ( inUser, inAuthentication, inName, inValue );

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_options_reset`$$
CREATE PROCEDURE `sp_MyDBR_options_reset`(inUser varchar(128), inAuthentication int )
begin

delete 
from mydbr_options 
where user = inUser and authentication = inAuthentication;
  
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sproc_exists
$$
CREATE PROCEDURE sp_MyDBR_sproc_exists(
inProcName varchar(64)
)
begin

select count(*) 
from information_schema.routines 
where routine_name COLLATE utf8_general_ci = cast( inProcName AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci 
and routine_schema = database();  

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_HasStyleFunction$$
CREATE PROCEDURE sp_MyDBR_HasStyleFunction()
begin

select count(*) from information_schema.routines where routine_name = 'mydbr_style' and routine_schema = database();  

end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_IsWebReport`$$
CREATE PROCEDURE `sp_MyDBR_IsWebReport` ( 
inReportID int
)
begin

select count(*)
from mydbr_reports_priv p
where p.report_id = inReportID and p.authentication=0 and p.username='MYDBR_WEB';

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_IsWebReportName`$$
CREATE PROCEDURE `sp_MyDBR_IsWebReportName` ( 
vName varchar(100)
)
begin

select count(*)
from mydbr_reports_priv p
  join mydbr_reports r on p.report_id=r.report_id
where (r.proc_name = vName or lower(md5(r.proc_name)) = lower(vName)) and p.authentication=0 and p.username='MYDBR_WEB';

end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_report_from_hash`$$
CREATE PROCEDURE `sp_MyDBR_report_from_hash` ( 
vHash varchar(100)
)
begin

select r.proc_name
from mydbr_reports r
where md5(r.proc_name)=vHash;

end
$$



DROP PROCEDURE IF EXISTS `sp_MyDBR_Reportgroups`$$
CREATE PROCEDURE `sp_MyDBR_Reportgroups` ()
begin

select id, name, sortorder, color
from mydbr_reportgroups
order by sortorder;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_Reportgroup_set`$$
CREATE PROCEDURE `sp_MyDBR_Reportgroup_set` (
inId int, 
inName varchar(128), 
inSortorder int, 
inColor  char(6)
)
begin

if (inId<-1) then
  insert into mydbr_reportgroups (name, sortorder, color) 
  values (inName, inSortorder, inColor);
else
  update mydbr_reportgroups
  set name=inName,
    sortorder=inSortorder,
    color=inColor
  where id=inId;
end if;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_Reportgroup_del`
$$
CREATE PROCEDURE `sp_MyDBR_Reportgroup_del` (
inId int
)
begin
declare vCntf int;
declare vCntr int;

select count(*) into vCntf
from mydbr_folders
where reportgroup = inId;

select count(*) into vCntr
from mydbr_reports
where reportgroup = inId;

if (vCntf+vCntr>0) then
  select 'Cannot delete item in use!';
else

delete 
from mydbr_reportgroups
where id=inId and inId>1;

end if;


end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_db_dbs`$$
CREATE PROCEDURE `sp_MyDBR_db_dbs` (
)
begin

show databases;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_db_objects`$$
CREATE PROCEDURE `sp_MyDBR_db_objects` (
inDB varchar(64)
)
begin

select table_name, 'T', 1
from information_schema.tables 
where table_schema=cast( inDB AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci and table_name not like 'mydbr_%'
union
select routine_name, substr(routine_type, 1,1), 2
from information_schema.routines 
where routine_schema=cast( inDB AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci 
and routine_name not like 'sp_MyDBR%' and routine_name not like 'mydbr_%'
order by 3, 2 desc, 1;

end
$$


DROP PROCEDURE IF EXISTS `sp_MyDBR_db_columns`$$
CREATE PROCEDURE `sp_MyDBR_db_columns` (
inDB varchar(64),
inTable varchar(64)
)
begin

select column_name, column_type
from information_schema.columns 
where table_schema=cast( inDB AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci 
and table_name=cast( inTable AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci
order by ordinal_position;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_table_reference`
$$
CREATE PROCEDURE `sp_MyDBR_table_reference` (
inDB varchar(64),
inTable varchar(64)
)
begin

select 
  u.COLUMN_NAME COLLATE utf8_general_ci, 
  u.REFERENCED_TABLE_SCHEMA COLLATE utf8_general_ci, 
  u.REFERENCED_TABLE_NAME COLLATE utf8_general_ci, 
  u.REFERENCED_COLUMN_NAME COLLATE utf8_general_ci
from information_schema.KEY_COLUMN_USAGE u
where u.TABLE_SCHEMA=cast( inDB AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci 
and u.TABLE_NAME=cast( inTable AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci
union
select column_name, referenced_table_schema, referenced_table_name, referenced_column_name
from mydbr_key_column_usage
where table_schema=inDB and table_name=inTable;

end
$$

DROP PROCEDURE IF EXISTS `sp_MyDBR_report_info`$$
CREATE PROCEDURE `sp_MyDBR_report_info` (
inProcName varchar(64)
)
begin

declare vName varchar(150);
declare vExplanation varchar(4096);
declare vFolderID int;
declare vFolderIDPrev int;
declare vFName varchar(100);
declare vPath varchar(1000);
declare vSep varchar(6);
declare vStop int;
declare vID int;

select r.name, r.explanation, r.folder_id, r.report_id into vName, vExplanation, vFolderID, vID
from mydbr_reports r
where r.proc_name=inProcName;

if (vName is not null) then
  set vPath = '';
  set vSep = '';
  set vStop = 0;
  repeat
    set vFolderIDPrev = vFolderID;
    select name, mother_id into vFName, vFolderID
    from mydbr_folders
    where folder_id=vFolderID;
  
    select concat( '<a href="index.php?m=',cast(vFolderIDPrev as char(8)),'">', vFName,'</a>', vSep, vPath ) into vPath;
    select vStop +1 into vStop;
    select ' &gt; ' into vSep;
  until(vFolderID is null or vStop=100)
  end repeat;

  select vName, vExplanation, vPath, vID;
end if;
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_languages$$
create procedure sp_MyDBR_languages()
begin
select lang_locale, language
from mydbr_languages
order by language;
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_locale_formats
$$
create procedure sp_MyDBR_locale_formats( inLocale char(5) )
begin

select date_format, time_format, thousand_separator, decimal_separator
from mydbr_languages
where lower(lang_locale) = lower(inLocale);

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_localization
$$
create procedure sp_MyDBR_localization(
in_lang_locales varchar(255) 
)
begin
select lang_locale, keyword, translation
from mydbr_localization
where in_lang_locales like concat('%',lang_locale,'%');
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_localization_get$$
create procedure sp_MyDBR_localization_get (
inKeyword varchar(50)
)
begin
select lang_locale, translation
from mydbr_localization
where keyword = inKeyword;
end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_localization_set$$
create procedure sp_MyDBR_localization_set (
inKeyword varchar(50),
inLangLocale varchar(50),
inTranslation varchar(1024)
)
begin
declare vCnt int;

select count(*) into vCnt
from mydbr_localization
where keyword = inKeyword and lang_locale =inLangLocale;

if (inTranslation='') then
  delete from mydbr_localization
  where keyword = inKeyword and lang_locale =inLangLocale;
else 
  if (vCnt=0) then
    insert into mydbr_localization ( keyword, lang_locale, translation, creation_date)
    values (inKeyword, inLangLocale, inTranslation, now());
  else 
    update mydbr_localization
    set translation = inTranslation, creation_date = now()
    where keyword = inKeyword and lang_locale =inLangLocale;
  end if;
end if;
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_localization_cnt$$
create procedure sp_MyDBR_localization_cnt()
begin
select keyword, count(*)
from mydbr_localization
group by keyword;
end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_report_copy
$$
create procedure sp_MyDBR_report_copy(
inOriginal varchar(100),
inNew varchar(100)
)
begin

declare vOriginalID int;
declare vNewID int;
declare vName varchar(150);
declare vCnt int;
declare vCntAll int;

select report_id, name into vOriginalID, vName
from mydbr_reports
where proc_name = inOriginal;

select report_id into vNewID
from mydbr_reports
where proc_name = inNew;

select count(*) into vCnt
from information_schema.ROUTINES p 
where routine_schema=database() and routine_type='PROCEDURE' 
and p.ROUTINE_NAME = cast( inNew AS CHAR CHARACTER SET utf8) COLLATE utf8_general_ci;

set vCntAll = 0;

/* Do we have both procedures? */
if (vCnt!=1 or vNewID is not null or vOriginalID is null) then
  set vCntAll = -1;
end if;

/* Make sure the new one is really a new one */
if (vCntAll=0) then
  select count(*) into vCnt
  from mydbr_reports
  where proc_name = inNew;

  set vCntAll = vCntAll+vCnt;

  select count(*) into vCnt
  from mydbr_params
  where proc_name = inNew;

  set vCntAll = vCntAll+vCnt;

  select count(*) into vCnt
  from mydbr_report_extensions
  where proc_name = inNew;

  set vCntAll = vCntAll+vCnt;

  select count(*) into vCnt
  from mydbr_reports_priv
  where report_id = vNewID;

  set vCntAll = vCntAll+vCnt;
end if;

if (vCntAll=0) then
  START TRANSACTION;

  insert into mydbr_reports ( name, proc_name, folder_id, explanation, reportgroup, 
    sortorder, runreport, autoexecute, parameter_help, export )
  select concat(name, ' +'), inNew, folder_id, explanation, reportgroup, 
    sortorder, runreport, autoexecute, parameter_help, export
  from mydbr_reports
  where report_id = vOriginalID;
  
  select report_id into vNewID
  from mydbr_reports
  where proc_name = inNew;

  insert into mydbr_params ( proc_name, param, query_name, title, default_value, optional, only_default, suffix, options )
  select inNew, param, query_name, title, default_value, optional, only_default, suffix, options
  from mydbr_params
  where proc_name = inOriginal;

  insert into mydbr_report_extensions( proc_name, extension )
  select inNew, extension
  from mydbr_report_extensions
  where proc_name = inOriginal;

  insert into mydbr_reports_priv( report_id, username, group_id, authentication )
  select vNewID, username, group_id, authentication
  from mydbr_reports_priv
  where report_id = vOriginalID;

  COMMIT;
  select vNewID;
else 
  select 0;
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_favourites 
$$
CREATE PROCEDURE sp_MyDBR_favourites (
inUser varchar(128),
inAuthentication int
)
BEGIN

select f.report_id, r.name, f.url, f.id, r.explanation
from mydbr_favourite_reports f, mydbr_reports r
where f.report_id=r.report_id
  and f.user=inUser and f.authentication=inAuthentication;

END
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_favourite_del $$
CREATE PROCEDURE sp_MyDBR_favourite_del (
inUser varchar(128),
inAuthentication int,
inFavID int
)
BEGIN

delete from mydbr_favourite_reports
where user = inUser and authentication=inAuthentication and id=inFavID;

select 'not_set';
END
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_favourite_set $$
CREATE PROCEDURE sp_MyDBR_favourite_set (
inUser varchar(128),
inAuthentication int,
inReportID int,
inURL varchar(512)
)
BEGIN
declare vRet varchar(10);
declare vCnt int;

select count(*) into vCnt
from mydbr_favourite_reports
where user = inUser and authentication=inAuthentication and report_id = inReportID;

if (vCnt>0) then
  set vRet = 'not_set';

  delete from mydbr_favourite_reports
  where user = inUser and authentication=inAuthentication and report_id = inReportID;
else 
  set vRet = 'set';

  insert into mydbr_favourite_reports ( user, authentication, report_id, url )
  values (inUser, inAuthentication, inReportID, inURL);
end if;

select vRet;
END
$$

drop procedure if exists sp_MyDBR_mydbr_remote_servers_ins
$$
drop procedure if exists sp_MyDBR_remote_srv_ins
$$
create procedure sp_MyDBR_remote_srv_ins(
inId int(11),
inServer varchar(128),
inUrl varchar(255),
inHash varchar(40),
inUsername varchar(128),
inPassword varchar(128)
)
begin
declare vCnt int;

select count(*) into vCnt
from mydbr_remote_servers
where server = inServer;

if (vCnt=0) then
  insert into mydbr_remote_servers ( server, url, hash, username, password )
  values ( inServer, inUrl, inHash, inUsername, inPassword );
end if;

end
$$

drop procedure if exists sp_MyDBR_mydbr_remote_servers_del
$$
drop procedure if exists sp_MyDBR_remote_srv_del
$$
create procedure sp_MyDBR_remote_srv_del(
inId int(11)
)
begin

delete 
from mydbr_remote_servers
where id=inId;

end
$$

drop procedure if exists sp_MyDBR_mydbr_remote_servers_upd
$$
drop procedure if exists sp_MyDBR_remote_srv_upd
$$
create procedure sp_MyDBR_remote_srv_upd(
inId int(11),
inServer varchar(128),
inUrl varchar(255),
inHash varchar(40),
inUsername varchar(128),
inPassword varchar(128)
)
begin

update mydbr_remote_servers
set 
  server=inServer,
  url=inUrl,
  hash=inHash,
  username=inUsername,
  password=inPassword
where id=inId;

end
$$

drop procedure if exists sp_MyDBR_mydbr_remote_servers_sel_all
$$
drop procedure if exists sp_MyDBR_remote_srv_sel_all
$$
create procedure sp_MyDBR_remote_srv_sel_all()
begin

select id, server, url, hash, username, password
from mydbr_remote_servers;

end
$$

drop procedure if exists sp_MyDBR_has_unattached_report
$$
create procedure sp_MyDBR_has_unattached_report(
in_proc varchar(128)
)
begin

declare vCnt int;

select count(*) into vCnt
from mydbr_reports
where proc_name=in_proc;

if (vCnt=0) then
  select count(*) into vCnt
  from information_schema.ROUTINES
  where ROUTINE_SCHEMA=database() and ROUTINE_TYPE='PROCEDURE' and ROUTINE_NAME COLLATE utf8_general_ci = in_proc;

  if (vCnt>0) then
    select 1;
  else
    select 0;
  end if;
else
  select 0;
end if;

end
$$

drop procedure if exists sp_MyDBR_template_folder
$$
create procedure sp_MyDBR_template_folder(
inId int
)
begin
declare v_order int;
declare v_cnt int;

create temporary table folders_tmp (
id int,
dorder int
);

set v_order=0;

while( inId>0 ) do
  insert into folders_tmp values ( inID, v_order );

  set v_order = v_order + 1;

  select parent_id into inID
  from mydbr_template_folders
  where id =  inID;
  
  select count(*) into v_cnt
  from folders_tmp
  where id = inID;
  
  if (v_cnt>0) then
    set inId = -1;
  end if;
end while;

select f.id, f.name, t.dorder
from mydbr_template_folders f 
  join folders_tmp t on t.id=f.id
order by t.dorder;

drop temporary table folders_tmp;

end
$$

drop procedure if exists sp_MyDBR_template_set
$$
create procedure sp_MyDBR_template_set(
inId int,
inName varchar(128),
inHeader text,
inRow text,
inFooter text,
inFolder_id int
)
begin

declare vCnt int;

select count(*) into vCnt
from mydbr_templates
where id != inId and name = inName;

if (vCnt>0) then
  select 0;
else
  select count(*) into vCnt
  from mydbr_templates
  where id = inId and ifnull(inId,0)!=0;

  if (vCnt=0) then
    insert into mydbr_templates ( name, header, row, footer, folder_id, creation_date )
    values ( inName, inHeader, inRow, inFooter, inFolder_id, now() );
  
    select 1;
  else 
    update mydbr_templates
    set name = inName, 
      header = inHeader,
      row = inRow,
      footer = inFooter,
      creation_date = now()
    where id=inId;

    select 1;
  end if;
end if;
end
$$


drop procedure if exists sp_MyDBR_template_set_sync
$$
create procedure sp_MyDBR_template_set_sync(
inName varchar(128),
inHeader text,
inRow text,
inFooter text
)
begin

declare vCnt int;

select count(*) into vCnt
from mydbr_templates
where name = inName;

if (vCnt=0) then
  insert into mydbr_templates ( name, header, row, footer, folder_id, creation_date )
  values ( inName, inHeader, inRow, inFooter, 1, now() );
else 
  update mydbr_templates
  set 
    header = inHeader,
    row = inRow,
    footer = inFooter,
    creation_date = now()
  where name=inName;
end if;
end
$$


drop procedure if exists sp_MyDBR_templates_get
$$
create procedure sp_MyDBR_templates_get(inID int)
begin

select p.id, p.name, 'folder_up', 1
from mydbr_template_folders f
  join mydbr_template_folders p on p.id=f.parent_id
where f.id=inID
union
select id, name, 'folder', 2
from mydbr_template_folders
where parent_id=inID
union
select id, name, 'template', 3
from mydbr_templates
where folder_id=inID
order by 4, 2;

end
$$

drop procedure if exists sp_MyDBR_template_get
$$
create procedure sp_MyDBR_template_get(
inId int(11)
)
begin

select header, row, footer
from mydbr_templates
where id=inId;

end
$$


drop procedure if exists sp_MyDBR_template_get_name
$$
create procedure sp_MyDBR_template_get_name(
inName varchar(128)
)
begin

select header, row, footer
from mydbr_templates
where name=inName;

end
$$



drop procedure if exists sp_MyDBR_template_del
$$
create procedure sp_MyDBR_template_del(
inId int(11)
)
begin

delete 
from mydbr_templates
where id=inId;

end
$$

drop procedure if exists sp_MyDBR_template_folder_del
$$
create procedure sp_MyDBR_template_folder_del(
inId int(11)
)
begin

declare v_cnt int;

select count(*) into v_cnt
from mydbr_template_folders
where parent_id=inId;

delete 
from mydbr_template_folders
where id=inId and v_cnt=0 and id not in (
  select folder_id
  from mydbr_templates
);

end
$$

drop procedure if exists sp_MyDBR_template_move
$$
create procedure sp_MyDBR_template_move(
in_id int,
in_folder_id int
)
begin

update mydbr_templates
set folder_id = in_folder_id
where id=in_id;

end
$$

drop procedure if exists sp_MyDBR_template_folder_move
$$
create procedure sp_MyDBR_template_folder_move(
in_id int,
in_folder_id int
)
begin

update mydbr_template_folders
set parent_id = in_folder_id
where id=in_id;

end
$$



drop procedure if exists sp_MyDBR_user_find
$$
create procedure sp_MyDBR_user_find(
inNameSearch varchar(100),
inExpiration int
)
begin

select u.user, u.name, u.authentication, a.name
from mydbr_userlogin u
  join mydbr_authentication a on u.authentication=a.mask
where (lower(u.user) like concat('%', inNameSearch, '%') or lower(u.name) like concat('%', inNameSearch, '%'))
and (inExpiration=0 or date_add( ifnull( u.passworddate, now() ), interval inExpiration day )>=current_date())
and u.admin=0
limit 40;

end
$$

drop procedure if exists sp_MyDBR_template_folder_new
$$
create procedure sp_MyDBR_template_folder_new(
in_parent_id int,
in_name varchar(128)
)
begin

insert into mydbr_template_folders (name, parent_id)
select in_name, id
from mydbr_template_folders
where id=in_parent_id;

end
$$

drop procedure if exists sp_MyDBR_template_folder_ren
$$
create procedure sp_MyDBR_template_folder_ren(
in_id int,
in_name varchar(128)
)
begin

update mydbr_template_folders 
set name = in_name
where id = in_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_pw_reset_options_get
$$
CREATE PROCEDURE sp_MyDBR_pw_reset_options_get()
begin

select name, value
from  mydbr_options 
where name like 'password_reset%' or name like 'mail_%';

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_password_reset_token
$$
CREATE PROCEDURE sp_MyDBR_password_reset_token( 
in_user varchar(128),
in_email varchar(128),
in_allow_admin_change int,
in_ip_address varchar(255)
)
begin

declare v_cnt int;
declare v_user varchar(128);
declare v_email varchar(128);
declare v_perishable varchar(20);

select count(*) into v_cnt
from mydbr_userlogin 
where user = ifnull(in_user, user) and email=ifnull(in_email, email) and authentication = 2 and (in_allow_admin_change=1 or admin=0);

if (v_cnt>1) then
  select 'multiemail', null, null;
else
  select user, email into v_user, v_email
  from mydbr_userlogin 
  where ifnull(user, '') = ifnull(in_user, ifnull(user, '')) and ifnull(email, '')=ifnull(in_email, ifnull(email, '')) 
  and authentication = 2 and (in_allow_admin_change=1 or admin=0);

  if (v_user is null) then
    select null, null, null;
  else 
    if (v_email='' or v_email is null) then
      select 'noemail', null, null;
    else 
      set v_perishable = substring(sha1( concat(now(), rand(), v_user)), 2, 20 );

      delete from mydbr_password_reset where user = v_user;

      insert into mydbr_password_reset (user, perishable_token, request_time, ip_address) 
      values ( v_user, v_perishable, now(), in_ip_address );

      select v_perishable, name, email
      from mydbr_userlogin 
      where user = v_user and authentication = 2;
    end if;
  end if;
end if;

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_pw_reset_user_get
$$
CREATE PROCEDURE sp_MyDBR_pw_reset_user_get( in_token varchar(255), in_timeout int )
begin

declare v_user_id varchar(128);
declare v_cnt int;

select max(user), count(*) into v_user_id, v_cnt
from mydbr_password_reset
where perishable_token = in_token and date_add( now(), interval -1*in_timeout minute) < request_time;

if (v_cnt=1) then
  select pr.user, u.email
  from mydbr_password_reset pr
    join mydbr_userlogin u on u.user=pr.user and u.authentication=2
  where perishable_token = in_token;
end if;

delete
from mydbr_password_reset
where date_add( now(), interval -1*in_timeout minute) > request_time;

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_editor_hint
$$
CREATE PROCEDURE `sp_MyDBR_editor_hint`(
in_db varchar(512) 
)
begin

set @s = concat( 'select table_schema, table_name, column_name from information_schema.columns where table_schema in (',in_db,')');
prepare stmt from @s;
execute stmt;
deallocate prepare stmt;

end;
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_snippets_get
$$
CREATE PROCEDURE sp_MyDBR_snippets_get()
begin

select id, name, code, shortcut, cright, cdown
from mydbr_snippets;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_snippets_save
$$
CREATE PROCEDURE sp_MyDBR_snippets_save(
in_id int,
in_name varchar(30),
in_code text
)
begin

declare v_cnt int;

select count(*) into v_cnt
from mydbr_snippets
where id = in_id;

if (v_cnt>0) then
  update mydbr_snippets
  set name = in_name, code = in_code
  where id = in_id;
else 
  insert into mydbr_snippets (name, code, cright, cdown)
  values (in_name, in_code, 0, 0);
  
  select last_insert_id();
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_snippets_delete
$$
CREATE PROCEDURE sp_MyDBR_snippets_delete(
in_id int
)
begin

delete 
from mydbr_snippets
where id = in_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_snippets_shortcut
$$
CREATE PROCEDURE sp_MyDBR_snippets_shortcut(
in_id int,
in_shortcut varchar(20)
)
begin

update mydbr_snippets
set shortcut = in_shortcut
where id = in_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_snippets_save_move
$$
CREATE PROCEDURE sp_MyDBR_snippets_save_move(
in_id int,
in_right int,
in_down int
)
begin

update mydbr_snippets
set cright = in_right, cdown = in_down
where id = in_id;

end
$$ 

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_latest_reports
$$
CREATE PROCEDURE `sp_MyDBR_sync_latest_reports`( 
inUser varchar(128),
inAuthentication int,
in_date date,
in_source_folder int,
in_no_excluded int,
in_sp_single varchar(128)
)
begin

create temporary table procs_tmp( 
name varchar(150),
type varchar(20)
);

create temporary table routines_tmp (
name varchar(150)
);

if (in_sp_single is not null) then
  insert into procs_tmp values (in_sp_single, 'PROCEDURE');
else 
  if (in_source_folder is not null) then
    insert into procs_tmp
    select r.ROUTINE_NAME, r.ROUTINE_TYPE
    from information_schema.ROUTINES r
      join mydbr_reports fr on fr.proc_name = r.ROUTINE_NAME
    where 
      fr.folder_id=in_source_folder and
      r.ROUTINE_SCHEMA = database() and 
      r.ROUTINE_NAME not like 'sp_MyDBR%' and
      r.ROUTINE_NAME not like 'sp_DBR_demo_%' and
      r.ROUTINE_NAME != 'mydbr_style' and
      r.ROUTINE_NAME not in ('sp_DBR_StatisticsReport', 'sp_DBR_StatisticsSummary')
      and r.ROUTINE_NAME not in (
        select e.proc_name
        from mydbr_sync_exclude e
        where e.username = inUser and e.authentication=inAuthentication and in_no_excluded=1 and e.type='routine'
      );
  else 
    insert into procs_tmp
    select ROUTINE_NAME, ROUTINE_TYPE
    from information_schema.ROUTINES
    where ROUTINE_SCHEMA = database() and CREATED >= in_date and 
      ROUTINE_NAME not like 'sp_MyDBR%' and
      ROUTINE_NAME not like 'sp_DBR_demo_%' and
      ROUTINE_NAME != 'mydbr_style' and
      ROUTINE_NAME not in ('sp_DBR_StatisticsReport', 'sp_DBR_StatisticsSummary')
      and ROUTINE_NAME not in (
        select proc_name
        from mydbr_sync_exclude
        where username = inUser and authentication=inAuthentication and in_no_excluded=1 and type='routine'
      );
  end if;
end if;

insert into routines_tmp
select t.name
from procs_tmp t
  join mydbr_reports r on t.name=r.proc_name;


insert into routines_tmp
/* Additional procs & functions */
select t.name
from procs_tmp t
  left join mydbr_reports r on t.name=r.proc_name 
where r.proc_name  is null
  and t.name not in (
    select proc_name
    from mydbr_sync_exclude
    where username = inUser and authentication=inAuthentication and in_no_excluded=1 and type='routine'
  ) and in_sp_single is null;

/* PARAMETER QUERIES */
insert into routines_tmp
select ro.ROUTINE_NAME as 'name'
from information_schema.ROUTINES ro
  join mydbr_param_queries q on q.query=ro.ROUTINE_NAME
  join mydbr_params p on p.query_name=q.name
  join procs_tmp t on t.name = p.proc_name and t.name!=q.query
where t.type='PROCEDURE' and ro.ROUTINE_SCHEMA = database()
;

select 'routines' as 'MYDBRTYPE';

select distinct name 
from routines_tmp
order by name;

select 'table' as 'MYDBRTYPE', 'mydbr_templates' as 'table_name', 'name';

select name, header, row, footer
from mydbr_templates
where creation_date >= in_date
and name not in (
  select proc_name
  from mydbr_sync_exclude
  where username = inUser and authentication=inAuthentication and in_no_excluded=1 and type='template'
) and in_sp_single is null
order by name;

select 'table' as 'MYDBRTYPE', 'mydbr_localization' as 'table_name', 'lang_locale', 'keyword';

select *
from mydbr_localization
where creation_date >= in_date
and keyword not in (
  select proc_name
  from mydbr_sync_exclude
  where username = inUser and authentication=inAuthentication and in_no_excluded=1 and type='localization'
) and in_sp_single is null;


select 'table' as 'MYDBRTYPE', 'mydbr_params' as 'table_name', 'proc_name', 'params';

select p.*
from mydbr_params p
  join procs_tmp t on t.name = p.proc_name 
where t.type='PROCEDURE';


select 'table' as 'MYDBRTYPE', 'mydbr_param_queries' as 'table_name', 'name';

select q.*
from mydbr_param_queries q
  join mydbr_params p on p.query_name=q.name or p.default_value=q.name
  join procs_tmp t on t.name = p.proc_name 
where t.type='PROCEDURE';


select 'table' as 'MYDBRTYPE', 'mydbr_report_extensions' as 'table_name', 'proc_name', 'extension';

select e.*
from mydbr_report_extensions e
  join procs_tmp t on t.name=e.proc_name
where t.type='PROCEDURE';


select 'table' as 'MYDBRTYPE', 'mydbr_reports' as 'table_name', 'report_id';

select r.*, rg.name as 'rgname', rg.sortorder as 'rgsortorder', rg.color as 'rgcolor'
from procs_tmp t
  join mydbr_reports r on t.name=r.proc_name 
  join mydbr_reportgroups rg on rg.id=r.reportgroup 
;


drop temporary table procs_tmp;
drop temporary table routines_tmp;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_table_columns
$$
CREATE PROCEDURE `sp_MyDBR_table_columns`( 
in_table varchar(150) 
)
begin

select COLUMN_NAME, DATA_TYPE 
from information_schema.COLUMNS 
where TABLE_SCHEMA = database() and table_name=in_table;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_reportgrp_check
$$
CREATE PROCEDURE sp_MyDBR_sync_reportgrp_check(
out in_report_group_id int,
in_reportgroup varchar(128),
in_rgsortorder int,
in_rgcolor char(6)
)
begin

set in_report_group_id = null;

select id into in_report_group_id
from mydbr_reportgroups
where name = in_reportgroup;

if (in_report_group_id is null) then
  insert into mydbr_reportgroups ( name, sortorder, color )
  values ( in_reportgroup, in_rgsortorder, in_rgcolor );

  set in_report_group_id = last_insert_id();
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_mydbr_reports
$$
CREATE PROCEDURE `sp_MyDBR_sync_mydbr_reports`(
in_sync_folder_name varchar(100),
in_name varchar(150),
in_proc_name varchar(100),
in_explanation varchar(4096),
in_sortorder int,
in_runreport varchar(50),
in_autoexecute tinyint(4),
in_parameter_help varchar(10000),
in_export varchar(10),
in_reportgroup varchar(128),
in_rgsortorder int,
in_rgcolor char(6)
)
begin

declare v_cnt int;
declare v_folder_id int;
declare v_report_group_id int;
declare v_folder_rg_id int;

set v_report_group_id = null;

call sp_MyDBR_sync_reportgrp_check( v_report_group_id, in_reportgroup, in_rgsortorder, in_rgcolor );

select count(*) into v_cnt
from mydbr_reports
where proc_name = in_proc_name;

if (v_cnt=0) then
  select folder_id into v_folder_id
  from mydbr_folders
  where name = in_sync_folder_name and mother_id=1;
  
  if (v_folder_id is null) then
    select min(id) into v_folder_rg_id
    from mydbr_reportgroups
    where id>0;

    insert into mydbr_folders ( mother_id, name, invisible, reportgroup, explanation )
    values ( 1, in_sync_folder_name, 2, v_folder_rg_id, 'Temporary folder for new myDBR sync reports');
    
    select folder_id into v_folder_id
    from mydbr_folders
    where name = in_sync_folder_name and mother_id=1;
  end if;
  
  insert into mydbr_reports (name, proc_name, folder_id, explanation, sortorder, runreport, autoexecute, parameter_help, export, reportgroup )
  values (in_name, in_proc_name, v_folder_id, in_explanation, in_sortorder, in_runreport, in_autoexecute, in_parameter_help, in_export, v_report_group_id);
else 
  update mydbr_reports
  set
    name = in_name,
    proc_name = in_proc_name,
    explanation = in_explanation,
    sortorder = in_sortorder,
    runreport = in_runreport,
    autoexecute = in_autoexecute,
    parameter_help = in_parameter_help,
    export = in_export,
    reportgroup = v_report_group_id
  where proc_name = in_proc_name;
end if;

end
$$


DROP PROCEDURE IF EXISTS sp_MyDBR_sync_exclude_toggle
$$
CREATE PROCEDURE sp_MyDBR_sync_exclude_toggle(
inUser varchar(128),
inAuthentication int,
inProcName varchar(100),
inType varchar(20)
)
begin

delete 
from mydbr_sync_exclude
where username = inUser and authentication=inAuthentication and proc_name=inProcName and type=inType;

if (row_count() = 0) then
  insert into mydbr_sync_exclude ( username, authentication, proc_name, type )
  values ( inUser, inAuthentication, inProcName, inType );
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_exclude_get
$$
CREATE PROCEDURE sp_MyDBR_sync_exclude_get(
inUser varchar(128),
inAuthentication int
)
begin

select proc_name, type
from mydbr_sync_exclude
where username = inUser and authentication=inAuthentication;

end
$$

drop procedure if exists sp_MyDBR_sync_report_path_get
$$
create procedure sp_MyDBR_sync_report_path_get(
in_report varchar(100),
in_delim varchar(10)
)
begin

declare v_path text;
declare v_path2 text;

declare v_folder text;

declare v_mother_id int;
declare v_mother_id2 int;

select f.name, f.mother_id into v_path, v_mother_id
from mydbr_reports r
  join mydbr_folders f on f.folder_id = r.folder_id
where r.proc_name = in_report;

set v_path2 = v_path;
set v_mother_id2 = v_mother_id;

while ( ifnull(v_mother_id2,0) !=0 ) do
  select name, mother_id into v_folder, v_mother_id
  from mydbr_folders
  where folder_id = v_mother_id2;
  
  set v_mother_id2 = v_mother_id;
  
  set v_path2 = concat(v_folder, in_delim, v_path2);
end while;

select v_path2;

end
$$

drop PROCEDURE if exists sp_MyDBR_path_to_folder_id
$$
CREATE PROCEDURE `sp_MyDBR_path_to_folder_id`(
in_path text,
in_delim varchar(10),
in_parent_child varchar(10),
out out_folder_id int,
out out_folder_name varchar(100)
)
begin
declare v_path text;
declare v_path2 text;
declare v_folder text;
declare v_pos int;
declare v_mother_id int;
declare v_folder_id int;
declare v_incorrect int;

set v_mother_id = null;
set v_path = in_path;

select locate(in_delim, v_path) into v_pos;
set v_incorrect = 0;

while( v_pos > 0 and v_incorrect = 0) do
  select substring( v_path, 1, v_pos-1 ) into v_folder;
  select substring( v_path, v_pos+length(in_delim) ) into v_path2;

  select locate(in_delim, v_path) into v_pos;
  
  set v_folder_id = null;
  select folder_id into v_folder_id
  from mydbr_folders
  where ifnull(mother_id, 0) = ifnull(v_mother_id, 0) and name = v_folder;

  if (v_folder_id is null) then
    set v_incorrect = 1;
  end if;

  set v_path = v_path2;
  select locate(in_delim, v_path) into v_pos;

  set v_mother_id = v_folder_id;
end while;

set out_folder_id = null;



if (v_incorrect=0) then
  if (in_parent_child = 'mother') then
    set out_folder_id = v_folder_id;
    set out_folder_name = v_path;
  else

    set v_mother_id = v_folder_id;

    set v_folder_id = null;

    select folder_id into v_folder_id
    from mydbr_folders
    where ifnull(mother_id, 0) = ifnull(v_mother_id, 0) and name = v_path;

    set out_folder_id = v_folder_id;
  end if;
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_folder_info
$$
CREATE PROCEDURE sp_MyDBR_sync_folder_info (
in_path text,
in_delim varchar(10)
)
begin
declare v_folder_id int;
declare v_folder_name varchar(100);

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'child', v_folder_id, v_folder_name );

select f.explanation, g.name, g.sortorder, g.color
from mydbr_folders f
  join mydbr_reportgroups g on g.id = f.reportgroup
where f.folder_id = v_folder_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_check_for_folder
$$
CREATE PROCEDURE sp_MyDBR_sync_check_for_folder(
in_path text,
in_delim varchar(10),
in_explanation varchar(4096),
in_reportgroup varchar(128),
in_rgsortorder int,
in_rgcolor char(6)
)
begin
declare v_mother_id int;
declare v_folder_name varchar(100);
declare v_cnt int;
declare v_report_group_id int;

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'mother', v_mother_id, v_folder_name );

select count(*) into v_cnt
from mydbr_folders
where mother_id = v_mother_id and name = v_folder_name;

if (v_cnt=0) then
  call sp_MyDBR_sync_reportgrp_check( v_report_group_id, in_reportgroup, in_rgsortorder, in_rgcolor );

  insert into mydbr_folders ( mother_id, name, invisible, reportgroup, explanation )
  values ( v_mother_id, v_folder_name, 2, v_report_group_id, in_explanation);
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_report_path_set
$$
CREATE PROCEDURE `sp_MyDBR_sync_report_path_set`(
in_report varchar(100),
in_path text,
in_delim varchar(10)
)
begin
declare v_folder_id int;
declare v_folder_name varchar(100);

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'child', v_folder_id, v_folder_name );

if (v_folder_id is not null) then
  update mydbr_reports
  set folder_id = v_folder_id
  where proc_name = in_report;
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_report_priv_get
$$
CREATE PROCEDURE sp_MyDBR_sync_report_priv_get(
in_report varchar(100)
)
begin

select p.username, g.name, p.authentication
from mydbr_reports_priv p 
  join mydbr_reports r on r.report_id=p.report_id
  left join mydbr_groups g on g.group_id = p.group_id
where r.proc_name=in_report;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_folder_priv_get
$$
CREATE PROCEDURE sp_MyDBR_sync_folder_priv_get(
in_path text,
in_delim varchar(10)
)
begin

declare v_folder_id int;
declare v_folder_name varchar(100);

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'child', v_folder_id, v_folder_name );

select p.username, g.name, p.authentication
from mydbr_folders_priv p 
  left join mydbr_groups g on g.group_id = p.group_id
where p.folder_id=v_folder_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_report_priv_rst
$$
CREATE PROCEDURE sp_MyDBR_sync_report_priv_rst(
in_report varchar(100)
)
begin

delete 
from mydbr_reports_priv
where report_id in (
  select report_id
  from mydbr_reports
  where proc_name=in_report
);

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_folder_priv_rst
$$
CREATE PROCEDURE sp_MyDBR_sync_folder_priv_rst(
in_path text,
in_delim varchar(10)
)
begin
declare v_folder_id int;
declare v_folder_name varchar(100);

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'child', v_folder_id, v_folder_name );

delete 
from mydbr_folders_priv
where folder_id = v_folder_id;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_check_priv
$$
CREATE PROCEDURE sp_MyDBR_sync_check_priv(
in_username varchar(128),
in_group varchar(100),
in_authentication int,
out out_ok int,
out out_group_id int
)
begin
declare v_cnt int;

set out_group_id = 0;
set out_ok = 0;

if (in_username is not null) then
  if (in_username in ('MYDBR_WEB', 'PUBLIC') and in_authentication=0) then
    set out_ok = 1;
  else
    select count(*) into v_cnt
    from mydbr_userlogin
    where user = in_username and authentication = in_authentication;
    
    if (v_cnt>0) then
      set out_ok = 1;
    end if;
  end if;
else 
  select group_id into out_group_id
  from mydbr_groups
  where name = in_group;
  
  if (out_group_id != 0) then
    set out_ok = 1;
  end if;
end if;

end
$$  

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_folder_priv_set
$$
CREATE PROCEDURE sp_MyDBR_sync_folder_priv_set(
in_path varchar(100),
in_delim varchar(10),
in_username varchar(128),
in_group varchar(100),
in_authentication int
)
begin

declare v_folder_id int;
declare v_folder_name varchar(100);
declare v_ok int;
declare v_cnt int;
declare v_group_id int;

call sp_MyDBR_path_to_folder_id( in_path, in_delim, 'child', v_folder_id, v_folder_name );

set v_ok = 0;
set v_group_id = 0;
if (v_folder_id is not null) then
  call sp_MyDBR_sync_check_priv(in_username, in_group, in_authentication, v_ok, v_group_id);
end if;

if (v_ok=1) then
    insert ignore into mydbr_folders_priv( folder_id, username, group_id, authentication )
    values ( v_folder_id, in_username, v_group_id, in_authentication );
end if;

end
$$

DROP PROCEDURE IF EXISTS sp_MyDBR_sync_report_priv_set
$$
CREATE PROCEDURE sp_MyDBR_sync_report_priv_set(
in_report varchar(100),
in_username varchar(128),
in_group varchar(100),
in_authentication int
)
begin

declare v_report_id int;
declare v_ok int;
declare v_cnt int;
declare v_group_id int;

select report_id into v_report_id
from mydbr_reports
where proc_name = in_report;

set v_ok = 0;
set v_group_id = 0;
if (v_report_id is not null) then
  call sp_MyDBR_sync_check_priv(in_username, in_group, in_authentication, v_ok, v_group_id);
end if;

if (v_ok=1) then
    insert ignore into mydbr_reports_priv( report_id, username, group_id, authentication )
    values ( v_report_id, in_username, v_group_id, in_authentication );
end if;

end
$$

drop procedure if exists sp_MyDBR_sync_drop_sync_folder
$$
create procedure sp_MyDBR_sync_drop_sync_folder(
in_sync_folder_name varchar(100)
)
begin

declare v_folder_id int;
declare vReportCnt int;
declare vFolderCnt int;

select folder_id into v_folder_id
from mydbr_folders
where name = in_sync_folder_name and mother_id=1;

if (v_folder_id is not null) then

  select count(*) into vReportCnt 
  from mydbr_reports
  where folder_id = v_folder_id;

  select count(*) into vFolderCnt 
  from mydbr_folders
  where mother_id = v_folder_id;

  if ( vReportCnt+vFolderCnt = 0) then
    delete 
    from mydbr_folders_priv 
    where folder_id = v_folder_id;
    
    delete 
    from mydbr_folders
    where folder_id = v_folder_id;
  end if;
end if;

end
$$

drop procedure if exists sp_MyDBR_scheduled_tasks
$$
create procedure sp_MyDBR_scheduled_tasks()
begin

select timing, description, url, last_run, disabled, id, if(last_run is not null, last_run, created_at) as 'last_run_calc'
from mydbr_scheduled_tasks
order by id;

end
$$

drop procedure if exists sp_MyDBR_scheduled_task_set
$$
create procedure sp_MyDBR_scheduled_task_set(
in_task_id int,
in_timing varchar(255),
in_url varchar(2048),
in_description varchar(2048),
in_disabled int
)
begin

if (in_task_id is null) then
  insert into mydbr_scheduled_tasks (description, url, timing, disabled, created_at)
  values (in_description, in_url, in_timing, in_disabled, now());
else
  update mydbr_scheduled_tasks 
  set 
    description = in_description,
    url = in_url,
    timing = in_timing,
    disabled = in_disabled,
    last_run = if (url=in_url and timing=in_timing, last_run, null),
    created_at = if (url=in_url and timing=in_timing, created_at, now())
  where id = in_task_id;
end if;

end
$$

drop procedure if exists sp_MyDBR_scheduled_task_del
$$
create procedure sp_MyDBR_scheduled_task_del(
in_task_id int
)
begin

delete from mydbr_scheduled_tasks 
where id = in_task_id;

end
$$

drop procedure if exists sp_MyDBR_scheduled_update_run
$$
create procedure sp_MyDBR_scheduled_update_run(
in_task_id int,
in_last_run datetime
)
begin

update mydbr_scheduled_tasks 
set last_run = in_last_run
where id = in_task_id;

end
$$

drop procedure if exists sp_MyDBR_report_location
$$
create procedure sp_MyDBR_report_location(
in_id int
)
begin

select folder_id
from mydbr_reports
where report_id = in_id;

end
$$

drop procedure if exists sp_MyDBR_check_mydbr_username
$$
create procedure sp_MyDBR_check_mydbr_username(
in_username varchar(128)
)
begin

select count(*) 
from mydbr_userlogin u 
where user = in_username and authentication = 2;

end
$$

drop procedure if exists sp_MyDBR_scheduler_users
$$
create procedure sp_MyDBR_scheduler_users(
in_search varchar(128)
)
begin

select (user) as 'id', (user) as 'text'
from mydbr_userlogin
where authentication=2 and 
  (lower(user) like concat('%', lower(in_search), '%') or lower(name) like concat('%', lower(in_search), '%'));

end
$$  

DROP PROCEDURE IF EXISTS sp_DBR_StatisticsReport
$$
CREATE PROCEDURE `sp_DBR_StatisticsReport`(
inReportID int,
inStartDate date,
inEndDate date
)
BEGIN

declare vDay date;
declare vEndTime datetime;
declare vCnt int;
declare vDayCnt int;
declare vProcName varchar(100);

select proc_name into vProcName
from mydbr_reports
where report_id = inReportID;

set vEndTime = addtime(cast(inEndDate as datetime), '23:59:59');

select datediff(vEndTime, inStartDate) into vDayCnt;

select 'dbr.pageview';

select name as 'Report', 
       proc_name as 'Procedure', 
       concat( inStartDate, ' - ', inEndDate) as 'Period'
from mydbr_reports
where proc_name = vProcName;

select count(*) into vCnt
from mydbr_statistics s
where proc_name = vProcName and s.start_time between inStartDate and vEndTime;


if (vDayCnt<0) then
  select 'dbr.hideheader';
  select 'Check the dates!';
else
  if (vCnt = 0 ) then
    select 'dbr.hideheader';
    select 'Report has not been run during selected period!';
  else
    if (vDayCnt<32) then

    create temporary table tmp_cnt (
    day date,
    cnt int
    ) ENGINE=MEMORY;

    insert into tmp_cnt ( day, cnt )
    select cast(start_time as date), count(*)
    from mydbr_statistics
    where proc_name= vProcName and start_time between inStartDate and vEndTime
    group by cast(start_time as date);

    while (vDayCnt >= 0) do
      set vDay = vEndTime - interval vDayCnt day;
      insert into tmp_cnt ( day, cnt )
      values (vDay, 0);

      set vDayCnt = vDayCnt -1;
    end while;

    select 'dbr.chart', 'bar';
    select 'dbr.chart.color', '0x0099CC';

    select day, sum(cnt)
    from tmp_cnt
    group by day;

    drop temporary table tmp_cnt;
  end if;

  select 'dbr.keepwithnext';

  select 
    ifnull(u.name , s.username) as 'User', 
    count(*) as 'Run count'
  from mydbr_statistics s left join mydbr_userlogin u on s.username=u.user
  where proc_name = vProcName and s.start_time between inStartDate and vEndTime
  group by 1
  order by 2 desc;


  select 'dbr.colstyle', 'exec_time', 'hh:mm:ss';

  select 
    ifnull(u.name , s.username) as 'User', 
    start_time as 'Report run', 
    timediff(s.end_time,s.start_time) as 'Execution time[exec_time]',
    query as 'Query'
  from mydbr_statistics s left join mydbr_userlogin u on s.username=u.user
  where proc_name = vProcName and s.start_time between inStartDate and vEndTime
  order by start_time desc;

  end if;
end if;
END
$$

DROP PROCEDURE IF EXISTS `sp_DBR_StatisticsSummary` 
$$
CREATE PROCEDURE `sp_DBR_StatisticsSummary`(
inRowCount int,
inStartDate date,
inEndDate date
)
BEGIN
declare vEndTime datetime;

select 'dbr.parameters.show';
select 'dbr.title', concat('Statistics summary ', inStartDate, ' - ', inEndDate);

set vEndTime = addtime(cast(inEndDate as datetime), '23:59:59');

select 'dbr.subtitle', concat(inRowCount, ' Most active users');

/* Not included in distribution */
select 'dbr.report', 'sp_DBR_userusage', 'popup', '[Name]', 'inUser=Username', 'inStartDate=(inStartDate)', 'inEndDate=(inEndDate)';
select 'dbr.hidecolumns', 'Username';

select 'dbr.sum', 'Cnt';

set @num = 0;

select @num := (@num + 1) as '#', 
     Name, 
     Cnt as 'Count',
     Username
from ( select ifnull(u. name , s.username) as 'Name', s.username as 'Username', count(*) as 'Cnt'
  from (select @rows := 0) as x, mydbr_statistics s left join mydbr_userlogin u on s.username= u.user
  where s.start_time between inStartDate and vEndTime
  group by 1, s.username
  having ((@rows := @rows + 1) <= inRowCount )
  order by 3 desc
) as q;


select 'dbr.subtitle', concat(inRowCount, ' Most used reports');

select 'dbr.report', 'sp_DBR_StatisticsReport','inReportID=report_id','inStartDate=(inStartDate)', 'inEndDate=(inEndDate)';
select 'dbr.hidecolumns', 'report_id';
select 'dbr.sum', 'Count';

set @num = 0;

select  @num := (@num + 1) as '#', 
    Name,
    sp as 'Stored procedure', 
    Cnt as 'Count',
    report_id
from (
  select 
    r.name as 'Name', 
    s.proc_name as 'sp', 
    count(*) as 'Cnt', 
    r.report_id
  from mydbr_statistics s, mydbr_reports r, (select @rows := 0) as x
  where s.proc_name=r.proc_name and s.start_time between inStartDate and vEndTime
  group by r.name, s.proc_name, r.report_id
  having ((@rows := @rows + 1) <= inRowCount )
  order by 3 desc
) as q;

select 'dbr.subtitle', concat(inRowCount, ' Slowest reports');


select 'dbr.report', 'sp_DBR_StatisticsReport','inReportID=report_id','inStartDate=(inStartDate)', 'inEndDate=(inEndDate)';
select 'dbr.hidecolumns', 'report_id';

set @num = 0;

select 'dbr.colstyle', 'Min', 'hh:mm:ss';
select 'dbr.colstyle', 'Avg', 'hh:mm:ss';
select 'dbr.colstyle', 'Max', 'hh:mm:ss';

select  @num := (@num + 1) as '#', 
    name as 'Report',
    proc_name as 'Stored procedure',
    Counts as 'Count',
    Mini as 'Min',
    Avge as 'Avg',
    Maxi as 'Max',
    report_id
from (
  select
    r.name,
    s.proc_name,
    count(*) as 'Counts', 
    sec_to_time(min(time_to_sec(timediff(s.end_time,s.start_time)))) as 'Mini', 
    sec_to_time(cast(avg(time_to_sec(timediff(s.end_time,s.start_time))) as signed)) as 'Avge', 
    sec_to_time(max(time_to_sec(timediff(s.end_time,s.start_time)))) as 'Maxi', 
    r.report_id
  from mydbr_statistics s, mydbr_reports r, (select @rows := 0) as x
  where s.proc_name = r.proc_name and s.start_time between inStartDate and vEndTime
  group by r.name, s.proc_name, r.report_id
  having ((@rows := @rows + 1) <= inRowCount )
  order by 5 desc
) as q;

END
$$

INSERT IGNORE INTO `mydbr_params` VALUES 
('sp_DBR_StatisticsSummary','inEndDate',NULL,'End date','Now', 0, 0, null, null),
('sp_DBR_StatisticsSummary','inRowCount','Steps_5-10-20-100','Row count',NULL, 0, 0, null, null),
('sp_DBR_StatisticsSummary','inStartDate',NULL,'Start date','MonthAgo', 0, 0, null, null),
('sp_DBR_StatisticsReport','inEndDate',NULL,'End date','Now', 0, 0, null, null),
('sp_DBR_StatisticsReport','inStartDate',NULL,'Start date','MonthAgo', 0, 0, null, null)
$$

 
delete from mydbr_update
$$
delete from mydbr_version
$$
insert into mydbr_version values ( '5.0.2' )
$$

DROP FUNCTION IF EXISTS `mydbr_style`
$$
CREATE FUNCTION `mydbr_style`( inStyle varchar(150) ) 
RETURNS varchar(400)
DETERMINISTIC
READS SQL DATA
begin
declare vRet varchar(400);
select definition into vRet
from mydbr_styles
where name = inStyle;
return vRet;
END
$$
