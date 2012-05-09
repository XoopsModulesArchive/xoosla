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
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * {description}
 *
 * @package pm
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 */
class PmMessage extends XoopsObject {
	/**
	 * PmMessage::__construct()
	 */
	public function __construct()
	{
		$this->initVar( 'msg_id', XOBJ_DTYPE_INT, null, false );
		$this->initVar( 'msg_image', XOBJ_DTYPE_OTHER, 'icon1.gif', false, 100 );
		$this->initVar( 'subject', XOBJ_DTYPE_TXTBOX, null, true, 255 );
		$this->initVar( 'from_userid', XOBJ_DTYPE_INT, null, true );
		$this->initVar( 'to_userid', XOBJ_DTYPE_INT, null, true );
		$this->initVar( 'msg_time', XOBJ_DTYPE_INT, time(), false );
		$this->initVar( 'msg_text', XOBJ_DTYPE_TXTAREA, null, true );
		$this->initVar( 'read_msg', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'from_delete', XOBJ_DTYPE_INT, 1, false );
		$this->initVar( 'to_delete', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'from_save', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'to_save', XOBJ_DTYPE_INT, 0, false );
	}

	/**
	 * PmMessage::PmMessage()
	 */
	public function PmMessage()
	{
		$this->__construct();
	}

	/**
	 * PmMessage::Form()
	 *
	 * @return
	 */
	public function pmForm( $msg_id = 0 )
	{
		$op = system_CleanVars( $_REQUEST, 'op', 'inbox', 'string' );
        $msg_id = system_CleanVars( $_REQUEST, 'msg_id', 0 );

        $pmform = new XoopsThemeForm( '', 'pmform', 'readpmsg.php', 'post', true );

		if ( $this->getVar( 'from_userid' ) != $GLOBALS['xoopsUser']->getVar( 'uid' ) ) {
			$reply_button = new XoopsFormButton( '', 'send', _PM_REPLY );
			$reply_button->setExtra( "onclick='javascript:openWithSelfMain(\"" . XOOPS_URL . "/modules/pm/pmlite.php?reply=1&amp;msg_id={$msg_id}\", \"pmlite\", 565,500);'" );
			$pmform->addElement( $reply_button );
		}

		$pmform->addElement( new XoopsFormHidden( 'op', $op ) );
		$pmform->addElement( new XoopsFormButton( '', 'delete', _PM_DELETE, 'submit' ) );
		$pmform->addElement( new XoopsFormButton( '', 'move', ( $op == 'save' ) ? _PM_UNSAVE : _PM_TOSAVE, 'submit' ) );
		$pmform->addElement( new XoopsFormButton( '', 'email', _PM_EMAIL, 'submit' ) );

		$pmform->addElement( new XoopsFormHidden( 'msg_id', $this->getVar( 'msg_id' ) ) );
		$pmform->assign( $GLOBALS['xoopsTpl'] );

		if ( $this->getVar( 'from_userid' ) == $GLOBALS['xoopsUser']->getVar( 'uid' ) ) {
			$poster = new XoopsUser( $this->getVar( 'to_userid' ) );
		} else {
			$poster = new XoopsUser( $this->getVar( 'from_userid' ) );
		}
		if ( !is_object( $poster ) ) {
			$GLOBALS['xoopsTpl']->assign( 'poster', false );
			$GLOBALS['xoopsTpl']->assign( 'anonymous', $GLOBALS['xoopsConfig']['anonymous'] );
		} else {
			$GLOBALS['xoopsTpl']->assign( 'poster', $poster );
		}

		if ( $this->getVar( 'to_userid' ) == $GLOBALS['xoopsUser']->getVar( 'uid' ) && $this->getVar( 'read_msg' ) == 0 ) {
			$pm_handler->setRead( $pm );
		}
		$message = $this->getValues();
		$message['msg_time'] = formatTimestamp( $this->getVar( 'msg_time' ) );

        return $message;
	}
}

