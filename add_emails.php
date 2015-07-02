<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Add Emails';


// ************ Including Templates **********

include_once(TD.'header.php');

// ************ Processing Form **********

if(isset($_POST['emails'])){
    $emails = $_POST['emails'];
    if(!empty($emails)){
        $em = new SMAIL();
        $R = $em->add_emails($emails);
    }
        
}

// ************ Rendering the Email manager **********


?>
<div class="jumbotron cc">
    <div class="page-header">
		<h2>Add Emails</h2>
	</div><?php if(!empty($R[2])) {?>
    <div class="alert alert-success">
        <?php echo " $R[2] emails added successfully !"; ?>
    </div>
        <?php } ?>
	<?php if(!empty($R[3])) {?>
    <div class="alert alert-warning">
        <?php echo " $R[3] emails exist already !"; ?>
    </div>
        <?php } ?>
    <?php if(!empty($R[1])) {?>
    <div class="alert alert-danger">
        <?php echo " $R[1] emails were not valid."; ?>
    </div>
        <?php } ?>
    <form name="f1" method="post" action="add_emails.php">
        <p>
        	<textarea name="emails" placeholder="Insert your email addresses here..." style="width: 55%;min-height: 300px;margin: 0px;font-size: 18px;"></textarea>
        </p>
    <p>
        <input type="submit" value="Submit" class="btn btn-primary">
    </p>
    </form>
</div>


<?php

include_once(TD.'footer.php');


?>