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
 * TextSanitizer extension
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @subpackage textsanitizer
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * MytsRtsp
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
class MytsRtsp extends MyTextSanitizerExtension {
	/**
	 * MytsRtsp::encode()
	 *
	 * @param mixed $textarea_id
	 * @return
	 */
	public function encode( $textarea_id )
	{
		$config = parent::loadConfig( dirname( __FILE__ ) );
		$code = "<img src='{$this->image_path}/rtspimg.gif' alt='" . _XOOPS_FORM_ALTRTSP . "' onclick='xoopsCodeRtsp(\"{$textarea_id}\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERRTSPURL, ENT_QUOTES ) . "\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERHEIGHT, ENT_QUOTES ) . "\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERWIDTH, ENT_QUOTES ) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
		$javascript = <<<EOH
            function xoopsCodeRtsp(id,enterRtspPhrase, enterRtspHeightPhrase, enterRtspWidthPhrase){
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                        var selection = "rtsp://"+selection;
                        var text = selection;
                    } else {
                        var text = prompt(enterRtspPhrase+"       Rtsp or http", "Rtsp://");
                    }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 && text!="rtsp://") {
                    var text2 = prompt(enterRtspWidthPhrase, "480");
                    var text3 = prompt(enterRtspHeightPhrase, "330");
                    var result = "[rtsp="+text2+","+text3+"]" + text + "[/rtsp]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
		return array( $code, $javascript );
	}

	/**
	 * MytsRtsp::load()
	 *
	 * @param mixed $ts
	 * @return
	 */
	public function load( MyTextSanitizer &$ts )
	{
		$ts->patterns[] = '/\[rtsp=([""]?)([^""]*),([^""]*)\\1]([^"]*)\[\/rtsp\]/su';
		$rp = '<object classid="clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa" height="\\3" id=player width="\\2" viewastext>';
		$rp .= '<param name="_extentx" value="12726">';
		$rp .= '<param name="_extenty" value="8520">';
		$rp .= '<param name="autostart" value="0">';
		$rp .= '<param name="shuffle" value="0">';
		$rp .= '<param name="prefetch" value="0">';
		$rp .= '<param name="nolabels" value="0">';
		$rp .= '<param name="controls" value="imagewindow">';
		$rp .= '<param name="console" value="_master">';
		$rp .= '<param name="loop" value="0">';
		$rp .= '<param name="numloop" value="0">';
		$rp .= '<param name="center" value="0">';
		$rp .= '<param name="maintainaspect" value="1">';
		$rp .= '<param name="backgroundcolor" value="#000000">';
		$rp .= '<param name="src" value="\\4">';
		$rp .= '<embed autostart="0" src="\\4" type="audio/x-pn-realaudio-plugin" height="\\3" width="\\2" controls="imagewindow" console="cons"> </embed>';
		$rp .= '</object>';
		$rp .= '<br /><object classid=clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa height=32 id=player width="\\2" viewastext>';
		$rp .= '<param name="_extentx" value="18256">';
		$rp .= '<param name="_extenty" value="794">';
		$rp .= '<param name="autostart" value="0">';
		$rp .= '<param name="shuffle" value="0">';
		$rp .= '<param name="prefetch" value="0">';
		$rp .= '<param name="nolabels" value="0">';
		$rp .= '<param name="controls" value="controlpanel">';
		$rp .= '<param name="console" value="_master">';
		$rp .= '<param name="loop" value="0">';
		$rp .= '<param name="numloop" value="0">';
		$rp .= '<param name="center" value="0">';
		$rp .= '<param name="maintainaspect" value="0">';
		$rp .= '<param name="backgroundcolor" value="#000000">';
		$rp .= '<param name="src" value="\\4">';
		$rp .= '<embed autostart="0" src="\\4" type="audio/x-pn-realaudio-plugin" height="30" width="\\2" controls="controlpanel" console="cons"> </embed>';
		$rp .= '</object>';
		$ts->replacements[] = $rp;
	}
}

?>