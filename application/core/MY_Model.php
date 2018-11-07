<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model {

	protected $_table_name='';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='';
	protected $_rules=[];
	protected $_timestamps=false;


	function __construct()
	{ 
		parent::__construct();
		$this->loggedid = $this->session->userdata('UserUID');
		
		$this->UserName = $this->session->userdata('UserName');
		$this->RoleUID = $this->session->userdata('RoleUID');
	}

	// Return Result.
	public function get($_table_name, $filter = NULL, $_order_by=NULL, $_group_by=NULL)
	{
		// CHECK TABLE IS NOT EMPTY
		if (empty($_table_name)) {
			return false;
		}

		// DECIDE IF FILTER TABLE BY ARRAY OR STRING
		if (is_array($filter) && !empty($filter)) {
			$this->db->where($filter);			
		}
		elseif (!empty($filter) && !empty($keyword)) {
			$this->db->where($filter, $keyword);
		}

		// DECIDE IF ORDER BY TABLE USING ARRAY OR STRING
		if (is_array($_order_by) && !empty($_order_by)) {
			foreach ($_order_by as $key => $value) {
				if ($value=='DESC') {
					$this->db->order_by($key, $value);
				}
				else{
					$this->db->order_by($key, 'ASC');
				}
			}
		}

		// DECIDE IF GROUP BY TABLE USING ARRAY OR STRING
		if (is_array($_group_by) && !empty($_group_by)) {
			foreach ($_group_by as $key => $value) {
				$this->db->group_by($value);
			}
		}
		else if (!empty($_group_by)) {
			$this->db->group_by($_group_by);
		}

		return $this->db->get($_table_name)->result();


	}

	// Return Row.
	public function get_row($_table_name, $filter = NULL, $_order_by=NULL, $_group_by=NULL)
	{

		// CHECK TABLE IS NOT EMPTY
		if (empty($_table_name)) {
			return false;
		}

		// DECIDE IF FILTER TABLE BY ARRAY OR STRING
		if (is_array($filter) && !empty($filter)) {
			$this->db->where($filter);			
		}
		elseif (!empty($filter) && !empty($keyword)) {
			$this->db->where($filter, $keyword);
		}

		// DECIDE IF ORDER BY TABLE USING ARRAY OR STRING
		if (is_array($_order_by) && !empty($_order_by)) {
			foreach ($_order_by as $key => $value) {
				if ($value=='DESC') {
					$this->db->order_by($key, $value);
				}
				else{
					$this->db->order_by($key, 'ASC');
				}

			}
		}

		// DECIDE IF GROUP BY TABLE USING ARRAY OR STRING
		if (is_array($_group_by) && !empty($_group_by)) {
			foreach ($_group_by as $key => $value) {
				$this->db->group_by($value);
			}
		}
		else if (!empty($_group_by)) {
			$this->db->group_by($_group_by);
		}

		return $this->db->get($_table_name)->row();

	}

	public function save($_table_name, $data, $primarykey = NULL, $value = NULL)
	{
		// Insert
		if ($primarykey === NULL) {
			if ( !empty($data) && ( is_array($data) || is_object($data) ) ) {
				$this->db->insert($_table_name, $data);
				$id =  $this->db->insert_id();
			}
			return $id;			
		}
		// Update
		else{
			if ( !empty($data) && ( is_array($data) || is_object($data) ) && $value != NULL) {
				$this->db->set($data);
				$this->db->where($primarykey, $value);
				$this->db->update($_table_name);

				$id = $value;
				return $id;			
			}
		return false;			
		}

	}
	// Delete
	public function delete($_table_name, $primary_key, $value)
	{
		// Insert
		if ($primary_key != NULL && $value != NULL) {
			$this->db->where($primary_key, $value);
			$this->db->delete();
			return true;
		}
		return false;
	}


	/* --- Find Date Differnce of two date ---- */
	function dateDiff($date1, $date2) 
	{
		$date1_ts = strtotime($date1);
		$date2_ts = strtotime($date2);
		$diff = $date2_ts - $date1_ts;
		return round($diff / 86400);
	}

	public function CreateDirectoryToPath($Path = '')
	{
		if (!file_exists($Path)) {
			if (!mkdir($Path, 0777, true)) die('Unable to create directory');
		}
		chmod($Path, 0777);
	}
	



}