<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS methods for user handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: about.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * xoopsAbout
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 */
final class xoopsAbout {
    /**
     * xoopsAbout::__Construct()
     */
    public function __Construct()
    {
        // empty
    }

    /**
     * xoopsAbout::getInstance()
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
     * xoopsAbout::about_TableStart()
     *
     * @param mixed $heading
     * @return
     */
    private function about_TableStart( $heading = null )
    {
        return '<table width="100%" cellpadding="2" cellspacing="1" class="outer">';
    }

    /**
     * xoopsAbout::about_header()
     *
     * @param mixed $heading
     * @return
     */
    private function about_header( $heading = null )
    {
        return '<thead><tr><th colspan="2">' . $heading . '</th></tr></thead>';
    }

    /**
     * xoopsAbout::about_footer()
     *
     * @return
     */
    private function about_footer()
    {
        return '<tfoot><tr><td colspan="2" class="footer">&nbsp;</td></tr></tfoot><tbody>';
    }

	/**
     * xoopsAbout::about_TableEnd()
     *
     * @param mixed $heading
     * @return
     */
    private function about_TableEnd( $heading = null )
    {
        return '</tbody></table><br />';
    }

    /**
     * xoopsAbout::about_content()
     *
     * @param string $heading
     * @param string $value
     * @param string $value2
     * @param string $type
     * @param mixed $colspan
     * @return
     */
    private function about_content( $heading = '', $value = '', $value2 = '', $type = 'normal', $colspan = null )
    {
        $heading 	= ( !empty( $heading ) ) 	? htmlspecialchars( $heading, ENT_QUOTES ) 	: '';
        $value 		= ( !empty( $value ) ) 		? htmlspecialchars( $value, ENT_QUOTES ) 	: '';
        switch ( strtolower( $type ) ) {
            /* Display normal table cell */
			case 'normal':
            default:
                $value = ( empty( $value ) ) ? '' : ( $value == 'changelog' ) ? self::changelog() : $GLOBALS['xoopsModule']->getInfo( $value );
                switch ( $colspan ) {
                    case 0:
                        return '<tr><td class="head" width="35%">' . $heading . '</td><td class="even">' . $value . '</td></tr>';
                        break;
                    case 1:
                        $ts = &MyTextSanitizer::getInstance();
                        return '<tr><td colspan="2" class="even">' . $ts->displayTarea( $value ) . '</td></tr>';
                        break;
                } // switch
                break;
	            /* Display url table cell */
			case 'url':
                $value 	= ( $value ) 	? $GLOBALS['xoopsModule']->getInfo( $value ) 	: '';
                $value2 = ( $value2 ) 	? $GLOBALS['xoopsModule']->getInfo( $value2 ) 	: '';
                return '<tr><td class="head" width="35%">' . $heading . '</td><td class="even"><a href="' . $value . '" target="_blank">' . $value2 . '</a></td></tr>';
                break;

			case 'email':
                $value = ( $value ) ? $GLOBALS['xoopsModule']->getInfo( $value ) : '';
                return '<tr><td class="head">' . $heading . '</td><td class="even"><a href="mailto:' . $value . '">' . $value . '</a></td></tr>';
                break;
        } // switch
    }

    /**
     * xoopsAbout::changelog()
     *
     * @return
     */
    private function changelog()
    {
        $file_name = XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . '/docs/changelog.txt2';
        if ( file_exists( $file_name ) && !is_dir( $file_name ) ) {
            $changelog = file_get_contents( $file_name );
        } else {
            $changelog = _CP_AM_AB_NOLOG;
        }
        return $changelog;
    }

