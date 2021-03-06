<?php
/**
 * Xoosla
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package blocksadmin.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version blocksadmin.php 00 27/02/2012 04:47 Catzwolf $Id:
 */
if ( !is_object( $xoopsUser ) || !is_object( $xoopsModule ) || !$xoopsUser->isAdmin( $xoopsModule->mid() ) ) {
	die( 'Restricted access' );
}

function list_blocks()
{
	global $xoopsUser, $xoopsConfig, $xoopsDB, $xoBreadCrumb;

	$module_handler = xoops_gethandler( 'module' );
	$criteria = new CriteriaCompo( new Criteria( 'hasmain', 1 ) );
	$criteria->add( new Criteria( 'isactive', 1 ) );
	// Modules for blocks to be visible in
	$display_list = $module_handler->getList( $criteria );
	unset( $criteria );
	// Modules generating the blocks
	$generator_list = $module_handler->getList();

	$gen_list[ - 1] = _AM_TYPES;
	$gen_list[0] = _AM_CUSTOM;
	foreach ( $generator_list as $k => $v ) {
		$gen_list[$k] = $v;
	}
	$generator_list = $gen_list;
	unset( $gen_list );
	// for custom blocks
	$requests = array( 'selmod' => - 1,
		'selgen' => - 2,
		'selvis' => - 1,
		'selgrp' => 1 );
	foreach ( $requests as $req => $def ) {
		if ( isset( $_GET[$req] ) ) {
			$ {
				$req} = intval( $_GET[$req] );
		} elseif ( isset( $_COOKIE[$req] ) ) {
			$ {
				$req} = intval( $_COOKIE[$req] );
		} else {
			$ {
				$req} = $def;
		}
	}
	// echo "<h4>" . _AM_BADMIN . "</h4>";
	// For selection of generated by
	echo '<form action="admin.php" method="get">';
	$form = '<select size="1" name="selgen" onchange=\'location="' . XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;selmod='.$selmod.'&amp;selvis='.$selvis.'&amp;selgrp='.$selgrp.'&amp;selgen="+this.options[this.selectedIndex].value\'>';
	foreach ( $generator_list as $k => $v ) {
		$form .= '<option value="' . $k . '"' . ( $k == $selgen ? ' selected="selected"' : '' ) . '>' . $v . '</option>';
	}
	$form .= '</select> ';
	printf( _AM_GENERATOR, $form );
	// For selection of visible in
	$form = '<select size="1" name="selmod" onchange=\'location="' . XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;selgen='.$selgen.'&amp;selvis='.$selvis.'&amp;selgrp='.$selgrp.'&amp;selmod="+this.options[this.selectedIndex].value\'>';
	$toponlyblock = false;
	ksort( $display_list );

	$display_list_spec[ - 2] = _AM_ALL;
	$display_list_spec[ - 1] = _AM_TOPONLY;
	$display_list_spec[0] = _AM_ALLPAGES;
	$display_list_spec[ - 3] = _AM_UNASSIGNED;
	$display_list = $display_list_spec + $display_list;
	foreach ( $display_list as $k => $v ) {
		$form .= '<option value="' . $k . '"' . ( $k == $selmod ? ' selected="selected"' : '' ) . '>' . $v . '</option>';
	}
	$form .= '</select> ';

	printf( _AM_SVISIBLEIN, $form );
	unset( $display_list[ - 2] );
	// For selection of group access
	$member_handler = xoops_gethandler( 'member' );
	$group_list[ - 1] = _AM_ALLGROUPS;
	$groups = $member_handler->getGroupList();
	foreach ( $groups as $k => $v ) {
		$group_list[$k] = $v;
	}
	$group_list[0] = _AM_UNASSIGNED;

	$group_sel = _AM_GROUP . ' <select size="1" name="selgrp" onchange=\'location="' . XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;selgen='.$selgen.'&amp;selvis='.$selvis.'&amp;selmod='.$selmod.'&amp;selgrp="+this.options[this.selectedIndex].value\'>';
	foreach ( $group_list as $k => $v ) {
		$group_sel .= '<option value="' . $k . '"' . ( $k == $selgrp ? ' selected="selected"' : '' ) . '>' . $v . '</option>';
	}
	$group_sel .= '</select> ';
	echo $group_sel;
	// For selection of visiblility
	echo _AM_VISIBLE . ' <select size="1" name="selvis" onchange=\'location="' . XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;selgen='.$selgen.'&amp;selmod='.$selmod.'&amp;selgrp='.$selgrp.'&amp;selvis="+this.options[this.selectedIndex].value\'>';
	echo '<option value="-1"' . ( $selvis == - 1 ? ' selected="selected"' : '' ) . '>' . _AM_TYPES . '</option>';
	echo '<option value="1"' . ( $selvis == 1 ? ' selected="selected"' : '' ) . '>' . _AM_SHOWEN . '</option>';
	echo '<option value="0"' . ( $selvis == 0 ? ' selected="selected"' : '' ) . '>' . _AM_HIDDEN . '</option>';
	echo '</select> <input type="hidden" name="fct" value="blocksadmin" /><input type="submit" value="' . _GO . '" name="selsubmit" />';
	echo '</form><br />';

	echo '<form action="admin.php" name="blockadmin" method="post">
    <table width="100%" class="outer" cellpadding="2" cellspacing="1">
    <tr valign="middle">
    <th width="5%">' . _AM_BID . '</th>
    <th align="left"  width="15%">' . _AM_TITLE . '</th>
    <th align="left"  width="25%">' . _AM_BLKDESC . '</th>
    <th width="10%">' . _AM_MODULE . '</th>
    <th width="10%"  nowrap="nowrap">' . _AM_SIDE . ' ' . _LEFT . '-' . _CENTER . '-' . _RIGHT . '</th>
    <th width="10%">' . _AM_VISIBLEIN . '</th>
    <th width="10%">' . _AM_BCACHETIME . '</th>
    <th width="10%">' . _AM_VISIBLE . '</th>
    <th width="5%">' . _AM_WEIGHT . '</th>
    <th>' . _AM_ACTION . '</th>
    </tr>';

	if ( $selvis == - 1 ) {
		// $selvis = null;
	}

	$order_block = ( isset( $selvis ) ? '' : 'b.visible DESC, ' ) . 'b.side,b.weight,b.bid';
	$vis_block = ( $selvis == - 1 ) ? null : $selvis;
	// $mod_block = ( $selmod == - 2 ) ? null : $selmod;
	$mod_block = $selmod;

	if ( $selgrp == 0 ) {
		// get blocks that are not assigned to any groups
		$block_arr = XoopsBlock::getNonGroupedBlocks( $mod_block, $toponlyblock = false, $vis_block, $order_block );
	} else {
		$grp_block = ( $selgrp == - 1 ) ? null : $selgrp;

		$block_arr = XoopsBlock::getAllByGroupModule( $grp_block, $mod_block, $toponlyblock, $vis_block, $order_block );
	}
	if ( $selgen >= 0 ) {
		foreach ( array_keys( $block_arr ) as $bid ) {
			if ( $block_arr[$bid]->getVar( "mid" ) != $selgen ) {
				unset( $block_arr[$bid] );
			}
		}
	}
	// xoops_result(array_keys($block_arr));
	$block_count = count( $block_arr );
	$class = 'even';

	$block_mod = array();
	if ( $block_count ) {
		$sql = 'SELECT block_id, module_id FROM ' . $xoopsDB->prefix( 'block_module_link' ) . ' WHERE block_id IN (' . implode( ', ', array_keys( $block_arr ) ) . ')';
		$result = $xoopsDB->query( $sql );
		while ( $row = $xoopsDB->fetchArray( $result ) ) {
			$block_mod[$row['block_id']][] = $row['module_id'];
		}
	}
	$bcachetime = array( '0' => _NOCACHE, '30' => sprintf( _SECONDS, 30 ), '60' => _MINUTE, '300' => sprintf( _MINUTES, 5 ), '1800' => sprintf( _MINUTES, 30 ), '3600' => _HOUR, '18000' => sprintf( _HOURS, 5 ), '86400' => _DAY, '259200' => sprintf( _DAYS, 3 ), '604800' => _WEEK, '2592000' => _MONTH ) ;
	foreach ( array_keys( $block_arr ) as $i ) {
		$module_options = '';
		foreach ( $display_list as $key => $mod ) {
			if ( !empty( $block_mod[$i] ) && in_array( $key, $block_mod[$i] ) ) {
				$module_options .= '<option value="'.$key.'" selected="selected">'.$mod.'</a>';
			} else {
				$module_options .= '<option value="'.$key.'">'.$mod.'</a>';
			}
		}
		$cachetime_options = '';
		foreach ( $bcachetime as $key => $cachetime ) {
			if ( $key == $block_arr[$i]->getVar( 'bcachetime' ) ) {
				$cachetime_options .= '<option value="'.$key.'" selected="selected">'.$cachetime.'</option>';
			} else {
				$cachetime_options .= '<option value="'.$key.'">'.$cachetime.'</option>';
			}
		}

		$sel0 = $sel1 = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = $ssel5 = $ssel6 = $ssel7 = "";
		if ( $block_arr[$i]->getVar( 'visible' ) == 1 ) {
			$sel1 = ' checked="checked"';
		} else {
			$sel0 = ' checked="checked"';
		}
		if ( $block_arr[$i]->getVar( 'side' ) == XOOPS_SIDEBLOCK_LEFT ) {
			$ssel0 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_SIDEBLOCK_RIGHT ) {
			$ssel1 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_LEFT ) {
			$ssel2 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_RIGHT ) {
			$ssel4 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_CENTER ) {
			$ssel3 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_BOTTOMLEFT ) {
			$ssel5 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_BOTTOMRIGHT ) {
			$ssel6 = ' checked="checked"';
		} elseif ( $block_arr[$i]->getVar( 'side' ) == XOOPS_CENTERBLOCK_BOTTOM ) {
			$ssel7 = ' checked="checked"';
		}
		$title = $block_arr[$i]->getVar( 'title' );
		$name = $block_arr[$i]->getVar( 'name' );
		// unset( $module_options[-2] );


        echo '<tr valign="top">
        <td class="'.$class.'" align="center">' . $block_arr[$i]->getVar( 'bid' ) . '</td>
		<td class="'.$class.'">
        	<div>' . _AM_TITLE . ': &nbsp;  <input type="text" name=title['.$i.'] value="' . $title . '" size="30" /></div>';
		if ( $block_arr[$i]->isCustom() ) {
			switch ( $block_arr[$i]->getVar( 'ctype' ) ) {
				case 'H':
					$custom = _AM_CUSTOMHTML;
					break;
				case 'P':
					$custom = _AM_CUSTOMPHP;
					break;
				case 'S':
					$custom = _AM_CUSTOMSMILE;
					break;
				default:
					$custom = _AM_CUSTOMNOSMILE;
					break;
			}
			echo '<div>' . _AM_NAME . ': <input type="text" name=name['.$i.'] value="' . $name . '" size="30" /></div>';
			echo '<div class="small blue"><i>' . $custom . '</i></div>';
		} else {
			echo '<div>' . _AM_NAME . ': ' . $name . '</div>';
            echo '<input type="hidden" name="name['.$i.']" value="' . $block_arr[$i]->getVar( 'name' ) . '"/>';
		}

	    echo '<input type="hidden" name="name['.$i.']" value="' . $block_arr[$i]->getVar( 'name' ) . '"/>';
		echo '</td><td class="'.$class.'">' . $block_arr[$i]->getVar( 'description' ) . '</td>
        <td class="'.$class.'" align="center">' . $generator_list[$block_arr[$i]->getVar( 'mid' )] . '</td>
        <td class="'.$class.'" align="center" nowrap="nowrap">
            <div align="center" >
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_LEFT . '"'.$ssel2.' />
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_CENTER . '"'.$ssel3.' />
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_RIGHT . '"'.$ssel4.' />
            </div>
            <div>
                <span style="float:right">
					<input type="radio" name="side['.$i.']" value="' . XOOPS_SIDEBLOCK_RIGHT . '"'.$ssel1.' />
				</span>
                <div align="left"><input type="radio" name="side['.$i.']" value="' . XOOPS_SIDEBLOCK_LEFT . '"'.$ssel0.' /></div>
            </div>
            <div align="center">
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_BOTTOMLEFT . '"'.$ssel5.' />
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_BOTTOM . '"'.$ssel7.' />
                <input type="radio" name="side['.$i.']" value="' . XOOPS_CENTERBLOCK_BOTTOMRIGHT . '"'.$ssel6.' />
            </div>
        </td>
        <td class="'.$class.'" align="center">
            <select name="bmodule['.$i.'][]" size="5" multiple="multiple">'.$module_options.'</select>
        </td>
        <td class="'.$class.'" align="center">
            <select name="bcachetime['.$i.']" size="1">'.$cachetime_options.'</select>
        </td>
        <td class="'.$class.'" align="center" nowrap><input type="radio" name="visible['.$i.']" value="1"'.$sel1.'>' . _YES . '&nbsp;<input type="radio" name="visible['.$i.']" value="0"'.$sel0.'>' . _NO . '</td>
        <td class="'.$class.'" align="center"><input type="text" name="weight['.$i.']" value="' . $block_arr[$i]->getVar( 'weight' ) . '" size="5" maxlength="5" /></td>
        <td class="'.$class.'" align="right"><a href="admin.php?fct=blocksadmin&amp;op=edit&amp;bid=' . $block_arr[$i]->getVar( 'bid' ) . '">' . _EDIT . '</a>';

		echo '<br /><a href="admin.php?fct=blocksadmin&amp;op=clone&amp;bid=' . $block_arr[$i]->getVar( 'bid' ) . '">' . _AM_CLONE . '</a>';
		if ( $block_arr[$i]->getVar( 'block_type' ) != 'S' ) {
			echo '<br /><a href="admin.php?fct=blocksadmin&amp;op=delete&amp;bid=' . $block_arr[$i]->getVar( 'bid' ) . '">' . _DELETE . '</a>';
		}
		echo '
        <input type="hidden" name="oldside['.$i.']" value="' . $block_arr[$i]->getVar( 'side' ) . '" />
        <input type="hidden" name="oldweight['.$i.']" value="' . $block_arr[$i]->getVar( 'weight' ) . '" />
        <input type="hidden" name="oldvisible['.$i.']" value="' . $block_arr[$i]->getVar( 'visible' ) . '" />
        <input type="hidden" name="oldbmodule['.$i.']" value="' . @implode( ",", $block_mod[$i] ) . '"/>
		<input type="hidden" name="oldname['.$i.']" value="' . $block_arr[$i]->getVar( 'name' ) . '"/>
        <input type="hidden" name="oldtitle['.$i.']" value="' . $block_arr[$i]->getVar( 'title' ) . '"/>
        <input type="hidden" name="olddescription['.$i.']" value="' . $block_arr[$i]->getVar( 'description' ) . '"/>
        <input type="hidden" name="oldbcachetime['.$i.']" value="' . $block_arr[$i]->getVar( 'bcachetime' ) . '"/>
        <input type="hidden" name="bid['.$i.']" value="' . $i . '" />
        </td></tr>';
		$class = ( $class == 'even' ) ? 'odd' : 'even';
	}
	echo '<tr><td class="foot" align="center" colspan="10">
    <input type="hidden" name="fct" value="blocksadmin" />
    <input type="hidden" name="op" value="order" />' . $GLOBALS["xoopsSecurity"]->getTokenHTML() . '
    <input type="submit" name="submit" value="' . _SUBMIT . '" />
    </td></tr></table>
    </form>';
	// $block = array('form_title' => _AM_ADDBLOCK, 'side' => 0, 'weight' => 0, 'visible' => 1, 'title' => '', 'content' => '', 'modules' => array(-1), 'is_custom' => true, 'ctype' => 'H', 'cachetime' => 0, 'op' => 'save', 'edit_form' => false, 'groups' => array(XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS));
	// include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
	// $form->display();
}

