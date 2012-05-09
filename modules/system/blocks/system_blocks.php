<?php
// $Id$
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
 * @package system_blocks.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version system_blocks.php 26 2012-02-17 09:16:15Z catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * b_system_online_show()
 *
 * @return
 */
function b_system_online_show()
{
	global $xoopsUser, $xoopsModule;

	$online_handler = xoops_gethandler( 'online' );

	mt_srand( (double)microtime() * 1000000 );
	// set gc probabillity to 10% for now..
	if ( mt_rand( 1, 100 ) < 11 ) {
		$online_handler->gc( 300 );
	}

	if ( is_object( $xoopsUser ) ) {
		$uid = $xoopsUser->getVar( 'uid' );
		$uname = $xoopsUser->getVar( 'uname' );
	} else {
		$uid = 0;
		$uname = '';
	}

	$module = ( is_object( $xoopsModule ) ) ? $xoopsModule->getVar( 'mid' ) : 0;

	$online_handler->write( $uid, $uname, time(), $module, $_SERVER['REMOTE_ADDR'] );

	$onlines = $online_handler->getAll();
	if ( false != $onlines ) {
		$total = count( $onlines );
		$block = array();
		$guests = 0;
		$members = '';
		for ( $i = 0; $i < $total; $i++ ) {
			if ( $onlines[$i]['online_uid'] > 0 ) {
				$members .= ' <a href="' . XOOPS_URL . '/userinfo.php?uid=' . $onlines[$i]['online_uid'] . '" title="' . $onlines[$i]['online_uname'] . '">' . $onlines[$i]['online_uname'] . '</a>,';
			} else {
				$guests++;
			}
		}
		$block['online_total'] = $total;
		if ( is_object( $xoopsModule ) ) {
			$mytotal = $online_handler->getCount( new Criteria( 'online_module', $xoopsModule->getVar( 'mid' ) ) );
			$block['online_total'] = $mytotal;
            $block['online_module'] = $xoopsModule->getVar( 'name' );
		}
		$block['online_names'] = $members;
		$block['online_members'] = $total - $guests;
		$block['online_guests'] = $guests;
		$block['lang_more'] = _MORE;
		return $block;
	} else {
		return false;
	}
}

/**
 * b_system_login_show()
 *
 * @return
 */
function b_system_login_show()
{
	global $xoopsUser, $xoopsConfig;
	if ( !$xoopsUser ) {
		$block = array();
		$block['lang_username'] = _USERNAME;
		$block['unamevalue'] = '';
		$block['lang_password'] = _PASSWORD;
		$block['lang_login'] = _LOGIN;
		$block['lang_lostpass'] = _MB_SYSTEM_LPASS;
		$block['lang_registernow'] = _MB_SYSTEM_RNOW;
		$block['lang_rememberme'] = _MB_SYSTEM_REMEMBERME;
		if ( $xoopsConfig['use_ssl'] == 1 && $xoopsConfig['sslloginlink'] != '' ) {
			$block['sslloginlink'] = "<a href=\"javascript:openWithSelfMain('" . $xoopsConfig['sslloginlink'] . "', 'ssllogin', 300, 200);\">" . _MB_SYSTEM_SECURE . "</a>";
		}
		// elseif ( $xoopsConfig['usercookie'] ) {
		// $block['lang_rememberme'] = _MB_SYSTEM_REMEMBERME;
		// }
		return $block;
	}
	return false;
}

/**
 * b_system_main_show()
 *
 * @return
 */
