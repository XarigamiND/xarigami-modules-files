<?php
/**
* Serve a file to the user for viewing
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
* Serve a file to the user for viewing
*
* Send the raw file and hope the client's browser has a plugin for it.
*
* @param  string $args['path'] Item to be viewed, with relative path
*/
function files_user_view($args)
{
    // security check
    if (!xarSecurityCheck('ReadFiles')) return;

    extract($args);

    // get HTTP vars
    if (!xarVarFetch('path', 'str:1', $path, $path)) return;

    // clean up HTML elements in path
    $path = xarModAPIFunc('files', 'user', 'cleanpath', array('path' => $path));

    // get details on this file
    $item = xarModAPIFunc('files', 'user', 'get', array('path' => $path));

    $validfile = strstr($path,'.');
    if (!$validfile) $item['viewable'] = false ;
    if ($item['viewable'])  {
         // send file to user's browser
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        if ($item['mime']) header("Content-Type: $item[mime]");
        header("Content-length: $item[size]");

        // send file in chunks so we avoid memory_limit problems
        // this section taken from user comments at http://www.php.net/readfile
        $chunksize = 1*(1024*1024);
        $handle = fopen($item['realpath'], 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            echo fread($handle, $chunksize);
        }
        fclose($handle);
        // make sure Xar doesn't try to output anything
        exit;
    } else {
        xarResponseRedirect(xarModURL('files','user','display',array('path'=>$path)));
    }

}

?>
