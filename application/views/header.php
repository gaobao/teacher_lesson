<!DOCTYPE html>
<html lang="zh-ch">
<head>
    <meta http-equiv="content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <title>教师首页</title>
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/bootstrap.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/man.css');?>" />
</head>
<body>
<header class="nav nav-default">
    <span class="nav-title">教师管理云端</span>
		 <span role="presentation" class="dropdown">
		    <a id="teacher-name" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="caret"><?php echo $userinfo['name'];?></span>
            </a>
		    <ul class="dropdown-menu">
                <li><a href="<?php echo base_url($userinfo['type']);?>">个人设置</a></li>
                <li><a href="<?php echo base_url('login/loginOut');?>">退出</a></li>
            </ul>
		  </span>
</header>