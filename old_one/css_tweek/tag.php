<?php
/*
//	Name: CSS modifier
//	Description: This class can be used for modifying CSS files.
//	Usage: 
//	#FIRST STEP: INCLUDE CLASS FILE AND CREATE AN OBJECT
		include_once ('tag.php');
		$tags =  new Tags;
	
//	#SECOND STEP: Use it :-)
		//This function loads (by default) style.css file
		//and it creates an object/array of all tags in this css file.
		//NOTE: This function does not display tags, JUST loads tags info into object.
			$tags->initTags();
		
		//If you want to load tags into object yourself you can do it by calling:
			$tags->readFromCSS(path_to_your_css_file);
			
			//Exp:
				$tags->readFromCSS("style.css");
		//When loading tag infos is done you can display it by calling:
			//There are four ways to display tags:
				//Display tags in <ul></ul> as <li>
				echo $tags->displayTagAsUl();
				echo $tags->displaySelectedTagAsUl(Name_or_Part_of_tags_name);
				
				//	or
				
				//Display tags in <select></select> as <option>
				echo $tags->displayTagAsSelect();
				echo $tags->displaySelectedTagAsSelect(Name_or_Part_of_tags_name);
			
			//To display group of tags with similar name or just one tag by Name use:
				echo $tags->displaySelectedTagAsUl(Name_or_Part_of_tags_name);
					//or
				echo $tags->displaySelectedTagAsSelect(Name_or_Part_of_tags_name);
				
				Exp:
					echo $tags->displaySelectedTagAsSelect('footer');
					//result is:
					// <select>
					//		<option value="$tags_id">#footer</option>
					//		<option value="$tags_id">#footer a</option>					
					//		<option value="$tags_id">#footer .sitemap</option>					
					//</select>
*/
class Tags {
	var $tag = array();
	var $tagParams = array();
	var $id = "0";
	var $name = "tag_name";
	var $line = array();
	var $lines = array();
	var $tags_count = 0;
	
	public function initTags(){
		$this->readFromCSS("css/style.css");	
	}
	
	public function setID($newId){
		$this->id=$newId;
	}
	
	public function getID(){
		return $this->id;
	}
	
	public function setName($newName){
		$this->name=$newName;
	}
	public function getName(){
		return $this->name;
	}
	public function setProps($cline, $tag_order){
		if ($this->inStr($cline, '{') === true){
			$where = strpos($cline, '{');
		} else {
			$where = -1;
		}
		$this->tagParams[$tag_order] = substr($cline, $where+1, strlen($cline)-$where-3);
	}
	public function getProps($tag_order){
		return $this->tagParams[$tag_order];
	}
	
	public function setLine($newLine, $witch_tag){
		$this->line[$witch_tag] = $newLine;
		//$this->line[] = $witch_tag . ':' . $newLine;
	}
	public function getLine($witch_tag){
		return $this->line[$witch_tag];
	}
	
	public function getNew(){
        return new Tags();
	}// END OF GETNEW TAG
	
