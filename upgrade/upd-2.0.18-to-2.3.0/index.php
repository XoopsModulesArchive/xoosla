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
 * Upgrader from 2.0.18 to 2.3.0
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id: index.php 1369 2008-03-04 02:53:55Z phppp $
 */


class PathStuffController
{
    var $xoopsPath = array(
            'lib'   => '',
            'data'  => '',
            );
    var $path_lookup = array(
            'data'  => 'VAR_PATH',
            'lib'   => 'PATH',
            );
    
    var $validPath = array(
            'data'  => 0,
            'lib'   => 0,
            );
    
    var $permErrors = array(
            'data'  => null,
            );

    function PathStuffController()
    {
        if ( isset( $_SESSION['settings']['VAR_PATH'] ) ) {
            foreach ($this->path_lookup as $req => $sess) {
                $this->xoopsPath[$req] = $_SESSION['settings'][$sess];
            }
        } else {
            $path = XOOPS_ROOT_PATH;
            if ( defined("XOOPS_PATH") ) {
                $this->xoopsPath['lib'] = XOOPS_PATH;
            } elseif ( defined("XOOPS_TRUST_PATH") ) {
                $this->xoopsPath['lib'] = XOOPS_TRUST_PATH;
            } else {
                $this->xoopsPath['lib'] = dirname($path) . "/xoops_lib";
                if ( !is_dir($this->xoopsPath['lib'] . "/") ) {
                    $this->xoopsPath['lib'] = $path . "/xoops_lib";
                }
            }
            if ( defined("XOOPS_DATA_PATH") ) {
                $this->xoopsPath['data'] = XOOPS_DATA_PATH;
            } else {
                $this->xoopsPath['data'] = dirname($path) . "/xoops_data";
                if ( !is_dir($this->xoopsPath['data'] . "/") ) {
                    $this->xoopsPath['data'] = $path . "/xoops_data";
                }
            }
        }
    }

