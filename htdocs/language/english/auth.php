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
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: auth.php 2008-04-30 13:11:48Z phppp $
 */
define( '_AUTH_MSG_AUTH_METHOD', 'using %s authentication method' );
define( '_AUTH_LDAP_EXTENSION_NOT_LOAD', 'PHP LDAP extension not loaded (verify your PHP configuration file php.ini)' );
define( '_AUTH_LDAP_SERVER_NOT_FOUND', "Can't connect to the server" );
define( '_AUTH_LDAP_USER_NOT_FOUND', 'Member %s not found in the directory server (%s) in %s' );
define( '_AUTH_LDAP_CANT_READ_ENTRY', 'Can\'t read entry %s' );
define( '_AUTH_LDAP_XOOPS_USER_NOTFOUND', 'Sorry no corresponding user information has been found in the XOOPS database for connection: %s <br>Please verify your user datas or set on the automatic provisionning' );
define( '_AUTH_LDAP_START_TLS_FAILED', 'Failed to open a TLS connection' );

?>