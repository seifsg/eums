<?php
Class SMAIL{
    private $_db;
    public function __construct(){
        require_once('configs.php');
        try{
            $this->_db = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USERNAME, PASSWORD);
            $this->_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }
        catch(PDOException $e){
            die('mysql error '.$e);
        }  
        date_default_timezone_set('Africa/Tunis');
    }
    
    public function list_emails($s=0,$e=25,$sending = false){
        $sql_query = "SELECT * FROM emails ";
        if($sending)   $sql_query .= ' WHERE sent = 0 ';
        $sql_query .= " ORDER BY id ASC "; $s= (int)$s; $e= (int)$e;
        $sql_query .= " LIMIT $s , $e ";
        $all_emails = $this->_db->prepare($sql_query);
        /*$all_emails->bindParam(':starting',$s,PDO::PARAM_INT);
        $all_emails->bindParam(':ending',$e,PDO::PARAM_INT);*/
        $result = $all_emails->execute();
        $all_emails = $all_emails->fetchALL(PDO::FETCH_ASSOC);
        return $all_emails;
    }
    public static function emails_to_array($emails){
        $emails = trim($emails);
        $emails = explode(',',$emails);
        $count_emails = count($emails);
        for($i = 0;$i<$count_emails;$i++){
            $emails[$i] = trim($emails[$i]);
            if(empty($emails[$i])) unset($emails[$i]);
        }
        //print_r($emails);
        return $emails;
    }
    public function verify_emails($emails){
        $invalid_emails_count = 0; 
        $exists = 0;
        require_once('PHPMailer-master/class.phpmailer.php');
        $emails = self::emails_to_array($emails);
        $count_emails = count($emails);
        for($i = 0;$i<$count_emails;$i++){
            if(!PHPMailer::validateAddress($emails[$i])){
                $invalid_emails_count++;
               unset($emails[$i]);
            }
        }
        for($i=0;$i<$count_emails;$i++){
            $exists_in = $this->_db->prepare("SELECT COUNT(*) FROM emails WHERE email = '$emails[$i]'");
            $result = $exists_in->execute();
            $exists_in = $exists_in->fetchALL(PDO::FETCH_ASSOC);
            $exists_in = implode('',$exists_in[0]);
            if($exists_in>0){
                $exists++;
                unset($emails[$i]);
            }
            
        }
        usort( $emails, 'strnatcmp' );
        return array($emails,$invalid_emails_count,$count_emails-$invalid_emails_count-$exists,$exists);
    }
    
    public function add_emails($emails){
        $emails = $this->verify_emails($emails);
        if(!empty($emails[0])){
            $add_emails_pdo_query = "INSERT INTO emails VALUES ";
            for($i=0;$i<$emails[2];$i++){
                $add_emails_pdo_query .= "(NULL, '" . $emails[0][$i]. "', 0)";
                if($i < ($emails[2]-1))
                    $add_emails_pdo_query .= ', ';
            }
            $all_emails = $this->_db->prepare($add_emails_pdo_query);
            $result = $all_emails->execute();
            $emails[4] = $result;
            $this->cond_log($emails[2].' emails were added successfully .',$emails[2].' emails were not added successfully .',$result);
        }
        return $emails;
        
    }
    public function delete_email($id){
        $deleting = $this->_db->prepare('DELETE FROM emails WHERE id = ?');
        $result = $deleting->execute(array($id));
        $this->cond_log("Email with id $id was deleted.","Email with id $id was not deleted",$result);
        return $result;
    }
    
    public function email_by_id($id){
        $gn = $this->_db->prepare("SELECT * FROM emails WHERE id = ? LIMIT 1");
        $result = $gn->execute(array($id));
        $gn = $gn->fetchALL(PDO::FETCH_ASSOC);
        if(!empty($gn[0]))
            return $gn[0];
        else
            return false;
    }
    
    public function edit_email($id,$value,$svalue){
        $editing = $this->_db->prepare("UPDATE emails SET email = ?, sent = ? WHERE id = ?");
        $result = $editing->execute(array($value,$svalue,$id));
        $this->cond_log("Email with id $id was edited successfully.","There was a problem editing email with id $id",$result);
        return $result;
    }
    public function get_email(){
        $email = $this->_db->prepare("SELECT * FROM email WHERE id=1");
        $email->execute();
        $email = $email->fetchALL(PDO::FETCH_BOTH);
        return $email[0];
    }
    public function update_email($title,$html,$nohtml){
        $html = htmlspecialchars($html);
        $editing = $this->_db->prepare("UPDATE email SET title = ?, html = ?, nohtml = ?  WHERE id = 1");
        $result = $editing->execute(array($title,$html,$nohtml));
		$this->cond_log($title.'Email updated successfully.','There was a problem updating the email!',$result);
        return $result;
    }
    public function get_servers(){
        $servers = $this->_db->prepare("SELECT * FROM servers");
        $servers->execute();
        $servers = $servers->fetchALL(PDO::FETCH_ASSOC);
        return (empty($servers[0])) ? false:$servers;
    }
    public function add_server($name='',$host='',$username='',$password='',$encryption='',$port=0,$hourly=0){
        $server = $this->_db->prepare("INSERT INTO servers VALUES(NULL,?,?,?,?,?,?,?,?,NULL)");
        $R = $server->execute(array($name,$host,$username,$password,$encryption,$port,$hourly,$hourly));
        $this->cond_log($name.' server was added successfully .',$name.' Server was not added.',$R);
        return $R;
    }
    public function delete_server($id){
        $deleting = $this->_db->prepare('DELETE FROM servers WHERE id = ?');
        $result = $deleting->execute(array($id));
        $this->cond_log("server with id $id was deleted","server with id $id was not deleted",$result);
        return $result;
    }
    
    public function check_if_an_hour_passed($server){
        // If we still have some remaining emails to send or there is no last sent time
        if( ($server['last_sent'] == '') || ($server['remaining'] > 0 ))
            return false;
        
       	// Let's see if the time passed so we can reset the remaining

       		 $now = new DateTime();
             $then = new DateTime($server['last_sent']);
             $diff = $now->diff($then);
             if($diff->format('%h') <= 0 )
                 return false;
        	 $resetting_the_remaining = $this->_db->prepare('UPDATE servers SET remaining = ? WHERE id = ?');
        	 $R = $resetting_the_remaining->execute(array($server['hourly'],$server['id']));
        	$this->cond_log('server id '.$server['id'].' is now available again','server id '.$server['id'].' is not available.',$R);
        return true;
        
    }
    public function update_server_TaR($server){
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $the_query = $this->_db->prepare('UPDATE servers SET remaining = ?, last_sent = ? WHERE id = ? ');
        $execute = $the_query->execute(array($server[1],$now,$server[0]));
        $this->cond_log('server id '.$server[0].' is still available','server id '.$server[0].' is going to be unavailable for an hour',$server[1]);
        return $execute;
    }
    public function log($message,$type=0){
        switch($type){
            case 0: $type = 'info'; break;
            case 1: $type = 'error'; break;
        }
        $logging = $this->_db->prepare('INSERT INTO logs VALUES(NULL,?, ?,?)');
        $execute = $logging->execute(array(date('Y-m-d H:i:s'),$message,$type));
    }
    public function cond_log($message0,$message1,$cond){
        if($cond) $this->log($message0,0);
        else	$this->log($message1,1);
    }
    
    public function mark_sent($email_id){
        $marking = $this->_db->prepare('UPDATE emails SET sent = 1 WHERE id = ?');
        $executing = $marking->execute(array($email_id));
    }
    
    public function send_emails($server,$emails){
        require_once('PHPMailer-master/PHPMailerAutoload.php');
        
        //Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;


        //Set the hostname of the mail server
        $mail->Host = $server['host'];

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = $server['port'];

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure =  $server['encryption'];

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username =  $server['username'];

        //Password to use for SMTP authentication
        $mail->Password =  $server['password'];

        // Get the email that we are going to send
        $the_email_to_send = $this->get_email();
            
        //Set who the message is to be sent from
        $mail->setFrom( $server['username'] , 'TeamTreeHouse Promoter');

        //Set the subject line
        $mail->Subject = $the_email_to_send['title'];

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(html_entity_decode($the_email_to_send['html']));

        //Replace the plain text body with one created manually
        $mail->AltBody = $the_email_to_send['nohtml'];

        
        $not_sent = 0;
        $sent = 0;
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        for($i = 0; $i<count($emails);$i++){
            //Set who the message is to be sent to
            $mail->addAddress($emails[$i]['email'], '');
            //send the message, check for errors
		
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
                $this->log("Mailer Error: " . $mail->ErrorInfo,1);
                $not_sent++;
            } else {
                echo "Message sent!";
                $this->log("Messages sent ! ");
                $sent++;
                $this->mark_sent($emails[$i]['id']);
            }
            
             $mail->ClearAddresses();
        }

            

	return $sent-$not_sent;
          
        
    }
}

?>