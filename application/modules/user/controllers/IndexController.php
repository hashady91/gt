<?php 
class User_IndexController extends Cl_Controller_Action_UserIndex
{
	public function init()
	{
		parent::init();
	}
	
	public function viewAction()
	{
		parent::viewAction();	
	}
	
	public function anyOtherRequestAction()
	{
		
	}

	public function loginAction()
	{
		parent::loginAction();
		if (Zend_Registry::isRegistered('authentication_happened'))
		{
			//set one more cookie. _cl_is_admin
			$user = Zend_Registry::get('user');
			if (has_perm('admin_story'))
				set_cookie('is_admin', 1);
		}
		
		Bootstrap::$pageTitle = t('login_signup', 2);
	}
	
	public function registerAction()
	{
		parent::registerAction();
		Bootstrap::$pageTitle = t('login_signup', 2);
	}
	
	public function logoutAction(){
	//clear identity & reset cookie
		$adapter = new Cl_Auth_Adapter_PersistentDb();
		$r = $adapter->clearIdentity();
		$r = array('success' => true);
		
		set_cookie('is_admin','', -3600);
		
		if(is_ajax()) {
			send_json($r);
		}
		else
		{
			if(!$r['success']){
				// return error;
				$this->setViewParam('err', $r['err']);
			}
			else {
				// redirect to homepage here?
				$this->_redirect("/");
			}
		}
	}
}
