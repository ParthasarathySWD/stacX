<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ordersummarymodel extends MY_Model { 
	function __construct()
	{ 
		parent::__construct();
	}

	function GettOrders($OrderUID)
	{
		$this->db->select('*');
		$this->db->from('tOrders');
		$this->db->where(array('tOrders.OrderUID'=>$OrderUID));
		$query = $this->db->get();
		return $query->row();
	}

	function GetDocuments($OrderUID)
	{
	    $this->db->select('*');
		$this->db->from('tDocuments');
		$this->db->join('mUsers','mUsers.UserUID = tDocuments.UploadedByUserUID','left');
		$this->db->where(array('tDocuments.OrderUID'=>$OrderUID));
		$query = $this->db->get();
		return $query->result();
	}


	function insert_order($data)
	{

		$date = date('Ymd');

		$this->db->trans_begin();

		$insertdata = new stdClass();

		$insertdata->AltORderNumber = $data['AltORderNumber'];
		$insertdata->LoanNumber = $data['LoanNumber'];
		// $insertdata->LoanAmount = $data['LoanAmount'];
		$insertdata->CustomerRefNumber = $data['CustomerRefNum'];
		$insertdata->CustomerUID = $data['Customer'];
		$insertdata->PropertyAddress1 = $data['PropertyAddress1'];
		$insertdata->PropertyAddress2 = $data['PropertyAddress2'];
		$insertdata->PropertyCityName = $data['PropertyCityName'];
		$insertdata->PropertyStateCode = $data['PropertyStateCode'];
		$insertdata->PropertyCountyName = $data['PropertyCountyName'];
		$insertdata->PropertyZipcode = $data['PropertyZipcode'];
		$insertdata->ProjectUID = $data['ProjectUID'];
		$insertdata->StatusUID = $this->config->item('keywords')['New Order'];
		$insertdata->OrderEntryDatetime = Date('Y-m-d H:i:s', strtotime("now"));
		// $insertdata->EmailReportTo = $data['EmailReportTo'];
		// $insertdata->AttentionName = $data['AttentionName'];
		// $insertdata->APN = $data['APN'];
		// $insertdata->IsDuplicateOrder = $IsDuplicateOrder;

		//$insertdata->OrderNumber = $this->Order_Number();

		$OrderNos = [];
		$insertdata->OrderDueDate = date('Y-m-d H:i:s');
		// $insertdata->OrderDocsPath = 'uploads/Documents/' . $date . '/' . $OrderNo . '/';
		$this->db->where(array("tOrders.OrderUID"=>$data['OrderUID']));    
		$query = $this->db->update('tOrders', $insertdata);
		$insert_id = $data['OrderUID'];


		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			$OrderNos[] = $this->db->select('OrderNumber')->from('tOrders')->where('tOrders.OrderUID',$data['OrderUID'])->get()->row()->OrderNumber;
		}

		$OrderNumbers = implode(",", $OrderNos);
		$Msg = $this->lang->line('Order_Update');
		$Rep_msg = str_replace("<<Order Number>>", $OrderNumbers, $Msg);
		return ['message'=>$Rep_msg, 'OrderUID'=>$insert_id, 'OrderNumber'=>$OrderNumbers];

	}
}
?>