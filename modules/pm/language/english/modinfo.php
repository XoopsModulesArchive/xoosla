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
 * Xoosla Language
 *
 * @copyright Xoosla http://sourceforge.net/projects/xoosla/
 * @license http://www.fsf.org/licensing/licenses/gpl.html GNU General Public License
 * @package Language
 * @since v1.0.0
 * @author John
 * @version $Id$
 */
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

define( '_PM_MI_NAME', 'Private Messaging' );
define( '_PM_MI_DESC', 'Module for private messaging between users' );
define( '_PM_MI_INDEX', 'Home' );
define( '_PM_MI_PRUNE', 'Prune Messages' );
define( '_PM_MI_LINK_TITLE', 'PM Link' );
define( '_PM_MI_LINK_DESCRIPTION', 'Shows a link to send a private message to the user' );
define( '_PM_MI_MESSAGE', 'Write a message to' );
define( '_PM_MI_PRUNESUBJECT', 'Prune PM subject line' );
define( '_PM_MI_PRUNESUBJECT_DESC', 'This will be the subject of the PM to the user, received after a PM prune' );
define( '_PM_MI_PRUNEMESSAGE', 'Prune PM body message' );
define( '_PM_MI_PRUNEMESSAGE_DESC', 'This message will be in the body of the message to users after one or more of their messages have been removed from their inbox during a PM prune. Use {PM_COUNT} in the text to be replaced with the number of messages removed from this user\'s inbox' );
define( '_PM_MI_PRUNESUBJECTDEFAULT', 'Messages deleted during cleanup' );
define( '_PM_MI_PRUNEMESSAGEDEFAULT', 'During a cleanup of the Private Messaging, XOOPS has deleted {PM_COUNT} messages from your inbox to save space and resources' );
define( '_PM_MI_MAXSAVE', 'Maximum items in savebox' );
define( '_PM_MI_MAXSAVE_DESC', '' );
define( '_PM_MI_PERPAGE', 'Messages per page' );
define( '_PM_MI_PERPAGE_DESC', '' );
define( '_PM_MI_ABOUT', 'About' );