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
 * USAGE
 *
 * Load Xoops Print Class a create a working instance of it
 * xoops_load( 'xoopsprint' );
 * $xoopsPrinter = xoopsPrint::getInstance();
 *
 * Add information via an associative;
 * $xoopsPrinter->setOptions( $array );
 *
 * Or via set methods
 * $xoopsPrinter->setTitle( 'My title' );
 * $xoopsPrinter->setSubTitle( 'My Subtitle' );
 *
 * For a full list of options available see the 'set' list within the class itself
 *
 * To render your print page use the following method
 * $xoopsPrinter->render();
 *
 * Todo: Unknown yet
 */

/**
 * XOOPS methods for user handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: xoopsprint.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

define ( 'DSEP', DIRECTORY_SEPARATOR );
if ( file_exists( XOOPS_ROOT_PATH . DSEP . 'language' . DSEP . $GLOBALS['xoopsConfig']['language'] . DSEP . 'print.php' ) ) {
    include_once XOOPS_ROOT_PATH . DSEP . 'language' . DSEP . $GLOBALS['xoopsConfig']['language'] . DSEP . 'print.php';
} else {
    include_once XOOPS_ROOT_PATH . DSEP . 'language' . DSEP . 'english' . DSEP . 'print.php';
}

/**
 * xoopsPrint
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2008
 * @version $Id$
 * @access public
 */
class xoopsPrint {
    /**
     */
    private $options = array();
    private $font = 'helvetica';
    private $fontsize = '12';
    /**
     * xoopsDoPrint::__construct()
     */
    public function __construct()
    {
    }

    /**
     * xoopsDoPrint::getInstance()
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
     * xoopsDoPrint::renderPrint()
     *
     * @return
     */
    public function render()
    {
        include_once XOOPS_ROOT_PATH . DSEP . 'class' . DSEP . 'template.php';

        $tpl = new XoopsTpl();
        $tpl->assign( 'xoops_pagetitle', self::getTitle() );
        $tpl->assign( 'xoops_sitename', htmlspecialchars( $GLOBALS['xoopsConfig']['sitename'], ENT_QUOTES ) );
        $tpl->assign( 'xoops_meta_author', self::getAuthor() );
        $tpl->assign( 'xoops_meta_keywords', htmlspecialchars( $GLOBALS['xoopsConfig']['meta_keywords'], ENT_QUOTES ) );
        $tpl->assign( 'xoops_meta_description', htmlspecialchars( $GLOBALS['xoopsConfig']['meta_description'], ENT_QUOTES ) );
        $tpl->assign( 'xoops_meta_copyright', "Copyright (c) " . date( 'Y' ) . " by " . htmlspecialchars( $GLOBALS['xoopsConfig']['sitename'], ENT_QUOTES ) );

        $tpl->assign( 'xo_printer_font', $this->font );
        $tpl->assign( 'xo_printer_size', $this->fontsize );
        $tpl->assign( 'xo_printer_slogan', self::getSlogan() );
        $tpl->assign( 'xo_printer_creator', self::getCreater() );
        $tpl->assign( 'xo_printer_renderdate', self::getRenderDate() );
        $tpl->assign( 'xo_printer_author', self::getAuthor() );
        $tpl->assign( 'xo_printer_pdate', self::getPDate() );
        $tpl->assign( 'xo_printer_udate', self::getUDate() );
        $tpl->assign( 'xo_printer_url', self::getUrl() );
        $tpl->assign( 'xo_printer_subtitle', self::getSubTitle() );
        $tpl->assign( 'xo_printer_content', self::getContent() );
        $tpl->display( 'db:system_printer.html' );
        exit();
    }

    /**
     * xoopsDoPrint::setOptions()
     *
     * @return
     */
    public function setOptions()
    {
        if ( func_num_args() == 1 ) {
            $args = func_get_arg();
            if ( !empty( $args ) ) {
                foreach( $args as $k => $v ) {
                    $this->options[$k] = $v;
                }
            }
        }
    }

    /**
     * xoopsDoPrint::setFont()
     *
     * @param string $value
     * @return
     */
    public function setFont( $value = '' )
    {
        $this->font = htmlspecialchars( $value, ENT_QUOTES );
    }

    /**
     * xoopsDoPrint::setFontSize()
     *
     * @param string $value
     * @return
     */
    public function setFontSize( $value = '' )
    {
        $this->fontsize = ( int )$value;
    }

    /**
     * xoopsDoPrint::setTitle()
     *
     * @param string $value
     * @return
     */
    public function setTitle( $value = '' )
    {
        $this->options['title'] = $value;
    }

    /**
     * xoopsDoPrint::setSubTitle()
     *
     * @param string $value
     * @return
     */
    public function setSubTitle( $value = '' )
    {
        $this->options['subtitle'] = $value;
    }

