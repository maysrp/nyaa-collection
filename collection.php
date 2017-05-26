<?php
	include_once 'phpQuery/phpQuery.php';
	include_once 'class/nyaa.php';
	include_once 'class/medoo.php';
	$nyaa=new nyaa();
	$nyaa->sukebei();//sukebei采集
	$nyaa->nyaa();//nyaa采集