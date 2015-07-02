<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Servers Manager';


// ************	The form 	**********

$SMAIL = new SMAIL();
if(isset($_POST['new_server'])){
    $SMAIL->add_server($_POST['name'],$_POST['host'],$_POST['username'],$_POST['password'],$_POST['encryption'],$_POST['port'],$_POST['hourly']);
}
$servers = $SMAIL->get_servers(); 
/*print_r($servers);
exit();*/

// ************ Including Templates **********

include_once(TD.'header.php');

// ************ Rendering the Email manager **********


?>
<div class="jumbotron cc">
    <div class="page-header">
		<h2>Servers Manager</h2>
	</div>
    	<div class="row">
        <div class="col-md-6">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Host</th>
                <th>Username</th>
                <th>Password</th>
                <th>Encryption</th>
                <th>Port</th>
                <th>Hourly limit</th>
              </tr>
            </thead>
            <tbody>
				<?php
					if(!$servers){
                        echo '<tr><h3>No servers found.</h3></tr>';
                    }else{
                        foreach($servers as $server){
                            echo "<tr>"; 
                            $server = array_filter($server,function($v){ return (!empty($v)); });
                                foreach($server as $key=>$details){
                                    if($key != 'id')
                                    echo "<td><input type='text' name='".$server['id'].'-'.$key."'' value='$details' ></td>";
                                }
                            echo "<td><a href='servers.php?del=".$server['id']."'>Delete</a></td> </tr>";
                        }
                    }

                ?>
            </tbody>
          </table>
        </div>
     </div>
	<form action="servers.php" method="post">
    	<labled>Add new server</labled>
        <p>
        	<input type="text" size="25" name="name" placeholder="Server name">
            <input type="text" size="25" name="host" placeholder="host">
            <input type="text" size="25" name="username" placeholder="username">
            <input type="text" size="25" name="password" placeholder="password">
            <input type="text" size="25" name="encryption" placeholder="encryption">
            <input type="text" size="25" name="port" placeholder="port">
            <input type="text" size="25" name="hourly" placeholder="hourly max emails">
            <input type="submit" value="Submit" name="new_server">
        </p>
    </form>
</div>
<style>

    input{
        font-size: 12px;
    }

	.container, .jumbotron{
        padding: 0;
        margin: 0;
	}
    body{
        background-image: none;
    }
    .jumbotron{
        position: absolute;
        left:0;
    }
</style>
<?php

include_once(TD.'footer.php');


?>