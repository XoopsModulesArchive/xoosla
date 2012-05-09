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
 * Xoops MultiMailer Base Class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package Kernel
 * @subpackage mail
 * @since 2.0.0
 * @author Author: Jochen Büînagel (job@buennagel.com)
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * Mailer Class.
 *
 * At the moment, this does nothing but send email through PHP "mail()" function,
 * but it has the ability to do much more.
 *
 * If you have problems sending mail with "mail()", you can edit the member variables
 * to suit your setting. Later this will be possible through the admin panel.
 *
 * @todo Make a page in the admin panel for setting mailer preferences.
 * @package class
 * @subpackage mail
 * @author Jochen Buennagel <job@buennagel.com>
 */
class XoopsMultiMailer extends PHPMailer {
	/**
	 * 'from' address
	 *
	 * @var string
	 */
	public $From = '';

	/**
	 * 'from' name
	 *
	 * @var string
	 */
	public $FromName = '';
	// can be 'smtp', 'sendmail', or 'mail'
	/**
	 * Method to be used when sending the mail.
	 *
	 * This can be:
	 * <li>mail (standard PHP function 'mail()') (default)
	 * <li>smtp    (send through any SMTP server, SMTPAuth is supported.
	 * You must set {@link $Host}, for SMTPAuth also {@link $SMTPAuth},
	 * {@link $Username}, and {@link $Password}.)
	 * <li>sendmail (manually set the path to your sendmail program
	 * to something different than 'mail()' uses in {@link $Sendmail})
	 *
	 * @var string
	 * @access public
	 */
	public $Mailer = 'mail';

	/**
	 * set if $Mailer is 'sendmail'
	 *
	 * Only used if {@link $Mailer} is set to 'sendmail'.
	 * Contains the full path to your sendmail program or replacement.
	 *
	 * @var string
	 * @access public
	 */
	public $Sendmail = '/usr/sbin/sendmail';

	/**
	 * SMTP Host.
	 *
	 * Only used if {@link $Mailer} is set to 'smtp'
	 *
	 * @var string
	 * @access public
	 */
	public $Host = '';

	/**
	 * Does your SMTP host require SMTPAuth authentication?
	 *
	 * @var boolean
	 * @access private
	 */
	public $SMTPAuth = false;

	/**
	 * Username for authentication with your SMTP host.
	 *
	 * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
	 *
	 * @var string
	 * @access public
	 */
	public $Username = '';

	/**
	 * Password for SMTPAuth.
	 *
	 * Only used if {@link $Mailer} is 'smtp' and {@link $SMTPAuth} is TRUE
	 *
	 * @var string
	 * @access public
	 */
	public $Password = '';

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __Construct()
	{
		$config_handler = xoops_gethandler( 'config' );
		$xoopsMailerConfig = $config_handler->getConfigsByCat( XOOPS_CONF_MAILER );
		$this->From = $xoopsMailerConfig['from'];
		if ( $this->From == '' ) {
			$this->From = $GLOBALS['xoopsConfig']['adminmail'];
		}
		$this->Sender = $this->From;
		if ( $xoopsMailerConfig['mailmethod'] == 'smtpauth' ) {
			$this->Mailer = 'smtp';
			$this->SMTPAuth = true;
			// TODO: change value type of xoopsConfig 'smtphost' from array to text
			$this->Host = implode( ';', $xoopsMailerConfig['smtphost'] );
			$this->Username = $xoopsMailerConfig['smtpuser'];
			$this->Password = $xoopsMailerConfig['smtppass'];
		} else {
			$this->Mailer = $xoopsMailerConfig['mailmethod'];
			$this->SMTPAuth = false;
			$this->Sendmail = $xoopsMailerConfig['sendmailpath'];
			$this->Host = implode( ';', $xoopsMailerConfig['smtphost'] );
		}
		$this->CharSet = strtolower( _CHARSET );

		$this->language = $PHPMAILER_LANG;

        $this->SetLanguage( 'en', XOOPS_ROOT_PATH . '/libraries/3rdparty/phpmailer/language/' );
		$this->PluginDir = XOOPS_ROOT_PATH . '/libraries/3rdparty/phpmailer/';
	}

	/**
	 * Formats an address correctly. This overrides the default addr_format method which does not seem to encode $FromName correctly
	 *
	 * @access private
	 * @return string
	 */
	public function AddrFormat( $addr )
	{
		if ( empty( $addr[1] ) ) {
			$formatted = $addr[0];
		} else {
			$formatted = sprintf( '%s <%s>', '=?' . $this->CharSet . '?B?' . base64_encode( $addr[1] ) . '?=', $addr[0] );
		}
		return $formatted;
	}
}

?>