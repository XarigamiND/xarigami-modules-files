<?php
/**
* Rename a file or folder
*
* @package unassigned
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}

* @subpackage Xarigami Files Module
* @copyright (C) 2009-2011 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
/**
* Rename a file or folder
*
* If 'name' not given, show the GUI.  Otherwise, create it.
*
* @param  $args ['path'] path of the file relative to archive_dir
* @param  $args ['name'] name to change the file to
* @returns bool
* @return true on success, false on failure
* @raise BAD_PARAM, NO_PERMISSION
*/
function files_userapi_rename($args)
{
    // security check
    if (!xarSecurityCheck('DeleteFiles', 1)) return;

    extract($args);

    // validate inputs
    $invalid = array();
    if (!isset($name) || empty($name) || !is_string($name) || strstr($name, '/')) {
        $invalid[] = 'name';
    }
    if (!isset($path) || empty($path) || !is_string($path) || $path == '/') {
        $invalid[] = 'path';
    }

    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            join(', ', $invalid), 'userapi', 'rename', 'files');
          throw new BadParameterException(null,$msg);
    }

    // get some paths
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $realpath = realpath("$archive_dir/$path");
    $oldpath = $realpath;
    $basepath = dirname($realpath);
    $newpath = "$basepath/$name";

    // perform renaming function
    if (!rename($oldpath, $newpath)) {
        return;
    }

    // success
    return true;
}

?>