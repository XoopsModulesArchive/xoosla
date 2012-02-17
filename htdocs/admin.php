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
//$xoopsOption['pagetype'] = "admin";
include 'mainfile.php';
xoops_inlude( 'include.cp_functions' );
xoops_language( 'admin' );

/**
 */
/* Admin Authentication                                  */
/**
 */
if ( $xoopsUser ) {
    if ( !$xoopsUser->isAdmin( -1 ) ) {
        redirect_header( "index.php", 2, _AD_NORIGHT );
        exit();
    }
} else {
    redirect_header( "index.php", 2, _AD_NORIGHT );
    exit();
}

xoops_cp_header();
// ###### Output warn messages for security ######
if ( is_dir( XOOPS_ROOT_PATH . "/install/" ) ) {
    xoops_error( sprintf( _WARNINSTALL2, XOOPS_ROOT_PATH . '/install/' ) );
    echo '<br />';
}

if ( is_writable( XOOPS_ROOT_PATH . "/mainfile.php" ) ) {
    xoops_error( sprintf( _WARNINWRITEABLE, XOOPS_ROOT_PATH . '/mainfile.php' ) );
    echo '<br />';
}

if ( !empty( $_GET['xoopsorgnews'] ) ) {
    $rssurl = 'http://sourceforge.net/export/rss2_projnews.php?group_id=41586&rss_fulltext=1';
    $rssfile = XOOPS_CACHE_PATH . '/adminnews.xml';
    $rssdata = '';
    if ( !file_exists( $rssfile ) || filemtime( $rssfile ) < time() - 86400 ) {
        xoops_inlude( 'libraries.snoopy.snoopy' );
        $snoopy = new Snoopy();
        if ( $snoopy->fetch( $rssurl ) ) {
            $rssdata = $snoopy->results;
            if ( false !== $fp = fopen( $rssfile, 'w' ) ) {
                fwrite( $fp, $rssdata );
            }
            fclose( $fp );
        }
    } else {
        if ( false !== $fp = fopen( $rssfile, 'r' ) ) {
            while ( !feof ( $fp ) ) {
                $rssdata .= fgets( $fp, 4096 );
            }
            fclose( $fp );
        }
    }
    if ( $rssdata != '' ) {
        include_once XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php';
        $rss2parser = new XoopsXmlRss2Parser( $rssdata );
        if ( false != $rss2parser->parse() ) {
            echo '<table class="outer" width="100%">';
            $items = &$rss2parser->getItems();
            $count = count( $items );
            $myts = &MyTextSanitizer::getInstance();
            for ( $i = 0; $i < $count; $i++ ) {
                echo '<tr class="head"><td><a href="' . htmlspecialchars( $items[$i]['link'] ) . '" rel="external">';
                echo htmlspecialchars( $items[$i]['title'] ) . '</a> (' . htmlspecialchars( $items[$i]['pubdate'] ) . ')</td></tr>';
                if ( $items[$i]['description'] != "" ) {
                    echo '<tr><td class="odd">' . utf8_decode( $items[$i]['description'] );
                    if ( $items[$i]['guid'] != "" ) {
                        echo '&nbsp;&nbsp;<a href="' . htmlspecialchars( $items[$i]['guid'] ) . '" rel="external">' . _MORE . '</a>';
                    }
                    echo '</td></tr>';
                } elseif ( $items[$i]['guid'] != "" ) {
                    echo '<tr><td class="even" valign="top"></td><td colspan="2" class="odd"><a href="' . htmlspecialchars( $items[$i]['guid'] ) . '" rel="external">' . _MORE . '</a></td></tr>';
                }
            }
            echo '</table>';
        } else {
            echo $rss2parser->getErrors();
        }
    }
}
xoops_cp_footer();

?>