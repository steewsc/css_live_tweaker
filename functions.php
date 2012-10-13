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
                $result["content_".$i] = "";
                $currFile = explode("\r\n", str_replace('"', '\'', str_replace(';;', ';', file_get_contents( reset( $tmpJSON ) ) ) ) );
                if( count( $currFile ) <= 1 ){
                    $currFile = explode("\n", str_replace('"', '\'', str_replace(';;', ';', file_get_contents( reset( $tmpJSON ) ) ) ) );
                }
                $commentFlag = false;
                for( $f = 0; $f < count( $currFile ); $f++ ){
                    if( $currFile[$f] != '' && $currFile[$f] != "\t" ){
                        if( strpos($currFile[$f], '/*') === false && strpos($currFile[$f], '*/') === false && $commentFlag == false ){
                            $result["content_".$i] .=  $currFile[$f];
                        }else if( strpos($currFile[$f], '*/') !== false ){    
                            $commentFlag = false;
                        }else{
                            $commentFlag = true;
                        }
                    }
                }
            }
        } else {
            $result["counter"] = 1;
            $result["content_0"] = "";
                $currFile = explode("\r\n", str_replace('"', '\'', str_replace(';;', ';', file_get_contents( reset( $tmpJSON ) ) ) ) );
                if( count( $currFile ) <= 1 ){
                    $currFile = explode("\n", str_replace('"', '\'', str_replace(';;', ';', file_get_contents( reset( $tmpJSON ) ) ) ) );
                }
                $commentFlag = false;
                for( $f = 0; $f < count( $currFile ); $f++ ){      
                    if( $currFile[$f] != '' && $currFile[$f] != "\t" ){
                        if( strpos($currFile[$f], '/*') === false && strpos($currFile[$f], '*/') === false && $commentFlag == false ){
                            $result["content_0"] .=  $currFile[$f];
                        }else if( strpos($currFile[$f], '*/') !== false ){    
                            $commentFlag = false;
                        }else{
                            $commentFlag = true;
                        }
                    }
                }
        }
        echo json_encode( $result );
    }
?>
