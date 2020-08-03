<?php
/**
* Delete an item
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
* Delete an item
*
* @param integer $args['confirm'] Confirm deletion
* @param string $args['path'] Item to delete, with relative path
*/
function files_user_delete($args)
{
    // security check
    if (!xarSecurityCheck('DeleteFiles', 1)) return;

    extract($args);

    // get HTTP input vars
    if (!xarVarFetch('path', 'str:0:', $path)) return;
    if (!xarVarFetch('confirm', 'str:1:', $confirm, '', XARVAR_NOT_REQUIRED)) return;

    // clean up the path and prepare to validate it
    $path = xarModAPIFunc('files', 'user', 'cleanpath', array('path' => $path));


    // retrieve info on this file
    $item = xarModAPIFunc('files', 'user', 'get', array('path' => $path));

    // show GUI if we haven't confirmed
    if (empty($confirm)) {

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

        // add template vars
        $data['authid'] = xarSecGenAuthKey();
        $data['path'] = $path;
        $data['item'] = $item;
        $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath',
            array('path' => $path));
        $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
        $data['options'] = $options;
        $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);

        return $data;
    }

    // security check
    if (!xarSecConfirmAuthKey()) return;

    // call API function to delete this file
    if (!xarModAPIFunc('files', 'user', 'delete', array('path' => $path))) {
        return;
    }

    // set status and return to the folder we were in

       $msg = xarML('Item successfully deleted!');
     xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('files', 'user', 'main', array('path' => dirname($path))));

    // success
    return true;
}

?>
