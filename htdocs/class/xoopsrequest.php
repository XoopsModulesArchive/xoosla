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
 * @version $Id: xoopsrequest.php 0 2008-04-20 14:49:37Z catzwolf $
 * @package class
 */

/**
 * List of Input Scopes (Type)
 *
 * INPUT_POST
 * INPUT_GET
 * INPUT_COOKIE
 * INPUT_ENV
 * INPUT_SERVER
 * INPUT_SESSION (Will supply alias)
 * INPUT_REQUEST (Will Supply Alias)
 * INPUT_FILES (Will supply alias)
 */
define( 'INPUT_FILES', 999999 );
/**
 * List of Input Scopes
 *
 * Please see http://uk3.php.net/manual/en/filter.constants.php
 * for a define list of constants that can be used by the filter system
 */

/**
 * XoopsRequest
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2008
 * @version $Id$
 * @access public
 */
class XoopsRequest {
    var $_debug = false;
    /**
     * XoopsRequest::__construct()
     */
    function __construct()
    {
    }

    /**
     * XoopsRequest::getVar()
     *
     * @param mixed $type
     * @param mixed $variable_name
     * @param mixed $filter
     * @param mixed $default
     * @param array $flags
     * @return
     */
    private function getVar( $type, $variable_name, $filter, $default = null, $flags = '' )
    {
        /**
         * switch
         */
        switch ( ( int )$type ) {
            case INPUT_POST:
            case INPUT_GET:
            case INPUT_COOKIE:
            case INPUT_ENV:
            case INPUT_SERVER:
                $value = filter_input( $type, $variable_name, $filter, $flags );
                break;
            case INPUT_REQUEST:
                $value = filter_input( INPUT_GET | INPUT_POST, $variable_name, $filter, $flags );
                break;
            case INPUT_SESSION:
            case INPUT_FILES:
                return false;
                break;
        } // switch
        /**
         */
        if ( is_null( $value ) ) {
            trigger_error( "Missing arguement" );
            return false;
        } else if ( $value === false ) {
            trigger_error( "The argument must be a valid" );
            return false;
        } else {
            return ( !is_null( $default ) ) ? $default : $value;
        }
    }

    /**
     * XoopsRequest::getInt()
     *
     * @param mixed $type
     * @param mixed $key
     * @param mixed $default
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    function getInt( $variable_name, $default = 0, $type = INPUT_POST, $min = null, $max = null )
    {
        $flag = array();
        if ( !is_null( $min ) || is_null( $max ) ) {
            $flag = array( 'min_range' => ( int )$min, 'max_range' => ( int )$max );
        }
        return self::getVar( $type, $variable_name, FILTER_VALIDATE_INT, $default );
    }

    /**
     * XoopsRequest::getBool()
     *
     * @param string $variable_name
     * @param integer $default
     * @param mixed $scope
     * @param string $type
     * @return
     */
    function getBool( $variable_name, $default = false, $type = INPUT_POST )
    {
        return self::getVar( $type, $variable_name, FILTER_VALIDATE_BOOLEAN, $default, null );
    }

    /**
     * XoopsRequest::getFloat()
     *
     * @param string $variable_name
     * @param integer $default
     * @param mixed $scope
     * @return
     */
    function getFloat( $variable_name, $default = 0.00, $type = INPUT_POST )
    {
        return self::getVar( $type, $variable_name, FILTER_VALIDATE_FLOAT, $default );
    }

    /**
     * XoopsRequest::getString()
     *
     * @param string $variable_name
     * @param integer $default
     * @param mixed $scope
     * @param mixed $length
     * @return
     */
    function getTextbox( $variable_name, $default = '', $type = INPUT_POST )
    {
        $value = self::getVar( $type, $variable_name, FILTER_SANITIZE_STRING, $default );
        if ( $value = ! false && !get_magic_quotes_gpc() ) {
            $value = self::validateType( $type, FILTER_SANITIZE_MAGIC_QUOTES );
        }
        return $value;
    }

