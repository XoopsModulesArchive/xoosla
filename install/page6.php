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
 * Installer final page
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
 * @version $Id: page_end.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

setcookie( 'xo_install_user', '', null, null, null );

$installer_modified = 'install_remove_' . uniqid( mt_rand() );
// register_shutdown_function( 'install_finalize', $installer_modified );
ob_start();

xoShowNotice( sprintf( INSTALL_FINISH_NOTICE, $installer_modified ), 't-warning' );
?>

    <fieldset>
        <legend><?php echo INSTALL_FINISH_YOUR_SITE_LEGEND; ?></legend>
        <div class="message"><?php echo INSTALL_FINISH_YOUR_SITE_MSG; ?></div>

        <div class="clear"></div>
     </fieldset>

    <fieldset>
        <legend><?php echo INSTALL_FINISH_SUPPORT_LEGEND; ?></legend>
        <div class="message"><?php echo INSTALL_FINISH_SUPPORT_MSG; ?></div>

        <div class="clear"></div>
     </fieldset>

     <fieldset>
        <legend><?php echo INSTALL_FINISH_SECURITY_LEGEND; ?></legend>
        <div class="message"><?php echo INSTALL_FINISH_SECURITY_MSG; ?></div>

        <div class="clear"></div>
     </fieldset>

<?php

$content = ob_get_contents();
ob_end_clean();
require_once XOOPS_ROOT_PATH . '/install/include/install_tpl.php';

?>