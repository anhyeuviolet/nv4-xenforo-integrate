<?php

/**
* @Project NUKEVIET 3.x
* @Author DANGDINHTU (dlinhvan@gmail.com)
* @Copyright (C) 2013 tdweb.vn All rights reserved
* @Createdate  2, 9, 2014 20:21
*/

if ( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/library/XenForo/Autoloader.php' ) )
{
    Header( "Location: " . $global_config['site_url'] . "/" . DIR_FORUM . "/index.php?account/personal-details" );
    die();
}
else
{
    trigger_error( "Error no forum xenforo", 256 );
}