function save_block( $bside, $bweight, $bvisible, $bname, $btitle, $bdescription, $bcontent, $bctype, $bmodule, $bcachetime, $bgroups )
{
	global $xoopsUser;

	if ( empty( $bmodule ) ) {
		xoops_cp_header();
		xoops_error( sprintf( _AM_NOTSELNG, _AM_VISIBLEIN ) );
		xoops_cp_footer();
		exit();
	}
	$myblock = new XoopsBlock();
	$myblock->setVar( 'side', $bside );
	$myblock->setVar( 'weight', $bweight );
	$myblock->setVar( 'visible', $bvisible );
	$myblock->setVar( 'weight', $bweight );
	$myblock->setVar( 'name', $bname );
	$myblock->setVar( 'title', $btitle );
	$myblock->setVar( 'description', $bdescription );
	$myblock->setVar( 'content', $bcontent );
	$myblock->setVar( 'c_type', $bctype );
	$myblock->setVar( 'block_type', 'C' );
	$myblock->setVar( 'bcachetime', $bcachetime );
	$newid = $myblock->store();

	if ( !$newid ) {
		xoops_cp_header();
		$myblock->getHtmlErrors();
		xoops_cp_footer();
		exit();
	}
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	foreach ( $bmodule as $bmid ) {
		$sql = 'INSERT INTO ' . $db->prefix( 'block_module_link' ) . ' (block_id, module_id) VALUES (' . $newid . ', ' . intval( $bmid ) . ')';
		$db->query( $sql );
	}
	$groupperm_handler = xoops_gethandler( 'groupperm' );
	$groups_with_access = $groupperm_handler->getGroupIds( 'block_read', $newid );
	$removed_groups = array_diff( $groups_with_access, $bgroups );
	if ( count( $removed_groups ) > 0 ) {
		foreach ( $removed_groups as $groupid ) {
			$criteria = new CriteriaCompo( new Criteria( 'gperm_name', 'block_read' ) );
			$criteria->add( new Criteria( 'gperm_groupid', $groupid ) );
			$criteria->add( new Criteria( 'gperm_itemid', $newid ) );
			$criteria->add( new Criteria( 'gperm_modid', 1 ) );
			$perm = $groupperm_handler->getObjects( $criteria );
			if ( isset( $perm[0] ) && is_object( $perm[0] ) ) {
				$groupperm_handler->delete( $perm[0] );
			}
		}
	}
	$new_groups = array_diff( $bgroups, $groups_with_access );
	if ( count( $new_groups ) > 0 ) {
		foreach ( $new_groups as $groupid ) {
			$groupperm_handler->addRight( 'block_read', $newid, $groupid );
		}
	}
	redirect_header( 'admin.php?fct=blocksadmin&amp;selgen=0&amp;t=' . time(), 1, _AM_DBUPDATED );
	exit();
}

