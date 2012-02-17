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
 * TextSanitizer Configurations
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: xoopsInputFilter.php 0 2008-04-20 14:49:37Z catzwolf $
 * @package class
 */
class xoopsInputFilter {
    var $input;
    var $inputPath;
    var $search;
    var $replace;
    var $_clean_xss = true;
    var $_clean_keys = array();
    var $_clean_data = array();

    /* never allowed, string replacement */
    var $never_allowed_str = array( 'document.cookie' => '[removed]',
        'document.write' => '[removed]',
        '.parentNode' => '[removed]',
        '.innerHTML' => '[removed]',
        'window.location' => '[removed]',
        '-moz-binding' => '[removed]',
        '<!--' => '&lt;!--',
        '-->' => '--&gt;',
        '<![CDATA[' => '&lt;![CDATA['
        );
    /* never allowed, regex replacement */
    var $never_allowed_regex = array( "javascript\s*:" => '[removed]',
        "expression\s*\(" => '[removed]', // CSS and IE
        "Redirect\s+302" => '[removed]'
        );
    /* filter xss */
    var $xss_clean = true;
    /**
     * Constructor
     *
     * @access protected
     */
    function __construct()
    {
        $this->inputPath = dirname( __FILE__ ) . '/filters/';
        $this->inputPath = str_replace( '/', XD_S , $this->inputPath );
    }

    /**
     * xoopsInputFilter::getInstance()
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
     * xoopsInputFilter::doFilter()
     *
     * @param mixed $input
     * @param string $filter
     * @return
     */
    public function loadFilter( $input, $filter )
    {
        $this->input = &$input;
        self::dofilter( $filter );
        self::exectuteFilter();
    }

    /**
     * xoopsInputFilter::loadFilters()
     *
     * @return
     */
    public function loadFilters( $input )
    {
        $this->input = &$input;
        if ( xoops_inlude( 'class/xoopslists' ) ) {
            $path = XOOPS_ROOT_PATH . '/class/inputfilters/filters';
            if ( $filters = XoopsLists::getFileListAsArray( $path ) ) {
                foreach( $filters as $filter ) {
                    if ( pathinfo( $filter, PATHINFO_EXTENSION ) == 'php' ) {
                        $file_info = explode ( '.', $filter );
                        if ( strtolower( $file_info[0] ) == 'filter' ) {
                            self::dofilter( $filter );
                        }
                    }
                }
            }
        }
    }

    /**
     * xoopsInputFilter::loadFilter()
     *
     * @param mixed $filter
     * @return
     */
    private function doFilter( $filter )
    {
        xoops_inlude( 'class/inputfilters/filters/filter_' . $filter );
        $class = 'Filter' . ucfirst( $filter );
        if ( ! class_exists( $class ) ) {
            trigger_error( 'Extension ' . $filter . ' not exist', E_USER_WARNING );
            return false;
        }
        $filterClass = new $class( $this ); ;
        if ( $ret = &call_user_func_array( array( $filterClass, 'render' ), array( &$this ) ) ) {
            array_merge( array( &$this ), array( &$ret ) );
        }
    }

    /**
     * xoopsInputFilter::exececutefilter()
     *
     * @return
     */
    function exectuteFilter()
    {
        preg_replace( $this->search, $this->replace, $this->input );
        return $this->input;
    }

