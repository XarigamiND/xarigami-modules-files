<?php
/**
* Files - file manager
*
* @package unassigned
* @copyright (C) 2002-2005 by The Digital Development Foundation
* @license GPL {@link http://www.gnu.org/licenses/gpl.html}
*
* @subpackage Xarigami Files Module
* @copyright (C) 2009-2011 2skies.com
* @link http://xarigami.com/project/xarigami_files
*/
$modversion['name']           = 'files';
$modversion['id']             = '554';
$modversion['version']        = '0.6.3';
$modversion['displayname']    = 'File Manager';
$modversion['description']    = 'File Manager for Xarigami';
$modversion['credits']        = 'xardocs/credits.txt';
$modversion['help']           = 'xardocs/help.txt';
$modversion['changelog']      = 'xardocs/changelog.txt';
$modversion['license']        = 'xardocs/license.txt';
$modversion['official']       = 0;
$modversion['author']         = 'Original author Curtis Farnham, Jo Dalle Nogare, Xarigami Team';
$modversion['contact']        = 'http://xarigami.com';
$modversion['homepage']        = 'http://xarigami.com/project/xarigami_files';
$modversion['admin']          = 1;
$modversion['user']           = 1;
$modversion['class']          = 'Complete';
$modversion['category']       = 'Content';
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.3.4'
                                         ),
                                    999 => array(
                                            'name' => 'mime',
                                            'version_ge' => '2.4.0'
                                        )
                                );
if (false) { //Load and translate once
    xarML('File Manager');
    xarML('File Manager for Xarigami');
}
?>