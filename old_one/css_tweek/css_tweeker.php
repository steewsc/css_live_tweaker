	<link type="text/css" rel="stylesheet" href="css_tweek/css_tweek.css" /> 
	
	<script type="text/javascript">
		var $jq = jQuery.noConflict();
		var selected="";
		var ttext;
		var arrCssProps=new Array();
		
		$jq(document).ready(function(){
			$jq("#tweek_tools").css('display','none');
			
			$jq("#toggle_tools").click(function() { 
				$jq("#tweek_tools").slideToggle('slow');
			});
			 
			$jq("#filter").click(function() { 
				var tag_name = $jq("#tag_name").val();
				var css_file = $jq("#css_file").val();
				
				$jq("#add_new_prop").hide();
				$jq("#modify").hide();
				$jq("#save").hide();
				$jq("#add_new").hide();
				
				if (tag_name.length == 0){
					tag_name = "__all";
				}
				if (css_file.length != 0){
					run_filter(tag_name, css_file);
				} else {
					alert("Please insert path to css file.");
				}
			 });
			$jq("#modify").click(function() { 
			
				var counter = 0;
				var props = "";
				$jq.each($jq('.tag_prop'), function() {
					if($jq(this).css('text-decoration') != 'line-through'){
						tp = $jq(this).text().trim();
						$jq.each($jq('.css_params'), function(index, value) {
							if(index == counter){
								var temps = $jq(this).val();
								temps = replaceAll(temps,"\\(../","(");
								
								$jq(selected).css(tp, temps);
								props = props + tp + ':' + $jq(this).val() + "\n";
							}
						});
					}
					counter++;
				});
			 });
			$jq("#save").click(function() { 
			
				var counter = 0;
				var props = "";
				var lineNum = 0;
				var setLineTo = "";
				var newLineAdd = "F";
				
				$jq.each($jq('.tag_prop'), function() {
					oldLineNum = lineNum;
					
					if($jq(this).css('text-decoration') != 'line-through'){
						if($jq(this).attr("alt") != "new"){
							lineNum = $jq(this).attr("alt");
							newLineAdd = "F";
						}else{
							lineNum++;
							newLineAdd = "T";
						}
						
						setLineTo = $jq(this).text().trim();
						$jq.each($jq('.css_params'), function(index, value) {
							if(index == counter){
								setLineTo = setLineTo + ":" + $jq(this).val().trim() + ";";
								saveToCSS($jq("#css_file").val(), lineNum, setLineTo, newLineAdd);
								//alert(setLineTo);
							}
						});
					}
					counter++;
				});
				alert("Saved Successfully");
			 });
			 
			$jq("#add_new_prop").live('click',function(){
				getAllCSS("css_tweek/all_css.txt");
				$jq("#add_new").show();
			}); 
			$jq(".tag_prop").live('click',function(){
				tp = $jq(this).text().trim();
				if($jq(this).css('text-decoration') != 'line-through'){
					$jq(this).css('text-decoration', 'line-through');
					arrCssProps[selected][tp] = $jq(selected).css(tp);
					if($jq(selected).css(tp).indexOf("px") > 0){
						$jq(selected).css(tp,'0');
					}else{
						$jq(selected).css(tp,'none');
					}
				}else{
					$jq(this).css('text-decoration', 'none');
					$jq(selected).css(tp,arrCssProps[selected][tp]);
				}
			});
			$jq("#add_new").live('click',function(){
				new_item = $jq("#all_tags option:selected").text().trim();
				propExist = false;
				$jq(".tag_prop").each(function (){
					if ($jq(this).text().trim() == new_item){
						propExist = true;
						$jq(this).css('color','red');
					}
				});
				if(propExist == true){
					alert('Selected property is already available for editing.');
				}else{
					newParamSet = "<div alt=\"new\" class=\"tag_prop\" style=\"color: rgb(255, 255, 255); cursor: pointer;\">" + 
									new_item + "</div><input class=\"css_params\" type=\"text\" value=\"0\" name=\"css_params\"><br>";
									
					$jq("#params").html($jq("#params").html() + newParamSet);
				}
			}); 
			
			$jq(".css_params").live('click',function(){
				$jq(".tag_prop").css('color','#FFF');				
			}); 
			
			$jq("#sel_tags").click(function() { 
				var tag_params = ttext;
				selected = $jq("#sel_tags option:selected").text().trim();
				
				arrCssProps = new Array();
				arrCssProps[selected] = new Array();
				tag_params = tag_params + ';';
				
				tag_params = replaceAll(tag_params, ';',';<br/>');
				tag_params = replaceAll(tag_params, ':','<input type="text" name="css_params" class="css_params" value="');
				tag_params = replaceAll(tag_params, ';','" />');
				tag_params = replaceAll(tag_params, '}','');
				
				$jq("#params").html(tag_params);
				
				$jq("#all_tags").remove();
				$jq("#add_new").remove();
				$jq("#add_new_prop").show();
				$jq("#modify").show();
				$jq("#save").show();
				$jq(".tag_prop").attr('title','Click to disable/enable.');
				
			});
			
			$jq('#sel_tags > option').live('mouseover',function(){
				ttext = $jq(this).attr('title');
				$jq(this).attr('title', '');
			});
			$jq('#sel_tags > option').live('mouseout',function(){
				$jq(this).attr('title', ttext);
			});
			
			function replaceAll(txt, replace, with_this) {
				return txt.replace(new RegExp(replace, 'g'),with_this);
			}
			function run_filter( filter, css_f ) {
			 
				$jq.ajax({
					url:"css_tweek/run_filter.php",
					type:"GET",
					data:"tag="+filter+"&css_file="+css_f,
					cache:true,
					async:false,
					success:function(html){
						$jq("#sel_tags").html(html);
					}
				});
			}
			function getAllCSS(file_path) {
			 
				$jq.ajax({
					url:"css_tweek/run_filter.php",
					type:"GET",
					data:"all_css="+file_path,
					cache:true,
					async:false,
					success:function(html){
						$jq("#new_params").html($jq("#new_params").html() + html);						
					}
				});
			}
			function saveToCSS(file_path, lineNum, value, newLine) {
			 
				$jq.ajax({
					url:"css_tweek/save_to_css.php",
					type:"GET",
					data:"file="+file_path+"&linenum="+lineNum+"&newval="+value+"&newLine="+newLine,
					cache:true,
					async:false,
					success:function(html){
						//alert(html);					
					}
				});
			}
		});