function b_system_main_show()
{
	global $xoopsUser, $xoopsModule;

    $block = array();
	//$block['lang_home'] = _MB_SYSTEM_HOME;
	//$block['lang_close'] = _CLOSE;

    $module_handler = xoops_gethandler( 'module' );
	$criteria = new CriteriaCompo( new Criteria( 'hasmain', 1 ) );
	$criteria->add( new Criteria( 'isactive', 1 ) );
	$criteria->add( new Criteria( 'weight', 0, '>' ) );
	$modules = $module_handler->getObjects( $criteria, true );
	$moduleperm_handler = xoops_gethandler( 'groupperm' );
	$groups = is_object( $xoopsUser ) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$read_allowed = $moduleperm_handler->getItemIds( 'module_read', $groups );
	foreach ( array_keys( $modules ) as $i ) {
        if ( in_array( $i, $read_allowed ) ) {
			$block['modules'][$i]['name'] = $modules[$i]->getVar( 'name' );
			$block['modules'][$i]['directory'] = $modules[$i]->getVar( 'dirname' );
			$sublinks = $modules[$i]->subLink();
			if ( ( !empty( $xoopsModule ) ) && ( $i == $xoopsModule->getVar( 'mid' ) ) ) {
				$block['modules'][$i]['highlight'] = true;
				$block['nothome'] = true;
			}
			if ( ( !empty( $xoopsModule ) ) && ( $i == $xoopsModule->getVar( 'mid' ) ) ) {
				$block['modules'][$i]['highlight'] = true;
				$block['nothome'] = true;
			}
			if ( ( count( $sublinks ) > 0 ) && ( !empty( $xoopsModule ) ) && ( $i == $xoopsModule->getVar( 'mid' ) ) ) {
				foreach( $sublinks as $sublink ) {
					$block['modules'][$i]['sublinks'][] = array( 'name' => $sublink['name'], 'url' => XOOPS_URL . '/modules/' . $modules[$i]->getVar( 'dirname' ) . '/' . $sublink['url'] );
				}
			} else {
				$block['modules'][$i]['sublinks'] = array();
			}
		}
	}
	return $block;
}

/**
 * b_system_search_show()
 *
 * @return
 */
function b_system_search_show()
{
	$block = array();
	//$block['lang_search'] = _MB_SYSTEM_SEARCH;
	//$block['lang_advsearch'] = _MB_SYSTEM_ADVS;
	return $block;
}

/**
 * b_system_user_show()
 *
 * @return
 */
function b_system_user_show()
{
	global $xoopsUser;

	if ( !is_object( $xoopsUser ) ) {
		return false;
	}

	$block = array();
	$block['lang_youraccount'] = _MB_SYSTEM_VACNT;
	$block['lang_editaccount'] = _MB_SYSTEM_EACNT;
	$block['lang_notifications'] = _MB_SYSTEM_NOTIF;
	$block['uid'] = $xoopsUser->getVar( 'uid' );
	$block['lang_logout'] = _MB_SYSTEM_LOUT;
	$criteria = new CriteriaCompo( new Criteria( 'read_msg', 0 ) );
	$criteria->add( new Criteria( 'to_userid', $xoopsUser->getVar( 'uid' ) ) );
	// $pm_handler = xoops_gethandler( 'privmessage' );
	// $xoopsPreload = XoopsPreload::getInstance();
	// $xoopsPreload->triggerEvent( 'system.blocks.system_blocks.usershow', array( &$pm_handler ) );
	// $block['new_messages'] = $pm_handler->getCount( $criteria );
	$block['lang_inbox'] = _MB_SYSTEM_INBOX;
	$block['lang_adminmenu'] = _MB_SYSTEM_ADMENU;
	return $block;
}

/**
 * b_system_info_show()
 *
 * @param mixed $options
 * @return
 */
