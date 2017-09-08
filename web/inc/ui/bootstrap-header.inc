<?php 

    use \com\yuktix\lake\auth\Login as Login;
    $login = Login::tryLoginInSession();
    $gMDLMenus = array();

    
    array_push($gMDLMenus, ["name" => "Website", "link" => "#" ]) ;
    array_push($gMDLMenus, ["name" => "Citizen Dashboard", "link" => "#" ]) ;

    if(!empty($login)) {
       
        if($login->customerAdmin) { 
            array_push($gMDLMenus, ["name" => "Admin Dashboard", "link" => "/admin/lake/list.php"]) ;
        }
        
        array_push($gMDLMenus, ["name" => "Logout", "link" => "/app/logout.php"]) ;

    } else {
         array_push($gMDLMenus, ["name" => "Login", "link" => "/app/login.php"]) ;
    }

?>
<head>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="/test/bootstrap/assets/css/style.css" />
</head>
<header>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/app/login.php">Bangalore Lake Dashboard</a>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php foreach($gMDLMenus as $gMDLMenu)  { ?>
							<li><a class="mdl-navigation__link"  href="<?php echo $gMDLMenu['link']; ?>"> <?php echo $gMDLMenu['name']; ?><span class="sr-only">(current)</span></a></li>
						<?php } ?> 
					</ul>
				</div>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</header>