/**
 * edit_block()
 *
 * @param mixed $bid
 * @return
 */
function edit_block( $bid )
{
	$myblock = new XoopsBlock( $bid );
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql = 'SELECT module_id FROM ' . $db->prefix( 'block_module_link' ) . ' WHERE block_id=' . intval( $bid );
	$result = $db->query( $sql );
	$modules = array();
	while ( $row = $db->fetchArray( $result ) ) {
		$modules[] = intval( $row['module_id'] );
	}
	$is_custom = $myblock->isCustom();
	$groupperm_handler = xoops_gethandler( 'groupperm' );
	$groups = $groupperm_handler->getGroupIds( "block_read", $bid );
	$block = array( 'form_title' => _AM_EDITBLOCK, 'name' => $myblock->getVar( 'name' ), 'side' => $myblock->getVar( 'side' ), 'weight' => $myblock->getVar( 'weight' ), 'visible' => $myblock->getVar( 'visible' ), 'title' => $myblock->getVar( 'title', 'E' ), 'description' => $myblock->getVar( 'description', 'E' ), 'content' => $myblock->getVar( 'content', 'E' ), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar( 'c_type' ), 'cachetime' => $myblock->getVar( 'bcachetime' ), 'op' => 'update', 'bid' => $myblock->getVar( 'bid' ), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar( 'template' ), 'options' => $myblock->getVar( 'options' ), 'groups' => $groups );
	// echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_EDITBLOCK.'<br /><br />';
	include_once XOOPS_ROOT_PATH . '/modules/system/admin/blocksadmin/blockform.php';
	$form->display();
}

