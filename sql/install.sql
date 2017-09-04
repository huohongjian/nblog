/*
	database: 	nblog
	date:		2017-09-12
	author:		huohongjian
*/

DROP TABLE IF EXISTS nblog_article CASCADE;
DROP SEQUENCE IF EXISTS nblog_article_seq;
CREATE SEQUENCE nblog_article_seq;
CREATE TABLE IF NOT EXISTS nblog_article (
	articleid 	integer NOT NULL DEFAULT nextval('nblog_article_seq'),
	columnid 		integer NOT NULL default 0, 	-- 栏目id
	categoryid 	integer NOT NULL default 0,	-- 自定义类别id
	title 			varchar(255) NOT NULL default '',
	alias			varchar(255) NOT NULL default '',
	caption 		varchar(255) NOT NULL default '',
	userid 		integer NOT NULL default 0,
	publish 		boolean NOT NULL default true,
	counter 		integer NOT NULL default 0,
	comment 		integer NOT NULL default 0,
	posttime 		timestamp(0) without time zone NOT NULL DEFAULT now(),
	content 		text,
	thumb			varchar(255),
	format		integer NOT NULL default 1, --1:text, 2:markdown, 3:html
	CONSTRAINT nblog_article_pkey PRIMARY KEY (articleid)
);
CREATE INDEX nblog_article_columnid 	ON nblog_article (columnid);
CREATE INDEX nblog_article_categoryid 	ON nblog_article (categoryid);
CREATE INDEX nblog_article_userid 		ON nblog_article (userid);

/*
CREATE OR REPLACE FUNCTION nblog_article_get_by_categoryid(_categoryid integer)
	RETURNS SETOF nblog_article AS $$
DECLARE
	_path TEXT;
BEGIN
	SELECT INTO _path path FROM nblog_category WHERE categoryid=$1;
	_path := _path || $1 || ',%';
	RETURN QUERY SELECT a.* FROM nblog_article a 
				 LEFT JOIN nblog_category b 
				 ON a.categoryid=b.categoryid 
				 WHERE b.categoryid=$1 OR b.path LIKE _path;
END;
$$ LANGUAGE plpgsql;
*/


DROP TABLE IF EXISTS nblog_comment CASCADE;
DROP SEQUENCE IF EXISTS nblog_comment_seq;
CREATE SEQUENCE nblog_comment_seq;
CREATE TABLE IF NOT EXISTS nblog_comment (
	commentid 	integer NOT NULL DEFAULT nextval('nblog_comment_seq'),
	articleid 	integer NOT NULL default 0,
	userid 		integer NOT NULL default 0,
	posttime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	agree 		integer NOT NULL default 0,
	oppose 		integer NOT NULL default 0,
	content 	text,
	CONSTRAINT nblog_comment_pkey PRIMARY KEY (commentid)
);
CREATE INDEX nblog_comment_articleid ON nblog_comment (articleid);


DROP TABLE IF EXISTS nblog_session CASCADE;
DROP SEQUENCE IF EXISTS nblog_session_seq;
CREATE SEQUENCE nblog_session_seq;
CREATE TABLE nblog_session
(
	sid 		bigint NOT NULL DEFAULT nextval('nblog_session_seq'),
	sessionid 	varchar(255) NOT NULL DEFAULT '',
	logintime 	timestamp(0) NOT NULL DEFAULT current_timestamp,
	data jsonb,
	CONSTRAINT nblog_session_pkey PRIMARY KEY (sid)	
);
CREATE INDEX nblog_session_sessionid ON nblog_session(sessionid);
CREATE INDEX nblog_session_logintime ON nblog_session(logintime);

/*
CREATE OR REPLACE FUNCTION nblog_session_update(varchar, jsonb) RETURNS boolean AS $body$
BEGIN
	UPDATE nblog_session SET logintime=now(), data=data || $2 WHERE sessionid=$1;
	IF found THEN
		RETURN true;
	END IF;
	
	INSERT INTO nblog_session(sessionid, data) VALUES ($1, $2);
	IF found THEN
		RETURN true;
	END IF;
	
	RETURN false;
END;
$body$ LANGUAGE 'plpgsql';
*/


DROP TABLE IF EXISTS nblog_role CASCADE;
DROP SEQUENCE IF EXISTS nblog_role_seq;
CREATE SEQUENCE nblog_role_seq;
CREATE TABLE IF NOT EXISTS nblog_role (
	roleid 	integer NOT NULL DEFAULT nextval('nblog_role_seq'),
	name 	varchar(16) NOT NULL default '',
	remark	varchar(32) NOT NULL default '',
	CONSTRAINT nblog_role_pkey PRIMARY KEY (roleid)
);

INSERT INTO nblog_role VALUES
(10,'root',		'超级用户'),
(20,'admin', 	'系统管理员'),
(30,'column', 	'栏目负责人'),
(40,'vip',		'高级用户'),
(50,'regist',	'注册用户'),
(60,'anon',		'匿名用户'),
(90,'other',	'其他');





