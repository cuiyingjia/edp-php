<?php
/**
 * Created by PhpStorm.
 * User: cuiyingjia
 * Date: 2016/12/25
 * Time: 下午10:01
 */

namespace Admin\Controller;
use Admin\Model\AuthGroupModel;


class ClearcacheController extends AdminController {

    public function index(){

        $cahce_dirs = RUNTIME_PATH;
        $this->rmdirr ( $cahce_dirs );

        @mkdir ( $cahce_dirs, 0777, true );
        $this->display ();
    }

    function rmdirr($dirname) {
        if (! file_exists ( $dirname )) {
            return false;
        }
        if (is_file ( $dirname ) || is_link ( $dirname )) {
            return unlink ( $dirname );
        }
        $dir = dir ( $dirname );
        if ($dir) {
            while ( false !== $entry = $dir->read () ) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $this->rmdirr ( $dirname . DIRECTORY_SEPARATOR . $entry );
            }
        }
        $dir->close ();
        return rmdir ( $dirname );
    }
}
