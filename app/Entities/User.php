<?php 

namespace App\Entities;
use CodeIgniter\Entity\Entity;
use App\Libraries\Token;

class User extends Entity
{

	public function verifyPassword($password, $userPass){
		return password_verify($password, $userPass);
	}

	public function startActivation()
	{
		$token = new Token;
		$this->token = $token->getValue();
		$this->activation_hash = $token->getHash();
	}

	public function activate()
	{
		$this->is_active = true;
		$this->activation_hash = null;
	}

	public function startPasswordReset()
	{
		$token = new Token;
		$this->reset_token = $token->getValue();
		$this->reset_hash = $token->gethash();

		$this->reset_expires_at = date("Y-m-d H:i:s", time() + 1800);

	}

	public function completePasswordReset()
	{
		$this->reset_hash = null;
		$this->reset_expires_at = null;
	}
}