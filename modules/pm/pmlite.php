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
 * Private message module
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package pm
 * @since 2.3.0
 * @author Jan Pedersen
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
include_once dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'mainfile.php';

$reply = !empty( $_GET['reply'] ) ? 1 : 0;
$send = !empty( $_GET['send'] ) ? 1 : 0;
$send2 = !empty( $_GET['send2'] ) ? 1 : 0;
$sendmod = !empty( $_POST['sendmod'] ) ? 1 : 0; // send from other modules with post data
$to_userid = isset( $_GET['to_userid'] ) ? intval( $_GET['to_userid'] ) : 0;
$msg_id = isset( $_GET['msg_id'] ) ? intval( $_GET['msg_id'] ) : 0;

if ( empty( $_GET['refresh'] ) && isset( $_POST['op'] ) && $_POST['op'] != 'submit' ) {
	$jump = 'pmlite.php?refresh=' . time();
	if ( $send == 1 ) {
		$jump .= '&amp;send=' . $send;
	} else if ( $send2 == 1 ) {
		$jump .= '&amp;send2=' . $send2 . '&amp;to_userid=' . $to_userid;
	} else if ( $reply == 1 ) {
		$jump .= '&amp;reply=' . $reply . '&amp;msg_id=' . $msg_id;
	} else {
	}
	header( 'location: ' . $jump );
	exit();
}

if ( !is_object( $GLOBALS['xoopsUser'] ) ) {
	redirect_header( XOOPS_URL, 1, _NOPERM );
	exit();
}

