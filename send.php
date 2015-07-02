<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer sending';


// ************ Including Templates **********

//include_once(TD.'header.php');

// ************ Processing  **********

$SMAIL = new SMAIL();
$SMAIL->log('starting the sending process now.',0);

$servers = $SMAIL->get_servers(); // Gather the servers first
// Check if an hour passed and reset the remaining
$arr = array();
foreach($servers as $server){
    $SMAIL->check_if_an_hour_passed($server);
}
$servers = $SMAIL->get_servers(); // Gather the servers after the check_if_an_hour_passed
$servers = array_filter($servers,function ($v){ return $v['remaining'] !== '0'; });sort($servers);

if(!empty($servers)){
    $servers_count = count($servers);
    for($i=0;$i<$servers_count;$i++){
        $email_adresses = $SMAIL->list_emails(0,$servers[$i]['remaining'],true);
        if(!empty($email_adresses))
        	$sent = $SMAIL->send_emails($servers[$i],$email_adresses);
        else{
            $sent = 0;
             $SMAIL->log('No emails available.');
        }
        if($sent) $SMAIL->update_server_TaR(array($servers[$i]['id'],$servers[$i]['remaining']  - $sent ));
        }
}else{
    $SMAIL->log('No servers available.');
}


?>