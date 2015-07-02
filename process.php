<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Process';
$SMAIL = new SMAIL();

// ************ Including Templates **********
//print_r($_POST);exit();
include_once(TD.'header.php');


// ************ Deleting Emails **********



if(isset($_POST['delete'])){
    
    foreach($_POST['checkz'] as $id)
    	$SMAIL->delete_email($id);
    
    ?>
	<div class="jumbotron cc">
        <div class="page-header">
            <h2>Deleting Emails</h2>
        </div>
        <p><?php echo count($_POST['checkz']); ?> Emails deleted. Redirecting you to the manager...</p>
            <script>setTimeout(function(){ window.location.replace('manage.php'); },1000);</script>
    </div>
<?php
    
}


if(isset($_POST['edit'])){
    
    ?>
	<div class="jumbotron cc">
        <div class="page-header">
            <h2>Editing Emails</h2>
        </div>
        <form method="post" action="process.php">
    	<div class="row">
        <div class="col-md-12">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Email</th>
                <th>Sent<select id="bla">
                    <option value="none" selected>&nbsp</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    </select></th>
              </tr>
            </thead>
            <tbody>
                
                    
                
<?php
    
		foreach($_POST['checkz'] as $id){
            echo "<tr><td>$id</td>";
            $email = $SMAIL->email_by_id($id);
            echo "<td><input type='text' size='35' value='" . $email['email'] . "' name='$id'></td> ";
            $selected0 = ($email['sent']==0) ? 'selected' : '';
            $selected1 = ($email['sent']==1) ? 'selected' : '';
            echo "<td><select name='s-$id'><option $selected0 value='0'>0</option><option $selected1 value='1'>1</option></select></td></tr>";
            //print_r($email);
        }

?>			
                
                            </tbody>
          </table>
        </div>
     </div>
<input type="submit" value="Submit" name="process_edit" class="btn btn-primary">
</form>
    </div>
<script>

    $("#bla").change(function(){
        var value = document.getElementById('bla').value;
        if(value != 'none')
            $('select').each(function() {
                this.value = value;                        
            });
    });

</script>
<?php
	}



if(isset($_POST['process_edit'])){
    $count = 0;
    foreach($_POST as $key=>$value){
        if(is_numeric($key)){
            if($SMAIL->edit_email($key,$value,$_POST['s-'.$key]))
                $count++;
            
        }
    }
    
    ?>


	<div class="jumbotron cc">
        <div class="page-header">
            <h2>Editing Emails</h2>
        </div>
        <p><?php echo $count; ?> Emails edited. Redirecting you to the manager...</p>
            <script>setTimeout(function(){ window.location.replace('manage.php'); },1000);</script>
    </div>

<?php
    
}



include_once(TD.'footer.php');


?>
