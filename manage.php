<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Manager';


// ************ Including Templates **********

include_once(TD.'header.php');

// ************	The listing form 	**********

$emails_in = new SMAIL();
$emails = $emails_in->list_emails();
$emails_count = count($emails);
$showall = isset($_GET['showall']) ? '1' : '0';
$s = isset($_GET['s']) ? $_GET['s'] : '0';
$e = isset($_GET['e']) ? $_GET['e'] : '25';
if($e == 0) $e = 25;
if($showall){$e = 0;$s=0;}


// ************ Rendering the Email manager **********


?>
<div class="jumbotron cc">
    <div class="page-header">
		<h2>Email Manager</h2>
	</div>
    <form method="post" action="process.php" onsubmit="return confirm('Do you really want to continue the process?');">
    	<p>
        <button type="button" onclick="window.location='add_emails.php'" class="btn btn-primary">Add</button>
        <input type="submit" name="edit" class="btn btn-default" value="Edit">
        <input type="submit" name="delete" class="btn btn-danger" value="Delete">
   
            <button type="button" onclick="window.location='manage.php?showall=1'" class="btn btn-sm btn-default">Show All</button>
            <span>show: </span>
            <select name="s" id="sel" onchange="window.location='manage.php?<?php echo "s=$s"; ?>&e='+document.getElementById('sel').value">
                <?php 
					$arr = array('25','50','100','250');
					foreach($arr as $ar){
                        echo "<option value='$ar' ";
                        if($ar == $e)
                            	echo 'selected ';
                        echo "> $ar </option>";
                    }
				?>
            </select>
        	<?php
				if($s > 0 ){
                    $bs = $s-$e;
                    echo "<a href='manage.php?s=0&e=$e'> << </a>";
                    echo "<a href='manage.php?s=$bs&e=$e'> < </a>";
                }
					$fs = $s+$e;
				if($s+$e < $emails_count ){
                     echo "<a href='manage.php?s=$fs&e=$e'> > </a>";
                    $bla = $emails_count - $e;
                    echo "<a href='manage.php?s=$bla&e=$e'> >> </a>";
                }
             ?>
      </p>
    	<div class="row">
        <div class="col-md-12">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Email</th>
                <th>Sent</th>
                <th>Control <input type="checkbox" name="selectall" id="selectall" /></th>
              </tr>
            </thead>
            <tbody>
				<?php 
					$emails= $emails_in->list_emails($s,$e);
                	foreach($emails as $email){
                        echo "<tr>"; 
                        	foreach($email as $details){
                                echo "<td>$details</td>";
                            }
                        echo "<td><input type='checkbox' name='checkz[]' value='" . $email['id']."'></td> </tr>";
                    }
                ?>
            </tbody>
          </table>
        </div>
     </div>
	</form>
</div>
<script>

    $('#selectall').click(function(event) {   
        if(this.checked) {
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        }else{
            $(':checkbox').each(function() {
                this.checked = false;                        
            });
        }
    });

</script>
<?php

include_once(TD.'footer.php');


?>