    /**
     * XoopsRequest::getTextArea()
     *
     * flags:
     * FILTER_FLAG_STRIP_LOW - Strip characters with ASCII value below 32
     * FILTER_FLAG_STRIP_HIGH - Strip characters with ASCII value above 32
     * FILTER_FLAG_ENCODE_HIGH - Encode characters with ASCII value above 32
     *
     * @return
     */
    function getTextArea( $variable_name, $default = '', $type = INPUT_POST, $flags = array() )
    {
        $_flags = self::flagCheck( $flags );
        $value = self::getVar( $type, $variable_name, FILTER_SANITIZE_SPECIAL_CHARS, $default, $_flags );
        /**
         * Xoops filter to remove malicous code
         */
        $ts = MyTextSanitizer::getInstance();
        $value = $ts->textFilter( $value );
        if ( !get_magic_quotes_gpc() ) {
            $value = self::validateType( $type, FILTER_SANITIZE_MAGIC_QUOTES );
        }
        return $value;
    }

    /**
     * XoopsRequest::getEmail()
     *
     * @param mixed $variable_name
     * @param string $default
     * @param mixed $type
     * @return
     */
    function getEmail( $variable_name, $default = '', $type = INPUT_POST )
    {
        return self::getVar( $type, $variable_name, FILTER_VALIDATE_EMAIL, $default );
    }

    /**
     * XoopsRequest::getUrl()
     *
     * @param mixed $variable_name
     * @param string $default
     * @param mixed $type
     * @return
     */
    function getUrl( $variable_name, $default = 'http://', $type = INPUT_POST, $flags = array( FILTER_FLAG_SCHEME_REQUIRED ) )
    {
        $_flags = self::flagCheck( $flags );
        return self::getVar( $type, $variable_name, FILTER_SANITIZE_URL, $default, $_flags );
    }

    /**
     * XoopsRequest::getIP()
     *
     * @param mixed $variable_name
     * @param string $default
     * @param mixed $type
     * @param array $flags
     * @return
     */
    function getIP( $variable_name, $default = '', $type = INPUT_POST, $flags = array() )
    {
        $_flags = self::flagCheck( $flags );
        return self::getVar( $type, $variable_name, FILTER_VALIDATE_IP, $default, $_flags );
    }

    /**
     * XoopsRequest::getUserAgent()
     *
     * @return
     */
    function getUserAgent()
    {
        return ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? false : $_SERVER['HTTP_USER_AGENT'];;
    }
    /**
     * XoopsRequest::setVar()
     *
     * @param mixed $value
     * @param mixed $filter
     * @param string $options
     * @return
     */
    private function setVar( $value, $filter, $options = '' )
    {
        return filter_var( $value, $filter, $options );
    }

    /**
     * XoopsRequest::setInt()
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    public function setInt( $value )
    {
        return self::setVar( $value, FILTER_SANITIZE_NUMBER_INT );
    }

    /**
     * XoopsRequest::setBool()
     *
     * @param mixed $value
     * @return
     */
    public function setBool( $value )
    {
        return self::setVar( $value, FILTER_SANITIZE_NUMBER_BOOL );
    }

    /**
     * XoopsRequest::setFloat()
     *
     * @param mixed $value
     * @return
     */
    public function setFloat( $value )
    {
        return self::setVar( $value, FILTER_SANITIZE_NUMBER_FLOAT );
    }

    /**
     * XoopsRequest::setTextBox()
     *
     * @param mixed $value
     * @return
     */
    public function setTextBox( $value )
    {
        return self::setVar( $value, FILTER_SANITIZE_MAGIC_QUOTES );
    }

    /**
     * XoopsRequest::setEmail()
     *
     * @param mixed $value
     * @return
     */
    function setEmail( $value )
    {
        return self::setVar( $value, FILTER_SANITIZE_EMAIL );
    }

    /**
     * XoopsRequest::setUrl()
     *
     * @return
     */
    function setUrl( $value, $flags )
    {
        $value = self::setVar( $value, FILTER_SANITIZE_URL );
        return self::setVar( $value, FILTER_SANITIZE_ENCODED, $flags );
    }

    /**
     * XoopsRequest::getFilename()
     *
     * @param mixed $value
     * @return
     */
    function setFilename( $value )
    {
        $bad = array( "../", "./", "<!--", "-->", "<", ">", "'", '"', '&', '$', '#', '{', '}', '[', ']', '=', ';', '?', "%20", "%22", "%3c", "%253c", "%3e", "%0e", "%28", "%29", "%2528", "%26", "%24", "%3f", "%3b", "%3d" );
        return stripslashes( str_replace( $bad, '', $value ) );
    }

