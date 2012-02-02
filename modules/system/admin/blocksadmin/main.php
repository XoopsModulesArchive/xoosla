<?php
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
if ( !is_object( $xoopsUser ) || !is_object( $xoopsModule ) || !$xoopsUser->isAdmin( $xoopsModule->mid() ) ) {
	exit( "Access Denied" );
}
include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
include XOOPS_ROOT_PATH . '/modules/system/admin/blocksadmin/blocksadmin.php';

$op = "list";
if ( isset( $_POST ) ) {
	foreach ( $_POST as $k => $v ) {
		$$k = $v;
	}
}

if ( isset( $_GET['op'] ) ) {
	if ( $_GET['op'] == "edit" || $_GET['op'] == "add" || $_GET['op'] == "delete" || $_GET['op'] == "delete_ok" || $_GET['op'] == "clone" ) {
		$op = $_GET['op'];
		$bid = isset( $_GET['bid'] ) ? intval( $_GET['bid'] ) : 0;
	}
}

$requests = array( 'selmod', 'selgen', 'selvis', 'selgrp' );
foreach ( $requests as $req ) {
	if ( isset( $_GET[$req] ) ) {
		setcookie( $req, $_GET[$req] );
	}
}

if ( isset( $previewblock ) ) {
	if ( !$GLOBALS['xoopsSecurity']->check() ) {
		redirect_header( "admin.php?fct=blocksadmin", 3, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		exit();
	}
	xoops_cp_header();

	if ( isset( $bid ) ) {
		$block['bid'] = $bid;

		$block['form_title'] = _AM_EDITBLOCK;
		$myblock = new XoopsBlock( $bid );
		$block['name'] = $myblock->getVar( 'name' );
	} else {
		if ( $op == 'save' ) {
			$block['form_title'] = _AM_ADDBLOCK;
		} else {
			$block['form_title'] = _AM_CLONEBLOCK;
		}
		$myblock = new XoopsBlock();
		$myblock->setVar( 'block_type', 'C' );
	}
	$myts = &MyTextSanitizer::getInstance();
	$myblock->setVar( 'title', $myts->stripSlashesGPC( $btitle ) );
	$myblock->setVar( 'content', $myts->stripSlashesGPC( $bcontent ) );
	$dummyhtml = '<html><head><meta http-equiv="content-type" content="text/html; charset=' . _CHARSET . '" />';
	$dummyhtml .= '<meta http-equiv="content-language" content="' . _LANGCODE . '" /><title>' . $xoopsConfig['sitename'] . '</title>';
	$dummyhtml .= '<link rel="stylesheet" type="text/css" media="all" href="' . xoops_getcss( $xoopsConfig['theme_set'] ) . '" />';
	$dummyhtml .= '</head><body><table><tr><th>' . $myblock->getVar( 'title' ) . '</th></tr><tr><td>';
	$dummyhtml .= $myblock->getContent( 'S', $bctype ) . '</td></tr></table></body></html>';

	$block['groups'] = $bgroups;
	$block['edit_form'] = false;
	$block['template'] = '';
	$block['op'] = $op;
	$block['side'] = $bside;
	$block['weight'] = $bweight;
	$block['visible'] = $bvisible;
	$block['name'] = $myblock->getVar( 'name', 'E' );
	$block['title'] = $myblock->getVar( 'title', 'E' );
	$block['content'] = $myblock->getVar( 'content', 'E' );
	$block['modules'] = &$bmodule;
	$block['ctype'] = isset( $bctype ) ? $bctype : $myblock->getVar( 'c_type' );
	$block['is_custom'] = true;
	$block['cachetime'] = intval( $bcachetime );
	echo '<a href="admin.php?fct=blocksadmin">' . _AM_BADMIN . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . $block['form_title'] . '<br /><br />';
	include XOOPS_ROOT_PATH . '/modules/system/admin/blocksadmin/blockform.php';
	$form->display();
	xoops_cp_footer();
	echo '<script type="text/javascript">
    <!--//
    win = openWithSelfMain("", "popup", 250, 200, true);
    win.document.clear();
    ';
	$lines = preg_split( "/(\r\n|\r|\n)( *)/", $dummyhtml );
	foreach ( $lines as $line ) {
		echo 'win.document.writeln("' . str_replace( '"', '\"', $line ) . '");';
	}
	echo '
    win.focus();
    win.document.close();
    //-->
    </script>';
	exit();
}

if ( $op == "list" ) {
	xoops_cp_header();

	// Define main template
	$xoopsOption['template_main'] = 'system_blocks.html';
	// Call Header
	xoops_cp_header();
	// Define Stylesheet
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css' );
	// Define scripts
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.ui.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/admin.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/blocks.js' );
	// Define Breadcrumb and tips
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADMIN, system_adminVersion( 'blocksadmin', 'adminpath' ) );
	$xoBreadCrumb->addHelp( system_adminVersion( 'blocksadmin', 'help' ) );
	$xoBreadCrumb->addTips( sprintf( _AM_SYSTEM_BLOCKS_TIPS, system_AdminIcons( 'block.png' ), system_AdminIcons( 'success.png' ), system_AdminIcons( 'cancel.png' ) ) );
	$xoBreadCrumb->render();
	echo '<div class="floatright">';
	echo '<div class="xo-buttons">';
	echo '<button id="xo-add-btn" class="ui-corner-all" onclick="self.location.href=\'admin.php?fct=blocksadmin&amp;op=add\';">';
	echo '<img src="';

	$argStr = "add.png";
	$icons = xoops_getModuleOption( 'typeicons', 'system' );
	if ( $icons == '' ) $icons = 'default';

	if ( file_exists( $xoops->path( 'modules/system/images/icons/' . $icons . '/index.html' ) ) ) {
		$url = $xoops->url( 'modules/system/images/icons/' . $icons . '/' . $argStr );
	} else {
		if ( file_exists( $xoops->path( 'modules/system/images/icons/default/' . $argStr ) ) ) {
			$url = $xoops->url( 'modules/system/images/icons/default/' . $argStr );
		} else {
			$url = $xoops->url( 'modules/system/images/icons/default/xoops/xoops.png' );
		}
	}
	echo addslashes( $url );
	// <{xoAdminIcons add.png}>
	echo '" alt="' . _AM_SYSTEM_BLOCKS_ADD . '" />';
	echo _AM_SYSTEM_BLOCKS_ADD;
	echo '</button>';
	echo '</div>';
	echo '</div>';
	echo '<div class="clear"></div>';
	list_blocks();
	xoops_cp_footer();
	exit();
}

