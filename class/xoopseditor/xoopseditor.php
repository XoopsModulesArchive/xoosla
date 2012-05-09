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
 * XOOPS Editor Abstract class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package core
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

xoops_loadLanguage( 'admin' );

/**
 * XoopsEditor
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class XoopsEditor extends XoopsFormTextArea {
	public $isEnabled;
	public $configs;
	public $rootPath;
	public $_rows = 5;
	public $_cols = 50;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$args = func_get_args();
		// For backward compatibility
		if ( !is_array( $args[0] ) ) {
			$i = 0;
			foreach ( array( 'caption' , 'name' , 'value' , 'rows' , 'cols' , 'hiddentext' ) as $key ) {
				if ( isset( $args[$i] ) ) {
					$configs[$key] = $args[$i];
				}
				$i ++;
			}
			$configs = ( isset( $args[$i] ) && is_array( $args[$i] ) ) ? array_merge( $configs, $args[$i] ) : $configs;
		} else {
			$configs = $args[0];
		}
		// TODO: switch to property_exists() as of PHP 5.1.0
		$vars = get_class_vars( __CLASS__ );
		foreach ( $configs as $key => $val ) {
			if ( method_exists( $this, 'set' . ucfirst( $key ) ) ) {
				$this->{'set' . ucfirst( $key )}( $val );
			} else if ( array_key_exists( "_{$key}", $vars ) ) {
				$this-> {"_{$key}"} = $val;
			} else if ( array_key_exists( $key, $vars ) ) {
				$this->{$key} = $val;
			} else {
				$this->configs[$key] = $val;
			}
		}
		$this->isActive();
	}

	/**
	 * XoopsEditor::XoopsEditor()
	 *
	 * @param mixed $configs
	 */
	public function XoopsEditor( $configs )
	{
		$this->__construct( $configs );
	}

	/**
	 * XoopsEditor::isActive()
	 *
	 * @return
	 */
	public function isActive()
	{
		$this->isEnabled = true;
		return $this->isEnabled;
	}
}

/**
 * Editor handler
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package core
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 */
class XoopsEditorHandler {
	// static $instance;
	public $root_path = '';
	public $nohtml = false;
	public $allowed_editors = array();
	/**
	 * Enter description here...
	 */
	function __construct()
	{
		$this->root_path = XOOPS_ROOT_PATH . '/class/xoopseditor';
	}

	/**
	 * Access the only instance of this class
	 *
	 * @return object
	 * @static
	 * @staticvar object
	 */
	static function getInstance()
	{
		static $instance;
		if ( !isset( $instance ) ) {
			$class = __CLASS__;
			$instance = new $class();
		}
		return $instance;
	}

	/**
	 *
	 * @param string $name Editor name which is actually the folder name
	 * @param array $options editor options: $key => $val
	 * @param string $OnFailure a pre-validated editor that will be used if the required editor is failed to create
	 * @param bool $noHtml dohtml disabled
	 */
	public function get( $name = '', $options = null, $noHtml = false, $OnFailure = '' )
	{
		if ( $editor = $this->_loadEditor( $name, $options ) ) {
			return $editor;
		}
		$list = array_keys( $this->getList( $noHtml ) );
		if ( empty( $OnFailure ) || !in_array( $OnFailure, $list ) ) {
			$OnFailure = $list[0];
		}
		$editor = $this->_loadEditor( $OnFailure, $options );
		return $editor;
	}

	/**
	 * XoopsEditorHandler::getList()
	 *
	 * @param mixed $noHtml
	 * @return
	 */
	public function getList( $noHtml = false )
	{
		$list = XoopsCache::read( 'editorlist' );
		if ( empty( $list ) ) {
			$list = array();
			$order = array();
			$_list = XoopsLists::getDirListAsArray( $this->root_path );
			foreach ( $_list as $item ) {
				if ( file_exists( $file = $this->root_path . '/' . $item . '/language/' . $GLOBALS['xoopsConfig']['language'] . '.php' ) ) {
					include_once $file;
				} else if ( file_exists( $file = $this->root_path . '/' . $item . '/language/english.php' ) ) {
					include_once $file;
				}
				
				if ( file_exists( $file = $this->root_path . '/' . $item . '/editor_registry.php' ) ) {
					include $file;
					if ( empty( $config['order'] ) )
						continue;
					$order[] = $config['order'];
					$list[$item] = array( 'title' => $config['title'] , 'nohtml' => $config['nohtml'] );
				}
			}
			array_multisort( $order, $list );
			XoopsCache::write( 'editorlist', $list );
		}
		$editors = array_keys( $list );
		if ( !empty( $this->allowed_editors ) ) {
			$editors = array_intersect( $editors, $this->allowed_editors );
		}
		$_list = array();
		foreach ( $editors as $name ) {
			if ( !empty( $noHtml ) && empty( $list[$name]['nohtml'] ) )
				continue;
			$_list[$name] = $list[$name]['title'];
		}
		return $_list;
	}

	/**
	 * XoopsEditorHandler::setConfig()
	 *
	 * @param mixed $editor
	 * @param mixed $options
	 * @return
	 */
	public function setConfig( $editor, $options )
	{
		if ( method_exists( $editor, 'setConfig' ) ) {
			$editor->setConfig( $options );
		} else {
			foreach ( $options as $key => $val ) {
				$editor->$key = $val;
			}
		}
	}

	/**
	 * XoopsEditorHandler::_loadEditor()
	 *
	 * @param mixed $name
	 * @param mixed $options
	 * @return
	 */
	public function _loadEditor( $name, $options = null )
	{
		$editor = null;
		if ( empty( $name ) || !array_key_exists( $name, $this->getList() ) ) {
			return $editor;
		}
		
		$editor_path = $this->root_path . '/' . $name;
		if ( file_exists( $file = $editor_path . '/language/' . $GLOBALS['xoopsConfig']['language'] . '.php' ) ) {
			include_once $file;
		} else if ( file_exists( $file = $editor_path . '/language/english.php' ) ) {
			include_once $file;
		}
		
		if ( file_exists( $file = $editor_path . '/editor_registry.php' ) ) {
			include $file;
		} else {
			return $editor;
		}
		if ( empty( $config['order'] ) ) {
			return $editor;
		}

		include_once $config['file'];
		$editor = new $config['class']( $options );
		return $editor;
	}
}

?>