    /**
     * xoopsAbout::display()
     *
     * @return
     */
    public function display()
    {
        $ret = '';
        $author_name = $GLOBALS['xoopsModule']->getInfo( 'author' ) ? $GLOBALS['xoopsModule']->getInfo( 'author' ) : '';

        $ret .= '<p><img src="' . XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . '/' . $GLOBALS['xoopsModule']->getInfo( 'image' ) . '" align="left" title="' . $GLOBALS['xoopsModule']->getInfo( 'name' ) . '" alt="' . $GLOBALS['xoopsModule']->getInfo( 'name' ) . '" hspace="5" vspace="0" /></a>
				<div style="margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 16px; line-height: 16px; font-weight: bold; display: block;">' . $GLOBALS['xoopsModule']->getInfo( 'name' ) . ' version ' . $GLOBALS['xoopsModule']->getInfo( 'version' ) . '</div>
				<div><strong>' . _CP_AM_AB_RELEASEDATE . '</strong> ' . $GLOBALS['xoopsModule']->getInfo( 'releasedate' ) . '</div>
				<div><strong>' . _CP_AM_AB_AUTHOR . '</strong> ' . $author_name . '</div>
				<div>' . $GLOBALS['xoopsModule']->getInfo( 'license' ) . '</div><br />
			</p>';

        $ret .= self::about_TableStart();
        $ret .= self::about_header( _CP_AM_AB_MAIN_INFO );
        $ret .= self::about_footer();
        $ret .= self::about_content( _CP_AM_AB_MODULE , 'name' );
        $ret .= self::about_content( _CP_AM_AB_DESCRIPTION , 'description' );
        $ret .= self::about_content( _CP_AM_AB_AUTHOR , 'author' );
        $ret .= self::about_content( _CP_AM_AB_VERSION , 'version' );
        $ret .= self::about_content( _CP_AM_AB_STATUS , 'status' );
        $ret .= self::about_TableEnd();
        /**
         */
        $ret .= self::about_TableStart();
        $ret .= self::about_header( _CP_AM_AB_DEV_INFO );
        $ret .= self::about_footer();
        $ret .= self::about_content( _CP_AM_AB_LEAD , 'lead' );
        $ret .= self::about_content( _CP_AM_AB_CONTRIBUTORS , 'contributors' );
        $ret .= self::about_content( _CP_AM_AB_WEBSITE_URL , 'website_url', 'website_name', 'url' );
        $ret .= self::about_content( _CP_AM_AB_EMAIL , 'email', '', 'email' );
        $ret .= self::about_content( _CP_AM_AB_CREDITS , 'credits' );
        $ret .= self::about_content( _CP_AM_AB_LICENSE , 'license' );
        $ret .= self::about_TableEnd();
        /**
         */
        $ret .= self::about_TableStart();
        $ret .= self::about_header( _CP_AM_AB_SUPPORT_INFO );
        $ret .= self::about_footer();
        $ret .= self::about_content( _CP_AM_AB_DEMO_SITE_URL , 'demo_site_url', 'demo_site_name', 'url' );
        $ret .= self::about_content( _CP_AM_AB_SUPPORT_SITE_URL , 'support_site_url', 'support_site_name', 'url' );
        $ret .= self::about_content( _CP_AM_AB_SUBMIT_BUG , 'submit_bug_url', 'submit_bug_name', 'url' );
        $ret .= self::about_content( _CP_AM_AB_SUBMIT_FEATURE , 'submit_feature_url', 'submit_feature_name', 'url' );
        $ret .= self::about_TableEnd();
        /**
         */
        $ret .= self::about_TableStart();
        $ret .= self::about_header( _CP_AM_AB_CHANGELOG );
        $ret .= self::about_footer();
        $ret .= self::about_content( '' , 'changelog', null, null, 1 );
        $ret .= self::about_TableEnd();
        /**
         */
        $ret .= self::about_TableStart();
        $ret .= self::about_header( _CP_AM_AB_DISCLAIMER );
        $ret .= self::about_footer();
        $ret .= self::about_content( '' , 'disclaimer', null, null, 1 );
        $ret .= self::about_TableEnd();
        echo $ret;
    }
}

?>