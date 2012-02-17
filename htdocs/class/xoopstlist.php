<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
defined( 'XOOPS_ROOT_PATH' ) or die( 'You do not have permission to access this file!' );

/**
 * XOOPS methods for user handling
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package class
 * @since 2.3.0
 * @author John Neill <catzwolf@users.sourceforge.net>
 * @version $Id: xoopsprint.php 1531 2008-05-01 09:25:50Z catzwolf $
 */
class xoopsTlist {
    var $_headers;
    var $_data = array();
    var $_pre_fix = '_XO_AD_';
    var $_output = false;
    var $_footer = false;
    var $_hidden;
    var $_path;
    var $_formName;
    var $_formAction;
    var $_submitArray;

    /**
     * Tlist::Tlist()
     *
     * @return
     */
    function xoopsTlist( $headers = array() )
    {
        $this->_headers = $headers;
    }
    /**
     * xoopsAbout::getInstance()
     *
     * @return
     */
    function &getInstance()
    {
        static $instance;
        if ( !isset( $instance ) ) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    function AddHeader( $name, $size = 0, $align = 'left', $islink = false )
    {
        $this->_headers[] = array( 'name' => $name, 'width' => $size, 'align' => $align, 'islink' => $islink );
        $this->_headers_count = count( $this->_headers );
    }

    function setPrefix( $value )
    {
        $this->_pre_fix = ( isset( $value ) ) ? strval( $value ) : '_XO_AD_';
    }

    function setOutput( $value = true )
    {
        $this->_output = ( $value == true ) ? true : false;
    }

    function setPath( $value )
    {
        $this->_path = strval( $value );
    }

    function setOp( $value )
    {
        $this->_op = strval( $value );
    }

    function add( $data, $class = null, $isarray = false )
    {
        if ( $isarray ) {
            foreach ( $data as $value ) {
                $this->_data[] = array( $value, $class );
            }
        } else {
            $this->_data[] = array( $data, $class );
        }
    }

    function import( $array )
    {
        foreach ( $array as $a_rrays ) {
            $this->add( $a_rrays, $class = null, $isarray = false );
        }
    }

    function addHidden( $value, $name = "" )
    {
        if ( $name != "" ) {
            $this->_hidden[$value] = $name;
        } else {
            $this->_hidden[$value] = $value;
        }
    }

    function addHiddenArray( $options, $multi = true )
    {
        if ( is_array( $options ) ) {
            if ( $multi == true ) {
                foreach ( $options as $k => $v ) {
                    $this->addHidden( $k, $v );
                }
            } else {
                foreach ( $options as $k ) {
                    $this->addHidden( $k, $k );
                }
            }
        }
    }

    function noselection()
    {
        $ret = "<tr>\n<td colspan='" . $this->_headers_count . "' class='emptylist'>" . _XO_AD_NOITEMSFOUND . "</td>\n</tr>\n";
        if ( $this->_output ) {
            echo $ret;
        } else {
            return $ret;
        }
    }

    function addFooter( $value = '', $submitArray = null )
    {
        $this->_footer = $value;
    }

    function footer_listing( $align = 'right' )
    {
        $ret = "<tr style='text-align: $align;'>\n<td colspan='" . $this->_headers_count . "' class='foot'>";
        if ( count( $this->_data ) && $this->_footer ) {
            $ret .= self::addSubmit();
        }
        $ret .= "</td>\n</tr>\n</table>\n";
        if ( $this->_output ) {
            echo $ret;
        } else {
            return $ret;
        }
    }

    function addFormStart( $method = 'post', $action = '', $name = '' )
    {
        $this->_formName = strval( $name );
        if ( $this->_formName ) {
            $this->_formAction .= "<form style='margin: 0px;' method='" . $method . "' action='" . $action . "' id='" . $this->_formName . "' name='" . $this->_formName . "' >";
        }
    }

    function addFormEnd()
    {
        if ( $this->_formName ) {
            return '</form>';
        }
    }

    function addSubmit( $value = '', $name = '', $_array = array() )
    {
        if ( empty( $_array ) ) {
            $_array = array( 'updateall' => 'Update Selected', 'deleteall' => 'Delete Selected', 'cloneall' => 'Clone Selected' );
        }
        $ret = '<select size="1" name="op" id="op">';
        foreach( $_array as $k => $v ) {
            $ret .= '<option value="' . $k . '">' . htmlspecialchars( $v ) . '</option>';
        }
        $ret .= '</select>&nbsp;<input type="hidden" name="' . htmlspecialchars( $name, ENT_QUOTES ) . '" value="' . $value . '" /><input type="submit" class="formbutton" value="' . _SUBMIT . '" name="Submit" />';
        return $ret;
    }

    /**
     * xoops_TList::render()
     *
     * @return
     */
    function render( $display = true )
    {
        $ret = $this->_formAction;
        $count = count( $this->_headers );
        $ret .= "
		<style type=\"text/css\">
			.emptylist { background-color: #D0D9F9; padding: 5px; vertical-align: middle; text-align: center; font-weight: bold;}
			.foot { height: 20px; }
		</style>
		<table width='100%' cellpadding='0' cellspacing='1' class='outer' summary=''>\n";
        $ret .= "<tr style='text-align: center;'>\n";
        foreach ( $this->_headers as $value ) {
            $width = ( isset( $value['width'] ) ) ? "style='width: " . $value['width'] . ";'" : '';
            $ret .= "<th $width align={$value['align']}>\n";
            if ( intval( $value['islink'] ) == 2 ) {
                $ret .= xoops_getConstants( $this->_pre_fix . $value['name'] );
                $ret .= "<input name='" . $value['name'] . "_checkall' id='" . $value['name'] . "_checkall' onclick='xoopsCheckAll(\"" . $this->_formName . "\", \"" . $value['name'] . "_checkall\");' type='checkbox' value='Check All' />";
            } elseif ( $value['islink'] == true ) {
                $ret .= "<a href='index.php?";
                if ( $this->_path ) {
                    $ret .= $this->_path . "&amp;";
                }
                $ret .= "sort=" . $value['name'] . "&amp;order=ASC'>" . xoops_showImage( 'down' ) . "</a>";
                $ret .= xoops_getConstants( $this->_pre_fix . $value['name'] );
                $ret .= "<a href='index.php?";
                if ( $this->_path ) {
                    $ret .= $this->_path . "&amp;";
                }
                $ret .= "sort=" . $value['name'] . "&amp;order=DESC'>" . xoops_showImage( 'up' ) . "</a>";
            } else {
                $ret .= xoops_getConstants( $this->_pre_fix . $value['name'] );
            }
            unset( $constant );
            $ret .= "</th>\n";
        }
        $ret .= "</tr>\n";
        if ( isset( $this->_data[0] ) && count( $this->_data ) ) {
            foreach ( $this->_data as $data ) {
                if ( !empty( $data[1] ) ) {
                    $class = $data[1];
                } else {
                    $class = ( isset( $class ) && $class == 'even' ) ? 'odd' : 'even';
                }
                $ret .= "<tr class='" . $class . "'>\n";
                $i = 0;
                if ( $data[1] != true ) {
                    foreach ( $data[0] as $value ) {
                        $ret .= "<td align='" . $this->_headers[$i]['align'] . "'>" . $value . "</td>\n";
                        $i++;
                    }
                    $ret .= "</tr>\n";
                }
            }
        } else {
            $ret .= $this->noselection();
        }
        if ( count( $this->_hidden ) ) {
            foreach( $this->_hidden as $k => $v ) {
                $ret .= "<input type='hidden' name='" . $v . "[" . $k . "]' id='" . $v . "[]' value='" . $k . "' />\n";
            }
        }
        $ret .= $this->footer_listing();
        $ret .= $this->addFormEnd();
        if ( $display == true ) {
            echo $ret;
        } else {
            return $ret;
        }
    }
}

?>