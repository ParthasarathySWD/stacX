<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();	
		ob_start();
		$this->load->library('form_validation');
		$this->load->library('Aes');
		$this->load->library('AesCtr');
		$this->load->model('LoginModel');
		$this->load->helper('string');
		

	}
	public function index()
	{	
		if($this->session->userdata('UserUID'))
		{
			redirect(base_url('Dashboard'));
		}else{

		$this->load->view('index');		
		}
	}


	function Logout()
	{ 

		$this->session->sess_destroy();
		redirect(base_url('Login')); 
		
	}


	function LoginSubmit()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('Username', '', 'required');
		$this->form_validation->set_rules('Password', '', 'required');

		if($this->form_validation->run() == TRUE)
		{
			
				$Username = $this->input->post('Username');
				$Password = md5($this->input->post('Password'));
				$result = $this->LoginModel->CheckLogin($Username,$Password);
				if(!empty($result['Password']))
				{

					$salt_key = $this->session->userdata('salt_key');
					$secret = 'zdfghtuOdfPKJL2551*^$#$()k';
					$encrypt = new AesCtr();
					$encrptval = $encrypt->encrypt($salt_key, $secret, 256); 
					$userpwd = md5($Password.$encrptval);
					$dbpwd = md5($result['Password'].$encrptval);				   
					if($userpwd == $dbpwd)
					{ 
						$res = array("validation_error" => 1,'Redirect'=>'Dashboard','message' => '' ,"RoleType" => 0);
								echo json_encode($res);exit;
					} 
					else 
					{
						$res = array("validation_error" => 2,'message' => 'Invalid Username or Password');
						echo json_encode($res);exit;
					}
				}
				else
				{
					$res = array("validation_error" => 2,'message' => 'Invalid Username or Password');
					echo json_encode($res);exit;
				}

		}
		else
		{ 
			$data = array(
				'validation_error' => 2,
				'message' =>'Please Fill The Required Fields',
				'Username' => form_error('Username'),
				'Password' => form_error('Password'),
			); 
			foreach($data as $key=>$value)
			{
				if(is_null($value) || $value == '')
					unset($data[$key]);
			}
			echo json_encode($data);exit;
		} 
	}
}
?>