function b_system_info_show( $options )
{
	global $xoopsConfig, $xoopsUser;

    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
	$myts = MyTextSanitizer::getInstance();
	$block = array();
	if ( !empty( $options[3] ) ) {
		$block['showgroups'] = true;
		$result = $xoopsDB->query(
			'SELECT u.uid, u.uname, u.email, u.user_viewemail, u.user_avatar, g.name AS groupname
				FROM ' . $xoopsDB->prefix( 'groups_users_link' ) . ' l
				LEFT JOIN ' . $xoopsDB->prefix( 'users' ) . ' u ON l.uid=u.uid
				LEFT JOIN ' . $xoopsDB->prefix( 'groups' ) . ' g ON l.groupid=g.groupid
			WHERE g.group_type=\'Admin\'
				ORDER BY l.groupid, u.uid' );
		if ( $xoopsDB->getRowsNum( $result ) > 0 ) {
			$prev_caption = '';
			$i = 0;
			while ( $userinfo = $xoopsDB->fetchArray( $result ) ) {
				if ( $prev_caption != $userinfo['groupname'] ) {
					$prev_caption = $userinfo['groupname'];
					$block['groups'][$i]['name'] = $myts->htmlSpecialChars( $userinfo['groupname'] );
				}
				if ( isset( $xoopsUser ) && is_object( $xoopsUser ) ) {
					$block['groups'][$i]['users'][] = array( 'id' => $userinfo['uid'], 'name' => $myts->htmlspecialchars( $userinfo['uname'] ), 'msglink' => "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/pmlite.php?send2=1&amp;to_userid=" . $userinfo['uid'] . "','pmlite',450,370);\"><img src=\"" . XOOPS_URL . "/images/icons/email.png\" border=\"0\" width=\"24\" height=\"24\" alt=\"\" /></a>", 'avatar' => XOOPS_UPLOAD_URL . '/' . $userinfo['user_avatar'] );
				} else {
					if ( $userinfo['user_viewemail'] ) {
						$block['groups'][$i]['users'][] = array( 'id' => $userinfo['uid'], 'name' => $myts->htmlspecialchars( $userinfo['uname'] ), 'msglink' => '<a href="mailto:' . $userinfo['email'] . '"><img src="' . XOOPS_URL . '/images/icons/email.png" border="0" width="24" height="24" alt="" /></a>', 'avatar' => XOOPS_UPLOAD_URL . '/' . $userinfo['user_avatar'] );
					} else {
						$block['groups'][$i]['users'][] = array( 'id' => $userinfo['uid'], 'name' => $myts->htmlspecialchars( $userinfo['uname'] ), 'msglink' => '&nbsp;', 'avatar' => XOOPS_UPLOAD_URL . '/' . $userinfo['user_avatar'] );
					}
				}
				$i++;
			}
		}
	} else {
		$block['showgroups'] = false;
	}
	$block['logourl'] = XOOPS_URL . '/images/' . $options[2];
	$block['recommendlink'] = "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/misc.php?action=showpopups&amp;type=friend&amp;op=sendform&amp;t=" . time() . "','friend'," . $options[0] . "," . $options[1] . ")\">" . _MB_SYSTEM_RECO . "</a>";
	return $block;
}

/**
 * b_system_newmembers_show()
 *
 * @param mixed $options
 * @return
 */
function b_system_newmembers_show( $options )
{
	$block = array();
	$criteria = new CriteriaCompo( new Criteria( 'level', 0, '>' ) );
	$limit = ( !empty( $options[0] ) ) ? $options[0] : 10;
	$criteria->setOrder( 'DESC' );
	$criteria->setSort( 'user_regdate' );
	$criteria->setLimit( $limit );
	$member_handler = xoops_gethandler( 'member' );
	$newmembers = $member_handler->getUsers( $criteria );
	$count = count( $newmembers );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $options[1] == 1 ) {
			$block['users'][$i]['avatar'] = $newmembers[$i]->getVar( 'user_avatar' ) != 'blank.gif' ? XOOPS_UPLOAD_URL . '/' . $newmembers[$i]->getVar( 'user_avatar' ) : '';
		} else {
			$block['users'][$i]['avatar'] = '';
		}
		$block['users'][$i]['id'] = $newmembers[$i]->getVar( 'uid' );
		$block['users'][$i]['name'] = $newmembers[$i]->getVar( 'uname' );
		$block['users'][$i]['joindate'] = formatTimestamp( $newmembers[$i]->getVar( 'user_regdate' ), 's' );
	}
	return $block;
}

/**
 * b_system_topposters_show()
 *
 * @param mixed $options
 * @return
 */
function b_system_topposters_show( $options )
{
	$block = array();
	$criteria = new CriteriaCompo( new Criteria( 'level', 0, '>' ) );
	$limit = ( !empty( $options[0] ) ) ? $options[0] : 10;
	$size = count( $options );
	for ( $i = 2; $i < $size; $i++ ) {
		$criteria->add( new Criteria( 'rank', $options[$i], '<>' ) );
	}
	$criteria->setOrder( 'DESC' );
	$criteria->setSort( 'posts' );
	$criteria->setLimit( $limit );
	$member_handler = xoops_gethandler( 'member' );
	$topposters = $member_handler->getUsers( $criteria );
	$count = count( $topposters );
	for ( $i = 0; $i < $count; $i++ ) {
		$block['users'][$i]['rank'] = $i + 1;
		if ( $options[1] == 1 ) {
			$block['users'][$i]['avatar'] = $topposters[$i]->getVar( 'user_avatar' ) != 'blank.gif' ? XOOPS_UPLOAD_URL . '/' . $topposters[$i]->getVar( 'user_avatar' ) : '';
		} else {
			$block['users'][$i]['avatar'] = '';
		}
		$block['users'][$i]['id'] = $topposters[$i]->getVar( 'uid' );
		$block['users'][$i]['name'] = $topposters[$i]->getVar( 'uname' );
		$block['users'][$i]['posts'] = $topposters[$i]->getVar( 'posts' );
	}
	return $block;
}