</script>
<div id="css_tweeker">
			<div id="toggle_holder">
				<div id="toggle_tools">CSS Tweeker</div>
			</div><br/>
			<div id="tweek_tools">
				<label for="css_file">Path to CSS file: </label>
				<input type="text" name="css_file" id="css_file" value="css/style.css"/><br/>
				<div id="tags_filter">
					<label for="tag_name">Full tag name or just a part of the name: </label>
					<input type="text" name="tag_name" id="tag_name"/>
					<div id="filter">FILTER</div>
				</div><br/><br/>
				<hr>
				<div id="filtered">
					<?php
						include_once ('tag.php');
						$tags =  new Tags;
						
						$tags->readFromCSS("css/style.css");
						$select = $tags->displayTagsAsSelect();
						
						//$select = str_replace('__',";",$select);
						echo '<select id="sel_tags" size="10" style="width: 100%;">';
						echo $select;
						echo '</select>';
					?>
				</div>
				<div id="params" style="width: 286px; float:left; padding-left: 15px; text-align: right;">
				</div>
				<div id="new_params" style="width: 286px; float:left; padding-left: 15px;">
					<div id="add_new_prop">Add New CSS Property</div><br/>
				</div>
				<div id="modify">Test</div>
				<div id="save">Save to CSS</div>
			</div>
		</div>