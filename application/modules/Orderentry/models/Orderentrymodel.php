<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Orderentrymodel extends MY_Model { 
	function __construct()
	{ 
		parent::__construct();
	}


	function insert_order($data)
	{

		$date = date('Ymd');

		$this->db->trans_begin();

		$insertdata = new stdClass();

		$insertdata->AltORderNumber = $data['AltORderNumber'];
		$insertdata->LoanNumber = $data['LoanNumber'];
		// $insertdata->LoanAmount = $data['LoanAmount'];
		// $insertdata->CustomerRefNum = $data['CustomerRefNum'];
		$insertdata->CustomerUID = $data['Customer'];
		$insertdata->PropertyAddress1 = $data['PropertyAddress1'];
		$insertdata->PropertyAddress2 = $data['PropertyAddress2'];
		$insertdata->PropertyCityName = $data['PropertyCityName'];
		$insertdata->PropertyStateCode = $data['PropertyStateCode'];
		$insertdata->PropertyCountyName = $data['PropertyCountyName'];
		$insertdata->PropertyZipcode = $data['PropertyZipcode'];
		$insertdata->ProjectUID = $data['ProjectUID'];
		$insertdata->StatusUID = $this->config->item('keywords')['New Order'];;
		$insertdata->OrderEntryDatetime = Date('Y-m-d H:i:s', strtotime("now"));
		// $insertdata->EmailReportTo = $data['EmailReportTo'];
		// $insertdata->AttentionName = $data['AttentionName'];
		// $insertdata->APN = $data['APN'];
		// $insertdata->IsDuplicateOrder = $IsDuplicateOrder;

		$insertdata->OrderNumber = $this->Order_Number();

		$OrderNos = [];
		$insertdata->OrderDueDate = date('Y-m-d H:i:s');
		// $insertdata->OrderDocsPath = 'uploads/Documents/' . $date . '/' . $OrderNo . '/';
		$query = $this->db->insert('tOrders', $insertdata);
		$insert_id = $this->db->insert_id();


		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			$OrderNos[] = $insertdata->OrderNumber;
		}

		$OrderNumbers = implode(",", $OrderNos);
		$Msg = $this->lang->line('Order_Save');
		$Rep_msg = str_replace("<<Order Number>>", $OrderNumbers, $Msg);
		return ['message'=>$Rep_msg, 'OrderUID'=>$insert_id, 'OrderNumber'=>$insertdata->OrderNumber];

	}


	function Order_Number()
	{

		$date = date("y") % 20;

		$id = sprintf("%06d", 0);
		$code="S";
		$lastOrderNo = $code . $date . $id;

		$last_row = $this->db->select('*')->order_by('OrderUID', "DESC")->limit(1)->get('tOrders')->row();
		if (!empty($last_row)) {

			$lastOrderNo = $last_row->OrderNumber;

		}


		$year = substr($date, strpos($lastOrderNo, $date));

		$db_2digitdate = substr($lastOrderNo, strlen($code), strlen($date));


		if ($year == $db_2digitdate) {

			$lastOrderNosliced = substr($lastOrderNo, (strlen($code) + strlen($date)));
			$id = sprintf("%06d", $lastOrderNosliced + 1);
			$OrderNumber = $code . $date . $id;
		} else {
			$id = sprintf("%06d", 1);
			$OrderNumber = $code . $date . $id;

		}

		return $OrderNumber;

	}

}
?>