<?
class AdminController extends BaseController
{

	/*******************************************************************************/
	/* Entry Actions ***************************************************************/

	
	public function request_login()
	{
		Render::loginScreen( (isset($_REQUEST['errormsg']) ? $_REQUEST['errormsg'] : "") );
	}
	
	public function auth()
	{
		$admin_name    = Input::check_alpha($_POST['tx_login']);
		$admin_passwd  = Input::check_alpha($_POST['tx_password']);

		if (Input::$error_code == 1)
			Render::loginScreen(Input::$error_msg);

		$authenticated = $this->model->auth_user($admin_name, $admin_passwd);

		if($authenticated === true)
		{				
			setcookie('saturno_admin_name', $admin_name, time() + (60 * 60 * 24 * 90));
			Log::verbose("Admin $admin_name authenticated");
			Render::toAdminHome();
		}
		else
		{
			Render::loginScreen($authenticated);
			Log::verbose("Admin $admin_name not authenticated");
		}
	}
	
	public function myaccount()
	{
		parent::edit($_SESSION['admin_id']);
	}

	public function logoff()
	{
		//session_destroy();
		$_SESSION["admin_logged"] = 0;
		Render::toHome();
	}

	public function send_password()
	{

		$admin_name    = Input::validate($_POST['tx_login'], 0, 20, 1, "ALPHA", "Email address", "");

		if (Input::$error_code == 1)
			Render::return_ajax_error(Input::$error_msg);

		$admin_data = $this->model->admin_exists($admin_name);
		if(is_array($admin_data))
		{
			$new_password = String::make_random_string(8);
			$admin_data[$module_config['passwd_field']] = $new_password;

			if($this->model->update($admin_data) && $admin_data['tx_email'] != '')
			{
				$mail = new PHPMailer();
				//$mail->IsSmtp();
				$mail->Host = SMTP_SERVER; // SMTP server
				$mail->From = SMTP_FROM;
				$mail->Sender = SMTP_FROM;
				$mail->FromName = SMTP_FROM;
				$mail->Subject = "XXX - Password Recovery";
				$mail->Body = Render::get_file(LIB_ROOT . "/templates/pwd_recovery.php", array('new_password' => $new_password, 'url' => WEB_ROOT, 'admin_name' => $admin_data['tx_first_name']), true);
				$mail->IsHTML(true);
				$mail->AddAddress($admin_data['tx_email']);
				if($mail->Send())
					Render::return_ajax_success("Your password has been sent! Check your email address within few minutes.");
				else
					Render::return_ajax_error("Something went wrong while sending your new password! Please try again within few seconds.");
			}
			else
				Render::return_ajax_error("Something went wrong while setting your new password! Please try again within few seconds.");
		}
		else
			Render::return_ajax_error("This email is not signed up yet.");

	}

	public function changepwd($ajax = "")
	{
		$this->data['admin_id'] = isset( $_REQUEST['a'] ) ? $_REQUEST['a'] : $_SESSION["admin_id"];
		$this->data['ajax_view'] = ($ajax != "") ? true : false;

		if($ajax != "")
			$this->view->layout($this->data, false);
		else
			$this->view->layout($this->data, true);
	}

	public function save($admin_id)
	{			

		if(isset($_POST['tx_password']))
		{
			if(isset($_POST['passwd']))
			{
				$admin_passwd   = Input::validate($_POST['tx_password'], 0, 20, 6, "ALPHA", "Password", "");
				$admin_passwd2  = Input::validate($_POST['passwd'], 0, 20, 6, "ALPHA", "Password Confirmation", "");
				
				if ( $admin_passwd != $admin_passwd2 )
					Render::return_ajax_error("Password and confirmation doesn´t match!");
					
			}
			else
			{
				$admin_passwd   = Input::validate($_POST['passwd'], 0, 20, 6, "ALPHA", "Password", "");

				if (Input::$error_code == 1)
					Render::return_ajax_error(Input::$error_msg);
			}
				
				
		}
			
		parent::save($admin_id);

	}



}

?>