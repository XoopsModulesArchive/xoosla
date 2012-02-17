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
 * Xoops extended Dhtmltextarea class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      editor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

xoops_load('XoopsEditor');

class FormDhtmlExt extends XoopsEditor 
{
    var $renderer;
    var $_rows = 35;
    var $_cols = 50;
    /**
     * Hidden text
     * @var    string
     * @access    private
     */
    var $_hiddenText = "xoopsHiddenText";
    
    /**
     * Constructor
     *
     * @param    array   $options    
     */
    
    function __construct($options = array())
    {
        parent::__construct($options);
        $this->rootPath = "/class/xoopseditor/" . basename(dirname(__FILE__));
        $this->_hiddenText = isset($this->configs["hiddenText"]) ? $this->configs["hiddenText"] : $this->_hiddenText;
    }
    
    function FormDhtmlExt($options = array())
    {
        $this->__construct($options);
    }

    /**
     * Prepare HTML for output
     *
     * @return    string  HTML
     */
    function render()
    {
        $ret = '<script language="JavaScript" type="text/javascript" src="' . XOOPS_URL . $this->rootPath . '/javascript/xoops.js"></script>';
        $ret .= $this->_codeIcon()."<br />\n";
        $ret .= $this->_fontArray();
        $ret .= "<input type='button' onclick=\"XoopsCheckLength('".$this->getName()."', '". @$this->configs['maxlength'] ."', '"._ALTLENGTH."', '"._ALTLENGTH_MAX."');\" value=' ? ' />";
        $ret .= "<br /><br />\n\n";
        
        $ret .= "<textarea id='".$this->getName()."' name='".$this->getName()."' onselect=\"xoopsSavePosition('".$this->getName()."');\" onclick=\"xoopsSavePosition('".$this->getName()."');\" onkeyup=\"xoopsSavePosition('".$this->getName()."');\" cols='".$this->getCols()."' rows='".$this->getRows()."'".$this->getExtra().">".$this->getValue()."</textarea><br />\n";
        return $ret;
    }


