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
		            <h1 class="title">메뉴</h1>
		            <ul>
						<li>
		                    <a href="#">관리</a>
							<ul>
								<li>
				                    <a href="/calendar/" class="<?php echo ($page=='calendar')?'active':''; ?>">달력</a>
				                </li>
								<li>
				                    <a href="/cron/" class="<?php echo ($page=='cron')?'active':''; ?>">시스템 상태</a>
				                </li>
							</ul>
		                </li>
						<li>
							<a href="#">회사</a>
							<ul>
								<li>
				                    <a href="/company/search/" class="<?php echo ($page=='company=>search')?'active':''; ?>">검색하기</a>
				                </li>
							</ul>
		                </li>
						<li>
		                    <a href="#">그룹</a>
							<ul>
		                        <li>
									<a href="/playlist/" class="<?php echo ($page=='playlist')?'active':''; ?>">일별 분석</a>
								</li>
								<?php
									$group_list = PlaylistGroupM::new()->getList();
								?>
								<?php foreach($group_list as $group) { ?>
									<li>
										<a href="/playlist/group/view/<?php echo $group->getIdx(); ?>" class="<?php echo ($page=='playlist=>group=>view=>'.$group->getIdx())?'active':''; ?>"><?php echo $group->getName(); ?></a>
									</li>
								<?php } ?>
								<li>
									<a href="/playlist/group/manage/" class="<?php echo ($page=='playlist=>group=>manage')?'active':''; ?>">그룹 관리</a>
								</li>
		                    </ul>
		                </li>
		            </ul>
		        </div>
		        <!--/.section-->
		    </div>
		    <!--/.sidebar-->

		    <div class="content">
