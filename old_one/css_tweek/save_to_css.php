<?php
	function isWin(){
		$result = true;
		if (substr(PHP_OS,0,3) != 'WIN') { 
			$result = false;
		}
		return $result;
	}
	
	$filename = $_GET['file'];
	if(!file_exists($filename)){
		$filename = '../' . $filename;
	}
	$lineNum = $_GET['linenum'];
	$value = $_GET['newval'];
	$isNewLine = $_GET['newLine'];
	
	$lineNum = $lineNum-1;
	
	$lines = file( $filename , true );
	
	if (isWin() == true){
		$rowseparator = "\r\n";
	} else {
		$rowseparator = "\n";
	}
	if($isNewLine == "F"){
		$lines[$lineNum] = "\t" . $value . $rowseparator;
	}else{
		$lines[$lineNum] = "\t" . $value . $rowseparator . '}' . $rowseparator;
	}
	file_put_contents( $filename , $lines );
	return "Saved succesfully. " .  $rowseparator . 'isNewLine: ' . $isNewLine . '.';
	
?>