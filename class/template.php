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
 * XOOPS template engine class
 *
 * @copyright The XOOPS project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @package kernel
 * @subpackage core
 * @version $Id$
 */

defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );
/**
 * Base class: Smarty template engine
 */
define( 'SMARTY_DIR', XOOPS_ROOT_PATH . '/libraries/3rdparty/smarty/' );

require_once SMARTY_DIR . 'Smarty.class.php';

/**
 * Template engine
 *
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsTpl extends Smarty {
	/**
	 * XoopsTpl::XoopsTpl()
	 */
	public function __Construct()
	{
		global $xoopsConfig;

		$this->left_delimiter = '<{';
		$this->right_delimiter = '}>';
		$this->template_dir = XOOPS_THEME_PATH;
		$this->cache_dir = XOOPS_VAR_PATH . '/caches/smarty_cache';
		$this->compile_dir = XOOPS_VAR_PATH . '/caches/smarty_compile';
		$this->compile_check = ( $xoopsConfig['theme_fromfile'] == 1 );
		$this->plugins_dir = array(
			XOOPS_ROOT_PATH . '/libraries/3rdparty/smarty/xoops_plugins' ,
			XOOPS_ROOT_PATH . '/libraries/3rdparty/smarty/plugins' );
		if ( $xoopsConfig['debug_mode'] ) {
			$this->debugging_ctrl = 'URL';
			if ( $xoopsConfig['debug_mode'] == 3 ) {
				$this->debugging = true;
			}
		}
		$this->Smarty();
		$this->setCompileId();
		$this->assign( array(
				'xoops_url' => XOOPS_URL ,
				'xoops_rootpath' => XOOPS_ROOT_PATH ,
				'xoops_langcode' => _LANGCODE ,
				'xoops_charset' => _CHARSET ,
				'xoops_version' => XOOPS_VERSION ,
				'xoops_upload_url' => XOOPS_UPLOAD_URL ) );
	}

	/**
	 * Renders output from template data
	 *
	 * @param string $data The template to render
	 * @param bool $display If rendered text should be output or returned
	 * @return string Rendered output if $display was false
	 */
	public function fetchFromData( $tplSource, $display = false, $vars = null )
	{
		if ( !function_exists( 'smarty_function_eval' ) ) {
			require_once SMARTY_DIR . '/plugins/public function.eval.php';
		}
		if ( isset( $vars ) ) {
			$oldVars = $this->_tpl_vars;
			$this->assign( $vars );
			$out = smarty_function_eval( array(
					'var' => $tplSource ), $this );
			$this->_tpl_vars = $oldVars;
			return $out;
		}
		return smarty_function_eval( array(
				'var' => $tplSource ), $this );
	}

	/**
	 * XoopsTpl::touch
	 *
	 * @param mixed $resourceName
	 * @return
	 */
	public function touch( $resourceName )
	{
		$isForced = $this->force_compile;
		$this->force_compile = true;
		$this->clear_cache( $resourceName );
		$result = $this->_compile_resource( $resourceName, $this->_get_compile_path( $resourceName ) );
		$this->force_compile = $isForced;
		return $result;
	}

	/**
	 * returns an auto_id for auto-file-functions
	 *
	 * @param string $cache_id
	 * @param string $compile_id
	 * @return string |null
	 */
	public function _get_auto_id( $cache_id = null, $compile_id = null )
	{
		if ( isset( $cache_id ) ) {
			return ( isset( $compile_id ) ) ? $compile_id . '-' . $cache_id : $cache_id;
		} else if ( isset( $compile_id ) ) {
			return $compile_id;
		} else {
			return null;
		}
	}

	/**
	 * XoopsTpl::setCompileId()
	 *
	 * @param mixed $module_dirname
	 * @param mixed $theme_set
	 * @param mixed $template_set
	 * @return
	 */
	public function setCompileId( $module_dirname = null, $theme_set = null, $template_set = null )
	{
		global $xoopsConfig;

		$template_set = empty( $template_set ) ? $xoopsConfig['template_set'] : $template_set;
		$theme_set = empty( $theme_set ) ? $xoopsConfig['theme_set'] : $theme_set;
		$module_dirname = empty( $module_dirname ) ? ( empty( $GLOBALS['xoopsModule'] ) ? 'system' : $GLOBALS['xoopsModule']->getVar( 'dirname', 'n' ) ) : $module_dirname;
		$this->compile_id = substr( md5( XOOPS_URL ), 0, 8 ) . '-' . $module_dirname . '-' . $theme_set . '-' . $template_set;
		$this->_compile_id = $this->compile_id;
	}

	/**
	 * XoopsTpl::clearCache()
	 *
	 * @param mixed $module_dirname
	 * @param mixed $theme_set
	 * @param mixed $template_set
	 * @return
	 */
	public function clearCache( $module_dirname = null, $theme_set = null, $template_set = null )
	{
		$compile_id = $this->compile_id;
		$this->setCompileId( $module_dirname, $template_set, $theme_set );
		$_params = array(
			'auto_base' => $this->cache_dir ,
			'auto_source' => null ,
			'auto_id' => $this->compile_id,
			'exp_time' => null,
			);
		$this->_compile_id = $this->compile_id = $compile_id;
		require_once SMARTY_CORE_DIR . 'core.rm_auto.php';
		return smarty_core_rm_auto( $_params, $this );
	}
}


?>