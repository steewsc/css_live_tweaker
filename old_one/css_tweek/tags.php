<?php
	include_once ('tag.php');
	$tags =  new Tags;
	
	$tags->readFromCSS("style.css");
	
	$select = $tags->displayTagsAsSelect();
						
	//$select = str_replace('__',";",$select);
	echo '<select id="sel_tags" size="10" style="width: 100%;">';
	echo $select;
	echo '</select>';
	//print_r($tags->line);
	//$tags->setLine(55,123);
	//$select = $tags->displayTagsAsSelect();
	//echo $tags->getLine(55);
	//echo $tags->displayTagsAsUl();
	//$test = implode("\n",$tags->line);
	//echo $test;
?>