    function execute()
    {
        $this->readRequest();
        $valid = $this->validate();
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'path' ) {
            foreach ($this->path_lookup as $req => $sess) {
                $_SESSION['settings'][$sess] = $this->xoopsPath[$req];
            }
            if ( $valid ) {
                return $_SESSION['settings'];
            } else {
                return false;
            }
        }
    }
    
    function readRequest()
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'path' ) {
            $request = $_POST;
            foreach ($this->path_lookup as $req => $sess) {
                if ( isset($request[$req]) ) {
                    $request[$req] = str_replace( "\\", "/", trim($request[$req]) );
                    if ( substr( $request[$req], -1 ) == '/' ) {
                        $request[$req] = substr( $request[$req], 0, -1 );
                    }
                    $this->xoopsPath[$req] = $request[$req];
                }
            }
        }
    }
    
    function validate()
    {
        foreach (array_keys($this->xoopsPath) as $path) {
            if ($this->checkPath($path)) {
                $this->checkPermissions($path);
            }
        }
        $validPaths = ( array_sum(array_values($this->validPath)) == count(array_keys($this->validPath)) ) ? 1 : 0;
        $validPerms = true;
        foreach ($this->permErrors as $key => $errs) {
            if (empty($errs)) continue;
            foreach ($errs as $path => $status) {
                if (empty($status)) {
                    $validPerms = false;
                    break;
                }
            }
        }
        return ( $validPaths && $validPerms );
    }
    
    function checkPath($PATH = '')
    {
        $ret = 1;
        if ( $PATH == 'lib' || empty($PATH) ) {
            $path = 'lib';
            if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ( $PATH == 'data' || empty($PATH) ) {
            $path = 'data';
            if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        return $ret;
    }
    
    function setPermission($parent, $path, &$error)
    {
        if (is_array($path)) {
            foreach ( array_keys($path) as $item ) {
                if (is_string($item)) {
                    $error[$parent . "/" . $item] = $this->makeWritable( $parent . "/" . $item );
                    if (empty($path[$item])) continue;
                    foreach ($path[$item] as $child) {
                        $this->setPermission( $parent . "/" . $item, $child, $error );
                    }
                } else {
                    $error[$parent . "/" . $path[$item]] = $this->makeWritable( $parent . "/" . $path[$item] );
                }
            }
        } else {
            $error[$parent . "/" . $path] = $this->makeWritable( $parent . "/" . $path );
        }
        return;
    }
    
    function checkPermissions($path = "data")
    {
        $paths = array(
            'data'  => array(
                'caches' => array(
                    'xoops_cache',
                    'smarty_cache',
                    'smarty_compile',
                    ),
                ),
            );
        $errors = array(
            'data'  => null,
            );
        if (!isset($this->xoopsPath[$path])) {
            return false;
        }
        if (!isset($paths[$path])) {
            return true;
        }
        $this->setPermission($this->xoopsPath[$path], $paths[$path], $errors[$path]);
        if ( in_array( false, $errors[$path] ) ) {
            $this->permErrors[$path] = $errors[$path];
            return false;
        }
        return true;
    }
    
    
    /**
     * Write-enable the specified file/folder
     * @param string $path
     * @param string $group
     * @param bool $recurse
     * @return false on failure, method (u-ser,g-roup,w-orld) on success
     */
    function makeWritable( $path, $group = false, $create = true )
    {
        if ( !file_exists( $path ) ) {
            if (!$create) {
                return false;
            } else {
                $perm = 6;
                @mkdir($path, octdec( '0' . $perm . '00' ));
            }
        } else {
            $perm = is_dir( $path ) ? 6 : 7;
        }
        if ( !is_writable($path) ) {
            // First try using owner bit
            @chmod( $path, octdec( '0' . $perm . '00' ) );
            clearstatcache();
            if ( !is_writable( $path ) && $group !== false ) {
                // If group has been specified, try using the group bit
                @chgrp( $path, $group );
                @chmod( $path, octdec( '0' . $perm . $perm . '0' ) );
            }
            clearstatcache();
            if ( !is_writable( $path ) ) {
                @chmod( $path, octdec( '0' . $perm . $perm . $perm ) );
            }
        }
        clearstatcache();
        if ( is_writable( $path ) ) {
            $info = stat( $path );
            //echo $path . ' : ' . sprintf( '%o', $info['mode'] ) . '....';
            if ( $info['mode'] & 0002 ) {
                return 'w';
            } elseif ( $info['mode'] & 0020 ) {
                return 'g';
            }
            return 'u';
        }
        return false;
    }
}

class upgrade_230 extends xoopsUpgrade
{
    var $usedFiles = array( 'mainfile.php' );
    var $tasks = array('config', 'cache', 'path', 'db');
    
    function isApplied()
    {
        if (!isset($_SESSION[__CLASS__]) || !is_array($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        foreach ($this->tasks as $task) {
            if (!in_array($task, $_SESSION[__CLASS__])) {
                if (!$res = $this->{"check_{$task}"}()) {
                    $_SESSION[__CLASS__][] = $task;
                }
            }
        }
        return empty($_SESSION[__CLASS__]) ? true : false;
    }

    function apply()
    {
        $this->loadLanguage(basename(dirname(__FILE__)));
        $tasks = $_SESSION[__CLASS__];
        foreach ($tasks as $task) {
            $res = $this->{"apply_{$task}"}();
            if (!$res) return false;
            array_shift($_SESSION[__CLASS__]);
        }
        return true;
    }

    /**
     * Check if cpanel config already exists
     *
     */
    function check_config()
    {
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` IN ('welcome_type', 'cpanel')";
        if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            return false;
        }
        list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
        return ($count == 2) ? true : false;
    }

    /**
     * Check if cache_model table already exists
     *
     * SHOW TABLES requires specific privilege thus the tricky SELECT COUNT query is used
     */
    function check_cache()
    {
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('cache_model') . "`";
        if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            return false;
        }
        return true;
    }

    function apply_config()
    {
        $result = true;
        if (!isset($GLOBALS["xoopsConfig"]["cpanel"])) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 1, 'cpanel', '_MD_AM_CPANEL', 'default', '_MD_AM_CPANELDSC', 'cpanel', 'other', 11)";

            $result *= $GLOBALS['xoopsDB']->queryF( $sql );
        }

        $welcometype_installed = false;
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` = 'welcome_type'";
        if ( $result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
            if ($count == 1) {
                $welcometype_installed = true;
            }
        }
        if (!$welcometype_installed) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 2, 'welcome_type', '_MD_AM_WELCOMETYPE', '1', '_MD_AM_WELCOMETYPE_DESC', 'select', 'int', 3)";

            if (!$GLOBALS['xoopsDB']->queryF( $sql )) {
                return false;
            }
            $config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    " (confop_id, confop_name, confop_value, conf_id)" .
                    " VALUES" .
                    " (NULL, '_NO', '0', {$config_id})," .
                    " (NULL, '_MD_AM_WELCOMETYPE_EMAIL', '1', {$config_id})," .
                    " (NULL, '_MD_AM_WELCOMETYPE_PM', '2', {$config_id})," .
                    " (NULL, '_MD_AM_WELCOMETYPE_BOTH', '3', {$config_id})";
            if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
                return false;
            }
        }
        
        return $result;
    }

    function apply_cache()
    {
        $allowWebChanges = $GLOBALS['xoopsDB']->allowWebChanges;
        $GLOBALS['xoopsDB']->allowWebChanges = true;
        $result = $GLOBALS['xoopsDB']->queryFromFile( dirname(__FILE__) . "/mysql.structure.sql" );
        $GLOBALS['xoopsDB']->allowWebChanges = $allowWebChanges;
        return $result;
    }

    function check_path()
    {
        if (! ( defined("XOOPS_PATH") && defined("XOOPS_VAR_PATH") && defined("XOOPS_TRUST_PATH") ) ) {
            return false;
        }
        $ctrl = new PathStuffController();
        if (!$ctrl->checkPath()) {
            return false;
        }
        if (!$ctrl->checkPermissions()) {
            return false;
        }
        return true;
    }
    
    function apply_path()
    {
        return $this->update_configs('path');
    }

    function check_db()
    {
        $lines = file( XOOPS_ROOT_PATH . '/mainfile.php' );
        foreach ( $lines as $line ) {
            if( preg_match( "/(define\(\s*)([\"'])(XOOPS_DB_CHARSET)\\2,\s*([\"'])([^\"']*?)\\4\s*\);/", $line ) ) {
                return true;
            }
        }
        return false;
    }
    
    function apply_db()
    {
        return $this->update_configs('db');
    }

    function update_configs($task)
    {
        if (!$vars = $this->set_configs($task) ) {
            return false;
        }
        if ($task == "db" && !empty($vars["XOOPS_DB_COLLATION"])) {
            if ($pos = strpos($vars["XOOPS_DB_COLLATION"], "_")) {
                $vars["XOOPS_DB_CHARSET"] = substr($vars["XOOPS_DB_COLLATION"], 0, $pos);
                $this->convert_db($vars["XOOPS_DB_CHARSET"], $vars["XOOPS_DB_COLLATION"]);
            }
        }
        
        return $this->write_mainfile($vars);
    }
    
    function convert_db($charset, $collation)
    {
	    $sql = "ALTER DATABASE `" . XOOPS_DB_NAME . "` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation);
	    if ( !$GLOBALS["xoopsDB"]->queryF($sql) ) {
		    return false;
	    }
	    if ( !$result = $GLOBALS["xoopsDB"]->queryF("SHOW TABLES LIKE '" . XOOPS_DB_PREFIX . "\_%'") ) {
		    return false;
	    }
	    $tables = array();
	    while (list($table) = $GLOBALS["xoopsDB"]->fetchRow($result)) {
    	    $tables[] = $table;
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
	    }
	    $this->convert_table($tables, $charset, $collation);
    }
    
    // Some code not ready to use
    function convert_table($tables, $charset, $collation)
    {
    	// Initialize vars.
    	$string_querys = array();
    	$binary_querys = array();
    	$gen_index_querys = array();
    	$drop_index_querys = array();
    	$tables_querys = array();
    	$optimize_querys = array();
    	$final_querys = array();
    
    	// Begin Converter Core
    	if ( !empty($tables) ) {
    		foreach ( (array) $tables as $table ) {
    			// Analyze tables for string types columns and generate his binary and string correctness sql sentences.
    			$resource = $GLOBALS["xoopsDB"]->queryF("DESCRIBE $table");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/(char)|(text)|(enum)|(set)/', $result['Type']) ) {
    					// String Type SQL Sentence.
    					$string_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . " CHARACTER SET $charset COLLATE $collation " . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    
    					// Binary String Type SQL Sentence.
    					if ( preg_match('/(enum)|(set)/', $result['Type']) ) {
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' CHARACTER SET binary ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					} else {
    						$result['Type'] = preg_replace('/char/', 'binary', $result['Type']);
    						$result['Type'] = preg_replace('/text/', 'blob', $result['Type']);
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					}
    				}
    			}
    
    			// Analyze table indexs for any FULLTEXT-Type of index in the table.
    			$fulltext_indexes = array();
    			$resource = $GLOBALS["xoopsDB"]->queryF("SHOW INDEX FROM `$table`");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/FULLTEXT/', $result['Index_type']) )
    					$fulltext_indexes[$result['Key_name']][$result['Column_name']] = 1;
    			}
    
    			// Generate the SQL Sentence for drop and add every FULLTEXT index we found previously.
    			if ( !empty($fulltext_indexes) ) {
    				foreach ( (array) $fulltext_indexes as $key_name => $column ) {
    					$drop_index_querys[] = "ALTER TABLE `$table` DROP INDEX `$key_name`";
    					$tmp_gen_index_query = "ALTER TABLE `$table` ADD FULLTEXT `$key_name`(";
    					$fields_names = array_keys($column);
    					for ($i = 1; $i <= count($column); $i++)
    						$tmp_gen_index_query .= $fields_names[$i - 1] . (($i == count($column)) ? '' : ', ');
    					$gen_index_querys[] = $tmp_gen_index_query . ')';
    				}
    			}

    			// Generate the SQL Sentence for change default table character set.
    			$tables_querys[] = "ALTER TABLE `$table` DEFAULT CHARACTER SET $charset COLLATE $collation";

    			// Generate the SQL Sentence for Optimize Table.
    			$optimize_querys[] = "OPTIMIZE TABLE `$table`";
    		}

    	}
    	// End Converter Core

    	// Merge all SQL Sentences that we temporary store in arrays.
    	$final_querys = array_merge( (array) $drop_index_querys, (array) $binary_querys, (array) $tables_querys, (array) $string_querys, (array) $gen_index_querys, (array) $optimize_querys );

    	foreach ($final_querys as $sql) {
        	$GLOBALS["xoopsDB"]->queryF($sql);
    	}
    	
    	// Time to return.
    	return $final_querys;
    }

    function write_mainfile($vars)
    {
        if (empty($vars)) {
            return false;
        }

        $file = dirname(__FILE__) . '/mainfile.dist.php';

        $lines = file($file);
        foreach (array_keys($lines) as $ln) {
            if ( preg_match("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] ) 
                        ? strval( constant($matches[3]) ) 
                        : ( defined($matches[3]) 
                            ? strval( constant($matches[3]) ) 
                            : "0"
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", 
                    "define( '" . $matches[3] . "', " . $val . " )", 
                    $lines[$ln] );
            } elseif( preg_match( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])([^\"']*?)\\4\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] )
                        ? strval( $vars[$matches[3]] )
                        : ( defined($matches[3])
                            ? strval( constant($matches[3]) ) 
                            : ""
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])(.*?)\\4\s*\)/",
                    "define( '" . $matches[3] . "', '" . $val . "' )", 
                    $lines[$ln] );
            }
        }
        
        $fp = fopen( XOOPS_ROOT_PATH . '/mainfile.php', 'wt' );
        if ( !$fp ) {
            echo ERR_COULD_NOT_WRITE_MAINFILE;
            echo "<pre style='border: 1px solid black; width: 80%; overflow: auto;'><div style='color: #ff0000; font-weight: bold;'><div>" . implode("</div><div>", array_map("htmlspecialchars", $lines)) . "</div></div></pre>";
            return false;
        } else {
            $newline = defined( PHP_EOL ) ? PHP_EOL : ( strpos( php_uname(), 'Windows') ? "\r\n" : "\n" );
            $content = str_replace( array("\r\n", "\n"), $newline, implode('', $lines) );

            fwrite( $fp,  $content );
            fclose( $fp );
            return true;
        }
    }

    function set_configs($task)
    {
        $ret = array();
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if ( !$configs || !is_array($configs) ) {
            return $ret;
        }
        if (empty($_POST['task']) || $_POST['task'] != $task) {
            return false;
        }
        
        foreach ( $configs as $key => $val ) {
            $ret['XOOPS_' . $key] = $val;
        }
        return $ret;
        
    }
}

$upg = new upgrade_230();
return $upg;

?>
