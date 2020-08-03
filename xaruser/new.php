<?php
/**
* Create a new file or folder
*
* @package unassigned
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}
*
* @subpackage Xarigami Files Module
* @copyright (C) 2009-2011 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
/**
* Create a new file or folder
*
* If 'name' not given, show the GUI.  Otherwise, create it.
*
* @param  string $args['path'] Path of folder to create in, with relative path
* @param  string $args['name'] Name of new item
* @param  string $args['itemtype'] 'folder' is default. can also be 'file'.
*/
function files_user_new($args)
{
    // security check
    if (!xarSecurityCheck('AddFiles', 1)) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('name', 'str:1:', $name, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('itemtype', 'str:1:', $itemtype, '', XARVAR_NOT_REQUIRED)) return;

    // set defaults
    if (empty($itemtype)) $itemtype = 'folder';
    if (empty($path)) $path = '/';

    // clean and validate the path
    $path = xarModAPIFunc('files', 'user', 'cleanpath',
        array('path' => $path, 'mode' => 'write'));

    // if no name given, show the GUI
    if (empty($name)) {

        $archive_dir = xarModGetVar('files', 'archive_dir');

        // generate options
        $options = array();
        if (is_writable("$archive_dir/$path")) {
            if (xarSecurityCheck('AddFiles', 0)) {
                $options['add'] = true;
            }
            if (xarSecurityCheck('DeleteFiles', 0)) {
                $options['delete'] = true;
            }
        }
        // generate template vars
        $data['path'] = $path;
        $data['authid'] = xarSecGenAuthKey();
        $data['itemtype'] = $itemtype;
        $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath',
            array('path' => $path));
        $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
        $data['options'] = $options;
        $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);
        return $data;
    }

    // security check
    if (!xarSecConfirmAuthKey()) return;

    // let the API function do the creating
    if (!xarModAPIFunc('files', 'user', 'create',
        array('path' => $path, 'name' => $name, 'itemtype' => $itemtype))) return;

    // set status message and redirect to folder we just created in
    $msg = xarML('New item created successfully!');
    xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('files', 'user', 'main', array('path' => $path)));

    // success
    return true;
}

?>
