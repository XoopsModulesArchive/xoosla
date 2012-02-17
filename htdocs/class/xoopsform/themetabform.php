<?php
// $Id: themeform.php 1237 2008-01-08 23:02:27Z phppp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );
/**
 *
 * @package kernel
 * @subpackage form
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
/**
 * base class
 */
include_once XOOPS_ROOT_PATH . '/class/xoopsform/form.php';

/**
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 * @subpackage form
 */
class XoopsThemeTabForm extends XoopsThemeForm
{
    /**
     * XoopsThemeTabForm::insertSplit()
     *
     * @param string $extra
     * @return
     */
    function insertSplit( $extra = '' )
    {
        $ret = "<tr valign='top' align='left'>"
         . "  <td class='foot' width='35%' colspan='2'>"
         . "&nbsp;</td>"
         . " </tr>\n"
         . "</table>\n"
         . "<br />\n"
         . "<table width='100%' cellspacing='1'>\n"
         . " <tr valign='top' align='left'>"
         . "  <th width='35%' colspan='2'>"
         . "$extra</th>"
         . " </tr>\n";
        $this->addElement( $ret );
    }

    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * @return string
     */
    function render()
    {
        $ele_name = $this->getName();
        $ret = '<h3>' . $this->getTitle() . '</h3>';
        $ret .= "<form name='" . $ele_name . "' id='" . $ele_name . "' action='" . $this->getAction() . "' method='" . $this->getMethod() . "' onsubmit='return xoopsFormValidate_" . $ele_name . "();'" . $this->getExtra() . ">";
        $ret .= '<div style="text-align: right; padding-right: 20px;"><input type="button" class="formbutton"  name="cancel"  id="cancel" value="' . _CANCEL . '" onClick="history.go(-1);return true;" />
			<input type="reset" class="formbutton"  name="reset"  id="reset" value="' . _RESET . '"  />
			<input type="submit" class="formbutton"  name="submit"  id="submit" value="Submit" /></div>';
        $ret .= "<table width='100%' cellspacing='1'><tr><td colspan=\"2\">\n";
        $ret .= $this->_tabs->startPane( 'tab_' . $this->getTitle() );
        $hidden = '';
        $class = 'even';
        foreach ( $this->getElements() as $ele )
        {
            if ( !is_object( $ele ) )
            {
                $ret .= $ele;
            } elseif ( !$ele->isHidden() )
            {
                if ( !$ele->getNocolspan() )
                {
                    $ret .= "<tr valign='top' align='left'><td class='head' width='35%'>";
                    if ( ( $caption = $ele->getCaption() ) != '' )
                    {
                        $ret .= "<div class='xoops-form-element-caption" . ( $ele->isRequired() ? "-required" : "" ) . "'>"
                         . "<span class='caption-text'>{$caption}</span>"
                         . "<span class='caption-marker'>*</span>" . "</div>";
                    }
                    if ( ( $desc = $ele->getDescription() ) != '' )
                    {
                        $ret .= "<div class='xoops-form-element-help'>{$desc}</div>";
                    }
                    $ret .= "</td><td class='$class'>" . $ele->render() . "</td></tr>\n";
                }
                else
                {
                    $ret .= "<tr valign='top' align='left'><td class='head' colspan='2'>";
                    if ( ( $caption = $ele->getCaption() ) != '' )
                    {
                        $ret .= "<div class='xoops-form-element-caption" . ( $ele->isRequired() ? "-required" : "" ) . "'>"
                         . "<span class='caption-text'>{$caption}</span>"
                         . "<span class='caption-marker'>*</span>" . "</div>";
                    }
                    $ret .= "</td></tr><tr valign='top' align='left'><td class='$class' colspan='2'>" . $ele->render() . "</td></tr>";
                }
            }
            else
            {
                $hidden .= $ele->render();
            }
        }
        $ret .= $this->_tabs->endPane();
        $ret .= "</tr></table>\n$hidden\n</form>\n";
        $ret .= $this->renderValidationJS( true );
        return $ret;
    }

    function startTab( $tabText, $paneid )
    {
        $this->addElement( $this->_tabs->startTab( $tabText, $paneid ) );
    }

    function endTab()
    {
        $this->addElement( $this->_tabs->endTab() );
    }
}

?>