    /**
     * Start of the is_set type of methods.
     *
     * Usage:
     */

    /**
     * do is valid checks on vars
     */
    private function isVar( $value, $filter, $options = '' )
    {
        return filter_var( $value, $filter, $options );
    }

    /**
     * isInt()
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    public function isInt( $value, $min = null, $max = null, $flags = null )
    {
        $int_options = ( is_numeric( $min ) && is_numeric( $max ) ) ? array( 'options' =>
            array( 'min_range' => $min, 'max_range' => $max ) ) :
        null;
        return self::isVar( $value, FILTER_VALIDATE_INT, $int_options, $flags );
    }

    /**
     * isWithinRange()
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    public function isWithinRange( $value, $min = null, $max = null )
    {
        return isInt( $value, $min = null, $max = null, $flags = null );
    }
    /**
     * isTrue()
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    public function isBool( $value )
    {
        return self::isVar( $value, FILTER_VALIDATE_BOOLEAN );
    }

    /**
     * isTrue()
     *
     * alias of isBool
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return
     */
    public function isTrue( $value )
    {
        return self::isVar( $value, FILTER_VALIDATE_BOOLEAN );
    }

    /**
     * isEmail()
     *
     * @param mixed $variable
     * @param mixed $flags
     * @return
     */
    public function isEmail( $value, $flags )
    {
        return self::isVar( $value, FILTER_VALIDATE_EMAIL );
    }

    /**
     * XoopsRequest::isIP()
     *
     * @param mixed $value
     * @param mixed $flags
     * @return
     */
    function isIP( $value, $flags )
    {
        return self::isVar( $value, FILTER_VALIDATE_IP );
    }

    /**
     * isUrl()
     *
     * @param mixed $value
     * @param mixed $flags
     * @return
     */
    function isUrl( $value, $flags )
    {
        return self::isVar( $value, FILTER_VALIDATE_URL, $flags );
    }
    /**
     * isString()
     *
     * @param mixed $value
     * @return
     */
    function isString( $value )
    {
        return is_string( $value );
    }

    /**
     * XoopsRequest::isStringLength()
     *
     * @param mixed $value
     * @param mixed $length
     * @return
     */
    function isStringLength( $value, $length )
    {
        return ( is_numeric( $length ) && ( strlen( $value ) >= ( int )$length ) ) ? false : true;
    }

    /**
     * isAlpha()
     *
     * @param mixed $value
     * @return
     */
    function isAlpha( $value )
    {
        return preg_match( '/^[a-zA-Z]+$/', $value );
    }

    /**
     * isNumeric()
     *
     * @param mixed $value
     * @return
     */
    function isNumeric( $value )
    {
        return is_numeric( $value );
    }

    /**
     * isArray()
     *
     * @param mixed $value
     * @return
     */
    function isArray( $value )
    {
        return ( is_array( $value ) && !empty( $value ) );
    }

    /**
     * isInArray()
     *
     * @param mixed $value
     * @param mixed $array
     * @return
     */
    function isInArray( $value, $array )
    {
        if ( self::isArray( $array ) ) {
            return in_array( $value, $array );
        }
    }

    /**
     * XoopsRequest::isSubmit()
     *
     * @return
     */
    function isSubmit()
    {
        return filter_has_var( INPUT_POST, 'submit' ) ? true : false;
    }

    /**
     * XoopsRequest::_getDefault()
     *
     * @param mixed $value
     * @return
     */
    function _getDefault( $value )
    {
        return $value;
    }
    /**
     * XoopsRequest::flagCheck()
     *
     * @param mixed $flags
     * @return
     */
    private function flagCheck( $flags )
    {
        if ( is_array( $flags ) && !empty( $flags ) ) {
            return explode( '|', $flags );
        } else if ( !empty( $flags ) ) {
            return array( $flags );
        } else {
            return '';
        }
    }

    function cleanSuperGlobals()
    {
        xoops_inlude( 'include/version' );
    }

    function checkbadIps()
    {
    }
}

?>