	public function readFromCSS($which_css){
		$line_count = 0;
		$this->tags_count = 0;
		
		$file = fopen($which_css, "r") or exit("Unable to open file!");
		
		while(!feof($file)){
			$curline = fgets($file);
			$line_count++;
			//echo $line_count . '<br/>';
			$pos = $this->inStr($curline, '{');
			if($pos === true){
				$tag_name = substr($curline,0,strpos($curline,'{'));
				$this->tags_count++;
				
				$this->tag[$this->tags_count] = Tags::getNew();
					$this->tag[$this->tags_count]->setID($this->tags_count);
					$this->tag[$this->tags_count]->setName($tag_name);
					$this->tag[$this->tags_count]->setLine($line_count, $this->tags_count);
				//echo $line_count;
				$onelinecss = $this->inStr($curline, '}');
				//Next line checks if css tag settings are in one line
				if($onelinecss === true){
				
					$this->setProps($curline, $this->tags_count);
				} else { 
					$properties = '';
					$curline = fgets($file);
					$line_count++;
					//echo $line_count . '<br/>';
					while($this->inStr($curline, '}') === false){
						$arrCurLine = explode(":", $curline);
						$arrCurLine[0] = '<div class=\'tag_prop\' alt=\'' . $line_count . '\'>' . $arrCurLine[0] . '</div>';
						$curline = implode(':', $arrCurLine);
						
						$properties .= $curline;
						$curline = fgets($file);
						$line_count++;
						//echo $line_count . '<br/>';
					}
					$this->setProps($properties, $this->tags_count);
				}
			}
		}
		fclose($file); 
	}// END OF READFROMCSS
	public function readAllCSSProps($witch_file){
		$result = '<select id="all_tags" size="1" style="width: 65%; position: relative; left: 0; top: 0; margin-bottom: 10px;">';
		$lines = file( $witch_file , true );
		$count = 1;
		foreach($lines as $value){	
			$new_val = $value;
			$result .= '<option class="all_css" value="' . $count . '">' . $new_val . '</option>';
			$count++;
		}
		$result .= '</select>';
		//$lines[$line_i_am_looking_for] = 'XX-igfggfdgdfgeno'."\n";
		return $result; 
	}// END OF READALLCSSPROPS
	public function displayTagsAsUl(){
		$count=1;
		$displayAs = 'li';
		$result = ''; 
		while($count <= $this->tags_count){
			$result .= "<ul>";
				$result .= '<' . $displayAs . '>Tag #' . $this->tag[$count]->getID() . '</' . $displayAs . '>';
				$result .= '<' . $displayAs . '>Tag Name: ' . $this->tag[$count]->getName() . '</' . $displayAs . '>';
				$result .= '<' . $displayAs . '>Line number in css file: ' . $this->tag[$count]->getLine($count) . '</' . $displayAs . '>';  
			$result .= "</ul>";
			$count++;
		}
		return $result;
	}// END OF DISPLAYTAGSASUL
	
	public function displaySelectedTagAsUl($selected_tag){
		$count=1;
		$displayAs = 'li';
		$result='';
		while($count <= $this->tags_count){
			$result .= "<ul>";
				if($this->inStr($this->tag[$count]->getName(),$selected_tag) === true){
					$result .= '<' . $displayAs . '>Tag #' . $this->tag[$count]->getID() . '</' . $displayAs . '>';
					$result .= '<' . $displayAs . '>Tag Name: ' . $this->tag[$count]->getName() . '</' . $displayAs . '>';
					$result .= '<' . $displayAs . '>Line number in css file: ' . $this->tag[$count]->getLine($count) . '</' . $displayAs . '>';  
				}
			$result .= "</ul>";
			$count++;
		}
		return $result;
	}// END OF DISPLAYSELECTEDTAGASUL
	
	public function displayTagsAsSelect(){
		$count=1;
		$displayAs = 'option';
		$result = "";
		//$result = '<select id="sel_tags" size="10" style="width: 100%;">';
			while($count <= $this->tags_count){
					$param = trim($this->getProps($count));
					//$param = str_replace(';',"__",$param);
					
					$result .= '<' . $displayAs . ' class="opts" value="' . $this->tag[$count]->getLine($count) . '" title="' . $param . '" alt="' . $this->tag[$count]->getLine($count) . '">' . $this->tag[$count]->getName() . '</' . $displayAs . '>';
				$count++;
			}
		//$result .= "</select>";
		return $result;
	}// END OF DISPLAYTAGSASSELECT
	
	public function displaySelectedTagAsSelect($selected_tag){
		$count=1;
		$displayAs = 'option';
		
		$result = "";
		//$result = '<select id="sel_tags" size="10" style="width: 100%;">';
		while($count <= $this->tags_count){
				if($this->inStr($this->tag[$count]->getName(),$selected_tag) === true){
					$param = trim($this->getProps($count));
					//$param = str_replace(';',"__",$param);
					
					$result .= '<' . $displayAs . ' class="opts" value="' . $this->tag[$count]->getID() . '" title="' . $param . '" alt="' . $this->tag[$count]->getLine($count) . '">' . $this->tag[$count]->getName() . '</' . $displayAs . '>';
				}
			$count++;
		}
		//$result .= '</select>';
		return $result;
	}// END OF DISPLAYSELECTEDTAGASSELECT
	
	private function inStr($haystack, $needle){
		return strpos($haystack, $needle) !== false;
	}// END OF INSTR
}
?>





