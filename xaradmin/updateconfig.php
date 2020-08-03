<?php
/**
* Update module configuration
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
* Update module configuration
*/
function files_admin_updateconfig()
{
    // security check
    if (!xarSecConfirmAuthKey()) return;

    // get HTTP vars
    if (!xarVarFetch('supportshorturls', 'checkbox', $supportshorturls, false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('usemodulealias', 'checkbox', $usemodulealias, false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('allow_multiple', 'checkbox', $allow_multiple, false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('aliasname', 'str:1:', $aliasname, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('archive_dir', 'str:0', $archive_dir, sys::varpath().'/files', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('max_upload', 'int:0', $max_upload, 0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('filetypes', 'str:0:', $filetypes, '', XARVAR_NOT_REQUIRED)) return;
    // validate archive dir
    if (empty($archive_dir) || !is_dir($archive_dir) || !is_readable($archive_dir)) {
        $dirmsg = xarML('Invalid archive directory.  Make sure it exists and is readable.');
        xarResponseRedirect(xarModURL('files','admin','modifyconfig', array('dirmsg'=>$dirmsg)));
    }

    // validate and clean up module alias
    $aliasname = trim($aliasname);
    $aliasname = str_replace(' ', '_', $aliasname);
    $currentalias = xarModGetVar('files', 'aliasname');
    if ($usemodulealias && $aliasname) {
        if (!xarModSetAlias($aliasname, 'files')) return;
    } elseif ($currentalias) {
        xarModDelAlias($currentalias, 'files');
    }
    //clean filetypes string
    $filetypes = str_replace(' ','',$filetypes);
    // save module vars
    xarModSetVar('files', 'SupportShortURLs', $supportshorturls);
    xarModSetVar('files', 'useModuleAlias', $usemodulealias);
    xarModSetVar('files', 'aliasname', $aliasname);
    xarModSetVar('files', 'archive_dir', trim($archive_dir));
    xarModSetVar('files', 'max_upload', $max_upload);
    xarModSetVar('files', 'allow_multiple', $allow_multiple);
    xarModSetVar('files', 'filetypes', trim($filetypes));
    // call updateconfig hooks
    xarModCallHooks('module', 'updateconfig', 'files', array('module' => 'files'));

    // set session var and redirect to modifyconfig page
    $msg= xarML('Configuration successfully updated!');
    xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('files', 'admin', 'modifyconfig'));

    // success
    return true;
}

?>