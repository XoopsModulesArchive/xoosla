<?php
/**
 * Xoosla English Language Defines
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The Xoosla Project http://sourceforge.net/projects/xoosla/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package install
 * @since 1.0.0.0
 * @author John Neill <zaquria@xoosla.com>
 * @version $Id:
 */
defined( 'XOOSLA_INSTALL' ) or die( 'Direct Access To This File Not Allowed!' );

define( '_LANGCODE', 'en-GB' );
define( '_CHARSET', 'UTF-8' );
define( '_RTL', 'ltr' );
define( '_INSTALL_CHARSET', 'UTF-8' );
define( '_INSTALL_FOOTER', '<a href="http:#www.xoosla.com">Xoosla CMS</a> Copyright &copy; 2012' );
/**
 * Install Steps
 */
define( 'INSTALL_STARTING', 'Getting Started' );
define( 'INSTALL_LICENSE_SELECTION', 'GPL license' );
define( 'INSTALL_CONFIGURATION_CHECK', 'Server Configuration Check' );
define( 'INSTALL_CONFIG_CHECK', 'Installation Configuration Check' );
define( 'INSTALL_DATABASE_CONNECTION', 'Database Information' );
define( 'INSTALL_ADMINISTRATION_DETAILS', 'Administration Details' );
define( 'INSTALL_FINISH', 'Finish' );

/**
 * Install Titles
 */
define( 'INSTALL_STARTING_TITLE', 'Getting Started and Installing Your New Website' );
define( 'INSTALL_CONFIGURATION_CHECK_TITLE', 'Pre-installation Check And Server Configuration Information' );
define( 'INSTALL_LICENSE_SELECTION_TITLE', 'Please Read The GNU General Public License' );
define( 'INSTALL_DATABASE_CONNECTION_TITLE', 'Database Configuration And Settings' );
define( 'INSTALL_ADMINISTRATION_DETAILS_TITLE', 'Administration Login Details' );
define( 'INSTALL_FINISH_TITLE', 'Welcome To Your New Xoosla Website' );

/**
 * GUI
 */
define( 'INSTALL_STEPS', ' Steps' );
define( 'INSTALL_WIZARD', ' Installation' );
define( 'INSTALL_BUTTON_PREVIOUS', 'Previous' );
define( 'INSTALL_BUTTON_NEXT', 'Next' );
define( 'INSTALL_SUPPORT', 'International Support' );
define( 'INSTALL_ON', 'On' );
define( 'INSTALL_OFF', 'Off' );

/**
 * Getting Started Page
 */
define( 'INSTALL_STARTING_MSG', 'Welcome to the Xoosla Installer.<br /><br />We would like to thank you for using Xoosla! as your Webportal/Content Management System. This installer will now guide you through the required steps to install Xoosla CMS on your hosting server. If you have any problems installing your new webportal, please visit the forums at <a href="http:#www.xoosla.com">Xoosla CMS</a> and ask for assistance there.' );
define( 'INSTALL_SELECT_LANGUAGE_TITLE', 'Language Selection' );
define( 'INSTALL_SELECT_LANGUAGE_TITLE_MSG', 'Please select the language that you would like to use during the installation.<br /><br />If your choosen language is not listed, then why not help us translate our installer to your language. Please visit <a href="http:#www.xoosla.com">Xoosla CMS</a> or send us an <a href="mailto:webmaster@xoosla.com">Email</a> with your translation.' );

/**
 * Server Configuration Check Page
 */
define( 'INSTALL_SERVER_CHECK_LEGEND', 'Server Configuration Check' );
define( 'INSTALL_SERVER_CHECK_MSG', 'If any of these items are marked as not supported, then please take the required actions to correct them. Failure to do so, could lead to your Xoosla installation not functioning as you expect. ' );
define( 'INSTALL_RECOMMENDED_EXTENSIONS_LEGEND', 'Recommended Extensions' );
define( 'INSTALL_RECOMMENDED_EXTENSIONS_LEGEND_MSG', 'These extensions are not required for normal use, but may be necessary to explore some specific features (like the Multi-language or RSS support). It&apos;s highly recommended that these extensions are installed.' );

define( 'INSTALL_SERVER_API', 'Server API' );
define( 'INSTALL_PHP_VERSION', 'PHP Version' );
define( 'INSTALL_PHP_EXTENSION', '%s Extension' );
define( 'INSTALL_CHAR_ENCODING', 'Character Encoding' );
define( 'INSTALL_XML_PARSING', 'XML Parsing' );
define( 'INSTALL_REQUIREMENTS', 'Requirements' );
define( 'INSTALL_FILEUPLOADS', 'File Uploads' );

