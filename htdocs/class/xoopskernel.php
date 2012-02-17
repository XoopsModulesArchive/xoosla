<?php

/**
 * Extremely reduced kernel class
 * This class should not really be defined in this file, but it wasn't worth including an entire
 * file for those two functions.
 * Few notes:
 * - modules should use this class methods to generate physical paths/URIs (the ones which do not conform
 * will perform badly when true URL rewriting is implemented)
 */
class xoops_Kernel {
    var $paths = array( 'www' => array(), 'modules' => array(), 'themes' => array(),
        );
    function __construct()
    {
        $this->paths['www'] = array( XOOPS_ROOT_PATH, XOOPS_URL );
        $this->paths['modules'] = array( XOOPS_ROOT_PATH . XD_S . 'modules', XOOPS_URL . '/modules' );
        $this->paths['themes'] = array( XOOPS_ROOT_PATH . XD_S . 'themes', XOOPS_URL . '/themes' );
    }

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
     * Convert a XOOPS path to a physical one
     */
    function path( $url, $virtual = false )
    {
        $path = '';
        @list( $root, $path ) = explode( '/', $url, 2 );
        if ( !isset( $this->paths[$root] ) ) {
            list( $root, $path ) = array( 'www', $url );
        }
        if ( !$virtual ) { // Returns a physical path
            return $this->paths[$root][0] . '/' . $path;
        }
        return !isset( $this->paths[$root][1] ) ? '' : ( $this->paths[$root][1] . '/' . $path );
    }
    /**
     * Convert a XOOPS path to an URL
     */
    function url( $url )
    {
        return ( false !== strpos( $url, '://' ) ? $url : $this->path( $url, true ) );
    }
    /**
     * Build an URL with the specified request params
     */
    function buildUrl( $url, $params = array() )
    {
        if ( $url == '.' ) {
            $url = $_SERVER['REQUEST_URI'];
        }
        $split = explode( '?', $url );
        if ( count( $split ) > 1 ) {
            list( $url, $query ) = $split;
            parse_str( $query, $query );
            $params = array_merge( $query, $params );
        }
        if ( !empty( $params ) ) {
            foreach ( $params as $k => $v ) {
                $params[$k] = $k . '=' . rawurlencode( $v );
            }
            $url .= '?' . implode( '&', $params );
        }
        return $url;
    }

    function gzipCompression()
    {
        global $xoopsConfig;
        // Disable gzip compression if PHP is run under CLI mode
        // To be refactored
        if ( empty( $_SERVER['SERVER_NAME'] ) || substr( PHP_SAPI, 0, 3 ) == 'cli' ) {
            $xoopsConfig['gzip_compression'] = 0;
        }
        if ( $xoopsConfig['gzip_compression'] == 1 && extension_loaded( 'zlib' ) && !ini_get( 'zlib.output_compression' ) ) {
            if ( @ini_get( 'zlib.output_compression_level' ) < 0 ) {
                ini_set( 'zlib.output_compression_level', 6 );
            }
            ob_start( 'ob_gzhandler' );
        }
    }

    /**
     * xoops_Kernel::pathTranslation()
     *
     * @return
     */
    function pathTranslation()
    {
        /**
         * /**
         * *#@+
         * Host abstraction layer
         */
        if ( !isset( $_SERVER['PATH_TRANSLATED'] ) && isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
            $_SERVER['PATH_TRANSLATED'] = &$_SERVER['SCRIPT_FILENAME']; // For Apache CGI
        } elseif ( isset( $_SERVER['PATH_TRANSLATED'] ) && !isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
            $_SERVER['SCRIPT_FILENAME'] = &$_SERVER['PATH_TRANSLATED']; // For IIS/2K now I think :-(
        }

        if ( empty( $_SERVER['REQUEST_URI'] ) ) { // Not defined by IIS
            // Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
            if ( !( $_SERVER['REQUEST_URI'] = @$_SERVER['PHP_SELF'] ) ) {
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
            }
            if ( isset( $_SERVER['QUERY_STRING'] ) ) {
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
    }

    /**
     * xoops_Kernel::checkBadips()
     *
     * @return
     */
    function checkBadips()
    {
        global $xoopsConfig;
        if ( $xoopsConfig['enable_badips'] == 1 && isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] != '' ) {
            foreach ( $xoopsConfig['bad_ips'] as $bi ) {
                if ( !empty( $bi ) && preg_match( "/" . $bi . "/", $_SERVER['REMOTE_ADDR'] ) ) {
                    exit();
                }
            }
        }
        unset( $bi );
        unset( $bad_ips );
        unset( $xoopsConfig['badips'] );
    }

    function getConfig()
    {
        global $config_handler;

        $ret = &$config_handler->getConfigsByCat( array( XOOPS_CONF, XOOPS_CONF_METAFOOTER ) ); // Made change to allow more that one category for xoopsconfig
        return $ret;
    }

    function loadDB()
    {
        global $xoopsSecurity, $xoopsDB;

        if ( xoops_inlude( 'class.database.databasefactory' ) ) {
            if ( $_SERVER['REQUEST_METHOD'] != 'POST' || !$xoopsSecurity->checkReferer( XOOPS_DB_CHKREF ) ) {
                define( 'XOOPS_DB_PROXY', 1 );
            }
            $ret = &XoopsDatabaseFactory::getDatabaseConnection();
            return $ret;
        }
    }
}

?>