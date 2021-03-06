<?php

function smarty_function_xoBlocks( $argStr, &$smarty )
{
	static $block_objs;
	static $allowed_blocks;

	if ( !isset( $argStr['bid'] ) ) {
		return;
	}

	$block_id = ( isset( $argStr['bid'] ) ) ? (int)$argStr['bid'] : 0;
	$block_class = ( isset( $argStr['class'] ) && !empty( $argStr['class'] ) == 1 ) ? strip_tags( strtolower( $argStr['class'] ) ) : '';
	$block_read_more = ( isset( $argStr['readmore'] ) && !empty( $argStr['readmore'] ) == 1 ) ? htmlspecialchars( $argStr['class'] ) : '';
	/**
	* get the block if it existsits
	**/
	if ( !isset( $block_objs[$block_id] ) ) {
		$blockObj = new XoopsBlock( $block_id );
		if ( !is_object( $blockObj ) ) {
			return;
		}
		$block_objs[$block_id] = $blockObj;
	} else {
		$blockObj = $block_objs[$block_id];
	}
	if ( !$blockObj->getVar( 'visible' ) ) {
		return;
	}

	$user_groups = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : array( XOOPS_GROUP_ANONYMOUS );
	if ( count( $allowed_blocks ) == 0 ) {
		$allowed_blocks = XoopsBlock::getAllBlocksByGroup( $user_groups, false );
	}
	if ( $groups ) {
		if ( !array_intersect( $user_groups, $groups ) ) {
			return;
		}
	} else {
		if ( !in_array( $block_id, $allowed_blocks ) ) {
			return;
		}
	}

	$xoopsLogger = &XoopsLogger::getInstance();
	$template = &$GLOBALS['xoopsTpl'];

	$bcachetime = $blockObj->getVar( 'bcachetime' );
	if ( empty( $bcachetime ) ) {
		$template->caching = 0;
	} else {
		$template->caching = 2;
		$template->cache_lifetime = $bcachetime;
	}

	$template->setCompileId( $blockObj->getVar( 'dirname', 'n' ) );
	$tplName = $blockObj->getVar( 'template' ) ? "db:{$blockObj->getVar( 'template' )}" : 'db:system_block_dummy.html';
	$cacheid = 'blk_' . $block_id;
	if ( !$bcachetime || !$template->is_cached( $tplName, $cacheid ) ) {
		$xoopsLogger->addBlock( $blockObj->getVar( 'name' ) );
		if ( !( $block = $blockObj->buildBlock() ) ) {
			return;
		}
		$template->assign( 'block', $block );
		$content = $GLOBALS['xoopsTpl']->fetch( $tplName, $cacheid );
	} else {
		$xoopsLogger->addBlock( $blockObj->getVar( 'name' ), true, $bcachetime );
		$content = $GLOBALS['xoopsTpl']->fetch( $tplName, $cacheid );
	}
	$template->setCompileId( $blockObj->getVar( 'dirname', 'n' ) );

	$block = '';
	$class = ( $block_class ) ? 'class="' . $block_class . '"' : '';
	$block = '<div class="block"><div ' . $class . '>';
	if ( $blockObj->getVar( 'title' ) ) {
		$block .= '<h2 class="title">' . $blockObj->getVar( 'title' ) . '</h2>';
	}
	$block .= '<div class="block-content">' . $content . '</div>';
	if ( $block_read_more ) {
		$block .= '<span class="block-read-more">Read More</span>';
	}
	$block .= '</div>';
	$block .= '</div>';
	return $block;
}

?>
