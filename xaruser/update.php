<?php
/**
* Update a text file
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
* Update a text file
*
* @param  string $args['path'] File we are saving, with relative path
* @param  string $args['contents'] Contents of file to save
*/
function files_user_update($args)
{
    // security check
    if (!xarSecurityCheck('EditFiles', 1)) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path)) return;
    if (!xarVarFetch('contents', 'str:0:', $contents, '', XARVAR_NOT_REQUIRED)) return;

    // we need a path!
    if (empty($path)) return;

    // clean and validate path
    $path = xarModAPIFunc('files', 'user', 'cleanpath',
        array('path' => $path, 'type' => 'file', 'mode' => 'write'));

    // security check
    if (!xarSecConfirmAuthKey()) return;

    // let the API function do the saving
    if (!xarModAPIFunc('files', 'user', 'update',
        array('path' => $path, 'contents' => $contents))) return;

    // set status message and return to the file just edited
    $msg = xarML('File successfully saved!');
     xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('files', 'user', 'display', array('path' => $path)));
    return true;
}

?>
