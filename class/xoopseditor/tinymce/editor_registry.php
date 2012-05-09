<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_ROOT_PATH' ) or die( 'Restricted access' );

return $config = array(
	'name' => 'tinymce',
	'class' => 'XoopsFormTinymce',
	'file' => XOOPS_ROOT_PATH . '/class/xoopseditor/tinymce/formtinymce.php',
	'title' => _XOOPS_EDITOR_TINYMCE,
	'order' => 5,
	'nohtml' => 0
	);

?>