if ( $op == "order" ) {
	if ( !$GLOBALS['xoopsSecurity']->check() ) {
		redirect_header( "admin.php?fct=blocksadmin", 3, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		exit();
	}

	$key = 0 ;
	$tmpoldbmodule = array();
	foreach ( $oldbmodule as $key => $eachBmodule ) {
		$tmpoldbmodule[$key] = @explode( ',', $eachBmodule );
	}
	unset( $oldbmodule );
	$oldbmodule = $tmpoldbmodule;
	unset( $tmpoldbmodule );

	foreach ( array_keys( $bid ) as $i ) {
		$isChange = 0 ;
		$list = array( 'name', 'title', 'weight', 'visible', 'side', 'bcachetime', 'bmodule' );
		foreach ( $list as $each ) {
			if ( isset( ${$each}[$i] ) && is_array( ${$each}[$i] ) ) {
				if ( count( ${$each}[$i] ) != count( ${'old' . $each}[$i] ) ) {
					$isChange = 1;
				} else if ( isset( ${$each}[$i] ) && array_diff( ${$each}[$i], ${'old' . $each}[$i] ) ) {
					$isChange = 1;
				}
			} else if ( isset( ${'old' . $each}[$i] ) && trim( ${'old' . $each}[$i] ) != trim( ${$each}[$i] ) ) {
				$isChange = 1;
			}
		}
		if ( $isChange == 1 ) {
			order_block( $bid[$i], $weight[$i], $visible[$i], $side[$i], $name[$i], $title[$i], $bmodule[$i], $bcachetime[$i] );
		}
	}
redirect_header( "admin.php?fct=blocksadmin", 1, _AM_DBUPDATED );
	exit();
}

if ( $op == "save" ) {
	if ( !$GLOBALS['xoopsSecurity']->check() ) {
		redirect_header( "admin.php?fct=blocksadmin", 3, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		exit();
	}
	$bgroups = isset( $bgroups ) ? $bgroups : array();
	save_block( $bside, $bweight, $bvisible, $bname, $btitle, $bdescription, $bcontent, $bctype, $bmodule, $bcachetime, $bgroups );
	exit();
}

if ( $op == "update" ) {
	if ( !$GLOBALS['xoopsSecurity']->check() ) {
		redirect_header( "admin.php?fct=blocksadmin", 3, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		exit();
	}
	$bcachetime = isset( $bcachetime ) ? intval( $bcachetime ) : 0;
	$options = isset( $options ) ? $options : array();
	$bcontent = isset( $bcontent ) ? $bcontent : '';
	$bctype = isset( $bctype ) ? $bctype : '';
	$bgroups = isset( $bgroups ) ? $bgroups : array();
	update_block( $bid, $bside, $bweight, $bvisible, $bname, $btitle, $bdescription, $bcontent, $bctype, $bcachetime, $bmodule, $options, $bgroups );
}

if ( $op == "delete_ok" ) {
	if ( !$GLOBALS['xoopsSecurity']->check() ) {
		redirect_header( "admin.php?fct=blocksadmin", 3, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		exit();
	}
	delete_block_ok( $bid );
	exit();
}

if ( $op == "delete" ) {
	// Define main template
	$xoopsOption['template_main'] = 'system_blocks.html';
	// Call Header
	xoops_cp_header();
	// Define Stylesheet
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css' );
	// Define scripts
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.ui.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/admin.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/blocks.js' );
	// Define Breadcrumb and tips
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADMIN, system_adminVersion( 'blocksadmin', 'adminpath' ) );
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_DELETEBLOCK );
	// $xoBreadCrumb->addHelp( system_adminVersion('blocksadmin', 'help') );
	// $xoBreadCrumb->addTips( sprintf(_AM_SYSTEM_BLOCKS_TIPS, system_AdminIcons('block.png'), system_AdminIcons('success.png'), system_AdminIcons('cancel.png')));
	$xoBreadCrumb->render();
	delete_block( $bid );
	xoops_cp_footer();
	exit();
}

if ( $op == "edit" ) {
	// Define main template
	$xoopsOption['template_main'] = 'system_blocks.html';
	// Call Header
	xoops_cp_header();
	// Define Stylesheet
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css' );
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption( 'jquery_theme', 'system' ) . '/ui.all.css' );
	// Define scripts
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.ui.js' );
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.form.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/admin.js' );
	// Define Breadcrumb and tips
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADMIN, system_adminVersion( 'blocksadmin', 'adminpath' ) );
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_EDITBLOCK );
	$xoBreadCrumb->render();
	edit_block( $bid );
	xoops_cp_footer();
	exit();
}