/**
 * b_system_comments_show()
 *
 * @param mixed $options
 * @return
 */
function b_system_comments_show( $options )
{
	$block = array();
	include_once XOOPS_ROOT_PATH . '/include/comment/comment_constants.php';

    $comment_handler = xoops_gethandler( 'comment' );
	$criteria = new CriteriaCompo( new Criteria( 'com_status', XOOPS_COMMENT_ACTIVE ) );
	$criteria->setLimit( intval( $options[0] ) );
	$criteria->setSort( 'com_created' );
	$criteria->setOrder( 'DESC' );

    // Check modules permissions
	global $xoopsUser;
	$moduleperm_handler = xoops_gethandler( 'groupperm' );
	$gperm_groupid = is_object( $xoopsUser ) ? $xoopsUser->getGroups() : array( XOOPS_GROUP_ANONYMOUS );
	$criteria1 = new CriteriaCompo( new Criteria( 'gperm_name', 'module_read', '=' ) );
	$criteria1->add( new Criteria( 'gperm_groupid', '(' . implode( ',', $gperm_groupid ) . ')', 'IN' ) );
	$perms = $moduleperm_handler->getObjects( $criteria1, true );
	$modIds = array();
	foreach( $perms as $item ) {
		$modIds[] = $item->getVar( 'gperm_itemid' );
	}
	if ( count( $modIds ) > 0 ) {
		$modIds = array_unique( $modIds );
		$criteria->add( new Criteria( 'com_modid', '(' . implode( ',', $modIds ) . ')', 'IN' ) );
	}
	// Check modules permissions
	$comments = $comment_handler->getObjects( $criteria, true );
	$member_handler = xoops_gethandler( 'member' );
	$module_handler = xoops_gethandler( 'module' );
	$modules = $module_handler->getObjects( new Criteria( 'hascomments', 1 ), true );
	$comment_config = array();
	foreach ( array_keys( $comments ) as $i ) {
		$mid = $comments[$i]->getVar( 'com_modid' );
		$com['module'] = '<a href="' . XOOPS_URL . '/modules/' . $modules[$mid]->getVar( 'dirname' ) . '/">' . $modules[$mid]->getVar( 'name' ) . '</a>';
		if ( !isset( $comment_config[$mid] ) ) {
			$comment_config[$mid] = $modules[$mid]->getInfo( 'comments' );
		}
		$com['id'] = $i;
		$com['title'] = '<a href="' . XOOPS_URL . '/modules/' . $modules[$mid]->getVar( 'dirname' ) . '/' . $comment_config[$mid]['pageName'] . '?' . $comment_config[$mid]['itemName'] . '=' . $comments[$i]->getVar( 'com_itemid' ) . '&amp;com_id=' . $i . '&amp;com_rootid=' . $comments[$i]->getVar( 'com_rootid' ) . '&amp;' . htmlspecialchars( $comments[$i]->getVar( 'com_exparams' ) ) . '#comment' . $i . '">' . $comments[$i]->getVar( 'com_title' ) . '</a>';
		$com['icon'] = htmlspecialchars( $comments[$i]->getVar( 'com_icon' ), ENT_QUOTES );
		$com['icon'] = ( $com['icon'] != '' ) ? $com['icon'] : 'icon1.gif';
		$com['time'] = formatTimestamp( $comments[$i]->getVar( 'com_created' ), 'm' );
		if ( $comments[$i]->getVar( 'com_uid' ) > 0 ) {
			$poster = $member_handler->getUser( $comments[$i]->getVar( 'com_uid' ) );
			if ( is_object( $poster ) ) {
				$com['poster'] = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $comments[$i]->getVar( 'com_uid' ) . '">' . $poster->getVar( 'uname' ) . '</a>';
			} else {
				$com['poster'] = $GLOBALS['xoopsConfig']['anonymous'];
			}
		} else {
			$com['poster'] = $GLOBALS['xoopsConfig']['anonymous'];
		}
		$block['comments'][] = $com;
		unset( $com );
	}
	return $block;
}

