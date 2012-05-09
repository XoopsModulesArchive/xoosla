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
 * MytsMms
 */
class MytsMms extends MyTextSanitizerExtension {
	/**
	 * MytsMms::encode()
	 *
	 * @param mixed $textarea_id
	 * @return
	 */
	public function encode( $textarea_id )
	{
		$config = parent::loadConfig( dirname( __FILE__ ) );
		$code = "<img src='{$this->image_path}/mmssrc.gif' alt='" . _XOOPS_FORM_ALTMMS . "' onclick='xoopsCodeMms(\"{$textarea_id}\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERMMSURL, ENT_QUOTES ) . "\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERHEIGHT, ENT_QUOTES ) . "\",\"" . htmlspecialchars( _XOOPS_FORM_ENTERWIDTH, ENT_QUOTES ) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
		$javascript = <<<EOH
            function xoopsCodeMms(id,enterMmsPhrase, enterMmsHeightPhrase, enterMmsWidthPhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var selection="mms://"+selection;
                    var text = selection;
                } else {
                    var text = prompt(enterMmsPhrase+"mms or http", "mms://");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 && text != "mms://") {

                    var text2 = prompt(enterMmsWidthPhrase, "480");
                    var text3 = prompt(enterMmsHeightPhrase, "330");
                    var result = "[mms="+text2+","+text3+"]" + text + "[/mms]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
		return array( $code,
			$javascript );
	}

	/**
	 * MytsMms::load()
	 *
	 * @param mixed $ts
	 * @return
	 */
	public function load( MyTextSanitizer &$ts )
	{
		$ts->patterns[] = '/\[mms=([""]?)([^""]*),([^""]*)\\1]([^"]*)\[\/mms\]/su';
		$rp = '<object id=videowindow1 height="\\3" width="\\2" classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6">';
		$rp .= '<param name="url" value="\\4">';
		$rp .= '<param name="rate" value="1">';
		$rp .= '<param name="balance" value="0">';
		$rp .= '<param name="currentposition" value="0">';
		$rp .= '<param name="defaultframe" value="">';
		$rp .= '<param name="playcount" value="1">';
		$rp .= '<param name="autostart" value="0">';
		$rp .= '<param name="currentmarker" value="0">';
		$rp .= '<param name="invokeurls" value="-1">';
		$rp .= '<param name="baseurl" value="">';
		$rp .= '<param name="volume" value="50">';
		$rp .= '<param name="mute" value="0">';
		$rp .= '<param name="uimode" value="full">';
		$rp .= '<param name="stretchtofit" value="0">';
		$rp .= '<param name="windowlessvideo" value="0">';
		$rp .= '<param name="enabled" value="-1">';
		$rp .= '<param name="enablecontextmenu" value="-1">';
		$rp .= '<param name="fullscreen" value="0">';
		$rp .= '<param name="samistyle" value="">';
		$rp .= '<param name="samilang" value="">';
		$rp .= '<param name="samifilename" value="">';
		$rp .= '<param name="captioningid" value="">';
		$rp .= '<param name="enableerrordialogs" value="0">';
		$rp .= '<param name="_cx" value="12700">';
		$rp .= '<param name="_cy" value="8731">';
		$rp .= '</object>';
		$ts->replacements[] = $rp;

		return true;
	}
}

?>