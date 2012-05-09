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
 * XOOPS TextSanitizer extension
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @since 2.0.0
 * @author Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @author Goghs Cheng (http://www.eqiao.com, http://www.devbeez.com/)
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Abstract class for extensions
 */
abstract class MyTextSanitizerExtension {
	/**
	 *
	 * @var MyTextSanitizerExtension
	 */
	public $instance;

	/**
	 *
	 * @var MyTextSanitizer
	 */
	public $ts;

	/**
	 *
	 * @var
	 */
	public $config;

	/**
	 *
	 * @var string
	 */
	public $image_path;

	/**
	 * Constructor
	 *
	 * @param unknown_type $ts
	 */
	public function __construct( MyTextSanitizer &$ts )
	{
		$this->ts = $ts;
		$this->image_path = XOOPS_URL . '/images/form';
	}

	/**
	 * loadConfig
	 *
	 * @param string $path
	 * @return string
	 */
	public static function loadConfig( $path = null )
	{
		$ts = MyTextSanitizer::getInstance();
		$path = str_replace( DIRECTORY_SEPARATOR, '/', $path );
		if ( false === strpos( $path, '/' ) ) {
			if ( is_dir( $ts->path_basic . '/' . $path ) ) {
				$path = $ts->path_basic . '/' . $path;
			} else {
				if ( is_dir( $ts->path_plugin . '/' . $path ) ) {
					$path = $ts->path_plugin . '/' . $path;
				}
			}
		}
		$config_default = array();
		$config_custom = array();
		if ( file_exists( $path . '/config.php' ) ) {
			$config_default = include $path . '/config.php';
		}
		if ( file_exists( $path . '/config.custom.php' ) ) {
			$config_custom = include $path . '/config.custom.php';
		}
		return self::mergeConfig( $config_default, $config_custom );
	}

	/**
	 * Merge Config
	 *
	 * @param array $config_default
	 * @param array $config_custom
	 * @return array
	 */
	public function mergeConfig( $config_default, $config_custom )
	{
		if ( is_array( $config_custom ) ) {
			foreach ( $config_custom as $key => $val ) {
				if ( is_array( $config_default[$key] ) ) {
					$config_default[$key] = $this->mergeConfig( $config_default[$key], $config_custom[$key] );
				} else {
					$config_default[$key] = $val;
				}
			}
		}
		return $config_default;
	}

	/**
	 * encode
	 *
	 * @return array
	 */
	public function encode()
	{
		return array();
	}

	/**
	 * decode
	 *
	 * @return Null
	 */
	public function decode()
	{
		return null;
	}
}

/**
 * Class to "clean up" text for various uses
 *
 * <strong>Singleton</strong>
 *
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author Goghs Cheng
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class MyTextSanitizer {
	/**
	 *
	 * @var array
	 */
	public $smileys = array();
	/**
	 *
	 * @var array
	 */
	public $censorConf;

	/**
	 *
	 * @var holding reference to text
	 */
	public $text = '';

	/**
	 *
	 * @var array
	 */
	public $patterns = array();

	/**
	 *
	 * @var array
	 */
	public $replacements = array();

	/**
	 *
	 * @var array
	 */
	public $path_basic;

	/**
	 *
	 * @var array
	 */
	public $path_plugin;

	/**
	 *
	 * @var array
	 */
	public $config;

	/**
	 * MyTextSanitizer::__construct()
	 */
	public function __construct()
	{
		$this->path_basic = XOOPS_ROOT_PATH . '/class/textsanitizer';
		$this->config = $this->loadConfig();
	}

	/**
	 * Enter description here...
	 *
	 * @param string $name
	 * @return array
	 */
	public function loadConfig( $name = null )
	{
		if ( !empty( $name ) ) {
			return MyTextSanitizerExtension::loadConfig( $name );
		}
		$config_default = include $this->path_basic . '/config.php';
		$config_custom = array();
		if ( file_exists( $file = $this->path_basic . '/config.custom.php' ) ) {
			$config_custom = include $file;
		}
		return $this->mergeConfig( $config_default, $config_custom );
	}

	/**
	 * Enter description here...
	 *
	 * @param array $config_default
	 * @param array $config_custom
	 * @return unknown
	 */
	public function mergeConfig( $config_default, $config_custom )
	{
		if ( is_array( $config_custom ) ) {
			foreach ( $config_custom as $key => $val ) {
				if ( isset( $config_default[$key] ) && is_array( $config_default[$key] ) ) {
					$config_default[$key] = $this->mergeConfig( $config_default[$key], $config_custom[$key] );
				} else {
					$config_default[$key] = $val;
				}
			}
		}
		return $config_default;
	}

	/**
	 * Access the only instance of this class
	 *
	 * @return object
	 * @static
	 * @staticvar object
	 */
	public function getInstance()
	{
		static $instance;
		if ( !isset( $instance ) ) {
			$instance = new MyTextSanitizer();
		}
		return $instance;
	}

	/**
	 * Get the smileys
	 *
	 * @param boole $isAll TRUE for all smileys, FALSE for smileys with display = 1
	 * @return array
	 */
	public function getSmileys( $isAll = true )
	{
		if ( count( $this->smileys ) == 0 ) {
			$xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
			if ( $getsmiles = $xoopsDB->query( 'SELECT * FROM ' . $xoopsDB->prefix( 'smiles' ) ) ) {
				while ( $smiles = $xoopsDB->fetchArray( $getsmiles ) ) {
					array_push( $this->smileys, $smiles );
				}
			}
		}
		if ( $isAll ) {
			return $this->smileys;
		}

		$smileys = array();
		foreach( $this->smileys as $smile ) {
			if ( empty( $smile['display'] ) ) {
				continue;
			}
			$smileys[] = $smile;
		}
		return $smileys;
	}

	/**
	 * Replace emoticons in the message with smiley images
	 *
	 * @param string $message
	 * @return string
	 */
	public function smiley( $message )
	{
		$smileys = $this->getSmileys();
		foreach( $smileys as $smile ) {
			$message = str_replace( $smile['code'], '<img class="imgsmile" src="' . XOOPS_UPLOAD_URL . '/' . htmlspecialchars( $smile['smile_url'] ) . '" alt="" />', $message );
		}
		return $message;
	}

	/**
	 * Make links in the text clickable
	 *
	 * @param string $text
	 * @return string
	 */
	public function makeClickable( &$text )
	{
		$valid_chars = "a-z0-9\/\-_+=.~!%@?#&;:$\|";
		$end_chars = "a-z0-9\/\-_+=~!%@?#&;:$\|";

		$patterns = array();
		$replacements = array();

		$patterns[] = "/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([{$valid_chars}]+[{$end_chars}])/ei";
		$replacements[] = "'\\1<a href=\"\\2://\\3\" title=\"\\2://\\3\" rel=\"external\">\\2://'.MyTextSanitizer::truncate( '\\3' ).'</a>'";

		$patterns[] = "/(^|[^]_a-z0-9-=\"'\/:\.])www\.((([a-zA-Z0-9\-]*\.){1,}){1}([a-zA-Z]{2,6}){1})((\/([a-zA-Z0-9\-\._\?\,\'\/\\+&%\$#\=~])*)*)/ei";
		$replacements[] = "'\\1<a href=\"http://www.\\2\\6\" title=\"www.\\2\\6\" rel=\"external\">'.MyTextSanitizer::truncate( 'www.\\2\\6' ).'</a>'";

		$patterns[] = "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([{$valid_chars}]+[{$end_chars}])/ei";
		$replacements[] = "'\\1<a href=\"ftp://ftp.\\2.\\3\" title=\"ftp.\\2.\\3\" rel=\"external\">'.MyTextSanitizer::truncate( 'ftp.\\2.\\3' ).'</a>'";

		$patterns[] = "/(^|[^]_a-z0-9-=\"'\/:\.])([-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+)@((?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?)/ei";
		$replacements[] = "'\\1<a href=\"mailto:\\2@\\3\" title=\"\\2@\\3\">'.MyTextSanitizer::truncate( '\\2@\\3' ).'</a>'";

		$text = preg_replace( $patterns, $replacements, $text );
		return $text;
	}

	/**
	 * MyTextSanitizer::truncate()
	 *
	 * @param mixed $text
	 * @return
	 */
	public function truncate( $text )
	{
		$instance = MyTextSanitizer::getInstance();
		if ( empty( $text ) || empty( $instance->config['truncate_length'] ) || strlen( $text ) < $instance->config['truncate_length'] ) {
			return $text;
		}
		$len = floor( $instance->config['truncate_length'] / 2 );
		$ret = substr( $text, 0, $len ) . ' ... ' . substr( $text, 5 - $len );
		return $ret;
	}

	/**
	 * Replace XoopsCodes with their equivalent HTML formatting
	 *
	 * @param string $text
	 * @param bool $allowimage Allow images in the text?
	 *                                                                                            On FALSE, uses links to images.
	 * @return string
	 */
	public function xoopsCodeDecode( $text, $allowimage = 1 )
	{
		$patterns = array();
		$replacements = array();
		$patterns[] = "/\[siteurl=(['\"]?)([^\"'<>]*)\\1](.*)\[\/siteurl\]/sU";
		$replacements[] = '<a href="' . XOOPS_URL . '/\\2">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(http[s]?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" rel="external">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(ftp?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" rel="external">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)([^'\"<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="http://\\2" rel="external">\\3</a>';
		$patterns[] = "/\[color=(['\"]?)([a-zA-Z0-9]*)\\1](.*)\[\/color\]/sU";
		$replacements[] = '<span style="color: #\\2;">\\3</span>';
		$patterns[] = "/\[size=(['\"]?)([a-z0-9-]*)\\1](.*)\[\/size\]/sU";
		$replacements[] = '<span style="font-size: \\2;">\\3</span>';
		$patterns[] = "/\[font=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/font\]/sU";
		$replacements[] = '<span style="font-family: \\2;">\\3</span>';
		$patterns[] = "/\[email]([^;<>\*\(\)\"']*)\[\/email\]/sU";
		$replacements[] = '<a href="mailto:\\1">\\1</a>';

		$patterns[] = "/\[b](.*)\[\/b\]/sU";
		$replacements[] = '<strong>\\1</strong>';
		$patterns[] = "/\[i](.*)\[\/i\]/sU";
		$replacements[] = '<em>\\1</em>';
		$patterns[] = "/\[u](.*)\[\/u\]/sU";
		$replacements[] = '<u>\\1</u>';
		$patterns[] = "/\[d](.*)\[\/d\]/sU";
		$replacements[] = '<del>\\1</del>';
		$patterns[] = "/\[center](.*)\[\/center\]/sU";
		$replacements[] = '<div style="text-align: center;">\\1</div>';
		$patterns[] = "/\[left](.*)\[\/left\]/sU";
		$replacements[] = '<div style="text-align: left;">\\1</div>';
		$patterns[] = "/\[right](.*)\[\/right\]/sU";
		$replacements[] = '<div style="text-align: right;">\\1</div>';

		$this->text = $text;
		$this->patterns = $patterns;
		$this->replacements = $replacements;

		$this->config['allowimage'] = $allowimage;
		$this->executeExtensions();

		$text = preg_replace( $this->patterns, $this->replacements, $this->text );
		$text = $this->quoteConv( $text );
		return $text;
	}

	/**
	 * Convert quote tags
	 *
	 * @param string $text
	 * @return string
	 */
	public function quoteConv( $text )
	{
		// look for both open and closing tags in the correct order
		$pattern = "/\[quote](.*)\[\/quote\]/sU";
		$replacement = _QUOTEC . '<div class="xoopsQuote"><blockquote>\\1</blockquote></div>';

		$text = preg_replace( $pattern, $replacement, $text, - 1, $count );
		// no more matches, return now
		if ( !$count ) {
			return $text;
		}
		// new matches could have been created, keep doing it until we have no matches
		return $this->quoteConv( $text );
	}

	/**
	 * A quick solution for filtering XSS scripts
	 *
	 * @TODO : To be improved
	 */
	public function filterXss( $text )
	{
		$patterns = array();
		$replacements = array();
		$text = str_replace( "\x00", "", $text );
		$c = "[\x01-\x1f]*";
		$patterns[] = "/\bj{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}[\s]*:/si";
		$replacements[] = "javascript;";
		$patterns[] = "/\ba{$c}b{$c}o{$c}u{$c}t{$c}[\s]*:/si";
		$replacements[] = "about;";
		$patterns[] = "/\bx{$c}s{$c}s{$c}[\s]*:/si";
		$replacements[] = "xss;";
		$text = preg_replace( $patterns, $replacements, $text );
		return $text;
	}

	/**
	 * Convert linebreaks to <br /> tags
	 *
	 * @param string $text
	 * @return string
	 */
	public function nl2Br( $text )
	{
		return preg_replace( '/(\015\012)|(\015)|(\012)/', '<br />', $text );
	}

	/**
	 * Add slashes to the text if magic_quotes_gpc is turned off.
	 *
	 * @param string $text
	 * @return string
	 */
	public function addSlashes( $text )
	{
		if ( PHP_VERSION < 6 ) {
			if ( !get_magic_quotes_gpc() ) {
				$text = addslashes( $text );
			}
		}
		return $text;
	}

	/**
	 * MyTextSanitizer::stripSlashes()
	 *
	 * @return
	 */
	public function stripSlashes( $text )
	{
		if ( PHP_VERSION < 6 ) {
			if ( get_magic_quotes_gpc() ) {
				$text = stripslashes( $text );
			}
		}
		return $text;
	}

	/**
	 * if magic_quotes_gpc is on, stirip back slashes
	 *
	 * @param string $text
	 * @return string
	 */
	public function stripSlashesGPC( $text )
	{
		if ( PHP_VERSION < 6 ) {
			$text = self::stripSlashes( $text );
		}
		return $text;
	}

	/**
	 * Convert special characters to HTML entities
	 *
	 * @param string $text string being converted
	 * @param int $quote_style
	 * @param string $charset character set used in conversion
	 * @param bool $double_encode
	 * @return string
	 */
	public function htmlSpecialChars( $text, $quote_style = ENT_QUOTES, $charset = null, $double_encode = true )
	{
		if ( version_compare( phpversion(), '5.2.3', '>=' ) ) {
			$text = htmlspecialchars( $text, $quote_style, $charset ? $charset : ( defined( '_CHARSET' ) ? _CHARSET : 'UTF-8' ), $double_encode );
		} else {
			$text = htmlspecialchars( $text, $quote_style );
		}
		return preg_replace( array( '/&amp;/i' , '/&nbsp;/i' ), array( '&' , '&amp;nbsp;' ), $text );
	}

	/**
	 * MyTextSanitizer::stripHtmlSpecialChars()
	 *
	 * @param mixed $text
	 * @param mixed $quote_style
	 * @param mixed $charset
	 * @param mixed $double_encode
	 * @return
	 */
	public function stripHtmlSpecialChars( $text, $quote_style = ENT_QUOTES, $charset = null, $double_encode = true )
	{
		$text = $this->stripSlashes( $text );
		$text = $this->htmlSpecialChars( $text, $quote_style, $charset, $double_encode );
		return $text;
	}

	/**
	 * Reverses {@link htmlSpecialChars()}
	 *
	 * @param string $text
	 * @return string
	 */
	public function undoHtmlSpecialChars( $text )
	{
		return preg_replace( array( '/&gt;/i' , '/&lt;/i' , '/&quot;/i' , '/&#039;/i' , '/&amp;nbsp;/i' ), array( '>' , '<' , '"' , '\'' , "&nbsp;" ), $text );
	}

	/**
	 * Filters textarea form data in DB for display
	 *
	 * @param string $text
	 * @param bool $html allow html?
	 * @param bool $smiley allow smileys?
	 * @param bool $xcode allow xoopscode?
	 * @param bool $image allow inline images?
	 * @param bool $br convert linebreaks?
	 * @return string
	 */
	public function displayTarea( $text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1 )
	{
		if ( $html != 1 ) {
			// html not allowed
			$text = $this->htmlSpecialChars( $text );
		}
		$text = $this->codePreConv( $text, $xcode ); // Ryuji_edit(2003-11-18)
		if ( $smiley != 0 ) {
			// process smiley
			$text = $this->smiley( $text );
		}
		if ( $xcode != 0 ) {
			// decode xcode
			if ( $image != 0 ) {
				// image allowed
				$text = $this->xoopsCodeDecode( $text );
			} else {
				// image not allowed
				$text = $this->xoopsCodeDecode( $text, 0 );
			}
		}
		if ( $br != 0 ) {
			$text = $this->nl2Br( $text );
		}
		$text = $this->codeConv( $text, $xcode );
		$text = $this->makeClickable( $text );
		if ( !empty( $this->config['filterxss_on_display'] ) ) {
			$text = $this->filterXss( $text );
		}

		return $text;
	}

	/**
	 * Filters textarea form data submitted for preview
	 *
	 * @param string $text
	 * @param bool $html allow html?
	 * @param bool $smiley allow smileys?
	 * @param bool $xcode allow xoopscode?
	 * @param bool $image allow inline images?
	 * @param bool $br convert linebreaks?
	 * @return string
	 */
	public function previewTarea( $text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1 )
	{
		$text = $this->stripSlashesGPC( $text );
		$text = $this->displayTarea( $text, $html, $smiley, $xcode, $image, $br );
		return $text;
	}

	/**
	 * Replaces banned words in a string with their replacements
	 *
	 * @param string $text
	 * @return string
	 * @deprecated
	 */
	public function censorString( &$text )
	{
		$ret = $this->executeExtension( 'censor', $text );
		if ( $ret === false ) {
			return $text;
		}
		return $ret;
	}

	/**
	 * MyTextSanitizer::codePreConv()
	 *
	 * @param mixed $text
	 * @param mixed $xcode
	 * @return
	 */
	public function codePreConv( $text, $xcode = 1 )
	{
		if ( $xcode != 0 ) {
			$patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/esU";
			$replacements = "'[code\\1]'.base64_encode('\\2').'[/code]'";
			$text = preg_replace( $patterns, $replacements, $text );
		}
		return $text;
	}

	/**
	 * MyTextSanitizer::codeConv()
	 *
	 * @param mixed $text
	 * @param mixed $xcode
	 * @return
	 */
	public function codeConv( $text, $xcode = 1 )
	{
		if ( empty( $xcode ) ) {
			return $text;
		}
		$patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/esU";
		$replacements = "'<div class=\"xoopsCode\">'.\$this->executeExtension('syntaxhighlight', str_replace('\\\"', '\"', base64_decode('$2')), '$1').'</div>'";
		$text = preg_replace( $patterns, $replacements, $text );
		return $text;
	}

	/**
	 * MyTextSanitizer::executeExtensions()
	 *
	 * @return
	 */
	public function executeExtensions()
	{
		$extensions = array_filter( $this->config['extensions'] );
		if ( empty( $extensions ) ) {
			return true;
		}
		foreach ( array_keys( $extensions ) as $extension ) {
			$this->executeExtension( $extension );
		}
	}

	/**
	 * MyTextSanitizer::loadExtension()
	 *
	 * @param mixed $name
	 * @return
	 */
	public function loadExtension( $name )
	{
		if ( file_exists( $file = $this->path_basic . '/' . $name . '/' . $name . '.php' ) ) {
			include_once $file;
		} else if ( file_exists( $file = $this->path_plugin . '/' . $name . '/' . $name . '.php' ) ) {
			include_once $file;
		} else {
			return false;
		}
		$class = 'Myts' . ucfirst( $name );
		if ( !class_exists( $class ) ) {
			trigger_error( "Extension '{$name}' does not exist", E_USER_WARNING );
			return false;
		}
		$extension = null;
		$extension = new $class( $this );
		return $extension;
	}

	/**
	 * MyTextSanitizer::executeExtension()
	 *
	 * @param mixed $name
	 * @return
	 */
	public function executeExtension( $name )
	{
		$extension = $this->loadExtension( $name );
		$args = array_slice( func_get_args(), 1 );
		return call_user_func_array( array( $extension , 'load' ), array_merge( array( &$this ), $args ) );
	}

	/**
	 * Filter out possible malicious text
	 * kses project at SF could be a good solution to check
	 *
	 * @param string $text text to filter
	 * @param bool $force force filtering
	 * @return string filtered text
	 */
	public function textFilter( $text, $force = false )
	{
		$ret = $this->executeExtension( 'textfilter', $text, $force );
		if ( $ret === false ) {
			return $text;
		}
		return $ret;
	}
}

?>