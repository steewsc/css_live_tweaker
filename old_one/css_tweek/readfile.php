<?php
	include_once ('tag.php');
	
	$tags =  new Tags;
	$tags->initTags();
	
	$tags->displaySelectedTag('page');
	$tags->displayTags();
?>