/**
 * PmMessageHandler
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
/**
 * PmMessageHandler
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class PmMessageHandler extends XoopsPersistableObjectHandler {
	/**
	 * PmMessageHandler::__construct()
	 *
	 * @param mixed $db
	 */
	public function __construct( &$db )
	{
		parent::__construct( $db, 'priv_msgs', 'PmMessage', 'msg_id', 'subject' );
	}

	/**
	 * PmMessageHandler::getInbox()
	 *
	 * @return
	 */
	public function getInbox( $start = 0 )
	{
		$criteria = new CriteriaCompo( new Criteria( 'to_delete', 0 ) );
		$criteria->add( new Criteria( 'to_userid', $GLOBALS['xoopsUser']->getVar( 'uid' ) ) );
		$criteria->add( new Criteria( 'to_save', 0 ) );

		$criteria->setLimit( 1 );
		$criteria->setStart( $start );
		$criteria->setSort( 'msg_time' );
		$criteria->setOrder( 'DESC' );
		return $this->getObjects( $criteria );
	}

	/**
	 * PmMessageHandler::getSaveBox()
	 *
	 * @param integer $start
	 * @return
	 */
	public function getSaveBox( $start = 0 )
	{
		$crit_to = new CriteriaCompo( new Criteria( 'to_delete', 0 ) );
		$crit_to->add( new Criteria( 'to_save', 1 ) );
		$crit_to->add( new Criteria( 'to_userid', $GLOBALS['xoopsUser']->getVar( 'uid' ) ) );
		$crit_from = new CriteriaCompo( new Criteria( 'from_delete', 0 ) );
		$crit_from->add( new Criteria( 'from_save', 1 ) );
		$crit_from->add( new Criteria( 'from_userid', $GLOBALS['xoopsUser']->getVar( 'uid' ) ) );
		$criteria = new CriteriaCompo( $crit_to );
		$criteria->add( $crit_from, 'OR' );

		$criteria->setLimit( 1 );
		$criteria->setStart( $start );
		$criteria->setSort( 'msg_time' );
		$criteria->setOrder( 'DESC' );
		return $this->getObjects( $criteria );
	}

	/**
	 * PmMessageHandler::outBox()
	 *
	 * @param integer $start
	 * @return
	 */
	public function outBox( $start = 0 )
	{
		$criteria = new CriteriaCompo( new Criteria( 'from_delete', 0 ) );
		$criteria->add( new Criteria( 'from_userid', $GLOBALS['xoopsUser']->getVar( 'uid' ) ) );
		$criteria->add( new Criteria( 'from_save', 0 ) );

		$criteria->setLimit( 1 );
		$criteria->setStart( $start );
		$criteria->setSort( 'msg_time' );
		$criteria->setOrder( 'DESC' );
		return $this->getObjects( $criteria );
	}

	/**
	 * Mark a message as read
	 *
	 * @param object $pm {@link PmMessage} object
	 * @return bool
	 */
	public function setRead( $pm, $val = 1 )
	{
		return $this->updateAll( 'read_msg', intval( $val ), new Criteria( 'msg_id', $pm->getVar( 'msg_id' ) ), true );
	}

	/**
	 * Mark a message as from_delete = 1 or removes it if the recipient has also deleted it
	 *
	 * @param object $pm {@link PmMessage} object
	 * @return bool
	 */
	public function setFromdelete( $pm, $val = 1 )
	{
		if ( $pm->getVar( 'to_delete' ) == 0 ) {
			return $this->updateAll( 'from_delete', intval( $val ), new Criteria( 'msg_id', $pm->getVar( 'msg_id' ) ) );
		} else {
			return parent::delete( $pm );
		}
	}

	/**
	 * Mark a message as to_delete = 1 or removes it if the sender has also deleted it or sent by anonymous
	 *
	 * @param object $pm {@link PmMessage} object
	 * @return bool
	 */
	public function setTodelete( $pm, $val = 1 )
	{
		if ( $pm->getVar( 'from_delete' ) == 0 && $pm->getVar( 'from_userid' ) == 0 ) {
			return $this->updateAll( 'to_delete', intval( $val ), new Criteria( 'msg_id', $pm->getVar( 'msg_id' ) ) );
		} else {
			return parent::delete( $pm );
		}
	}

	/**
	 * Mark a message as from_save = 1
	 *
	 * @param object $pm {@link PmMessage} object
	 * @return bool
	 */
	public function setFromsave( $pm, $val = 1 )
	{
		return $this->updateAll( 'from_save', intval( $val ), new Criteria( 'msg_id', $pm->getVar( 'msg_id' ) ) );
	}

	/**
	 * Mark a message as to_save = 1
	 *
	 * @param object $pm {@link PmMessage} object
	 * @return bool
	 */
	public function setTosave( $pm, $val = 1 )
	{
		return $this->updateAll( 'to_save', intval( $val ), new Criteria( 'msg_id', $pm->getVar( 'msg_id' ) ) );
	}

	/**
	 * get user's message count in savebox
	 *
	 * @param object $user
	 * @return int
	 */
	public function getSavecount( $user = null )
	{
		if ( !is_object( $user ) ) {
			$user = $GLOBALS['xoopsUser'];
		}
		$crit_to = new CriteriaCompo( new Criteria( 'to_delete', 0 ) );
		$crit_to->add( new Criteria( 'to_save', 1 ) );
		$crit_to->add( new Criteria( 'to_userid', $user->getVar( 'uid' ) ) );
		$crit_from = new CriteriaCompo( new Criteria( 'from_delete', 0 ) );
		$crit_from->add( new Criteria( 'from_save', 1 ) );
		$crit_from->add( new Criteria( 'from_userid', $user->getVar( 'uid' ) ) );
		$criteria = new CriteriaCompo( $crit_to );
		$criteria->add( $crit_from, 'OR' );
		return $this->getCount( $criteria );
	}

	/**
	 * Send a message to user's email
	 *
	 * @param object $pm {@link XoopsPrivmessage} object
	 * @param object $user
	 * @return bool
	 */
	public function sendEmail( $pm, $user )
	{
		global $xoopsConfig;

		if ( !is_object( $user ) ) {
			$user = $GLOBALS['xoopsUser'];
		}
		$msg = sprintf( _PM_EMAIL_DESC, $user->getVar( 'uname' ) );
		$msg .= NWLINE . NWLINE;
		$msg .= formatTimestamp( $pm->getVar( 'msg_time' ) );
		$msg .= NWLINE;
		$from = new XoopsUser( $pm->getVar( 'from_userid' ) );
		$to = new XoopsUser( $pm->getVar( 'to_userid' ) );
		$msg .= sprintf( _PM_EMAIL_FROM, $from->getVar( 'uname' ) . ' (' . XOOPS_URL . '/userinfo.php?uid=' . $pm->getVar( 'from_userid' ) . ')' );
		$msg .= NWLINE;
		$msg .= sprintf( _PM_EMAIL_TO, $to->getVar( 'uname' ) . ' (' . XOOPS_URL . '/userinfo.php?uid=' . $pm->getVar( 'to_userid' ) . ')' );
		$msg .= NWLINE;
		$msg .= _PM_EMAIL_MESSAGE . ':' . NWLINE;
		$msg .= NWLINE . $pm->getVar( 'subject' ) . NWLINE;
		$msg .= NWLINE . strip_tags( str_replace( array( '<p>', '</p>', '<br />', '<br />' ), '\n', $pm->getVar( 'msg_text' ) ) ) . NWLINE . NWLINE;
		$msg .= '--------------' . NWLINE;
		$msg .= $xoopsConfig['sitename'] . ': ' . XOOPS_URL . NWLINE;

		$xoopsMailer = xoops_getMailer();
		$xoopsMailer->useMail();
		$xoopsMailer->setToEmails( $user->getVar( 'email' ) );
		$xoopsMailer->setFromEmail( $xoopsConfig['adminmail'] );
		$xoopsMailer->setFromName( $xoopsConfig['sitename'] );
		$xoopsMailer->setSubject( sprintf( _PM_EMAIL_SUBJECT, $pm->getVar( 'subject' ) ) );
		$xoopsMailer->setBody( $msg );
		return $xoopsMailer->send();
	}

	/**
	 * Get {@link XoopsForm} for setting prune criteria
	 *
	 * @return object
	 */
	public function getPruneForm()
	{
		$form = new XoopsThemeForm( _PM_AM_PRUNE, 'form', 'prune.php', 'post', true );
		$form->addElement( new XoopsFormDateTime( _PM_AM_PRUNEAFTER, 'after' ) );
		$form->addElement( new XoopsFormDateTime( _PM_AM_PRUNEBEFORE, 'before' ) );
		$form->addElement( new XoopsFormRadioYN( _PM_AM_ONLYREADMESSAGES, 'onlyread', 1 ) );
		$form->addElement( new XoopsFormRadioYN( _PM_AM_INCLUDESAVE, 'includesave', 0 ) );
		$form->addElement( new XoopsFormRadioYN( _PM_AM_NOTIFYUSERS, 'notifyusers', 0 ) );
		$form->addElement( new XoopsFormHidden( 'op', 'prune' ) );
		$form->addElement( new XoopsFormButton( '', 'submit', _SUBMIT, 'submit' ) );
		return $form;
	}
}

?>