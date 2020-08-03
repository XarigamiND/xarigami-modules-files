<?php
/**
* Files initialization functions
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
* Initialize the module
*
* This function is only ever called once during the lifetime of a particular
* module instance.
*/
function files_init()
{
    // register module vars
    xarModSetVar('files', 'SupportShortURLs', 0);
    xarModSetVar('files', 'archive_dir', sys::varpath().'/files');

    // register masks
    xarRegisterMask('ViewFiles',   'All', 'Files', 'Item', '', 'ACCESS_OVERVIEW');
    xarRegisterMask('ReadFiles',   'All', 'Files', 'Item', '', 'ACCESS_READ');
    xarRegisterMask('EditFiles',   'All', 'Files', 'Item', '', 'ACCESS_EDIT');
    xarRegisterMask('AddFiles',    'All', 'Files', 'Item', '', 'ACCESS_ADD');
    xarRegisterMask('DeleteFiles', 'All', 'Files', 'Item', '', 'ACCESS_DELETE');
    xarRegisterMask('AdminFiles',  'All', 'Files', 'Item', '', 'ACCESS_ADMIN');

   /* This init function brings our module to version 0.6.0, run the upgrades for the rest of the initialisation */
    return files_upgrade('0.6.0');
}

/**
* Upgrade the module from an old version
*
* This function can be called multiple times.
*
* @param string $oldVersion Version to upgrade from
*/

function files_upgrade($oldversion)
{
    /* Upgrade dependent on old version number */
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    switch ($oldversion) {
        case '0.6.0':
            xarRegisterMask('SubmitFiles', 'All', 'Files', 'Item', '', 'ACCESS_COMMENT');
            xarRegisterMask('ModerateFiles', 'All', 'Files', 'Item', '', 'ACCESS_MODERATE');
        case '0.6.1':
            xarModSetVar('files','max_upload',3);
            xarModSetVar('files','allow_multiple',true);
            xarModSetVar('files','filetypes','jpg,jpeg,png,gif,pdf,txt,zip,gz');
        case '0.6.2':
            break;
    }
    /* Update successful */
    return true;
}


/**
* Delete the module
*
* This function is only ever called once during the lifetime of a particular
* module instance.
*/
function files_delete()
{
    // remove module vars and masks
    xarModDelAllVars('files');
    xarRemoveMasks('files');

    // Deletion successful
    return true;
}

?>
