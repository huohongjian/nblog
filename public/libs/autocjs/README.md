# autoc.js

[![npm](https://img.shields.io/npm/v/autocjs.svg)]()
[![npm](https://img.shields.io/npm/dt/autocjs.svg)]()
[![Github file size](https://img.shields.io/github/size/yaohaixiao/autocjs/dist/autoc.min.js.svg)]()
[![npm](https://img.shields.io/npm/l/autocjs.svg)]()

Automatically create directory navigation for your article.

## Idea
[AnchorJS](http://bryanbraun.github.io/anchorjs/) 是 autocjs 的创作灵感。既然 AnchorJS 可创建标题的链接，为什么不直接给文章生成一个目录（Table of Contents）导航呢？ 于是就有了 autocjs。


## What is autocjs?
autocjs 是一个专门用来生成文章目录（Table of Contents）导航的工具。autocjs 会查找文章指定区域中的所有 h1~h6 的标签，并自动分析文章的层次结构，生成文章的目录导航（独立的侧边栏菜单，或者在文章的开始处生成文章目录）。


## Why autocjs?
AnchorJS 由于不是中国工程师开发的，所以对中文支持不好，无法给中文标题生成锚点。而 autocjs 即支持英文也支持中文。autocjs 在拥有 autocjs 的基础功能同时，还可以自动分析文章的层次结构，生成文章的目录导航。

## Features

* 支持 AMD 和 CMD 规范；
* 可以作为独立模块使用，也可以作为 jQuery 插件使用；
* 支持中文和英文（标题文字）；
* 界面简洁大方；
* 拥有 AnchorJS 的基础功能；
* 即支持生成独立文章目录导航菜单，又可以直接在文章中生成目录导航；
* 可直接在段落标题上显示段落层级索引值；
* 配置灵活，丰富，让你随心所欲掌控 autocjs；

## Use CDN in browser

``` html
<script type="text/javascript" src="https://unpkg.com/autocjs@1.3.0/dist/autoc.min.js"></script>
```

## Install

### Install with [npm](https://www.npmjs.com/):

```sh
$ npm install stringofit
```

### Install width [bower](https://bower.io/)

```sh
$ bower install stringofit
```

## Usage

### Use as a CommonJS/AMD/CMD Module

```js
var AutocJS = require('autocjs');

new AutocJS({
    article: '#article'
});
```

### Use as a jQuery plugin

```js
$('#article').autoc({
    title: 'AutocJS v0.2.0'
});
```

### Use as an independent Module

```js
new AutocJS({
    article: '#article',
    title: 'AutocJS v0.2.0'
});
```

## [API Documentation](http://yaohaixiao.github.io/autocjs/index.htm)

### Configuration Options

#### [article](http://yaohaixiao.github.io/autocjs/index.htm#option-article) 
Type: `String` `HTMLElement`  
Default: `''`

必选，用来指定页面中显示文章正文的 DOM 节点或者 ID 选择器。如果没有指定它，则程序将不会执行。

#### [selector](http://yaohaixiao.github.io/autocjs/index.htm#option-selector) 
Type: `String`  
Default: `'h1,h2,h3,h4,h5,h6'`

可选，用来指定 article 节点下，要生成导航的标题标签的选择器。

#### [title](http://yaohaixiao.github.io/autocjs/index.htm#option-title) 
Type: `String`  
Default: `'Table of Contents'`

可选，用来指定 AutocJS 自动创建的文章导读索引导航菜单的标题文字。

#### [isAnchorsOnly](http://yaohaixiao.github.io/autocjs/index.htm#option-isAnchorsOnly) 
Type: `Boolean`  
Default: `false`

可选，用来指定是否只创建标题链接。

#### [isAnimateScroll](http://yaohaixiao.github.io/autocjs/index.htm#option-isAnimateScroll) 
Type: `Boolean`  
Default: `true`

可选，用来指定在点击段落索引导航链接时，是使用动画滚动定位，还是使用默认的锚点链接行为。

#### [hasDirectoryInArticle](http://yaohaixiao.github.io/autocjs/index.htm#option-hasDirectoryInArticle) 
Type: `Boolean`  
Default: `false`

可选，用来指定是否在文章（开始位置）中创建目录导航。

#### [hasChapterCodeAtHeadings](http://yaohaixiao.github.io/autocjs/index.htm#option-hasChapterCodeAtHeadings) 
Type: `Boolean`  
Default: `false`

可选，用来指定是否在文章标题中显示该标题的段落索引编号。

#### [hasChapterCodeInDirectory](http://yaohaixiao.github.io/autocjs/index.htm#option-hasChapterCodeInDirectory) 
Type: `Boolean`  
Default: `true`

可选，用来指定是否在导航菜单中显示段落索引编号。

### Properties

#### [defaults](http://yaohaixiao.github.io/autocjs/index.htm#property-defaults) 
Type: `Objects`

静态属性，存储的是 AutocJS 对象默认配置选项。

#### [attributes](http://yaohaixiao.github.io/autocjs/index.htm#property-attribues)
Type: `Objects`

存储的是 AutocJS 对象当前使用中的配置选项。

#### [elements](http://yaohaixiao.github.io/autocjs/index.htm#property-elements) 
Type: `Objects`

存储的是 AutocJS 对象相关的 DOM 元素。

#### [data](http://yaohaixiao.github.io/autocjs/index.htm#property-data) 
Type: `Objects`

存储的是 AutocJS 根据标题 DOM 元素分析的数据。

### Methods

* [reload](http://yaohaixiao.github.io/autocjs/index.htm#method-reload)  - （根据新的配置信息）重启程序
* [set](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 设置 attributes 属性
* [get](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回某个 attributes 属性
* [dom](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回 elements 属性
* [article](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回页面文章正文的容器 DOM 元素
* [headings](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回 article 中 selector 匹配的所有标题 DOM 元素
* [chapters](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 设置或者返回 data.chapters 属性
* [anchors](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回 data.anchors 属性
* [list](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回 data.list 属性
* [index](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 返回 chapter 在 data.list 中对应段落层次位置索引值
* [render](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 绘制 UI 界面
* [show](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 展开侧边栏菜单
* [hide](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 收起侧边栏菜单
* [toggle](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 收起/展开侧边栏菜单
* [resize](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 根据当前窗口高度更新侧边栏菜单界面高度
* [scrollTo](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 将窗口的滚动条滚动到指定 top 值的位置
* [destroy](http://yaohaixiao.github.io/autocjs/index.htm#method-reload) - 移除所有绘制的 DOM 节点，并移除绑定的事件处理器


## Release History

[https://github.com/yaohaixiao/autoc.js/releases](https://github.com/yaohaixiao/autoc.js/releases).


## License

Code licensed under [MIT License](http://opensource.org/licenses/mit-license.html). 

API Documentation licensed under [CC BY 3.0](http://creativecommons.org/licenses/by/3.0/).