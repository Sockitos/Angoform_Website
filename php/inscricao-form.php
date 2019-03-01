<?php
/*
Name: 			Contact Form
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	7.1.0
*/

namespace PortoContactForm;

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';


// Set email to send message to --temporary
$email = 'zemtep@gmail.com';

// If the e-mail is not working, change the debug option to 2 | $debug = 2;
$debug = 2;

// Set variables of form
$fromName = ( isset($_POST['name']) ) ? $_POST['name'] : 'O utilizador nao incluiu um Nome.';
$fromEmail = ( isset($_POST['email']) ) ? $_POST['email'] : 'O utilizador nao incluiu um Email.';
$telefone = ( isset($_POST['telefone']) ) ? $_POST['telefone'] : 'O utilizador nao incluiu um Telefone.';
$funcao = ( isset($_POST['funcao']) ) ? $_POST['funcao'] : 'O utilizador nao incluiu um Função.';
$empresa = ( isset($_POST['empresa']) ) ? $_POST['empresa'] : 'O utilizador nao incluiu um Empresa.';
$message_text = ( isset($_POST['message']) ) ? $_POST['message'] : 'O utilizador nao incluiu um Mensagem.';
$curso = $_POST['curso'];
$target_email = $_POST['angomail'];


// Inser form fields in email
$message_h = file_get_contents('templates/inscricao_form.html');

$placeholders = array("$1", "$2", "$3", "$4", "$5", "$6", "$7");
$values   = array($fromName, $fromEmail, $telefone, $funcao, $empresa, $message_text, $curso);
$message = str_replace($placeholders, $values, $message_h);
echo '<script>console.log('.json_encode($message).')</script>';

$mail = new PHPMailer(true);
try {
	$mail->SMTPDebug = $debug;                                 // Debug Mode
	
	// Step 2 (Optional) - If you don't receive the email, try to configure the parameters below:

	$mail->IsSMTP();                                         // Set mailer to use SMTP
	$mail->Host = 'angoform.com';                            // Specify main and backup server
	$mail->SMTPAuth = true;                                  // Enable SMTP authentication
	$mail->Username = 'socks@angoform.com';                  // SMTP username
	$mail->Password = 'TesteAngoform123';                    // SMTP password
	//$mail->SMTPSecure = 'tls';                             // Enable encryption, 'ssl' also accepted
	$mail->Port = 587;   								                     // TCP port to connect to

	$mail->AddAddress($email);	 						                 // Add another recipient
	$mail->SetFrom($fromEmail, $fromName);

	$mail->IsHTML(true);                                     // Set email format to HTML
	$mail->CharSet = 'UTF-8';

	$mail->Subject = 'Pedido de inscrição';
	$mail->msgHTML($message, __DIR__);

	//$mail->Body    = $message;

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