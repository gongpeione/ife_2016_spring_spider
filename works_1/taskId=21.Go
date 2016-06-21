<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"><meta name="format-detection" content="telephone=no"><meta name="apple-mobile-sweb-app-capable" content="yes"><meta name="keywords" content="前端, 前端开发, web前端, 前端开发工程师, CSS, JavaScript, HTML, Web标准"><meta name="description" content="百度前端技术学院"><link rel="shortcut icon" href="/asset/img/favicon.ico" type="image/x-icon"><link rel="icon" href="/asset/img/favicon.ico" type="image/x-icon"><link rel="bookmark" href="/asset/img/favicon.ico" type="image/x-icon"><title>百度前端技术学院</title>
<link href="//ife.baidu.com/asset/css/review/reviewDetail_ad83eb8a.css" rel="stylesheet">
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?d422e3cabaaaa7445e54622b97472bab";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>





</head><body class="result-op"><div id="viewport"><header ><nav class="navbar navbar-default container ife-header"><div class="row"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><div class="navbar-brand ife-logo"><a href="//ife.baidu.com"><img src="/asset/img/logo.png"></a></div></div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav"><li><a href="/task/all">任务</a></li><li><a href="/note/all">学习笔记</a></li><li><a href="/statistic/index">统计</a></li><li class="user-dropdown"><a href="https://openapi.baidu.com/oauth/2.0/authorize?client_id=89XtMi6980UVMb5iMiRAL27g&response_type=code&redirect_uri=http%3A%2F%2Fife.baidu.com%2Flog%2Fin&scope=&state=622f2359c84f83efe290c067edf47404&display=page">登录</a></li></ul></div></div></nav></header>
<main class="container review-detail-page"><div class="breadcrumb nav-title"><span class="active">任务二十一：基础JavaScript练习（四）（<a href="/task/detail?taskId=21">查看详情</a>）</span></div><div class="main"><div class="submit-group-info"><h4>团队：<a href="/group/profile?groupId=2529" target="_blank">Go</a></h4><p class="submit-basic"><span class="submit-time"><a href="/user/profile?userId=6772">孔阿栋</a>&nbsp;更新于<em>04-10 09:26</em></span><span class="review-num">已经有<em>1</em>人 review</span><span class="score">平均得分：<em>8</em></span></p><p>代码地址：<a href="https://github.com/BaiduGo3/task02/tree/gh-pages/task02_021" target="_blank">https://github.com/BaiduGo3/task02/tree/gh-pages/task02_021</a></p><p>demo：<a href="http://baidugo3.github.io/task02/index.html" target="_blank">http://baidugo3.github.io/task02/index.html</a></p></div><ul class="review-result-list container-fluid"><li class="row review-result" data-id="2241"><div class="review-user clearfix"><span class="pull-left"><span class="text-success">导师</span><span class="time-info">review&nbsp;<span class="time">04-16 00:32</span><span class="update-time">（04-16 00:32&nbsp;更新）</span></span></span><span class="pull-right score-info"><span class="score text-success">8</span><span class="text-muted">分</span></span></div><div class="review-comment"><span class="comment">整体功能实现上较好，但是需要指出一些不足的地方：<br />
1.IE8及其以下不支持字符串的trim()方法,所以需要进一步兼容处理，不然浏览器会报错；<br />
2.function getTarget()函数中event成了全局变量。另外，整个js中全局变量太多，思考一下怎样减少全局变量<br />
3.对于这种for(var i = 0; i &lt; eventName.childNodes.length; i++)，需要做个缓存，不要每次循环都计算长度，换成这种for(var i = 0, len = eventName.childNodes.length; i &lt; len ; i++)<br />
4.样式上独立出来单独作为一个css文件，而且最好不要直接对标签写css，可以通过加class或者id方式；<br />
5.代码的整体组织上可以继续优化，比如像addEvent这种公共方法可以抽离出来放到一个文件中，最好去多了解一下模块化开发，AMD,CMD这些</span></div></li></ul><div class="review-page-wrap" data-total="1" data-page-size="10" data-page-no="1"></div></div></main>
<footer><p><span class="contact">微信公众号：baidu_ife</span><span class="contact">微博：<a target="_blank" href="http://weibo.com/u/5568860641?topnav=1&wvr=6&topsug=1">Baidu前端技术学院</a></span></p><p class="friend-link"><span class="note">友情链接</span><a target="_blank" title="百度EFE前端团队" href="http://efe.baidu.com">百度EFE</a><a target="_blank" href="http://voice.baidu.com">百度语音搜索</a><a href="http://www.chuanke.com/" target="_blank">百度传课</a><a target="_blank" href="http://campus.baidu.com/">百度校园</a><a target="_blank" href="http://talent.baidu.com/external/baidu/campus.html">百度校园招聘</a></p><p><span class="copyright-text"><span>©2016&nbsp;Baidu&nbsp;</span><a href="//www.baidu.com/duty/" target="_blank">使用百度前必读</a>&nbsp;<a href="http://jianyi.baidu.com" target="_blank">意见反馈</a>&nbsp;<span>京ICP证030173号&nbsp;</span></span></p></footer></div><script src="http://s1.bdstatic.com/r/www/cache/ecom/esl/2-1-4/esl.js"></script><script src="https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/js/lib/jquery-1.10.2_d88366fd.js"></script><script src="../../dep/EpicEditor-v0.2.2/js/epiceditor.js"></script><script>require.config({'baseUrl': '//ife.baidu.com/asset','paths': { 'bootstrapvalidator': '../dep/bootstrapvalidator/dist/js/bootstrapValidator' },'waitSeconds': 5,'packages': [{'name': 'etpl','location': '../dep/etpl/3.1.0/asset','main': 'main'},{'name': 'bootstrap','location': '../dep/bootstrap-3.3.5/js'},{'name': 'select2','location': '../dep/select2/dist/js','main': 'select2'},{'name': 'jquery-bootpag','location': '../dep/jquery-bootpag/lib','main': 'jquery.bootpag'},{'name': 'bootstrap-slider','location': '../dep/bootstrap-slider/asset/js','main': 'bootstrap-slider'},{'name': 'saber-emitter','location': '../dep/saber-emitter/1.0.0/asset','main': 'emitter'},{'name': 'marked','location': '../dep/marked/lib','main': 'marked'}],'shim': {'bootstrap/button': ['jquery'],'bootstrap/modal': ['jquery'],'bootstrap/collapse': ['jquery'],'bootstrap/dropdown': ['jquery'],'bootstrap/tab': ['jquery'],'bootstrapvalidator': ['jquery'],'jquery-bootpag/jquery.bootpag': ['jquery']}});</script>
<script>
    require(['review/reviewDetail_e5394537'], function (app) {
        app.init({"login":0,"taskUrl":"\/task\/all","noteUrl":"\/note\/all","statisticUrl":"\/statistic\/index","loginUrl":"https:\/\/openapi.baidu.com\/oauth\/2.0\/authorize?client_id=89XtMi6980UVMb5iMiRAL27g&response_type=code&redirect_uri=http%3A%2F%2Fife.baidu.com%2Flog%2Fin&scope=&state=622f2359c84f83efe290c067edf47404&display=page"}, {
            taskId: 21,
            workId: 8240,
            groupId: 2529,
            allowAddReview: 0,
            allowUpdateReview: 0        });
    });
</script>
</body>
</html>