/**
 * update_block()
 *
 * @param mixed $bid
 * @param mixed $bside
 * @param mixed $bweight
 * @param mixed $bvisible
 * @param mixed $btitle
 * @param mixed $bcontent
 * @param mixed $bctype
 * @param mixed $bcachetime
 * @param mixed $bmodule
 * @param array $options
 * @param array $bgroups
 * @return
 */
function update_block( $bid, $bside, $bweight, $bvisible, $bname, $btitle, $bdescription, $bcontent, $bctype, $bcachetime, $bmodule, $options = array(), $bgroups = array() )
{
	global $xoopsConfig;
	if ( empty( $bmodule ) ) {
		xoops_cp_header();
		xoops_error( sprintf( _AM_NOTSELNG, _AM_VISIBLEIN ) );
		xoops_cp_footer();
		exit();
	}
	$myblock = new XoopsBlock( $bid );
	$myblock->setVar( 'side', $bside );
	$myblock->setVar( 'weight', $bweight );
	$myblock->setVar( 'visible', $bvisible );
	$myblock->setVar( 'name', $bname );
	$myblock->setVar( 'title', $btitle );
	$myblock->setVar( 'content', $bcontent );
	$myblock->setVar( 'description', $bdescription );
	$myblock->setVar( 'bcachetime', $bcachetime );
	if ( isset( $options ) ) {
		$options_count = count( $options );
		if ( $options_count > 0 ) {
			// Convert array values to comma-separated
			for ( $i = 0; $i < $options_count; $i++ ) {
				if ( is_array( $options[$i] ) ) {
					$options[$i] = implode( ',', $options[$i] );
				}
			}
			$options = implode( '|', $options );
			$myblock->setVar( 'options', $options );
		}
	}
	if ( $myblock->isCustom() ) {
		$myblock->setVar( 'c_type', $bctype );
	} else {
		$myblock->setVar( 'c_type', 'H' );
	}
	$msg = _AM_DBUPDATED;
	if ( $myblock->store() != false ) {
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$sql = sprintf( "DELETE FROM %s WHERE block_id = %u", $db->prefix( 'block_module_link' ), $bid );
		$db->query( $sql );
		foreach ( $bmodule as $bmid ) {
			$sql = sprintf( "INSERT INTO %s (block_id, module_id) VALUES (%u, %d)", $db->prefix( 'block_module_link' ), $bid, intval( $bmid ) );
			$db->query( $sql );
		}

		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->caching = 2;
		if ( $myblock->getVar( 'template' ) != '' ) {
			if ( $xoopsTpl->is_cached( 'db:' . $myblock->getVar( 'template' ), 'blk_' . $myblock->getVar( 'bid' ) ) ) {
				if ( !$xoopsTpl->clear_cache( 'db:' . $myblock->getVar( 'template' ), 'blk_' . $myblock->getVar( 'bid' ) ) ) {
					$msg = 'Unable to clear cache for block ID ' . $bid;
				}
			}
		} else {
			if ( $xoopsTpl->is_cached( 'db:system_dummy.html', 'blk_' . $bid ) ) {
				if ( !$xoopsTpl->clear_cache( 'db:system_dummy.html', 'blk_' . $bid ) ) {
					$msg = 'Unable to clear cache for block ID ' . $bid;
				}
			}
		}
		$groupperm_handler = xoops_gethandler( 'groupperm' );
		$groups_with_access = $groupperm_handler->getGroupIds( "block_read", $bid );
		$removed_groups = array_diff( $groups_with_access, $bgroups );
		if ( count( $removed_groups ) > 0 ) {
			foreach ( $removed_groups as $groupid ) {
				$criteria = new CriteriaCompo( new Criteria( 'gperm_name', 'block_read' ) );
				$criteria->add( new Criteria( 'gperm_groupid', $groupid ) );
				$criteria->add( new Criteria( 'gperm_itemid', $bid ) );
				$criteria->add( new Criteria( 'gperm_modid', 1 ) );
				$perm = $groupperm_handler->getObjects( $criteria );
				if ( isset( $perm[0] ) && is_object( $perm[0] ) ) {
					$groupperm_handler->delete( $perm[0] );
				}
			}
		}
		$new_groups = array_diff( $bgroups, $groups_with_access );
		if ( count( $new_groups ) > 0 ) {
			foreach ( $new_groups as $groupid ) {
				$groupperm_handler->addRight( "block_read", $bid, $groupid );
			}
		}
	} else {
		$msg = 'Failed update of block. ID:' . $bid;
	}
	redirect_header( 'admin.php?fct=blocksadmin&amp;t=' . time(), 1, $msg );
	exit();
}

