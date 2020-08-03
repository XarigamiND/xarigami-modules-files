<?php
/**
* Update a file or folder
*
* @package unassigned
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}

* @subpackage Xarigami Files Module
* @copyright (C) 2009-2011 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
/**
* Update a file or folder
*
* Save the modified contents of a file
*
* @author Curtis Farnham <curtis@farnham.com>
* @access  public
* @param   string $args['path'] relative path of item to update
* @return  boolean
* @returns true if successful
* @throws  BAD_PARAM, NO_PERMISSION
*/
function files_userapi_update($args)
{
    // security check
    if (!xarSecurityCheck('EditFiles', 1)) return;

    extract($args);

    // validate inputs
    if (!isset($path) || empty($path) || !is_string($path) || $path == '/') {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
            'item ID', 'userapi', 'delete', 'files');
          throw new BadParameterException(null,$msg);
    }

    // set defaults
    if (!isset($contents)) {
        $contents = '';
    }

    // get other paths
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $realpath = realpath("$archive_dir/$path");

    // overwrite previous contents of file
    $fd = fopen($realpath, 'w');
    fputs($fd, $contents);
    fclose($fd);

    // success
    return true;
}

?>
