<?php
/**
* Rename a file or folder
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
* Rename a file or folder
*
* If 'name' not given, show the GUI.  Otherwise, create it.
*
* @param  string $args['path'] Path of folder to rename, with relative path
* @param  string $args['name'] New name
* @param  string $args['itemtype'] Type of item to rename.  'folder' or 'file'
*/
function files_user_rename($args)
{
    // security check
    if (!xarSecurityCheck('AddFiles', 1)) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path)) return;
    if (!xarVarFetch('name', 'str:1:', $name, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('itemtype', 'str:1:', $itemtype, '', XARVAR_NOT_REQUIRED)) return;

    // set defaults
    if (empty($itemtype)) {
        $itemtype = 'folder';
    }

    // don't rename the root dir!
    if (empty($path) || $path == '/') {
        return;
    }

    // clean and validate path
    $path = xarModAPIFunc('files', 'user', 'cleanpath',
        array('path' => $path, 'type' => $itemtype, 'mode' => 'write'));

    // if no name given, show the GUI
    if (empty($name)) {

        $item = xarModAPIFunc('files', 'user', 'get', array('path' => $path));
        $archive_dir = xarModGetVar('files', 'archive_dir');

        if (is_dir("$archive_dir/$path")) {

            // generate options
            $options = array();
            if (is_writable("$archive_dir/$path")) {
                if (xarSecurityCheck('ModerateFiles', 0)) {
                    $options['moderate'] = true;
                }
                if (xarSecurityCheck('DeleteFiles', 0)) {
                    $options['delete'] = true;
                }
            }
        } else {

            $realpath = $item['realpath'];
            $text_mimes = xarModAPIFunc('files', 'user', 'getmimetext');

            // generate options menu
            $options = array();
            if (xarSecurityCheck('ViewFiles', 0) && !is_dir($realpath) && is_readable($realpath)) {
                $options['view'] = true;
            }
            if (xarSecurityCheck('EditFiles', 0) && $path != '/' && in_array($item['mime'], $text_mimes) && is_writable($realpath) && is_writeable(dirname($realpath))) {
                $options['edit'] = true;
            }
            if (xarSecurityCheck('ModerateFiles', 0) && $path != '/' && is_writable($realpath) && is_writeable(dirname($realpath))) {
                $options['moderate'] = true;
            }
            if (xarSecurityCheck('DeleteFiles', 0) && $path != '/' && is_writable($realpath) && is_writeable(dirname($realpath)) ) {
                $options['delete'] = true;
            }

        }
        $validfile = strstr($path,'.');
        if (!$validfile) $item['viewable'] = false;
        // generate template vars
        $data['path'] = $path;
        $data['authid'] = xarSecGenAuthKey();
        $data['itemtype'] = $itemtype;
        $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath',
            array('path' => $path));
        $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
        $data['item'] = $item;
        $data['options'] = $options;
        $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);

        return $data;
    }

    // security check
    if (!xarSecConfirmAuthKey()) return;

    // let the API function do the creating
    if (!xarModAPIFunc('files', 'user', 'rename',
        array('path' => $path, 'name' => $name, 'itemtype' => $itemtype))) return;

    // set status message and redirect to the renamed item
    $newpath = dirname($path)."/$name";
    $msg = xarML('Item successfully renamed!');
     xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('files', 'user', 'display', array('path' => $newpath)));

    // success
    return true;
}

?>
