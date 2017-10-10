/*
	database: 	nblog
	date:		2017-10-08
	author:		huohongjian
*/


/*

DROP TABLE IF EXISTS nb_donation CASCADE;
DROP SEQUENCE IF EXISTS nb_donation_seq;
CREATE SEQUENCE nb_donation_seq;
CREATE TABLE IF NOT EXISTS nb_donation (
	donationid 	integer NOT NULL DEFAULT nextval('nb_donation_seq'),
	donor 		text NOT NULL default '',
	amount		numeric(9,2) NOT NULL default 0,
	donations	text NOT NULL default '',
	remark		text NOT NULL default '',
	day			date NOT NULL default CURRENT_DATE,
	CONSTRAINT nb_donation_pkey PRIMARY KEY (donationid)
);

INSERT INTO nb_donation(donor, amount, donations, remark) VALUES
('陆压',		0,		'VPS', 				'1年'),
('豆芽',		0,		'chinafreebsd.org',	'1年'),
('Helo',		100,	'chinafreebsd.cn',	'1年'),
('Helo',		0,		'服务器一台',		'宽带费1年'),
('冰河四世纪',	14,		'',		''),
('j3ff',		100,	'四类源克隆',		'4T'),
('Arthas',		12,		'',		''),
('東雯羌',		6,		'',		''),
('╰→手牵ぷ手',	20,		'',		''),
('天天向上',	100,	'',		''),
('一个少年',	201,	'',		''),
('andreas',		17.9,	'',		''),
('sulit',		50,		'',		''),
('独孤云飞',	28.4,	'',		''),
('四方果',		100,	'',		''),
('BigDragon',	34.43,	'',		''),
('andreas',		100,	'',		''),
('njlyf2011',	40,		'',		''),
('ifind',		10.75,	'',		''),
('副帅',		4,		'',		''),
('夶陆毳毳蟲',	10,		'',		''),
('风滚草',		30,		'',		''),
('Simontune',	15,		'',		''),
('Bluse',		50,		'',		''),
('玉米汁',		5,		'',		''),
('Jasonlecson',	20,		'',		''),
('Auk7F7',		4,		'',		''),
('Dasyatis',	27.52,	'',		''),
('欧仁友',		30,		'',		''),
('X-Ray',		7,		'',		''),
('Dasyatis',	11.11,	'',		''),
('寄生前夜',	50.0,	'',		''),
('祥子',		100,	'',		''),
('寄生前夜',	20,		'',		''),
('Hacking',		20,		'',		'');




DROP TABLE IF EXISTS nb_suggest CASCADE;
DROP SEQUENCE IF EXISTS nb_suggest_seq;
CREATE SEQUENCE nb_suggest_seq;
CREATE TABLE IF NOT EXISTS nb_suggest (
	suggestid 	integer NOT NULL DEFAULT nextval('nb_suggest_seq'),
	content 	varchar(128) NOT NULL default '',
	answer		text NOT NULL default '',
	addtime		timestamp(0) without time zone NOT NULL DEFAULT now(),
	CONSTRAINT nb_suggest_pkey PRIMARY KEY (suggestid)
);




DROP TABLE IF EXISTS nb_article CASCADE;
DROP SEQUENCE IF EXISTS nb_article_seq;
DROP TYPE IF EXISTS nb_article_status_enum;

CREATE TYPE nb_article_status_enum AS ENUM ('公开', '隐藏', '删除');
CREATE SEQUENCE nb_article_seq;
CREATE TABLE IF NOT EXISTS nb_article (
	artid		integer NOT NULL DEFAULT nextval('nb_article_seq'),
	articleid 	varchar(32) UNIQUE,
	category 	varchar(32) NOT NULL default '其他',
	columns 	varchar(32) NOT NULL default '其他',
	userid 		integer NOT NULL default 0,
	counter 	integer NOT NULL default 0,
	comment 	integer NOT NULL default 0,
	title 		varchar(255) NOT NULL default '',
	alias		varchar(255) NOT NULL default '',
	keywords	varchar(255) NOT NULL default '',
	thumb		varchar(255) NOT NULL default '',
	caption 	text NOT NULL default '',
	content 	text NOT NULL default '',
	isbook		boolean NOT NULL default false,	-- 电子书格式
	approved	boolean NOT NULL default true,  -- 审核通过
	status		nb_article_status_enum NOT NULL default '公开',
	addtime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	newtime 	timestamp(0) without time zone NOT NULL DEFAULT now(),
	CONSTRAINT nb_article_pkey PRIMARY KEY (artid)
);
CREATE INDEX nb_article_articleid 	ON nb_article (articleid);
CREATE INDEX nb_article_columns		ON nb_article (columns, status, approved);
CREATE INDEX nb_article_category 	ON nb_article (category, status, approved);
CREATE INDEX nb_article_userid		ON nb_article (userid, category, status, approved);

INSERT INTO nb_article (status, columns, articleid, thumb, title, userid, content) VALUES 
('隐藏', '首页栏目', 'chinafreebsd-column-100', '/thumbs/1.jpg', 'FreeBSD简介', 3, '<span><span style="color:#990000; font-weight:900;">FreeBSD</span> 是一个适用于现代服务器、桌面与嵌入式平台的先进操作系统。由一个庞大的开发人员团队持续开发超过三十年。FreeBSD先进的网络、安全性和存储方面的特色使得它成为许多大型网站以及最普遍的嵌入式网络与存储设备的平台选择。</span>'),
('隐藏', '首页栏目', 'chinafreebsd-column-110', '', 'China FreeBSD简介', 3, '<span><span style="color:#990000; font-weight:900;">China FreeBSD</span> 致力于FreeBSD在中国的推广。我们的计划是：(1)建立China FreeBSD镜像服务器，免费提供下载和更新服务；(2)建立China FreeBSD Wiki；(3)建立DDNS系统；(4)建立中文man查询系统等。本站遵从开源精神，不定期更新和发布 FreeBSD的资讯消息和技术资料。我们将尽全力帮助那些需要帮助的人们，也热切期盼您的参与和贡献！</span>'),
('隐藏', '首页栏目', 'chinafreebsd-column-120', '', 'ChinaFreeBSD社区新闻', 3, '<span style="color:#990000;font-weight:900;">China FreeBSD 中国社区改版啦！</span>我们重新改版了社区主站，站点程序和内容由iceage和HHJ共同设计开发。新版的文章显示遵循了 FreeBSD 手册的风格，文章格式及样式简洁统一，更符合社区成立时的初衷理念（收集与共享 FreeBSD 知识）。网站主体风格更加贴近 FreeBSD 官方样式，这是我们有意为之，目的不是替代，而是传承 FreeBSD 的文化，使爱好者更加爱好，共同陶醉在 FreeBSD 的氛围里。'),
('隐藏', '首页栏目', 'chinafreebsd-column-130', '', '社区FTP服务器', 3, '<span style="color:#990000;font-weight:900;">社区FTP服务器建成</span> 通过长时间的努力和收集，ChinaFreeBSD社区FTP服务器建成并上线，服务器地址为<a href="http://ftp1.chinafreebsd.cn" target="_blank"><span style="color:#337FE5;">ftp1.chinafreebsd.cn</span></a> ，由J3ff提供维护。目前服务器包含了FreeBSD、NetBSD和OpenBSD的在线资源，其中FreeBSD为每两个小时更新一次，其他BSD系统为每天更新一次，停止更新的系统版本请访问*BSD-Archive。目前只能通过http协议访问，FTP协议的访问方式正在建设中。');


INSERT INTO nb_article (status, columns, articleid, title, userid, content) VALUES 
('隐藏', '系统文章', 'chinafreebsd-resouce', '本站源', 3, '<table class="wikitable"><tbody><tr><th>源类型</th><th>源地址</th><th>状态</th><th>地区</th><th>线路</th><th>备注</th></tr><tr><td><span>pkg源0</span> </td><td><a class="external free" href="http://pkg0.chinafreebsd.cn">http://pkg0.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>吉林</td><td><span>联通</span> </td><td rowspan="3"><a href="https://www.chinafreebsd.cn/index.php?title=Pkg_source"><span>换源方法</span></a> </td></tr><tr><td><span>pkg源1</span> </td><td><a class="external free" href="http://pkg1.chinafreebsd.cn">http://pkg1.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>河北</td><td><span>联通</span> </td></tr><tr><td><span>pkg源2</span> </td><td><a class="external free" href="http://pkg2.chinafreebsd.cn">http://pkg2.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>陕西</td><td><span>电信</span> </td></tr><tr><td><span>ports源0</span> </td><td><a class="external free" href="http://ports0.chinafreebsd.cn">http://ports0.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>吉林</td><td><span>联通</span> </td><td rowspan="3"><a href="https://www.chinafreebsd.cn/index.php?title=Ports_source"><span>换源方法</span></a> </td></tr><tr><td><span>ports源1</span> </td><td><a class="external free" href="http://ports1.chinafreebsd.cn">http://ports1.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>河北</td><td><span>联通</span> </td></tr><tr><td><span>ports源2</span> </td><td><a class="external free" href="http://ports2.chinafreebsd.cn">http://ports2.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>陕西</td><td><span>电信</span> </td></tr><tr><td><span>update源0</span> </td><td><a class="external free" href="http://update0.chinafreebsd.cn">http://update0.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>吉林</td><td><span>联通</span> </td><td rowspan="3"><a href="https://www.chinafreebsd.cn/index.php?title=Update_source"><span>换源方法</span></a> </td></tr><tr><td><span>update源1</span> </td><td><a class="external free" href="http://update1.chinafreebsd.cn">http://update1.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>河北</td><td><span>联通</span> </td></tr><tr><td><span>update源2</span> </td><td><a class="external free" href="http://update2.chinafreebsd.cn">http://update2.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>陕西</td><td><span>电信</span> </td></tr><tr><td><span>portsnap源0</span> </td><td><a class="external free" href="http://portsnap0.chinafreebsd.cn">http://portsnap0.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>吉林</td><td><span>联通</span> </td><td rowspan="3"><a href="https://www.chinafreebsd.cn/index.php?title=Portsnap_source"><span>换源方法</span></a> </td></tr><tr><td><span>portsnap源1</span> </td><td><a class="external free" href="http://portsnap1.chinafreebsd.cn">http://portsnap1.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>河北</td><td><span>联通</span> </td></tr><tr><td><span>portsnap源2</span> </td><td><a class="external free" href="http://portsnap2.chinafreebsd.cn">http://portsnap2.chinafreebsd.cn</a> </td><td><span>在线</span> </td><td>陕西</td><td><span>电信</span> </td></tr></tbody></table><h3 class="title"><span class="mw-headline" id=".C2.A0.E6.B3.A8.E6.84.8F.21.21.21.EF.BC.9A"><span class="mw-headline">&nbsp;注意!!!：</span></span></h3><p><b>请尽量使用 1 源，比如 pkg1.chinafreebsd.cn ，第一 1 源带宽稍大，第二 1 源为无缝升级源,也就是在源升级时候不影响客户端请求业务</b></p><h3 class="title"><span class="mw-headline" id=".C2.A0FreeBSD.E7.B3.BB.E7.BB.9F.E5.9B.9B.E7.B1.BB.E6.BA.90.E6.9C.8D.E5.8A.A1.E5.99.A8.E8.A7.A3.E9.87.8A.EF.BC.9A"><span class="mw-headline">&nbsp;FreeBSD系统四类源服务器解释：</span></span></h3><p>1、<b>pkg(8)</b> 源<br />为 pkg 工具提供二进制远程下载仓储目录，为使用 pkg 工具安装二进制软件包的必须条件，请参考 <a href="https://www.chinafreebsd.cn/index.php?title=Pkg_source">更换PKG源</a><br />2、<b>ports(8)</b> 源<br />为 ports 工具提供远程源码下载缓存目录，为使用 ports 工具编译安装软件包的必须条件，请参考 <a href="https://www.chinafreebsd.cn/index.php?title=Ports_source">更换PORTS源</a><br />3、<b>portsnap(8)</b>源<br />为 ports 框架当前快照，portsnap 为系统安装或者更新 ports 框架辅助工具，请参考 <a href="https://www.chinafreebsd.cn/index.php?title=Portsnap_source">更换PORTSNAP源</a><br />4、<b>freebsd-update(8)</b>源<br />为 FreeBSD 更新基系统、内核、源码树的快照源，更新操作系统时需要使用此源，请参考 <a href="https://www.chinafreebsd.cn/index.php?title=Update_source">更换UPDATE源</a></p><h3 class="title"><span class="mw-headline" id=".C2.A0.E6.BA.90.E7.BB.B4.E6.8A.A4.E4.BF.A1.E6.81.AF.E4.BB.A5.E5.8F.8A.E8.81.94.E7.B3.BB.E6.96.B9.E5.BC.8F.EF.BC.9A"><span class="mw-headline">&nbsp;源维护信息以及联系方式：</span></span></h3><p>源0维护者：Helo     &lt; helo@chinafreebsd.cn&gt;<br />源1维护者：J3ff     &lt;j3ff@chinafreebsd.cn&gt;</p><h3 class="title"><span class="mw-headline" id=".C2.A0.E6.AC.A2.E8.BF.8E.E5.8A.A0.E5.85.A5"><span class="mw-headline">&nbsp;欢迎加入</span></span></h3><p>如果您也是FreeBSD的爱好者，并且您有服务器和宽带资源，并且愿意为中国的FreeBSD使用者改善源的同步环境做出贡献请致信 &lt;bsd@chinafreebsd.cn &gt;索取修改版同步脚本。我们期待您的加入！！！</p>'),
('隐藏', '系统文章', 'chinafreebsd-memorabilia', '社区大事记', 3, '<table><tbody><tr><th>时间</th><th>主要贡献者</th><th>大事记</th></tr></tbody><tbody><tr><td>2016年12月</td><td><br/></td><td>社区成立</td></tr><tr><td>2016年12月</td><td>陆压</td><td>赞助vps社区上线</td></tr><tr><td>2016年12月</td><td>Helo</td><td>赞助chinafreebsd.cn域名一个</td></tr><tr><td>2016年12月</td><td>豆芽</td><td>赞助chinafreebsd.org域名一个</td></tr><tr><td>2017年1月</td><td>冰河四世纪</td><td>对克隆freebsd四类源技术论证成功</td></tr><tr><td>2017年1月始</td><td>全体社员</td><td>对社区建设表示支持，社区开始接受社会捐助</td></tr><tr><td>2017年1月</td><td>Helo</td><td>赞助服务器一台，宽带一年社区迁移至新服务器，pkg0源上线</td></tr><tr><td>2017年2月</td><td>J3ff</td><td>赞助服务器宽带若干，组织了pkg1源上线。</td></tr><tr><td>2017年3月</td><td>BigDragon</td><td>组建了社区邮件服务器</td></tr><tr><td>2017年2月</td><td>Helo&amp;J3ff</td><td>两地同时上线update0portsnap0ports0、update1portsnap1ports1源</td></tr><tr><td>2017年4月</td><td>冰河四世纪</td><td>重新优化了克隆脚本</td></tr><tr><td>2017年6月</td><td>Tiger</td><td>赞助宽带服务器若干，组织了pkg2ports2portsnap2update2源上线</td></tr><tr><td>2017年9月</td><td>HHJ</td><td>改版了社区网站，二次开发了文章编辑器</td></tr><tr><td>2017年10月</td><td>J3ff</td><td>组织上线了ftp1.chinafreebsd.cn在线资源服务器</td></tr></tbody></table>'),
('隐藏', '系统文章', 'chinafreebsd-about', '关于...', 3, '');


DROP TABLE IF EXISTS nb_category CASCADE;
DROP SEQUENCE IF EXISTS nb_category_seq;
CREATE SEQUENCE nb_category_seq;
CREATE TABLE IF NOT EXISTS nb_category (
	categoryid 	integer NOT NULL DEFAULT nextval('nb_category_seq'),
	name 		varchar(64) NOT NULL default '',
	remark		text,
	CONSTRAINT nb_category_pkey PRIMARY KEY (categoryid)
);
INSERT INTO nb_category (name) VALUES
('系统手册'),
('环境变量'),
('桌面应用'),
('服务应用'),
('网络应用'),
('内核模块'),
('存储安全'),
('脚本工具'),
('源码实例'),
('其他');




DROP TABLE IF EXISTS nb_column CASCADE;
DROP SEQUENCE IF EXISTS nb_column_seq;
CREATE SEQUENCE nb_column_seq;
CREATE TABLE IF NOT EXISTS nb_column (
	columnid 	integer NOT NULL DEFAULT nextval('nb_column_seq'),
	name 		varchar(64) NOT NULL default '',
	remark		text,
	CONSTRAINT nb_column_pkey PRIMARY KEY (columnid)
);
INSERT INTO nb_column (name) VALUES
('首页栏目'),
('系统文章'),
('其他');



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
	
	INSERT INTO nb_session(sessionid, data) VALUES ($1, $2);
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
	categories	text NOT NULL default '',
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

INSERT INTO nb_user(name, login, password, roleid, email)  VALUES
('root', 		   	'root', 		'202cb962ac59075b964b07152d234b70', 1, ''),
('administrator',  	'administrator','202cb962ac59075b964b07152d234b70', 2, ''),
('admin',  			'admin', 		'202cb962ac59075b964b07152d234b70', 2, ''),
('anonymous', 		'anon', 		'202cb962ac59075b964b07152d234b70', 6, ''),
('iceage',			'iceage',		'202cb962ac59075b964b07152d234b70',	2, ''),
('Huo', 			'huo',			'202cb962ac59075b964b07152d234b70', 2, ''),
('HHJ',			 	'hhj', 			'202cb962ac59075b964b07152d234b70', 5, ''),
('J3ff',			'J3ff',			'd29b77270909112f25a854cf3eb5928b', 2, 'j3ff@0x9.org');

UPDATE nb_user SET 
categories='系统手册,环境变量,桌面应用,服务应用,网络应用,内核模块,存储安全,脚本工具,源码实例,其他';




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
