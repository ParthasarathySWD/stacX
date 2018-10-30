<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class LoginModel extends MY_Model {

	
	function __construct()
	{ 
		parent::__construct();
	}

	function CheckLogin($Username,$Password)
	{
		$this->db->select('*');	
		$this->db->from('musers');
		$this->db->where(array('LoginID'=>$Username,'Password'=>$Password)); 
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$result = $query->result();
			foreach($result as $data)
			{
			  $UserUID = $data->UserUID;
			  $UserName = $data->UserName;
			  $Password = $data->Password;
			}
			$data = array('UserUID'=>$UserUID,'UserName'=>$UserName);
			$this->session->set_userdata($data);
			return array('Password'=>$Password);

		}
		else
		{
			return false;
		}
	}
}