/**
 * delete_block()
 *
 * @param mixed $bid
 * @return
 */
function delete_block( $bid )
{
	$myblock = new XoopsBlock( $bid );
	if ( $myblock->getVar( 'block_type' ) == 'S' ) {
		$message = _AM_SYSTEMCANT;
		redirect_header( 'admin.php?fct=blocksadmin', 1, $message );
		exit();
	} elseif ( $myblock->getVar( 'block_type' ) == 'M' ) {
		// Fix for duplicated blocks created in 2.0.9 module update
		// A module block can be deleted if there is more than 1 that
		// has the same func_num/show_func which is mostly likely
		// be the one that was duplicated in 2.0.9
		if ( 1 >= $count = XoopsBlock::countSimilarBlocks( $myblock->getVar( 'mid' ), $myblock->getVar( 'func_num' ), $myblock->getVar( 'show_func' ) ) ) {
			$message = _AM_MODULECANT;
			redirect_header( 'admin.php?fct=blocksadmin', 1, $message );
			exit();
		}
	}
	xoops_confirm( array( 'fct' => 'blocksadmin', 'op' => 'delete_ok', 'bid' => $myblock->getVar( 'bid' ) ), 'admin.php', sprintf( _AM_RUSUREDEL, $myblock->getVar( 'title' ) ) );
}

