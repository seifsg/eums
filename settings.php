<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Settings';


// ************ Including Templates **********

include_once(TD.'header.php');

// ************ Processing Form **********

$em = new SMAIL();
if(isset($_POST['email_title'])){
        $R = $em->update_email($_POST['email_title'],$_POST['email_body_html'],$_POST['email_body_text']);
}else	$R = 'null';

$email_info = $em->get_email();
$email_title = $email_info[1];
$email_body_html = $email_info[2];
$email_body_text = $email_info[3];



// ************ Rendering the Email manager **********


?>
<div class="jumbotron cc">
    <div class="page-header">
		<h2>Settings</h2>
	</div>
    <p><a href="servers.php" >Manage Servers</a></p>
    <p><a href="send.php" >Send Emails now</a></p>
</div>


<?php

include_once(TD.'footer.php');


?>