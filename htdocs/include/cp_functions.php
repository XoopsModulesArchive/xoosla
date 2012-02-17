<?php
// $Id$
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
define( 'XOOPS_CPFUNC_LOADED', 1 );
/**
 * Will need to move somewhere else, but should do just for now.
 */
$list_array = array( 1 => '1', 2 => '2', 3 => '3', 5 => '5', 10 => '10', 15 => '15', 25 => '25', 50 => '50', 100 => '100', 0 => 'All' );

function xoops_cp_header()
{
    xoops_load( "cpanel", "system" );
    $cpanel = &XoopsSystemCpanel::getInstance();
    $cpanel->gui->header();
}

function xoops_cp_footer()
{
    xoops_load( "cpanel", "system" );
    $cpanel = &XoopsSystemCpanel::getInstance();
    $cpanel->gui->footer();
}
// We need these because theme files will not be included
function OpenTable()
{
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";
}

function CloseTable()
{
    echo '</td></tr></table>';
}

function themecenterposts( $title, $content )
{
    echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">' . $title . '</td></tr><tr><td><br />' . $content . '<br /></td></tr></table>';
}

function myTextForm( $url , $value )
{
    return '<form action="' . $url . '" method="post"><input type="submit" value="' . $value . '" /></form>';
}

function xoopsfwrite()
{
    if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
        return false;
    } else {
    }
    if ( !$GLOBALS['xoopsSecurity']->checkReferer() ) {
        return false;
    } else {
    }
    return true;
}

