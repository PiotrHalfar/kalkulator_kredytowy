<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="utf-8"/>
	<title>Kalkulator Kredytowy</title>
	<link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<link rel="stylesheet" href="{$conf->app_url}/css/bootstrap.min.css">
	<link rel="stylesheet" href="{$conf->app_url}/css/font-awesome.min.css">
	<link rel="stylesheet" href="{$conf->app_url}/css/bootstrap-theme.css" media="screen" >
	<link rel="stylesheet" href="{$conf->app_url}/css/main.css">
</head>

<body>
	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-static-top headroom" >
		<div class="container">
         
				<ul class="nav navbar-nav pull-right">
					<li class="active"><a href="{$conf->action_url}homeShow">Strona Główna</a></li>
					<li><a href="{$conf->action_url}calcShow">Kalkulator Kredytowy</a></li>
                                        <li><a href="{$conf->action_url}logout">Wyloguj</a></li>
                                        
				</ul>
                               <div class="login"> 
                                    <span>użytkownik: {$user->login}, rola: {$user->role}</span>
                               </div>
		</div>
                                        
	</div> 

	<header id="head">
		<div class="container">
			<div class="row">
				<h1 class="lead">Witaj na stronie z kalkulatorami</h1>
				<p class="tagline">Za pomocą naszych kalkulatorów obliczysz wysokość raty kredytu!</p>
				<p><a class="btn btn-action btn-lg" href="{$conf->action_url}calcShow"">Kalkulator Kredytowy</a></p>
			</div>
		</div>
	</header>

	 <footer id="footer">
        <div class="footer2">
                <div class="container">
                        <div class="row">
                                <div class="col-md-12 widget">
                                        <div class="widget-body">
                                                <p class="text-right">
                                                        Copyright © 2021 Piotr Halfar
                                                </p>
                                        </div>
                                </div>
                        </div> 
                </div>
        </div>
    </footer>        
		




	
</body>
</html>