-- TABLE: nblog_user  用户表
DROP TABLE IF EXISTS nblog_user CASCADE;
DROP SEQUENCE IF EXISTS nblog_user_seq;	CREATE SEQUENCE nblog_user_seq;
CREATE TABLE IF NOT EXISTS nblog_user (
	userid 		integer NOT NULL DEFAULT nextval('nblog_user_seq'),
	realname 	character varying(32) NOT NULL default '',
	username 	character varying(32) NOT NULL default '',
	password 	character varying(32) NOT NULL default '',
	roleid 		integer default 0,
	score 		integer NOT NULL default 0,
	jointime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	telephone 	character varying(16) default NULL,
	email 		character varying(64) default NULL,
	homepage 	character varying(64) default NULL,
	qq 			character varying(32) default NULL,
	intro 		character varying(255) default NULL,
	photo 		character varying(255) default NULL,
  	CONSTRAINT nblog_user_pkey PRIMARY KEY (userid),
  	UNIQUE (username)
);
CREATE INDEX nblog_user_username ON nblog_user (username);
CREATE INDEX nblog_user_score 	 ON nblog_user (score);

INSERT INTO nblog_user(realname, username, password, roleid)  VALUES
('root', 		   	'root', 		'202cb962ac59075b964b07152d234b70', 	10),
('administrator',  	'admin', 		'202cb962ac59075b964b07152d234b70', 	20),
('HuoHongJian', 	'huohongjian',	'202cb962ac59075b964b07152d234b70', 	20),
('huohongjian', 	'hhj', 			'202cb962ac59075b964b07152d234b70', 	50),
('anonymous', 		'anon', 		'202cb962ac59075b964b07152d234b70', 	60);


DROP TABLE IF EXISTS nblog_column CASCADE;
DROP SEQUENCE IF EXISTS nblog_column_seq;
CREATE SEQUENCE nblog_column_seq;
CREATE TABLE IF NOT EXISTS nblog_column (
	columnid 	integer NOT NULL DEFAULT nextval('nblog_column_seq'),
	name 		varchar(64) NOT NULL default '',
	remark		text,
	CONSTRAINT nblog_column_pkey PRIMARY KEY (columnid)
);
CREATE INDEX nblog_column_name 	ON nblog_column (name);


INSERT INTO nblog_column (name) VALUES
('最新消息'),
('技术交流'),
('文档手册'),
('软件下载');



DROP TABLE IF EXISTS nblog_category CASCADE;
DROP SEQUENCE IF EXISTS nblog_category_seq;
CREATE SEQUENCE nblog_category_seq;
CREATE TABLE IF NOT EXISTS nblog_category (
	categoryid 	integer NOT NULL DEFAULT nextval('nblog_category_seq'),
	parentid 	integer NOT NULL default 0,
	name 		varchar(64) NOT NULL default '',
	leaf		boolean NOT NULL default true,
	userid 		integer NOT NULL default 0,
	path 		varchar(64) NOT NULL default '0,',
	odr 		integer NOT NULL default 0,
	CONSTRAINT nblog_category_pkey PRIMARY KEY (categoryid)
);
CREATE INDEX nblog_category_parentid 	ON nblog_category (parentid);
CREATE INDEX nblog_category_userid		ON nblog_category (userid);
CREATE INDEX nblog_category_path		ON nblog_category (path);
CREATE INDEX nblog_category_odr			ON nblog_category (odr);

INSERT INTO nblog_category VALUES
(1, 0, '全部类别', true, 0, '', 0);




/*
DROP TABLE IF EXISTS nblog_appendage CASCADE;
DROP SEQUENCE IF EXISTS nblog_appendage_seq;
CREATE SEQUENCE nblog_appendage_seq;
CREATE TABLE IF NOT EXISTS nblog_appendage (
	appendageid int NOT NULL DEFAULT nextval('nblog_appendage_seq'),
	categoryid integer NOT NULL default 0,
	pathid integer NOT NULL default 0,
	savename varchar(255) NOT NULL default '',
	filename varchar(255) NOT NULL default '',
	size int NOT NULL default 0,
	articleid int NOT NULL default 0,
	userid integer NOT NULL default 0,
	CONSTRAINT nblog_appendage_pkey PRIMARY KEY (appendageid)
);
CREATE INDEX nblog_appendage_categoryid 	ON nblog_appendage (categoryid);
CREATE INDEX nblog_appendage_articleid		ON nblog_appendage (articleid);
CREATE INDEX nblog_appendage_userid			ON nblog_appendage (userid);
*/

/*
DROP TABLE IF EXISTS nblog_appendage_category CASCADE;
DROP SEQUENCE IF EXISTS nblog_appendage_category_seq;
CREATE SEQUENCE nblog_appendage_category_seq;
CREATE TABLE IF NOT EXISTS nblog_appendage_category (
	categoryid integer NOT NULL DEFAULT nextval('nblog_appendage_category_seq'),
	name varchar(255) NOT NULL default '',
	extra varchar(255) NOT NULL default '',
	CONSTRAINT nblog_appendage_category_pkey PRIMARY KEY (categoryid)
);

INSERT INTO nblog_appendage_category VALUES
(1,'文本文件','.txt'),
(2,'网页文件','.htm.html.js.php.asp.apsx'),
(3,'xml文件','.xml'),
(4,'chm文件','.chm'),
(5,'office文件','.doc.xls.ppt'),
(6,'压缩文件','.rar.zip'),
(7,'图像文件','.jpg.ico.png.gif'),
(8,'音频文件','.mp3'),
(9,'视频文件','.avi.rm'),
(10,'数据文件',''),
(11,'驱动程序',''),
(12,'应用程序',''),
(13,'linux程序',''),
(14,'bsd程序',''),
(99,'其他','');
*/

