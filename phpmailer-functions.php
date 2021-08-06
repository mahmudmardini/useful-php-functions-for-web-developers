<?php
/*
be sure that you import PHPMailer class
to files you want to use these functions there using the following line of code:
include 'phpmailer_class/class.phpmailer.php';
*/

// send an email
function send_email($name, $email, $subject, $message){
    $mail = new PHPMailer;
    $mail->IsSMTP();								//Sets Mailer to send message using SMTP
    $mail->Host = '';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
    $mail->Port = '587';								//Sets the default SMTP server port
    $mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
    $mail->Username = '';					//Sets SMTP username
    $mail->Password = '';					//Sets SMTP password
    $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
    $mail->From = '';					//Sets the From email address for the message
    $mail->FromName = '';				//Sets the From name of the message
    $mail->AddAddress($email, $name);		//Adds a "To" address
    $mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
    $mail->IsHTML(true);							//Sets message type to HTML
    $mail->CharSet = 'UTF-8';         // set utf-8 character encoding
    $mail->Subject = $subject;				//Sets the Subject of the message
    $mail->Body = $message;				//An HTML or plain text message body
    if($mail->Send())								//Send an Email. Return true on success or false on error
    {
      return true;
    }
    else
    {
      return false;
    }
  }


// request an email
function request_email($name, $email, $subject, $message){
  $mail = new PHPMailer;
  $mail->IsSMTP();							//Sets Mailer to send message using SMTP
  $mail->Host = '';		          //Sets the SMTP hosts of your Email hosting, this for Godaddy
  $mail->Port = '587';					//Sets the default SMTP server port
  $mail->SMTPAuth = true;				//Sets SMTP authentication. Utilizes the Username and Password variables
  $mail->Username = '';					//Sets SMTP username
  $mail->Password = '';					//Sets SMTP password
  $mail->SMTPSecure = '';				//Sets connection prefix. Options are "", "ssl" or "tls"
  $mail->From = $email;					//Sets the From email address for the message
  $mail->FromName = $name;			//Sets the From name of the message
  $mail->AddAddress('your email', 'your name');		//Adds a "To" address
  $mail->WordWrap = 50;					//Sets word wrapping on the body of the message to a given number of characters
  $mail->IsHTML(true);					//Sets message type to HTML
  $mail->Subject = $subject;		//Sets the Subject of the message
  $mail->Body = $message;				//An HTML or plain text message body
  $mail->CharSet = 'UTF-8';     // set utf-8 character encoding
  if($mail->Send()){
    return true;
  }else{
    return false;
  }
}
 ?>
