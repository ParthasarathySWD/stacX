<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyOrders extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('MyOrders_Model');
	}	
	
	public function index()
	{
		$data['content']='index';
		$data['is_selfassign'] = 1;
		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}
	
	
	function sort_table(){
		
		$UserUID = $this->session->userdata('UserUID');
		$FieldName = $this->input->post('FieldsDisplayTable');
		$FieldFormName = $this->input->post('FieldFormName');
		$SortingDescription = $this->input->post('SortingTable');
		$FieldSortByName = $this->input->post('SortingDescription');
		
		$CheckCustomFields = $this->MyOrders_Model->GetCheckCustomFields($UserUID);
		$CheckCustomFields = $CheckCustomFields->CheckCustomFields;
		
		if($CheckCustomFields == 0 )
		{
			
			for($i=0;$i<count($FieldName);$i++){  
				
				if($FieldName[$i] === 'StatusName'){
					
				}
				else{
					
					$fieldArray = array(
						"CustomSortByUserUID"=>$UserUID,
						"CustomSortDateTime"=>Date('Y-m-d H:i:s',strtotime("now")),
						"FieldName"=>$FieldName[$i],
						"FieldFormName"=>$FieldFormName[$i],
						"FieldPosition"=>$i,
						"FieldSortBy"=>$SortingDescription,
						"FieldSortByName"=>$FieldSortByName[0]
					);
					
					$res = $this->db->insert('mcustomsortcolumns', $fieldArray);
				}
			}
		}
		
		else{
			
			$this->db->where(array("CustomSortByUserUID"=>$UserUID));
			$res = $this->db->delete('mcustomsortcolumns');
			
			for($i=0;$i<count($FieldName);$i++){
				
				if($FieldName[$i] === 'StatusName'){
					
				}
				else{
					
					$FieldPosition = $i + 1;
					
					$fieldArray = array(
						"CustomSortByUserUID"=>$UserUID,
						"CustomSortDateTime"=>Date('Y-m-d H:i:s',strtotime("now")),
						"FieldName"=>$FieldName[$i],
						"FieldFormName"=>$FieldFormName[$i],
						"FieldPosition"=>$FieldPosition,
						"FieldSortBy"=>$SortingDescription,
						"FieldSortByName"=>$FieldSortByName[0]
					);
					
					$res = $this->db->insert('mcustomsortcolumns', $fieldArray);
					
				}
			}
			
		}
		
		echo json_encode($res);
	}
	
	
	function reset_table()
	{
		
		$UserUID = $this->session->userdata('UserUID');
		$FieldName = $this->input->post('FieldsDisplayTable');
		$FieldFormName = $this->input->post('FieldFormName');
		$SortingDescription = $this->input->post('SortingTable');
		
		$CheckCustomFields = $this->MyOrders_Model->GetCheckCustomFields($UserUID);
		$CheckCustomFields = $CheckCustomFields->CheckCustomFields;
		$res = '';
		
		if($CheckCustomFields != 0 )
		{
			$this->db->where(array("CustomSortByUserUID"=>$UserUID));
			$res = $this->db->delete('mcustomsortcolumns');
		}
		
		echo json_encode($res);
	}
	
	
	public function custom_sort()
	{ 
		
		$UserUID = $this->session->userdata('UserUID');
		
		$UserName = $this->MyOrders_Model->GetUserName($UserUID);
		
		$UserName = $UserName->UserName;
		
		$CheckCustomFields = $this->MyOrders_Model->GetCheckCustomFields($UserUID);
		$CheckCustomFields = $CheckCustomFields->CheckCustomFields;
		
		if($CheckCustomFields == 0 )
		{
			$data['Action']="ADD";
		}
		else
		{
			$data['Action']="EDIT_Sort";
			$data['Sorting'] = $this->MyOrders_Model->GetOrderByField($UserUID);
			$data['Sort'] = $data['Sorting']->FieldSortBy;
			$data['SortFieldName'] = $data['Sorting']->FieldSortByName;
			$data['CustomFieldName'] = $this->MyOrders_Model->GetCustomFieldName($UserUID);
		}
		
		$data['UserUID']= $UserUID;
		$data['UserName']= $UserName;
		$data['Roles'] = $this->MyOrders_Model->GetRoles();
		
		$this->load->view('custom_sort',$data);
		
		
		/* $data['UserUID']= $UserUID;
		$data['UserName']= $UserName;
		$data['Roles'] = $this->MyOrders_Model->GetRoles();
		
		$this->load->view('custom_sort',$data);*/
	}
	
	function Get_UserUID_ByRoleUID(){
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){
			$RoleUID = $this->input->post('RoleUID');
			$details = $this->MyOrders_Model->GetUserByRoleUID($RoleUID);
			$returnvalue = array('data'=>$details,'success'=>'1');
			echo json_encode($returnvalue);
			
		}
	}
	
	
	function preview($OrderUID)
	{
		
		if($OrderUID){
			
			$this->loggedid = $this->UserUID;
			
			$OrderUID = $OrderUID;
			
			redirect(base_url().'preview?OrderUID='.$OrderUID.'/');
			
		}
		else
		{
			redirect(base_url().'my_orders');
		}
		
	}
	
	
	function notes($OrderUID)
	{
		
		if($OrderUID){
			
			$this->loggedid = $this->UserUID;
			
			$OrderUID = $OrderUID;
			
			redirect(base_url().'notes?OrderUID='.$OrderUID.'/');
			
		}
		else
		{
			redirect(base_url().'my_orders');
		}
		
	}
	
	
	
	
	function acceptorder(){
		$orderUID = $this->input->post('OrderUID');
		$status = 3;
		$flag = 1;
		$result = $this->MyOrders_Model->Change_order_status($orderUID,$status,$flag,$this->loggedid);
		if($result){
			echo json_encode(array('data'=>$result,'Error'=>0));
			
		}else{
			echo json_encode(array('data'=>$result,'Error'=>1));
		} 
	}
	
	/*Function to accept Orders*/
	
	function rejectorder(){
		$orderUID = $this->input->post('OrderUID');
		$Remarks = $this->input->post('Remarks');
		$Reason = $this->input->post('Reason');
		
		$logged_details = $this->Common_Model->get_logged_details();
		$vendors  = $this->Common_Model->get_vendors($logged_details,$this->loggedid);
		$vendoruids = $this->Common_Model->get_vendor_uids($vendors);
		
		$result = $this->MyOrders_Model->RejectAssignedOrder($orderUID,$this->loggedid,$vendoruids,$Remarks,$Reason);
		if($result){
			echo json_encode(array('data'=>$result,'message'=>'Order Rejected','Error'=>0));
			
		}else{
			echo json_encode(array('data'=>$result,'message'=>'Failed','Error'=>1));
		} 
	}	
	
	/*Function to cancel Orders*/
	
	function cancel_order()
	{
		$orderUID = $this->input->post('OrderUID'); 
		$Remarks = $this->input->post('Remarks');
		$OrderNumber =  $this->Common_Model->GetOrderNumberByOrderUID($orderUID);
		$value = $this->MyOrders_Model->CheckCancelOrderExist($orderUID);
		
		
		if($value)
		{
			
			$Msg = $this->lang->line('Cancel_error');
			$res = array('validation_error' => 3,'message'=>$Msg);
			echo json_encode($res);
		}
		else
		{
			
			
			if(in_array($this->RoleUID, array('1','2','3','4','5','6')))
			{
				
				$CancellationRequestDateTime  = date('Y-m-d H:i:s',strtotime('now'));
				$result = $this->MyOrders_Model->approve_cancel_order($orderUID,$Remarks,$this->loggedid,$CancellationRequestDateTime);
				
				if($result)
				{
					// insert cancellation notes
					$this->load->model('notes/notes_model');
					$RoleType=$this->session->userdata('RoleType');
					$RoleTypeUID=new stdClass();
					if ($RoleType==8) {
						$Customers=1;
						$RoleTypeUID->Customers=1;
					}
					else{
						$Customers=0;	
					}
					
					$SectionUID=23;
					$message=$Remarks;
					$this->loggedid=$this->loggedid;
					$filename='';
					$SectionColor='';
					$AbstractorUID='';
					
					$this->notes_model->save_note($orderUID,$SectionUID,$message,$this->loggedid,$filename,$RoleTypeUID,$SectionColor,$AbstractorUID);
					$Msg = $this->lang->line('Cancel');
					$Rep_msg=str_replace("<<Order Number>>",$OrderNumber->OrderNumber, $Msg);
					$res = array('validation_error' => 1,'message'=> $Rep_msg);
					echo json_encode($res);
					
				}
				else
				{
					$Msg = $this->lang->line('Error');
					$res = array('validation_error' => 0,'message'=> $Msg);
					echo json_encode($res);
				}
				
			}
			else
			{
				
				$CancellationRequestDateTime  = date('Y-m-d H:i:s',strtotime('now'));
				$result = $this->MyOrders_Model->cancel_order($orderUID,$Remarks,$this->loggedid,$CancellationRequestDateTime);
				
				if($result)
				{
					$Msg = $this->lang->line('Cancel');
					$Rep_msg=str_replace("<<Order Number>>",$OrderNumber->OrderNumber, $Msg);
					$res = array('validation_error' => 1,'message'=> $Rep_msg);
					echo json_encode($res);
				}
				else
				{
					$Msg = $this->lang->line('Error');
					$res = array('validation_error' => 0,'message'=> $Msg);
					echo json_encode($res);
				}
				
			}
		}
	}
	
	
	/*Assigned Orders by GetnextOrder*/
	function add_order($OrderUID,$filter_workflow){
		
		
		
		$Order = $this->MyOrders_Model->get_orderbyid($OrderUID);
		$OrderNumber =  $this->Common_Model->GetOrderNumberByOrderUID($OrderUID);
		
		
		$customer_workflow = $this->MyOrders_Model->customer_workflow($Order->CustomerUID,$Order->SubProductUID);
		
		if(!empty($customer_workflow)){
			$orders_assign = $this->MyOrders_Model->assign_selectedorders($OrderUID,$this->loggedid,$customer_workflow,$filter_workflow);
			
			
			if(!$orders_assign){
				$Msg = $this->lang->line('Assign_Failed');
				$Rep_msg=str_replace("<<Order Number>>",$OrderNumber->OrderNumber, $Msg);
				return array('Error'=>'1','message'=>$Rep_msg,'status'=>'danger');
				
			}else{
				$Msg = $this->lang->line('Assign');
				$Rep_msg=str_replace("<<Order Number>>",$OrderNumber->OrderNumber, $Msg);
				return array('Error'=>'0','message'=>$Rep_msg,'status'=>'success');
				
			}
			
		}else{
			$Msg = $this->lang->line('Assign_NoWorkflow');
			$Rep_msg=str_replace("<<Order Number>>",$OrderNumber->OrderNumber, $Msg);
			
			return array('Error'=>'1','message'=>$Rep_msg,'status'=>'danger');
			
			
		}
		
		
	}
	
	
	function common_orderuidformat($array,$getfieldvalue){
		$OrderUIDs = [];
		foreach ($array as $key => $value) {
			$OrderUIDs[] = $value->$getfieldvalue;
		}
		return $OrderUIDs;
	}
	
	
	function getnextorder(){
		
		$filter_workflow = $this->input->post('filter_workflow');
		$get_onhold = $this->MyOrders_Model->get_onhold_orders($this->loggedid);
		$assigned_orders = $this->MyOrders_Model->get_assigned_orders($this->loggedid);
		$onhold_orderuids = $this->common_orderuidformat($get_onhold,'OrderUID');
		$assigned_orderuids = $this->common_orderuidformat($assigned_orders,'OrderUID');
		$is_pending = array_merge(array_diff($onhold_orderuids,$assigned_orderuids),array_diff($assigned_orderuids,$onhold_orderuids));
		
		$Msg = $this->lang->line('Existing_oreder_based_error');
		$Msg1 = $this->lang->line('No_Orders');
		$is_vendorlogin = $this->Common_Model->is_vendorlogin();
		if($filter_workflow !=''){
			if(!empty($is_pending)){
				
				echo json_encode(array("message"=>$Msg,"status"=>"warning","data"=>"",'Error'=>'1'));exit;
			}else{
				
				if($is_vendorlogin){
					
					$result = $this->get_vendor_login_orders($this->input->post(),$is_vendorlogin);
					
				}else{
					if (in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6))){
						$result = $this->MyOrders_Model->getnext_order_all($this->input->post());
					}else{
						$result = $this->MyOrders_Model->get_next_order($this->loggedid,$this->input->post());
					}
				}
				if($result['Error'] == 1){
					
					echo json_encode(array("message"=>$Msg1,"status"=>"danger","data"=>"",'Error'=>'1'));exit;
				}
				if($filter_workflow == 1){
					if($result['Error'] == '0'){
						$is_assigned = $this->add_order($result['data']->OrderUID,$filter_workflow);
						$data1['ModuleName']='GetNextOrder - insert';
						$data1['IpAddreess']=$_SERVER['REMOTE_ADDR']; 
						$data1['DateTime']=date('y-m-d H:i:s');
						$data1['TableName']='torders';
						$data1['OrderUID'] = $result['data']->OrderUID;
						$data1['UserUID']=$this->session->userdata('UserUID');                
						$this->Common_Model->Audittrail_insert($data1);
						echo json_encode($is_assigned);exit;
					}else{
						
						echo json_encode(array("message"=>$Msg1,"status"=>"danger","data"=>"",'Error'=>'1'));exit;
						
					}
					
				}
				
				if($filter_workflow == 2){
					
					$is_searchcompleted = $this->Common_Model->is_workflow_completed($result['data']->OrderUID,1);
					if(!empty($is_searchcompleted)){
						$is_searchcompleted = 1;
					}else{
						$is_searchcompleted = 0;
					}
					
					
					if($is_searchcompleted == 1){
						
						if($result['Error'] == 0){
							$is_assigned = $this->add_order($result['data']->OrderUID,$filter_workflow);
							echo json_encode($is_assigned);exit;
						}else{
							echo json_encode(array("message"=>$Msg1 ,"status"=>"danger","data"=>"",'Error'=>'1'));exit;
						}
					}else{
						
						echo json_encode(array("message"=>$Msg1 ,"status"=>"danger","data"=>"",'Error'=>'1'));exit;
					}
				}
				
				if($filter_workflow == 3){
					
					if($result['Error'] == '0'){
						
						$is_assigned = $this->add_order($result['data']->OrderUID,$filter_workflow);
						echo json_encode($is_assigned);exit;
						
					}else{
						
						echo json_encode(array("message"=>$Msg1,"status"=>"danger","data"=>"",'Error'=>'1'));exit;
						
					}
				}
			}
		}else{
			echo json_encode(array("message"=>"Select Workflow","status"=>"danger","data"=>"",'Error'=>'1'));exit;
			
		}
	}
	
	function get_product_bygroup(){
		
		$GroupUID = $this->input->post('GroupUID');
		if($GroupUID != ''){
			$psub = $this->MyOrders_Model->get_prod_by_groupuid($GroupUID);
			echo json_encode(array("message"=>'',"status"=>"","data"=>$psub,'Error'=>'0'));exit;
		}else{
			echo json_encode(array("message"=>'select Group',"status"=>"danger","data"=>"",'Error'=>'1'));exit;
		}
		
	}
	function subproduct_bygroup_product(){
		
		$GroupUID = $this->input->post('GroupUID');
		$ProductUID = $this->input->post('ProductUID');
		if($GroupUID != '' && $ProductUID !=''){
			$psub = $this->MyOrders_Model->get_subprod_bygroup_product($GroupUID,$ProductUID);
			
			echo json_encode(array("message"=>'',"status"=>"","data"=>$psub[0],'Workflows'=>$psub[1],'Error'=>'0'));exit;
		}else{
			echo json_encode(array("message"=>'select Group',"status"=>"danger","data"=>"",'Error'=>'1'));exit;
		}
		
	}
	
	
	
	function get_workflowbygroups(){
		
		$GroupUID = $this->input->post('GroupUID');
		$ProductUID = $this->input->post('ProductUID');
		$SubProductUID = $this->input->post('SubProductUID');
		
		if($GroupUID != '' && $ProductUID !='' && $SubProductUID !=''){
			$workflow = $this->MyOrders_Model->get_workflowbygroups($GroupUID,$ProductUID,$SubProductUID);
			echo json_encode(array("message"=>'',"status"=>"","data"=>$workflow,'Error'=>'0'));exit;
		}else{
			echo json_encode(array("message"=>'select Required',"status"=>"danger","data"=>"",'Error'=>'1'));exit;
			
		}
		
	}
	
	function get_product_by_group($GroupUID){
		
		$psub = [];
		if($GroupUID != ''){
			$psub = $this->MyOrders_Model->get_prod_by_groupuid($GroupUID);
			return $psub;
		}else{
			return $psub;
		}
		
	}
	function subproduct_by_group_product($GroupUID,$ProductUID){
		
		
		$psub = [];
		if($GroupUID != '' && $ProductUID !=''){
			$psub = $this->MyOrders_Model->get_subprod_bygroup_product($GroupUID,$ProductUID);
			
			return $psub;
		}else{
			return $psub;
		}
		
	}
	
	
	
	function get_workflowby_groups($GroupUID,$ProductUID,$SubProductUID){
		
		$workflow =[];
		if($GroupUID != '' && $ProductUID !='' && $SubProductUID !=''){
			$workflow = $this->MyOrders_Model->get_workflowbygroups($GroupUID,$ProductUID,$SubProductUID);
			return $workflow;
		}else{
			return $workflow;
			
		}
		
	}
	
	
	/*Function to generate Excel Export Orders*/
	
	function export_excel()
	{
		$this->load->library('Excel');
		$is_vendorlogin = $this->Common_Model->is_vendorlogin();
		
		
		$Customer_Check = $this->MyOrders_Model->GetCustomerByUserUID($this->loggedid);
		
		if($Customer_Check->CustomerUID != '' && $Customer_Check->CustomerUID != '0' ){
			
			$CustomerUID = $Customer_Check->CustomerUID;
			$myorders = $this->MyOrders_Model->getexcel_myorders_by_cust_id($this->loggedid,$CustomerUID);
			
		}elseif($is_vendorlogin){
			$post['length'] = '';
			$post['search_value'] = '';
			$logged_details = $this->Common_Model->get_logged_details();
			$vendors  = $this->MyOrders_Model->get_vendors($logged_details,$this->loggedid);
			if(!empty($vendors)){
				$myorders=$this->MyOrders_Model->get_vendor_orders($this->loggedid,$post,$vendors[0]->VendorUID);
			}
			
		}else{
			$myorders = $this->MyOrders_Model->export_option($this->loggedid);
		}
		
		foreach ($myorders as $key => $value) {
			
			if (in_array($this->session->userdata('RoleType'),array(1,2,3,4,5,6,13,8)) == False){
				$assigned = $this->Common_Model->get_assigned_workflows($value['OrderUID'],$this->loggedid);
				$completed = $this->Common_Model->get_completed_workflows($value['OrderUID'],$this->loggedid);
				$assigned_orderss = [];
				$completed_orderss = [];
				foreach ($assigned as $keys => $values) {
					$assigned_orderss[] = $values['WorkflowModuleUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_orderss[] = $valuess['WorkflowModuleUID'];
				}
				$assigned_workflows = [];
				$completed_workflows = [];
				foreach ($assigned as $keys => $values) {
					$assigned_workflows[] = $values['OrderUID'];
				}
				foreach ($completed as $keyss => $valuess) {
					$completed_workflows[] = $valuess['OrderUID'];
				}
				if($assigned_orderss === array_intersect($assigned_orderss, $completed_orderss) && $completed_orderss === array_intersect($completed_orderss, $assigned_orderss)) {
					
					if($assigned_workflows === array_intersect($assigned_workflows, $completed_workflows) && $completed_workflows === array_intersect($completed_workflows, $assigned_workflows)) {
						unset($myorders[$key]);
					} 
				}   
			}
		}
		
		
		
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$ColumnArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
		foreach ($ColumnArray as  $value) {
			$objPHPExcel->getActiveSheet()->getStyle($value.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()->setRGB('003366');
		}
		$styleArray = array('font'  => array('bold'  => true,'color' => array('rgb' => 'ffffff')));
		
		
		
		
		if (in_array($this->session->userdata('RoleType'),array(8))){
			$ColumnArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
			foreach ($ColumnArray as  $value) {
				$objPHPExcel->getActiveSheet()->getStyle($value.'1')->applyFromArray($styleArray);
			}
			
			$ColumnArray = array('A'=>'Customer Name','B'=>'Product/Sub Product','C'=>'Order Number','D'=>'Status','E'=>'Ordered Date','F'=>'Loan Number','G'=>'Borrower Name','H'=>'Property Address','I'=>'City','J'=>'County','K'=>'State','L'=>'Zip','M'=>'Due Date','N'=>'Completed Date');
			
			foreach ($ColumnArray as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.'1', $value);
			}
			$n=2;
			
			
			foreach ($myorders as $value) 
			{
				
				$Borrowername = $this->Common_Model->Get_borrowers_by_OrderUID($value['OrderUID']);
				$onholdworkflow = $this->Common_Model->get_onholdWorkflow($value['OrderUID']);
				if($onholdworkflow->WorkflowModuleName != '')
				{ 
					$stus = 'On-Hold';
				} else { 
					if(!in_array($value['StatusUID'], array(0,5,49,100,110)))
					{ 
						$assigned = $this->Common_Model->check_order_is_assigned($value['OrderUID']); 
						if($assigned!=0)
						{  
							$typingin = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($value['OrderUID'], array(1,2,3),array(0,3)); 
							$search_typing = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($value['OrderUID'], array(1),array(5)); 
							$typing_review = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($value['OrderUID'], array(2),array(5)); 
							$tax_review = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($value['OrderUID'], array(3),array(5)); 
							if($typingin!=0)
							{
								$stus = 'Search in Progress';
							} 
							if($search_typing!=0) {
								$stus = 'Typing in Progress';
							} 
							if($typing_review!=0) {
								$stus = 'Review in Progress';
							} 
							if($tax_review!=0) {
								$stus = 'Review in Progress';
							}  
						} else {
							$stus = $value['StatusName'];
						}
					} else {
						$stus = $value['StatusName'];
					} 
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$n, $value['CustomerName']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$n, substr($value['ProductName'], 0, 1).'-'.$value['SubProductName']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$n, $value['OrderNumber']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$n, $stus);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$n, $value['OrderEntryDatetime']);   
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$n, $value['LoanNumber']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$n, $Borrowername->BorrowerNames);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$n, $value['PropertyAddress1']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$n, $value['PropertyCityName']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$n, $value['PropertyCountyName']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$n, $value['PropertyStateCode']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$n, $value['PropertyZipcode']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$n, $value['OrderDueDateTime']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$n, $value['OrderCompleteDateTime']);
				$n++;
			}  
			
		}elseif($is_vendorlogin){
			
			
			$ColumnArray = array('A','B','C','D','E','F');
			foreach ($ColumnArray as  $value) {
				$objPHPExcel->getActiveSheet()->getStyle($value.'1')->applyFromArray($styleArray);
			}
			
			$ColumnArray = array('A'=>'Order Number','B'=>'Product/SubProduct','C'=>'State','D'=>'AssignedUser','E'=>'Status','F'=>'Ordered Date');
			
			foreach ($ColumnArray as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.'1', $value);
			}
			$n=2;
			
			$Workflows = $this->MyOrders_Model->get_all_Workflows();
			$is_vendorlogin = $this->Common_Model->is_vendorlogin();
			
			$Workflows = $this->MyOrders_Model->get_all_Workflows();
			$is_vendorlogin = $this->Common_Model->is_vendorlogin();
			
			foreach ($myorders as $value) 
			{
				
				$assignedusers =  $this->MyOrders_Model->get_order_assigned_users($value,$Workflows,$is_vendorlogin);
				$completed_workflowstatus = $this->Common_Model->completed_status_order($value['OrderUID']);
				$onholdworkflow = $this->Common_Model->get_onholdWorkflow($value['OrderUID']);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$n, $value['OrderNumber']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$n, substr($value['ProductName'], 0, 1).'-'.$value['SubProductName']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$n, $value['PropertyStateCode']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$n, $assignedusers);
				if($onholdworkflow->WorkflowModuleName != '') {
					
					$status = $onholdworkflow->WorkflowModuleName.'-OnHold';
					
				}else{ 
					$status = $value["StatusName"];
				} 
				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$n, $status);
				
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$n, $value['OrderEntryDatetime']);
				$n++;
			}
			
			
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			header("Content-Disposition: attachment; filename=\"myorders.xlsx\"");
			header("Cache-Control: max-age=0");
			ob_clean();
			$objWriter->save('php://output');exit;	
		}else{
			
			$ColumnArray = array('A','B','C','D','E','F','G','H','I');
			foreach ($ColumnArray as  $value) {
				$objPHPExcel->getActiveSheet()->getStyle($value.'1')->applyFromArray($styleArray);
			}
			
			$ColumnArray = array('A'=>'Order Number','B'=>'Customer Name','C'=>'Product/SubProduct','D'=>'State','E'=>'AssignedUser','F'=>'Status','G'=>'Workflow Module Completed','H'=>'Ordered Date','I'=>'Due Date');
			
			foreach ($ColumnArray as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue($key.'1', $value);
			}
			$n=2;
			
			$Workflows = $this->MyOrders_Model->get_all_Workflows();
			$is_vendorlogin = $this->Common_Model->is_vendorlogin();
			
			
			foreach ($myorders as $value) 
			{
				
				$assignedusers =  $this->MyOrders_Model->get_order_assigned_users($value,$Workflows,$is_vendorlogin);
				$completed_workflowstatus = $this->Common_Model->completed_status_order($value['OrderUID']);
				$onholdworkflow = $this->Common_Model->get_onholdWorkflow($value['OrderUID']);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$n, $value['OrderNumber']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$n, $value['CustomerName']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$n, substr($value['ProductName'], 0, 1).'-'.$value['SubProductName']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$n, $value['PropertyStateCode']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$n, $assignedusers);
				if($onholdworkflow->WorkflowModuleName != '') {
					
					$status = $onholdworkflow->WorkflowModuleName.'-OnHold';
					
				}else{ 
					$status = $value["StatusName"];
				} 
				
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$n, $status);
				
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$n, $completed_workflowstatus->WorkflowModuleName);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$n, $value['OrderEntryDatetime']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$n, $value['OrderDueDateTime']);
				$n++;
			}
			
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"myorders.xlsx\"");
		header("Cache-Control: max-age=0");
		ob_clean();
		$objWriter->save('php://output');exit;
		
		
	}
	
	
	/*Function to get orders for Organizational Users*/
	
	function ajax_list()
	{ 
		
		$post = $this->get_post_input_data();  
		$post['column_search'] = array('OrderNumber','CustomerName','PropertyStateCode','StatusName','OrderEntryDatetime','OrderDueDatetime');
		$row=[];
		for ($i=0; $i < 10; $i++) { 
			$col=[];
			$col[]= '<tr data-OrderUID="362"><td><a href="http://revamp.direct2title.com/Order_Summary/index/362/1" class="text-primary">P18000357</a> <img src="http://revamp.direct2title.com/assets/img/rush.png" title="Rush" height="20px" width="20px"></td>';
			$col[]= '60245101 / ZB N.A';
			$col[]= 'CARLA MCNELLIS,GERDA RANGE';
			$col[]= 'Rush';
			$col[]= '<span class="btn btn-rounded btn-sm "  style="font-size: 8pt; color: #fff; background: #2B2B2B">Partial Draft Complete</span> ';
			$col[]= 'Review';
			$col[]= '208 5TH AVE';
			$col[]= 'VENICE';
			$col[]= 'LOS ANGELES';
			$col[]= 'CA';
			$col[]= '90291';
			$col[]= 'Property Report';
			$col[]= '2 Owner Search';
			$col[]= '14';
			$col[]= 'mageshm / Sajithkumar / grazillakf / chandramohang / --';
			$col[]= 'TaxCert,Search,Typing,Review';
			$col[]= '03-22-2018 23:59:59';
			$col[]= '';
			$col[]= '03-22-2018 23:59:59';
			$col[]= '$0.00';
			$col[]= '<div class="badgebar"><span class="customerdelay-badge">C</span><a href="http://revamp.direct2title.com/Order_Summary/index/362/1" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload"><i class="icon-pencil"></i><div class="ripple-container"></div></a><button data-OrderUID = "362" class="btn btn-link btn-danger btn-just-icon btn-xs cancel_order"><i class="icon-cross2"></i><div class="ripple-container"></div></button></div></tr>';

			$row[]=$col;
		}


		$data['data'] = $row;
		$post['draw'] = 1;
		$totalrecords = 6914;
		

			$output = array(
				"draw" => $post['draw'],
				"recordsTotal" => $totalrecords,
				"recordsFiltered" => $totalrecords,
				"data" => $data['data'],
			);
			// if ($post['search_value']!='' && isset($post['search_value'])) {
			// 	$output['recordsFiltered']=$this->MyOrders_Model->filter_check_order($this->loggedid,$post);
			// }
		
		unset($post);
		unset($data);
		echo json_encode($output);        
	}
	
	function get_post_input_data(){
		$post['length'] = $this->input->post('length');
		$post['start'] = $this->input->post('start');
		$search = $this->input->post('search');
		$post['search_value'] = trim($search['value']);
		$post['order'] = $this->input->post('order');
		$post['draw'] = $this->input->post('draw');
		return $post;
	}
	
	
	/*Function to get orders for Organizational Users --- looped data*/
	
	function myorder_datatable($orders,$is_vendorlogin='', $logged_details='',$TATOrdersUIDs='')
	{
		$Workflowassigned = $this->MyOrders_Model->get_Workflowassigned($orders['OrderUID']);
		$completed_workflowstatus = $this->Common_Model->completed_status_order($orders['OrderUID']);
		$onholdworkflow = $this->Common_Model->get_onholdWorkflow($orders['OrderUID']);
		
		$row = array();
		
		$OrderNumber = '';
		$Action = '';
		if($orders['PriorityUID'] == '3')
		{
			
			$OrderNumber = '<tr data-OrderUID="'.$orders['OrderUID'].'">
			<td><a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1" class="'.(in_array($orders["OrderUID"], $TATOrdersUIDs) ? "text-danger" : "text-primary").'" >'.$orders['OrderNumber'].'</a> <img src="'.base_url().'assets/img/asap.png" title="'.$orders['PriorityName'].'" height="20px" width="20px"></td>';
			
		}else if($orders['PriorityUID'] == '1'){
			
			$OrderNumber = '<tr data-OrderUID="'.$orders['OrderUID'].'"><td><a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1" class="'.(in_array($orders["OrderUID"], $TATOrdersUIDs) ? "text-danger" : "text-primary").'">'.$orders['OrderNumber'].'</a> <img src="'.base_url().'assets/img/rush.png" title="'.$orders['PriorityName'].'" height="20px" width="20px"></td>';
			
		}else{
			
			$OrderNumber = '<tr data-OrderUID="'.$orders['OrderUID'].'"><td><a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1" class="'.(in_array($orders["OrderUID"], $TATOrdersUIDs) ? "text-danger" : "text-primary").'">'.$orders['OrderNumber'].'</a></td>';
			
		}
		
		if(!$is_vendorlogin){
			$row[] = $orders['CustomerNumber'].' / '.$orders['CustomerName'];
			$row[] = $orders['LoanNumber'];
		}
		$row[] = $OrderNumber;
		$Borrower = $this->MyOrders_Model->GetPRNAME($orders['OrderUID']);
		$row[]=$Borrower->Borrower;
		$row[] = $orders['PriorityName'];
		
		if($onholdworkflow->WorkflowModuleName != '') {
			
			$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color:#fff; background: #ff8600;">'.$onholdworkflow->WorkflowModuleName.'-OnHold</span>';
			
		}else{ 
			
			$Status = ' <span class="btn btn-rounded btn-sm "  style="font-size: 8pt; color: #fff; background: '.$orders["StatusColor"].'">'.$orders["StatusName"].'</span> ';
			
		} 
		$search='';
		$type='';
		$tax='';
		$review='';
		$Orderstatus='';
		$currentqueue='';
		$search=$this->MyOrders_Model->SearchCheckWorkflowStatus($orders['OrderUID']);
		$type=$this->MyOrders_Model->TypingCheckWorkflowStatus($orders['OrderUID']);
		$tax=$this->MyOrders_Model->TaxingCheckWorkflowStatus($orders['OrderUID']);
		$Orderstatus=$this->MyOrders_Model->SearchStatus($orders['OrderUID']);
		$currentqueue='';
		$currentqueue = $this->Common_Model->GetCurrentQueueStatus($orders['OrderUID']);
		$row[] = $Status;
		
		$row[] = $currentqueue;
		$row[] = $orders['whole_name'];
		$row[] = $orders['PropertyCityName'];
		$row[] = $orders['PropertyCountyName'];
		$row[] = $orders['PropertyStateCode'];
		$row[] = $orders['PropertyZipcode'];
		$row[] = $orders['ProductName'];
		$row[] = $orders['SubProductName'];
		$row[] = $orders['SubProductCode'];		
		$row[] =$this->MyOrders_Model->get_assigned_workflow_users($orders['OrderUID'],$is_vendorlogin,$logged_details);
		
		if(!$is_vendorlogin){
			$row[] = $completed_workflowstatus->WorkflowModuleName;
			$row[] = $orders['OrderDueDateTime'];
			$row[] = '';
			$row[] = $orders['OrderEntryDatetime']; 
		}else{
			
			$vendor_completed_workflowstatus = $this->Common_Model->vendor_completed_status_order($orders['OrderUID'],$logged_details->VendorUID);
			
			$row[] = $vendor_completed_workflowstatus->WorkflowModuleName;
			
			$row[]=$this->MyOrders_Model->get_vendor_due_datetime($orders['OrderUID'],$is_vendorlogin,$logged_details);
			$row[] = 'Due Past';
			$row[] =  '<td> <span class="more">'.$this->MyOrders_Model->get_vendor_ordered_datetime($orders['OrderUID'],$is_vendorlogin,$logged_details).'</span></td>';
			
		}
		
		if(!$is_vendorlogin){
			if($orders['IsInhouseExternal']!='1'){
				$row[]='Inhouse';
			}else{
				$row[]='External';
			}
			$row[]=$orders['OrderTypeName'];
			$row[]=$orders['AbstractorNo'];
			$row[]=$orders['AbstractorCompanyName'];
		}
		$RoleUID = $this->RoleUID;
		$permission=$this->Common_Model->GetRolePermissions($RoleUID);
		if($permission->AbstractorFee!=0) { 
			$row[]='$'.$orders['AbstractorFee'];
		}              
		if($permission->CustomerPricing!=0) 
		{       
			
			$row[]='$'.$orders['CustomerAmount'];
		}
		if($this->session->userdata('RoleType') != 13){
			if($Workflowassigned->WorkflowStatus == '0' && $this->Common_Model->check_order_is_assignedtouser($orders['OrderUID']) > 0  ) { 
				
				$Action =  '<button data-OrderUID = "'.$orders['OrderUID'].'" class="btn btn-link btn-success btn-just-icon btn-xs acceptorder"><i class="icon-checkmark4"></i><div class="ripple-container"></div></button>';
				
			}else{ 
				$Action = '<div class="badgebar">';
				$CustomerDelay = $this->Common_Model->GetCustomerDelayByOrder($orders['OrderUID']);  
				if($CustomerDelay==1)
				{
					$Action .='<span class="customerdelay-badge">C</span>';
				} 
				
				$Action .= '<a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload"><i class="icon-pencil"></i><div class="ripple-container"></div></a>';
				
				
				if($this->Common_Model->CheckOrderCancelRole($this->loggedid) == 1)
				{
					$Action .=  '<button data-OrderUID = "'.$orders['OrderUID'].'" class="btn btn-link btn-danger btn-just-icon btn-xs cancel_order"><i class="icon-cross2"></i><div class="ripple-container"></div></button>';
				}
				$Action .= '</div>';
			}
		}else{
			if($Workflowassigned->WorkflowStatus == '0' && $this->Common_Model->check_order_is_assignedtouser($orders['OrderUID']) > 0  ) { 
				
				$Action =  '<button data-OrderUID = "'.$orders['OrderUID'].'" class="btn btn-link btn-success btn-just-icon acceptorder"><i class="icon-checkmark4"></i><div class="ripple-container"></div></button>';
				
			}
			else{
				$AssignedCount = $this->MyOrders_Model->check_assigned_to_agent($orders['OrderUID'],$logged_details);
				if($AssignedCount > 0){
					
					$Action .= '<a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1"  class="btn btn-link btn-info btn-just-icon btn-xs btn-just-icon btn-xs ajaxload">
					<i class="icon-pencil"></i><div class="ripple-container"></div></a>';
				}	
				else{
					
					$Action =  '<button data-OrderUID = "'.$orders['OrderUID'].'" class="btn btn-link btn-success btn-just-icon btn-xs acceptorder"><i class="icon-checkmark4"></i><div class="ripple-container"></div></button>';
					
					$Action .= '<button data-placement="top" title="Order Reject" data-toggle="rejectpopover" data-container="body" type="button" data-html="true"  class="btn btn-link btn-danger btn-just-icon btn-xs" id="vendorreject" data-OrderUID = "'.$orders['OrderUID'].'" ><i class="icon-cross2"></i></button>';
					
					$Action .= '<a href="'.base_url('Order_Summary/index/'.$orders['OrderUID']).'/1" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload">
					<i class="icon-pencil"></i><div class="ripple-container"></div></a>';
				}					
			}
			
		}
		
		
		$row[] = $Action.'</tr>';
		return $row;
	} 
	
	/*Function to get orders for Customer ajax Orders*/
	
	function ajax_customer_order_list()
	{	
		
		$UserUID = $this->session->userdata('UserUID');
		$Customer_Check = $this->MyOrders_Model->GetCustomerByUserUID($UserUID);
		$Customer_Check = $Customer_Check->CustomerUID;	  
		$data = $this->process_get_customer_orders();
		$post = $data['post'];
		$output = array(
			"draw" => $post['draw'],
			"recordsTotal" => $this->MyOrders_Model->count_get_myorders_by_cust_id($this->loggedid,$Customer_Check,$post),
			"recordsFiltered" => $this->MyOrders_Model->filter_get_myorders_by_cust_id($this->loggedid,$Customer_Check,$post),
			"data" => $data['data'],
		);
		unset($post);
		unset($data);
		echo json_encode($output);        
	}
	
	function process_get_customer_orders()
	{
		
		$UserUID = $this->session->userdata('UserUID');
		$Customer_Check = $this->MyOrders_Model->GetCustomerByUserUID($UserUID);
		$Customer_Check = $Customer_Check->CustomerUID;
		
		$data['is_selfassign'] = $this->Common_Model->is_selfassign($this->loggedid);
		$data['User_Workflows'] = $this->Common_Model->select_role_workflows();
		$data['controller'] = $this;
		
		$CustomTablevalues = $this->MyOrders_Model->GetCustomTablevalues($UserUID);
		$CustomTablevalues = $CustomTablevalues[0]->COUNT;
		
		$post = $this->get_customer_post_input_data(); 
		$post['column_search'] = array('OrderNumber','CustomerName','PropertyStateCode','StatusName','OrderEntryDatetime','OrderDueDatetime','LoanNumber','PropertyAddress1','PropertyCityName','PropertyZipcode','PropertyCountyName','OrderCompleteDateTime');
		
		$customer_order = $this->MyOrders_Model->get_myorders_by_cust_id($this->loggedid,$Customer_Check,$post);
		if(!empty($customer_order)){
			$my_orders = $customer_order;
		}	else{
			$my_orders = array();
		}
		
		$data = array();      
		foreach ($my_orders as $cities) {
			$row =  $this->customer_table_data($cities);
			$data[] = $row;
		}
		
		return array(
			'data' => $data,
			'post' => $post
		);
	}
	
	function get_customer_post_input_data(){
		$post['length'] = $this->input->post('length');
		$post['start'] = $this->input->post('start');
		$search = $this->input->post('search');
		$post['search_value'] = $search['value'];
		$post['order'] = $this->input->post('order');
		$post['draw'] = $this->input->post('draw');
		return $post;
	}
	
	/*Function to get orders for Customer Login*/
	function customer_table_data($cities)
	{
		$Workflowassigned = $this->MyOrders_Model->get_Workflowassigned($cities['OrderUID']);
		$completed_workflowstatus = $this->Common_Model->completed_status_order($cities['OrderUID']);
		$onholdworkflow = $this->Common_Model->get_onholdWorkflow($cities['OrderUID']);
		$Borrowername = $this->Common_Model->Get_borrowers_by_OrderUID($cities['OrderUID']);
		
		$row = array();
		$row[] = $cities['CustomerName'];
		$row[] = substr($cities['ProductName'], 0, 1).'-'.$cities['SubProductName'];
		$row[] = '<a href="'.base_url('Order_Summary/index/'.$cities['OrderUID']).'" class="text-primary">'.$cities['OrderNumber'].'</a>';
		
		if($onholdworkflow->WorkflowModuleName != '') {
			
			$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color:#fff;background: #ff8600;">On-Hold</span>';			
			
		}else{ 
			
			if(!in_array($cities['StatusUID'], array(0,5,49,100,110)))
			{ 
				$assigned = $this->Common_Model->check_order_is_assigned($cities['OrderUID']); 
				if($assigned!=0)
				{  
					$typingin = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($cities['OrderUID'], array(1,2,3),array(0,3)); 
					$search_typing = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($cities['OrderUID'], array(1),array(5)); 
					$typing_review = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($cities['OrderUID'], array(2),array(5)); 
					$tax_review = $this->Common_Model->Check_Order_ParticularWorkflowCompleted($cities['OrderUID'], array(3),array(5)); 
					if($typingin!=0)
					{
						$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: #0259e8">Search in Progress</span>';
					} 
					if($search_typing!=0) {
						$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: #0259e8">Typing in Progress</span>';
					} 
					if($typing_review!=0) {
						$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: #f3c10a">Review in Progress</span>';
					} 
					if($tax_review!=0) {
						$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: #f3c10a">Review in Progress</span>';
					}  
				} else {
					$Status='<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: '.$cities['StatusColor'].'">'.$cities['StatusName'].'</span>';
				}
			} else {
				$Status='<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: '.$cities['StatusColor'].'">'.$cities['StatusName'].'</span>';
			} 
			
		} 
		
		$row[] = $Status;
		$row[] = $cities['OrderEntryDatetime']; 
		$row[] = $cities['LoanNumber']; 
		$row[] = $Borrowername->BorrowerNames; 
		
		$row[] = $cities['PropertyAddress1'];
		$row[] = $cities['PropertyCityName'];
		$row[] = $cities['PropertyCountyName'];
		$row[] = $cities['PropertyStateCode'];
		$row[] = $cities['PropertyZipcode'];
		$row[] = $cities['OrderDueDateTime'];
		$row[] = $cities['OrderCompleteDateTime'];
		
		$Action = '<span style="text-align: center">
		<a class="btn btn-default" data-toggle="confirmation" data-title="Accept Order?" data-placement="top" href="'.base_url('my_orders/preview/'.$cities['OrderUID']).'" ><span class="glyphicon glyphicon-pencil"></span></a><button  data-OrderUID = "'.$cities['OrderUID'].'" class="btn btn-default cancel_order"><span class="glyphicon glyphicon-remove"></span></button></span>';
		
		$row[] = $Action;
		return $row;
	}
	
	/*Function to get orders for vendors refers to logged users*/
	function get_vendor_login_orders($post_data,$is_vendorlogin){
		
		$logged_details = $this->Common_Model->get_logged_details();
		
		$vendors  = $this->MyOrders_Model->get_vendors($logged_details,$this->loggedid);
		
		$vendoruids = $this->MyOrders_Model->get_vendor_uids($vendors);
		
		$vendor_groups  = $this->MyOrders_Model->get_vendor_groups($is_vendorlogin,$vendoruids,$this->loggedid);
		
		if(!empty($vendor_groups)){
			
			$vendor_users  = $this->MyOrders_Model->get_vendor_users($is_vendorlogin,$this->loggedid,$vendor_groups[0]->GroupUID,$vendors);
			$customer_ingroup_by_vendors = $this->MyOrders_Model->get_customer_ingroup_by_vendors($vendor_groups[0]->GroupUID);
			$customer_uids =  $this->MyOrders_Model->get_customeruid_format($customer_ingroup_by_vendors);
			
			if(!empty($vendors)){
				$data = $this->MyOrders_Model->get_vendor_getnextorder($this->loggedid,$post_data,$customer_uids,$vendors[0]->VendorUID);
				return $data;
			}else{
				return array("message"=>"No Vendors","status"=>"danger","data"=>"",'Error'=>'1');
			}
		}else{
			return array("message"=>"No Vendor in group","status"=>"danger","data"=>"",'Error'=>'1');
			
		}
		
		return array("message"=>"Error","status"=>"danger","data"=>"",'Error'=>'1');
		
		
	}
	
	/*-----AUDIT TRAIL HISTORY ---*/
	function get_order_history(){
		
		$this->load->model('audit_trail/audit_trail_model');
		$OrderUID = $this->input->post('OrderUID');
		$html = '';
		$Status = '';
		
		if($OrderUID){
			
			$assignedorder = $this->audit_trail_model->get_assignedorderdetails($OrderUID);
			$order_details = $this->audit_trail_model->get_orderdetails($OrderUID);
			$onholdworkflow = $this->Common_Model->get_onholdWorkflow($OrderUID);
			if($onholdworkflow->WorkflowModuleName != '') {
				
				$Status = '<span class="btn btn-rounded btn-sm" style="font-size: 10px; color:#fff; background: #ff8600;">'.$onholdworkflow->WorkflowModuleName.'-OnHold</span>';
				
			}else{ 
				
				$Status = ' <span class="btn btn-rounded btn-sm "  style="font-size: 8pt; color: #fff; background: '.$order_details->StatusColor.'">'.$order_details->StatusName.'</span> ';
				
			} 
			
			$date=Date('d-m-Y H:i:s'); 
			$DueDate=$assignedorder[0]->OrderDueDatetime; 
			$DueDate = ($DueDate == NULL) ? $DueDate : Date('d-m-Y H:i:s',strtotime($DueDate));
			
			if($DueDate < $date){
				$value='<span style="color:#ea4335;">(Due by'.' '.$DueDate.')</span>';
			}
			
			if($DueDate > $date){
				$value='<span style="color:#34a853;">(Due by'.' '.$DueDate.')</span>';
			}
			
			if($DueDate == $date){
				$value='<span style="color:#ffa500;">(Due by'.' '.$DueDate.')</span>';
			}
			
			
			$html .= '<span class="font-bold">Order # '.$order_details->OrderNumber.  '-'.$value.'</span>
			<div class="pull-right">
			'.$Status.'
			</div>
			<span class="panel-subtitle" style="">
			<input type="hidden" value="'.$OrderNumber->OrderUID.'" id="OrderUID">
			
			<div class="style_css" id="table1_filter" class="dataTables_filter"></div></span>
			</div>
			
			
			</div> 
			
			<table  id="test" style="width: 100%;font-size:11px;" class="table table-striped table-borderless history-table">
			<thead>
			<tr>
			<th>WorkFlowModule</th>
			<th>Assigned User</th>
			<th>AssignedDateTime</th>
			<th>Status</th>
			<th>OnholdDateTime</th>
			<th>CompletedDateTime</th>
			</tr>
			</thead>
			
			<tbody>';
			
			foreach($assignedorder as $d){ 
				
				if($d->AssignedToUserUID == NULL || $d->SendToVendor == '1'){
					$Workflow_User = strtok($d->VendorName, ' ');
				} else{
					$Workflow_User = $d->UserName;
				}
				
				
				$html	.='<tr>
				<td style="padding-left:29px;">
				'.$d->WorkflowModuleName.'                   
				</td>
				
				<td>
				'.$Workflow_User.'                    
				</td>
				
				<td>  
				'.$d->AssignedDatetime.' 
				</td>
				
				<td>';
				
				
				if(($d->AssignedToUserUID == NULL || $d->SendToVendor == '1') && ($d->WorkflowStatus=='5')){
					if($d->QCCompletedDateTime!=''){
						$html .="Completed"; 
					}else{
						$html .="QC In Progress"; 
					}
				}else{
					if($d->WorkflowStatus=='3'){ 
						$html .="Work In Progress"; 
					} 
					
					if($d->WorkflowStatus=='5'){  
						$html .="Completed"; 
					}  
					
					if($d->WorkflowStatus=='4'){
						$html .="OnHold";
					}
					
					if($d->WorkflowStatus=='0'){
						$html .="Assigned";
					}
				} 
				
				$html .='</td>
				
				<td>';
				
				if($d->OnholdDateTime!='00-00-0000 00:00:00' && $d->WorkflowStatus=='5'){  
					$html .=$d->OnholdDateTime;  
				}  
				
				$html .='</td>
				
				<td>';
				
				if($d->AssignedToUserUID == NULL ||$d->SendToVendor == '1'){ 
					if($d->QCCompletedDateTime!=''){  
						$html .=$d->CompleteDateTime;  
					}
				}else{
					if($d->WorkflowStatus=='5' && $d->CompleteDateTime!='00-00-0000 00:00:00'){
						$html .=$d->CompleteDateTime;
					}
				} 
				
				$html .='</td>                  
				
				</tr>'; 
				
			}  
			
			$html .='</tbody>
			</table>';
			
			echo $html;exit;
		}
		echo $html;exit;	
	}    	  
	
}?>
