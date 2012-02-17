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
 * Installer template file
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
 * @author Kris <kris@frxoops.org>
 * @author DuGris (aka L. JEN) <dugris@frxoops.org>
 * @author John Neill (aka Zaquria) <zaquria@xoosla.com>
 * @version $Id$
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );
/**
 * Support websites
 */

$page = str_replace ( ' ', '-', $wizard->pages[$wizard->currentPage]['name'] );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo _LANGCODE; ?>" lang="<?php echo _LANGCODE; ?>" dir="<?php echo _RTL ?>">
<head>
<title><?php echo XOOPS_VERSION . ' : ' . INSTALL_WIZARD; ?>(<?php echo ( $wizard->pageIndex + 1 ) . '/' . count( $wizard->pages ); ?>)</title>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo _INSTALL_CHARSET ?>" />
<link href="../favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
<script type="text/javascript" src="./js/prototype-1.6.0.3.js"></script>
<script type="text/javascript" src="./js/xo-installer.js"></script>
</head>

<body>
<div id="wrapper">
    <!--<div id="translate-this"><a style="float: right;margin-bottom: 20px;width:180px;height:18px;display:block;overflow:hidden;text-indent: -2000px;" class="translate-this-button" href="http://www.translatecompany.com/">Translation Agency</a></div>-->
	<div class="clear"></div>
	<div id="xo-header">
        <div id="xo-banner" class="commercial"><img id="xo-main-logo" src="img/logo.png" alt="Xoosla!" />
            <div id="xo-support"><?php echo getSupportHtml() ?></div>
        </div>
    </div>
    <div id="xo-globalnav"><!-- --></div>
    <div id="xo-content">
        <div class="tagsoup1">
            <div class="tagsoup2">
                <div id="wizard">
                    <form id="<?php echo $page; ?>" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post">
                        <div id="button-header">
                            <div class="step"><span class="title"><?php echo XOOPS_VERSION . ' ' . INSTALL_WIZARD;?></span></div>
                            <div class="step2"><?php if ( $wizard->pageIndex != 0 ) { ?><button type="button" id="forward" onclick="history.back()"><?php echo INSTALL_BUTTON_PREVIOUS; ?></button><?php } ?><?php if ( @$pageHasForm ) { ?><button class="forward" type="submit"><?php } else { ?><button class="forward" type="button" accesskey="n" onclick="location.href='<?php echo $wizard->pageURI( '+1' ); ?>'"><?php } ?><?php echo INSTALL_BUTTON_NEXT; ?></button></div>
                        </div>
                        <div class="clear"></div>
                        <div id="pageslist">
                            <div class="steps"><?php echo INSTALL_STEPS;?> <?php echo ( $wizard->pageIndex + 1 ) . '/' . count( $wizard->pages ); ?></div>
                            <ul id="navigation">
                            	<?php echo getPagesHtml() ?>
                            </ul>
                        </div>
                        <div class="page" id="page-<?php echo $page; ?>">
                            <div class="topbar"><?php echo $wizard->pages[$wizard->currentPage]['title'] ; ?></div>
                            <div id="content"><?php echo $content; ?></div>
                        </div>
                        <div id="button-footer">
                            <div class="step"><!-- --></div>
                            <div class="step2"><?php if ( $wizard->pageIndex != 0 ) { ?><button type="button" onclick="history.back()"><?php echo INSTALL_BUTTON_PREVIOUS; ?></button><?php } ?><?php if ( @$pageHasForm ) { ?><button type="submit"><?php } else { ?><button type="button" accesskey="n" onclick="location.href='<?php echo $wizard->pageURI( '+1' ); ?>'"><?php } ?><?php echo INSTALL_BUTTON_NEXT; ?></button></div>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer"><?php echo _INSTALL_FOOTER; ?></div>
<!-- Begin TranslateThis Button -->
<!--
<script type="text/javascript" src="http://x.translateth.is/translate-this.js"></script>
<script type="text/javascript">
TranslateThis();
</script>
-->
<!-- End TranslateThis Button -->
</body>
</html>