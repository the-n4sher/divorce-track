<?php 

namespace App\Libraries;

class Authentication
{	
	private $user;

	public function login($email, $password)
	{

		$model = model("UserModel");

		// Check if this is a split application, if it is then we need to check against that email instead
		if (strpos($email, 'jointapplications') !== false) 
		{
		    // check if this is a valid splitapplications email address, if it is, return the account email address
		    $email = $model->checkForSplitEmail($email);
		    if($email === false)
		    {
		    	return false;
		    }
		}

    	$user = $model->findByEmail($email);

    	if($user === null){
    		return false;
    	}

    	if( ! $user->verifyPassword($password, $user->Password) )
    	{
    		
    		// Check if this is a temp password before failing the reqest
    		if($user->TempCmPass != "" && $user->temp_password_expire != "")
    		{
    			// Has it expired?
    			$now = date("Y-m-d H:i:s");

    			// The password has expired
    			if($now > $user->temp_password_expire){
    				return false;
    			}

    			if($user->verifyPassword($password, $user->TempCmPass) ){
    				$this->loginUser($user->ID);
					return true;
    			}
    		}

    		return false;
    	}

    	
    	//Set the sessions
		$this->loginUser($user->ID);
		return true;
    	
	}

	public function returnName()
	{
		$user = $this->getCurrentUser();

		return ( $user ) ? ucwords(strtolower($user->first_name)) : "";
	}


	public function loginUser($user)
	{
		$session = session();
		$session->regenerate();
		$session->set('user_id', $user);

		$model = model("UserModel");
		$model->updateLastLogin($user);
	}

	public function logout()
	{
		session()->destroy();
	}

	public function getUserFromSession()
	{
		if( ! session()->has('user_id') ){
			return null;
		}

		$model = new \App\Models\UserModel;
		$user = $model->find(session()->get("user_id"));

		if($user){
			return $user;
		}
	}

	public function getCurrentUser()
	{

		if($this->user === null)
		{
			$this->user = $this->getUserFromSession();
		}
		return $this->user;

	}

	public function isLoggedIn(){
		return $this->getCurrentUser() !== null;
	}

} 