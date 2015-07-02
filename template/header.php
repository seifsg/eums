<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="template/main.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <style>
      	body{background-image: url('http://farm1.staticflickr.com/55/112451289_fffe8bced7_o.jpg');background-size: cover;background-repeat: no-repeat;}
      </style>
  </head>
  <body role="document">
          <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Bulk Mailer Script</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
              <?php 
              	$arr = array('Home'=>'index.php','Manage Contacts'=>'manage.php','Edit the current Email'=>'email.php','Settings'=>'settings.php','About the script'=>'about.php');
				foreach($arr as $key=>$value){
                    echo "<li";
                    if($value ==basename($_SERVER['SCRIPT_NAME'])) echo " class = 'active' ";
                    echo "><a href='$value'>$key</a></li>";
                }
              ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div class="container" role="main">