<?php

/*********************************************
 * 	Bulk Mailer Script by Seif Sgayer		 *
 * 09/08/2014 seifeddine@sghaier.me          *
 * *******************************************/


// ************ calling required files ************
//require('login.php?checking=true');


require_once('globals.php');


// ************ Setting global variables **********

$page_title = 'Bulk Mailer Home Page';


// ************ Including Templates **********

include_once(TD.'header.php');

// ************ Rendering the home page **********


?>
<div class="container" role="main" style="margin: auto;position: absolute;top: 0;right: 0;left: 0;bottom: 0;height: 333px;">
    <div class="jumbotron">
        <h1>Welcome!</h1>
        <p>The bulk mailer script allows you to send your email and promote your business online automatically and non stop, it also gives you the feature to rotate smtp servers !</p>
        <p><a href="manage.php" class="btn btn-primary btn-lg" role="button">Start managing your emails now !</a></p>
    </div>
</div>

<?php

include_once(TD.'footer.php');


?>