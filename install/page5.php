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
 * Installer site configuration page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package installer
 * @since 2.3.0
 * @author Haruki Setoyama <haruki@planewave.org>
 * @author Kazumi Ono <webmaster@myweb.ne.jp>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version $Id: page_administration.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

require_once XOOPS_ROOT_PATH . '/install/class/dbmanager.php';

if ( empty( $vars['ROOT_PATH'] ) ) {
	$wizard->redirectToPage( 'page3' );
	exit();
}
if ( empty( $vars['DB_HOST'] ) ) {
	$wizard->redirectToPage( 'page4' );
	exit();
}

$dbm = new db_manager();
if ( !$dbm->isConnectable() ) {
	$wizard->redirectToPage( 'page4' );
	exit();
}

$res = $dbm->query( 'SELECT * FROM ' . $dbm->db->prefix( 'users' ) . ' WHERE uid = 1' );
$count = $dbm->getRowsNum( $res );

if ( !$count ) {
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		$vars['adminname'] = htmlspecialchars( $_POST['adminname'] );
		$vars['adminmail'] = htmlspecialchars( $_POST['adminmail'] );
		$vars['adminpass'] = htmlspecialchars( $_POST['adminpass'] );
		$vars['adminpass2'] = htmlspecialchars( $_POST['adminpass2'] );

		if ( empty( $vars['adminname'] ) ) {
			$errors['adminname'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_LOGON_REQUIRED;
		}
		if ( empty( $vars['adminmail'] ) ) {
			$errors['adminmail'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_EMAIL_REQUIRED;
		}
		if ( empty( $vars['adminpass'] ) ) {
			$errors['adminpass'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_PASS_REQUIRED;
		} else {
			if ( !preg_match( '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $vars['adminmail'] ) ) {
				$errors['email'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_INVALID_EMAIL;
			}
		}
		if ( empty( $vars['adminpass2'] ) ) {
			$errors['adminpass2'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_PASS_REQUIRED2;
		}
		if ( $vars['adminpass'] != $vars['adminpass2'] ) {
			$errors['adminpass2'] = INSTALL_ERR_REQUIRED . INSTALL_ERR_PASSWORD_MATCH;
		}
		if ( empty( $errors ) ) {
			$errors = xoCreateAdminData( $errors, $dbm, $vars['adminname'], $vars['adminmail'], $vars['adminpass'] );
			if ( empty( $errors ) ) {
				$wizard->redirectToPage( '+1' );
				exit();
			}
		}
	}
} else {
	$isadmin = $count;
}
ob_start();

$pageHasForm = true;

xoShowErrors( $errors );
if ( $isadmin ) {
	$pageHasForm = false;
	xoShowNotice( INSTALL_ERR_ADMIN_EXIST );
} else {?>
    <fieldset>
        <legend><?php echo INSTALL_ADMIN_ACCOUNT_LEGEND; ?></legend>
        <div class="message"><?php echo INSTALL_CONFIG_CHECK_MSG; ?></div>
        <?php
	echo '<script type="text/javascript">
                var desc = new Array();
                desc[0] = "' . PASSWORD_DESC . '";
                desc[1] = "' . PASSWORD_VERY_WEAK . '";
                desc[2] = "' . PASSWORD_WEAK . '";
                desc[3] = "' . PASSWORD_BETTER . '";
                desc[4] = "' . PASSWORD_MEDIUM . '";
                desc[5] = "' . PASSWORD_STRONG . '";
                desc[6] = "' . PASSWORD_STRONGEST . '";
        </script>';
	xoFormField( 'adminname', $vars['adminname'], INSTALL_ADMIN_LOGIN_LABEL, INSTALL_ADMIN_LOGIN_LABEL_MSG, issetError( $errors['adminname'] ) );
	xoFormField( 'adminmail', $vars['adminmail'], INSTALL_ADMIN_EMAIL_LABEL, INSTALL_ADMIN_EMAIL_LABEL_MSG, issetError( $errors['adminmail'] ) );
    xoPassField( 'adminpass', $vars['adminpass'], INSTALL_ADMIN_PASS_LABEL, INSTALL_ADMIN_PASS_LABEL_MSG, issetError( $errors['adminpass'] ) );
    xoPassField( 'adminpass2', $vars['adminpass2'], INSTALL_ADMIN_CONFIRMPASS_LABEL, INSTALL_ADMIN_CONFIRMPASS_LABEL_MSG, issetError( $errors['adminpass2'] ) );
	?>
    <div id="password">
    	<div id="passwordinput"></div>
	<?php
	?>
			<div id="passwordmetter" class="xoform-help">
                <label class="xolabel" for='passwordStrength'><?php echo PASSWORD_LABEL; ?></label>
                <div id='passwordStrength' class='strength0'>
                    <span id='passwordDescription'><?php echo PASSWORD_DESC; ?></span>
                </div>
                <label class="xolabel" for='password_generator'><?php echo PASSWORD_GENERATOR; ?></label>
                <div id="passwordgenerator">
                    <input type='text' name='generated_pw' id='generated_pw' value='' size="25"/><br />
                    <button type='button' onclick='javascript:suggestPassword(15);'><?php echo PASSWORD_GENERATE; ?></button>
                    <button type='button' onclick='javascript:suggestPasswordCopy("adminpass");'><?php echo PASSWORD_COPY; ?></button>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
     </fieldset>
<?php
}
$content = ob_get_contents();
ob_end_clean();
include './include/install_tpl.php';

?>