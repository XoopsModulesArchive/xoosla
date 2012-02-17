<?php
if ( !isset( $_SERVER['REDIRECT_STATUS'] ) && empty( $_SERVER['REDIRECT_STATUS'] ) ) {
    die( "Fuck OFF" );
}

include '../mainfile.php';
if ( isset( $_GET['error'] ) && !empty( $_GET['error'] ) ) {
    xoops_load( 'xoops404' );
    $PageNotFound = xoops404::getInstance();
    include XOOPS_ROOT_PATH . '/header.php';
    $PageNotFound->render();
    include XOOPS_ROOT_PATH . '/footer.php';
}

?>