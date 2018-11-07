<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class LoginModel extends MY_Model {

	
	function __construct()
	{ 
		parent::__construct();
	}

	function CheckLogin($Username,$Password)
	{
		$this->db->select('*');	
		$this->db->from('mUsers');
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
			  $Firstlogin =  $data->FirstLogin;
			  $RoleUID = $data->RoleUID;
			}
			if($Firstlogin == 1)
			{
				$data = array('UserUID'=>$UserUID);
				$this->session->set_userdata($data);
				return array('Firstlogin'=>$Firstlogin,'Password'=>$Password);
			}
			else{

				$data = array('UserUID'=>$UserUID,'UserName'=>$UserName,'RoleUID'=>$RoleUID);
				$this->session->set_userdata($data);
				return array('Firstlogin'=>$Firstlogin,'Password'=>$Password);
			}

		}
		else
		{
			return false;
		}
	}

	function CheckLoginExist($loginid)
    {

    	$this->db->select('LoginID,EmailID');	
		$this->db->from('mUsers');
		$this->db->where(array('LoginID'=>$loginid)); 
		$this->db->or_where(array('EmailID'=>$loginid)); 
		$query = $this->db->get();
    	if($query->num_rows() > 0)
        {
            $result = $query->result();
			return $result;
        }
        else{
            return false;
        }
    }

    function SaveDynamicAccessCode($Email,$DynamicAccessCode)
	{
		$fieldArray = array(
	          "DynamicAccessCode"=>$DynamicAccessCode,  
	    );
		$this->db->where(array("EmailID"=>$Email));    
        $result = $this->db->update('mUsers', $fieldArray);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function CheckAccessCode($accesscode)
    {
		$this->db->select('*');	
		$this->db->from('mUsers');
		$this->db->where(array('DynamicAccessCode'=>$accesscode)); 
		$query = $this->db->get();
    	if($query->num_rows() > 0)
        {
			return true;
        }
        else{
            return false;
        }
    }

	function UpdatePassword($accesscode,$cpassword)
	{
		$fieldArray = array(
	          "DynamicAccessCode"=>'',  
	          "Password"=>$cpassword,
	          
	        );
		$this->db->where(array("DynamicAccessCode"=>$accesscode));    
        $result = $this->db->update('mUsers', $fieldArray);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

  function ChangePassword($UserUID,$cpassword,$firstlogin)
  {

  		if($firstlogin == 1)
  		{
  		    $fieldArray = array(
		          "Password"=>$cpassword,  
		          "FirstLogin"=>'0'
		    );

  		}else{

		    $fieldArray = array(
		          "Password"=>$cpassword,  
		    );
  		}
		$this->db->where(array("UserUID"=>$UserUID));    
        $result = $this->db->update('mUsers', $fieldArray);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
  }


	  function CheckOldPassword($oldpassword,$UserUID)
	  {
		$this->db->select('*');	
		$this->db->from('mUsers');
		$this->db->where(array('UserUID'=>$UserUID)); 
		$query = $this->db->get();
	    if($query->num_rows() > 0)
	    {
	      $result = $query->result();
	      foreach($result as $data)
	      {
	        $Pass = $data->Password;
	      }
	      $EncPassword = md5($oldpassword);
	      if($Pass == $EncPassword)
	      {
	        
	        return true;
	      }
	      else
	      {
	        
	        return false;
	      }
	    }
	  }

}
?>