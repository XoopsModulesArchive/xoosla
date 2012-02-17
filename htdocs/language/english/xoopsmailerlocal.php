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
 * XOOPS Language defines
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package Language
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id: xoopsmailerlocal.php phppp $
 */

/**
 * Localize the mail functions
 *
 * The English localization is solely for demonstration
 */
// Do not change the class name
class XoopsMailerLocal extends XoopsMailer {
    function XoopsMailerLocal()
    {
        $this->XoopsMailer();
        // It is supposed no need to change the charset
        $this->charSet = strtolower( _CHARSET );
        // You MUST specify the language code value so that the file exists: XOOPS_ROOT_PAT/class/mail/phpmailer/language/lang-["your-language-code"].php
        $this->multimailer->SetLanguage( 'en' );
    }
    // Multibyte languages are encouraged to make their proper method for encoding FromName
    function encodeFromName( $text )
    {
        // Activate the following line if needed
        // $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
        return $text;
    }
    // Multibyte languages are encouraged to make their proper method for encoding Subject
    /**
     * XoopsMailerLocal::encodeSubject()
     *
     * @param mixed $text
     * @return
     */
    function encodeSubject( $text )
    {
        // Activate the following line if needed
        // $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
        return $text;
    }
}

?>