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
 * Installer path configuration page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

require_once 'common.inc.php';

if ( !defined( 'XOOPS_INSTALL' ) )    exit();

$wizard->setPage( 'pathsettings' );
$pageHasForm = true;
$pageHasHelp = true;

    
class PathStuffController
{
    var $xoopsPath = array(
            'root'  => '',
            'lib'   => '',
            'data'  => '',
            );
    var $path_lookup = array(
            'root'  => 'ROOT_PATH',
            'data'  => 'VAR_PATH',
            'lib'   => 'PATH',
            );
    var $xoopsUrl = '';
    
    var $validPath = array(
            'root'  => 0,
            'data'  => 0,
            'lib'   => 0,
            );
    var $validUrl = false;
    
    var $permErrors = array(
            'root'  => null,
            'data'  => null,
            );

    function PathStuffController()
    {
        if ( isset( $_SESSION['settings']['ROOT_PATH'] ) ) {
            foreach ($this->path_lookup as $req => $sess) {
                $this->xoopsPath[$req] = $_SESSION['settings'][$sess];
            }
        } else {
            $path = str_replace( "\\", "/", realpath( '../' ) );
            if ( substr( $path, -1 ) == '/' ) {
                $path = substr( $path, 0, -1 );
            }
            if ( file_exists( "$path/mainfile.php" ) ) {
                $this->xoopsPath['root'] = $path;
            }
            // Firstly, locate XOOPS lib folder out of XOOPS root folder
            $this->xoopsPath['lib'] = dirname($path) . "/xoops_lib";
            // If the folder is not created, re-locate XOOPS lib folder inside XOOPS root folder
            if ( !is_dir($this->xoopsPath['lib'] . "/") ) {
                $this->xoopsPath['lib'] = $path . "/xoops_lib";
            }
            // Firstly, locate XOOPS data folder out of XOOPS root folder
            $this->xoopsPath['data'] = dirname($path) . "/xoops_data";
            // If the folder is not created, re-locate XOOPS data folder inside XOOPS root folder
            if ( !is_dir($this->xoopsPath['data'] . "/") ) {
                $this->xoopsPath['data'] = $path . "/xoops_data";
            }
        }
        if ( isset( $_SESSION['settings']['URL'] ) ) {
            $this->xoopsUrl = $_SESSION['settings']['URL'];
        } else {
            $path = $GLOBALS['wizard']->baseLocation();
            $this->xoopsUrl = substr( $path, 0, strrpos( $path, '/' ) );    
        }
    }

