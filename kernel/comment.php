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
 * XOOPS Kernel Class
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package kernel
 * @since 2.0.0
 * @author Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version $Id$
 */defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

/**
 * A Comment
 *
 * @package kernel
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class XoopsComment extends XoopsObject {
	/**
	 * Constructor
	 */
	public function __Construct()
	{
		$this->initVar( 'com_id', XOBJ_DTYPE_INT, null, false );
		$this->initVar( 'com_pid', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_modid', XOBJ_DTYPE_INT, null, false );
		$this->initVar( 'com_icon', XOBJ_DTYPE_OTHER, null, false );
		$this->initVar( 'com_title', XOBJ_DTYPE_TXTBOX, null, true, 255, true );
		$this->initVar( 'com_text', XOBJ_DTYPE_TXTAREA, null, true, null, true );
		$this->initVar( 'com_created', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_modified', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_uid', XOBJ_DTYPE_INT, 0, true );
		$this->initVar( 'com_ip', XOBJ_DTYPE_OTHER, null, false );
		$this->initVar( 'com_sig', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_itemid', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_rootid', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_status', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'com_exparams', XOBJ_DTYPE_OTHER, null, false, 255 );
		$this->initVar( 'dohtml', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'dosmiley', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'doxcode', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'doimage', XOBJ_DTYPE_INT, 0, false );
		$this->initVar( 'dobr', XOBJ_DTYPE_INT, 0, false );
	}

	/**
	 * Returns Class Base Variable com_id
	 */
	public function id( $format = 'N' )
	{
		return $this->getVar( 'com_id', $format );
	}

	/**
	 * Returns Class Base Variable com_id
	 */
	public function com_id( $format = '' )
	{
		return $this->getVar( 'com_id', $format );
	}

	/**
	 * Returns Class Base Variable com_pid
	 */
	public function com_pid( $format = '' )
	{
		return $this->getVar( 'com_pid', $format );
	}

	/**
	 * Returns Class Base Variable com_modid
	 */
	public function com_modid( $format = '' )
	{
		return $this->getVar( 'com_modid', $format );
	}

	/**
	 * Returns Class Base Variable com_icon
	 */
	public function com_icon( $format = '' )
	{
		return $this->getVar( 'com_icon', $format );
	}

	/**
	 * Returns Class Base Variable bid
	 */
	public function com_title( $format = '' )
	{
		return $this->getVar( 'com_title', $format );
	}

	/**
	 * Returns Class Base Variable com_text
	 */
	public function com_text( $format = '' )
	{
		return $this->getVar( 'com_text', $format );
	}

	/**
	 * Returns Class Base Variable com_created
	 */
	public function com_created( $format = '' )
	{
		return $this->getVar( 'com_created', $format );
	}

	/**
	 * Returns Class Base Variable com_modified
	 */
	public function com_modified( $format = '' )
	{
		return $this->getVar( 'com_modified', $format );
	}

	/**
	 * Returns Class Base Variable com_uid
	 */
	public function com_uid( $format = '' )
	{
		return $this->getVar( 'com_uid', $format );
	}

	/**
	 * Returns Class Base Variable com_ip
	 */
	public function com_ip( $format = '' )
	{
		return $this->getVar( 'com_ip', $format );
	}

	/**
	 * Returns Class Base Variable com_sig
	 */
	public function com_sig( $format = '' )
	{
		return $this->getVar( 'com_sig', $format );
	}

	/**
	 * Returns Class Base Variable com_itemid
	 */
	public function com_itemid( $format = '' )
	{
		return $this->getVar( 'com_itemid', $format );
	}

	/**
	 * Returns Class Base Variable com_rootid
	 */
	public function com_rootid( $format = '' )
	{
		return $this->getVar( 'com_rootid', $format );
	}

	/**
	 * Returns Class Base Variable com_status
	 */
	public function com_status( $format = '' )
	{
		return $this->getVar( 'com_status', $format );
	}

	/**
	 * Returns Class Base Variable com_exparams
	 */
	public function com_exparams( $format = '' )
	{
		return $this->getVar( 'com_exparams', $format );
	}

	/**
	 * Returns Class Base Variable bid
	 */
	public function dohtml( $format = '' )
	{
		return $this->getVar( 'dohtml', $format );
	}

	/**
	 * Returns Class Base Variable dosmiley
	 */
	public function dosmiley( $format = '' )
	{
		return $this->getVar( 'dosmiley', $format );
	}

	/**
	 * Returns Class Base Variable doxcode
	 */
	public function doxcode( $format = '' )
	{
		return $this->getVar( 'doxcode', $format );
	}

	/**
	 * Returns Class Base Variable doimage
	 */
	public function doimage( $format = '' )
	{
		return $this->getVar( 'doimage', $format );
	}

	/**
	 * Returns Class Base Variable dobr
	 */
	public function dobr( $format = '' )
	{
		return $this->getVar( 'dobr', $format );
	}

	/**
	 * Is this comment on the root level?
	 *
	 * @return bool
	 */
	public function isRoot()
	{
		return ( $this->getVar( 'com_id' ) == $this->getVar( 'com_rootid' ) );
	}
}

/**
 * XOOPS comment handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS comment class objects.
 *
 * @package kernel
 * @subpackage comment
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class XoopsCommentHandler extends XoopsObjectHandler {
	/**
	 * Create a {@link XoopsComment}
	 *
	 * @param bool $isNew Flag the object as "new"?
	 * @return object
	 */
	public function create( $isNew = true )
	{
		$comment = new XoopsComment();
		if ( $isNew ) {
			$comment->setNew();
		}
		return $comment;
	}

	/**
	 * Retrieve a {@link XoopsComment}
	 *
	 * @param int $id ID
	 * @return object {@link XoopsComment}, FALSE on fail
	 */
	public function get( $id )
	{
		$comment = false;
		$id = intval( $id );
		if ( $id > 0 ) {
			$sql = 'SELECT * FROM ' . $this->db->prefix( 'xoopscomments' ) . ' WHERE com_id=' . $id;
			if ( !$result = $this->db->query( $sql ) ) {
				return $comment;
			}
			$numrows = $this->db->getRowsNum( $result );
			if ( $numrows == 1 ) {
				$comment = new XoopsComment();
				$comment->assignVars( $this->db->fetchArray( $result ) );
			}
		}
		return $comment;
	}

	/**
	 * Write a comment to database
	 *
	 * @param object $ &$comment
	 * @return bool
	 */
	public function insert( XoopsComment $comment )
	{
		if ( !$comment->isDirty() ) {
			return true;
		}
		if ( !$comment->cleanVars() ) {
			return false;
		}
		foreach( $comment->cleanVars as $k => $v ) {
			$ {
				$k} = $v;
		}
		if ( $comment->isNew() ) {
			$com_id = $this->db->genId( 'xoopscomments_com_id_seq' );
			$sql = sprintf( 'INSERT INTO %s (com_id, com_pid, com_modid, com_icon, com_title, com_text, com_created, com_modified, com_uid, com_ip, com_sig, com_itemid, com_rootid, com_status, com_exparams, dohtml, dosmiley, doxcode, doimage, dobr) VALUES (%u, %u, %u, %s, %s, %s, %u, %u, %u, %s, %u, %u, %u, %u, %s, %u, %u, %u, %u, %u)', $this->db->prefix( 'xoopscomments' ), $com_id, $com_pid, $com_modid, $this->db->quoteString( $com_icon ), $this->db->quoteString( $com_title ), $this->db->quoteString( $com_text ), $com_created, $com_modified, $com_uid, $this->db->quoteString( $com_ip ), $com_sig, $com_itemid, $com_rootid, $com_status, $this->db->quoteString( $com_exparams ), $dohtml, $dosmiley, $doxcode, $doimage, $dobr );
		} else {
			$sql = sprintf( 'UPDATE %s SET com_pid = %u, com_icon = %s, com_title = %s, com_text = %s, com_created = %u, com_modified = %u, com_uid = %u, com_ip = %s, com_sig = %u, com_itemid = %u, com_rootid = %u, com_status = %u, com_exparams = %s, dohtml = %u, dosmiley = %u, doxcode = %u, doimage = %u, dobr = %u WHERE com_id = %u', $this->db->prefix( 'xoopscomments' ), $com_pid, $this->db->quoteString( $com_icon ), $this->db->quoteString( $com_title ), $this->db->quoteString( $com_text ), $com_created, $com_modified, $com_uid, $this->db->quoteString( $com_ip ), $com_sig, $com_itemid, $com_rootid, $com_status, $this->db->quoteString( $com_exparams ), $dohtml, $dosmiley, $doxcode, $doimage, $dobr, $com_id );
		}
		if ( !$result = $this->db->query( $sql ) ) {
			return false;
		}
		if ( empty( $com_id ) ) {
			$com_id = $this->db->getInsertId();
		}
		$comment->assignVar( 'com_id', $com_id );
		return true;
	}

	/**
	 * Delete a {@link XoopsComment} from the database
	 *
	 * @param object $ &$comment
	 * @return bool
	 */
	public function delete( XoopsComment $comment )
	{
		$sql = sprintf( 'DELETE FROM %s WHERE com_id = %u', $this->db->prefix( 'xoopscomments' ), $comment->getVar( 'com_id' ) );
		if ( !$result = $this->db->query( $sql ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get some {@link XoopsComment}s
	 *
	 * @param object $criteria
	 * @param bool $id_as_key Use IDs as keys into the array?
	 * @return array Array of {@link XoopsComment} objects
	 */
	public function getObjects( CriteriaElement $criteria = null, $id_as_key = false )
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM ' . $this->db->prefix( 'xoopscomments' );
		if ( isset( $criteria ) ) {
			$sql .= ' ' . $criteria->renderWhere();
			$sort = ( $criteria->getSort() != '' ) ? $criteria->getSort() : 'com_id';
			$sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query( $sql, $limit, $start );
		if ( !$result ) {
			return $ret;
		} while ( $myrow = $this->db->fetchArray( $result ) ) {
			$comment = new XoopsComment();
			$comment->assignVars( $myrow );
			if ( !$id_as_key ) {
				$ret[] = $comment;
			} else {
				$ret[$myrow['com_id']] = $comment;
			}
			unset( $comment );
		}
		return $ret;
	}

	/**
	 * Count Comments
	 *
	 * @param object $criteria {@link CriteriaElement}
	 * @return int Count
	 */
	public function getCount( CriteriaElement $criteria = null )
	{
		$sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix( 'xoopscomments' );
		if ( isset( $criteria ) ) {
			$sql .= ' ' . $criteria->renderWhere();
		}
		if ( !$result = $this->db->query( $sql ) ) {
			return 0;
		}
		list ( $count ) = $this->db->fetchRow( $result );
		return $count;
	}

	/**
	 * Delete multiple comments
	 *
	 * @param object $criteria {@link CriteriaElement}
	 * @return bool
	 */
	public function deleteAll( CriteriaElement $criteria = null )
	{
		$sql = 'DELETE FROM ' . $this->db->prefix( 'xoopscomments' );
		if ( isset( $criteria ) ) {
			$sql .= ' ' . $criteria->renderWhere();
		}
		if ( !$result = $this->db->query( $sql ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get a list of comments
	 *
	 * @param object $criteria {@link CriteriaElement}
	 * @return array Array of raw database records
	 */
	public function getList( CriteriaElement $criteria = null )
	{
		$comments = $this->getObjects( $criteria, true );
		$ret = array();
		foreach( array_keys( $comments ) as $i ) {
			$ret[$i] = $comments[$i]->getVar( 'com_title' );
		}
		return $ret;
	}

	/**
	 * Retrieves comments for an item
	 *
	 * @param int $module_id Module ID
	 * @param int $item_id Item ID
	 * @param string $order Sort order
	 * @param int $status Status of the comment
	 * @param int $limit Max num of comments to retrieve
	 * @param int $start Start offset
	 * @return array Array of {@link XoopsComment} objects
	 */
	public function getByItemId( $module_id, $item_id, $order = null, $status = null, $limit = null, $start = 0 )
	{
		$criteria = new CriteriaCompo( new Criteria( 'com_modid', intval( $module_id ) ) );
		$criteria->add( new Criteria( 'com_itemid', intval( $item_id ) ) );
		if ( isset( $status ) ) {
			$criteria->add( new Criteria( 'com_status', intval( $status ) ) );
		}
		if ( isset( $order ) ) {
			$criteria->setOrder( $order );
		}
		if ( isset( $limit ) ) {
			$criteria->setLimit( $limit );
			$criteria->setStart( $start );
		}
		return $this->getObjects( $criteria );
	}

	/**
	 * Gets total number of comments for an item
	 *
	 * @param int $module_id Module ID
	 * @param int $item_id Item ID
	 * @param int $status Status of the comment
	 * @return array Array of {@link XoopsComment} objects
	 */
	public function getCountByItemId( $module_id, $item_id, $status = null )
	{
		$criteria = new CriteriaCompo( new Criteria( 'com_modid', intval( $module_id ) ) );
		$criteria->add( new Criteria( 'com_itemid', intval( $item_id ) ) );
		if ( isset( $status ) ) {
			$criteria->add( new Criteria( 'com_status', intval( $status ) ) );
		}
		return $this->getCount( $criteria );
	}

	/**
	 * Get the top {@link XoopsComment}s
	 *
	 * @param int $module_id
	 * @param int $item_id
	 * @param strint $order
	 * @param int $status
	 * @return array Array of {@link XoopsComment} objects
	 */
	public function getTopComments( $module_id, $item_id, $order, $status = null )
	{
		$criteria = new CriteriaCompo( new Criteria( 'com_modid', intval( $module_id ) ) );
		$criteria->add( new Criteria( 'com_itemid', intval( $item_id ) ) );
		$criteria->add( new Criteria( 'com_pid', 0 ) );
		if ( isset( $status ) ) {
			$criteria->add( new Criteria( 'com_status', intval( $status ) ) );
		}
		$criteria->setOrder( $order );
		return $this->getObjects( $criteria );
	}

	/**
	 * Retrieve a whole thread
	 *
	 * @param int $comment_rootid
	 * @param int $comment_id
	 * @param int $status
	 * @return array Array of {@link XoopsComment} objects
	 */
	public function getThread( $comment_rootid, $comment_id, $status = null )
	{
		$criteria = new CriteriaCompo( new Criteria( 'com_rootid', intval( $comment_rootid ) ) );
		$criteria->add( new Criteria( 'com_id', intval( $comment_id ), '>=' ) );
		if ( isset( $status ) ) {
			$criteria->add( new Criteria( 'com_status', intval( $status ) ) );
		}
		return $this->getObjects( $criteria );
	}

	/**
	 * Update
	 *
	 * @param object $ &$comment       {@link XoopsComment} object
	 * @param string $field_name Name of the field
	 * @param mixed $field_value Value to write
	 * @return bool
	 */
	public function updateByField( XoopsComment &$comment, $field_name, $field_value )
	{
		$comment->unsetNew();
		$comment->setVar( $field_name, $field_value );
		return $this->insert( $comment );
	}

	/**
	 * Delete all comments for one whole module
	 *
	 * @param int $module_id ID of the module
	 * @return bool
	 */
	public function deleteByModule( $module_id )
	{
		return $this->deleteAll( new Criteria( 'com_modid', intval( $module_id ) ) );
	}
}

?>