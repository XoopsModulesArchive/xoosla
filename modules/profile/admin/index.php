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
 * @package index.php
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version index.php 26 2012-02-17 09:16:15Z catzwolf $Id:
 */
include_once 'admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();
// -----------------------
// $xpPartnerHandler =& xoops_getmodulehandler('partners', $xoopsModule->getVar('dirname'));
// $totalPartners = $xpPartnerHandler->getCount();
// $totalNonActivePartners = $xpPartnerHandler->getCount(new Criteria('status', 0, '='));
// $totalActivePartners = $totalPartners - $totalNonActivePartners;
// $indexAdmin->addInfoBox(_MD_XPARTNERS_DASHBOARD);
// $indexAdmin->addInfoBoxLine(_MD_XPARTNERS_DASHBOARD, '<infolabel>' ._MD_XPARTNERS_TOTALACTIVE. '</infolabel>', $totalActivePartners, 'Green');
// $indexAdmin->addInfoBoxLine(_MD_XPARTNERS_DASHBOARD,  '<infolabel>' ._MD_XPARTNERS_TOTALNONACTIVE. '</infolabel>', $totalNonActivePartners, 'Red');
// $indexAdmin->addInfoBoxLine(_MD_XPARTNERS_DASHBOARD,  '<infolabel>' ._MD_XPARTNERS_TOTALPARTNERS. '</infolabel><infotext>', $totalPartners.'</infotext>');
// ----------------------------
echo $indexAdmin->addNavigation( 'index.php' );
echo $indexAdmin->renderIndex();

xoops_cp_footer();

?>