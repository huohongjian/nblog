/*
	database: 	nblog
	date:		2017-09-12
	author:		huohongjian
*/


DROP TABLE IF EXISTS nb_donation CASCADE;
DROP SEQUENCE IF EXISTS nb_donation_seq;
CREATE SEQUENCE nb_donation_seq;
CREATE TABLE IF NOT EXISTS nb_donation (
	donationid 	integer NOT NULL DEFAULT nextval('nb_donation_seq'),
	donor 		text NOT NULL default '',
	amount		numeric(9,2) NOT NULL default 0,
	beneficence	text NOT NULL default '',
	day			date NOT NULL default CURRENT_DATE,
	CONSTRAINT nb_donation_pkey PRIMARY KEY (donationid)
);



/*
DROP TABLE IF EXISTS nb_suggest CASCADE;
DROP SEQUENCE IF EXISTS nb_suggest_seq;
CREATE SEQUENCE nb_suggest_seq;
CREATE TABLE IF NOT EXISTS nb_suggest (
	suggestid 	integer NOT NULL DEFAULT nextval('nb_suggest_seq'),
	content 	varchar(32) NOT NULL default '',
	money		
	answer		text NOT NULL default '',
	addtime		timestamp(0) without time zone NOT NULL DEFAULT now(),
	CONSTRAINT nb_suggest_pkey PRIMARY KEY (suggestid)
);

*/




DROP TABLE IF EXISTS nb_article CASCADE;
DROP SEQUENCE IF EXISTS nb_article_seq;
DROP TYPE IF EXISTS nb_article_status_enum;
CREATE TYPE nb_article_status_enum AS ENUM ('推荐', '公开', '隐藏', '删除');
CREATE SEQUENCE nb_article_seq;
CREATE TABLE IF NOT EXISTS nb_article (
	artid		integer NOT NULL DEFAULT nextval('nb_article_seq'),
	articleid 	varchar(32) UNIQUE,
	category 	varchar(32) NOT NULL default '',
	columnid 	integer NOT NULL default 0,
	userid 		integer NOT NULL default 0,
	counter 	integer NOT NULL default 0,
	comment 	integer NOT NULL default 0,
	isbook		boolean NOT NULL default false,	-- 电子书格式
	title 		varchar(255) NOT NULL default '',
	alias		varchar(255) NOT NULL default '',
	keywords	varchar(255) NOT NULL default '',
	thumb		varchar(255) NOT NULL default '',
	caption 	text NOT NULL default '',
	content 	text NOT NULL default '',
	status		nb_article_status_enum NOT NULL default '公开',
	addtime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	newtime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	CONSTRAINT nb_article_pkey PRIMARY KEY (artid)
);
CREATE INDEX nb_article_articleid 		ON nb_article (articleid);
CREATE INDEX nb_article_columnid 		ON nb_article (columnid);
CREATE INDEX nb_article_userid_category	ON nb_article (userid, category);
CREATE INDEX nb_article_status 			ON nb_article (status);





DROP TABLE IF EXISTS nb_column CASCADE;
DROP SEQUENCE IF EXISTS nb_column_seq;
CREATE SEQUENCE nb_column_seq;
CREATE TABLE IF NOT EXISTS nb_column (
	columnid 	integer NOT NULL DEFAULT nextval('nb_column_seq'),
	parentid 	integer NOT NULL default 0,
	name 		varchar(64) NOT NULL default '',
	leaf		boolean NOT NULL default true,
	path 		varchar(64) NOT NULL default '0,',
	odr 		integer NOT NULL default 0,
	CONSTRAINT nb_column_pkey PRIMARY KEY (columnid)
);
CREATE INDEX nb_column_parentid 	ON nb_column (parentid);
CREATE INDEX nb_column_path		ON nb_column (path);
CREATE INDEX nb_column_odr		ON nb_column (odr);

INSERT INTO nb_column VALUES
(1,    0, '全部类别', false, '0,', 0),
(2,	   1, '网站栏目', false, '0,1,', 0),
(3,    1, 'FreeBSD',  false, '0,1,', 0),

(201,  2, '资讯1',    true,  '0,1,2,', 0),
(202,  2, '资讯2',    true,  '0,1,2,', 0),
(203,  2, '资讯4',    true,  '0,1,2,', 0),
(204,  2, '资讯5',    true,  '0,1,2,', 0),
(205,  2, '资讯6',    true,  '0,1,2,', 0),
(206,  2, '技术精华', true,  '0,1,2,', 0),
(207,  2, '资源链接', true,  '0,1,2,', 0),

(301,  3, '系统手册', true,  '0,1,3,', 0),
(302,  3, '环境变量', true,  '0,1,3,', 0),
(303,  3, '桌面应用', true,  '0,1,3,', 0),
(304,  3, '服务应用', true,  '0,1,3,', 0),
(305,  3, '网络应用', true,  '0,1,3,', 0),
(306,  3, '内核模块', true,  '0,1,3,', 0),
(307,  3, '存储安全', true,  '0,1,3,', 0),
(308,  3, '脚本工具', true,  '0,1,3,', 0),
(309,  3, '源码实例', true,  '0,1,3,', 0);