/**
 * delete_block_ok()
 *
 * @param mixed $bid
 * @return
 */
function delete_block_ok( $bid )
{
	$myblock = new XoopsBlock( $bid );
	$myblock->delete();
	if ( $myblock->getVar( 'template' ) != '' ) {
		$tplfile_handler = xoops_gethandler( 'tplfile' );
		$btemplate = $tplfile_handler->find( $GLOBALS['xoopsConfig']['template_set'], 'block', $bid );
		if ( count( $btemplate ) > 0 ) {
			$tplfile_handler->delete( $btemplate[0] );
		}
	}
	redirect_header( 'admin.php?fct=blocksadmin&amp;t=' . time(), 1, _AM_DBUPDATED );
	exit();
}

/**
 * order_block()
 *
 * @param mixed $bid
 * @param mixed $weight
 * @param mixed $visible
 * @param mixed $side
 * @param mixed $title
 * @param mixed $bmodule
 * @param mixed $bcachetime
 * @return
 */
function order_block( $bid, $weight, $visible, $side , $name, $title, $bmodule, $bcachetime )
{
	$myblock = new XoopsBlock( $bid );
	$myblock->setVar( 'weight', $weight );
	$myblock->setVar( 'visible', $visible );
	$myblock->setVar( 'side', $side );
	$myblock->setVar( 'name', $name );
	$myblock->setVar( 'title', $title );
	$myblock->setVar( 'bcachetime', $bcachetime );
	$myblock->store();

	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql = sprintf( 'DELETE FROM %s WHERE block_id = %u', $db->prefix( 'block_module_link' ), $bid );
	$db->queryF( $sql );
	foreach ( $bmodule as $bmid ) {
		$sql = sprintf( 'INSERT INTO %s (block_id, module_id) VALUES (%u, %d)', $db->prefix( 'block_module_link' ), $bid, intval( $bmid ) );
		$res = $db->queryF( $sql );
		if ( !$res ) {
			echo $db->error();
		}
	}
}

