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
 * @version xoops_version.php 00 27/02/2012 05:07 Catzwolf $Id:
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$modversion['name'] = _AM_SYSTEM_TPLSETS;
$modversion['version'] = '1.0';
$modversion['description'] = _AM_SYSTEM_TPLSETS_DESC;
$modversion['author'] = '';
$modversion['credits'] = 'The XOOPS Project; Maxime Cointin (AKA Kraven30)';
$modversion['help'] = 'page=tplsets';
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = 'tpls.png';
$modversion['hasAdmin'] = 1;
$modversion['adminpath'] = 'admin.php?fct=tplsets';
$modversion['category'] = XOOPS_SYSTEM_TPLSET;

?>