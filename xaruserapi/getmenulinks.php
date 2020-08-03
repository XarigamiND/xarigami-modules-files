<?php
/**
 * Utility function pass individual menu items to the main menu
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Files Module
 * @copyright (C) 2009-2011 2skies.com
 * @link http://xarigami.com/project/xarigami_files
 */
/**
 * Utility function pass individual menu items to the main menu
 *
 * @author the Example module development team
 * @return array containing the menulinks for the main menu items.
 */
function files_userapi_getmenulinks($args)
{
    extract($args);
    $itemtype = isset($itemtype)?$itemtype:'';
    $urlpath = isset($urlpath)? $urlpath:'';
    if (xarSecurityCheck('ReadFiles', 0)) {
        $menulinks[] = array('url' => xarModURL('files','user','main'),
            'title' => xarML('Browse files'),
            'label' => xarML('Browse'),
            'active' => array('main','display','view','download','rename','delete'),
            'activelabels' =>array('',xarML('Details'),xarML('Preview'),xarML('Download'),xarML('Rename'),xarML('Delete'))
            );
    }
    if (xarSecurityCheck('AddFiles', 0)) {

        $menulinks[] = array('url' => xarModURL('files','user','new',array('path' => $urlpath, 'itemtype' => 'folder')),
            'title' => xarML('New Folder'),
            'label' => xarML('New folder'),
            'active' =>array('new','folder')

            );
         $menulinks[] = array('url' => xarModURL('files','user','new',array('path' => $urlpath, 'itemtype' => 'file')),
            'title' => xarML('New File'),
            'label' => xarML('New File'),
            'active' =>array('new')
            );
        $menulinks[] = array('url' => xarModURL('files','user','upload',array('path' => $urlpath)),
            'title' => xarML('Upload'),
            'label' => xarML('Upload'),
            'active' =>array('upload')
            );
    }

    if (empty($menulinks)) {
        $menulinks = '';
    }

    return $menulinks;
}
?>