    /**
     * xoopsInputFilter::sanitizeSuperGlobals()
     *
     * @return
     */
    function sanitizeSuperGlobals()
    {
        // Protected global items
        $protected = array( 'GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS',
            '_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS', '_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES',
            'HTTP_RAW_POST_DATA', 'xoopsDB', 'xoopsUser', 'xoopsUserId', 'xoopsUserGroups', 'xoopsUserIsAdmin', 'xoopsConfig', 'xoopsOption',
            'xoopsModule', 'xoopsModuleConfig', 'xoopsRequestUri' );
        // Unset globals for added xtra security
        foreach ( array( $_GET, $_POST, $_REQUEST, $_COOKIE, $_SERVER, $_FILES, $_ENV,
                ( isset( $_SESSION ) && is_array( $_SESSION ) ) ? $_SESSION : array() ) as $global ) {
            if ( !is_array( $global ) ) {
                if ( !in_array( $global, $protected ) ) {
                    unset( $GLOBALS[$global] );
                }
            } else {
                foreach ( $global as $key => $val ) {
                    if ( !in_array( $key, $protected ) ) {
                        unset( $GLOBALS[$key] );
                    }
                    if ( is_array( $val ) ) {
                        foreach( $val as $k => $v ) {
                            if ( ! in_array( $k, $protected ) ) {
                                unset( $GLOBALS[$k] );
                            }
                        }
                    }
                }
            }
        }

        if ( is_array( $_GET ) AND count( $_GET ) > 0 ) {
            foreach( $_GET as $key => $val ) {
                if ( $key = $this->_clean_keys( $key ) ) {
                    $_GET[$key] = $this->_clean_data( $val );
                } else {
                    unset( $_GET[$key] );
                }
            }
        }

        if ( is_array( $_POST ) AND count( $_POST ) > 0 ) {
            if ( $key = $this->_clean_keys( $key ) ) {
                $_POST[$key] = $this->_clean_data( $val );
            } else {
                unset( $_POST[$key] );
            }
        }

        if ( is_array( $_REQUEST ) AND count( $_REQUEST ) > 0 ) {
            if ( $key = $this->_clean_keys( $key ) ) {
                $_REQUEST[$key] = $this->_clean_data( $val );
            } else {
                unset( $_REQUEST[$key] );
            }
        }

        if ( is_array( $_COOKIE ) AND count( $_COOKIE ) > 0 ) {
            if ( $key = $this->_clean_keys( $key ) ) {
                $_COOKIE[$key] = $this->_clean_data( $val );
            } else {
                unset( $_COOKIE[$key] );
            }
        }
    }

    /**
     * xoopsInputFilter::_clean_keys()
     *
     * @param mixed $value
     * @return
     */
    function _clean_keys( $value )
    {
        if ( !preg_match( "/^[a-z0-9:_\/-]+$/i", $value ) ) {
            return false;
        }
        return $value;
    }

    /**
     * xoopsInputFilter::_clean_data()
     *
     * @param mixed $value
     * @return
     */
    function _clean_data( $value )
    {
        if ( is_array( $value ) ) {
            $_array = array();
            foreach ( $value as $key => $val ) {
                $_array[$this->_clean_keys( $key )] = $this->_clean_data( $val );
            }
            return $_array;
        }

        /**
         * Strip Magic quotes
         */
        if ( get_magic_quotes_gpc() ) {
            $value = stripslashes( $value );
        }

        /**
         * Do filter data if clean is true
         */
        if ( $this->_clean_xss === true ) {
            $value = self::_clean_xss( $value );
        }
        // Standardize newlines
        if ( strpos( $value, "\r" ) !== false ) {
            $value = str_replace( array( "\r\n", "\r" ), "\n", $value );
        }
        return $value;
    }

