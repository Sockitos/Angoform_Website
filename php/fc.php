<?php
namespace PortoContactForm;

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';


// Destination email address
$email = 'zemtep@gmail.com';

// If honeypot is changed exit
if(!empty($_POST['email2'])){
	// or take other measures.
	die();
}
// Get values from user
// Using htmlspecialchars to escape input

foreach($_POST as $label => $value) {
	$label = ucwords($label);

	if( $label == 'Name' ) {               
		$label = 'Nome';
		$fromName = htmlspecialchars($value, ENT_QUOTES);               
	}

	if( $label == 'Subject' ) {               
		$label = 'Assunto';
		$subject = htmlspecialchars($value, ENT_QUOTES);              
	}

	if( $label == 'Message' ) {               
		$label = 'Mensagem';
		$message_text = htmlspecialchars($value, ENT_QUOTES);               
	}

	if( $label == 'Email' ) {               
		$label = 'Email';
		$email_m = htmlspecialchars($value, ENT_QUOTES);               
    }

	//$message .= "<b>" . $label.":</b> " . htmlspecialchars($value, ENT_QUOTES) . "<br>\n";
}

//Create html body for email
$message_h = file_get_contents('templates/contact_form.html');
$message = str_replace("$", $message_text, $message_h);


$mail = new PHPMailer(true);
try {
	$mail->SMTPDebug = 0;                                      // Debug Mode	

	$mail->IsSMTP();                                           // Set mailer to use SMTP
	$mail->Host = 'angoform.com';                              // Specify main and backup server
	$mail->SMTPAuth = true;                                    // Enable SMTP authentication
	$mail->Username = 'testes@angoform.com';                   // SMTP username
	$mail->Password = 'Testesdeseguranca123';                  // SMTP password
	//$mail->SMTPSecure = 'tls';                               // Enable encryption, 'ssl' also accepted
	$mail->Port = 587;   								                       // TCP port to connect to

	$mail->IsHTML(true);                                       // Set email format to HTML
	$mail->CharSet = 'UTF-8';

	$mail->AddAddress($email);	 						                
	$mail->SetFrom($email_m, $fromName);
	$mail->Subject = $subject;
	$mail->msgHTML($message, __DIR__);

	$mail->Send();
	$arrResult = array ('response'=>'success');

} catch (Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->errorMessage());
} catch (\Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}

