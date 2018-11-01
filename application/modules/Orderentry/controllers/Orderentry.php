<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class OrderEntry extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Orderentrymodel');

	}	

	public function index()
	{
		
		$data['content'] = 'index';

		$data['Customers'] = $this->Common_Model->get('mCustomer', [], ['CustomerUID'=>'ASC'], []);

		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}



	function insert()
	{
		$this->load->library('form_validation');

		$data['content'] = 'index';
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_error_delimiters('', '');


			$this->form_validation->set_rules('Customer', '', 'required');
			$this->form_validation->set_rules('PropertyAddress1', '', 'required');
			$this->form_validation->set_rules('PropertyCityName', '', 'required');
			$this->form_validation->set_rules('PropertyStateCode', '', 'required');
			$this->form_validation->set_rules('PropertyCountyName', '', 'required');
			$this->form_validation->set_rules('PropertyZipcode', '', 'required');
			$this->form_validation->set_rules('ProjectUID', '', 'required');
			$this->form_validation->set_rules('PriorityUID', '', 'required');

			/*LOOP VALIDATION*/


			$this->form_validation->set_message('required', 'This Field is required');

			if ($this->form_validation->run() == true) {

				$OrderDetails = $this->input->post();

				
				$result = $this->Orderentrymodel->insert_order($OrderDetails);


				
				$Path='uploads/OrderDocumentPath/' .$result['OrderNumber'] . '/';

				// Executes entire block when file is uploaded.
				if (isset($_FILES['DocumentFiles']) && count($_FILES['DocumentFiles'])) {
					$this->Orderentrymodel->CreateDirectoryToPath($Path);
					$UploaedFiles = $this->UploadFileToPath($_FILES['DocumentFiles'], $Path);					
					
					$OrderUID=$result['OrderUID'];
	
					foreach ($UploaedFiles as $key => $File) {
						/*Save tDocuments*/
						$tDocuments['DocumentName'] = $File['file_name'];
						$tDocuments['DocumentURL'] = $Path . $File['file_name'];
						$tDocuments['OrderUID'] = $OrderUID;
						$tDocuments['IsStacking'] = isset($OrderDetails['Stacking'][$key]) ? 1 : 0;
						$tDocuments['UploadedDateTime'] = date('Y-m-d H:i:s');
						$tDocuments['UploadedByUserUID'] = $this->loggedid;
						$this->Orderentrymodel->save('tDocuments', $tDocuments);
					}
				}





				$result = array("validation_error" => 0, "id" => '', 'message' => $result['message']);
				echo json_encode($result);

			} else {

				$Msg = $this->lang->line('Empty_Validation');

				$formvalid = [];

				$data = array(
					'validation_error' => 1,
					'message' => $Msg,
					'Customer' => form_error('Customer'),
					'PropertyAddress1' => form_error('PropertyAddress1'),
					'PropertyCityName' => form_error('PropertyCityName'),
					'PropertyStateCode' => form_error('PropertyStateCode'),
					'PropertyCountyName' => form_error('PropertyCountyName'),
					'PropertyZipcode' => form_error('PropertyZipcode'),
					'LoanNumber' => form_error('LoanNumber'),
					'ProjectUID' => form_error('ProjectUID'),
					'PriorityUID' => form_error('PriorityUID'),
				);
				// $datas = array_merge($datas1, $datas2, $formvalid);
				// $Merged = array_merge($datas, $data);
				foreach ($data as $key => $value) {
					if (is_null($value) || $value == '')
						unset($data[$key]);
				}
				echo json_encode($data);
			}
		}
	}


	function text_preview_bulkentry()
	{
		// echo '<pre>';print_r('test');exit;
		if ($this->input->post('bulk_order_details') != '') {
			
			$inputdata = $this->input->post('bulk_order_details');
			$returnvalue = false;
			$orders_yts = 0;
			$duplicate_order_id = 0;
			$duplicate_row = 0;
			$column_empty = 0;
			$element_empty = 0;
			
			
			$CustomerUID = $this->input->post('CustomerUID');
			$ProjectUID = $this->input->post('ProjectUID');
			
			
			
			
			if ($CustomerUID == '' ) {
				echo json_encode(array('error' => '1', 'message' => 'Select the Required Fields'));
				exit;
			}
			
			
			
			
			
			
			$arrayCode = array();
			$rows = explode("\n", $inputdata);
			$rows = array_filter($rows);
			
			
			foreach ($rows as $idx => $row) {
				$row = explode("\t", $row);
				
				//to get rid of first item (the number)
				//comment it if you don't need.
				//array_shift ( $row );
				
				foreach ($row as $field) {
					//to clean up $ sign
					$arrayCode[$idx][0] = '';
					$arrayCode[$idx][1] = '';
					
					$field = trim($field, "$ ");
					
					$arrayCode[$idx][] = $field;
				}
			}
			
			
			
			$ProjectCheck = [];
			
			foreach ($arrayCode as $i => $v) {

				$ProjectCheck[$i] = false;

				$msubproducts = array();
				
				
				if ($CustomerUID!='' && $ProjectUID!='') {
					$CustomerProject=$this->Common_Model->GetCustomerandProject_row($CustomerUID, $ProjectUID);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;					
				}
				elseif ($CustomerUID!='' && $v[2]!='') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				}
				else{
					$ProjectCheck[$i] = false;
				}


				if (count($msubproducts) > 0) {
					
					$mcustomerproducts[$i] = $this->Orderentry_model->get_all_in_customerproduct($CustomerUID, $ProductUID, $SubProductUID);
					
					if (count($mcustomerproducts[$i]) > 0) {
						$SubProduct_check[$i] = true;
						$arrayCode[$i][0] = $mcustomerproducts[$i]->OrderTypeName;
						$arrayCode[$i][1] = "Normal";
						$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
						$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
						$arrayCode[$i][4] = $mcustomerproducts[$i]->SubProductName;
					} else {
						$SubProduct_check[$i] = false;
						$mcustomerproducts[$i] = $this->Orderentry_model->get_customer_product($CustomerUID, $ProductUID);
						$arrayCode[$i][0] = '';
						$arrayCode[$i][1] = '';
						$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
						$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
						$arrayCode[$i][4] = $msubproducts->SubProductName;
					}
				} else {
					$mcustomerproducts[$i] = $this->Orderentry_model->get_customer_product($CustomerUID, $ProductUID);
					$arrayCode[$i][0] = '';
					$arrayCode[$i][1] = '';
					$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
					$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
					$arrayCode[$i][4] = $v[4];
					
				}
			}
			
			$headingcount = 0;
			$counts = 0;
			foreach ($arrayCode as $key => $value) {
				
				
				if (count($value) > $counts) {
					$counts = count($value);
					$headingcount = count(array_chunk($value, 6));
				}
			}
			
			$tableheadcount = $headingcount - 3;
			?>
			<h6 class="panel-heading"><strong>Import Data Preview</strong></h6>
			<div class="col-sm-12">
			<p class="xs-mt-10 xs-mb-10 ">
			<span class="borderradius label pull-right" style="background-color: #AA00FF;">Borrower</span>
			<span class="borderradius label pull-right" style="background-color: #BDA601;">Zipcode</span>
			<span class="borderradius label pull-right" style="background-color: #3502BD;">County</span>
			<span class="borderradius label pull-right" style="background-color: #074D1E;">City</span>
			<span class="borderradius label pull-right" style="background-color: #02BD5A;">State</span>
			<span class="borderradius label pull-right" style="background-color: #757575;">Empty Field</span>
			</p>
			</div>
			
			<div class="col-sm-12">
			<div class="panel panel-default panel-table table-responsive defaultfontsize">
			<table class="table table-striped table-hover"  id="table-bulkorder">
			<thead>
			<tr>
			<th>Order Type</th>
			<th>Priority</th>
			<th>Customer/Client</th>
			<th>Product</th>
			<th>Sub Product</th>
			<th>Loan Number</th>
			<th>Loan Amount</th>
			<th>Property Address</th>
			<th>Property City</th>
			<th>Property County</th>
			<th>Property State</th>
			<th>Property Zip Code</th>
			<th>APN</th>
			<th>Additional Info</th>
			<th>Template</th>
			<th>Email Report to</th>
			<th>Attention Name</th>
			
			
			<?php for ($i = 1; $i <= $tableheadcount; $i++) { ?>
				<th>BorrowerName</th>
				<th>Email</th>
				<th>Home No</th>
				<th>Work No</th>
				<th>Cell No</th>
				<th>Social No</th>
				<?php 
			} ?>
			
			</tr>
			</thead>
			<tbody>
			
			<?php
			
			
			
			foreach ($arrayCode as $i => $a) {
				
				
				
				$count = count($a);
				$field_count = 17;
				
				//for missing fields
				if (count($arrayCode[$i]) >= $field_count) {
					
					if ((count($arrayCode[$i]) + 1) % 6 != 0) {
						?> <tr style="background-color: #757575; color: #fff;"> <?php 
						foreach ($arrayCode[$i] as $key => $value) {
							
							?>
							<td><?php echo $value; ?></td>										
							<?php 
						}
						
						?> </tr> <?php 
						
					} else {
						
						
						
						if ((count($a) >= 17)) {
							
							
							
							$CityName = $a[8];
							
							$StateCode = $a[10];
							$Zipcode = $a[11];
							
							$TemplateName = $a[14];
							$CountyName = $a[9];
							
							
							
							$template = $this->Orderentry_model->get_template($TemplateName);
							
							if ($TemplateName == '') {
								$default_template = $this->common_model->get_defaulttemplate_bycustomerUID($mcustomerproducts[$i]->CustomerUID);
								
								if (count($default_template) > 0) {
									$arrayCode[$i][14] = $default_template->TemplateName;
									$template = $default_template;
								}
							}
							
							/*$mstates = $this->Orderentry_model->get_state($StateCode);
							
							$mcounties = [];
							if($StateCode > 0){
								$mcounties = $this->Orderentry_model->get_county($StateCode,$CountyName);
							}
							
							$mcities = $this->Orderentry_model->get_city($CityName,$a[11]);*/
							
							if ($SubProduct_check[$i] == false) { ?>
								<tr style="background-color: #BF6105; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td ><?php echo $value; ?></td>										
									<?php 
								}
							} else
							
							if ($arrayCode[$i][17] == '') { ?>
								<tr style="background-color: #AA00FF; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td ><?php echo $value; ?></td>										
									<?php 
								}
							} elseif ($StateCode == '') {
								?> <tr style="background-color: #02BD5A; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} elseif ($CountyName == '') {
								?> <tr style="background-color: #3502BD; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
							} elseif ($CityName == '') {
								?> <tr style="background-color: #074D1E; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} elseif ($Zipcode == '') {
								?> <tr style="background-color: #BDA601; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} else {
								?> <tr style="color: #090809;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
							}
							
							
							
							
						} else {
							?> <tr style="background-color: #757575; color: #fff;"> <?php 
							foreach ($arrayCode[$i] as $key => $value) {
								
								?>
								<td><?php echo $value; ?></td>										
								<?php 
							}
							
							?> </tr> <?php 
						}
						
					}
				} else {
					
					?> <tr style="background-color: #757575; color: #fff;"> <?php 
					foreach ($arrayCode[$i] as $key => $value) {
						
						?>
						<td><?php echo $value; ?></td>										
						<?php 
					}
					
					?> 
					</tr> 
					
					<?php 
					
				}
				
			}
			
			?> 
			</tbody>
			
			</table>
			<?php
			
		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Fill the Required Field'));
		}
		
	}	






	/* ----- SUPPORTING FUNCTIONS STARTS ---- */
	function UploadFileToPath($files, $Path)
	{
		if (!file_exists($Path)) {
			if (!mkdir($Path, 0777, true)) die('Unable to create directory');
		}
		
		$config['upload_path'] = $Path;
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = 0;
		$config['overwrite'] = true;
		
		$this->load->library('upload', $config);
		
		$DocumentFiles = [];
		$Errors = [];
		foreach ($files['name'] as $key => $image) {
			$_FILES['DocumentFiles[]']['name'] = $files['name'][$key];
			$_FILES['DocumentFiles[]']['type'] = $files['type'][$key];
			$_FILES['DocumentFiles[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['DocumentFiles[]']['error'] = $files['error'][$key];
			$_FILES['DocumentFiles[]']['size'] = $files['size'][$key];
			
			$fileName = $files['name'][$key];
			
            // $DocumentFiles[] = $fileName;
			
			$config['file_name'] = $fileName;
			
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload('DocumentFiles[]')) {
				$DocumentFiles[] = $this->upload->data();
			} else {
				$this->upload->display_errors();
			}
		}
		return $DocumentFiles;
	}
	/* ----- SUPPORTING FUNCTIONS ENDS ---- */
	
	
}?>
