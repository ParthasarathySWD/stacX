<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_Model extends MY_Model {

	
	function __construct()
	{ 
		parent::__construct();
	}

	public function GetCustomerProjects($CustomerUID)
	{
		$this->db->select('*')->from('mProjectCustomer');
		$this->db->where('CustomerUID', $CustomerUID);
		$this->db->join('mCustomer', 'mCustomer.CustomerUID = mProjectCustomer.CustomerUID', 'left');
		
		return $this->db->get()->result();
	}

	public function GetCustomerandProject_row($CustomerUID, $ProjectUID)
	{
		$this->db->select('mProjectCustomer.CustomerUID, mProjectCustomer.ProjectUID, mProjectCustomer.ProjectName, mCustomer.CustomerName, mProjectCustomer.Priority')->from('mProjectCustomer');
		$this->db->join('mCustomer', 'mCustomer.CustomerUID = mProjectCustomer.CustomerUID', 'left');
		$this->db->where('mProjectCustomer.CustomerUID', $CustomerUID);
		$this->db->where('mProjectCustomer.ProjectUID', $ProjectUID);
		
		return $this->db->get()->row();
	}

	public function GetCustomerandProject_rowByName($CustomerUID, $ProjectName)
	{
		$this->db->select('mProjectCustomer.CustomerUID, mProjectCustomer.ProjectUID, mProjectCustomer.ProjectName, mCustomer.CustomerName, mProjectCustomer.Priority')->from('mProjectCustomer');
		$this->db->join('mCustomer', 'mCustomer.CustomerUID = mProjectCustomer.CustomerUID', 'left');
		$this->db->where('mProjectCustomer.CustomerUID', $CustomerUID);
		$this->db->where('mProjectCustomer.ProjectName', $ProjectName);
		
		return $this->db->get()->row();
	}


	function getCityDetail($zipcode)
	{
		
		$zipcode = str_replace('-', '', $zipcode);
		$query = $this->db->get_where('mcities', array('ZipCode' => $zipcode, "mcities.Active" => 1));
		$result = $query->result();
		
		if (empty($result)) {
			$zipcode_new = substr("$zipcode", 0, 5);
			$query = $this->db->query("SELECT * FROM `mcities` WHERE mcities.ZipCode  LIKE '$zipcode_new%' AND `Active` = 1");
			return $query->result();
		} else {
			return $result;
		}
		
	}

	function getStateDetail($zipcode)
	{
		
		$zipcode = str_replace('-', '', $zipcode);
		$query = $this->db->query("SELECT DISTINCT a.StateUID, StateCode, StateName from 
		(select StateUID, StateCode,StateName, Active from mstates)a
		LEFT JOIN 
		(SELECT StateUID,ZipCode from mcities)b  
		ON a.`StateUID`=b.`StateUID` WHERE `ZipCode` = $zipcode AND a.`Active` = 1");
		$result = $query->result();
		
		if (empty($result)) {
			$zipcode_new = substr("$zipcode", 0, 5);
			$query = $this->db->query("SELECT DISTINCT a.StateUID, StateCode, StateName from 
			(select StateUID, StateCode,StateName, Active from mstates)a
			LEFT JOIN 
			(SELECT StateUID,ZipCode from mcities)b  
			ON a.`StateUID`=b.`StateUID` WHERE `ZipCode` LIKE '$zipcode_new%' AND a.`Active` = 1");
			return $query->result();
		} else {
			return $result;
		}
		
	}

	function getCountyDetail($zipcode)
	{
		$zipcode = str_replace('-', '', $zipcode);
		$query = $this->db->query("SELECT DISTINCT a.CountyUID, CountyName from 
		(select CountyUID,CountyName, Active from mcounties)a
		LEFT JOIN 
		(SELECT CountyUID,ZipCode from mcities)b  
		ON a.`CountyUID`=b.`CountyUID` WHERE `ZipCode` = $zipcode AND a.`Active` = 1");
		
		$result = $query->result();
		
		if (empty($result)) {
			$zipcode_new = substr("$zipcode", 0, 5);
			$query = $this->db->query("SELECT DISTINCT a.CountyUID, CountyName from 
			(select CountyUID,CountyName, Active from mcounties)a
			LEFT JOIN 
			(SELECT CountyUID,ZipCode from mcities)b  
			ON a.`CountyUID`=b.`CountyUID` WHERE `ZipCode` LIKE '$zipcode_new%' AND a.`Active` = 1");
			return $query->result();
		} else {
			return $result;
		}
	}


}?>
