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
 * Xoosla xoops_version.php
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package
 * @subpackage
 * @since 1.0.1.0
 * @author John Neill <zaquria@xoosla.com>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

$modversion['name'] = _AM_SYSTEM_MODULES;
$modversion['version'] = '1.0';
$modversion['description'] = _AM_SYSTEM_MODULES_DESC;
$modversion['author'] = '';
$modversion['credits'] = 'The XOOPS Project; Kazumi Ono; Andricq Nicolas (AKA MusS)';
$modversion['help'] = 'page=modulesadmin';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'modules.png';

$modversion['hasAdmin'] = 1;
$modversion['adminpath'] = 'admin.php?fct=modulesadmin';
$modversion['category'] = XOOPS_SYSTEM_MODULE;

?>