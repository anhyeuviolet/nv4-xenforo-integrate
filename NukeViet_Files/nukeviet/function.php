<?php

/**
 * @Project NUKEVIET 3.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 tdweb.vn All rights reserved
 * @Createdate  2, 9, 2014 20:21
 */
class Session {
	
	// Xenforo if logged in function
	function isOnline() {
		$session = new XenForo_Session ();
		if (! $session->sessionExists ()) {
			XenForo_Session::startPublicSession ();
			
			$visitor = XenForo_Visitor::getInstance ();
			
			if ($visitor->getUserId ()) {
				$userModel = XenForo_Model::create ( 'XenForo_Model_User' );
				$userinfo = $userModel->getFullUserById ( $visitor->getUserId () );
				return $userinfo;
			}
		} else {
			return false;
		}
	}
	public function login($data) {
		$request = new Zend_Controller_Request_Http ();
		$loginModel = XenForo_Model::create ( 'XenForo_Model_Login' );
		$userModel = XenForo_Model::create ( 'XenForo_Model_User' );
		$error = "";
		
		$userid = $userModel->validateAuthentication ( $data ['login'], $data ['password'], $error );
		
		if (! $userid) {
			$loginModel->logLoginAttempt ( $data ['login'] );
			return $error;
		}
		
		$loginModel->clearLoginAttempts ( $data ['login'] );
		
		XenForo_Model_Ip::log ( $userid, 'user', $userid, 'login' );
		
		$session = XenForo_Application::get ( 'session' );
		$session->changeUserId ( $userid );
		$session->save ();
		XenForo_Visitor::setup ( $userid );
		
		XenForo_Model::create ( 'XenForo_Model_User' )->updateSessionActivity ( $userid, $data ['ip'], "XenForo_ControllerPublic_Index", "Index", "valid", $data ['getUserParams'] );
		return;
	}
	function logout() {
		// remove an admin session if we're logged in as the same person
		if (XenForo_Visitor::getInstance ()->get ( 'is_admin' )) {
			$adminSession = new XenForo_Session ( array (
					'admin' => true 
			) );
			$adminSession->start ();
			if ($adminSession->get ( 'user_id' ) == XenForo_Visitor::getUserId ()) {
				$adminSession->delete ();
			}
		}
		
		XenForo_Model::create ( 'XenForo_Model_Session' )->processLastActivityUpdateForLogOut ( XenForo_Visitor::getUserId () );
		
		XenForo_Application::get ( 'session' )->delete ();
		XenForo_Helper_Cookie::deleteAllCookies ( array (
				'session' 
		), array (
				'user' => array (
						'httpOnly' => false 
				) 
		) );
		
		XenForo_Visitor::setup ( 0 );
		
		return true;
	}
}