    function _codeIcon()
    {
        $textarea_id = $this->getName();
        $image_path = XOOPS_URL . $this->rootPath . "/images";
        $code = "<a name='moresmiley'></a>
            <img src='".XOOPS_URL."/images/url.gif' alt='"._ALTURL."' onclick='xoopsCodeUrl(\"$textarea_id\", \"".htmlspecialchars(_ENTERURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWEBTITLE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"'/>&nbsp;
            <img src='".XOOPS_URL."/images/email.gif' alt='"._ALTEMAIL."' onclick='xoopsCodeEmail(\"$textarea_id\", \"".htmlspecialchars(_ENTEREMAIL, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
            <img src='".XOOPS_URL."/images/imgsrc.gif' alt='"._ALTIMG."' onclick='xoopsCodeImg(\"$textarea_id\", \"".htmlspecialchars(_ENTERIMGURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_IMGPOSRORL, ENT_QUOTES)."\", \"".htmlspecialchars(_ERRORIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
            <img src='".XOOPS_URL."/images/image.gif' alt='"._ALTIMAGE."' onclick='openWithSelfMain(\"".XOOPS_URL."/imagemanager.php?target=".$textarea_id."\",\"imgmanager\",400,430);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
            <img src='".$image_path."/smiley.gif' alt='"._ALTSMILEY."' onclick='openWithSelfMain(\"".XOOPS_URL."/misc.php?action=showpopups&amp;type=smilies&amp;target=".$textarea_id."\",\"smilies\",300,475);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        
        $myts = MyTextSanitizer::getInstance();
        if (!empty($myts->config['extensions']['flash'])) {
            $config = $myts->loadConfig('flash');
            $code .= "<img src='".$image_path."/swf.gif' alt='"._ALTFLASH."'  onclick='xoopsCodeFlash(\"$textarea_id\",\"".htmlspecialchars(_ENTERFLASHURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\", \"".$config['detect_dimension']."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['youtube'])) {
            $code .= "<img src='".$image_path."/youtube.gif' alt='"._ALTFLASH."'  onclick='xoopsCodeYoutube(\"$textarea_id\",\"".htmlspecialchars(_ENTERFLASHURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['wmp'])) {
            $code .= "<img src='".$image_path."/wmp.gif' alt='"._ALTWMP."'  onclick='xoopsCodeWmp(\"$textarea_id\",\"".htmlspecialchars(_ENTERWMPURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['mms'])) {
            $code .= "<img src='".$image_path."/mmssrc.gif' alt='"._ALTMMS."' onclick='xoopsCodeMms(\"$textarea_id\",\"".htmlspecialchars(_ENTERMMSURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['rtsp'])) {
            $code .= "<img src='".$image_path."/rtspimg.gif' alt='"._ALTRTSP."' onclick='xoopsCodeRtsp(\"$textarea_id\",\"".htmlspecialchars(_ENTERRTSPURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERWIDTH, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['iframe'])) {
            $code .= "<img src='".$image_path."/iframe.gif' alt='"._ALTIFRAME."' onclick='xoopsCodeIframe(\"$textarea_id\",\"".htmlspecialchars(_ENTERIFRAMEURL, ENT_QUOTES)."\",\"".htmlspecialchars(_ENTERHEIGHT, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        if (!empty($myts->config['extensions']['wiki'])) {
            $code .= "<img src='".$image_path."/wiki.gif' alt='"._ALTWIKI."' onclick='xoopsCodeWiki(\"$textarea_id\",\"".htmlspecialchars(_ENTERWIKITERM, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        }
        $code .="
            <img src='".XOOPS_URL."/images/code.gif' alt='"._ALTCODE."' onclick='xoopsCodeCode(\"$textarea_id\", \"".htmlspecialchars(_ENTERCODE, ENT_QUOTES)."\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;
            <img src='".XOOPS_URL."/images/quote.gif' alt='"._ALTQUOTE."' onclick='xoopsCodeQuote(\"$textarea_id\", \"".htmlspecialchars(_ENTERQUOTE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"'/>
        ";
        return($code);
    }

    function _fontArray()
    {
        $textarea_id = $this->getName();
        $hiddentext = $this->_hiddenText;
        $fontStr = "<script type=\"text/javascript\" language=\"JavaScript\">
            var _editor_dialog = ''
            + '<select id=\'{$textarea_id}Size\' onchange=\'_setElementSize(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
            + '<option value=\'SIZE\'>"._SIZE."</option>'
            ";
        foreach ($GLOBALS["formtextdhtml_sizes"] as $_val => $_name) {
            $fontStr .= " + '<option value=\'{$_val}\'>{$_name}</option>'";
        };
        $fontStr .= " + '</select>'";
        
        $fontStr .= "
            + '<select id=\'{$textarea_id}Font\' onchange=\'_setElementFont(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
            + '<option value=\'FONT\'>"._FONT."</option>'
            ";
        $fontarray = !empty($GLOBALS["formtextdhtml_fonts"]) ? $GLOBALS["formtextdhtml_fonts"] : array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana", "Haettenschweiler");
        foreach ($fontarray as $font) {
            $fontStr .= " + '<option value=\'{$font}\'>{$font}</option>'";
        };
        $fontStr .= " + '</select>'";
        
        $fontStr .= "
            + '<select id=\'{$textarea_id}Color\' onchange=\'_setElementColor(\"{$hiddentext}\",this.options[this.selectedIndex].value, \"$textarea_id\");\'>'
            + '<option value=\'COLOR\'>"._COLOR."</option>';
            var _color_array = new Array('00', '33', '66', '99', 'CC', 'FF');
            for(var i = 0; i < _color_array.length; i ++) {
                for(var j = 0; j < _color_array.length; j ++) {
                    for(var k = 0; k < _color_array.length; k ++) {
                        var _color_ele = _color_array[i] + _color_array[j] + _color_array[k];
                        _editor_dialog += '<option value=\''+_color_ele+'\' style=\'background-color:#'+_color_ele+';color:#'+_color_ele+';\'>#'+_color_ele+'</option>';
                    }
                }
            }
            _editor_dialog += '</select>';";
        
        $fontStr .= "document.write(_editor_dialog); </script>";
        
        $styleStr = "<img src='".XOOPS_URL."/images/bold.gif' alt='"._ALTBOLD."' onmouseover='style.cursor=\"hand\"' onclick='_makeBold(\"".$hiddentext."\", \"$textarea_id\");' />&nbsp;";
        $styleStr .= "<img src='".XOOPS_URL."/images/italic.gif' alt='"._ALTITALIC."' onmouseover='style.cursor=\"hand\"' onclick='_makeItalic(\"".$hiddentext."\", \"$textarea_id\");' />&nbsp;";
        $styleStr .= "<img src='".XOOPS_URL."/images/underline.gif' alt='"._ALTUNDERLINE."' onmouseover='style.cursor=\"hand\"' onclick='_makeUnderline(\"".$hiddentext."\", \"$textarea_id\");'/>&nbsp;";
        $styleStr .= "<img src='".XOOPS_URL."/images/linethrough.gif' alt='"._ALTLINETHROUGH."' onmouseover='style.cursor=\"hand\"' onclick='_makeLineThrough(\"".$hiddentext."\", \"$textarea_id\");' /></a>&nbsp;";
        $styleStr .= "<input type='text' id='".$textarea_id."Addtext' size='20' />&nbsp;<input type='button' onclick='xoopsCodeText(\"$textarea_id\", \"".$hiddentext."\", \"".htmlspecialchars(_ENTERTEXTBOX, ENT_QUOTES)."\")' value='"._ADD."' />";

        $fontStr = $fontStr."<br />\n".$styleStr."&nbsp;&nbsp;<span id='".$hiddentext."'>"._EXAMPLE."</span>\n";
        return $fontStr;
    }
}

?>