/*


DROP TABLE IF EXISTS nb_status CASCADE;
DROP SEQUENCE IF EXISTS nb_status_seq;
CREATE SEQUENCE nb_status_seq;
CREATE TABLE IF NOT EXISTS nb_status (
	statusid 	integer NOT NULL DEFAULT nextval('nb_status_seq'),
	name 		varchar(32) NOT NULL default '',
	remark		text,
	CONSTRAINT nb_status_pkey PRIMARY KEY (statusid)
);

INSERT INTO nb_status (name, remark) VALUES
('推荐', '向管理员推荐此文，并在用户推荐栏中显示。'),
('公开', '任何人都可浏览、可搜索。'),
('隐藏', '只有用户可浏览、可搜索，其他人不可浏览、不可搜索。'),
('删除', '用户也不可浏览、不可搜索，但可通过文章管理更改状态。');





DROP TABLE IF EXISTS nb_comment CASCADE;
DROP SEQUENCE IF EXISTS nb_comment_seq;
CREATE SEQUENCE nb_comment_seq;
CREATE TABLE IF NOT EXISTS nb_comment (
	commentid 	integer NOT NULL DEFAULT nextval('nb_comment_seq'),
	artid 		integer NOT NULL default 0,
	userid 		integer NOT NULL default 0,
	agree 		integer NOT NULL default 0,
	oppose 		integer NOT NULL default 0,
	content 	text,
	posttime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	CONSTRAINT nb_comment_pkey PRIMARY KEY (commentid)
);
CREATE INDEX nb_comment_artid ON nb_comment (artid);


DROP TABLE IF EXISTS nb_session CASCADE;
CREATE TABLE nb_session
(
	sessionid 	varchar(255) NOT NULL DEFAULT '',
	logintime 	timestamp(0) NOT NULL DEFAULT current_timestamp,
	data jsonb,
	CONSTRAINT nb_session_pkey PRIMARY KEY (sessionid)	
);
CREATE INDEX nb_session_logintime ON nb_session(logintime);


CREATE OR REPLACE FUNCTION nb_session_upsert(varchar, jsonb) RETURNS boolean AS $body$
BEGIN
	UPDATE nb_session SET logintime=now(), data=data||($2) WHERE sessionid=$1;
	IF found THEN
		RETURN true;
	END IF;
	
	INSERT INTO nb_session(sessionid, data) VALUES ($1, ($2));
	IF found THEN
		RETURN true;
	END IF;
	
	RETURN false;
END;
$body$ LANGUAGE 'plpgsql';



DROP TABLE IF EXISTS nb_role CASCADE;
DROP SEQUENCE IF EXISTS nb_role_seq;
CREATE SEQUENCE nb_role_seq;
CREATE TABLE IF NOT EXISTS nb_role (
	roleid 	integer NOT NULL DEFAULT nextval('nb_role_seq'),
	name 	varchar(16) NOT NULL default '',
	remark	varchar(32) NOT NULL default '',
	CONSTRAINT nb_role_pkey PRIMARY KEY (roleid)
);

INSERT INTO nb_role VALUES
(1,'root',		'超级用户'),
(2,'admin', 	'系统管理员'),
(3,'column', 	'栏目管理人'),
(4,'vip',		'高级用户'),
(5,'regist',	'注册用户'),
(6,'anon',		'匿名用户');





-- TABLE: nb_user  用户表
DROP TABLE IF EXISTS nb_user CASCADE;
DROP SEQUENCE IF EXISTS nb_user_seq;
CREATE SEQUENCE nb_user_seq;
CREATE TABLE IF NOT EXISTS nb_user (
	userid 		integer NOT NULL DEFAULT nextval('nb_user_seq'),
	name 		character varying(32) NOT NULL default '',
	login 		character varying(32) UNIQUE,
	password 	character varying(32) NOT NULL default '',
	roleid 		integer NOT NULL default 5,
	score 		integer NOT NULL default 0,
	categories	text NOT NULL default 'FreeBSD,系统管理,网络服务,桌面应用,数据库,编程',
	jointime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	telephone 	character varying(16),
	email 		character varying(64),
	qq 			character varying(32),
	photo 		character varying(255) default '/thumbs/1.jpg',
	intro 		text,
	remark		text,
  	CONSTRAINT nb_user_pkey PRIMARY KEY (userid)
);
CREATE INDEX nb_user_login 	ON nb_user (login);
CREATE INDEX nb_user_score 	ON nb_user (score);

INSERT INTO nb_user(name, login, password, roleid)  VALUES
('root', 		   	'root', 		'202cb962ac59075b964b07152d234b70', 1),
('administrator',  	'administrator','202cb962ac59075b964b07152d234b70', 2),
('administrator',  	'admin', 		'202cb962ac59075b964b07152d234b70', 2),
('anonymous', 		'anon', 		'202cb962ac59075b964b07152d234b70', 6),
('iceage',			'iceage',		'202cb962ac59075b964b07152d234b70',	2),
('HuoHongJian', 	'huohongjian',	'202cb962ac59075b964b07152d234b70', 2),
('HHJ',			 	'hhj', 			'202cb962ac59075b964b07152d234b70', 5);


*/

