/**
 * clone_block()
 *
 * @param mixed $bid
 * @return
 */
function clone_block( $bid )
{
	global $xoopsConfig;
	$myblock = new XoopsBlock( $bid );
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql = 'SELECT module_id FROM ' . $db->prefix( 'block_module_link' ) . ' WHERE block_id=' . intval( $bid );
	$result = $db->query( $sql );
	$modules = array();
	while ( $row = $db->fetchArray( $result ) ) {
		$modules[] = intval( $row['module_id'] );
	}
	$is_custom = $myblock->isCustom();
	$groupperm_handler = xoops_gethandler( 'groupperm' );
	$groups = $groupperm_handler->getGroupIds( "block_read", $bid );
	$block = array( 'form_title' => _AM_CLONEBLOCK, 'name' => $myblock->getVar( 'name' ), 'title' => $myblock->getVar( 'title' ), 'description' => $myblock->getVar( 'description' ), 'side' => $myblock->getVar( 'side' ), 'weight' => $myblock->getVar( 'weight' ), 'visible' => $myblock->getVar( 'visible' ), 'content' => $myblock->getVar( 'content', 'N' ), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar( 'c_type' ), 'cachetime' => $myblock->getVar( 'bcachetime' ), 'op' => 'clone_ok', 'bid' => $myblock->getVar( 'bid' ), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar( 'template' ), 'options' => $myblock->getVar( 'options' ), 'groups' => $groups );
	// echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_CLONEBLOCK.'<br /><br />';
	include_once XOOPS_ROOT_PATH . '/modules/system/admin/blocksadmin/blockform.php';
	$form->display();
	xoops_cp_footer();
	exit();
}

