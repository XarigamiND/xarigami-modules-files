<?php
/**
* Get details on a file or folder
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
* Get details on a file or folder
*
* @author Curtis Farnham <curtis@farnham.com>
* @access  public
* @param   string $args['path'] Path of item to get, with relative path
* @return  array
* @returns array of item details
* @throws  BAD_PARAM, NO_PERMISSION
*/
function files_userapi_get($args)
{
    // security check
    if (!xarSecurityCheck('ViewFiles')) return;

    extract($args);

    // set defaults
    if (!isset($path)) $path = '';

    // clean and validate the path (must be folder)
    $path = xarModAPIFunc('files', 'user', 'cleanpath', array('path' => $path));

    // get some paths
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $archive_realpath = realpath($archive_dir);
    $realpath = realpath("$archive_dir/$path");
    $viewpath = $path;

    // get mime type and image
    if (is_dir($realpath)) {
        // note: mime module doesn't do folders, so we have to do it ourselves
        $mime = 'fs/directory';
        $image_big = xarTplGetImage('fs-directory.png', 'mime');
        $image_small = xarTplGetImage('fs-directory-16x16.png', 'mime');
        if (empty($image_big)) $image_big = xarTplGetImage('fs-folder-48x48.png');
        if (empty($image_small)) $image_small = xarTplGetImage('fs-folder.png');
        if (empty($image_small)) $image_small = $image_big;
    } else {
        $mime = xarModAPIFunc('mime', 'user', 'analyze_file',
            array('fileName' => $realpath));
        $image_big = xarModAPIFunc('mime', 'user', 'get_mime_image',
            array('mimeType' => $mime));
        $image_small = xarModAPIFunc('mime', 'user', 'get_mime_image',
            array('mimeType' => $mime,
                'fileSuffix' => '-16x16.png|-16x16.gif|.png|.gif'));
    }

    // make url-compatible path
    $urlpath = xarModAPIFunc('files', 'user', 'urlpath', array('path' => $path));

    // make file size human-readable
    $hrsize = filesize($realpath);
    $cnt = 0;
    $unit = '';
    $units = array(xarML(''), xarML('K'), xarML('M'), xarML('G'));
    while ($hrsize > 1000) {
        $hrsize /= 1024;
        $cnt++;
        $unit = $units[$cnt];
    }
    $hrsize = round($hrsize, 1).$unit;

    // get created and modified dates
    $created = filectime($realpath);
    $modified = filemtime($realpath);
    $folder = dirname($viewpath);
    $viewable = substr($mime,0,5) == 'image' || substr($mime,0,4) == 'text';
    // assemble info into one big array
    $filedata = array(
        'file'          => basename($path),
        'realpath'      => $realpath,
        'viewpath'      => $viewpath,
        'urlpath'       => $urlpath,
        'folder'        => $folder,
        'image_big'     => $image_big,
        'image_small'   => $image_small,
        'size'          => filesize($realpath),
        'hrsize'        => $hrsize,
        'is_dir'        => is_dir($realpath),
        'is_readable'   => is_readable($realpath),
        'is_writeable'  => is_writable($realpath),
        'is_executable' => is_executable($realpath),
        'created'       => $created,
        'modified'      => $modified,
        'mime'          => $mime,
        'viewable'      => $viewable
    );

    return $filedata;
}

?>
