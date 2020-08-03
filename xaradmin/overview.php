<?php
/**
* Display module overview
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
 * Display module overview
 * @return array
 */
function files_admin_overview()
{
    if (!xarSecurityCheck('AdminFiles', 0)) return;

    $data = array();
    $data['menulinks'] = xarModAPIFunc('files','admin','getmenulinks');

    return $data;
}

?>