/*
DROP TABLE IF EXISTS nblog_appendage_path CASCADE;
DROP SEQUENCE IF EXISTS nblog_appendage_path_seq;
CREATE SEQUENCE nblog_appendage_path_seq;
CREATE TABLE IF NOT EXISTS nblog_appendage_path (
	pathid integer NOT NULL DEFAULT nextval('nblog_appendage_path_seq'),
	name varchar(255) NOT NULL default '',
	CONSTRAINT nblog_appendage_path_pkey PRIMARY KEY (pathid)
);

INSERT INTO nblog_appendage_path VALUES 
(1,'_upload/cwc/default/'),
(2,'_upload/cwc/txt/'),
(3,'_upload/cwc/html/'),
(4,'_upload/cwc/office/'),
(5,'_upload/cwc/driver/'),
(6,'_upload/cwc/soft/'),
(7,'_upload/cwc/photo/'),
(8,'_upload/cwc/image/'),
(9,'_upload/cwc/mp3/'),
(10,'_upload/cwc/linux/'),
(11,'_upload/cwc/freebsd/'),
(12,'_upload/cwc/game/'),
(98,'_upload/cwc/temp/'),
(99,'_upload/cwc/other/');
*/

/*
DROP TABLE IF EXISTS nblog_homepage_image CASCADE;
DROP SEQUENCE IF EXISTS nblog_homepage_image_seq;
CREATE SEQUENCE nblog_homepage_image_seq;
CREATE TABLE IF NOT EXISTS nblog_homepage_image (
	imageid int NOT NULL DEFAULT nextval('nblog_homepage_image_seq'),
	categoryid integer NOT NULL default 0,
	appendageid int NOT NULL default 0,
	articleid int NOT NULL default 0,
	CONSTRAINT nblog_homepage_image_pkey PRIMARY KEY (imageid)
);
CREATE INDEX nblog_homepage_image_categoryid 	ON nblog_homepage_image (categoryid);



DROP TABLE IF EXISTS nblog_image_category CASCADE;
DROP SEQUENCE IF EXISTS nblog_image_category_seq;
CREATE SEQUENCE nblog_image_category_seq;
CREATE TABLE IF NOT EXISTS nblog_image_category (
	categoryid integer NOT NULL DEFAULT nextval('nblog_image_category_seq'),
	name varchar(255) NOT NULL default '',
	dimension varchar(64) NOT NULL default '',
	CONSTRAINT nblog_image_category_pkey PRIMARY KEY (categoryid)
);

INSERT INTO nblog_image_category VALUES
(1,'新闻图片1','200*150'),
(2,'右侧图片1','600*80');
*/


/*
DROP TABLE IF EXISTS nblog_session CASCADE;
DROP SEQUENCE IF EXISTS nblog_session_seq;	CREATE SEQUENCE nblog_session_seq;
CREATE TABLE nblog_session
(
	sid bigint NOT NULL DEFAULT nextval('nblog_session_seq'),
	sessionid varchar(255) NOT NULL DEFAULT '',
	logintime timestamp(0) NOT NULL DEFAULT now(),
	val text NOT NULL DEFAULT '',
	CONSTRAINT nblog_session_pkey PRIMARY KEY (sid)
);
CREATE INDEX nblog_session_sessionid ON nblog_session(sessionid);
CREATE INDEX nblog_session_logintime ON nblog_session(logintime);

CREATE OR REPLACE FUNCTION nblog_session_update(varchar, jsonb) RETURNS boolean AS $body$
BEGIN
	UPDATE nblog_session SET logintime=now(), data=data || $2 WHERE sessionid=$1;
	IF found THEN
		RETURN true;
	END IF;
	
	INSERT INTO nblog_session(sessionid, data) VALUES ($1, $2);
	IF found THEN
		RETURN true;
	END IF;
	
	RETURN false;
END;
$body$ LANGUAGE 'plpgsql';
*/


/*

DROP TABLE IF EXISTS nblog_cache CASCADE;
DROP SEQUENCE IF EXISTS nblog_cache_seq;
CREATE SEQUENCE nblog_cache_seq;
CREATE TABLE IF NOT EXISTS nblog_cache (
	cid integer NOT NULL DEFAULT nextval('nblog_cache_seq'),
	cacheid varchar(32) NOT NULL default '',
	thetime timestamp(0) without time zone,
	value text,
	CONSTRAINT nblog_cache_pkey PRIMARY KEY (cid)
);
CREATE INDEX nblog_cache_cacheid	ON nblog_cache (cacheid);
*/
