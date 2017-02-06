<?php

/**
 * @Project NUKEVIET 3.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 tdweb.vn All rights reserved
 * @Createdate  2, 9, 2014 20:21
 */
if (! defined ( 'NV_IS_MOD_USER' ))
	die ( 'Stop!!!' );

define ( 'IN_XENFORO', true );

if (file_exists ( NV_ROOTDIR . '/' . DIR_FORUM . '/library/XenForo/Autoloader.php' )) {
	$db_nkv = $db;
	$op_nkv = $op;
	global $db, $config, $xenforo_root_path;
	$result = "";
	$xenforo_root_path = NV_ROOTDIR . '/' . DIR_FORUM . '/';
	
	include ($xenforo_root_path . 'library/XenForo/Autoloader.php');
	
	XenForo_Autoloader::getInstance ()->setupAutoloader ( $xenforo_root_path . '/library' );
	XenForo_Application::initialize ( $xenforo_root_path . '/library', $xenforo_root_path );
	XenForo_Session::startPublicSession ();
	XenForo_Application::disablePhpErrorHandler ();
	XenForo_Application::setDebugMode ( true );
	$row = XenForo_Visitor::getInstance ();
	$session = XenForo_Application::get ( 'session' );
	$session->changeUserId ( $row ['user_id'] );
	$session->save ();
	XenForo_Visitor::setup ( $row ['user_id'] );
	
	// if guest on front-end, login there too
	$result = new XenForo_Session ();
	$result->start ();
	if (! $result->get ( 'user_id' )) {
		$result->changeUserId ( $row ['user_id'] );
		$result->save ();
	}
	if ($result) {
		define ( 'NV_IS_USER_LOGIN_FORUM_OK', true );
	}
	$db = $db_nkv;
	$op = $op_nkv;
} else {
	trigger_error ( "Error no forum xenforo", 256 );
}
