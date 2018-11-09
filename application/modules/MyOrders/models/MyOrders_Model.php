<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MyOrders_Model extends MY_Model {
	
	function __construct()
	{ 
		parent::__construct();
   
	}


	// MyOrders
	  function count_all()
	  {

      $status[0] = $this->config->item('keywords')['New Order'];
      $status[1] = $this->config->item('keywords')['Waiting For Images'];
      $status[2] = $this->config->item('keywords')['Image Received'];
      $status[3] = $this->config->item('keywords')['Stacking In Progress'];
      $status[4] = $this->config->item('keywords')['Stacking Completed'];
      $status[5] = $this->config->item('keywords')['Review In Progress'];
      $status[6] = $this->config->item('keywords')['Review Completed'];
      $status[7] = $this->config->item('keywords')['Export'];
      $status[8] = $this->config->item('keywords')['Draft In Progress'];
      $status[9] = $this->config->item('keywords')['Drafted'];




  	  $this->db->select("*,mStatus.StatusName,mStatus.StatusColor,mCustomer.CustomerName,mProjectCustomer.ProjectUID");
  		$this->db->from('tOrderAssignment');
      $this->db->join('tOrders','tOrders.OrderUID = tOrderAssignment.OrderUID','left');
      $this->db->join('mUsers', 'tOrderassignment.AssignedToUserUID = mUsers.UserUID' , 'left' );
      $this->db->join('mStatus','tOrders.StatusUID = mStatus.StatusUID','left');
      $this->db->join('mCustomer','tOrders.CustomerUID = mCustomer.CustomerUID','left');
      $this->db->join('mProjectCustomer','tOrders.ProjectUID = mProjectCustomer.ProjectUID','left');
      $this->db->where_in('torders.StatusUID', $status);
      if($this->RoleUID == 8)
      {
        $this->db->join('mCustomerUser','tOrders.CustomerUID = mCustomerUser.CustomerUID','left');
        $this->db->where(array('mCustomerUser.UserUID'=>$this->loggedid));
      }
  	    $query = $this->db->count_all_results();
  	    return $query;
	  }

	  function count_filtered($post)
	  {
      $status[0] = $this->config->item('keywords')['New Order'];
      $status[1] = $this->config->item('keywords')['Waiting For Images'];
      $status[2] = $this->config->item('keywords')['Image Received'];
      $status[3] = $this->config->item('keywords')['Stacking In Progress'];
      $status[4] = $this->config->item('keywords')['Stacking Completed'];
      $status[5] = $this->config->item('keywords')['Review In Progress'];
      $status[6] = $this->config->item('keywords')['Review Completed'];
      $status[7] = $this->config->item('keywords')['Export'];
      $status[8] = $this->config->item('keywords')['Draft In Progress'];
      $status[9] = $this->config->item('keywords')['Drafted'];




  		$this->db->select("*,mStatus.StatusName,mStatus.StatusColor,mCustomer.CustomerName,mProjectCustomer.ProjectUID");
  		$this->db->from('tOrderAssignment');
      $this->db->join('tOrders','tOrders.OrderUID = tOrderAssignment.OrderUID','left');
      $this->db->join('mUsers', 'tOrderassignment.AssignedToUserUID = mUsers.UserUID' , 'left' );
      $this->db->join('mStatus','tOrders.StatusUID = mStatus.StatusUID','left');
      $this->db->join('mCustomer','tOrders.CustomerUID = mCustomer.CustomerUID','left');
      $this->db->join('mProjectCustomer','tOrders.ProjectUID = mProjectCustomer.ProjectUID','left');
      $this->db->where_in('torders.StatusUID', $status);
      if($this->RoleUID == 8)
      {
        $this->db->join('mCustomerUser','tOrders.CustomerUID = mCustomerUser.CustomerUID','left');
        $this->db->where(array('mCustomerUser.UserUID'=>$this->loggedid));
      }
  		if (!empty($post['search_value'])) {
  			$like = "";
           foreach ($post['column_search'] as $key => $item) { // loop column 
              // if datatable send POST for search
              if ($key === 0) { // first loop
              	$like .= "( ".$item." LIKE '%".$post['search_value']."%' "; 
              } else {
              	$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";    
              }
            }
            $like .= ") ";
            $this->db->where($like, null, false);
          }

          if (!empty($post['order'])) 
          { 
        	// here order processing 
          	if($post['column_order'][$post['order'][0]['column']]!='')
          	{
          		$this->db->order_by($post['column_order'][$post['order'][0]['column']], $post['order'][0]['dir']);    
          	}    
          } else if (isset($this->order)) {
          	$order = $this->order;
          	$this->db->order_by(key($order), $order[key($order)]);  
          }   
  	  	$query = $this->db->get();
  	  	return $query->num_rows();
	  }



  function MyOrders($post)
	{

      $status[0] = $this->config->item('keywords')['New Order'];
      $status[1] = $this->config->item('keywords')['Waiting For Images'];
      $status[2] = $this->config->item('keywords')['Image Received'];
      $status[3] = $this->config->item('keywords')['Stacking In Progress'];
      $status[4] = $this->config->item('keywords')['Stacking Completed'];
      $status[5] = $this->config->item('keywords')['Review In Progress'];
      $status[6] = $this->config->item('keywords')['Review Completed'];
      $status[7] = $this->config->item('keywords')['Export'];
      $status[8] = $this->config->item('keywords')['Draft In Progress'];
      $status[9] = $this->config->item('keywords')['Drafted'];
		
		  $this->db->select("*,mStatus.StatusName,mStatus.StatusColor,mCustomer.CustomerName,mProjectCustomer.ProjectUID");
		  $this->db->from('tOrderAssignment');
      $this->db->join('tOrders','tOrders.OrderUID = tOrderAssignment.OrderUID','left');
      $this->db->join('mUsers', 'tOrderassignment.AssignedToUserUID = mUsers.UserUID' , 'left' );
      $this->db->join('mStatus','tOrders.StatusUID = mStatus.StatusUID','left');
      $this->db->join('mCustomer','tOrders.CustomerUID = mCustomer.CustomerUID','left');
      $this->db->join('mProjectCustomer','tOrders.ProjectUID = mProjectCustomer.ProjectUID','left');
      $this->db->where_in('torders.StatusUID', $status);
    if($this->RoleUID == 8)
    {
      $this->db->join('mCustomerUser','tOrders.CustomerUID = mCustomerUser.CustomerUID','left');
      $this->db->where(array('mCustomerUser.UserUID'=>$this->loggedid));
    }
		if (!empty($post['search_value'])) {
			$like = "";
         foreach ($post['column_search'] as $key => $item) { // loop column 
            // if datatable send POST for search
            if ($key === 0) { // first loop
            	$like .= "( ".$item." LIKE '%".$post['search_value']."%' "; 
            } else {
            	$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";    
            }
          }
          $like .= ") ";
          $this->db->where($like, null, false);
        }

        if (!empty($post['order'])) 
        { 
      	// here order processing 
        	if($post['column_order'][$post['order'][0]['column']]!='')
        	{
        		$this->db->order_by($post['column_order'][$post['order'][0]['column']], $post['order'][0]['dir']);    
        	}    
        } else if (isset($this->order)) {
        	$order = $this->order;
        	$this->db->order_by(key($order), $order[key($order)]);  
        }


	    if ($post['length']!='') {
	       $this->db->limit($post['length'], $post['start']);
	    }
	    $query = $this->db->get();
	    return $query->result();  
	}
	// MyOrders

  function CheckAutoAssignEnabled($id)
  {
      $this->db->select("AutoAssign");
      $this->db->from('mUsers');
      $this->db->where(array('UserUID'=>$id));
      return $this->db->get()->row()->AutoAssign;
  }

  function CheckExistingOrders($id,$ProjectUID,$Workflow)
  {
      $this->db->select("*");
      $this->db->from('tOrderAssignment');
      $this->db->join('tOrders','tOrders.OrderUID = tOrderAssignment.OrderUID','left');
      $this->db->where('tOrderassignment.ProjectUID',$ProjectUID);
      if($Workflow == 1)
      {
        $this->db->where(array('AssignedToUserUID'=>$id));
      }else{
        $this->db->where(array('QcAssignedToUserUID'=>$id));
      }
      return $this->db->get()->result();
  }

  function GetProjectCustomers($id)
  {
      $this->db->select("*");
      $this->db->from('mProjectCustomer');
      $this->db->join('mCustomer','mCustomer.CustomerUID = mProjectCustomer.CustomerUID','left');
      $this->db->join('mCustomeruser','mCustomeruser.CustomerUID = mProjectCustomer.CustomerUID','left');
      $this->db->where(array('mCustomeruser.UserUID'=>$id));
      return $this->db->get()->result();
  }

  function GetQcUsers()
  {
      $this->db->select("*");
      $this->db->from('mUsers');
      return $this->db->get()->result();
  }

  function AssignOrders($id,$ProjectUID,$Workflow)
  {
      $this->db->select("OrderUID");
      $this->db->from('tOrderAssignment');
      if($Workflow == 1)
      {
        $this->db->where('AssignedToUserUID !=',NULL);
      }else{
        $this->db->where('QcAssignedToUserUID !=',NULL);
      }
      $this->db->where('tOrderassignment.ProjectUID',$ProjectUID);
      $query = $this->db->get()->result_array();

      $OrderUID = [];
      foreach ($query as $key => $value) {
        $OrderUID[$key] = $value['OrderUID'];
      }
      if(sizeof($OrderUID) == 0)
      {
        $OrderUID = '0';
      }

      $status[0] = $this->config->item('keywords')['New Order'];
      $status[1] = $this->config->item('keywords')['Waiting For Images'];
      $status[2] = $this->config->item('keywords')['Image Received'];
      
      $this->db->select("*");
      $this->db->from('tOrders');
      $this->db->where(array('ProjectUID'=>$ProjectUID));
      $this->db->where_in('tOrders.StatusUID', $status);
      $this->db->where_not_in('tOrders.OrderUID', $OrderUID);
      $this->db->limit(1);
      $query = $this->db->get();
      if($query->num_rows() > 0)
      {

          $queryassign = $query->row();
          $tOrderAssignmentArray = array(
            'OrderUID' => $queryassign->OrderUID, 
            'ProjectUID' => $queryassign->ProjectUID,
            'AssignedToUserUID' => $this->loggedid,
            'AssignedDateTime' => Date('Y-m-d H:i:s', strtotime("now")),
            'AssignedByUserUID' => $this->loggedid,
            'QcAssignedDateTime' => Date('Y-m-d H:i:s', strtotime("now")),
            'QcAssignedToUserUID' => $this->loggedid,
            'QcAssignedByUserUID' => $this->loggedid
          );
          $query = $this->db->insert('tOrderAssignment', $tOrderAssignmentArray);
          if($this->db->affected_rows() > 0)
          {
            return 1;
          }

      }else{

          return 0;

      }
    }
  




}?>