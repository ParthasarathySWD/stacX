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

	public function forgotpassword()
	{
		$this->load->view('forgotpassword');		
	}

	public function firstloginchangepasswordpage()
	{
		$this->load->view('firstloginchangepassword');	
	}

	public function updatepasswordpage()
	{
		$this->load->view('updatepassword');		
	}

	public function changepasswordpage()
	{
		$data['content'] = 'changepassword';
		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);		
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

						if($result['Firstlogin'] == 1)
						{
							$res = array("validation_error" => 1,'Redirect'=>'ChangePassword');
						    echo json_encode($res);exit;
						}else{

							$res = array("validation_error" => 1,'Redirect'=>'Dashboard','message' => '');
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

	function CheckLoginExist()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('loginid', 'LoginID/Email', 'required');
		if($this->form_validation->run() == TRUE)
		{
			$loginid = $this->input->post('loginid');  
			$result = $this->LoginModel->CheckLoginExist($loginid);
			if($result)
			{
				foreach($result as $data)
				{
					$Email = $data->EmailID;
				}
				$DynamicAccessCode = random_string('numeric', 8);
				$UserName = $this->session->userdata('UserName');
				$this->LoginModel->SaveDynamicAccessCode($Email,$DynamicAccessCode);
				if($this->ForgetPasswordVerification($Email,$DynamicAccessCode,$UserName))
				{
					$res = array("validation_error" => 1,'message' => 'Check Your Mail To reset Your Password');
					echo json_encode($res);exit;
				}
			}
			else
			{
				$res = array("validation_error" => 0,'message' => 'LoginID Does Not Exist');
				echo json_encode($res); 
			}
		}
		else
		{
			$data = array(
				'validation_error' => 2,
				'message' =>'Please Fill The Required Fields',
				'loginid' => form_error('loginid'),
			);

			foreach($data as $key=>$value)
			{
				if(is_null($value) || $value == '')
					unset($data[$key]);
			}
			echo json_encode($data); 
		}	
	}

	private function ForgetPasswordVerification($Email,$DynamicAccessCode,$UserName)
	{

 		$from_email = "notifications@direct2title.com"; 
        $to_email = $Email; 
         //Load email library 
         $this->load->library('email'); 
   
         $this->email->from($from_email); 
         $this->email->to($to_email);
         $this->email->subject('Your Dynamic Access Code'); 
         $this->email->message('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"> <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> </head> <body> <div class="row" style="border:2px solid #ccc;width:750px;margin:0 auto;"> <div class="row" > <p style="background-color:#f5f3f3;color:#808080;text-align:center;line-height:50px;font-size:20px;margin:10px;"><img src="https://staging.direct2title.com/assets/img/logo.png" alt="logo" class="logo-img" style="margin-top: 15px;margin-bottom: -10px;width: 21%;"></p></div><br/> <table style="max-width: 620px; margin: 0 auto;font-size:15px;line-height:22px;"> <tbody> <tr style="height: 23px;"> <td style="height: 23px;"><span style="font-weight: bold;">Hi '.$UserName.',</span></td></tr><tr style="height: 43px;"> <td style="height: 43px;">You recently requested to reset your password for your Direct2Title Account- Click the button below to reset it.<strong> Your Access Code is '.$DynamicAccessCode.'.</strong></td></tr><tr style="height: 29px;"> <td style="text-align: center; height: 29px;"><a href="<?php echo base_url();?>users/updatepassword" style="background-color: red; color: #fff; display: inline-block; padding: 10px 10px 10px 10px; font-weight: bold; border-radius: 5px; text-align: center;font-size: 12px;text-decoration:none;border:px solid #FFFFFF; -webkit-border-radius: px; -moz-border-radius: px;border-radius: px;width:px;font-size:px;font-family:arial, helvetica, sans-serif; padding: 5px 10px 5px 10px;margin: 7px 0; text-decoration:none; display:inline-block; color: #FFFFFF;background-color: #ff9a9a; background-image: -webkit-gradient(linear, left top, left bottom, from(#ff9a9a), to(#ff4040));background-image: -webkit-linear-gradient(top, #ff9a9a, #ff4040);background-image: -moz-linear-gradient(top, #ff9a9a, #ff4040);background-image: -ms-linear-gradient(top, #ff9a9a, #ff4040);background-image: -o-linear-gradient(top, #ff9a9a, #ff4040);background-image: linear-gradient(to bottom, #ff9a9a, #ff4040);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#ff9a9a, endColorstr=#ff4040);" class="btn button_example"><span style="padding:10px,10px,10px,10px;">Reset Your Password</style></a></td></tr><tr style="height: 43px;"> <td style="height: 43px;">If you did not request a password reset. please ignore this email or reply to let us know. This password reset is only valid for the next 30 minutes.</td></tr><tr style="height: 23px;"> <td style="height: 23px;"></td></tr><tr style="height: 23px;"> <td style="height: 23px;">Thanks,</td></tr><tr style="height: 23px;"> <td style="height: 23px;">StacX Team</td></tr><tr style="height: 23px;"> <td style="height: 23px;"></td></tr><tr style="height: 43px;"> <td style="height: 43px;"><span style="font-weight: bold;">P.S.</span> We also love hearing from you and helping you with any issues you have. Please reply to this email if you want to ask a question or just say hi.</td></tr><tr style="height: 23px;"> <td style="height: 23px;border-bottom: 1px solid #ccc;"></td></tr><tr style="height: 23px; "> <td style="height: 23px;"></td></tr><tr style="height: 43px;"> <td style="height: 43px;"> If you are having trouble clicking the password reset button, copy and paste the URL below into your web browser and click forgot password link.</td></tr><tr style="height: 43px;"> <td style="height: 43px;font-size: 12px;text-decoration: underline;"> <a href="https://StacX.com"> https://StacX.com</a></td></tr></tbody> </table> <div class="row" style="margin:10px,10px,10px,10px;margin-left:10px;margin-right:10px;"> <p style="padding:10px,10px,10px,10px;background-color:#f5f3f3;color:#907f7f;text-align:center;line-height:50px"><strong> StacX Team. All Rights Reserved.</strong></p></div></div></body></html><style type="text/css">#main{max-width: 600px;margin: 0 auto;}.button_example{border:px solid #FFFFFF; -webkit-border-radius: px; -moz-border-radius: px;border-radius: px;width:px;font-size:px;font-family:arial, helvetica, sans-serif; padding: 10px 10px 10px 10px; text-decoration:none; display:inline-block; color: #FFFFFF;background-color: #ff9a9a; background-image: -webkit-gradient(linear, left top, left bottom, from(#ff9a9a), to(#ff4040));background-image: -webkit-linear-gradient(top, #ff9a9a, #ff4040);background-image: -moz-linear-gradient(top, #ff9a9a, #ff4040);background-image: -ms-linear-gradient(top, #ff9a9a, #ff4040);background-image: -o-linear-gradient(top, #ff9a9a, #ff4040);background-image: linear-gradient(to bottom, #ff9a9a, #ff4040);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#ff9a9a, endColorstr=#ff4040);}.button_example:hover{border:px solid #FFFFFF;background-color: #ff6767; background-image: -webkit-gradient(linear, left top, left bottom, from(#ff6767), to(#ff0d0d));background-image: -webkit-linear-gradient(top, #ff6767, #ff0d0d);background-image: -moz-linear-gradient(top, #ff6767, #ff0d0d);background-image: -ms-linear-gradient(top, #ff6767, #ff0d0d);background-image: -o-linear-gradient(top, #ff6767, #ff0d0d);background-image: linear-gradient(to bottom, #ff6767, #ff0d0d);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#ff6767, endColorstr=#ff0d0d);}</style>'); 
   
         //Send mail 
         if($this->email->send()) 
         {
         	// echo 'Yes';
         	echo json_encode(array('validation_error'=>1,'message' => 'Check Your Mail To reset Your Password'));
         }
         else{
         	// echo 'No';
         	echo json_encode(array('validation_error'=>0,'message'=>$email->print_debugger()));
         }
	}

	function UpdatePassword()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('accesscode', 'Access Code', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required');
		if($this->form_validation->run() == TRUE)
		{
			$accesscode = $this->input->post('accesscode');
			$result = $this->LoginModel->CheckAccessCode($accesscode);
			if($result)
			{
				$password = $this->input->post('password');
				$cpassword = md5($this->input->post('cpassword'));


				if (strlen($password) > 8 && preg_match('/[0-9]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password))
				{

				if($this->input->post('password') == $this->input->post('cpassword'))
				{
					$result = $this->LoginModel->UpdatePassword($accesscode,$cpassword);
					if($result)
					{
						$res = array('validation_error' => 1,'message'=>'Password Updated Successfully');
					}
					else{
						$res = array('validation_error' => 0,'message'=>'Error');
					}
					
				}else{
					$res = array("validation_error" => 3,'message'=>'Confirm Password field does not Match');
				}
																	 
						} 
						else {
							$res = array("validation_error" => 3,'message'=>'Must be atleast eight characters contain upercase,lowercase and numeric','password' => form_error('password'));
						}
						//echo json_encode($res);exit; 
			}
			else
			{
				$res = array('validation_error' => 3,'message'=>'Entered Access Code is Wrong');
			}
			echo json_encode($res);exit;
		}
		else{
			$data = array(
				'validation_error' => 2,
				'message' =>'Please Fill The Required Fields',
				'accesscode' => form_error('accesscode'),
				'password' => form_error('password'),
				'cpassword' => form_error('cpassword'),
			);

			foreach($data as $key=>$value)
			{
				if(is_null($value) || $value == '')
					unset($data[$key]);
			}
			echo json_encode($data); 
		}
	}

 function ChangeCurrentPassword()
  {
    $this->form_validation->set_error_delimiters('', '');
    $this->form_validation->set_rules('oldpassword', 'Old Password', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required');
    if($this->form_validation->run() == TRUE)
    {
     
      $UserUID = $this->input->post('UserUID');
      $oldpassword = $this->input->post('oldpassword');
      $firstlogin = $this->input->post('Firstlogin');

      $result = $this->LoginModel->CheckOldPassword($oldpassword,$UserUID);
      if($result)
      {
        $cpassword = md5($this->input->post('cpassword'));
        $password = $this->input->post('password');
        if (strlen($password) > 8 && preg_match('/[0-9]/', $password)
          && preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password))
          {
          		if($this->input->post('cpassword') == $this->input->post('password'))
          		{
		            $result = $this->LoginModel->ChangePassword($UserUID,$cpassword,$firstlogin);
		            if($result)
		            {
		              $res = array('validation_error' => 1,'message'=>'Password Changed Successfully');
		              $this->session->sess_destroy();
		              echo json_encode($res);exit;
		            }
		            else{
		              $res = array('validation_error' => 3,'message'=>'Error');
		              echo json_encode($res);exit;
		            }                           
          		}else{
          			$res = array("validation_error" => 3,'message'=>'Confirm Password field does not Match');
          		}
          } 
          else {
            $res = array("validation_error" => 3,'message'=>'Must be atleast eight characters contain upercase,lowercase and numeric','password' => form_error('password'));
          }
      }
      else
      {
        $res = array('validation_error' => 3,'message'=>'Old Password is Incorrect','oldpassword' => form_error('oldpassword'));
      }
      echo json_encode($res);

    }
    else{

      $data = array(
        'validation_error' => 2,
        'message' =>'Please Fill The Required Fields',
        'oldpassword' => form_error('oldpassword'),
        'password' => form_error('password'),
        'cpassword' => form_error('cpassword'),
      );

      foreach($data as $key=>$value)
      {
        if(is_null($value) || $value == '')
          unset($data[$key]);
      }
      echo json_encode($data); 
    }

  }
}
?>
