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
 * Extended User Profile
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package profile
 * @since 2.3.0
 * @author Jan Pedersen
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
// $xoopsOption['pagetype'] = 'user';
include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'header.php';

if ( !$GLOBALS['xoopsUser'] ) {
	redirect_header( XOOPS_URL, 1, _NOPERM );
}

$uid = intval( $_GET['uid'] );
if ( $uid <= 0 ) {
    if ( is_object( $GLOBALS['xoopsUser'] ) ) {
        $uid = $GLOBALS['xoopsUser']->getVar( 'uid' );
    } else {
        redirect_header( XOOPS_URL, 1, _NOPERM );
        exit();
    }
}


$xoopsOption['template_main'] = 'profile_changepass.html';
include $GLOBALS['xoops']->path( 'header.php' );

if ( !isset( $_POST['submit'] ) ) {
	if ( is_object( $GLOBALS['xoopsUser'] ) && $uid == $GLOBALS['xoopsUser']->getVar( 'uid' ) ) {
		$GLOBALS['xoopsTpl']->assign( 'user_ownpage', true );

        // show change password form
		$form = new XoopsThemeForm( _PROFILE_MA_CHANGEPASSWORD, 'form', $_SERVER['REQUEST_URI'], 'post', true );
		$form->addElement( new XoopsFormPassword( _PROFILE_MA_OLDPASSWORD, 'oldpass', 15, 50 ), true );
		$form->addElement( new XoopsFormPassword( _PROFILE_MA_NEWPASSWORD, 'newpass', 15, 50 ), true );
		$form->addElement( new XoopsFormPassword( _US_VERIFYPASS, 'vpass', 15, 50 ), true );
		$form->addElement( new XoopsFormButtonTray( 'submit', _SAVE, 'submit' ) );
		$form->assign( $GLOBALS['xoopsTpl'] );
		$xoBreadcrumbs[] = array( 'title' => _PROFILE_MA_CHANGEPASSWORD );
	}
} else {
	$config_handler = xoops_gethandler( 'config' );
	$GLOBALS['xoopsConfigUser'] = $config_handler->getConfigsByCat( XOOPS_CONF_USER );
	$myts = MyTextSanitizer::getInstance();

	$oldpass = @$myts->stripSlashesGPC( trim( $_POST['oldpass'] ) );
	$password = @$myts->stripSlashesGPC( trim( $_POST['newpass'] ) );
	$vpass = @$myts->stripSlashesGPC( trim( $_POST['vpass'] ) );

	$errors = array();
	if ( md5( $oldpass ) != $GLOBALS['xoopsUser']->getVar( 'pass', 'n' ) ) {
		$errors[] = _PROFILE_MA_WRONGPASSWORD;
	}
	if ( strlen( $password ) < $GLOBALS['xoopsConfigUser']['minpass'] ) {
		$errors[] = sprintf( _US_PWDTOOSHORT, $GLOBALS['xoopsConfigUser']['minpass'] );
	}
	if ( $password != $vpass ) {
		$errors[] = _US_PASSNOTSAME;
	}

	if ( $errors ) {
		$msg = implode( '<br />', $errors );
	} else {
		// update password
		$GLOBALS['xoopsUser']->setVar( 'pass', md5( $password ) );
		$member_handler = xoops_gethandler( 'member' );
		if ( $member_handler->insertUser( $GLOBALS['xoopsUser'] ) ) {
			$msg = _PROFILE_MA_PASSWORDCHANGED;
		} else {
			$msg = _PROFILE_MA_ERRORDURINGSAVE;
		}
	}
	redirect_header( XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar( 'dirname' ) . '/userinfo.php?uid=' . $GLOBALS['xoopsUser']->getVar( 'uid' ), 1, $msg );
}

include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'footer.php';

?>