/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Modules Javascript
 *  
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Andricq Nicolas (AKA MusS) 
 * @version     $Id$
 */
 
$(document).ready(
    function(){
        // Controls Drag + Drop
        $('#xo-module-sort tbody.xo-module').sortable({
                placeholder: 'ui-state-highlight',
                update: function(event, ui) {
                    var list = $(this).sortable( 'serialize');
                    $.post( 'admin.php?fct=modulesadmin&op=order', list );
                }
            }
        );
    }
);