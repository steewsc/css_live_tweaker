<?php
    function getCssFileList( $cssFiles ){
        $result = "";
        $tmpJSON = json_decode($cssFiles, true);
        for( $i = 0; $i < count( $tmpJSON ); $i++ ){
            var_dump( file_get_contents( $tmpJSON[$i] ) );
        }
    }
?>
