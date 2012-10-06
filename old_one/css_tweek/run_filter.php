<?php
	include_once ('tag.php');
	$output = '';
	$tags =  new Tags;
	
	if(isset($_GET['tag'])){
		$filter = $_GET['tag'];
		$cssfile = $_GET['css_file'];
		
		$tags->readFromCSS($cssfile);	
		//echo '<select id="sel_tags" size="10" style="width: 100%;">';
			if ($filter != '__all'){
				$output .= $tags->displaySelectedTagAsSelect($filter);
			} else {
				$output .= $tags->displayTagsAsSelect();
			}
		echo $output;
	}else{
		$all_css_file=$_GET['all_css'];
		//echo 'radi dovde ' . $all_css_file;
		$result = $tags->readAllCSSProps($all_css_file);
		echo $result;
		echo '<div id="add_new">Set</div>';
	}
	//echo '</select>';
?>