define( 'INSTALL_XOOSLA_PATHS_LEGEND', 'Xoosla Physical Paths' );
define( 'INSTALL_XOOSLA_PATHS_LEGEND_MSG', 'These paths are required for your Xoosla to work correctly and we highly suggest that you leave the following as their suggested values, unless these do not match your server configuration.' );

define( 'INSTALL_ROOT_PATH_LABEL', 'Installation Root Path <span class="required">*</span>' );
define( 'INSTALL_LIB_PATH_LABEL', 'Installation Library Directory <span class="required">*</span>' );
define( 'INSTALL_DATA_PATH_LABEL', 'Installation Data Directory <span class="required">*</span>' );
define( 'INSTALL_URL_LABEL', 'Website Url <span class="required">*</span>' );

define( 'INSTALL_ROOT_PATH_HELP', 'Physical path to the Xoosla installation directory. i.e. http:#mysite/xoosla/ (No trailing slashes).' );
define( 'INSTALL_LIB_PATH_HELP', 'Physical path to the Xoosla library directory (No trailing slashes).' );
define( 'INSTALL_DATA_PATH_HELP', 'Physical path to the Xoosla data directory (No trailing slashes).' );
define( 'INSTALL_URL_HELP', 'The URL that will be used to access your Xoosla website.' );

define( 'INSTALL_ICONV_CONVERSION', 'Character Set Conversion' );
define( 'INSTALL_ZLIB_COMPRESSION', 'Zlib Compression' );
define( 'INSTALL_IMAGE_FUNCTIONS', 'Image Functions' );
define( 'INSTALL_IMAGE_METAS', 'Image Meta Data (exif)' );

/**
 * Database Configuration And Settings
 */
define( 'INSTALL_CONNECTION_LEGEND', 'Database Connection Details' );
define( 'INSTALL_CONNECTION_LEGEND_MSG', 'Before we can begin to install your new web portal, we need to know more about the database settings and how to connect to your host database. If you are unsure what these setting are, please consult with your web hosting company first and ask them for the relative information on creating a new database.' );

define( 'INSTALL_DB_HOST_LABEL', 'Server Host Name <span class="required">*</span>' );
define( 'INSTALL_DB_USER_LABEL', 'Username <span class="required">*</span>' );
define( 'INSTALL_DB_PASS_LABEL', 'Password <span class="required">*</span>' );
define( 'INSTALL_DB_NAME_LABEL', 'Database Name <span class="required">*</span>' );
define( 'INSTALL_DB_PREFIX_LABEL', 'Table Prefix' );
define( 'INSTALL_DB_PCONNECT_LABEL', 'Persistent Connection?' );
define( 'INSTALL_DB_TYPE_LABEL', 'Database Type' );

define( 'INSTALL_DB_HOST_HELP', 'This is usually &quot;localhost&quot;. Some web hosting companies will have you use explict host names, check with your hosting company if the default fails to work. ' );
define( 'INSTALL_DB_USER_HELP', 'Enter the username associated with this database. You may have to create a username via your host server control panel.' );
define( 'INSTALL_DB_PASS_HELP', 'Enter the  password associated with this database. ' );
define( 'INSTALL_DB_NAME_HELP', 'Enter the name of the database that you will use to install Xoosla. If the Database does not exist, the installer will attempt to create it. If the installer cannot create this database, you will have to create one via your web hosting control panel.' );
define( 'INSTALL_DB_PREFIX_HELP', 'Enter a table prefix or choose to use the generated one. This prefix will be added to all new tables created to prevent naming conflicts.' );
define( 'INSTALL_DB_PCONNECT_HELP', 'Default is &quot;No&quot;. If you are unsure, leave it unchecked.' );
define( 'INSTALL_DB_TYPE_HELP', 'Please select which type of database you would like to use for the installation. We know, there is only one, more to come in the near future.' );

/**
 * Database Configuration And Settings Errors
 */
define( 'INSTALL_REQUIRED_MISSING', 'Required Field Missing: ' );
define( 'INSTALL_MISSING_DBTYPE', 'Database Types' );
define( 'INSTALL_MISSING_DBHOST', 'Database Host.' );
define( 'INSTALL_MISSING_DBUSER', 'Database User.' );
define( 'INSTALL_MISSING_DBPASS', 'Database Password.' );
define( 'INSTALL_MISSING_DNAME', 'Database Name.' );
define( 'INSTALL_MISSING_DBPREFIX', 'Database Prefix.' );

