<?php
/**
* Display GUI for config modification
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
* Display GUI for config modification
*/
function files_admin_modifyconfig()
{
    // security check
    if (!xarSecurityCheck('AdminFiles')) return;
    if (!xarVarFetch('dirmsg', 'str:0:', $dirmsg, '', XARVAR_NOT_REQUIRED)) return;
    // get module vars
    $supportshorturls    = xarModGetVar('files', 'SupportShortURLs');
    $usemodulealias      = xarModGetVar('files', 'useModuleAlias');
    $aliasname           = xarModGetVar('files', 'aliasname');
    $archive_dir         = xarModGetVar('files', 'archive_dir');
    $max_upload          = xarModGetVar('files', 'max_upload');
    $allow_multiple      = xarModGetVar('files', 'allow_multiple');
    $filetypes          = xarModGetVar('files', 'filetypes');
    $dirmsg = !empty($dirmsg)?$dirmsg:'';
    if (empty($archive_dir)) {
        $dirmsg = xarML('Please set a valid archive directory to browse files. Make it writeable to allow users to write/upload files.');
    }elseif (!is_dir($archive_dir)) {
        $dirmsg = xarML('Please set a valid archive directory to browse files. The current one does not exist.');
    }elseif (!is_writable($archive_dir)) {
        $dirmsg = xarML('Your archive directory is not writeable. Users can browse but not write/upload files.');
    }
    // set template vars
    $data = array();
    $data['supportshorturls']  = $supportshorturls;
    $data['usemodulealias']    = $usemodulealias;
    $data['aliasname']         = $aliasname;
    $data['archive_dir']       = $archive_dir;
    $data['max_upload']        = $max_upload;
    $data['allow_multiple']    = $allow_multiple;
    $data['filetypes']          = $filetypes;
    $data['dirmsg'] = $dirmsg;
    // add other vars to template
    $data['authid']   = xarSecGenAuthKey();
    $data['menulinks'] = xarModAPIFunc('files','admin','getmenulinks');
    // get modifyconfig hooks
    $data['hookoutput'] = xarModCallHooks('module', 'modifyconfig', 'files',
        array('module' => 'files')
    );

    return $data;
}

?>
