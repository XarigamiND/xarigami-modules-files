<?php
/**
* Main administration function
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
 * Main administration function
 *
 * Redirect to modifyconfig
 * @return bool true on success of redirect
 */
function files_admin_main()
{
    // security check
    if (!xarSecurityCheck('AdminFiles')) return;
    $data = array();
    $data['menulinks'] = xarModAPIFunc('files','admin','getmenulinks');

    // show overview or redirect to a more useful function
    xarResponseRedirect(xarModURL('files', 'admin', 'modifyconfig', $data));
    // success
    return true;
}

?>
