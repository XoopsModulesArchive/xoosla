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
 * Xoops Autoload class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id$
 * @todo For PHP 5 compliant
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * XoopsLoad
 *
 * @package
 * @author John
 * @copyright Copyright (c) 2012
 * @version $Id$
 * @access public
 */
abstract class XoopsLoad {
	static $loaded;
	static $configs;

	/**
	 * XoopsLoad::load()
	 *
	 * @param mixed $name
	 * @param string $type
	 * @return
	 */
	public static function load( $name, $type = 'core' )
	{
		static $loaded;

		$name = self::checkDeprecated( $name );

		$type = empty( $type ) ? 'core' : $type;
		if ( isset( $loaded[$type][$name] ) ) {
			return $loaded[$type][$name];
		}

		if ( class_exists( $name ) ) {
			$loaded[$type][$name] = true;
			return true;
		}
		$isloaded = false;
		switch ( $type ) {
			case 'framework':
				$isloaded = self::loadFramework( $name );
				break;
			case 'class':
			case 'core':
				$type = 'core';
				$isloaded = self::loadCore( $name );
				break;
			default:
				$isloaded = self::loadModule( $name, $type );
				break;
		}
		$loaded[$type][$name] = $isloaded;
		return $loaded[$type][$name];
	}

	/**
	 * XoopsLoad::_checkDeprecated()
	 *
	 * @return
	 */
	private static function checkDeprecated( $name = null )
	{
		static $deprecated;

		if ( !isset( $deprecated ) ) {
			$deprecated = array(
				'uploader' => 'xoopsmediauploader',
				'utility' => 'xoopsutility',
				'captcha' => 'xoopscaptcha',
				'cache' => 'xoopscache',
				'file' => 'xoopsfile',
				'model' => 'xoopsmodelfactory',
				'calendar' => 'xoopscalendar',
				'userutility' => 'xoopsuserutility',
				);
		}
		$name = strtolower( $name );
		if ( array_key_exists( $name, $deprecated ) ) {
			if ( isset( $GLOBALS['xoopsLogger'] ) ) {
				$GLOBALS['xoopsLogger']->addDeprecated( "xoops_load('{$name}') is deprecated, use xoops_load('{$deprecated[$name]}')" );
			} else {
				trigger_error( "xoops_load('{$name}') is deprecated, use xoops_load('{$deprecated[$name]}')", E_USER_WARNING );
			}
			$name = $deprecated[$name];
		}
		return $name;
	}

	/**
	 * Load core class
	 *
	 * @access private
	 */
	public static function loadCore( $name )
	{
		static $configs;

		if ( !isset( $configs ) ) {
			$configs = self::loadCoreConfig();
		}
		if ( isset( $configs[$name] ) ) {
			require $configs[$name];
			if ( class_exists( $name ) && method_exists( $name, '__autoload' ) ) {
				call_user_func( array( $name , '__autoload' ) );
			}
			return true;
		} elseif ( file_exists( $file = XOOPS_ROOT_PATH . '/class/' . $name . '.php' ) ) {
			include_once $file;
			$class = 'Xoops' . ucfirst( $name );

			echo $class;

			if ( class_exists( $class ) ) {
				return $class;
			} else {
				trigger_error( 'Class ' . $name . ' not found in file ' . __FILE__ . 'at line ' . __LINE__, E_USER_WARNING );
			}
		}
		return false;
	}