/**
 * b_system_notification_show()
 *
 * @return
 */
function b_system_notification_show()
{
	global $xoopsConfig, $xoopsUser, $xoopsModule;

	include_once XOOPS_ROOT_PATH . '/include/notification/notification_functions.php';

	xoops_loadLanguage( 'notification' );
	// Notification must be enabled, and user must be logged in
	if ( empty( $xoopsUser ) || !notificationEnabled( 'block' ) ) {
		return false; // do not display block
	}

    $notification_handler = xoops_gethandler( 'notification' );
	// Now build the a nested associative array of info to pass
	// to the block template.
	$block = array();
	$categories = notificationSubscribableCategoryInfo();
	if ( empty( $categories ) ) {
		return false;
	}
	foreach ( $categories as $category ) {
		$section['name'] = $category['name'];
		$section['title'] = $category['title'];
		$section['description'] = $category['description'];
		$section['itemid'] = $category['item_id'];
		$section['events'] = array();
		$subscribed_events = $notification_handler->getSubscribedEvents ( $category['name'], $category['item_id'], $xoopsModule->getVar( 'mid' ), $xoopsUser->getVar( 'uid' ) );
		foreach ( notificationEvents( $category['name'], true ) as $event ) {
			if ( !empty( $event['admin_only'] ) && !$xoopsUser->isAdmin( $xoopsModule->getVar( 'mid' ) ) ) {
				continue;
			}
			$subscribed = in_array( $event['name'], $subscribed_events ) ? 1 : 0;
			$section['events'][$event['name']] = array ( 'name' => $event['name'], 'title' => $event['title'], 'caption' => $event['caption'], 'description' => $event['description'], 'subscribed' => $subscribed );
		}
		$block['categories'][$category['name']] = $section;
	}
	// Additional form data
	$block['target_page'] = 'notification_update.php';
	// FIXME: better or more standardized way to do this?
	$script_url = explode( '/', $_SERVER['PHP_SELF'] );
	$script_name = $script_url[count( $script_url ) - 1];
	$block['redirect_script'] = $script_name;
	$block['submit_button'] = _NOT_UPDATENOW;
	$block['notification_token'] = $GLOBALS['xoopsSecurity']->createToken();
	return $block;
}

/**
 * b_system_comments_edit()
 *
 * @param mixed $options
 * @return
 */
function b_system_comments_edit( $options )
{
	$inputtag = '<input type="text" name="options[]" value="' . intval( $options[0] ) . '" />';
	$form = sprintf( _MB_SYSTEM_DISPLAYC, $inputtag );
	return $form;
}

/**
 * b_system_topposters_edit()
 *
 * @param mixed $options
 * @return
 */
function b_system_topposters_edit( $options )
{
	// include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
	$inputtag = '<input type="text" name="options[]" value="' . intval( $options[0] ) . '" />';
	$form = sprintf( _MB_SYSTEM_DISPLAY, $inputtag );
	$form .= '<br />' . _MB_SYSTEM_DISPLAYA . '&nbsp;<input type="radio" id="options[]" name="options[]" value="1"';
	if ( $options[1] == 1 ) {
		$form .= ' checked="checked"';
	}
	$form .= ' />&nbsp;' . _YES . '<input type="radio" id="options[]" name="options[]" value="0"';
	if ( $options[1] == 0 ) {
		$form .= ' checked="checked"';
	}
	$form .= ' />&nbsp;' . _NO . '';
	$form .= '<br />' . _MB_SYSTEM_NODISPGR . '<br /><select id="options[]" name="options[]" multiple="multiple">';
	$ranks = XoopsLists::getUserRankList();
	$size = count( $options );
	foreach ( $ranks as $k => $v ) {
		$sel = '';
		for ( $i = 2; $i < $size; $i++ ) {
			if ( $k == $options[$i] ) {
				$sel = ' selected="selected"';
			}
		}
		$form .= '<option value="' . $k . '"' . $sel . '>' . $v . '</option>';
	}
	$form .= '</select>';
	return $form;
}

/**
 * b_system_newmembers_edit()
 *
 * @param mixed $options
 * @return
 */