$xoBreadcrumbs = array();
$xoBreadcrumbs[] = array( 'title' => _HOME, 'link' => XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar( 'dirname', 'n' ) . '/' );
// xoops_header();
// $xoopsConfig['module_cache'] = 0;
$myts = MyTextSanitizer::getInstance();
if ( isset( $_POST['op'] ) && $_POST['op'] == 'submit' ) {
	$xoopsOption['template_main'] = 'pm_pmerror.html';
	include $GLOBALS['xoops']->path( 'header.php' );

	$member_handler = xoops_gethandler( 'member' );
	$count = $member_handler->getUserCount( new Criteria( 'uid', intval( $_POST['to_userid'] ) ) );
	if ( $count != 1 ) {
		$msg = '<h4>' . _PM_USERNOEXIST . '</h4>';
		$msg .= '<div>' . _PM_PLZTRYAGAIN . '</div><br />';
	} else if ( $GLOBALS['xoopsSecurity']->check() ) {
		$pm_handler = xoops_getModuleHandler( 'message', 'pm' );
		$pm = $pm_handler->create();
		$pm->setVar( 'msg_time', time() );
		if ( isset( $_POST['icon'] ) ) {
			$pm->setVar( 'msg_image', $_POST['icon'] );
		}
		$pm->setVar( 'subject', $_POST['subject'] );
		$pm->setVar( 'msg_text', $_POST['message'] );
		$pm->setVar( 'to_userid', $_POST['to_userid'] );
		$pm->setVar( 'from_userid', $GLOBALS['xoopsUser']->getVar( 'uid' ) );
		if ( isset( $_REQUEST['savecopy'] ) && $_REQUEST['savecopy'] == 1 ) {
			// PMs are by default not saved in outbox
			$pm->setVar( 'from_delete', 0 );
		}

		if ( !$pm_handler->insert( $pm ) ) {
			$msg = $pm->getHtmlErrors();
		} else {
			//  @todo : Send notification email if user has selected this in the profile
			$msg = '<h4>' . _PM_MESSAGEPOSTED . '</h4>';
		}
	} else {
		$msg = implode( '<br />', $GLOBALS['xoopsSecurity']->getErrors() );
		// $msg .= '<br /><a href="javascript:window.close();">' . _PM_ORCLOSEWINDOW . '</a>';
	}
    $msg .= '<div class="txtcenter">[ <a href="javascript:history.go(-1)">' . _PM_GOBACK . '</a> ]</div>';
	$GLOBALS['xoopsTpl']->assign( 'msg', $msg );
	// $xoBreadcrumbs[] = array( 'title' => _PM_EDIT_MESSAGE );
} else if ( $reply == 1 || $send == 1 || $send2 == 1 || $sendmod == 1 ) {
	$xoopsOption['template_main'] = 'pm_pmlite.html';
	include $GLOBALS['xoops']->path( 'header.php' );
	if ( $reply == 1 ) {
		$pm_handler = xoops_getModuleHandler( 'message', 'pm' );
		$pm = $pm_handler->get( $msg_id );
		if ( $pm->getVar( 'to_userid' ) == $GLOBALS['xoopsUser']->getVar( 'uid' ) ) {
			$pm_uname = XoopsUser::getUnameFromId( $pm->getVar( 'from_userid' ) );
			$message = '[quote]' . NWLINE;
			$message .= sprintf( _PM_USERWROTE , $pm_uname );
			$message .= NWLINE . $pm->getVar( 'msg_text', 'E' ) . NWLINE . '[/quote]';
		} else {
			unset( $pm );
			$reply = $send2 = 0;
		}
	}

	$pmform = new XoopsThemeForm( _PM_EDIT_MESSAGE, 'pmform', 'pmlite.php', 'post', true );

	if ( $reply == 1 ) {
		$subject = $pm->getVar( 'subject', 'E' );
		if ( !preg_match( '/^' . _RE . '/i', $subject ) ) {
			$subject = _RE . ' ' . $subject;
		}
		$GLOBALS['xoopsTpl']->assign( 'to_username', $pm_uname );
		$pmform->addElement( new XoopsFormHidden( 'to_userid', $pm->getVar( 'from_userid' ) ) );
	} else if ( $sendmod == 1 ) {
		$GLOBALS['xoopsTpl']->assign( 'to_username', XoopsUser::getUnameFromId( $_POST['to_userid'] ) );
		$pmform->addElement( new XoopsFormHidden( 'to_userid', $_POST['to_userid'] ) );
		$subject = $myts->htmlSpecialChars( $myts->stripSlashesGPC( $_POST['subject'] ) );
		$message = $myts->htmlSpecialChars( $myts->stripSlashesGPC( $_POST['message'] ) );
	} else {
		if ( $send2 == 1 ) {
			$GLOBALS['xoopsTpl']->assign( 'to_username', XoopsUser::getUnameFromId( $to_userid, false ) );
			$pmform->addElement( new XoopsFormHidden( 'to_userid', $to_userid ) );
		} else {
			$to_username = new XoopsFormSelectUser( '', 'to_userid' );
			$GLOBALS['xoopsTpl']->assign( 'to_username', $to_username->render() );
		}
		$subject = '';
		$message = '';
	}
	$pmform->addElement( new XoopsFormText( '', 'subject', 30, 100, $subject ), true );
	// $msg_image = '';
	// $icons_radio = new XoopsFormRadio( _MESSAGEICON, 'msg_image', $msg_image );
	$subject_icons = XoopsLists::getSubjectsList();

	$xoopsTpl->assign( 'radio_icons', $subject_icons );
	$pmform->addElement( new XoopsFormDhtmlTextArea( '', 'message', $message, 8, 37 ), true );
	$pmform->addElement( new XoopsFormRadioYN( '', 'savecopy', 0 ) );
	$pmform->addElement( new XoopsFormHidden( 'op', 'submit' ) );
	$pmform->addElement( new XoopsFormButtonTray( 'submit', _SUBMIT, 'submit' ) );
	$pmform->assign( $GLOBALS['xoopsTpl'] );

	$xoBreadcrumbs[] = array( 'title' => _PM_EDIT_MESSAGE );
	$GLOBALS['xoopsTpl']->assign( 'xoBreadcrumbs', $xoBreadcrumbs );
}

include $GLOBALS['xoops']->path( 'footer.php' );

?>