/**
 * Administration Login Details
 */
define( 'INSTALL_CONFIG_CHECK_MSG', 'Administrator Account' );
define( 'INSTALL_ADMIN_ACCOUNT_LEGEND', 'Administrator Account' );
define( 'INSTALL_ADMIN_LOGIN_LABEL', 'Administration Username <span class="required">*</span>' );
define( 'INSTALL_ADMIN_LOGIN_LABEL_MSG', 'Please enter your administration username that will be used to access your website.' );
define( 'INSTALL_ADMIN_EMAIL_LABEL', 'Administration Email Address <span class="required">*</span>' );
define( 'INSTALL_ADMIN_EMAIL_LABEL_MSG', 'Please enter a valid email address, this is required to send emails from your website.' );
define( 'INSTALL_ADMIN_PASS_LABEL', 'Administration Password <span class="required">*</span>' );
define( 'INSTALL_ADMIN_PASS_LABEL_MSG', 'Please enter a administration password that will be required to access your website. Please keep this safe and secure.' );
define( 'INSTALL_ADMIN_CONFIRMPASS_LABEL', 'Confirm Administration Password <span class="required">*</span>' );
define( 'INSTALL_ADMIN_CONFIRMPASS_LABEL_MSG', 'Please confirm your administration password.' );

/**
 * Administration Login Details Errors
 */
define( 'INSTALL_ERR_INVALID_EMAIL', 'Please enter a valid email address' );
define( 'INSTALL_ERR_REQUIRED', 'Field Is Required: ' );
define( 'INSTALL_ERR_LOGON_REQUIRED', 'Administration Login' );
define( 'INSTALL_ERR_EMAIL_REQUIRED', 'Administration Email' );
define( 'INSTALL_ERR_PASS_REQUIRED', 'Administration Password' );
define( 'INSTALL_ERR_PASS_REQUIRED2', 'Confirmation Password' );
define( 'INSTALL_ERR_PASSWORD_MATCH', 'Administration Password entered does not match' );

/**
 * Final Page
 */