function b_system_newmembers_edit( $options )
{
	$inputtag = '<input type="text" name="options[]" value="' . $options[0] . '" />';
	$form = sprintf( _MB_SYSTEM_DISPLAY, $inputtag );
	$form .= '<br />' . _MB_SYSTEM_DISPLAYA . '&nbsp;<input type="radio" id="options[]" name="options[]" value="1"';
	if ( $options[1] == 1 ) {
		$form .= ' checked="checked"';
	}
	$form .= ' />&nbsp;' . _YES . '<input type="radio" id="options[]" name="options[]" value="0"';
	if ( $options[1] == 0 ) {
		$form .= ' checked="checked"';
	}
	$form .= ' />&nbsp;' . _NO . '';
	return $form;
}

/**
 * b_system_info_edit()
 *
 * @param mixed $options
 * @return
 */
function b_system_info_edit( $options )
{
	$form = _MB_SYSTEM_PWWIDTH . '&nbsp;';
	$form .= '<input type="text" name="options[]" value="' . $options[0] . '" />';
	$form .= '<br />' . _MB_SYSTEM_PWHEIGHT . '&nbsp;';
	$form .= '<input type="text" name="options[]" value="' . $options[1] . '" />';
	$form .= '<br />' . sprintf( _MB_SYSTEM_LOGO, XOOPS_URL . '/images/' ) . '&nbsp;';
	$form .= '<input type="text" name="options[]" value="' . $options[2] . '" />';
	$chk = '';
	$form .= '<br />' . _MB_SYSTEM_SADMIN . '&nbsp;';
	if ( $options[3] == 1 ) {
		$chk = ' checked="checked"';
	}
	$form .= '<input type="radio" name="options[3]" value="1"' . $chk . ' />&nbsp;' . _YES . '';
	$chk = '';
	if ( $options[3] == 0 ) {
		$chk = ' checked="checked"';
	}
	$form .= '&nbsp;<input type="radio" name="options[3]" value="0"' . $chk . ' />' . _NO . '';
	return $form;
}

/**
 * b_system_themes_show()
 *
 * @param mixed $options
 * @return
 */
function b_system_themes_show( $options )
{
	global $xoopsConfig;
	$theme_options = '';
	foreach ( $xoopsConfig['theme_set_allowed'] as $theme ) {
		$theme_options .= '<option value="' . $theme . '"';
		if ( $theme == $xoopsConfig['theme_set'] ) {
			$theme_options .= ' selected="selected"';
		}
		$theme_options .= '>' . $theme . '</option>';
	}

	$block = array();
	if ( $options[0] == 1 ) {
		$block['theme_select'] = '
			<img vspace="2" id="xoops_theme_img" src="' . XOOPS_THEME_URL . '/' . $xoopsConfig["theme_set"] . '/screenshot.png" alt="screenshot" width="' . intval( $options[1] ) . '" />
				<br />
					<select id="xoops_theme_select" name="xoops_theme_select" onchange=\'showImgSelected( "xoops_theme_img", "xoops_theme_select", "themes", "/shot.gif", "' . XOOPS_URL . '"); \'>' . $theme_options . '</select>
					<input type="submit" value="' . _GO . '" />';
	} else {
		$block['theme_select'] = '<select name="xoops_theme_select" onchange="submit();" size="3">' . $theme_options . '</select>';
	}
	$block['theme_select'] .= '<br />(' . sprintf( _MB_SYSTEM_NUMTHEME, '<strong>' . count( $xoopsConfig['theme_set_allowed'] ) . '</strong>' ) . ')<br />';
	return $block;
}

/**
 * b_system_themes_edit()
 *
 * @param mixed $options
 * @return
 */
function b_system_themes_edit( $options )
{
	$chk = '';
	$form = _MB_SYSTEM_THSHOW . '&nbsp;';
	if ( $options[0] == 1 ) {
		$chk = ' checked="checked"';
	}
	$form .= '<input type="radio" name="options[0]" value="1"' . $chk . ' />&nbsp;' . _YES;
	$chk = '';
	if ( $options[0] == 0 ) {
		$chk = ' checked="checked"';
	}
	$form .= '&nbsp;<input type="radio" name="options[0]" value="0"' . $chk . ' />' . _NO;
	$form .= '<br />' . _MB_SYSTEM_THWIDTH . '&nbsp;';
	$form .= "<input type='text' name='options[1]' value='" . $options[1] . "' />";
	return $form;
}

?>