<?php
// $Id: auth_SOAP.php 1600 2008-05-07 wishcraft $
// auth_soap.php - SOAP authentification class
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * @package     kernel
 * @subpackage  auth
 * @description	Authentification class for standard Xoops X-Soap Server V2.3 or V3
 * @author	    Simon Roberts WISHCRAFT	<simon@chronolabs.org.au>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
include_once XOOPS_ROOT_PATH . '/class/auth/auth_soap_provisionning.php';
include_once XOOPS_ROOT_PATH . '/class/soap/xoopssoap.php';

class XoopsAuthSoap extends XoopsAuth {
    
	var $soap_client;
  	var $_dao;
	var $soap_soapclient;
	var $soap_wdsl;
	var $soap_proxyhost;
	var $soap_proxyport;
	var $soap_proxyusername;
	var $soap_proxypassword;
	var $soap_timeout;	
	var $soap_responsetimeout;					
    /**
	 * Authentication Service constructor
	 */
    function XoopsAuthSoap (&$dao) {
		$this->_dao = $dao;
        //The config handler object allows us to look at the configuration options that are stored in the database
        $config_handler =& xoops_gethandler('config');    
        $config =& $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);
        $confcount = count($config);
        foreach ($config as $key => $val) {
            $this->$key = $val;
        }	
		switch (SOAPLIB){
		case "NUSOAP":
			$this->soap_client = new soapclient($this->soap_soapclient, $this->soap_wdsl, $this->soap_proxyhost, $this->soap_proxyport, $this->soap_proxyusername, $this->soap_proxypassword, $this->soap_timeout, $this->soap_responsetimeout);
			break;
		case "INHERIT":
			$this->soap_client = new soapclient($this->soap_soapclient);
			break;
		}
    }


	    /**
	 *  Authenticate  user again SOAP directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
    function authenticate($uname, $pwd = null) {
        $authenticated = false;
	
		if (!$this->soap_client) {
			$this->setErrors(0, _AUTH_SOAP_EXTENSION_NOT_LOAD);
			return $authenticated;
		}

		if (in_array(strtolower($uname),explode("|",strtolower($this->soap_filterperson)))){
			$member_handler =& xoops_gethandler('member');
			$user =& $member_handler->loginUser($uname, $pwd);
			if ($user == false) {
				$this->setErrors(1, _US_INCORRECTLOGIN);
			}
			return ($user);
		
		}
	
				
		$rnd = rand(-100000, 100000000);
		switch (SOAPLIB){
		case "NUSOAP":
			$result = $this->soap_client->call('xoops_authentication', array("username"=> $this->soap_xoops_username, "password"=> $this->soap_xoops_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)));
			break;
		case "INHERIT":
			$result = $this->soap_client->__soapCall('xoops_authentication', array("username"=> $this->soap_xoops_username, "password"=> $this->soap_xoops_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)));
			break;
		}
		//print_r($result);
		if ($result['ERRNUM']==1){
			return $this->loadXoopsUser($result["RESULT"], $uname, $pwd);
		} else {
			$this->setErrors(1, _US_INCORRECTLOGIN.$result['ERRNUM']);
			return false;
		}
		

		
    }
    
    
	          
	function loadXoopsUser($result, $uname, $pwd = null) {
	
		$provisHandler = XoopsAuthProvisionning::getInstance($this);   
        if (count($result) > 0) {
        	$xoopsUser = $provisHandler->sync($result, $uname, $pwd);
	    }
        else $this->setErrors(0, sprintf('loadXoopsUser - ' . _AUTH_SOAP_CANT_READ_ENTRY, $userdn));
					
		return $xoopsUser;
	}

    
} // end class


?>
