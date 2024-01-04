<?php


namespace vendor\libs;


class Dir{


    public static function deleteDir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        self::deleteDir($dir."/".$object);
                    else unlink   ($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

//    public static function deleteDir($path) {
//        if ( file_exists( $path ) AND is_dir( $path ) ) {
//            $dir = opendir($path);
//            while ( false !== ( $element = readdir( $dir ) ) ) {
//                if ( $element != '.' AND $element != '..' )  {
//                    $tmp = $path . '/' . $element;
//                    @chmod( $tmp, 0777 );
//                    if ( is_dir( $tmp ) ) {
//                        self::deleteDir( $tmp );
//                    } else {
//                        @unlink( $tmp );
//                    }
//                }
//            }
//            closedir($dir);
//            if ( file_exists( $path ) ) {
//                @rmdir( $path );
//            }
//        }
//    }

    public static function addDir($path){
        $path = trim($path, '/');
        $exp = explode('/', $path);
        $pt = ROOT;
        foreach ($exp as $k => $v) {
            if (is_dir($pt . '/' . $v) !== true) {
                self::mkDirectory($pt, $v);
                chmod($pt . '/' . $v, 0777);
            }
            $pt .= '/' . $v;
        }
    }

    private static function mkDirectory($dir, $new){
        return chdir($dir) . "/" . mkdir($new, 0777);
    }
}