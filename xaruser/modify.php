<?php
/**
* Display GUI to edit a text file
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
* Display GUI to edit a text file
*
* @param  string $args['path'] Path of file to edit, with relative path
*/
function files_user_modify($args)
{
    // security check
    if (!xarSecurityCheck('EditFiles')) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:1', $path, $path)) return;

    // set defaults
    if (!isset($path)) $path = '';

    // clean and validate path
    $path = xarModAPIFunc('files', 'user', 'cleanpath',
        array('path' => $path, 'type' => 'file', 'mode' => 'write'));

    // get item details
    $item = xarModAPIFunc('files', 'user', 'get', array('path' => $path));

    // make sure file is plaintext
    $text_mimes = xarModAPIFunc('files', 'user', 'getmimetext');
    if (!in_array($item['mime'], $text_mimes)) {
        $msg = xarML('#(1) is not plain text.  Unable to edit.');
        throw new BadParameterException(null,$msg);
    }

    // get contents of file for editing
    $contents = file_get_contents($item['realpath']);

    // count newlines for runtime configurability of textarea
    preg_match_all("/(\r\n|\n\r|\n|\r)/", $contents, $matches);
    $newlines = 0;
    if (!empty($matches[0])) $newlines = count($matches[0]);

    $archive_dir = xarModGetVar('files', 'archive_dir');
    $realpath = $item['realpath'];

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

    // generate template vars
    $data['path'] = $path;
    $data['item'] = $item;
    $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
    $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath', array('path' => $path));
    $data['authid'] = xarSecGenAuthKey();
    $data['contents'] = $contents;
    $data['newlines'] = $newlines;
    $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
    $data['options'] = $options;
    $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);

    return $data;
}
?>