if ( $op == "add" ) {
	// Define main template
	$xoopsOption['template_main'] = 'system_blocks.html';
	// Call Header
	xoops_cp_header();
	// Define Stylesheet
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css' );
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption( 'jquery_theme', 'system' ) . '/ui.all.css' );
	// Define scripts
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/jquery.js' );
	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.ui.js' );

	$xoTheme->addScript( 'browse.php?Frameworks/jquery/plugins/jquery.form.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/admin.js' );
	$xoTheme->addScript( 'browse.php?modules/system/js/blocks.js' );
	// Define Breadcrumb and tips
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADMIN, system_adminVersion( 'blocksadmin', 'adminpath' ) );
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADDBLOCK );
	$xoBreadCrumb->render();

	$block = array( 'form_title' => _AM_ADDBLOCK, 'side' => 0, 'weight' => 0, 'visible' => 1, 'title' => '', 'description' => '', 'content' => '', 'modules' => array( - 1 ), 'is_custom' => true, 'ctype' => 'H', 'cachetime' => 0, 'op' => 'save', 'edit_form' => false, 'groups' => array( XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS ) );
	include XOOPS_ROOT_PATH . '/modules/system/admin/blocksadmin/blockform.php';
	$form->display();
	xoops_cp_footer();
	exit();
}

if ( $op == 'clone' ) {
	// Define main template
	$xoopsOption['template_main'] = 'system_blocks.html';
	// Call Header
	xoops_cp_header();
	// Define Stylesheet
	$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css' );
	// Define Breadcrumb and tips
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_ADMIN, system_adminVersion( 'blocksadmin', 'adminpath' ) );
	$xoBreadCrumb->addLink( _AM_SYSTEM_BLOCKS_CLONEBLOCK );
	$xoBreadCrumb->render();

	clone_block( $bid );
}

if ( $op == 'clone_ok' ) {
	$options = isset( $options ) ? $options : array();
	$bcontent = isset( $bcontent ) ? $bcontent : '';
	$bgroups = isset( $bgroups ) ? $bgroups : array();
	clone_block_ok( $bid, $bside, $bweight, $bvisible, $btitle, $bdescription, $bcontent, $bcachetime, $bmodule, $options, $bgroups );
}

?>