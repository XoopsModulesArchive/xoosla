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
 * @package xoops_version.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version xoops_version.php 00 27/02/2012 05:02 Catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$modversion['name'] = _AM_SYSTEM_PREF;
$modversion['version'] = '1.0';
$modversion['description'] = _AM_SYSTEM_PREF_DESC;
$modversion['author'] = '';
$modversion['credits'] = 'The XOOPS Project; Maxime Cointin (AKA Kraven30), Gregory Mage (AKA Mage)';
$modversion['help'] = 'page=preferences';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'prefs.png';
$modversion['hasAdmin'] = 1;
$modversion['adminpath'] = 'admin.php?fct=preferences';
$modversion['category'] = XOOPS_SYSTEM_PREF;

$modversion['configcat'][SYSTEM_CAT_MAIN] = 'system_main.png';
$modversion['configcat'][SYSTEM_CAT_USER] = 'system_user.png';
$modversion['configcat'][SYSTEM_CAT_META] = 'system_meta.png';
$modversion['configcat'][SYSTEM_CAT_WORD] = 'system_word.png';
$modversion['configcat'][SYSTEM_CAT_SEARCH] = 'system_search.png';
$modversion['configcat'][SYSTEM_CAT_MAIL] = 'system_mail.png';
$modversion['configcat'][SYSTEM_CAT_AUTH] = 'system_auth.png';

?>