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
 * @package deactivate.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version deactivate.php 00 27/02/2012 07:03 Catzwolf $Id:
 */
include 'header.php';

xoops_cp_header();

if ( !isset( $_REQUEST['uid'] ) ) {
	redirect_header( 'index.php', 1, _PROFILE_AM_NOSELECTION );
}

$member_handler = xoops_gethandler( 'member' );
$user = $member_handler->getUser( $_REQUEST['uid'] );
if ( !$user || $user->isNew() ) {
	redirect_header( 'index.php', 1, _PROFILE_AM_USERDONEXIT );
}

if ( in_array( XOOPS_GROUP_ADMIN, $user->getGroups() ) ) {
	redirect_header( 'index.php', 1, _PROFILE_AM_CANNOTDEACTIVATEWEBMASTERS );
}
$user->setVar( 'level', $_REQUEST['level'] );

if ( $member_handler->insertUser( $user ) ) {
	if ( $_REQUEST['level'] == 1 ) {
		$message = _PROFILE_AM_USER_ACTIVATED;
	} else {
		$message = _PROFILE_AM_USER_DEACTIVATED;
	}
} else {
	if ( $_REQUEST['level'] == 1 ) {
		$message = _PROFILE_AM_USER_NOT_ACTIVATED;
	} else {
		$message = _PROFILE_AM_USER_NOT_DEACTIVATED;
	}
}

redirect_header( '../userinfo.php?uid=' . $user->getVar( 'uid' ), 1, $message );

?>