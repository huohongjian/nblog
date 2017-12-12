

DROP TABLE IF EXISTS nb_thread CASCADE;
DROP SEQUENCE IF EXISTS nb_thread_seq;
CREATE SEQUENCE nb_thread_seq;
CREATE TABLE IF NOT EXISTS nb_thread (
	thid		integer NOT NULL default nextval('nb_thread_seq'),
	threadid	varchar(32) UNIQUE,
	category	varchar(32) NOT NULL default '',
	title		varchar(255) NOT NULL default '',
	content		text NOT NULL default '',
	userid		integer NOT NULL default 0,
	counter		integer NOT NULL default 0,
	replynum	integer NOT NULL default 0,
	addtime		timestamp(0) without time zone NOT NULL default now(),
	CONSTRAINT nb_thread_pkey PRIMARY KEY(thid)
);
CREATE INDEX nb_thread_threadid 	ON nb_thread (threadid);
CREATE INDEX nb_thread_category		ON nb_thread (category, thid);
CREATE INDEX nb_thread_userid		ON nb_thread (userid, thid);



DROP TABLE IF EXISTS nb_reply CASCADE;
DROP SEQUENCE IF EXISTS nb_reply_seq;
CREATE SEQUENCE nb_reply_seq;
CREATE TABLE IF NOT EXISTS nb_reply (
	replyid		integer NOT NULL default nextval('nb_reply_seq'),
	threadid	varchar(32) NOT NULL default '',
	content		text NOT NULL default '',
	userid		integer NOT NULL default 0,
	addtime		timestamp(0) without time zone NOT NULL default now(),
	CONSTRAINT nb_reply_pkey PRIMARY KEY(replyid)
);
CREATE INDEX nb_reply_threadid 	ON nb_reply (threadid, replyid);
CREATE INDEX nb_reply_userid	ON nb_reply (userid, replyid);


