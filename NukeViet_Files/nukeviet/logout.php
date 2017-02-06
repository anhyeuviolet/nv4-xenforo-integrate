<?php

/**
* @Project NUKEVIET 3.x
* @Author DANGDINHTU (dlinhvan@gmail.com)
* @Copyright (C) 2013 tdweb.vn All rights reserved
* @Createdate  2, 9, 2014 20:21
*/

if ( ! defined( 'NV_IS_MOD_USER' ) )
{
    die( 'Stop!!!' );
}
if( file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/library/XenForo/Autoloader.php' ) )
{
	$xenforo_root_path = NV_ROOTDIR . '/' . DIR_FORUM . '/';

	require ( $xenforo_root_path . 'library/XenForo/Autoloader.php' );
	
	XenForo_Autoloader::getInstance()->setupAutoloader($xenforo_root_path . '/library');
	XenForo_Application::initialize($xenforo_root_path . '/library', $xenforo_root_path);
	XenForo_Session::startPublicSession();
	XenForo_Application::disablePhpErrorHandler();
	XenForo_Application::setDebugMode(true);
	require ( $xenforo_root_path . 'nukeviet/function.php' );
	$session = new Session;
	$validate= $session->logout();

	$nv_Request->unset_request( 'nvloginhash', 'cookie' );
	$user_info = array();
	$user_info['in_groups'] = "";
}