    /**
     * xoopsInputFilter::_clean_xss()
     *
     * @author ExpressionEngine Dev Team
     * @param mixed $value
     * @return
     */
    function _clean_xss( $value )
    {
        /**
         * Do clean if $value is an array
         */
        if ( is_array( $value ) ) {
            while ( list( $key ) = each( $value ) ) {
                $value[$key] = $this->_clean_xss( $value[$key] );
            }
            return $value;
        }
        /**
         */
        $value = preg_replace( '/\0+/', '', $value );
        $value = preg_replace( '/(\\\\0)+/', '', $value );
        $value = preg_replace( '|\&([a-z\_0-9]+)\=([a-z\_0-9]+)|i', $this->xss_hash() . "\\1=\\2", $value );
        $value = preg_replace( '#(&\#?[0-9a-z]+)[\x00-\x20]*;?#i', "\\1;", $value );
        $value = preg_replace( '#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $value );
        $value = str_replace( $this->xss_hash(), '&', $value );
        $value = rawurldecode( $value );
        $value = preg_replace_callback( "/[a-z]+=([\'\"]).*?\\1/si", array( $this, '_attribute_conversion' ), $value );
        $value = preg_replace_callback( "/<([\w]+)[^>]*>/si", array( $this, '_html_entity_decode_callback' ), $value );
        if ( strpos( $value, "\t" ) !== false ) {
            $value = str_replace( "\t", ' ', $value );
        }
        /**
         * Do not allowed $values
         */
        foreach ( $this->never_allowed_str as $key => $val ) {
            $value = str_replace( $key, $val, $value );
        }
        foreach ( $this->never_allowed_regex as $key => $val ) {
            $value = preg_replace( "#" . $key . "#i", $val, $value );
        }
        $value = str_replace( array( '<?php', '<?PHP', '<?', '?' . '>' ), array( '&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;' ), $value );
        /**
         * Compact exploded data
         */
        $words = array( 'javascript', 'expression', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window' );
        foreach ( $words as $word ) {
            $temp = '';
            for ( $i = 0; $i < strlen( $word ); $i++ ) {
                $temp .= substr( $word, $i, 1 ) . "\s*";
            }
            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $value = preg_replace( '#(' . substr( $temp, 0, -3 ) . ')(\W)#ise', "preg_replace('/\s+/s', '', '\\1').'\\2'", $value );
        }
        /*
		 * Remove disallowed Javascript in links or img tags
		 */
        do {
            $original = $value;
            if ( ( version_compare( PHP_VERSION, '5.0', '>=' ) === true && stripos( $value, '</a>' ) !== false ) OR
                    preg_match( "/<\/a>/i", $value ) ) {
                $value = preg_replace_callback( "#<a.*?</a>#si", array( $this, '_js_link_removal' ), $value );
            }
            if ( ( version_compare( PHP_VERSION, '5.0', '>=' ) === true && stripos( $value, '<img' ) !== false ) OR
                    preg_match( "/img/i", $value ) ) {
                $value = preg_replace_callback( "#<img.*?" . ">#si", array( $this, '_js_img_removal' ), $value );
            }
            if ( ( version_compare( PHP_VERSION, '5.0', '>=' ) === true && ( stripos( $value, 'script' ) !== false OR stripos( $value, 'xss' ) !== false ) ) OR
                    preg_match( "/(script|xss)/i", $value ) ) {
                $value = preg_replace( "#</*(script|xss).*?\>#si", "", $value );
            }
        } while ( $original != $value );
        unset( $original );

        /**
         * Remove Javascript onevent handlers
         */
        $event_handlers = array( 'onblur', 'onchange', 'onclick', 'onfocus', 'onload', 'onmouseover', 'onmouseup', 'onmousedown', 'onselect', 'onsubmit', 'onunload', 'onkeypress', 'onkeydown', 'onkeyup', 'onresize', 'xmlns' );
        $value = preg_replace( "#<([^>]+)(" . implode( '|', $event_handlers ) . ")([^>]*)>#iU", "&lt;\\1\\2\\3&gt;", $value );

        /*
		 * Sanitize HTML elements
		 */
        $naughty = 'alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss';
        $value = preg_replace_callback( '#<(/*\s*)(' . $naughty . ')([^><]*)([><]*)#is', array( $this, '_sanitize_html' ), $value );

        /*
		 * Sanitize scripting elements
		 *
		 */
        $value = preg_replace( '#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $value );

        /*
		 * Final clean up
		 */
        foreach ( $this->never_allowed_str as $key => $val ) {
            $value = str_replace( $key, $val, $value );
        }
        foreach ( $this->never_allowed_regex as $key => $val ) {
            $value = preg_replace( "#" . $key . "#i", $val, $value );
        }
        return $value;
    }

    /**
     * Random Hash for protecting URLs
     *
     * @access public
     * @return string
     */
    function xss_hash()
    {
        if ( $this->xss_hash == '' ) {
            if ( phpversion() >= 4.2 )
                mt_srand();
            else
                mt_srand( hexdec( substr( md5( microtime() ), -8 ) ) &0x7fffffff );

            $this->xss_hash = md5( time() + mt_rand( 0, 1999999999 ) );
        }
        return $this->xss_hash;
    }

    /**
     * xoopsInputFilter::_attribute_conversion()
     *
     * @param mixed $match
     * @return
     */
    function _attribute_conversion( $match )
    {
        return str_replace( '>', '&lt;', $match[0] );
    }

    /**
     * xoopsInputFilter::_html_entity_decode_callback()
     *
     * @param mixed $match
     * @return
     */
    function _html_entity_decode_callback( $match )
    {
        return $this->_html_entity_decode( $match[0], _CHARSET );
    }

    /**
     * xoopsInputFilter::_js_link_removal()
     *
     * @param mixed $match
     * @return
     */
    function _js_link_removal( $match )
    {
        return preg_replace( "#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $match[0] );
    }

    /**
     * xoopsInputFilter::_js_img_removal()
     *
     * @param mixed $match
     * @return
     */
    function _js_img_removal( $match )
    {
        return preg_replace( "#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $match[0] );
    }

    /**
     * xoopsInputFilter::_sanitize_html()
     *
     * @param mixed $matches
     * @return
     */
    function _sanitize_html( $matches )
    {
        $value = '&lt;' . $matches[1] . $matches[2] . $matches[3];
        if ( $matches[4] == '>' ) {
            $value .= '&gt;';
        } elseif ( $matches[4] == '<' ) {
            $value .= '&lt;';
        }
        return $value;
    }

    /**
     * xoopsInputFilter::bruteForceCheck()
     *
     * @return
     */
    function bruteForceCheck()
    {
    }
}

?>