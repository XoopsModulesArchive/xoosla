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
 * XOOPS methods for user handling
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class XoopsUserUtility
{
    // Sending out welcoming message to a new member
    function sendWelcome($user)
    {
        global $xoopsConfigUser, $xoopsConfig;
        
        if (empty($xoopsConfigUser)) {
            $config_handler =& xoops_gethandler('config');
            $xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
        }
        
        if (empty($xoopsConfigUser["welcome_type"])) {
            return true;
        }

        if (!empty($user) && !is_object($user)) {
            $member_handler =& xoops_gethandler('member');
            $user =& $member_handler->getUser($user);
        }
        if (!is_object($user)) {
            return false;
        }
        xoops_loadLanguage("user");
        $xoopsMailer =& xoops_getMailer();
        if ($xoopsConfigUser["welcome_type"] == 1 || $xoopsConfigUser["welcome_type"] == 3) {
            $xoopsMailer->useMail();
        }
        if ($xoopsConfigUser["welcome_type"] == 2 || $xoopsConfigUser["welcome_type"] == 3) {
            $xoopsMailer->usePM();
        }
        $xoopsMailer->setTemplate('welcome.tpl');
        $xoopsMailer->setSubject(sprintf(_US_WELCOME_SUBJECT, $xoopsConfig['sitename']));
        $xoopsMailer->setToUsers($user);

        if ( $xoopsConfigUser['reg_dispdsclmr'] && $xoopsConfigUser['reg_disclaimer'] ) {
            $xoopsMailer->assign('TERMSOFUSE', $xoopsConfigUser['reg_disclaimer']);
        } else {
            $xoopsMailer->assign('TERMSOFUSE', "");
        }
        
        return $xoopsMailer->send();
    }
    
    function validate($uname, $email, $pass, $vpass)
    {
        global $xoopsConfigUser, $xoopsDB;
        xoops_loadLanguage("user");
        $myts =& MyTextSanitizer::getInstance();
        $stop = '';
        if (!checkEmail($email)) {
            $stop .= _US_INVALIDMAIL . '<br />';
        }
        foreach ($xoopsConfigUser['bad_emails'] as $be) {
            if (!empty($be) && preg_match("/" . $be . "/i", $email)) {
                $stop .= _US_INVALIDMAIL . '<br />';
                break;
            }
        }
        if (strrpos($email,' ') > 0) {
            $stop .= _US_EMAILNOSPACES . '<br />';
        }
        $uname = xoops_trim($uname);
        switch ($xoopsConfigUser['uname_test_level']) {
        case 0:
            // strict
            $restriction = '/[^a-zA-Z0-9\_\-]/';
            break;
        case 1:
            // medium
            $restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
            break;
        case 2:
            // loose
            $restriction = '/[\000-\040]/';
            break;
        }
        if (empty($uname) || preg_match($restriction, $uname)) {
            $stop .= _US_INVALIDNICKNAME . "<br />";
        }
        if (strlen($uname) > $xoopsConfigUser['maxuname']) {
            $stop .= sprintf(_US_NICKNAMETOOLONG, $xoopsConfigUser['maxuname']) . "<br />";
        }
        if (strlen($uname) < $xoopsConfigUser['minuname']) {
            $stop .= sprintf(_US_NICKNAMETOOSHORT, $xoopsConfigUser['minuname']) . "<br />";
        }
        foreach ($xoopsConfigUser['bad_unames'] as $bu) {
            if (!empty($bu) && preg_match("/" . $bu . "/i", $uname)) {
                $stop .= _US_NAMERESERVED . "<br />";
                break;
            }
        }
        if (strrpos($uname, ' ') > 0) {
            $stop .= _US_NICKNAMENOSPACES . "<br />";
        }
        $sql = sprintf('SELECT COUNT(*) FROM %s WHERE uname = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($uname)));
        $result = $xoopsDB->query($sql);
        list($count) = $xoopsDB->fetchRow($result);
        if ($count > 0) {
            $stop .= _US_NICKNAMETAKEN . "<br />";
        }
        $count = 0;
        if ( $email ) {
            $sql = sprintf('SELECT COUNT(*) FROM %s WHERE email = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($email)));
            $result = $xoopsDB->query($sql);
            list($count) = $xoopsDB->fetchRow($result);
            if ( $count > 0 ) {
                $stop .= _US_EMAILTAKEN . "<br />";
            }
        }
        if ( !isset($pass) || $pass == '' || !isset($vpass) || $vpass == '' ) {
            $stop .= _US_ENTERPWD . '<br />';
        }
        if ( (isset($pass)) && ($pass != $vpass) ) {
            $stop .= _US_PASSNOTSAME . '<br />';
        } elseif ( ($pass != '') && (strlen($pass) < $xoopsConfigUser['minpass']) ) {
            $stop .= sprintf(_US_PWDTOOSHORT, $xoopsConfigUser['minpass']) . "<br />";
        }
        return $stop;
    }
    
    
    
    /**
     * Get client IP
     * 
     * Adapted from PMA_getIp() [phpmyadmin project]
     *
     * @param	bool	$asString	requiring integer or dotted string
     * @return	mixed	string or integer value for the IP
     */
    function getIP($asString = false)
    {
        // Gets the proxy ip sent by the user
        $proxy_ip     = '';
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED"])) {
            $proxy_ip = $_SERVER["HTTP_X_FORWARDED"];
        } else if (!empty($_SERVER["HTTP_FORWARDED_FOR"])) {
            $proxy_ip = $_SERVER["HTTP_FORWARDED_FOR"];
        } else if (!empty($_SERVER["HTTP_FORWARDED"])) {
            $proxy_ip = $_SERVER["HTTP_FORWARDED"];
        } else if (!empty($_SERVER["HTTP_VIA"])) {
            $proxy_ip = $_SERVER["HTTP_VIA"];
        } else if (!empty($_SERVER["HTTP_X_COMING_FROM"])) {
            $proxy_ip = $_SERVER["HTTP_X_COMING_FROM"];
        } else if (!empty($_SERVER["HTTP_COMING_FROM"])) {
            $proxy_ip = $_SERVER["HTTP_COMING_FROM"];
        }
    
        if (!empty($proxy_ip) &&
            $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs) &&
            count($regs) > 0
      	) {
          	$the_IP = $regs[0];
      	}else{
          	$the_IP = $_SERVER['REMOTE_ADDR'];	      	
      	}
        
      	$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
      	
      	return $the_IP;
    }
    
    function getUnameFromIds( $uid, $usereal = false, $linked = false )
    {
    	if (!is_array($uid)) {
        	$uid = array($uid);
    	}
    	$userid = array_map("intval", array_filter($uid));
    
    	$myts =& MyTextSanitizer::getInstance();
    	$users = array();
    	if (count($userid) > 0) {
            $sql = 'SELECT uid, uname, name FROM ' . $GLOBALS['xoopsDB']->prefix('users'). ' WHERE level > 0 AND uid IN(' . implode(",", array_unique($userid)) . ')';
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                return $users;
            }
            while ($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
    	        $uid = $row["uid"];
                if ( $usereal && $row["name"] ) {
    				$users[$uid] = $myts->htmlSpecialChars($row["name"]);
            	} else {
    				$users[$uid] = $myts->htmlSpecialChars($row["uname"]);
    			}
    			if ($linked) {
    				$users[$uid] = "<a href='" . XOOPS_URL . "/userinfo.php?uid={$uid}' title='{$users[$uid]}'>{$users[$uid]}</a>";
    			}
            }
    	}
    	if (in_array(0, $users, true)) {
    		$users[0] = $myts->htmlSpecialChars($GLOBALS['xoopsConfig']['anonymous']);
    	}
        return $users;
    }
    
    function getUnameFromId( $userid, $usereal = false, $linked = false)
    {
    	$myts =& MyTextSanitizer::getInstance();
    	$userid = intval($userid);
    	$username = "";
    	if ($userid > 0) {
            $member_handler =& xoops_gethandler('member');
            $user =& $member_handler->getUser($userid);
            if (is_object($user)) {
                if ( $usereal && $user->getVar('name') ) {
    				$username = $user->getVar('name');
            	} else {
    				$username = $user->getVar('uname');
    			}
    	        if (!empty($linked)) {
    				$username = "<a href='" . XOOPS_URL . "/userinfo.php?uid={$userid}' title='{$username}'>{$username}</a>";
    	        }
            }
        }
        if (empty($username)){
    		$username = $myts->htmlSpecialChars($GLOBALS['xoopsConfig']['anonymous']);
        }
        return $username;
    }
}
?>