	/**
	 * Load Framework class
	 *
	 * @access private
	 */
	public static function loadFramework( $name )
	{
		if ( !file_exists( $file = XOOPS_ROOT_PATH . '/Frameworks/' . $name . '/xoops' . $name . '.php' ) ) {
			trigger_error( 'File ' . str_replace( XOOPS_ROOT_PATH, '', $file ) . ' not found in file ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING );
			return false;
		}
		require $file;
		$class = 'Xoops' . ucfirst( $name );
		if ( class_exists( $class ) ) {
			return $class;
		}
	}
	/**
	 * Load module class
	 *
	 * @access private
	 */
	public static function loadModule( $name, $dirname = null )
	{
		if ( empty( $dirname ) ) {
			return false;
		}
		if ( file_exists( $file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/class/' . $name . '.php' ) ) {
			require $file;
			if ( class_exists( ucfirst( $dirname ) . ucfirst( $name ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * XoopsLoad::loadCoreConfig()
	 *
	 * @return
	 */
	private static function loadCoreConfig()
	{
		return $configs = array(
			'xoopssystemgui' => XOOPS_ROOT_PATH . '/modules/system/class/gui.php',
			'xoopssystemcpanel' => XOOPS_ROOT_PATH . '/modules\system\class\cpanel.php',
			'xooslatheme' => XOOPS_ROOT_PATH . '/class/theme.php',
			'xooslathemefactory' => XOOPS_ROOT_PATH . '/class/theme.php',
			'xooslaadminthemefactory' => XOOPS_ROOT_PATH . '/class/theme.php',

			'xooslapagebuilder' => XOOPS_ROOT_PATH . '/class/theme_blocks.php',
			'xoopsauth' => XOOPS_ROOT_PATH . '/class/auth/auth.php',
			'xoopsauthfactory' => XOOPS_ROOT_PATH . '/class/auth/authfactory.php',
			'xoopsauthldap' => XOOPS_ROOT_PATH . '/class/auth/auth_ldap.php',
			'xoopsauthprovisionning' => XOOPS_ROOT_PATH . '/class/auth/auth_provisionning.php',

			'xoopscache' => XOOPS_ROOT_PATH . '/class/cache/xoopscache.php',
			'xoopscacheengine' => XOOPS_ROOT_PATH . '/class/cache/xoopscache.php',

			'xoopscaptcha' => XOOPS_ROOT_PATH . '/class/captcha/xoopscaptcha.php',

			'xoopsdatabase' => XOOPS_ROOT_PATH . '/class/database/database.php',
			'xoopsdatabasefactory' => XOOPS_ROOT_PATH . '/class/database/databasefactory.php',
			'sqlutility' => XOOPS_ROOT_PATH . '/class/database/sqlutility.php',

			'xoopsfile' => XOOPS_ROOT_PATH . '/class/file/xoopsfile.php',
			'xoopsfilehandler' => XOOPS_ROOT_PATH . '/class/file/file.php',
			'xoopsfolderhandler' => XOOPS_ROOT_PATH . '/class/file/folder.php',

			'formdhtmltextarea' => XOOPS_ROOT_PATH . '/class/xoopseditor/dhtmltextarea/dhtmltextarea.php',
			'formtextarea' => XOOPS_ROOT_PATH . '/class/xoopseditor/textarea/textarea.php',
			'xoopseditor' => XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php',
			'xoopseditorhandler' => XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php',

			'xoopslogger' => XOOPS_ROOT_PATH . '/class/logger/xoopslogger.php',

			'smarty' => XOOPS_PATH . '/smarty/Smarty.class.php',
			'snoopy' => XOOPS_ROOT_PATH . '/libraries/3rdparty/snoopy/snoopy.php',

			'tar' => XOOPS_ROOT_PATH . '/class/class.tar.php',
			'criteria' => XOOPS_ROOT_PATH . '/class/criteria.php',
			'criteriacompo' => XOOPS_ROOT_PATH . '/class/criteria.php',
			'criteriaelement' => XOOPS_ROOT_PATH . '/class/criteria.php',

			'xoopscommentrenderer' => XOOPS_ROOT_PATH . '/class/commentrenderer.php',

			'xoopskernel' => XOOPS_ROOT_PATH . '/class/xoopskernel.php',
			'xoopsdownloader' => XOOPS_ROOT_PATH . '/class/downloader.php',
			'xoopszipdownloader' => XOOPS_ROOT_PATH . '/class/zipdownloader.php',

			'xoopslists' => XOOPS_ROOT_PATH . '/class/xoopslists.php',
			'xoopslocalabstract' => XOOPS_ROOT_PATH . '/class/xoopslocal.php',

			'mytextsanitizer' => XOOPS_ROOT_PATH . '/class/module.textsanitizer.php',
			'mytextsanitizerextension' => XOOPS_ROOT_PATH . '/class/module.textsanitizer.php',

			'phpmailer' => XOOPS_ROOT_PATH . '/libraries/3rdparty/phpmailer/class.phpmailer.php',

			'bloggerapi' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/bloggerapi.php',
			'saxparser' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/saxparser.php',
			'xmltaghandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/xmltaghandler.php',
			'rssauthorhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsscategoryhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsscommentshandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsscopyrighthandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssdescriptionhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssdocshandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssgeneratorhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssguidhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssheighthandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssimagehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssitemhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsslanguagehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsslastbuilddatehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsslinkhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssmanagingeditorhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssnamehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsspubdatehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsssourcehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsstextinputhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsstitlehandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssttlhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rssurlhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsswebmasterhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'rsswidthhandler' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'xoopsxmlrss2parser' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rss/xmlrss2parser.php',
			'xoopsthemesetparser' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/themesetparser.php',
            'metaweblogapi' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/metaweblogapi.php',
			'movabletypeapi' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/movabletypeapi.php',
			'xoopsxmlrpcapi' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpcapi.php',
			'xoopsxmlrpcarray' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcbase64' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcboolean' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcdatetime' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcdocument' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcdouble' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcfault' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcint' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcparser' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpcparser.php',
			'xoopsxmlrpcrequest' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcresponse' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcstring' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpcstruct' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsxmlrpctag' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xmlrpctag.php',
			'xoopsapi' => XOOPS_ROOT_PATH . '/libraries/3rdparty/xml/rpc/xoopsapi.php',
			'xoopsavatar' => XOOPS_ROOT_PATH . '/kernel/avatar.php',
			'xoopsavatarhandler' => XOOPS_ROOT_PATH . '/kernel/avatar.php',
			'xoopsavataruserlink' => XOOPS_ROOT_PATH . '/kernel/avataruserlink.php',
			'xoopsavataruserlinkhandler' => XOOPS_ROOT_PATH . '/kernel/avataruserlink.php',
			'xoopsbanner' => XOOPS_ROOT_PATH . '/kernel/banner.php',
			'xoopsbannerhandler' => XOOPS_ROOT_PATH . '/kernel/banner.php',
			'xoopsbannerclient' => XOOPS_ROOT_PATH . '/kernel/bannerclient.php',
			'xoopsbannerclienthandler' => XOOPS_ROOT_PATH . '/kernel/bannerclient.php',
			'xoopsbannerfinish' => XOOPS_ROOT_PATH . '/kernel/bannerfinish.php',
			'xoopsbannerfinishhandler' => XOOPS_ROOT_PATH . '/kernel/bannerfinish.php',
			'xoopsblock' => XOOPS_ROOT_PATH . '/kernel/block.php',
			'xoopsblockhandler' => XOOPS_ROOT_PATH . '/kernel/block.php',
			'xoopscomment' => XOOPS_ROOT_PATH . '/kernel/comment.php',
			'xoopscommenthandler' => XOOPS_ROOT_PATH . '/kernel/comment.php',
			'xoopsconfigcategory' => XOOPS_ROOT_PATH . '/kernel/configcategory.php',
			'xoopsconfigcategoryhandler' => XOOPS_ROOT_PATH . '/kernel/configcategory.php',
			'xoopsconfighandler' => XOOPS_ROOT_PATH . '/kernel/config.php',
			'xoopsconfigitem' => XOOPS_ROOT_PATH . '/kernel/configitem.php',
			'xoopsconfigitemhandler' => XOOPS_ROOT_PATH . '/kernel/configitem.php',
			'xoopsconfigoption' => XOOPS_ROOT_PATH . '/kernel/configoption.php',
			'xoopsconfigoptionhandler' => XOOPS_ROOT_PATH . '/kernel/configoption.php',
			'xoopsgroup' => XOOPS_ROOT_PATH . '/kernel/group.php',
			'xoopsgrouphandler' => XOOPS_ROOT_PATH . '/kernel/group.php',
			'xoopsgroupperm' => XOOPS_ROOT_PATH . '/kernel/groupperm.php',
			'xoopsgrouppermhandler' => XOOPS_ROOT_PATH . '/kernel/groupperm.php',
			'xoopsimage' => XOOPS_ROOT_PATH . '/kernel/image.php',
			'xoopsimagecategory' => XOOPS_ROOT_PATH . '/kernel/imagecategory.php',
			'xoopsimagecategoryhandler' => XOOPS_ROOT_PATH . '/kernel/imagecategory.php',
			'xoopsimagehandler' => XOOPS_ROOT_PATH . '/kernel/image.php',
			'xoopsimageset' => XOOPS_ROOT_PATH . '/kernel/imageset.php',
			'xoopsimagesethandler' => XOOPS_ROOT_PATH . '/kernel/imageset.php',
			'xoopsimagesetimg' => XOOPS_ROOT_PATH . '/kernel/imagesetimg.php',
			'xoopsimagesetimghandler' => XOOPS_ROOT_PATH . '/kernel/imagesetimg.php',
			'xoopslocal' => XOOPS_ROOT_PATH . '/include/xoopslocal.php',
			'xoopsform' => XOOPS_ROOT_PATH . '/class/xoopsform/form.php',
			'xoopsformbutton' => XOOPS_ROOT_PATH . '/class/xoopsform/formbutton.php',
			'xoopsformbuttontray' => XOOPS_ROOT_PATH . '/class/xoopsform/formbuttontray.php',
			'xoopsformcalendar' => XOOPS_ROOT_PATH . '/class/xoopsform/formcalendar.php',
			'xoopsformcaptcha' => XOOPS_ROOT_PATH . '/class/xoopsform/formcaptcha.php',
			'xoopsformcheckbox' => XOOPS_ROOT_PATH . '/class/xoopsform/formcheckbox.php',
			'xoopsformcolorpicker' => XOOPS_ROOT_PATH . '/class/xoopsform/formcolorpicker.php',
			'xoopsformcontainer' => XOOPS_ROOT_PATH . '/class/xoopsform/formcontainer.php',
			'xoopsformdatetime' => XOOPS_ROOT_PATH . '/class/xoopsform/formdatetime.php',
			'xoopsformdhtmltextarea' => XOOPS_ROOT_PATH . '/class/xoopsform/formdhtmltextarea.php',
			'xoopsformeditor' => XOOPS_ROOT_PATH . '/class/xoopsform/formeditor.php',
			'xoopsformelement' => XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php',
			'xoopsformelementtray' => XOOPS_ROOT_PATH . '/class/xoopsform/formelementtray.php',
			'xoopsformfile' => XOOPS_ROOT_PATH . '/class/xoopsform/formfile.php',
			'xoopsformhidden' => XOOPS_ROOT_PATH . '/class/xoopsform/formhidden.php',
			'xoopsformhiddentoken' => XOOPS_ROOT_PATH . '/class/xoopsform/formhiddentoken.php',
			'xoopsformlabel' => XOOPS_ROOT_PATH . '/class/xoopsform/formlabel.php',
			'xoopsformloader' => XOOPS_ROOT_PATH . '/class/xoopsformloader.php',
			'xoopsformpassword' => XOOPS_ROOT_PATH . '/class/xoopsform/formpassword.php',
			'xoopsformradio' => XOOPS_ROOT_PATH . '/class/xoopsform/formradio.php',
			'xoopsformradioyn' => XOOPS_ROOT_PATH . '/class/xoopsform/formradioyn.php',
			'xoopsformselect' => XOOPS_ROOT_PATH . '/class/xoopsform/formselect.php',
			'xoopsformselectcheckgroup' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectcheckgroup.php',
			'xoopsformselectcountry' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectcountry.php',
			'xoopsformselecteditor' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecteditor.php',
			'xoopsformselectgroup' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectgroup.php',
			'xoopsformselectlang' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectlang.php',
			'xoopsformselectmatchoption' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectmatchoption.php',
			'xoopsformselectuser' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectuser.php',
			'xoopsformselecttheme' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecttheme.php',
			'xoopsformselecttimezone' => XOOPS_ROOT_PATH . '/class/xoopsform/formselecttimezone.php',
			'xoopsformselectuser' => XOOPS_ROOT_PATH . '/class/xoopsform/formselectuser.php',
			'xoopsformtab' => XOOPS_ROOT_PATH . '/class/xoopsform/formtab.php',
			'xoopsformtabtray' => XOOPS_ROOT_PATH . '/class/xoopsform/formtabtray.php',
			'xoopsformtext' => XOOPS_ROOT_PATH . '/class/xoopsform/formtext.php',
			'xoopsformtextarea' => XOOPS_ROOT_PATH . '/class/xoopsform/formtextarea.php',
			'xoopsformtextdateselect' => XOOPS_ROOT_PATH . '/class/xoopsform/formtextdateselect.php',
			'xoopsgroupformcheckbox' => XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php',
			'xoopsgrouppermform' => XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php',
			'xoopsguestuser' => XOOPS_ROOT_PATH . '/kernel/user.php',
			'xoopsmailer' => XOOPS_ROOT_PATH . '/class/xoopsmailer.php',
			'xoopsmediauploader' => XOOPS_ROOT_PATH . '/class/uploader.php',
			'xoopsmemberhandler' => XOOPS_ROOT_PATH . '/kernel/member.php',
			'xoopsmembership' => XOOPS_ROOT_PATH . '/kernel/membership.php',
			'xoopsmembershiphandler' => XOOPS_ROOT_PATH . '/kernel/membership.php',
			'xoopsmodelfactory' => XOOPS_ROOT_PATH . '/class/model/xoopsmodel.php',
			'xoopsmoduleadmin' => XOOPS_ROOT_PATH . '/class/moduleadmin.php',
			'xoopsmodules' => XOOPS_ROOT_PATH . '/kernel/module.php',
			'xoopsmodulehandler' => XOOPS_ROOT_PATH . '/kernel/module.php',
			'xoopsmultimailer' => XOOPS_ROOT_PATH . '/class/mail/xoopsmultimailer.php',
			'xoopsnotification' => XOOPS_ROOT_PATH . '/kernel/notification.php',
			'xoopsnotificationhandler' => XOOPS_ROOT_PATH . '/kernel/notification.php',
			'xoopsobject' => XOOPS_ROOT_PATH . '/kernel/object.php',
			'xoopsobjecthandler' => XOOPS_ROOT_PATH . '/kernel/object.php',
			'xoopsobjecttree' => XOOPS_ROOT_PATH . '/class/tree.php',
			'xoopsonline' => XOOPS_ROOT_PATH . '/kernel/online.php',
			'xoopsonlinehandler' => XOOPS_ROOT_PATH . '/kernel/online.php',
			'xoopspagenav' => XOOPS_ROOT_PATH . '/class/pagenav.php',
			'xoopspersistableobjecthandler' => XOOPS_ROOT_PATH . '/kernel/object.php',
			'xoopspreload' => XOOPS_ROOT_PATH . '/class/preload.php',
			'xoopspreloaditem' => XOOPS_ROOT_PATH . '/class/preload.php',
			'xoopsranks' => XOOPS_ROOT_PATH . '/kernel/ranks.php',
			'xoopsrankshandler' => XOOPS_ROOT_PATH . '/kernel/ranks.php',
			'xoopsregistry' => XOOPS_ROOT_PATH . '/class/registry.php',
			'xoopssecurity' => XOOPS_ROOT_PATH . '/class/xoopssecurity.php',
			'xoopssessionhandler' => XOOPS_ROOT_PATH . '/kernel/session.php',
			'xoopssimpleform' => XOOPS_ROOT_PATH . '/class/xoopsform/simpleform.php',
			'xoopssmilies' => XOOPS_ROOT_PATH . '/kernel/smilies.php',
			'xoopssmilieshandler' => XOOPS_ROOT_PATH . '/kernel/smilies.php',
			'xoopstableform' => XOOPS_ROOT_PATH . '/class/xoopsform/tableform.php',
			'xoopstardownloader' => XOOPS_ROOT_PATH . '/class/tardownloader.php',
			'xoopsthemeblocksplugin' => XOOPS_ROOT_PATH . '/class/theme_blocks.php',
			'xoopsthemefactory' => XOOPS_ROOT_PATH . '/class/theme.php',
			'xoopsthemeform' => XOOPS_ROOT_PATH . '/class/xoopsform/themeform.php',
			'xoopsthemeplugin' => XOOPS_ROOT_PATH . '/class/theme.php',
			'xoopstpl' => XOOPS_ROOT_PATH . '/class/template.php',
			'xoopstplfile' => XOOPS_ROOT_PATH . '/kernel/tplfile.php',
			'xoopstplfilehandler' => XOOPS_ROOT_PATH . '/kernel/tplfile.php',
			'xoopstplset' => XOOPS_ROOT_PATH . '/kernel/tplset.php',
			'xoopstplsethandler' => XOOPS_ROOT_PATH . '/kernel/tplset.php',
			'xoopsuser' => XOOPS_ROOT_PATH . '/kernel/user.php',
			'xoopsuserhandler' => XOOPS_ROOT_PATH . '/kernel/user.php',
			'xoopsuserutility' => XOOPS_ROOT_PATH . '/class/userutility.php',
			'xoopsutility' => XOOPS_ROOT_PATH . '/class/utility/xoopsutility.php',
			'zipfile' => XOOPS_ROOT_PATH . '/class/class.zipfile.php',
			);
	}

	/**
	 * XoopsLoad::loadConfig()
	 *
	 * @param mixed $data
	 * @return
	 */
	public function loadConfig( $data = null )
	{
		if ( is_array( $data ) ) {
			$configs = $data;
		} else {
			if ( ! empty( $data ) ) {
				$dirname = $data;
			} elseif ( is_object( $GLOBALS['xoopsModule'] ) ) {
				$dirname = $GLOBALS['xoopsModule']->getVar( 'dirname', 'n' );
			} else {
				return false;
			}
			if ( file_exists( $file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/autoload.php' ) ) {
				if ( ! $configs = include $file ) {
					return false;
				}
			}
		}
		return $configs = array_merge( self::loadCoreConfig(), $configs );
	}

	/**
	 * XoopsLoad::language()
	 *
	 * @return
	 */
	public function loadLanguage()
	{
	}
}
// To be enabled in XOOPS 3.0
spl_autoload_register( array( 'XoopsLoad', 'load' ) );

?>