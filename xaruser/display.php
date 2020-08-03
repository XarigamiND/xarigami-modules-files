<?php
/**
* Display details for an item
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
* Display details for an item
*
* @param  string $args['path'] Item to display, with relative path
*/
function files_user_display($args)
{
    // security check
    if (!xarSecurityCheck('ReadFiles', 1)) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path, '/', XARVAR_NOT_REQUIRED)) return;

    // clean up the path and validate it
    $path = xarModAPIFunc('files', 'user', 'cleanpath', array('path' => $path));

    // get info on this file
    $item = xarModAPIFunc('files', 'user', 'get', array('path' => $path));

    // prepare for setting options
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $realpath = realpath($archive_dir)."/$path";
    $text_mimes = xarModAPIFunc('files', 'user', 'getmimetext');

    // generate options menu
    $options = array();
    if (xarSecurityCheck('ViewFiles', 0) && !is_dir($realpath) && is_readable($realpath)) {
        $options['view'] = true;
    }
    if (xarSecurityCheck('EditFiles', 0) && $path != '/' && in_array($item['mime'], $text_mimes) && is_writable($realpath) ) {
        $options['edit'] = true;
    }
    if (xarSecurityCheck('ModerateFiles', 0) && $path != '/' && is_writable($realpath)) {
        $options['moderate'] = true;
    }
    if (xarSecurityCheck('DeleteFiles', 0) && $path != '/' && is_writable($realpath) ) {
        $options['delete'] = true;
    }
    // set page title
    xarTplSetPageTitle(xarVarPrepForDisplay($path));

    $validfile = strstr($path,'.');
    if (!$validfile) $item['viewable'] = false ;

    // generate template data array
    $data['path'] = $path;
    $data['item'] = $item;
    $data['options'] = $options;
    $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
    $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);
    return $data;
}

?>
