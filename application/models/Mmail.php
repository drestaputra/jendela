<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmail extends CI_Model {

	public function kirim_email($to,$to_name,$subjek,$pesan){
		$this->load->library("phpmailer_library");
		$mail = $this->phpmailer_library->load();
		$mail->SMTPDebug = 3;                               
	//Set PHPMailer to use SMTP.
		// $mail->isSMTP();            
	//Set SMTP host name                          
	$mail->Host = "tls://smtp.gmail.com"; //host mail server
	//Set this to true if SMTP host requires authentication to send email
	$mail->SMTPAuth = true;                          
	//Provide username and password     
	// $mail->Username = "almakwacilacap@gmail.com";   //nama-email smtp          
	// $mail->Password = "02825551222";           //password email smtp
	$mail->Username = "almakwanu@gmail.com";   //nama-email smtp          
	$mail->Password = "085777738909";           //password email smtp	
	//If SMTP requires TLS encryption then set it
	$mail->SMTPSecure = "tls";                           
	//Set TCP port to connect to 
	$mail->Port = 587;                                   

	$mail->From = "almakwanu@gmail.com"; //email pengirim
	$mail->FromName = "Biro Umroh Cilacap"; //nama pengirim

	 $mail->addAddress($to, $to_name); //email penerima

	 $mail->isHTML(true);

	$mail->Subject = $subjek; //subject
    $mail->Body    = $pesan; //isi email
        $mail->AltBody = "PHP mailer"; //body email (optional)

        if(!$mail->send()) 
        {
        	return $mail->ErrorInfo;
        } 
        else 
        {
        	return "Message has been sent successfully";
        }
    }


}

/* End of file Mmail.php */
/* Location: ./application/models/Mmail.php */