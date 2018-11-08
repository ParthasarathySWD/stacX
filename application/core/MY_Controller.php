<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$logged_in = $this->session->userdata('UserUID');
		if ($logged_in != TRUE || empty($logged_in)){

			$allowed = array('Login');
			if ( ! in_array($this->router->fetch_class(), $allowed))
			{
				if ($this->input->is_ajax_request()) {
				?>
					<script>
						window.location.href='<?php echo base_url("Login"); ?>';
					</script>
				<?php
				exit;
				}
				else{
					redirect(base_url('Login'));
				}
			}
		}
		else{

			$this->loggedid = $this->session->userdata('UserUID');
			$this->UserName = $this->session->userdata('UserName');
			$this->RoleUID = $this->session->userdata('RoleUID');
			
		}
	}



}?>

