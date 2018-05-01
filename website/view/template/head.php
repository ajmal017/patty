<!DOCTYPE html>
<html lang="en">
	<head>
        <!-- Page Encoding -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta charset="UTF-8"/>

        <!-- IE Condition Where Breaking CSS -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/res/css/new.css"/>

        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

		<title>강필구 P2P</title>
	</head>
	<body>
		<div class="container">
		    <div class="sidebar">
		        <div class="section">
		            <h1 class="title">Navigation</h1>
		            <ul>
		                <li>
		                    <a href="#">P2P</a>
		                    <ul>
		                        <li>
									<a href="/p2p/report/" <?php if (isset($menu)&&($menu=="p2p->report")){ echo 'class="active"' ;} ?>>Report</a>
								</li>
		                        <li>
									<a href="/p2p/company/" <?php if (isset($menu)&&($menu=="p2p->company")){ echo 'class="active"' ;} ?>>Company</a>
								</li>
		                        <li><a href="">Late</a></li>
		                    </ul>
		                </li>
		                <li>
		                    <a href="#">Stock</a>
		                </li>
		                <li>
		                    <a href="#">Leasing</a>
							<ul>
		                        <li>
									<a href="/leasing/report/" <?php if (isset($menu)&&($menu=="learing->report")){ echo 'class="active"' ;} ?>>Report</a>
								</li>
		                        <li>
									<a href="/leasing/contract/" <?php if (isset($menu)&&($menu=="leasing->contract")){ echo 'class="active"' ;} ?>>Contract</a>
								</li>
		                    </ul>
		                </li>
		            </ul>
		        </div>
		        <!--/.section-->
		    </div>
		    <!--/.sidebar-->

		    <div class="content">
