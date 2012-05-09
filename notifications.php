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
 * XOOPS notification
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package core
 * @since 2.0.0
 * @version $Id$
 */
include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'mainfile.php';

xoops_loadLanguage( 'notification' );

if ( !is_object( $xoopsUser ) ) {
	redirect_header( 'index.php', 1, _NOT_NOACCESS );
	exit();
}

$uid = $xoopsUser->getVar( 'uid' );

$op = system_CleanVars ( $_POST, 'op', 'list', 'string' );
$op = system_CleanVars ( $_POST, 'delete', $op, 'string' );
$op = system_CleanVars ( $_POST, 'delete_ok', $op, 'string' );
$op = system_CleanVars ( $_POST, 'cancel', $op, 'string' );

switch ( strtolower( $op ) ) {
	case 'cancel':
		redirect_header( XOOPS_URL );
		break;

	case 'list':
	default:
		$criteria = new Criteria( 'not_uid', $xoopsUser->getVar( 'uid' ) );
		$criteria->setSort( 'not_modid,not_category,not_itemid' );
		$notification_handler = xoops_gethandler( 'notification' );
		$notifications = $notification_handler->getObjects( $criteria );
		// Generate the info for the template
		$module_handler = xoops_gethandler( 'module' );
		include_once $GLOBALS['xoops']->path( 'include/notification/notification_functions.php' );

		$modules = array();
		$prev_modid = - 1;
		$prev_category = - 1;
		$prev_item = - 1;
		foreach ( $notifications as $n ) {
			$modid = $n->getVar( 'not_modid' );
			if ( $modid != $prev_modid ) {
				$prev_modid = $modid;
				$prev_category = - 1;
				$prev_item = - 1;
				$module = $module_handler->get( $modid );
				$modules[$modid] = array(
					'id' => $modid ,
					'name' => $module->getVar( 'name' ) ,
					'categories' => array() );
				// Get the lookup function, if exists
				$not_config = $module->getInfo( 'notification' );
				$lookup_func = '';
				if ( ! empty( $not_config['lookup_file'] ) ) {
					$lookup_file = $GLOBALS['xoops']->path( 'modules/' . $module->getVar( 'dirname' ) . '/' . $not_config['lookup_file'] );
					if ( file_exists( $lookup_file ) ) {
						include_once $lookup_file;
						if ( ! empty( $not_config['lookup_func'] ) && function_exists( $not_config['lookup_func'] ) ) {
							$lookup_func = $not_config['lookup_func'];
						}
					}
				}
			}
			$category = $n->getVar( 'not_category' );
			if ( $category != $prev_category ) {
				$prev_category = $category;
				$prev_item = - 1;
				$category_info = notificationCategoryInfo( $category, $modid );
				$modules[$modid]['categories'][$category] = array(
					'name' => $category ,
					'title' => $category_info['title'] ,
					'items' => array() );
			}
			$item = $n->getVar( 'not_itemid' );
			if ( $item != $prev_item ) {
				$prev_item = $item;
				if ( ! empty( $lookup_func ) ) {
					$item_info = $lookup_func( $category, $item );
				} else {
					$item_info = array(
						'name' => '[' . _NOT_NAMENOTAVAILABLE . ']' ,
						'url' => '' );
				}
				$modules[$modid]['categories'][$category]['items'][$item] = array(
					'id' => $item ,
					'name' => $item_info['name'] ,
					'url' => $item_info['url'] ,
					'notifications' => array() );
			}
			$event_info = notificationEventInfo( $category, $n->getVar( 'not_event' ), $n->getVar( 'not_modid' ) );
			$modules[$modid]['categories'][$category]['items'][$item]['notifications'][] = array(
				'id' => $n->getVar( 'not_id' ) ,
				'module_id' => $n->getVar( 'not_modid' ) ,
				'category' => $n->getVar( 'not_category' ) ,
				'category_title' => $category_info['title'] ,
				'item_id' => $n->getVar( 'not_itemid' ) ,
				'event' => $n->getVar( 'not_event' ) ,
				'event_title' => $event_info['title'] ,
				'user_id' => $n->getVar( 'not_uid' ) );
		}

		$xoopsOption['template_main'] = 'system_notification_list.html';
		include $GLOBALS['xoops']->path( 'header.php' );

		$xoopsTpl->assign( 'modules', $modules );
		$user_info = array( 'uid' => $xoopsUser->getVar( 'uid' ) );
		$xoopsTpl->assign( 'user', $user_info );
		$xoopsTpl->assign( 'notification_token', $GLOBALS['xoopsSecurity']->createToken() );
		include $GLOBALS['xoops']->path( 'footer.php' );
		break;

	case 'delete_ok':
		if ( empty( $_POST['del_not'] ) ) {
			redirect_header( 'notifications.php', 1, _NOT_NOTHINGTODELETE );
		}
		include $GLOBALS['xoops']->path( 'header.php' );
		$hidden_vars = array(
			'uid' => $uid ,
			'delete_ok' => 1 ,
			'del_not' => $_POST['del_not'] );
		echo '<h4>' . _NOT_DELETINGNOTIFICATIONS . '</h4>';
		xoops_confirm( $hidden_vars, xoops_getenv( 'PHP_SELF' ), _NOT_RUSUREDEL );
		include $GLOBALS['xoops']->path( 'footer.php' );
		break;

	case 'delete':
		if ( !$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header( 'notifications.php', 1, implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() ) );
		}
		if ( empty( $_POST['del_not'] ) ) {
			redirect_header( 'notifications.php', 1, _NOT_NOTHINGTODELETE );
		}
		$notification_handler = xoops_gethandler( 'notification' );
		foreach ( $_POST['del_not'] as $n_array ) {
			foreach ( $n_array as $n ) {
				$notification = $notification_handler->get( $n );
				if ( $notification->getVar( 'not_uid' ) == $xoopsUser->getVar( 'uid' ) ) {
					$notification_handler->delete( $notification );
				}
			}
		}
		redirect_header( 'notifications.php', 1, _NOT_DELETESUCCESS );
		break;
}

?>