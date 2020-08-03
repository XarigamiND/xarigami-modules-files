<?php
/**
* Upload file(s)
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
* Upload file(s)
*
* @param  string $args['path'] relative path where to upload files to
* @param  string $args['entered'] confirm whether user has submitted any files
*/
function files_user_upload($args)
{
    // security check
    if (!xarSecurityCheck('AddFiles', 1)) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:0:', $path, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('entered', 'str:1:', $entered, '', XARVAR_NOT_REQUIRED)) return;

    // set defaults
    if (empty($path)) $path = '';

    // clean and validate path
    $path = xarModAPIFunc('files', 'user', 'cleanpath',
        array('path' => $path, 'type' => 'folder', 'mode' => 'write'));

    // if no files have been submitted, display GUI
    if (empty($entered)) {

        // get some vars
        $archive_dir = xarModGetVar('files', 'archive_dir');

        $max = ini_get('upload_max_filesize');
        $maxnum = preg_replace("/[a-zA-Z].*/", '', $max);
        $maxunit = preg_replace("/^ *\d+/", '', $max);
        switch($maxunit) {
            case 'G': case 'GB': case 'g': case 'gb':
                $max_size = 1024*1024*1024*$maxnum;
                break;
            case 'M': case 'MB': case 'm': case 'mb':
                $max_size = 1024*1024*$maxnum;
                break;
            case 'K': case 'KB': case 'k': case 'kb':
                $max_size = 1024*$maxnum;
                break;
            case 'B': case 'b':
            default:
                $max_size = $maxnum;
            break;
        }

        // generate options
        $options = array();
        if (is_writable("$archive_dir/$path")) {
            if (xarSecurityCheck('AddFiles', 0)) {
                $options['add'] = true;
            }
           if (xarSecurityCheck('ModerateFiles', 0)) {
                $options['moderate'] = true;
            }
            if (xarSecurityCheck('DeleteFiles', 0)) {
                $options['delete'] = true;
            }
        }
        $max_upload          = xarModGetVar('files', 'max_upload');
        $allow_multiple      = xarModGetVar('files', 'allow_multiple');
        $filetypes          = xarModGetVar('files', 'filetypes');
        $data['max_upload']        = $allow_multiple?$max_upload:1;
        $data['filetypes']         = $filetypes;
        $data['uploadmessage'] = '';
        if ($max_upload > 0) {
            $data['uploadmessage'] = xarML('Multiple upload limit is #(1).',$max_upload);
            }
        // generate template vars
        $data['path'] = $path;
        $data['authid'] = xarSecGenAuthKey();
        $data['max_size'] = $max_size;
        $data['hrsize'] = $max;
        $data['urlpath'] = xarModAPIFunc('files', 'user', 'urlpath', array('path' => $path));
        $data['pathparts'] = xarModAPIFunc('files', 'user', 'getfilepager', array('path' => $path));
        $data['options'] = $options;
        $data['menulinks'] = xarModAPIFunc('files','user','getmenulinks',  $data);
        return $data;
    }

    // security check
    if (!xarSecConfirmAuthKey()) return;

    // get some paths
    $archive_dir = xarModGetVar('files', 'archive_dir');
    $realpath = realpath("$archive_dir/$path");

    // make file arrays available
    extract($_FILES['userfile']);

    # process files one by one
    $messages = array();
    foreach ($error as $index => $errcode) {

        // get file name
        $filename = $name[$index];

        // set count
        $cnt = $index + 1;

        // act according to error codes
        switch($errcode) {
            case UPLOAD_ERR_OK:
                if (move_uploaded_file($tmp_name[$index], "$realpath/$filename")) {
                    $messages[] = array(xarML('File ##(1) (#(2)) successfully uploaded.', $cnt, $filename),'status');
                } else {
                    $messages[] = array(xarML('File ##(1) (#(2)) could not be moved to archive.', $cnt, $filename),'error');
                }
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $messages[] = array(xarML('File ##(1) (#(2)) is too big to upload.', $cnt, $filename),'error');
                break;
            case UPLOAD_ERR_PARTIAL:
                $messages[] = array(xarML('File ##(1) (#(2)) was not completely uploaded.', $cnt, $filename),'error');
                break;
            case UPLOAD_ERR_NO_FILE:
                // Do nothing.  This isn't an error in our implementation.
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $messages[] = array(xarML('Temporary folder is missing.'),'error');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $messages[] = array(xarML('Error: Failed to write to disk.'),'error');
                break;
            default:
                $messages[] = array(xarML('File ##(1) (#(2)) failed uploading for an unknown reason.', $cnt, $filename),'error');
                break;
        }
    }
    foreach ($messages as $msg) {
        xarTplSetMessage($msg[0],$msg[1]);
    }
    xarResponseRedirect(xarModURL('files', 'user', 'main', array('path' => $path)));

    // success
    return true;
}

?>
