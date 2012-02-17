<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * XOOPS allow for custom error pages and logging
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: xoops404.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
class xoops404 {
    /**
     */
    var $_errorPage;
    var $_errorDetails;
    /**
     * Constructor
     *
     * @access protected
     */
    function __construct()
    {
        /**
         * Include languages
         */
        xoops_language( 'custom_errors' );
    }

    /**
     * xoops404::getInstance()
     *
     * @return
     */
    public function &getInstance()
    {
        static $instance;
        if ( !isset( $instance ) ) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * xoops404::getErrorNumber()
     *
     * @return
     */
    function getErrorDetails()
    {
        $strlen = strlen( $_SERVER['REQUEST_URI'] );
        if ( isset( $_SERVER['REQUEST_URI'] ) && substr( $_SERVER['REQUEST_URI'], ( $strlen-9 ), 9 ) == 'index.php' ) {
            $_SERVER['REQUEST_URI'] = substr_replace( $_SERVER['REQUEST_URI'], '', ( $strlen-9 ), 9 );
        }
        $url_start = ( isset( $_SERVER['HTTPS'] ) ) ? 'http://' : 'https://';
        /**
         * Populate
         */
        $this->_errorDetails['error_url'] = $url_start . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;
        $this->_errorDetails['error_date'] = time();
        $this->_errorDetails['error_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->_errorDetails['error_browser'] = $_SERVER['HTTP_USER_AGENT'];
        $this->_errorDetails['error_status'] = ( !empty( $_SERVER['REDIRECT_STATUS'] ) ) ? $_SERVER['REDIRECT_STATUS'] : '404';
        $this->_errorDetails['error_title'] = sprintf( constant( '_XO_HTTP_ERR_' . $this->_errorDetails['error_status'] ), "HTTP " . $this->_errorDetails['error_status'] . " -" );
        $this->_errorDetails['error_referer'] = ( !empty( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * xoops404::display()
     *
     * @return
     */
    function display()
    {
        $status = $this->_errorDetails['error_status'];
        $GLOBALS['xoopsTpl']->assign( 'error_contact', $GLOBALS['xoopsConfig']['adminmail'] );
        $GLOBALS['xoopsTpl']->assign( 'error_website', _ERR_SEARCH );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_title', sprintf( constant( "_XO_HTTP_ERR_$status" ), $status ) );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_http_footer', sprintf( constant( "_XO_HTTP_ERR_$status" ), "HTTP $status - " ) );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_desc', sprintf( constant( "_XO_HTTP_ERR_DESC_$status" ), '' ) );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_info', sprintf( _XO_HTTP_ERR_INFO, XOOPS_URL ) );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_contact', sprintf( _ERR_CONTACT, $GLOBALS['xoopsConfig']['adminmail'] ) );
        $GLOBALS['xoopsTpl']->assign( 'lang_error_search', _ERR_SEARCH );
        $GLOBALS['xoopsTpl']->assign( 'error_description', constant( "_XO_HTTP_ERR_DESC_$status" ) );

        if ( file_exists( $file = XOOPS_ROOT_PATH . "/language/{$GLOBALS['xoopsConfig']['language']}/custom_error_pages/page_{$status}.tpl" ) ) {
            $GLOBALS['xoopsTpl']->display( $file );
        } else if ( $GLOBALS['xoopsConfig']['language'] != 'english' ) {
            $GLOBALS['xoopsTpl']->display( XOOPS_ROOT_PATH . "/language/{$GLOBALS['xoopsConfig']['language']}/custom_error_pages/default_page.tpl" );
        } else {
            $GLOBALS['xoopsTpl']->display( XOOPS_ROOT_PATH . '/language/english/custom_error_pages/default_page.tpl' );
        }

        if ( !empty( $GLOBALS['xoopsConfig']['report_pagenotfound'] ) ) {
            self::sendEmail();
        }
    }

    /**
     * xoops404::xo_404Render()
     *
     * @return
     */
    public function render()
    {
        self::getErrorDetails();
        self::sendStatusCode( $this->_errorDetails['error_status'] );
        self::display();
    }

    /**
     * xoops404::sendStatusCode()
     *
     * @param mixed $statusCode
     * @return
     */
    function sendStatusCode( $statusCode = 404 )
    {
        header( 'Status: 404 Not Found' );
        header( 'HTTP/1.0 404 Not Found' );
    }

    /**
     * xoops404::sendEmail()
     *
     * @return
     */
    function sendEmail()
    {
        /**
         * Yet To Do::
         *
         * Why? Because I need to redo the Xoops Logger system to allow for
         * sending administration emails/file logging and sql logging first.
         */
    }
}

?>