<!DOCTYPE html>
<html lang="en">
	<head>
        <!-- Page Encoding -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta charset="UTF-8"/>

        <!-- IE Condition Where Breaking CSS -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/public/css/default.css"/>

        <!-- JS -->
		<script src="/public/js/component/CanvasChart.js"></script>
        <script src="/public/js/vendor/jquery-3.2.1.min.js"></script>

		<title>Phil Goo Kang</title>
	</head>
	<body>
		<div class="container">
		    <div class="sidebar">
		        <div class="section">
		            <h1 class="title">Navigation</h1>
		            <ul>
						<li>
		                    <a href="#">Dashboard</a>
							<ul>
								<li>
				                    <a href="/calendar/" class="<?php echo ($page=='calendar')?'active':''; ?>">Calendar</a>
				                </li>
								<li>
				                    <a href="/cron/" class="<?php echo ($page=='cron')?'active':''; ?>">Status</a>
				                </li>
							</ul>
		                </li>
						<li>
							<a href="#">Company</a>
							<ul>
								<li>
				                    <a href="/company/search/" class="<?php echo ($page=='company=>search')?'active':''; ?>">Search</a>
				                </li>
							</ul>
		                </li>
						<li>
		                    <a href="#">Playlist</a>
							<ul>
		                        <li>
									<a href="/playlist/" class="<?php echo ($page=='playlist')?'active':''; ?>">Top 100</a>
								</li>
								<li>
									<a href="/playlist/group/" class="<?php echo ($page=='playlist=>group')?'active':''; ?>">Group Manage</a>
								</li>
		                    </ul>
		                </li>
		            </ul>
		        </div>
		        <!--/.section-->
		    </div>
		    <!--/.sidebar-->

		    <div class="content">