define( 'INSTALL_FINISH_YOUR_SITE_LEGEND', 'So what\'s Next?' );
define( 'INSTALL_FINISH_YOUR_SITE_MSG', 'Now that the installer has finished installing your new webportal, there are a few things you must do to in order to get your website fully up and running:<br />
<p>
1. <strong>Configure Your Website:</strong> Log in and access the administration area and navigate to the system prefs. Change the configuration to suit your needs. If you are unsure how to do this, visit <a href="http://www.xoosla.com" target="_blank">www.xoosla.com</a> for more information with this subject.<br />
2. <strong>Install Modules:</strong> Modules help enhance the functionality of your website, from adding content, forums or providing extra functionality to the system. We strongly suggest that you install the Protector module and configure it for extra security.<br />
3. <strong>Install Theme:</strong>: Change the whole look of your new web portal by installing a theme. Visit <a href="http://www.xoosla.com" target="_blank">www.xoosla.com</a> for more advice on creating or installing a new theme.
</p>
Access your website from <a href="../index.php">here</a>.' );
define( 'INSTALL_FINISH_SUPPORT_LEGEND', 'Xoosla Support' );
define( 'INSTALL_FINISH_SUPPORT_MSG', 'Visit <a href="http://www.xoosla.com" rel="external">Xoosla!</a>' );
define( 'INSTALL_FINISH_SECURITY_LEGEND', 'Security Consideration' );
define( 'INSTALL_FINISH_SECURITY_MSG', 'The installer will try to configure your site for security considerations. Please double check to make sure the <em>mainfile.php</em> is readonly. 044' );
define( 'INSTALL_FINISH_NOTICE', '<p>The installer has attempted to rename the <strong>install</strong> folder to the following:</p> <p><strong>%s</strong></p><p>Please remove this folder for security reasons.</p>' );

/**
 * Messages
 */
define( 'INSTALL_SETTING_NAME', 'Setting Name' );
define( 'INSTALL_RECOMMENDED', 'Recommended' );
define( 'INSTALL_NONE', 'None' );
define( 'INSTALL_SUCCESS', 'Success' );
define( 'INSTALL_WARNING', 'Warning' );
define( 'INSTALL_FAILED', 'Failed' );

/**
 * Function.php
 */
define( 'TABLES_CREATION_FAILURE', 'Failed to create the database tables and data.' );
define( 'INSTALL_XOOSLA_FOUND', '%s found' );
define( 'INSTALL_IS_NOT_WRITABLE', '%s is NOT writable.' );
define( 'INSTALL_IS_WRITABLE', '%s is writable.' );
define( 'INSTALL_XOOSLA_PATH_FOUND', 'Path found.' );

/**
 * dbmanager.php
 */
define( 'INSTALL_TABLE_NOT_CREATED', 'Unable to create table %s' );
define( 'INSTALL_TABLE_CREATED', 'Table %s created.' );
define( 'INSTALL_ROWS_INSERTED', '%d entries inserted to table %s.' );
define( 'INSTALL_ROWS_FAILED', 'Failed inserting %d entries to table %s.' );
define( 'INSTALL_TABLE_ALTERED', 'Table %s updated.' );
define( 'INSTALL_TABLE_NOT_ALTERED', 'Failed updating table %s.' );
define( 'INSTALL_TABLE_DROPPED', 'Table %s dropped.' );
define( 'INSTALL_TABLE_NOT_DROPPED', 'Failed deleting table %s.' );

/**
 * Install Error Messages
 */
define( 'INSTALL_ERR_NO_DATABASE', 'Could not create database. Contact the server administrator for details.' );
define( 'INSTALL_ERR_COULD_NOT_ACCESS', 'Could not access the specified folder. Please verify that it exists and is readable by the server.' );
define( 'INSTALL_ERR_NO_INSTALL_XOOSLA_FOUND', 'No Xoosla! installation could be found in the specified folder.' );
define( 'INSTALL_ERR_NEED_WRITE_ACCESS', 'The server must be given write access to the following files and folders<br />(i.e. <em>chmod 777 directory_name</em> on a UNIX/LINUX server)<br />If they are not available or not created correctly, please create manually and set proper permissions.' );
define( 'INSTALL_ERR_WRITING_CONSTANT', 'Failed writing constant %s.' );
define( 'INSTALL_ERR_COPY_MAINFILE', 'Could not copy the distribution file to mainfile.php' );
define( 'INSTALL_ERR_WRITE_MAINFILE', 'Could not write into mainfile.php. Please check the file permission and try again.' );
define( 'INSTALL_ERR_READ_MAINFILE', 'Could not open mainfile.php for reading' );
define( 'INSTALL_ERR_INVALID_DBCHARSET', 'The char set &apos;%s&apos; is not supported.' );
define( 'INSTALL_ERR_INVALID_DBCOLLATION', 'The collation &apos;%s&apos; is not supported.' );
define( 'INSTALL_INSTALL_ERR_NO_DATABASE', 'MySQL error %s: %s<br />There is a problem conntecting to your choosen database. Either it doesn\'t exist, your settings are incorrect or you do not have permission to update/edit this database. ' );
define( 'INSTALL_ERR_CHARSET_NOT_SET', 'MySQL error %s: %s<br /> There is a problem updating the database default character set, please contact your hosting company about this error.' );
define( 'INSTALL_ERR_ADMIN_EXIST', 'The installer has notices that an administrator account has already been created for this installion. The installer will skip this step and proceed to the next one.<br /><br />Press <strong> next </strong> to continue.' );
define( 'INSTALL_ERR_PLEASE_CHECK_UANDP', 'MySQL error %s: %s<br />Your settings are incorrect, please check and update as required.' );
define( 'INSTALL_ERR_NO_PERMISSION', 'You do not have permission to access this area. Access to this area is restricted.' );
define( 'INSTALL_ERR_PATHS_INCORRECT', 'Selected paths are incorrect, please recheck and update them as required.' );

/**
 * Password Message
 */
define( 'PASSWORD_LABEL', 'Password Strength' );
define( 'PASSWORD_DESC', 'Enter Your Password' );
define( 'PASSWORD_GENERATOR', 'Password Generator' );
define( 'PASSWORD_GENERATE', 'Generate' );
define( 'PASSWORD_COPY', 'Copy' );
define( 'PASSWORD_VERY_WEAK', 'Very Weak' );
define( 'PASSWORD_WEAK', 'Weak' );
define( 'PASSWORD_BETTER', 'Better' );
define( 'PASSWORD_MEDIUM', 'Medium' );
define( 'PASSWORD_STRONG', 'Strong' );
define( 'PASSWORD_STRONGEST', 'Strongest' );

?>