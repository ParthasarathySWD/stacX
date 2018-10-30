<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MyOrders_Model extends MY_Model {
	
	function __construct()
	{ 
		parent::__construct();
	}

	function GetRoles(){

		$query = $this->db->get('mroles');
		return $query->result();
	}

	function GetCheckCustomFields($UserUID){

		$query = $this->db->query("SELECT EXISTS(SELECT * FROM mcustomsortcolumns WHERE CustomSortByUserUID = '$UserUID') as CheckCustomFields;
			");
		return $query->row();
	}

	function GetUserByRoleUID($RoleUID = '')
	{
		$User = $this->db->query("SELECT * FROM `musers` 
			WHERE musers.RoleUID = '$RoleUID'")->result_array(); 
		return array('User'=>$User);
	}

	function GetUserName($UserUID = '')
	{
		$query = $this->db->query("SELECT * FROM `musers` 
			WHERE musers.UserUID = '$UserUID'");
		return $query->row();
	}

	function GetCustomerByUserUID($UserUID){
		$this->db->select("CustomerUID");
		$this->db->from('musers');
		$this->db->where(array("UserUID"=>$UserUID));
		$query = $this->db->get();
		return $query->row();
	}

	function get_myorders($loggedid = '')
	{
		$status[0] = $this->config->item('keywords')['Order Assigned'];
		$status[1] = $this->config->item('keywords')['Order Work In Progress'];
		$status[2] = $this->config->item('keywords')['Partial Review Complete'];
		$status[3] = $this->config->item('keywords')['Partial Draft Complete'];
		$status[4] = $this->config->item('keywords')['Review In Progress'];
		$status[5] = $this->config->item('keywords')['Reopened Order'];
		
    //$status[2] = $this->config->item('keywords')['Complete'];

		$this->db->select ( 'CustomerName,OrderNumber,StatusName,StatusColor,torders.StatusUID,torderassignment.OrderUID,mproducts.ProductName,mproducts.ProductCode,msubproducts.SubProductCode,PropertyStateCode,VendorAssignedDateTime' );
		$this->db->select('DATE_FORMAT(torderassignment.AssignedDatetime, "%m-%d-%Y %H:%i:%s") as AssignedDatetime', FALSE);
		$this->db->select("DATE_FORMAT(OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime", FALSE);
		$this->db->select('DATE_FORMAT(torders.OrderDueDateTime, "%m-%d-%Y %H:%i:%s") as OrderDueDateTime', FALSE);    
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'torders', 'torderassignment.OrderUID = torders.OrderUID' , 'left' );
		$this->db->join ( 'musers', 'torderassignment.AssignedToUserUID = musers.UserUID' , 'left' );
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID' , 'left' );
		$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = torders.CustomerUID' , 'left' );
		$this->db->join ( 'morderstatus', 'morderstatus.StatusUID = torders.StatusUID' , 'left' );
		$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID' , 'left' );

		$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID' , 'left' );

		$this->db->where_in('torders.StatusUID', $status);
		$this->db->where('torderassignment.AssignedToUserUID',$loggedid);
		
		$this->db->group_by('OrderUID,AssignedToUserUID');
		$query = $this->db->get();
		return $query->result_array();
	}

	function GetTBodyContent($UserUID,$DynTable,$Sort,$VendorUID = '')
	{
		/*vendor change Starts*/
		$where = '';
		if($VendorUID != ''){
			$where = "AND torderassignment.WorkflowModuleUID !=4 AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'";
		}
		/*vendor change Ends*/

		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{
			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];
			$query = $this->db->query("SELECT torders.OrderUID,morderpriority.TAT,morderpriority.PriorityUID,morderstatus.StatusName,morderstatus.StatusColor,torders.StatusUID,".$DynTable."
				FROM (`torders`) 
				LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID` 
				LEFT JOIN `torderpropertyroles` ON `torders`.`OrderUID` = `torderpropertyroles`.`OrderUID`
				LEFT JOIN `mtemplates` ON `torders`.`TemplateUID` = `mtemplates`.`TemplateUID` 
				LEFT JOIN `mordertypes` ON `torders`.`OrderTypeUID` = `mordertypes`.`OrderTypeUID` 
				LEFT JOIN `musers` ON `torderassignment`.`AssignedToUserUID` = `musers`.`UserUID` 
				LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` 
				LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` 
				LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` 
				LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` 
				LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` 
				WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$UserUID." ".$where." 
				AND torderassignment.WorkflowModuleUID !=4 
				GROUP BY torders.`OrderUID` 
				ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC ,".$Sort."");
		}
		else
		{
			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];

			$query = $this->db->query("SELECT torders.OrderUID,morderpriority.TAT,morderpriority.PriorityUID,morderstatus.StatusName,morderstatus.StatusColor,torders.StatusUID,".$DynTable."
				FROM (`torders`)  
				LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID` 
				LEFT JOIN `torderpropertyroles` ON `torders`.`OrderUID` = `torderpropertyroles`.`OrderUID` 
				LEFT JOIN `mtemplates` ON `torders`.`TemplateUID` = `mtemplates`.`TemplateUID` 
				LEFT JOIN `mordertypes` ON `torders`.`OrderTypeUID` = `mordertypes`.`OrderTypeUID` 
				LEFT JOIN `musers` ON `torderassignment`.`AssignedToUserUID` = `musers`.`UserUID` 
				LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  
				LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` 
				LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` 
				LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` 
				LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` 
				WHERE `torders`.`StatusUID` NOT IN (".$statuses.") ".$where."  
				GROUP BY torders.`OrderUID` 
				ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC,".$Sort."");
		}

		return $query->result_array();
	}

	function GetOrderByField($UserUID)
	{
		$this->db->distinct();
		$this->db->select ( '*' );
		$this->db->from ( 'mcustomsortcolumns' );
		$this->db->where('mcustomsortcolumns.CustomSortByUserUID',$UserUID);
		$this->db->order_by('mcustomsortcolumns.FieldPosition');
		$query = $this->db->get();
		return $query->row();
	}

	function GetTHeadContent($UserUID)
	{
		$this->db->select ( 'FieldFormName' );
		$this->db->from ( 'mcustomsortcolumns' );
		$this->db->where('mcustomsortcolumns.CustomSortByUserUID',$UserUID);
		$this->db->order_by('mcustomsortcolumns.FieldPosition');
		$query = $this->db->get();
		return $query->result_array();
	}

	function GetCustomTablevalues($UserUID){

		$sql = "SELECT COUNT(1) AS COUNT FROM mcustomsortcolumns WHERE CustomSortByUserUID = $UserUID";
		$query = $this->db->query($sql);
		$res= $query->result();
		return $res;
	}

	function GetCustomFieldNameByUserUID($UserUID){

		$this->db->select ( 'FieldName' );
		$this->db->from ( 'mcustomsortcolumns' );
		$this->db->where('mcustomsortcolumns.CustomSortByUserUID',$UserUID);
		$this->db->order_by('mcustomsortcolumns.FieldPosition');
		$query = $this->db->get();
		return $query->result_array();
	}

	function GetCustomFieldName($UserUID){

		$this->db->select ( '*' );
		$this->db->from ( 'mcustomsortcolumns' );
		$this->db->where('mcustomsortcolumns.CustomSortByUserUID',$UserUID);
		$this->db->order_by('mcustomsortcolumns.FieldPosition');
		$query = $this->db->get();
		return $query->result();
	}

	function Count_get_myorders_by_cust_id($loggedid = '',$CustomerUID)
	{

		$status[0] = $this->config->item('keywords')['Cancelled'];
		$status[1] = $this->config->item('keywords')['Order Completed'];
		$status[2] = $this->config->item('keywords')['Exception Raised'];


		$this->db->select ( 'CustomerName,torders.OrderUID,OrderNumber,StatusName,torders.StatusUID,StatusColor,mproducts.ProductName,mproducts.ProductCode,msubproducts.SubProductCode,msubproducts.SubProductName,PropertyAddress1,PropertyZipcode,LoanNumber,torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName' );
		$this->db->select('DATE_FORMAT(torders.OrderDueDateTime, "%m-%d-%Y %H:%i:%s") as OrderDueDateTime', FALSE);    
		$this->db->select('DATE_FORMAT(OrderEntryDatetime, "%m-%d-%Y %H:%i:%s") as OrderEntryDatetime', FALSE);
		$this->db->select('DATE_FORMAT(OrderCompleteDateTime, "%m-%d-%Y %H:%i:%s") as OrderCompleteDateTime', FALSE);
		$this->db->from ( 'torders' );
		$this->db->join ( 'torderassignment', 'torderassignment.OrderUID = torders.OrderUID','left');
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID','left');
		$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = torders.CustomerUID','left');
		$this->db->join ( 'morderstatus', 'morderstatus.StatusUID = torders.StatusUID','left');
		$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID','left');
		$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID','left');

		$this->db->where_not_in('torders.StatusUID', $status);
		$this->db->where('torders.CustomerUID',$CustomerUID);

		$this->db->group_by('torders.OrderUID');
		$this->db->order_by('OrderUID,OrderNumber', 'DESC');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function filter_get_myorders_by_cust_id($loggedid = '',$CustomerUID, $post='')
	{

		$status[0] = $this->config->item('keywords')['Cancelled'];
		$status[1] = $this->config->item('keywords')['Order Completed'];
		$status[2] = $this->config->item('keywords')['Exception Raised'];

		$this->db->select ( 'CustomerName,torders.OrderUID,OrderNumber,StatusName,torders.StatusUID,StatusColor,mproducts.ProductName,mproducts.ProductCode,msubproducts.SubProductCode,msubproducts.SubProductName,PropertyAddress1,PropertyZipcode,LoanNumber,torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName' );
		$this->db->select('DATE_FORMAT(torders.OrderDueDateTime, "%m-%d-%Y %H:%i:%s") as OrderDueDateTime', FALSE);    
		$this->db->select('DATE_FORMAT(OrderEntryDatetime, "%m-%d-%Y %H:%i:%s") as OrderEntryDatetime', FALSE);
		$this->db->select('DATE_FORMAT(OrderCompleteDateTime, "%m-%d-%Y %H:%i:%s") as OrderCompleteDateTime', FALSE);
		$this->db->from ( 'torders' );
		$this->db->join ( 'torderassignment', 'torderassignment.OrderUID = torders.OrderUID','left');
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID','left');
		$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = torders.CustomerUID','left');
		$this->db->join ( 'morderstatus', 'morderstatus.StatusUID = torders.StatusUID','left');
		$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID','left');
		$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID','left');
		$this->db->where_not_in('torders.StatusUID', $status);
		$this->db->where('torders.CustomerUID',$CustomerUID);

		$this->db->group_by('torders.OrderUID');
		$this->db->order_by('OrderUID,OrderNumber', 'DESC');
		
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

		$query = $this->db->get();
		return $query->num_rows();

	}  

	function get_myorders_by_cust_id($loggedid = '',$CustomerUID, $post='')
	{

		$status[0] = $this->config->item('keywords')['Cancelled'];
		$status[1] = $this->config->item('keywords')['Order Completed'];
		$status[2] = $this->config->item('keywords')['Exception Raised'];


		$this->db->select ( 'CustomerName,torders.OrderUID,OrderNumber,StatusName,torders.StatusUID,StatusColor,mproducts.ProductName,mproducts.ProductCode,msubproducts.SubProductCode,msubproducts.SubProductName,PropertyAddress1,PropertyZipcode,LoanNumber,torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName' );
		$this->db->select('DATE_FORMAT(torders.OrderDueDateTime, "%m-%d-%Y %H:%i:%s") as OrderDueDateTime', FALSE);    
		$this->db->select('DATE_FORMAT(OrderEntryDatetime, "%m-%d-%Y %H:%i:%s") as OrderEntryDatetime', FALSE);
		$this->db->select('DATE_FORMAT(OrderCompleteDateTime, "%m-%d-%Y %H:%i:%s") as OrderCompleteDateTime', FALSE);
		$this->db->from ( 'torders' );
		$this->db->join ( 'torderassignment', 'torderassignment.OrderUID = torders.OrderUID','left');
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID','left');
		$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = torders.CustomerUID','left');
		$this->db->join ( 'morderstatus', 'morderstatus.StatusUID = torders.StatusUID','left');
		$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID','left');
		$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID','left');
		$this->db->where_not_in('torders.StatusUID', $status);
		$this->db->where('torders.CustomerUID',$CustomerUID);

		$this->db->group_by('torders.OrderUID');
		$this->db->order_by('OrderUID,OrderNumber', 'DESC');

		if ($post['length']!='') {
			$this->db->limit($post['length'], $post['start']);
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

		$query = $this->db->get();
		return $query->result_array();

	}


	function Change_order_status($OrderUID,$status,$flag,$UserUID)
	{
		$set_data = array(
			'OrderFlag' => $flag,
			'WorkflowStatus' => $status
		);		

		$where = array(
			'OrderUID'  => $OrderUID,
			'AssignedToUserUID' => $UserUID,
			'WorkflowStatus' => 0,
		);

		$this->db->set($set_data)->where($where)->update('torderassignment');
		if($this->db->affected_rows() > 0)
		{

			return true;
		}
		else
		{
			return false;
		}              

	}

	function RejectAssignedOrder($OrderUID,$UserUID,$VendorUID,$Remarks,$Reason){
		$this->db->trans_begin();
		$set_data = array(
			'AssignedToUserUID' => NULL,
			'AssignedDatetime' => NULL,
			'OrderFlag' => 2,
			'SendToVendor' => 0,
			'VendorUID' => NULL			
		);

		$where = array(
			'OrderUID'  => $OrderUID,
			// 'WorkflowStatus' => 0,
			'VendorUID' => $VendorUID
		);

		$this->db->set($set_data)->where($where)->update('torderassignment');
		$reject_data = array(
			'OrderUID' => $OrderUID,
			'VendorUID' => $VendorUID,
			// 'WorkflowModuleUID' => $filter_workflow,
			'RejectedByUserUID' => $UserUID,
			'RejectedDatetime' =>  Date('Y-m-d H:i:s',strtotime("now")),
			'Remarks' => $Remarks,
			'Reason'=> $Reason
		);

		$this->db->insert('tvendorreject',$reject_data);
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}				
	}

	function get_notes_count($loggedid,$OrderUID)
	{

		$query = $this->db->query("SELECT count(*) AS unread FROM `tordernotes` LEFT JOIN `tordernotifications` ON `tordernotifications`.`NoteUID` = `tordernotes`.`NoteUID` WHERE `tordernotifications`.`ReadStatus` = '0' AND `tordernotifications`.`RecepientUserUID` = '$loggedid' AND `tordernotes`.`SectionUID` != '' AND `tordernotes`.`OrderUID` = '$OrderUID' ");

		$unread =  $query->row();

		$query1 = $this->db->query("SELECT count(*) AS filecount FROM `tordernotes` LEFT JOIN `tordernotifications` ON `tordernotifications`.`NoteUID` = `tordernotes`.`NoteUID` WHERE `tordernotifications`.`ReadStatus` = '0' AND `tordernotifications`.`RecepientUserUID` = '$loggedid' AND `tordernotes`.`SectionUID` != '' AND `tordernotes`.`OrderUID` = '$OrderUID' AND `tordernotes`.`AttachedFile` IS NOT NULL  ");
		$filecount =  $query1->row();

		$unread1 = count($unread) > 0 ? $unread->unread : NULL ;
		$filecount1 = count($filecount) > 0 ? $filecount->filecount : NULL ;
		$result_array = array('unread'=>$unread1,'filecount'=>$filecount1);
		return $result_array;
	}


	function get_Workflowassigned($OrderUID){

		$loggedid = $this->session->userdata('UserUID');
		$this->db->select ( 'Group_concat(WorkflowModuleName) as WorkflowModuleName,WorkflowStatus' );
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID' , 'inner' );
		$this->db->where('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where('torderassignment.OrderUID',$OrderUID);

		$query = $this->db->get();
		$res =  $query->row();

		return $res;
	}


	function get_Workflowassignedbyid($OrderUID){

		$loggedid = $this->session->userdata('UserUID');
		$this->db->select ( 'Group_concat(WorkflowModuleName) as WorkflowModuleName' );
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID' , 'inner' );
		$this->db->where('torderassignment.OrderUID',$OrderUID);

		$query = $this->db->get();
		$res =  $query->row();

		return $res;
	}

	function count_check_order($loggedid,$post)
	{  
		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{
			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];


			$sql = "SELECT `torders`.`OrderUID`, `torderassignment`.`WorkflowModuleUID` FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 GROUP BY `OrderUID`";
		} else {

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];

			$sql = "SELECT  `torders`.`OrderUID`, `torderassignment`.`WorkflowModuleUID` FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") GROUP BY `OrderUID` "; 

		}

		$checkorders = $this->db->query($sql)->result_array();  
		foreach ($checkorders as $key => $value) 
		{

			if(in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6,13)) == False)
			{
				$assigned = $this->Common_Model->get_assigned_workflows($value['OrderUID'],$this->loggedid);
				$completed = $this->Common_Model->get_completed_workflows($value['OrderUID'],$this->loggedid);
				$assigned_orderss = [];
				$completed_orderss = [];
				$assigned_workflows = [];
				$completed_workflows = [];
				foreach ($assigned as $keys => $values) {
					$assigned_orderss[] = $values['WorkflowModuleUID'];
					$assigned_workflows[] = $values['OrderUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_orderss[] = $valuess['WorkflowModuleUID'];
					$completed_workflows[] = $valuess['OrderUID'];
				}
				if($assigned_orderss === array_intersect($assigned_orderss, $completed_orderss) && $completed_orderss === array_intersect($completed_orderss, $assigned_orderss)) {

					if($assigned_workflows === array_intersect($assigned_workflows, $completed_workflows) && $completed_workflows === array_intersect($completed_workflows, $assigned_workflows)) {
						unset($checkorders[$key]);
					} 
				}   
			}
		}

		if(!empty($checkorders)){
			$my_orders = $checkorders; 
		} else {
			$my_orders = array();
		}

		return sizeof($my_orders);
		
	}

	function filter_check_order($loggedid,$post)
	{ 
		if(!empty($post['search_value'])) 
		{
			$like = "AND ";
			foreach ($post['column_search'] as $key => $item) { // loop column 
            // if datatable send POST for search
				if ($key === 0) { // first loop
					$like .= "( ".$item." LIKE '%".$post['search_value']."%' "; 
				} else {
					$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";    
				}
			}
			$like .= ") ";
		} else {
			$like = "";
		}  

		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{
			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];

			$sql = "SELECT `torders`.`OrderUID`, `torderassignment`.`WorkflowModuleUID` FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC";
		}
		else{

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];

			$sql = "SELECT `torders`.`OrderUID`, `torderassignment`.`WorkflowModuleUID` FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$checkorders = $this->db->query($sql)->result_array(); 

		foreach ($checkorders as $key => $value) 
		{
			if(in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6,13)) == False)
			{
				$assigned = $this->Common_Model->get_assigned_workflows($value['OrderUID'],$this->loggedid);
				$completed = $this->Common_Model->get_completed_workflows($value['OrderUID'],$this->loggedid);
				$assigned_orderss = [];
				$completed_orderss = [];
				$assigned_workflows = [];
				$completed_workflows = [];
				foreach ($assigned as $keys => $values) {
					$assigned_orderss[] = $values['WorkflowModuleUID'];
					$assigned_workflows[] = $values['OrderUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_orderss[] = $valuess['WorkflowModuleUID'];
					$completed_workflows[] = $valuess['OrderUID'];
				}
				if($assigned_orderss === array_intersect($assigned_orderss, $completed_orderss) && $completed_orderss === array_intersect($completed_orderss, $assigned_orderss)) {

					if($assigned_workflows === array_intersect($assigned_workflows, $completed_workflows) && $completed_workflows === array_intersect($completed_workflows, $assigned_workflows)) {
						unset($checkorders[$key]);
					} 
				}   
			}
		}

		if(!empty($checkorders)){
			$my_orders = $checkorders; 
		} else {
			$my_orders = array();
		}

		return sizeof($my_orders);
	}        


	function check_order($loggedid,$post)
	{ 

		if ($post['length']!='') {
			$limit = 'LIMIT '.$post['start'].','.$post['length'];
		} else {
			$limit = '';
		}

		if(!empty($post['search_value'])) 
		{
			$like = "AND ";
			foreach ($post['column_search'] as $key => $item) { // loop column 
          // if datatable send POST for search
				if ($key === 0) { // first loop
					$like .= "( ".$item." LIKE '%".$post['search_value']."%' "; 
				} else {
					$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";    
				}
			}
			$like .= ") ";
		} else {
			$like = "";
		}  

		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{
			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`,`LoanNumber`, `OrderNumber`, `StatusName`,`StatusName`,`torders`.`StatusUID`,`torders`.`PropertyZipcode`,`StatusColor`, `torders`.`OrderUID`, `torders`.`OrderEntryDatetime` as OrderEntryDatetime, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime ,TRIM(CONCAT_WS(' ',TRIM(torders.PropertyAddress1),TRIM(torders.PropertyAddress2))) AS whole_name , torders.PropertyStateCode,torders.IsInhouseExternal,torders.PropertyCityName,torders.PropertyCountyName,mabstractor.AbstractorNo,mabstractor.AbstractorCompanyName,mabstractor.AbstractorFirstName,torders.AbstractorFee,torders.CustomerAmount,`mordertypes`.`OrderTypeName`,VendorAssignedDateTime FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mabstractor` ON `mabstractor`.`AbstractorUID` = `torders`.`AbstractorUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` LEFT JOIN `mordertypes` ON `mordertypes`.`OrderTypeUID` = `torders`.`OrderTypeUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC";
		} else {

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`,`LoanNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`,`torders`.`PropertyZipcode`, `torders`.`OrderEntryDatetime` as OrderEntryDatetime, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, TRIM(CONCAT_WS(' ',TRIM(torders.PropertyAddress1),TRIM(torders.PropertyAddress2))) AS whole_name ,torders.PropertyStateCode,torders.PropertyCityName,torders.IsInhouseExternal,torders.PropertyCountyName,mabstractor.AbstractorNo,mabstractor.AbstractorCompanyName,mabstractor.AbstractorFirstName,torders.AbstractorFee,torders.CustomerAmount,mordertypes.OrderTypeName,torders.PropertyZipcode,VendorAssignedDateTime FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mabstractor` ON `mabstractor`.`AbstractorUID` = `torders`.`AbstractorUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` LEFT JOIN `mordertypes` ON `mordertypes`.`OrderTypeUID` = `torders`.`OrderTypeUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/*--- GET LAST VIEWED ORDERS BY LOGGED USER ---*/

	function lastviewed_orders($loggedid)
	{

		$data = $this->db->query("SELECT `OrderUID` FROM `tlastviewedorders` WHERE UserUID = '".$loggedid."' ")->row();


		if(!empty($data)){

			$OrderUIDs = $data->OrderUID;

			if($OrderUIDs != ''){

				$OrderUIDs =  rtrim($OrderUIDs, ',');

				if($this->Common_Model->GetMyOrdersQueue() == 1)
				{

					$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];
					$where = "WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torders`.`OrderUID` IN (".$OrderUIDs.")  GROUP BY `OrderUID` ORDER BY FIELD(torders.OrderUID,".$OrderUIDs."),FIELD(torders.PriorityUID,'3','1') DESC,PriorityUID, `torders`.`OrderEntryDatetime` ASC LIMIT 10";
					
				}else{

					$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];
					$where = "WHERE `torders`.`StatusUID` NOT IN (".$statuses.") AND `torders`.`OrderUID` IN (".$OrderUIDs.")  GROUP BY `OrderUID` ORDER BY FIELD(torders.OrderUID,".$OrderUIDs."),FIELD(torders.PriorityUID,'3','1') DESC,PriorityUID, `torders`.`OrderEntryDatetime` ASC LIMIT 10";
					
				}

				$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`, `torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime FROM (`torders`) LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` ".$where." ";



				$query = $this->db->query($sql);
				return $query->result_array();

			}else{
				return array();
			}

		}else{

			return array();

		}
	}



	function get_orderbyid($OrderUID)
	{
		if($OrderUID){
			$this->db->select ( '*' ); 
			$this->db->select('DATE_FORMAT(OrderEntryDatetime, "%m-%d-%Y") as OrderEntryDatetime', FALSE);
			$this->db->from ( 'torders' );
			$this->db->join ( 'mcustomers', 'torders.CustomerUID = mcustomers.CustomerUID' , 'left' );
			$this->db->join ( 'mordertypes', 'mordertypes.OrderTypeUID = torders.OrderTypeUID' , 'left' );
			$this->db->join ( 'morderpriority', 'morderpriority.PriorityUID = torders.PriorityUID' , 'left' );
			$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID' , 'left' );
			$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID' , 'left' );
			$this->db->where ('torders.OrderUID',$OrderUID);
			$query = $this->db->get();
			return $query->row();
		}

	}

	function customer_workflow($CustomerUID,$SubProductUID){
		$this->db->distinct();
		$this->db->select ('CustomerUID,WorkflowModuleName,mcustomerworkflowmodules.workflowmoduleUID'); 
		$this->db->from ( 'mcustomerworkflowmodules' );
		$this->db->join ( 'mworkflowmodules', 'mcustomerworkflowmodules.workflowmoduleUID = mworkflowmodules.WorkflowModuleUID' , 'left' );
		$this->db->where ('mcustomerworkflowmodules.CustomerUID',$CustomerUID);
		$this->db->where ('mcustomerworkflowmodules.SubProductUID',$SubProductUID);
		$query = $this->db->get();
		$workflowmodules =  $query->result_array();
		return $workflowmodules;
	}

	function is_workflow_assigned($OrderUID,$WorkflowUID){

		$this->db->select ( '*' ); 
		$this->db->from ( 'torderassignment');
		$this->db->where ( 'WorkflowModuleUID',$WorkflowUID);
		$this->db->where('AssignedToUserUID is NOT NULL', NULL, FALSE);
		$this->db->where ( 'OrderUID',$OrderUID);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function assign_selectedorders($orderUID,$loggedid,$customer_workflow,$filter_workflow)
	{
		$status = $this->config->item('keywords')['Order Assigned'];

		$workflows = $this->Common_Model->get_role_workflows();
		$order_details = $this->Common_Model->get_orderdetails($orderUID);

		$this->db->trans_begin();

		foreach ($customer_workflow as $workflow_key => $workflow_value) {
			if((in_array($workflow_value['workflowmoduleUID'], $workflows)) &&  ($workflow_value['workflowmoduleUID'] == $filter_workflow )){
				if(($workflow_value['workflowmoduleUID'] != '4') && ($this->is_workflow_assigned($orderUID,$workflow_value['workflowmoduleUID']) == 0)) {





					$this->db->where('torderassignment.AssignedToUserUID IS NULL');
					$this->db->where('torderassignment.OrderUID',$orderUID);
					$this->db->where('torderassignment.WorkflowModuleUID',$filter_workflow);
					$this->db->where('torderassignment.WorkflowStatus !=',5);
					$q = $this->db->get('torderassignment');


					if ( $q->num_rows() > 0 ) 
					{
						$updatedata = array(
							'AssignedToUserUID' => $loggedid,
							'AssignedByUserUID' => $loggedid,
							'AssignedDatetime' =>  Date('Y-m-d H:i:s',strtotime("now")),
							'WorkflowStatus' => '0',
							'SelfManualAssign'=>'SELF'

						);
						$this->db->where('torderassignment.WorkflowModuleUID',$filter_workflow);
						$this->db->where('torderassignment.OrderUID',$orderUID);
						$inserted = $this->db->update('torderassignment',$updatedata);
					} else {

						$assign_data = array(
							'OrderUID' => $orderUID,
							'WorkflowModuleUID' => $filter_workflow,
							'AssignedToUserUID' => $loggedid,
							'AssignedDatetime' =>  Date('Y-m-d H:i:s',strtotime("now")),
							'AssignedByUserUID' => $loggedid,
							'WorkflowStatus' => '0',
							'SelfManualAssign'=>'SELF'

						);
						$inserted = $this->db->insert('torderassignment',$assign_data);

					}

					if($order_details->StatusUID == $this->config->item('keywords')['New Order'] || $order_details->StatusUID == $this->config->item('keywords')['Reopened Order'] || $order_details->StatusUID == $this->config->item('keywords')['Order Assigned']){
						$orders_data = array(
							'LastTouchDateTime' => Date('Y-m-d H:i:s',strtotime("now")),
							'LastModifiedByUserUID' => $loggedid,
							'StatusUID' => $status,
						);
					}else{
						$orders_data = array(
							'LastTouchDateTime' => Date('Y-m-d H:i:s',strtotime("now")),
							'LastModifiedByUserUID' => $loggedid,
						);
					}
					if(isset($inserted)){
						$this->db->set($orders_data)
						->where('OrderUID', $orderUID)
						->update('torders');
					}

				}

			}
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}

	}


	function get_customer_pendingorders($CustomerUID)
	{
  // $Customers = $this->Common_Model->GetCustomerDetails();

		$status = $this->config->item('keywords')['New Order'];

		$this->db->select('*');

		$this->db->from ( 'torders' );
		$this->db->join ( 'mcustomers', 'torders.CustomerUID = mcustomers.CustomerUID');

		$this->db->where("torders.OrderUID NOT IN (SELECT DISTINCT `torderassignment`.`OrderUID` FROM `torderassignment`)",NULL, false);

		$this->db->where ( 'torders.CustomerUID',$CustomerUID);

		$this->db->where ("torders.StatusUID",$status);

		$query = $this->db->get();

		return $query->num_rows();


	}


	function customer_workflowby_Cusid($CustomerUID){


		$this->db->distinct();
		$this->db->select ('CustomerUID,WorkflowModuleName,mcustomerworkflowmodules.workflowmoduleUID'); 
		$this->db->from ( 'mcustomerworkflowmodules' );
		$this->db->join ( 'mworkflowmodules', 'mcustomerworkflowmodules.workflowmoduleUID = mworkflowmodules.WorkflowModuleUID' , 'left' );
		$this->db->where ('mcustomerworkflowmodules.CustomerUID',$CustomerUID);
      // $this->db->where ('mcustomerworkflowmodules.SubProductUID',$SubProductUID);
		$query = $this->db->get();


		$workflowmodules =  $query->result();
		return $workflowmodules;
	}


	function Get_pending_orders($loggedid)
	{

		$group_id = $this->db->query("SELECT GROUP_CONCAT(GroupUID SEPARATOR ',') as group_id FROM mgroupusers where GroupUserUID = $loggedid")->row(); 


		if(!empty($group_id->group_id))
		{



			$groupIDs = $group_id->group_id; 

			$cust_id = $this->db->query("SELECT GROUP_CONCAT(DISTINCT GroupCustomerUID SEPARATOR ',') as cust_id FROM mgroupcustomers where GroupUID IN ($groupIDs)")->row();

			if($cust_id->cust_id != '')
			{
				$customer_id = $cust_id->cust_id;

				$status = $this->config->item('keywords')['New Order'];

				$this->db->select ( "*"); 
				$this->db->select("DATE_FORMAT(OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime", FALSE);
				$this->db->from ( "torders" );
				$this->db->join ( "mcustomers", "torders.CustomerUID = mcustomers.CustomerUID");
				$this->db->where("torders.CustomerUID IN (".$customer_id.")",NULL, false);
				$this->db->where ("torders.StatusUID",$status);
				$query = $this->db->get();

				return $query->result_array();

			}else{
				return array();
			}
		}else{
			return array();
		}

	}


	function GetCustomersbyOrders($loggedid){
		$group_id = $this->db->query("SELECT GROUP_CONCAT(GroupUID SEPARATOR ',') as group_id FROM mgroupusers where GroupUserUID = $loggedid")->row(); 
    // echo '<pre>';print_r($group_id->group_id);exit;

		if(!empty($group_id->group_id))
		{

			$groupIDs = $group_id->group_id; 

			$cust_id = $this->db->query("SELECT GROUP_CONCAT(DISTINCT GroupCustomerUID SEPARATOR ',') as cust_id FROM mgroupcustomers where GroupUID IN ($groupIDs)")->row();

			if($cust_id->cust_id != '')
			{
				$customer_id = $cust_id->cust_id;

				$status = $this->config->item('keywords')['New Order'];

				$this->db->distinct(); 
				$this->db->select ("CustomerUID,CustomerName"); 
				$this->db->from ( "mcustomers" );
				$this->db->where("mcustomers.CustomerUID IN (".$customer_id.")",NULL, false);
				$query = $this->db->get();

				return $query->result_array();

			}else{
				return array();
			}
		}else{

			return array();
		}
	}

	function cancel_order($orderUID,$Remarks,$loggedid,$CancellationRequestDateTime)
	{
		$ApprovalFunction = 'Order Cancellation';
		$ApprovalStatus = '0';
		$query = $this->db->query('INSERT INTO  torderapprovals(OrderUID,ApprovalFunction,RaisedByUserUID,RaisedDatetime,ApprovalStatus,Remark)VALUES
			("'.$orderUID.'","'.$ApprovalFunction.'","'.$loggedid.'","'.$CancellationRequestDateTime.'","'.$ApprovalStatus.'","'.$Remarks.'")');
		if($this->db->affected_rows() > 0)
		{
			$this->db->query('INSERT INTO tordercancel(OrderUID,Remarks,RequestedBy,CancelStatus,CancellationRequestTime)VALUES("'.$orderUID.'","'.$Remarks.'","'.$loggedid.'",0,"'.$CancellationRequestDateTime.'")');

			$data1['ModuleName']='Order Cancel Requst Status-update';
			$data1['IpAddreess']=$_SERVER['REMOTE_ADDR']; 
			$data1['DateTime']=date('y-m-d H:i:s');
			$data1['TableName']='tordercancel';
			$data1['OrderUID']=$orderUID;
			$data1['UserUID']=$this->session->userdata('UserUID'); 
			$data1['OldValue']=''; 
			$data1['FieldUID']='1138';
			$data1['NewValue']='110';                 
			$this->Common_Model->Audittrail_insert($data1); 
			return true;
		}
		else
		{
			return false;
		}
	}

	function approve_cancel_order($orderUID,$Remarks,$loggedid,$CancellationRequestDateTime)
	{

		$this->load->model('Real_EC_Model');
		$query = $this->db->query('INSERT INTO tordercancel(OrderUID,Remarks,RequestedBy,ApprovedBy,CancelStatus,CancellationRequestTime,CancellationApproveDeclineTime)VALUES("'.$orderUID.'","'.$Remarks.'","'.$loggedid.'","'.$loggedid.'",1,"'.$CancellationRequestDateTime.'","'.$CancellationRequestDateTime.'")');
		if($this->db->affected_rows() > 0)
		{
			$this->db->set('StatusUID','110')->where('OrderUID',$orderUID)->update('torders');
			$this->Real_EC_Model->CancelApiOrder($orderUID,$Remarks);

			$data1['ModuleName']='OrderStatus-update';
			$data1['IpAddreess']=$_SERVER['REMOTE_ADDR']; 
			$data1['DateTime']=date('y-m-d H:i:s');
			$data1['TableName']='tordercancel';
			$data1['OrderUID']=$orderUID;
			$data1['UserUID']=$this->session->userdata('UserUID'); 
			$data1['OldValue']=''; 
			$data1['FieldUID']='1138';
			$data1['NewValue']='110';                 
			$this->Common_Model->Audittrail_insert($data1); 
			return true;
		}
		else
		{
			return false;
		}
	}

	function getCancelOrderStatus($OrderUID)
	{
		$query = $this->db->query("SELECT OrderUID FROM tordercancel WHERE CancelStatus = '1' and OrderUID = '".$OrderUID."' ");
		$res = $query->row_array();
		return $res;

	}

	function getSearchCompleteStatus($orderUID)
	{
		$query = $this->db->query("SELECT WorkflowStatus FROM `torderassignment` WHERE WorkflowModuleUID = 1 and WorkflowStatus = 5 and OrderUID = $orderUID ");
		if($query->num_rows() > 0)
		{
			return true;
		}
		else{
			return false;
		}
	}

	function CheckCancelOrderExist($orderUID)
	{
		$query = $this->db->query("SELECT OrderUID FROM `torderapprovals` WHERE OrderUID = $orderUID and ApprovalStatus = '0' and ApprovalFunction = 'Order Cancellation' ");
		if($query->num_rows() > 0)
		{
			return true;
		}
		else{
			return false;
		}
	}

	function get_onhold_orders($loggedid)
	{
		$status[0] = $this->config->item('keywords')['Order Assigned'];
		$status[1] = $this->config->item('keywords')['Order Work In Progress'];
		$status[2] = $this->config->item('keywords')['Partial Draft Complete'];
		$status[3] = $this->config->item('keywords')['Partial Review Complete'];
		$status[4] = $this->config->item('keywords')['Review In Progress'];
		$status[5] = $this->config->item('keywords')['Reopened Order'];
    //$status[2] = $this->config->item('keywords')['New Order'];

		$this->db->select ( 'torderassignment.OrderUID,OrderAssignmentUID' );
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'torders', 'torderassignment.OrderUID = torders.OrderUID');

		$this->db->where_in('torders.StatusUID', $status);
		$this->db->where('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where('torderassignment.WorkflowStatus',4);
		$this->db->where('torderassignment.WorkflowModuleUID !=',4);
		$this->db->group_by('torders.OrderUID');
		$query = $this->db->get();
		return $query->result();
	}

	function check_pending_orders($OnholdUIDs,$loggedid)
	{

		$status[0] = $this->config->item('keywords')['Order Assigned'];
		$status[1] = $this->config->item('keywords')['Reopened Order'];
		$status[2] = $this->config->item('keywords')['Order Work In Progress'];
		$status[3] = $this->config->item('keywords')['Partial Draft Complete'];
		$status[4] = $this->config->item('keywords')['Partial Review Complete'];
		$status[5] = $this->config->item('keywords')['Review In Progress'];
    //$status[2] = $this->config->item('keywords')['New Order'];

		$this->db->select ( '*' );
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'torders', 'torderassignment.OrderUID = torders.OrderUID');

		$this->db->where_in('torders.StatusUID', $status);

		if(!empty($OnholdUIDs)){
			$this->db->where_not_in('torders.OrderUID', $OnholdUIDs);
		}
		$this->db->where('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where('torderassignment.WorkflowStatus !=',4);
		$this->db->where('torderassignment.WorkflowStatus !=',5);
		$this->db->group_by('torders.OrderUID');
		$query = $this->db->get();
		return $query->result();
	}

	function getnext_order_all($postarray){

		$filter_workflow = $postarray['filter_workflow'];
		$GroupUID = $postarray['GroupUID'];
		$SubProductUID = $postarray['SubProductUID'];

		$status1 = $this->config->item('keywords')['New Order'];
		$status2 = $this->config->item('keywords')['Order Assigned'];
		$status3 = $this->config->item('keywords')['Order Work In Progress'];
		$status4 = $this->config->item('keywords')['Partial Draft Complete'];
		$status5 = $this->config->item('keywords')['Partial Review Complete'];
		$status6 = $this->config->item('keywords')['Review In Progress'];
		$status7 = $this->config->item('keywords')['Reopened Order'];


		$subproduct_where  = '';
		if($SubProductUID != ''){
			$subproduct_where  .= "AND mgroupcustomers.GroupCustomerSubProductUID = '".$SubProductUID."' ";
		}else{
			$customer_ids = $this->get_customeringroup($GroupUID);
			$cus_subproducts = [];
			if(!empty($customer_ids)){

				$cus_subproducts = $this->get_customerproductandsubproduct($customer_ids);
			}
			

			if(!empty($cus_subproducts)){
				if($cus_subproducts->SubProductUIDs !='' ){
					$subproduct_where .= 'AND mgroupcustomers.GroupCustomerSubProductUID IN ('.$cus_subproducts->SubProductUIDs.')';
				}
			}
			
		}

		$check_workflow_permissions = $this->check_workflow_permissions($filter_workflow);

		$where  = '';
		if(!empty($check_workflow_permissions)){

			if($check_workflow_permissions->DependentWorkflowModule != ''){

				$where .= "AND `torders`.`OrderUID` IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID`  = $check_workflow_permissions->DependentWorkflowModule AND WorkflowStatus  = 5)";
			}
		}

		$this->db->select('GROUP_CONCAT(DISTINCT `OrderUID`) as  OrderUID '); 
		$this->db->where('torderunassignment.AssignedToUserUID',$this->session->userdata('UserUID'));
		$q = $this->db->get('torderunassignment');
		$unassigned = $q->row();

		if ( $unassigned->OrderUID != '' ) 
		{
			$where .= "AND torders.OrderUID NOT IN ($unassigned->OrderUID)";
		}

		/*--- INHOUSE ORDERS FOR SEARCH ----*/
		if($filter_workflow == '1' ){
			$where .= "AND torders.IsInhouseExternal = '0' ";
		}


		$query = $this->db->query("SELECT `torders`.`OrderUID`, `OrderNumber`, `torders`.`CustomerUID`, `CustomerName`, `OrderTypeName`, `StateName`, `PriorityName`, `torders`.`SubProductUID`, `msubproducts`.`SubProductName`, `mproducts`.`ProductUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `mstates`.`StateCode`, `morderstatus`.`StatusName`, DATE_FORMAT(OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, DATE_FORMAT(`torders`.`OrderDueDateTime`, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime FROM (`torders`) LEFT JOIN `mstates` ON `PropertyStateUID` = `mstates`.`StateUID` JOIN `mcustomers` ON `torders`.`CustomerUID` = `mcustomers`.`CustomerUID` JOIN `mordertypes` ON `mordertypes`.`OrderTypeUID` = `torders`.`OrderTypeUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `torderassignment` ON  `torderassignment`.`OrderUID` = `torders`.`OrderUID` LEFT JOIN `mgroupcustomers` ON  `mgroupcustomers`.`GroupCustomerSubProductUID` = `torders`.`SubProductUID` WHERE `torders`.`OrderUID` NOT IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID` ='".$filter_workflow."' AND AssignedToUserUID IS NOT NULL) AND torders.OrderUID NOT IN (SELECT torderabstractor.OrderUID FROM torderabstractor LEFT JOIN torderassignment ON torderassignment.OrderUID = torderabstractor.OrderUID WHERE DocumentReceived = 0 AND (torderassignment.WorkflowModuleUID = 1 AND torderassignment.WorkflowStatus != 5)) AND torders.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID  AND mgroupcustomers.GroupUID = '".$GroupUID."' ".$subproduct_where." AND `torders`.`StatusUID` IN ('".$status1."', '".$status2."', '".$status3."','".$status4."','".$status5."','".$status6."') ".$where."  ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC");

		$result =  $query->result();

		if(!empty($result)){

			return array("message"=>"Order Assigned","status"=>"success","data"=>$result[0],'Error'=>'0');

		}else{

			return array("message"=>"No Orders","status"=>"danger","data"=>"",'Error'=>'1');

		}
	}


	function get_assigned_orders($loggedid)
	{
		$status[0] = $this->config->item('keywords')['Order Assigned'];
		$status[1] = $this->config->item('keywords')['Order Work In Progress'];
		$status[2] = $this->config->item('keywords')['Partial Draft Complete'];
		$status[3] = $this->config->item('keywords')['Partial Review Complete'];
		$status[4] = $this->config->item('keywords')['Review In Progress'];
		$status[5] = $this->config->item('keywords')['Reopened Order'];

		$this->db->select ( 'torders.OrderUID' ); 
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'torders', 'torderassignment.OrderUID = torders.OrderUID');
		$this->db->where ('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where_in('torders.StatusUID', $status);
		$this->db->where ('torderassignment.WorkflowModuleUID !=',4);
		$this->db->where ('torderassignment.WorkflowStatus !=',5);
		$this->db->group_by('torderassignment.OrderUID');
		$query = $this->db->get();
		return $query->result();
	}  

	function check_workflow_completed($OrderUID,$WorkflowUID,$loggedid)
	{

		$this->db->select ( 'torderassignment.OrderUID' ); 
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'torders', 'torderassignment.OrderUID = torders.OrderUID');
      //$this->db->where ('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where ('torderassignment.WorkflowModuleUID',$WorkflowUID);
		$this->db->where ('torderassignment.WorkflowStatus',5);
		$this->db->where ('torderassignment.OrderUID',$OrderUID);
		$this->db->group_by('torderassignment.OrderUID');
		$query = $this->db->get();
		return $query->result();
	}


	function check_searchdocument($OrderUID){
		$this->db->select ( '*' ); 
		$this->db->from ( 'torderdocuments' );
		$this->db->where ('OrderUID',$OrderUID);
		$this->db->where ('IsReport',0);
		$query = $this->db->get();
		return $query->num_rows();
	}


	function get_next_order($loggedid,$postarray){

		$filter_workflow = $postarray['filter_workflow'];
		$GroupUID = $postarray['GroupUID'];


		$SubProductUID = $postarray['SubProductUID'];

		$subproduct_where  = '';
		if($SubProductUID != ''){
			$subproduct_where  .= "AND mgroupcustomers.GroupCustomerSubProductUID = '".$SubProductUID."' ";
		}else{
			$customer_ids = $this->get_customeringroup($GroupUID);
			$cus_subproducts = [];
			if(!empty($customer_ids)){

				$cus_subproducts = $this->get_customerproductandsubproduct($customer_ids);
			}
			

			if(!empty($cus_subproducts)){
				if($cus_subproducts->SubProductUIDs !='' ){
					$subproduct_where .= 'AND mgroupcustomers.GroupCustomerSubProductUID IN ('.$cus_subproducts->SubProductUIDs.')';
				}
			}
			
		}



		/*checking workflow permissions ---dependent workflow module*/
		$check_workflow_permissions = $this->check_workflow_permissions($filter_workflow);
		$where  = '';
		if(!empty($check_workflow_permissions)){

			if($check_workflow_permissions->DependentWorkflowModule != ''){

				$where .= "AND `torders`.`OrderUID` IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID`  = $check_workflow_permissions->DependentWorkflowModule AND WorkflowStatus  = 5)";
			}
		}

		$group_id = $this->db->query("SELECT GROUP_CONCAT(GroupUID SEPARATOR ',') as group_id FROM mgroupusers where GroupUserUID = $loggedid")->row(); 
		if(!empty($group_id->group_id))
		{
			


			$this->db->select('GROUP_CONCAT(DISTINCT `OrderUID`) as  OrderUID '); 
			$this->db->where('torderunassignment.AssignedToUserUID',$this->session->userdata('UserUID'));
			$q = $this->db->get('torderunassignment');
			$unassigned = $q->row();

			if ( $unassigned->OrderUID != '' ) 
			{

				$where .= "AND torders.OrderUID NOT IN ($unassigned->OrderUID)";

			}

			$groupIDs = $group_id->group_id; 

			$cust_id = $this->db->query("SELECT GROUP_CONCAT(DISTINCT GroupCustomerUID SEPARATOR ',') as cust_id FROM mgroupcustomers where GroupUID IN ($groupIDs)")->row();

			if($cust_id->cust_id != '')
			{
				$customer_id = $cust_id->cust_id;

				$status1 = $this->config->item('keywords')['New Order'];
				$status2 = $this->config->item('keywords')['Order Assigned'];
				$status3 = $this->config->item('keywords')['Order Work In Progress'];
				$status4 = $this->config->item('keywords')['Partial Draft Complete'];
				$status5 = $this->config->item('keywords')['Partial Review Complete'];
				$status6 = $this->config->item('keywords')['Review In Progress'];
				$status7 = $this->config->item('keywords')['Reopened Order'];


				/*--- INHOUSE ORDERS FOR SEARCH ----*/
				if($filter_workflow == '1' ){
					$where .= "AND torders.IsInhouseExternal = '0' ";
				}

				$query = $this->db->query("SELECT `torders`.`OrderUID`, `OrderNumber`, `torders`.`CustomerUID`, `CustomerName`, `OrderTypeName`, `PriorityName`, `torders`.`SubProductUID`, `msubproducts`.`SubProductName`, `mproducts`.`ProductUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `morderstatus`.`StatusName`, DATE_FORMAT(OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, DATE_FORMAT(`torders`.`OrderDueDateTime`, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName FROM (`torders`)  JOIN `mcustomers` ON `torders`.`CustomerUID` = `mcustomers`.`CustomerUID` JOIN `mordertypes` ON `mordertypes`.`OrderTypeUID` = `torders`.`OrderTypeUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `torderassignment` ON  `torderassignment`.`OrderUID` = `torders`.`OrderUID` JOIN `mgroupcustomers` ON `mgroupcustomers`.`GroupCustomerUID` = `torders`.`CustomerUID` WHERE `torders`.`CustomerUID` IN ($customer_id) AND `torders`.`OrderUID` NOT IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID` ='".$filter_workflow."' AND AssignedToUserUID IS NOT NULL ) 
					AND `torders`.`OrderUID` NOT IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID` ='".$filter_workflow."' AND SendToVendor = '1') AND torders.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID AND mgroupcustomers.GroupUID = '".$GroupUID."'  ".$subproduct_where." AND `torders`.`StatusUID` IN ('".$status1."', '".$status2."', '".$status3."','".$status4."','".$status5."','".$status6."','".$status7."') ".$where." AND torders.OrderUID NOT IN (SELECT torderabstractor.OrderUID FROM torderabstractor LEFT JOIN torderassignment ON torderassignment.OrderUID = torderabstractor.OrderUID WHERE DocumentReceived = 0 AND (torderassignment.WorkflowModuleUID = 1 AND torderassignment.WorkflowStatus != 5))  GROUP BY `torders`.`OrderUID`  ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC");

				$Orders =  $query->result();
				if(!empty($Orders)){

					return array("message"=>"Order Assigned","status"=>"success","data"=>$Orders[0],'Error'=>'0');

				}else{

					return array("message"=>"No Orders","status"=>"danger","data"=>"",'Error'=>'1');

				}


			}else{
				return array("message"=>"No customer in group","status"=>"danger","data"=>"",'Error'=>'1');

			}



		} else {
			return array("message"=>"User Not in Group","status"=>"danger","data"=>"",'Error'=>'1');

		}



	}


	function get_assigned_users($OrderUID,$loggedid)
	{

		$this->db->select ( 'Group_concat(LoginID) as LoginID' ); 
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'musers', 'musers.UserUID = torderassignment.AssignedToUserUID');
    //$this->db->where ('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where ('torderassignment.WorkflowModuleUID !=',4);
		$this->db->where ('torderassignment.OrderUID',$OrderUID);
		$query = $this->db->get();
		return $query->row();
	}

	function check_workflow_permissions($WorkflowModuleUID){
		$this->db->select ( "a.WorkflowModuleUID,a.WorkflowModuleName,a.CanIndependentWorkflowModule,a.DependentWorkflowModule,b.WorkflowModuleName as DependentWorkflowModulename" ); 
		$this->db->from ( "mworkflowmodules as a" );
		$this->db->join ( 'mworkflowmodules as b', 'b.workflowmoduleUID = a.DependentWorkflowModule' , 'left' );
		$this->db->where ("a.WorkflowModuleUID",$WorkflowModuleUID);
		$query = $this->db->get();
		return $query->row();

	}

	function get_groupsby_loggedid(){

		$where = '';
		$loggedid = $this->session->userdata('UserUID');
		if (in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6)) == FALSE){
			$where = 'AND GroupUserUID = '.$loggedid.'';
		}

		$query = $this->db->query("SELECT DISTINCT mgroups.GroupUID,GroupName FROM mgroups LEFT JOIN mgroupusers on mgroupusers.GroupUID = mgroups.GroupUID LEFT JOIN mgroupcustomers ON mgroupcustomers.GroupUID = mgroups.GroupUID WHERE mgroups.Active=1 and GroupType = 'C' ".$where." ");
		$result =  $query->result();
		return $result;


	} 


	function get_prod_by_groupuid($GroupUID){
		$query = $this->db->query("SELECT mgroupcustomers.GroupCustomerProductUID as ProductUID,mgroupcustomers.GroupCustomerSubProductUID As SubProductUID,ProductName,SubProductName FROM mgroups LEFT JOIN mgroupcustomers ON mgroupcustomers.GroupUID = mgroups.GroupUID  LEFT JOIN msubproducts on msubproducts.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID LEFT JOIN mproducts on mproducts.ProductUID = mgroupcustomers.GroupCustomerProductUID WHERE mgroups.GroupUID = $GroupUID  Group by mgroupcustomers.GroupCustomerProductUID");
		return $query->result();
	}

	function get_subprod_bygroup_product($GroupUID,$ProductUID){
		$query = $this->db->query("SELECT mgroupcustomers.GroupCustomerProductUID as ProductUID,mgroupcustomers.GroupCustomerSubProductUID as SubProductUID,ProductName,SubProductName FROM mgroups LEFT JOIN mgroupcustomers ON mgroupcustomers.GroupUID = mgroups.GroupUID  LEFT JOIN msubproducts on msubproducts.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID LEFT JOIN mproducts on mproducts.ProductUID = mgroupcustomers.GroupCustomerProductUID WHERE mgroups.GroupUID = $GroupUID AND mgroupcustomers.GroupCustomerProductUID = $ProductUID GROUP BY msubproducts.SubProductUID");
		$SubProducts =  $query->result();



		$workflowroles = $this->Common_Model->getrole_workflows();
		$where = '';
		if($workflowroles != ''){
			$where .= 'AND mcustomerworkflowmodules.WorkflowModuleUID IN ("'.$workflowroles.'")';
		}

		$query1 = $this->db->query("SELECT mworkflowmodules.WorkflowModuleUID,mworkflowmodules.WorkflowModuleName FROM mgroupcustomers LEFT JOIN mcustomerworkflowmodules ON mcustomerworkflowmodules.ProductUID = mgroupcustomers.GroupCustomerProductUID LEFT JOIN mworkflowmodules ON  mworkflowmodules.WorkflowModuleUID = mcustomerworkflowmodules.WorkflowModuleUID  WHERE mgroupcustomers.GroupUID = $GroupUID AND mgroupcustomers.GroupCustomerProductUID = $ProductUID AND mgroupcustomers.GroupCustomerUID = mcustomerworkflowmodules.CustomerUID AND mworkflowmodules.WorkflowModuleUID !=4 ".$where." GROUP BY mcustomerworkflowmodules.workflowModuleUID");
		$workflows =  $query1->result();
		return array($SubProducts,$workflows);
	}


	function get_workflowbygroups($GroupUID,$ProductUID,$SubProductUID){

		$workflowroles = $this->Common_Model->getrole_workflows();

		$where = '';
		if($workflowroles != ''){
			$where .= 'AND mcustomerworkflowmodules.WorkflowModuleUID IN ("'.$workflowroles.'")';

			$query = $this->db->query("SELECT mworkflowmodules.WorkflowModuleUID,WorkflowModuleName FROM mgroupcustomers LEFT JOIN mcustomerworkflowmodules ON mcustomerworkflowmodules.CustomerUID = mgroupcustomers.GroupCustomerUID LEFT JOIN mproducts on mproducts.ProductUID = mgroupcustomers.GroupCustomerProductUID LEFT JOIN msubproducts on msubproducts.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID JOIN mworkflowmodules ON mworkflowmodules.WorkflowModuleUID = mcustomerworkflowmodules.WorkflowModuleUID WHERE mcustomerworkflowmodules.ProductUID = $ProductUID AND mcustomerworkflowmodules.SubProductUID = $SubProductUID AND mcustomerworkflowmodules.WorkflowModuleUID !=4 AND mgroupcustomers.GroupUID = $GroupUID ".$where." GROUP BY mcustomerworkflowmodules.WorkflowModuleUID");
			return $query->result();
		}

		return array();

	}

	function get_Workflowassignedseperation($OrderUID){

		$loggedid = $this->session->userdata('UserUID');
		$this->db->select ( 'torderassignment.WorkflowModuleUID,WorkflowModuleName,WorkflowStatus' );
		$this->db->from ( 'torderassignment' );
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID' , 'inner' );
		$this->db->where('torderassignment.AssignedToUserUID',$loggedid);
		$this->db->where('torderassignment.OrderUID',$OrderUID);

		$query = $this->db->get();
		$res =  $query->result();

		return $res;
	}


	function get_all_Workflows(){

		$query = $this->db->get('mworkflowmodules');
		return $query->result_array();
	}



	function get_order_assigned_users($data,$Workflows,$is_vendor_login)
	{

		$ret_data = [];

		foreach ($Workflows as $key => $Workflow) {

			$query=$this->db->query("SELECT OrderUID,WorkflowModuleUID,LoginID,SendToVendor,torderassignment.VendorUID as  AssignedVendorUID,VendorName,musers.VendorUID as VendorUID FROM `torderassignment` LEFT JOIN musers on musers.UserUID = torderassignment.AssignedToUserUID LEFT JOIN mvendors ON mvendors.VendorUID = torderassignment.VendorUID WHERE `OrderUID` = '".$data['OrderUID']."' AND (AssignedToUserUID IS NOT NULL OR SendToVendor = '1') AND `WorkflowModuleUID` = '".$Workflow['WorkflowModuleUID']."' ");
			$result = $query->row();




			if(!empty($result)){

				$ret_data = '--';
				if($is_vendor_login){
					
					
					if($result->SendToVendor == '0' && ($result->VendorUID == ''|| $result->VendorUID == '0') ){
						$ret_data = '--';
					}else if( $result->AssignedVendorUID == $result->VendorUID){	

						$ret_data = $result->LoginID;
					}


				}else{	
					if($result->SendToVendor == '1' && $result->AssignedVendorUID != ''){

						$ret_data = strtok($result->VendorName, ' ');
					}else{
						
						$ret_data = $result->LoginID;
					}
				}

			}else{
				$ret_data = '--';
			}

			$ret[] =  $ret_data;

		}

		return  implode(" / ",$ret);

	}

	function export_option($loggedid)
	{
		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{
			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'].','.$this->config->item('keywords')['Reopened Order'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `torders`.`OrderEntryDatetime` as OrderEntryDatetime, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime , torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC";
		} else {

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'].','.$this->config->item('keywords')['Exception Raised'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `torders`.`OrderEntryDatetime` as OrderEntryDatetime, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}


	function getexcel_myorders_by_cust_id($loggedid = '',$CustomerUID)
	{

		$status[0] = $this->config->item('keywords')['Cancelled'];
		$status[1] = $this->config->item('keywords')['Order Completed'];
		$status[2] = $this->config->item('keywords')['Exception Raised'];


		$this->db->select ( 'CustomerName,torders.OrderUID,OrderNumber,StatusName,torders.StatusUID,StatusColor,mproducts.ProductName,mproducts.ProductCode,msubproducts.SubProductCode,msubproducts.SubProductName,PropertyAddress1,PropertyZipcode,LoanNumber,torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName' );
		$this->db->select('DATE_FORMAT(torders.OrderDueDateTime, "%m-%d-%Y %H:%i:%s") as OrderDueDateTime', FALSE);    
		$this->db->select('DATE_FORMAT(OrderEntryDatetime, "%m-%d-%Y %H:%i:%s") as OrderEntryDatetime', FALSE);
		$this->db->select('DATE_FORMAT(OrderCompleteDateTime, "%m-%d-%Y %H:%i:%s") as OrderCompleteDateTime', FALSE);
		$this->db->from ( 'torders' );
		$this->db->join ( 'torderassignment', 'torderassignment.OrderUID = torders.OrderUID','left');
		$this->db->join ( 'mworkflowmodules', 'mworkflowmodules.WorkflowModuleUID = torderassignment.WorkflowModuleUID','left');
		$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = torders.CustomerUID','left');
		$this->db->join ( 'morderstatus', 'morderstatus.StatusUID = torders.StatusUID','left');
		$this->db->join ( 'msubproducts', 'msubproducts.SubProductUID = torders.SubProductUID','left');
		$this->db->join ( 'mproducts', 'mproducts.ProductUID = msubproducts.ProductUID','left');
		$this->db->where_not_in('torders.StatusUID', $status);
		$this->db->where('torders.CustomerUID',$CustomerUID);

		$this->db->group_by('torders.OrderUID');
		$this->db->order_by('OrderUID,OrderNumber', 'DESC');
		$query = $this->db->get();
		return $query->result_array();


	}

	/*for vendors*/

	function get_vendors($logged_details,$loggedid = ''){


		if($loggedid && isset($logged_details->VendorUID)){


			if($logged_details->VendorUID != ''){

				$this->db->select ( 'VendorUID,VendorName' ); 
				$this->db->from ( 'mvendors');
				$this->db->where(array('VendorUID'=>$logged_details->VendorUID,'Active'=>1));
				$query = $this->db->get();
				return $query->result();
			}else{
				return (object)[];
			}
		}else{
			$this->db->select ( 'VendorUID,VendorName' ); 
			$this->db->from ( 'mvendors');
			$this->db->where('Active',1);
			$query = $this->db->get();
			return $query->result();
		}

		return (object)[];

	}


	function get_vendor_uids($vendors){

		$VendorUIDS = [];
		foreach ($vendors as $key => $vendor) {
			$VendorUIDS[] = $vendor->VendorUID;
		}
		$VendorUIDS = implode(',', $VendorUIDS);
		return $VendorUIDS;
	}



	function get_vendor_groups($is_vendor_login,$VendorUIDS,$loggedid){

		if($is_vendor_login){


			if($VendorUIDS != ''){
				if (in_array($this->session->userdata('RoleType'),array('13'))) {
					/*vendor Supervisor*/


					$query  = $this->db->query("SELECT mgroupvendors.GroupUID,GroupName FROM mgroupvendors LEFT JOIN mgroups ON mgroups.GroupUID =  mgroupvendors.GroupUID WHERE GroupType = 'V' AND mgroups.Active = 1 AND VendorUID IN (".$VendorUIDS.")   ");

					return $query->result();
				}elseif(in_array($this->session->userdata('RoleType'),array('14'))){
					/*Vendor Agent */

					$query  =$this->db->query("SELECT DISTINCT `mgroups`.`GroupUID`, `mgroups`.`GroupName` FROM (`mgroupusers`) JOIN `musers` ON `musers`.`UserUID` = `mgroupusers`.`GroupUserUID` LEFT JOIN `mgroups` ON `mgroupusers`.`GroupUID` = `mgroups`.`GroupUID` WHERE `mgroupusers`.`GroupUserUID` = '".$loggedid."' AND `mgroups`.`Active` = 1 AND GroupType = 'V' GROUP BY `mgroupusers`.`GroupUID` ORDER BY `mgroups`.`GroupName` ");

					return $query->result();

				}
			}
			return (object)[];

		}else{


			$query = $this->db->query("SELECT GroupUID,GroupName FROM mgroups WHERE GroupType = 'V' AND mgroups.Active = 1 ");

			return $query->result();
		}

		return (object)[];


	}


	function get_vendor_users($is_vendor_login,$loggedid,$GroupUID,$vendors){
		if($is_vendor_login){

			if(!empty($vendors)){
				$query = $this->db->query("SELECT * FROM musers JOIN mgroupvendors ON mgroupvendors.VendorUID = musers.VendorUID LEFT JOIN mroles on mroles.RoleUID = musers.RoleUID WHERE musers.Active = 1 AND mgroupvendors.VendorUID = '".$vendors[0]->VendorUID."' GROUP BY musers.UserUID");

				return  $query->result();
			}else{
				return (object)[];
			}
		}else{
			$query = $this->db->query("SELECT mgroupvendors.VendorUID,VendorName,OrderSearch,OrderTyping,OrderTaxCert,OrderReview FROM mgroupvendors LEFT JOIN mvendors on mvendors.VendorUID = mgroupvendors.VendorUID LEFT JOIN musers ON musers.VendorUID = mgroupvendors.VendorUID LEFT JOIN mroles ON mroles.RoleUID = musers.RoleUID  WHERE mgroupvendors.GroupUID = '".$GroupUID."' GROUP BY mvendors.VendorUID ");
			return  $query->result();
		}
	}


	function get_customer_ingroup_by_vendors($groupids)
	{

		if(!empty($groupids)){
			$this->db->distinct();
			$this->db->select ( 'CustomerName,CustomerUID' ); 
			$this->db->from ( 'mgroupcustomers' );
			$this->db->join ( 'mcustomers', 'mcustomers.CustomerUID = mgroupcustomers.GroupCustomerUID');
			$this->db->where_in ('mgroupcustomers.GroupUID',$groupids);
			$this->db->group_by('mgroupcustomers.GroupCustomerUID');
			$this->db->order_by('mcustomers.CustomerName');
			$query = $this->db->get();
			return $query->result();
		}else{
			return (object)[];
		}
	}

	function get_customeruid_format($CustomerUIDs){

		$cus_id = [];
		if(!empty($CustomerUIDs)){
			foreach ($CustomerUIDs as $key => $CustomerUID) {
				$cus_id[] = $CustomerUID->CustomerUID;
			}
		}
		return $cus_id;
	}



	function get_vendor_getnextorder($loggedid,$postarray,$customer_uids,$VendorUID){
		$filter_workflow = $postarray['filter_workflow'];
		$GroupUID = $postarray['GroupUID'];
		$SubProductUID = $postarray['SubProductUID'];

		$subproduct_where  = '';
		if($SubProductUID != ''){
			$subproduct_where  .= "AND mgroupcustomers.GroupCustomerSubProductUID = '".$SubProductUID."' ";
		}else{
			$customer_ids = $this->get_customeringroup($GroupUID);
			$cus_subproducts = [];
			if(!empty($customer_ids)){

				$cus_subproducts = $this->get_customerproductandsubproduct($customer_ids);
			}
			

			if(!empty($cus_subproducts)){
				if($cus_subproducts->SubProductUIDs !='' ){
					$subproduct_where .= 'AND mgroupcustomers.GroupCustomerSubProductUID IN ('.$cus_subproducts->SubProductUIDs.')';
				}
			}
		}

		$check_workflow_permissions = $this->check_workflow_permissions($filter_workflow);
		$where  = '';
		if(!empty($check_workflow_permissions)){

			if($check_workflow_permissions->DependentWorkflowModule != ''){

				$where .= "AND `torders`.`OrderUID` IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID`  = $check_workflow_permissions->DependentWorkflowModule AND WorkflowStatus  = 5)";
			}
		}

		$this->db->select('GROUP_CONCAT(DISTINCT `OrderUID`) as  OrderUID '); 
		$this->db->where('torderunassignment.AssignedToUserUID',$this->session->userdata('UserUID'));
		$q = $this->db->get('torderunassignment');
		$unassigned = $q->row();

		if ( $unassigned->OrderUID != '' ) 
		{
			$where .= "AND torders.OrderUID NOT IN ($unassigned->OrderUID)";
		}


		$customer_uids = implode(',', $customer_uids);
		if($customer_uids != '')
		{
			$customer_id = $customer_uids;
			$status1 = $this->config->item('keywords')['New Order'];
			$status2 = $this->config->item('keywords')['Order Assigned'];
			$status3 = $this->config->item('keywords')['Order Work In Progress'];
			$status4 = $this->config->item('keywords')['Partial Draft Complete'];
			$status5 = $this->config->item('keywords')['Partial Review Complete'];
			$status6 = $this->config->item('keywords')['Review In Progress'];

			/*--- INHOUSE ORDERS FOR SEARCH ----*/
			if($filter_workflow == '1' ){
				$where .= "AND torders.IsInhouseExternal = '0' ";
			}


			$query = $this->db->query("SELECT `torders`.`OrderUID`, `OrderNumber`, `torders`.`CustomerUID`, `CustomerName`, `OrderTypeName`, `PriorityName`, `torders`.`SubProductUID`, `msubproducts`.`SubProductName`, `mproducts`.`ProductUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `morderstatus`.`StatusName`, DATE_FORMAT(OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, DATE_FORMAT(`torders`.`OrderDueDateTime`, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime , torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName FROM (`torders`)  JOIN `mcustomers` ON `torders`.`CustomerUID` = `mcustomers`.`CustomerUID` JOIN `mordertypes` ON `mordertypes`.`OrderTypeUID` = `torders`.`OrderTypeUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `torderassignment` ON  `torderassignment`.`OrderUID` = `torders`.`OrderUID` LEFT JOIN `mgroupcustomers` ON  `mgroupcustomers`.`GroupCustomerSubProductUID` = `torders`.`SubProductUID` WHERE  `torders`.`CustomerUID` IN ($customer_id) AND `torders`.`OrderUID` NOT IN (SELECT `OrderUID` FROM `torderassignment` WHERE  `WorkflowModuleUID` ='".$filter_workflow."' AND AssignedToUserUID IS NOT NULL) AND `torders`.`StatusUID` IN ('".$status1."', '".$status2."', '".$status3."','".$status4."','".$status5."','".$status6."') ".$where." AND torders.OrderUID NOT IN (SELECT torderabstractor.OrderUID FROM torderabstractor LEFT JOIN torderassignment ON torderassignment.OrderUID = torderabstractor.OrderUID WHERE DocumentReceived = 0 AND (torderassignment.WorkflowModuleUID ='".$filter_workflow."' AND torderassignment.WorkflowStatus != 5)) AND torders.OrderUID IN (SELECT OrderUID FROM torderassignment WHERE WorkflowModuleUID = '".$filter_workflow."' AND SendToVendor = '1' AND VendorUID =  '".$VendorUID."' AND OrderFlag <>2) AND torders.SubProductUID = mgroupcustomers.GroupCustomerSubProductUID AND mgroupcustomers.GroupUID = '".$GroupUID."'  ".$subproduct_where." ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC");

			$Orders =  $query->result();

			if(!empty($Orders)){

				return array("message"=>"Order Assigned","status"=>"success","data"=>$Orders[0],'Error'=>'0');

			}else{

				return array("message"=>"No Orders","status"=>"danger","data"=>"",'Error'=>'1');

			}


		}else{
			return array("message"=>"No customer in group","status"=>"danger","data"=>"",'Error'=>'1');

		}
	}


	function get_vendor_orders($loggedid,$post,$VendorUID)
	{

		$limit = '';
		if ($post['length']!='') {
			// $limit = 'LIMIT '.$post['start'].','.$post['length'];
		} else {
			$limit = '';
		}

		if(!empty($post['search_value'])) 
		{
			$like = "AND ";
			foreach ($post['column_search'] as $key => $item) { 
						// if datatable send POST for search

				if ($key === 0) { // first loop
					if ($item=='OrderEntryDatetime' || $item=='OrderDueDatetime') {
						$like .= "( date(".$item.") = '".date('Y-m-d', strtotime($post['search_value']))."' ";
					}
					else{
						$like .= "( ".$item." LIKE '%".$post['search_value']."%' ";
					}
				} else {
					if ($item=='OrderEntryDatetime' || $item=='OrderDueDatetime') {
						$like .= "OR date(".$item.") = '".date('Y-m-d', strtotime($post['search_value']))."' ";
					}
					else{
						$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";
					}
				}
			}
			$like .= ") ";
		} else {
			$like = "";
		}
		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{

			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime , torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime, TRIM(CONCAT_WS(' ',TRIM(torders.PropertyAddress1),TRIM(torders.PropertyAddress2))) AS whole_name,PropertyZipcode FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."' AND torders.OrderUID IN (select OrderUID from torderassignment where AssignedToUserUID=$loggedid and SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2) $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC ";
		}
		else{

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime, TRIM(CONCAT_WS(' ',TRIM(torders.PropertyAddress1),TRIM(torders.PropertyAddress2))) AS whole_name,PropertyZipcode  FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  AND torders.OrderUID IN (select OrderUID from torderassignment where SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2)  $like  GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function get_vendor_countfiltered_orders($loggedid,$post,$VendorUID,$Workflows,$is_vendor_login)
	{

		$limit = '';
		if ($post['length']!='') {
			// $limit = 'LIMIT '.$post['start'].','.$post['length'];
		} else {
			$limit = '';
		}

		if(!empty($post['search_value'])) 
		{
			$like = "AND ";
			foreach ($post['column_search'] as $key => $item) { 
						// if datatable send POST for search
				if ($key === 0) { // first loop
					$like .= "( ".$item." LIKE '%".$post['search_value']."%' "; 
				} else {
					$like .= " OR ".$item." LIKE '%".$post['search_value']."%' ";    
				}
			}
			$like .= ") ";
		} else {
			$like = "";
		} 
		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{

			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'];

			$sql = "SELECT `CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime , torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  AND torders.OrderUID IN (select OrderUID from torderassignment where AssignedToUserUID=$loggedid and SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL)  AND OrderFlag <>2)  $like GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC ";
		}
		else{

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'];

			$sql = "SELECT `CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  AND torders.OrderUID IN (select OrderUID from torderassignment where SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2)  $like  GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$query = $this->db->query($sql);
		// return $query->result_array();

		$checkorders = $this->db->query($sql)->result_array(); 

		foreach ($checkorders as $key => $value) 
		{
			if(in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6,13)) == False)
			{
				$assigned = $this->Common_Model->get_assigned_workflows($value['OrderUID'],$this->loggedid);
				$completed = $this->Common_Model->get_completed_workflows($value['OrderUID'],$this->loggedid);
				$assigned_orderss = [];
				$completed_orderss = [];
				$assigned_workflows = [];
				$completed_workflows = [];
				foreach ($assigned as $keys => $values) {
					$assigned_orderss[] = $values['OrderUID'];
					$assigned_workflows[] = $values['WorkflowModuleUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_orderss[] = $valuess['OrderUID'];
					$completed_workflows[] = $valuess['WorkflowModuleUID'];
				}
				if($assigned_orderss === array_intersect($assigned_orderss, $completed_orderss) && $completed_orderss === array_intersect($completed_orderss, $assigned_orderss)) {

					if($assigned_workflows === array_intersect($assigned_workflows, $completed_workflows) && $completed_workflows === array_intersect($completed_workflows, $assigned_workflows)) {
						unset($checkorders[$key]);
					} 
				}   
			}
		}

		if(!empty($checkorders)){
			$my_orders = $checkorders; 
		} else {
			$my_orders = array();
		}

		return sizeof($my_orders);
	}
	function get_vendor_countall_orders($loggedid,$post,$VendorUID,$Workflows,$is_vendorlogin)
	{

		if($this->Common_Model->GetMyOrdersQueue() == 1)
		{

			$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime , torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID` LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` IN (".$statuses.") AND `torderassignment`.`AssignedToUserUID` = ".$loggedid." AND torderassignment.WorkflowModuleUID !=4 AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."' AND torders.OrderUID IN (select OrderUID from torderassignment where AssignedToUserUID=$loggedid and SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2) GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC ";
		}
		else{

			$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
			','.$this->config->item('keywords')['Cancelled'];

			$sql = "SELECT `CustomerNumber`,`CustomerName`, `OrderNumber`, `StatusName`,`torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`,DATE_FORMAT(torderassignment.AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName,VendorAssignedDateTime FROM (`torders`)  LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID`  LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE `torders`.`StatusUID` NOT IN (".$statuses.") AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  AND torders.OrderUID IN (select OrderUID from torderassignment where SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2)  GROUP BY `OrderUID` ORDER BY FIELD(`torders`.`PriorityUID`,3,1) DESC, `torders`.`OrderEntryDatetime` ASC"; 

		}

		$query = $this->db->query($sql);
		// return $query->result_array();

		$checkorders = $this->db->query($sql)->result_array(); 

		foreach ($checkorders as $key => $value) 
		{

			if(in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6,13)) == False)
			{
				$assigned = $this->Common_Model->get_assigned_workflows($value['OrderUID'],$this->loggedid);
				$completed = $this->Common_Model->get_completed_workflows($value['OrderUID'],$this->loggedid);
				$assigned_orderss = [];
				$completed_orderss = [];
				$assigned_workflows = [];
				$completed_workflows = [];
				foreach ($assigned as $keys => $values) {
					$assigned_orderss[] = $values['OrderUID'];
					$assigned_workflows[] = $values['WorkflowModuleUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_orderss[] = $valuess['OrderUID'];
					$completed_workflows[] = $valuess['WorkflowModuleUID'];
				}
				if($assigned_orderss === array_intersect($assigned_orderss, $completed_orderss) && $completed_orderss === array_intersect($completed_orderss, $assigned_orderss)) {

					if($assigned_workflows === array_intersect($assigned_workflows, $completed_workflows) && $completed_workflows === array_intersect($completed_workflows, $assigned_workflows)) {
						unset($checkorders[$key]);
					} 
				}   
			}
		}

		if(!empty($checkorders)){
			$my_orders = $checkorders; 
		} else {
			$my_orders = array();
		}

		return sizeof($my_orders);
	}

	function vendor_lastviewed_orders($loggedid,$VendorUID)
	{

		$data = $this->db->query("SELECT `OrderUID` FROM `tlastviewedorders` WHERE UserUID = '".$loggedid."' ")->row();


		if(!empty($data)){

			$OrderUIDs = $data->OrderUID;

			if($OrderUIDs != ''){

				$OrderUIDs =  rtrim($OrderUIDs, ',');

				if($this->Common_Model->GetMyOrdersQueue() == 1)
				{

					$statuses = $this->config->item('keywords')['Order Assigned'].','.$this->config->item('keywords')['Order Work In Progress'].','.$this->config->item('keywords')['New Order'].','.$this->config->item('keywords')['Partial Review Complete'].','.$this->config->item('keywords')['Partial Draft Complete'].','.$this->config->item('keywords')['Review In Progress'];

					$where = "AND `torders`.`StatusUID` IN (".$statuses.") AND `torders`.`OrderUID` IN (".$OrderUIDs.") AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  GROUP BY `OrderUID` ORDER BY FIELD(torders.OrderUID,".$OrderUIDs."),FIELD(torders.PriorityUID,1,3,2) LIMIT 10";

				}else{

					$statuses = $this->config->item('keywords')['Order Exported'].','.$this->config->item('keywords')['Order Completed'].
					','.$this->config->item('keywords')['Cancelled'];

					$where = "AND `torders`.`StatusUID` NOT IN (".$statuses.") AND `torders`.`OrderUID` IN (".$OrderUIDs.") AND torderassignment.SendToVendor = '1' AND torderassignment.VendorUID  = '".$VendorUID."'  GROUP BY `OrderUID` ORDER BY FIELD(torders.OrderUID,".$OrderUIDs."),FIELD(torders.PriorityUID,1,3,2) LIMIT 10";

				}

				$sql = "SELECT `CustomerName`, `OrderNumber`, `StatusName`, `torders`.`StatusUID`,`StatusColor`, `torders`.`OrderUID`, `morderpriority`.`PriorityName`,`morderpriority`.`TAT`,`morderpriority`.`PriorityUID`, `mproducts`.`ProductName`, `mproducts`.`ProductCode`, `msubproducts`.`SubProductCode`, `msubproducts`.`SubProductName`, DATE_FORMAT(torders.OrderDueDateTime, '%m-%d-%Y %H:%i:%s') as OrderDueDateTime, DATE_FORMAT(torders.OrderEntryDatetime, '%m-%d-%Y %H:%i:%s') as OrderEntryDatetime, torders.PropertyStateCode,torders.PropertyCityName,torders.PropertyCountyName FROM (`torders`) LEFT JOIN `torderassignment` ON `torders`.`OrderUID` = `torderassignment`.`OrderUID` LEFT JOIN `morderpriority` ON `morderpriority`.`PriorityUID` = `torders`.`PriorityUID`  LEFT JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `torders`.`CustomerUID` LEFT JOIN `msubproducts` ON `msubproducts`.`SubProductUID` = `torders`.`SubProductUID` LEFT JOIN `mproducts` ON `mproducts`.`ProductUID` = `msubproducts`.`ProductUID` LEFT JOIN `morderstatus` ON `morderstatus`.`StatusUID` = `torders`.`StatusUID` WHERE torders.OrderUID IN (select OrderUID from torderassignment where  SendToVendor='1' and (QCCompletedDateTime='0000-00-00 00:00:00' OR QCCompletedDateTime IS NULL) AND OrderFlag <>2) ".$where." ";



				$query = $this->db->query($sql);
				return $query->result_array();

			}else{
				return array();
			}

		}else{

			return array();

		}
	}

	function get_vendor_assigned_datetime($OrderUID,$is_vendor_login,$logged_details){

		$Workflows = $this->get_vendorworkflow($logged_details->VendorUID);
		$ret_data = [];

		foreach ($Workflows as $key => $Workflow) {

			$query=$this->db->query("SELECT torderassignment.VendorUID,SendToVendor,DATE_FORMAT(AssignedDatetime, '%m-%d-%Y %H:%i:%s') as AssignedDatetime FROM `torderassignment` LEFT JOIN mvendors ON mvendors.VendorUID = torderassignment.VendorUID WHERE `OrderUID` = '".$OrderUID."' AND AssignedToUserUID IS NOT NULL AND `WorkflowModuleUID` = '".$Workflow['WorkflowModuleUID']."' ");
			$result = $query->row();
			
			$ret_data = '--';
			if(!empty($result) ){

				if($is_vendor_login ){
					if($result->AssignedDatetime != '' && $result->SendToVendor == '1' && $result->VendorUID == $logged_details->VendorUID){
						$ret_data = $result->AssignedDatetime;
					}				

				}else{

					$ret_data = '--';
				}

			}
			$ret[] =  $ret_data;

		}


		return  implode(" / ",$ret);
	}

	function get_vendorworkflow($VendorUID){
		$query = $this->db->query("SELECT mworkflowmodules.WorkflowModuleUID,mworkflowmodules.WorkflowModuleName,CanIndependentWorkflowModule,DependentWorkflowModule,IsExternalAbstraction FROM mvendorsworkflowmodules JOIN mvendors ON mvendors.VendorUID = mvendorsworkflowmodules.VendorUID JOIN mworkflowmodules ON mworkflowmodules.WorkflowModuleUID = mvendorsworkflowmodules.WorkflowModuleUID WHERE mvendorsworkflowmodules.VendorUID = '".$VendorUID."' GROUP BY mworkflowmodules.WorkflowModuleUID ");
		return $result = $query->result_array();
	}


	function get_assigned_workflow_users($OrderUID,$is_vendor_login,$logged_details){



		if($is_vendor_login){

			$Workflows = $this->get_vendorworkflow($logged_details->VendorUID);

		}else{
			$Workflows = $this->Common_Model->get_all_Workflows();

		}
		$ret_data = [];

		foreach ($Workflows as $key => $Workflow) {

			$query=$this->db->query("SELECT RoleType,LoginID,SendToVendor,torderassignment.VendorUID as  AssignedVendorUID,VendorName,musers.VendorUID as VendorUID  FROM `torderassignment` LEFT JOIN musers on musers.UserUID  = torderassignment.AssignedToUserUID LEFT JOIN mvendors ON mvendors.VendorUID = torderassignment.VendorUID LEFT JOIN mroles ON mroles.RoleUID = musers.RoleUID WHERE `OrderUID` = '".$OrderUID."'  AND `WorkflowModuleUID` = '".$Workflow['WorkflowModuleUID']."' AND ( AssignedToUserUID IS NOT NULL OR SendToVendor = '1' ) ");
			$result = $query->row();
			$ret_data = '--';
			if(!empty($result)){

				if($is_vendor_login ){
					
					$ret_data = '--';
					if($result->SendToVendor == '0'  ){
						$ret_data = '--';
					}else if( $result->AssignedVendorUID == $logged_details->VendorUID){	
						if($result->LoginID == ''){
							$ret_data = strtok($result->VendorName, ' ');
						}else if($result->RoleType == '13' || $result->RoleType == '14'){
							$ret_data = $result->LoginID;
						}else{
							$ret_data = '--';
						}
					}


				}else{	
					if($result->SendToVendor == '1' ){

						$ret_data = strtok($result->VendorName, " ");
					}else{
						
						$ret_data = $result->LoginID;
					}
				}

			}else{
				$ret_data = '--';
			}

			$ret[] =  $ret_data;

		}


		return  implode(" / ",$ret);
	}

	function get_vendor_ordered_datetime($OrderUID,$is_vendor_login,$logged_details){

		$Workflows = $this->get_vendorworkflow($logged_details->VendorUID);
		$ret_data = [];

		foreach ($Workflows as $key => $Workflow) {

			$query=$this->db->query("SELECT torderassignment.VendorUID,SendToVendor,DATE_FORMAT(VendorAssignedDateTime, '%m-%d-%Y %H:%i:%s') as VendorAssignedDateTime FROM `torderassignment` LEFT JOIN mvendors ON mvendors.VendorUID = torderassignment.VendorUID WHERE `OrderUID` = '".$OrderUID."' AND  `WorkflowModuleUID` = '".$Workflow['WorkflowModuleUID']."' AND ( AssignedToUserUID IS NOT NULL OR SendToVendor = '1' )");
			$result = $query->row();
			
			$ret_data = '--';
			if(!empty($result) ){

				if($is_vendor_login ){
					if($result->VendorAssignedDateTime != '' && $result->SendToVendor == '1' && $result->VendorUID == $logged_details->VendorUID){
						$ret_data = $result->VendorAssignedDateTime;
					}				

				}else{

					$ret_data = '--';
				}

			}
			$ret[] =  $ret_data;

		}


		return  implode(" / ",$ret);
	}
	function get_vendor_due_datetime($OrderUID,$is_vendor_login,$logged_details){

		$Workflows = $this->get_vendorworkflow($logged_details->VendorUID);
		$ret_data = [];

		foreach ($Workflows as $key => $Workflow) {

			$query=$this->db->query("SELECT torderassignment.VendorUID,SendToVendor,DATE_FORMAT(VendorDueDate, '%m-%d-%Y %H:%i:%s') as VendorDueDate FROM `torderassignment` LEFT JOIN mvendors ON mvendors.VendorUID = torderassignment.VendorUID WHERE `OrderUID` = '".$OrderUID."' AND  `WorkflowModuleUID` = '".$Workflow['WorkflowModuleUID']."' AND ( AssignedToUserUID IS NOT NULL OR SendToVendor = '1' )");
			$result = $query->row();
			
			$ret_data = '--';
			if(!empty($result)){

				if($is_vendor_login ){
					if($result->VendorDueDate != '' && $result->SendToVendor == '1' && $result->VendorUID == $logged_details->VendorUID){
						$ret_data = $result->VendorDueDate;
					}				

				}else{

					$ret_data = '--';
				}

			}
			$ret[] =  $ret_data;

		}


		return  implode(" / ",$ret);
	}

	function get_customerproductandsubproduct($Customers){
		if($Customers->CustomerUIDs != ''){
			$query = $this->db->query("SELECT GROUP_CONCAT(DISTINCT SubProductUID) AS SubProductUIDs FROM `mcustomerproducts` WHERE CustomerUID IN ($Customers->CustomerUIDs)");
			return $query->row();
		}
		return array();
	}

	function get_customeringroup($GroupUID){
		
		$query = $this->db->query("SELECT GROUP_CONCAT(DISTINCT `mcustomers`.`CustomerUID`) AS CustomerUIDs FROM (`mgroupcustomers`) JOIN `mcustomers` ON `mcustomers`.`CustomerUID` = `mgroupcustomers`.`GroupCustomerUID` WHERE `mgroupcustomers`.`GroupUID`  =  $GroupUID  AND mcustomers.Active = 1 ORDER BY `mcustomers`.`CustomerName`  ");
		return $query->row();
	}

	function check_assigned_to_agent($OrderUID,$logged_details){
		$this->db->select ( '*' );
		$this->db->from ( 'torderassignment' );
		$this->db->where('torderassignment.AssignedToUserUID !=', 'NULL');
		$this->db->where('torderassignment.OrderUID',$OrderUID);
		$this->db->where('torderassignment.VendorUID',$logged_details->VendorUID);

		$query = $this->db->get();
		$res =  $query->num_rows();

		return $res;		
	}
	function SearchCheckWorkflowStatus($OrderUID){

		$this->db->select('*');
		$this->db->from('torderassignment');
		$this->db->where('OrderUID',$OrderUID);
		$this->db->where('WorkflowModuleUID',1);
		return $this->db->get()->row();
	}

	function TypingCheckWorkflowStatus($OrderUID){

		$this->db->select('*');
		$this->db->from('torderassignment');
		$this->db->where('OrderUID',$OrderUID);
		$this->db->where('WorkflowModuleUID',2);
		return $this->db->get()->row();
	}

	function TaxingCheckWorkflowStatus($OrderUID){

		$this->db->select('*');
		$this->db->from('torderassignment');
		$this->db->where('OrderUID',$OrderUID);
		$this->db->where('WorkflowModuleUID',3);
		return $this->db->get()->row();
	}
	function SearchStatus($OrderUID){
		$this->db->select('*');
		$this->db->from('torders');
		$this->db->where('OrderUID',$OrderUID);
		return $this->db->get()->row();

	}

	function GetPRNAME($OrderUID){
		$query=$this->db->query('SELECT GROUP_CONCAT(DISTINCT `torderpropertyroles`.`PRName`) AS Borrower FROM torderpropertyroles WHERE OrderUID='.$OrderUID.'');
		return $query->row();

	}

}?>

