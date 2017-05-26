<?php
	include_once 'phpQuery/phpQuery.php';
	include_once 'class/nyaa.php';
	include_once 'class/medoo.php';
	$nyaa=new nyaa();
	$name=isset($_GET['name'])?$_GET['name']:"";
	$pageIndex=isset($_GET['pageIndex'])?$_GET['pageIndex']:"0";
	$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:"0";
	$nyaa->s($name,$pageIndex,$pageSize);