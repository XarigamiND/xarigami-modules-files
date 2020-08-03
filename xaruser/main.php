<?php
/**
* Main user function
*
* @package modules
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}
*
* @subpackage Xarigami Files Module
* @copyright (C) 2009-2011 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
/**
* Main user function
*
* Show directory listing and options
*
* @param  string $args['path'] Folder to be listed
*/
function files_user_main($args)
{
    // security check
    if (!xarSecurityCheck('ViewFiles')) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path, '', XARVAR_NOT_REQUIRED)) return;

    // set defaults
    if (!isset($path)) $path = '';

    // clean and validate path
    $path = xarModAPIFunc('files', 'user', 'cleanpath', array('path' => $path));

    // get other vars
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $data['msg'] = '';
    if (empty($archive_dir)) {
        $data['msg'] = xarML('There is no file archive directory configured on this website.');
        if (xarSecurityCheck('AdminFiles',0)) {
        $data['msg'] .= "\n\n";
        $data['msg'] .= xarML('Please add a valid directory in the Files configuration screen. If you wish to allow uploads and file creation make sure it is WRITEABLE.');
        $data['msg'] = nl2br($data['msg']);
        }
        return $data;
    }

    // if path exists but is a regular file, redirect to display function
    if (!is_dir("$archive_dir/$path")) {
        xarResponseRedirect(xarModURL('files', 'user', 'display', array('path' => $path)));
        return true;
    }

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

    // set page title
    xarTplSetPageTitle(xarVarPrepForDisplay($path));

    $dirs = xarModAPIFunc('files', 'user', 'getall', array('path' => $path,'ftype'=>'dirs'));

    $files = xarModAPIFunc('files', 'user', 'getall', array('path' => $path,'ftype'=>'files'));
    $temp = array();
    $temp2 = array();
    foreach($dirs as $dirid=>$fileinfo) {
        if ($fileinfo['file'] == '.') {
            $temp[1] = $fileinfo;
            unset($dirs[$dirid]);
        }elseif ($fileinfo['file'] == '..' ){
            $temp[0] = $fileinfo;
            unset($dirs[$dirid]);
        }
    }
    //ensure we have parent first
    ksort($temp);

    $list = array_merge($temp,$files);
    $list = array_merge($list,$dirs);
    foreach ($list as $k=>$info)
    {

        $list[$k]['detaillink'] =xarModURL('files', 'user', 'display', array('path' => $info['urlpath']));
        if (substr( $info['mime'],0,5) == 'image') {
             $list[$k]['detailview'] =xarModURL('files', 'user', 'view', array('path' => $info['urlpath']));
        } else {
              $list[$k]['detailview'] = '';
        }
    }

    $data['path'] = $path;
    $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath', array('path' => $path));
    $data['files']= $list;
    $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
    $data['options'] = $options;
    $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);

    return $data;
}
?>