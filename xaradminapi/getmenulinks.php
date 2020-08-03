<?php
/**
* Get menu items for adminpanels
*
* @package unassigned
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}
*
* @subpackage Xarigami Files Module
* @copyright (C) 2009-2010 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
/**
* Get menu items for adminpanels
*
* @author  Curtis Farnham <curtis@farnham.com>
* @access  public
* @return array list of menu links
*/
function files_adminapi_getmenulinks()
{
    // initialize list
    $menulinks = array();

    // modifyconfig link
    if (xarSecurityCheck('AdminFiles', 0)) {
        $menulinks[] = array('url'   => xarModURL('files', 'admin', 'modifyconfig'),
            'title' => xarML('Modify the configuration for the module'),
            'label' => xarML('Modify Config'),
            'active' => array('modifyconfig')
            );
    }

    return $menulinks;
}
?>