function clone_block_ok( $bid, $bside, $bweight, $bvisible, $btitle, $bdescription, $bcontent, $bcachetime, $bmodule, $options = array(), $bgroups = array() )
{
	global $xoopsUser;

	$block = new XoopsBlock( $bid );
	$clone = $block->xoopsClone();
	$clone->setVar( 'side', $bside );
	$clone->setVar( 'weight', $bweight );
	$clone->setVar( 'visible', $bvisible );
	$clone->setVar( 'description', $bdescription );
	$clone->setVar( 'content', $bcontent );
	$clone->setVar( 'title', $btitle );
	$clone->setVar( 'bcachetime', $bcachetime );
	if ( isset( $options ) && ( count( $options ) > 0 ) ) {
		$options = implode( '|', $options );
		$clone->setVar( 'options', $options );
	}
	$clone->setVar( 'bid', 0 );
	// Custom block
	if ( $block->isCustom() ) {
		$clone->setVar( 'block_type', 'C' );
		// Clone of system or module block
	} else {
		$clone->setVar( 'block_type', 'D' );
	}
	$newid = $clone->store();
	if ( !$newid ) {
		xoops_cp_header();
		$clone->getHtmlErrors();
		xoops_cp_footer();
		exit();
	}
	if ( $clone->getVar( 'template' ) != '' ) {
		$tplfile_handler = xoops_gethandler( 'tplfile' );
		$btemplate = $tplfile_handler->find( $GLOBALS['xoopsConfig']['template_set'], 'block', $bid );
		if ( count( $btemplate ) > 0 ) {
			$tplclone = $btemplate[0]->xoopsClone();
			$tplclone->setVar( 'tpl_id', 0 );
			$tplclone->setVar( 'tpl_refid', $newid );
			$tplfile_handler->insert( $tplclone );
		}
	}
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	foreach ( @$bmodule as $bmid ) {
		$sql = 'INSERT INTO ' . $db->prefix( 'block_module_link' ) . ' (block_id, module_id) VALUES (' . $newid . ', ' . $bmid . ')';
		$db->query( $sql );
	}
	$groupperm_handler = xoops_gethandler( 'groupperm' );
	foreach ( $bgroups as $groupid ) {
		$groupperm_handler->addRight( 'block_read', $newid, $groupid );
	}
	redirect_header( 'admin.php?fct=blocksadmin&amp;t=' . time(), 1, _AM_DBUPDATED );
}

?>