/*
CREATE OR REPLACE FUNCTION nb_article_get_by_categoryid(_categoryid integer)
	RETURNS SETOF nb_article AS $$
DECLARE
	_path TEXT;
BEGIN
	SELECT INTO _path path FROM nb_category WHERE categoryid=$1;
	_path := _path || $1 || ',%';
	RETURN QUERY SELECT a.* FROM nb_article a 
				 LEFT JOIN nb_category b 
				 ON a.categoryid=b.categoryid 
				 WHERE b.categoryid=$1 OR b.path LIKE _path;
END;
$$ LANGUAGE plpgsql;
*/


/*************************************************************************************************
DROP TABLE IF EXISTS nb_appendage CASCADE;
DROP SEQUENCE IF EXISTS nb_appendage_seq;
CREATE SEQUENCE nb_appendage_seq;
CREATE TABLE IF NOT EXISTS nb_appendage (
	appendageid int NOT NULL DEFAULT nextval('nb_appendage_seq'),
	categoryid integer NOT NULL default 0,
	pathid integer NOT NULL default 0,
	savename varchar(255) NOT NULL default '',
	filename varchar(255) NOT NULL default '',
	size int NOT NULL default 0,
	articleid int NOT NULL default 0,
	userid integer NOT NULL default 0,
	CONSTRAINT nb_appendage_pkey PRIMARY KEY (appendageid)
);
CREATE INDEX nb_appendage_categoryid 	ON nb_appendage (categoryid);
CREATE INDEX nb_appendage_articleid		ON nb_appendage (articleid);
CREATE INDEX nb_appendage_userid			ON nb_appendage (userid);
*/

/*
DROP TABLE IF EXISTS nb_appendage_category CASCADE;
DROP SEQUENCE IF EXISTS nb_appendage_category_seq;
CREATE SEQUENCE nb_appendage_category_seq;
CREATE TABLE IF NOT EXISTS nb_appendage_category (
	categoryid integer NOT NULL DEFAULT nextval('nb_appendage_category_seq'),
	name varchar(255) NOT NULL default '',
	extra varchar(255) NOT NULL default '',
	CONSTRAINT nb_appendage_category_pkey PRIMARY KEY (categoryid)
);

INSERT INTO nb_appendage_category VALUES
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
DROP TABLE IF EXISTS nb_appendage_path CASCADE;
DROP SEQUENCE IF EXISTS nb_appendage_path_seq;
CREATE SEQUENCE nb_appendage_path_seq;
CREATE TABLE IF NOT EXISTS nb_appendage_path (
	pathid integer NOT NULL DEFAULT nextval('nb_appendage_path_seq'),
	name varchar(255) NOT NULL default '',
	CONSTRAINT nb_appendage_path_pkey PRIMARY KEY (pathid)
);

INSERT INTO nb_appendage_path VALUES 
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
DROP TABLE IF EXISTS nb_homepage_image CASCADE;
DROP SEQUENCE IF EXISTS nb_homepage_image_seq;
CREATE SEQUENCE nb_homepage_image_seq;
CREATE TABLE IF NOT EXISTS nb_homepage_image (
	imageid int NOT NULL DEFAULT nextval('nb_homepage_image_seq'),
	categoryid integer NOT NULL default 0,
	appendageid int NOT NULL default 0,
	articleid int NOT NULL default 0,
	CONSTRAINT nb_homepage_image_pkey PRIMARY KEY (imageid)
);
CREATE INDEX nb_homepage_image_categoryid 	ON nb_homepage_image (categoryid);



DROP TABLE IF EXISTS nb_image_category CASCADE;
DROP SEQUENCE IF EXISTS nb_image_category_seq;
CREATE SEQUENCE nb_image_category_seq;
CREATE TABLE IF NOT EXISTS nb_image_category (
	categoryid integer NOT NULL DEFAULT nextval('nb_image_category_seq'),
	name varchar(255) NOT NULL default '',
	dimension varchar(64) NOT NULL default '',
	CONSTRAINT nb_image_category_pkey PRIMARY KEY (categoryid)
);

INSERT INTO nb_image_category VALUES
(1,'新闻图片1','200*150'),
(2,'右侧图片1','600*80');
*/


/*

DROP TABLE IF EXISTS nb_cache CASCADE;
DROP SEQUENCE IF EXISTS nb_cache_seq;
CREATE SEQUENCE nb_cache_seq;
CREATE TABLE IF NOT EXISTS nb_cache (
	cid integer NOT NULL DEFAULT nextval('nb_cache_seq'),
	cacheid varchar(32) NOT NULL default '',
	thetime timestamp(0) without time zone,
	value text,
	CONSTRAINT nb_cache_pkey PRIMARY KEY (cid)
);
CREATE INDEX nb_cache_cacheid	ON nb_cache (cacheid);
*/
