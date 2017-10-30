
INSERT INTO nb_article (status, category, articleid, title, userid, content) VALUES (
'隐藏', 'system-home', 'system-home-nav', '网站首页导航条', 3,
'<ul class="b-m-0 c-m-10 d-m-30 e-m-50 f-m-80 g-m-120">
	<li><a href="/">首页</a></li>
	<li><a href="/search">文档</a></li>
	<li><a href="/article/chinafreebsd-resouce">本站源</a></li>
	<li><a href="/article/chinafreebsd-memorabilia">大事记</a></li>
	<li><a href="/article/chinafreebsd-about">关于</a></li>
	<li><a href="/donation">捐赠</a></li>
</ul>');




INSERT INTO nb_article (status, category, articleid, title, userid, content) VALUES (
'隐藏', 'system-home', 'system-home-left', '网站首页左侧内容', 3,
'<section>
	<h3>系统下载</h3>
	FreeBSD 11.1-RELEASE <br/>
	<a href="https://www.freebsd.org/where.html" target="_blank">[官方下载]</a><br/>
	<a href="http://ftp1.chinafreebsd.cn/pub/FreeBSD/releases/ISO-IMAGES/11.1/" target="_blank">[本站下载] - 更快更安全</a>
</section>

<section>
	<h3>官方资料</h3>
	<ul class="hor">
		<li><a href="https://www.freebsd.org/zh_CN/" target="_blank">中文网站</a></li>
		<li><a href="https://www.freebsd.org/doc/zh_CN/books/handbook/" target="_blank">中文手册</a></li>
	</ul>
</section>

<section>
	<h3>意见建议</h3>
	<form id="suggestForm">
		<textarea name="content" rows="4" placeholder="请把您的意见留给我们，我们会继续努力，不断完善和提高。"></textarea>
		<input type="button" value="查看" onclick="window.location.href=''/suggest''"/>
		<input type="button" value="提交" onclick="suggest()" />
	</form>
</section>'
);





INSERT INTO nb_article (status, category, articleid, title, userid, content) VALUES (
'隐藏', 'system-home', 'system-home-rt', '网站首页右上部内容', 3,
'<section class="a-1-1 d-1-2 f-1-3">
<img src="/thumbs/1.jpg" class="home-thumb"/><span style="color:#990000; font-weight:900;">FreeBSD</span> 是一个适用于现代服务器、桌面与嵌入式平台的先进操作系统。由一个庞大的开发人员团队持续开发超过三十年。FreeBSD先进的网络、安全性和存储方面的特色使得它成为许多大型网站以及最普遍的嵌入式网络与存储设备的平台选择。
</section>

<section class="a-1-1 d-1-2 f-1-3">
<span style="color:#990000; font-weight:900;">China FreeBSD招兵买马</span> 由于 FreeBSD 中文手册更新缓慢，与现行发行版严重脱节，河狸正在负责中文文档计划，如有英文水平不错且对系统比较熟悉的FreeBSD 爱好者愿意参与项目请直接加 FreeBSD 中文文档计划 QQ群：455612886。另外如果您有新的想法或者好文请加入China FreeBSD 社区 QQ群：317764984 验证码：ufs
</section>

<section class="a-1-1 d-1-2 f-1-3">
<span style="color:#990000;font-weight:900;">China FreeBSD 中国社区改版啦！</span> 我们重新改版了社区主站，站点程序和内容由iceage和HHJ共同设计开发。新版的文章显示遵循了 FreeBSD 手册的风格，文章格式及样式简洁统一，更符合社区成立时的初衷理念（收集与共享 FreeBSD 知识）。网站主体风格更加贴近 FreeBSD 官方样式，这是我们有意为之，目的不是替代，而是传承 FreeBSD 的文化，使爱好者更加爱好，共同陶醉在 FreeBSD 的氛围里。
</section>

<section class="a-1-1 d-1-2 f-1-3">
<span style="color:#990000;font-weight:900;">社区FTP服务器建成</span> 通过长时间的努力和收集，ChinaFreeBSD社区FTP服务器建成并上线，服务器地址为<a href="http://ftp1.chinafreebsd.cn" target="_blank"><span style="color:#337FE5;">ftp1.chinafreebsd.cn</span></a> ，由J3ff提供维护。目前服务器包含了FreeBSD、NetBSD和OpenBSD的在线资源，其中FreeBSD为每两个小时更新一次，其他BSD系统为每天更新一次，停止更新的系统版本请访问*BSD-Archive。目前支持 http、FTP 协议访问。
</section>'
);