function xoops_module_get_admin_menu()
{
    trigger_error( __FUNCTION__ . " is deprecated, should not be used any longer", E_USER_NOTICE );
    /**
     * Based on:
     * - PHP Layers Menu 1.0.7(c)2001,2002 Marco Pratesi <pratesi@telug.it>
     * - TreeMenu 1.1 - Bjorge Dijkstra <bjorge@gmx.net>
     *
     * - php code Optimized by DuGris
     */

    $left = 105;
    $top = 135;
    $js = "";
    $moveLayers = "";
    $shutdown = "";
    $firstleveltable = "";
    $menu_layers = "";

    $module_handler = &xoops_gethandler( 'module' );
    $criteria = new CriteriaCompo();
    $criteria->add( new Criteria( 'hasadmin', 1 ) );
    $criteria->add( new Criteria( 'isactive', 1 ) );
    $criteria->setSort( 'mid' );
    $mods = $module_handler->getObjects( $criteria );

    foreach ( $mods as $mod ) {
        $mid = $mod->getVar( 'mid' );
        $module_name = $mod->getVar( 'name' );
        $module_url = "\".XOOPS_URL.\"/modules/" . $mod->getVar( 'dirname' ) . "/" . trim( $mod->getInfo( 'adminindex' ) );
        $module_img = "<img class='admin_layer_img' src='\".XOOPS_URL.\"/modules/" . $mod->getVar( 'dirname' ) . "/" . $mod->getInfo( 'image' ) . "' alt='' />";
        $module_desc = "<strong>\"._VERSION.\":</strong> " . round( $mod->getVar( 'version' ) / 100 , 2 ) . "<br /><strong>\"._DESCRIPTION.\":</strong> " . $mod->getInfo( 'description' );

        $top = $top + 15;

        $js .= "\nfunction popUpL" . $mid . "() {\n    shutdown();\n    popUp('L" . $mid . "',true);}";
        $moveLayers .= "\n    setleft('L" . $mid . "'," . $left . ");\n    settop('L" . $mid . "'," . $top . ");";
        $shutdown .= "\n    popUp('L" . $mid . "',false);";
        $firstleveltable .= "$" . "xoops_admin_menu_ft[" . $mid . "] = \"<a href='" . $module_url . "' title='" . $module_name . "' onmouseover='moveLayerY(\\\"L" . $mid . "\\\", currentY, event) ; popUpL" . $mid . "(); ' >" . $module_img . "</a><br />\";\n";
        $menu_layers .= "\n<div id='L" . $mid . "' style='position: absolute; visibility: hidden; z-index:1000;' >\n<table class='admin_layer' cellpadding='0' cellspacing='0'>\n<tr><th nowrap='nowrap'>" . $module_name . "</th></tr>\n<tr><td class='even' nowrap='nowrap'>";

        $adminmenu = $mod->getAdminMenu();

        if ( $mod->getVar( 'hasnotification' ) || ( $mod->getInfo( 'config' ) && is_array( $mod->getInfo( 'config' ) ) ) || ( $mod->getInfo( 'comments' ) && is_array( $mod->getInfo( 'comments' ) ) ) ) {
            $adminmenu[] = array( 'link' => '".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $mid, 'title' => _PREFERENCES, 'absolute' => true );
        }
        if ( count( $adminmenu ) != 0 ) {
            $currenttarget = "";
            foreach ( $adminmenu as $menuitem ) {
                $menu_link = trim( $menuitem['link'] );
                $menu_title = trim( $menuitem['title'] );
                $menu_target = isset( $menuitem['target'] ) ? " target='" . trim( $menuitem['target'] ) . "'" : '';
                if ( isset( $menuitem['absolute'] ) && $menuitem['absolute'] ) {
                    $menu_link = ( empty( $menu_link ) ) ? "#" : $menu_link;
                } else {
                    $menu_link = ( empty( $menu_link ) ) ? "#" : "\".XOOPS_URL.\"/modules/" . $mod->getVar( 'dirname' ) . "/" . $menu_link;
                }

                $menu_layers .= "\n<img src='\".XOOPS_URL.\"/images/pointer.gif' width='8' height='8' alt='' />&nbsp;<a href='" . $menu_link . "'" . $menu_target . " onmouseover='popUpL" . $mid . "' >" . $menu_title . "</a><br />\n";
            }
        }

        $menu_layers .= "\n<div style='margin-top: 5px; font-size: smaller; text-align: right;'><a href='#' onmouseover='shutdown();'>[" . _CLOSE . "]</a></div></td></tr><tr><th style='font-size: smaller; text-align: left;'>" . $module_img . "<br />" . $module_desc . "</th></tr></table></div>\n";
    }

    $menu_layers .= "\n<script language='JavaScript' type='text/javascript'>\n<!--\nmoveLayers();\nloaded = 1;\n// -->\n</script>\n";

    $content = "<" . "?php\n";
    $content .= "\$xoops_admin_menu_js = \"" . $js . "\n\";\n\n";
    $content .= "\$xoops_admin_menu_ml = \"" . $moveLayers . "\n\";\n\n";
    $content .= "\$xoops_admin_menu_sd = \"" . $shutdown . "\n\";\n\n";
    $content .= $firstleveltable . "\n";
    $content .= "\$xoops_admin_menu_dv = \"" . $menu_layers . "\";\n";
    $content .= "\n?" . ">";

    return $content;
}

function xoops_module_write_admin_menu( $content )
{
    trigger_error( __FUNCTION__ . " is deprecated, should not be used any longer", E_USER_NOTICE );
    if ( !xoopsfwrite() ) {
        return false;
    }
    $filename = XOOPS_CACHE_PATH . '/adminmenu.php';
    if ( !$file = fopen( $filename, "w" ) ) {
        echo 'failed open file';
        return false;
    }
    if ( fwrite( $file, $content ) == -1 ) {
        echo 'failed write file';
        return false;
    }
    fclose( $file );
    // write index.html file in cache folder
    // file is delete after clear_cache (smarty)
    xoops_write_index_file( XOOPS_CACHE_PATH );
    return true;
}

function xoops_write_index_file( $path = '' )
{
    if ( empty( $path ) ) {
        return false;
    }
    if ( !xoopsfwrite() ) {
        return false;
    }

    $path = substr( $path, -1 ) == "/" ? substr( $path, 0, -1 ) : $path;
    $filename = $path . '/index.html';
    if ( file_exists( $filename ) ) {
        return true;
    }
    if ( !$file = fopen( $filename, "w" ) ) {
        echo 'failed open file';
        return false;
    }
    if ( fwrite( $file, "<script>history.go(-1);</script>" ) == -1 ) {
        echo 'failed write file';
        return false;
    }
    fclose( $file );
    return true;
}

function xoops_getObjectForm()
{
    if ( !class_exists( 'XoopsObjectForm' ) ) {
        if ( file_exists( $hnd_file = XOOPS_ROOT_PATH . '/kernel/objectform.php' ) ) {
            require_once $hnd_file;
        }
    }
}

/**
 * Admin functions required for redone admin areas
 */
function xoops_getConstants( $title, $prefix = '', $suffix = '' )
{
    $prefix = ( $prefix != '' || $title != 'action' ) ? trim( $prefix ) : '';
    $suffix = trim( $suffix );
    return constant( strtoupper( "$prefix$title$suffix" ) );
}

function xoops_getIcons( $icon_array = array(), $key, $value = null, $extra = null )
{
    $ret = '';
    if ( $value ) {
        foreach( $icon_array as $_op => $_icon ) {
            if ( !is_numeric( $_op ) ) {
                $url = $_op . "?{$key}=" . $value;
            } else {
                $url = $_SERVER['PHP_SELF'];
                if ( !empty( $GLOBALS['fct'] ) ) {
                    $url .= "?fct=" . htmlspecialchars( $GLOBALS['fct'] ) . '&amp;';
                } else {
                    $url .= '?';
                }
                $url .= "op={$_icon}&amp;{$key}=" . $value;
            }
            if ( $extra != null ) {
                $url .= $extra;
            }
            $ret .= '<a href="' . $url . '">' . xoops_showImage( 'xo_' . $_icon, xoops_getConstants( '_xo_' . $_icon ), null, 'png' ) . '</a>';
        }
    }
    return $ret;
}

function xoops_show_buttons( $butt_align = 'right', $butt_id = 'button', $class_id = 'formbutton' , $button_array = array() )
{
    if ( !is_array( $button_array ) ) {
        return false;
    }
    $ret = "<div style='text-align: $butt_align; margin-bottom: 12px;'>\n";
    $ret .= "<form id='{$butt_id}' action='showbuttons'>\n";
    foreach ( $button_array as $k => $v ) {
        $ret .= "<input type='button' style='cursor: hand;' class='{$class_id}'  name='" . trim( $v ) . "' onclick=\"location='" . htmlspecialchars( trim( $k ), ENT_QUOTES ) . "'\" value='" . trim( $v ) . "' />&nbsp;&nbsp;";
    }
    $ret .= "</form>\n";
    $ret .= "</div>\n";
    echo $ret;
}

function xoops_showImage( $name = '', $title = '', $align = 'absmiddle', $ext = 'png', $path = '' )
{
    if ( empty( $path ) ) {
        $path = '/modules/system/images/icon';
    }
    if ( $name ) {
        return "<img src='" . XOOPS_URL . "$path/$name.$ext' border='0' title='$title' alt='$title' align='$align' />";
    } else {
        return '';
    }
}

function xoops_ShowPagenav( $tot_num = 0, $num_dis = 10, $start = 0, $from = 'start', $nav_type = 1, $nav_path = '', $returns = false )
{
    $output = '';
    $ret = '';
    $from_result = $start + 1;
    if ( $num_dis == 0 ) {
        $num_dis = $tot_num;
    }
    if ( $start + $num_dis < $tot_num ) {
        $to_result = $start + $num_dis;
    } else {
        $to_result = $tot_num;
    }
    if ( $from_result > $tot_num ) {
        $from_result = 1;
        $start = 0;
    }

    if ( $tot_num > 0 ) {
        $output .= '<div class="navHeading">' . sprintf( _XO_RECORDSFOUND, $from_result, $to_result, $tot_num ) . '</div>';
    } else {
        $output .= '<div class="navHeading">' . _XO_NORECORDS . '</div>';
    }
    $ret = '<div class="navresults">' . $output . '</div><br />';
    if ( intval( $tot_num ) > intval( $num_dis ) ) {
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav( intval( $tot_num ), intval( $num_dis ), intval( $start ), $from, $nav_path );
        $page = ( $tot_num > $num_dis ) ? _PAGE : '';
        $ret .= '<div style="text-align: right; margin-top: 5px;">' . $page;
        switch ( intval( $nav_type ) ) {
            case 1:
            default:
                $_page_nav = $pagenav->renderNav();
                $ret .= $_page_nav;
                break;
            case 2:
                $_page_nav = $pagenav->renderImageNav();
                $ret .= $_page_nav;
                break;
            case 3:
                $ret .= "&nbsp;" . $pagenav->renderSelect() . "";
                break;
        } // switch
        $ret .= '</div><br />';
    }
    if ( $returns == false ) {
        echo $ret;
    } else {
        return $ret;
    }
}

function xoops_ShowLegend( $led_array )
{
    $legend = '';
    /**
     * show legend
     */
    if ( is_array( $led_array ) ) {
        foreach( $led_array as $key ) {
            $legend .= '<div style="padding: 3;">' . xoops_showImage( 'xo_' . $key ) . ' ' . xoops_getConstants( '_XO_' . $key . '_LEG' ) . '</div>';
        }
    } else {
        return '';
    }
    echo $legend;
}

function xoops_getSelection( $this_array = array(), $selected = 0, $value = '', $size = '', $emptyselect = false , $multipule = false, $noselecttext = "------------------", $extra = '', $vvalue = 0, $echo = true )
{
    if ( $multipule == true ) {
        $ret = "<select size='" . $size . "' name='" . $value . "[]' id='" . $value . "[]' multiple='multiple' $extra>";
    } else {
        $ret = "<select size='" . $size . "' name='" . $value . "' id='" . $value . "' $extra>";
    }
    if ( $emptyselect )
        $ret .= "<option value=''>$noselecttext</option>";
    if ( count( $this_array ) ) {
        foreach( $this_array as $key => $content ) {
            $opt_selected = "";
            $newKey = ( intval( $vvalue ) == 1 ) ? $content : $key;
            if ( is_array( $selected ) && in_array( $newKey, $selected ) ) {
                $opt_selected .= " selected='selected'";
            } else {
                if ( $key == $selected ) {
                    $opt_selected = "selected='selected'";
                }
            }
            $content = xoops_substr( $content, 0, 24 );
            $ret .= "<option value='" . $newKey . "' $opt_selected>" . $content . "</option>";
        }
    }
    $ret .= "</select>";
    if ( $echo == true ) {
        echo "<div>" . $ret . "</div><br />";
    } else {
        return $ret;
    }
}

?>