<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Edit your email';


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
		<h2>Edit Emails</h2>
	</div><?php if($R == '1') {?>
    <div class="alert alert-success">
        <?php echo " Updated successfully !"; ?>
    </div>
        <?php } ?>
    <?php if(!$R) {?>
    <div class="alert alert-danger">
        <?php echo "  Error Occured!"; ?>
    </div>
        <?php } ?>
    <form name="f1" method="post" action="email.php">
        <p><label>Email Title</label>
        	<input type="text" value="<?php echo $email_title; ?>" name="email_title" size="50">
        </p>
        <label>Email Body HTML</label>
        <p>
        	<textarea name="email_body_html"  style="width: 55%;min-height: 300px;margin: 0px;font-size: 18px;"><?php echo $email_body_html; ?></textarea>
        </p>
        <label>Email Body non-HTML</label>
        <p>
        	<textarea name="email_body_text"  style="width: 55%;min-height: 300px;margin: 0px;font-size: 18px;"><?php echo $email_body_text; ?></textarea>
        </p>
    <p>
        <input type="submit" value="Save" class="btn btn-primary">
    </p>
    </form>
</div>


<?php

include_once(TD.'footer.php');


?>