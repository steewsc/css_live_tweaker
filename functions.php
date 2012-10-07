<?php
    if( isset($_GET['fl']) ){
        $cssFiles = $_GET['cssFiles'];
        getCssFilesContent($cssFiles);
    }
    function getCssFilesContent( $cssFiles ){
        $result;
        $tmpJSON = json_decode($cssFiles, true);
        if( count($tmpJSON) > 1 ){
            $result["counter"] = count($tmpJSON);
            for( $i = 0; $i < count( $tmpJSON ); $i++ ){
                $result["content_".$i] = file_get_contents( $tmpJSON[$i] );
            }
        } else {
            $result["counter"] = 1;
            $result["content_0"] = file_get_contents( reset($tmpJSON) );
        }
        echo json_encode( $result );
    }
?>
