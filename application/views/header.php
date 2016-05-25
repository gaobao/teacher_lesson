<!DOCTYPE html>
<html lang="zh-ch">
<head>
    <meta http-equiv="content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <title>云备课系统</title>
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/bootstrap.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/man.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/fileinput.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/css/bootstrap-datetimepicker.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url('/static/bootstrap-fileinput/css/fileinput.min.css');?>" />
</head>
<script type="text/javascript" src="<?PHP echo base_url('/static/js/jquery-2.2.1.min.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/bootstrap-fileinput//js/fileinput.min.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/bootstrap-fileinput/js/fileinput_locale_zh.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/js/bootstrap-datetimepicker.min.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/js/locales/bootstrap-datetimepicker.zh-CN.js');?>"></script>
<script type="text/javascript" src="<?PHP echo base_url('/static/js/main.js');?>"></script>
<body>
<header class="nav nav-default">
    <span class="nav-title"><a href="<?php echo base_url();?>">云备课系统</a></span>
<span role="presentation" class="dropdown">
		    <a id="teacher-name" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span> <?php echo $userinfo['name'];?>
            </a>
		    <ul class="dropdown-menu">
                <li><a href="<?php echo base_url($userinfo['type']);?>" style="color: #0088cc">个人设置</a></li>
                <li><a href="<?php echo base_url('login/loginOut');?> " style="color: #0088cc">退出</a></li>
            </ul>
		  </span>
</header>