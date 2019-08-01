<?
class MailingController extends BaseController
{
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/


	public function register()
	{

		if($this->model->email_exists($_REQUEST['email_address']))
			Render::return_ajax_error("Endereço de email já consta em nosso banco de dados! Obrigado!");
		else
		{

			parent::save("", false);

			Render::return_ajax_success("Obrigado! Você já fará parte de nosso próximo mailing!");

		}

	}

	public function send_contact()
	{		
		
		$body = 'Mensagem recebida pelo site.';
		
		foreach ($_POST as $key => $value)
		{
			if(strpos($key, "mail") > -1)
				$email    = Input::validate($value, 0, 100, 0, "EMAIL", "E-Mail", "");
				
			if(strpos($key, "nome") > -1 || strpos($key, "name") > -1)
				$name    = $value;
			
			$body .= "<br>";
			$body .= (!is_array($value)) ? (ucwords(str_replace("_", " ", $key)) . " : " . $value) : ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value);
		}
		
		if (Input::$error_code == 1)
			die(json_encode(array("success" => false, "message" => Input::$error_msg, "div_class" => "error")));
		
		
		$mail = new PHPMailer();
		//$mail->IsSmtp();
		$mail->Host = SMTP_SERVER; 
		$mail->From = SMTP_FROM;
		$mail->Sender = SMTP_FROM;
		$mail->FromName = $name;
		$mail->Subject = (isset($_REQUEST['subject']) ? $_REQUEST['subject'] : "Mensagem via Website");
		$mail->Body = $body;
		$mail->IsHTML(true);
		$mail->AddAddress("daniel.js@gmail.com");
//		$mail->AddAddress(SMTP_RCPT);
		
		if($mail->Send())
			die(json_encode(array("success" => true, "message" => "Sua mensagem foi recebida com sucesso! Assim que possivel entraremos em contato! Obrigado.", "div_class" => "success")));
		else
			die(json_encode(array("success" => false, "message" => "Houve um erro ao enviar sua mensagem. Tente novamente em instantes.", "div_class" => "error")));
		
	}

	

	public function welcome()
	{

		$user_name    = Input::validate($_POST['tx_email'], 0, 20, 1, "ALPHA", "Email address", "");

		if (Input::$error_code == 1)
			Render::return_ajax_error(Input::$error_msg);

		$user_data = $this->model->user_exists($user_name);
		if(is_array($user_data))
		{
			$new_password = String::make_random_string(8);
			$user_data['tx_password'] = $new_password;

			if($this->model->update($user_data) && $user_data['tx_email'] != '')
			{
				$mail = new PHPMailer();
				//$mail->IsSmtp();
				$mail->Host = SMTP_SERVER; // SMTP server
				$mail->From = SMTP_FROM;
				$mail->Sender = SMTP_FROM;
				$mail->FromName = SMTP_FROM;
				$mail->Subject = "WebMarketing Calendar - Password Recovery";
				$mail->Body = Render::get_file(LIB_ROOT . "/templates/pwd_recovery.php", array('new_password' => $new_password, 'url' => WEB_ROOT, 'user_name' => $user_data['tx_first_name']), true);
				$mail->IsHTML(true);
				$mail->AddAddress($user_data['tx_email']);
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

}

?>