    function execute()
    {
        $this->readRequest();
        $valid = $this->validate();
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            foreach ($this->path_lookup as $req => $sess) {
                $_SESSION['settings'][$sess] = $this->xoopsPath[$req];
            }
            $_SESSION['settings']['URL'] = $this->xoopsUrl;
            if ( $valid ) {
                $GLOBALS['wizard']->redirectToPage( '+1' );
            } else {
                $GLOBALS['wizard']->redirectToPage( '+0' );
            }
        }
    }
    
    function readRequest()
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
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
            if ( isset( $request['URL'] ) ) {
                $request['URL'] = trim($request['URL']);
                if ( substr( $request['URL'], -1 ) == '/' ) {
                    $request['URL'] = substr( $request['URL'], 0, -1 );
                }
                $this->xoopsUrl = $request['URL'];
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
        $this->validUrl = !empty($this->xoopsUrl);
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
        return ( $validPaths && $this->validUrl && $validPerms );
    }
    
    function checkPath($PATH = '')
    {
        $ret = 1;
           if ( $PATH == 'root' || empty($PATH) ) {
               $path = 'root';
               if ( is_dir( $this->xoopsPath[$path] ) && is_readable( $this->xoopsPath[$path] ) ) {
                @include_once "{$this->xoopsPath[$path]}/include/version.php";
                if ( file_exists( "{$this->xoopsPath[$path]}/mainfile.php" ) && defined( 'XOOPS_VERSION' ) ) {
                    $this->validPath[$path] = 1;
                }
               }
            $ret *= $this->validPath[$path];
        }
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
    
    function checkPermissions($path)
    {
        $paths = array(
            'root'  => array( 'mainfile.php', 'uploads', /*'templates_c', 'cache'*/ ),
            'data'  => array(
                'caches'    => array(
                    'xoops_cache',
                    'smarty_cache',
                    'smarty_compile',
                    ),
                ),
            );
        $errors = array(
            'root'  => null,
            'data'  => null,
            );
        if (!isset($this->xoopsPath[$path])) {
            return false;
        }
        $this->setPermission($this->xoopsPath[$path], $paths[$path], $errors[$path]);
        if ( in_array( false, $errors[$path] ) ) {
            $this->permErrors[$path] = $errors[$path];
        }
        return true;
    }
    
    
    /**
     * Write-enable the specified folder
     * @param string $path
     * @param bool $recurse
     * @return false on failure, method (u-ser,g-roup,w-orld) on success
     */
    function makeWritable( $path, $create = true )
    {
        $mode = intval('0777', 8);
        if ( !file_exists( $path ) ) {
            if (!$create) {
                return false;
            } else {
                mkdir($path, $mode);
            }
        }
        if ( !is_writable($path) ) {
            chmod( $path, $mode );
        }
        clearstatcache();
        if ( is_writable( $path ) ) {
            $info = stat( $path );
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

function genPathCheckHtml( $path, $valid )
{
    if ( $valid ) {
        switch ($path) {
        case 'root':
            $msg = sprintf( XOOPS_FOUND, XOOPS_VERSION );
            break;
        case 'lib':
        case 'data':
        default:
            $msg = XOOPS_PATH_FOUND;
            break;
        }
        return '<img src="img/yes.png" alt="Success" />' . htmlspecialchars( $msg );
    }  else {
        switch ($path) {
        case 'root':
            $msg = ERR_NO_XOOPS_FOUND;
            break;
        case 'lib':
        case 'data':
        default:
            $msg = ERR_COULD_NOT_ACCESS;
            break;
        }
        return '<img src="img/no.png" alt="Error" /><br />' . htmlspecialchars( $msg );
    }
}


$ctrl = new PathStuffController();

if ( $_SERVER['REQUEST_METHOD'] == 'GET' && @$_GET['var'] && @$_GET['action'] == 'checkpath' ) {
    $path = $_GET['var'];
    $ctrl->xoopsPath[$path] = htmlspecialchars( trim($_GET['path']) );
    echo genPathCheckHtml( $path, $ctrl->checkPath($path) );
    exit();
}
$ctrl->execute();
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    return;
}

    ob_start();
?>
<script type="text/javascript">
function removeTrailing(id, val) {
    if (val[val.length-1] == '/') {
        val = val.substr(0, val.length-1);
        $(id).value = val;
    }
    return val;
}

function updPath( key, val ) {
    val = removeTrailing(key, val);
    new Ajax.Updater(
        key+'pathimg', '<?php echo $_SERVER['PHP_SELF']; ?>',
        { method:'get',parameters:'action=checkpath&var='+key+'&path='+val }
    );
    $(key+'perms').style.display='none';
}
</script>
<fieldset>
    <legend><?php echo XOOPS_PATHS; ?></legend>
    
    <label for="root"><?php echo XOOPS_ROOT_PATH_LABEL; ?></label>
    <div class="xoform-help"><?php echo htmlspecialchars(XOOPS_ROOT_PATH_HELP); ?></div>
    <input type="text" name="root" id="root" value="<?php echo $ctrl->xoopsPath['root']; ?>" onchange="updPath('root', this.value)" />
    <span id="rootpathimg"><?php echo genPathCheckHtml( 'root', $ctrl->validPath['root'] ); ?></span>
    <?php if ( $ctrl->validPath['root'] && !empty( $ctrl->permErrors['root'] ) ) { ?>
    <div id="rootperms" class="x2-note">
    <?php echo CHECKING_PERMISSIONS . '<br /><p>' . ERR_NEED_WRITE_ACCESS . '</p>'; ?>
    <ul class="diags">
    <?php foreach ( $ctrl->permErrors['root'] as $path => $result ) {
        if ( $result ) {
            echo '<li class="success">' . sprintf( IS_WRITABLE, $path ) . '</li>';
        } else {
            echo '<li class="failure">' . sprintf( IS_NOT_WRITABLE, $path ) . '</li>';
        }
    } ?>
    </ul>
    <?php } else { ?>
    <div id="rootperms" class="x2-note" style="display: none;" />
    <?php } ?>
    </div>
    
    <label for="data"><?php echo XOOPS_DATA_PATH_LABEL; ?></label>
    <div class="xoform-help"><?php echo htmlspecialchars(XOOPS_DATA_PATH_HELP); ?></div>
    <input type="text" name="data" id="data" value="<?php echo $ctrl->xoopsPath['data']; ?>" onchange="updPath('data', this.value)" />
    <span id="datapathimg"><?php echo genPathCheckHtml('data', $ctrl->validPath['data'] ); ?></span>
    <?php if ( $ctrl->validPath['data'] && !empty( $ctrl->permErrors['data'] ) ) { ?>
    <div id="dataperms" class="x2-note">
    <?php echo CHECKING_PERMISSIONS . '<br /><p>' . ERR_NEED_WRITE_ACCESS . '</p>'; ?>
    <ul class="diags">
    <?php foreach ( $ctrl->permErrors['data'] as $path => $result ) {
        if ( $result ) {
            echo '<li class="success">' . sprintf( IS_WRITABLE, $path ) . '</li>';
        } else {
            echo '<li class="failure">' . sprintf( IS_NOT_WRITABLE, $path ) . '</li>';
        }
    } ?>
    </ul>
    <?php } else { ?>
    <div id="dataperms" class="x2-note" style="display: none;" />
    <?php } ?>
    </div>
    
    <label for="lib"><?php echo XOOPS_LIB_PATH_LABEL; ?></label>
    <div class="xoform-help"><?php echo htmlspecialchars(XOOPS_LIB_PATH_HELP); ?></div>
    <input type="text" name="lib" id="lib" value="<?php echo $ctrl->xoopsPath['lib']; ?>" onchange="updPath('lib', this.value)" />
    <span id="libpathimg"><?php echo genPathCheckHtml( 'lib', $ctrl->validPath['lib'] ); ?></span>
    <div id="libperms" class="x2-note" style="display: none;" />
    
</fieldset>

<fieldset>
    <legend><?php echo XOOPS_URLS; ?></legend>
    <label for="url"><?php echo XOOPS_URL_LABEL; ?></label>
    <div class="xoform-help"><?php echo htmlspecialchars(XOOPS_URL_HELP); ?></div>
    <input type="text" name="URL" id="url" value="<?php echo $ctrl->xoopsUrl; ?>" onchange="removeTrailing('url', this.value)" />
</fieldset>

<?php
    $content = ob_get_contents();
    ob_end_clean();

    include 'install_tpl.php';
?>