    /**
     * xoopsDoPrint::setCreater()
     *
     * @param string $value
     * @return
     */
    public function setCreater( $value = '' )
    {
        $this->options['creator'] = $value;
    }

    /**
     * xoopsDoPrint::setSlogan()
     *
     * @param string $value
     * @return
     */
    public function setSlogan( $value = '' )
    {
        $this->options['slogan'] = $value;
    }

    /**
     * xoopsDoPrint::setAuthor()
     *
     * @param string $value
     * @return
     */
    public function setAuthor( $value = '' )
    {
        $this->options['author'] = $value;
    }

    /**
     * xoopsDoPrint::setContent()
     *
     * @param string $value
     * @return
     */
    public function setContent( $value = '' )
    {
        $this->options['content'] = $value;
    }

    /**
     * xoopsDoPrint::setUDate()
     *
     * @param string $value
     * @return
     */
    public function setUDate( $value = '' )
    {
        $this->options['udate'] = $value;
    }

    /**
     * xoopsDoPrint::setPDate()
     *
     * @param string $value
     * @return
     */
    public function setPDate( $value = '' )
    {
        $this->options['pdate'] = $value;
    }

    /**
     * xoopsDoPrint::setUrul()
     *
     * @param string $value
     * @return
     */
    function setUrl( $value = '' )
    {
        $this->options['itemurl'] = $value;
    }

    /**
     * xoopsPrint::getTitle()
     *
     * @param string $value
     * @return
     */
    private function getTitle( $value = '' )
    {
        return !empty( $this->options['title'] ) ? htmlspecialchars( $this->options['title'] , ENT_QUOTES ) : '';
    }

    /**
     * xoopsPrint::getSubTitle()
     *
     * @param string $value
     * @return
     */
    public function getSubTitle( $value = '' )
    {
        return !empty( $this->options['subtitle'] ) ? htmlspecialchars( $this->options['subtitle'] , ENT_QUOTES ) : '';
    }

    /**
     * xoopsPrint::getAuthor()
     *
     * @return
     */
    private function getAuthor()
    {
        return !empty( $this->options['author'] ) ? htmlspecialchars( $this->options['author'] , ENT_QUOTES ) : htmlspecialchars( $GLOBALS['xoopsConfig']['sitename'], ENT_QUOTES );
    }

    /**
     * xoopsPrint::getContent()
     *
     * @return
     */
    private function getContent()
    {
        $value = !empty( $this->options['content'] ) ? $this->options['content'] : '';
        return self::printerStrip( $value );
    }

    /**
     * xoopsPrint::getCreater()
     *
     * @param string $value
     * @return
     */
    private function getCreater( $value = '' )
    {
        return !empty( $this->options['creator'] ) ? htmlspecialchars( $this->options['subtitle'] , ENT_QUOTES ) : htmlspecialchars( $GLOBALS['xoopsConfig']['sitename'], ENT_QUOTES );
    }

    /**
     * xoopsPrint::getSlogan()
     *
     * @return
     */
    public function getSlogan()
    {
        return !empty( $this->options['slogan'] ) ? htmlspecialchars( $this->options['slogan'] , ENT_QUOTES ) : htmlspecialchars( $GLOBALS['xoopsConfig']['slogan'], ENT_QUOTES );
    }

    /**
     * xoopsPrint::getPDate()
     *
     * @return
     */
    private function getPDate()
    {
        if ( !empty( $this->options['pdate'] ) ) {
            return formatTimestamp( htmlspecialchars( $this->options['pdate'] , ENT_QUOTES ) );
        }
        return '';
    }
    /**
     * xoopsPrint::getUDate()
     *
     * @return
     */
    private function getUDate()
    {
        if ( !empty( $this->options['udate'] ) ) {
            return formatTimestamp( htmlspecialchars( $this->options['udate'], ENT_QUOTES ) );
        }
        return '';
    }

    /**
     * xoopsPrint::getUrul()
     *
     * @return
     */
    private function getUrl()
    {
        return !empty( $this->options['itemurl'] ) ? htmlspecialchars( $this->options['itemurl'] , ENT_QUOTES ) : '';
    }

    /**
     * xoopsPrint::getRenderDate()
     *
     * @return
     */
    private function getRenderDate()
    {
        return formatTimestamp( time() );
    }

    /**
     * xoopsPrint::printerStrip()
     *
     * @param mixed $value
     * @return
     */
    private function printerStrip( $value )
    {
        $pattern = '/<img[^>]+>/is';
        $value = preg_replace( $pattern, '', $value );
        $value = str_replace( '[pagebreak]', '<br /><br />', $value );
        return $value;
    }
}

?>