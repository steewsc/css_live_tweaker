<?php
    include('functions.php')
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Live CSS Tweaker plugin</title>
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <link type="text/css" rel="stylesheet" href="css/test.css">
    <script type="text/javascript" src="js/jquery/jquery-1.6.4.min.js"></script>
    <script type="text/javascript">
        var objCss = { files: new Array()};
        
        $(document).ready(function(){
            $('[type$="text/css"]').each( function(){
                objCss.files.push( $(this).attr("href") );
            });
        });
    </script>
  </head>
  <body>
    <div id="pageWrapper">
      <header>
	<ul>
	  <li><a href="#">Home</a></li>
	  <li><a href="#">About</a></li>
	  <li><a href="#">Download</a></li>
	  <li><a href="#">Author</a></li>
	  <li><a href="#">Contact</a></li>
	</ul>
      </header>      
      <div id="mainContent">
	<div class="sidebar left">
	  <ul>
	    <li>Item 1</li>
	    <li>Item 2</li>
	    <li>Item 3</li>
	    <li>Item 4</li>
	  </ul>
	  <div class="banner">
	    Banner
	  </div>
	</div>
      </div>
      
      <footer>
      </footer>
    </div>
  </body>
</html>