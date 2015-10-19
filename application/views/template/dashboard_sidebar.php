<?php
$current_page = ucfirst($this->router->fetch_class());
$current_controller = $this->router->fetch_class();
?>
			<!-- start: Main Menu -->
			<div id="sidebar-left" class="span1">
				<div class="nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="active"><a href="index.html"><i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Sources <?php echo $dashboard_title; ?></span></a></li>	
						<li><a href="infrastructure.html"><i class="fa-icon-hdd"></i><span class="hidden-tablet"> Variants</span></a></li>
						<li><a href="messages.html"><i class="fa-icon-envelope"></i><span class="hidden-tablet"> Submissions</span></a></li>
						<li><a href="tasks.html"><i class="fa-icon-tasks"></i><span class="hidden-tablet"> Users</span></a></li>
						<li><a href="ui.html"><i class="fa-icon-eye-open"></i><span class="hidden-tablet"> Groups</span></a></li>
						<li><a href="widgets.html"><i class="fa-icon-dashboard"></i><span class="hidden-tablet"> Appearance</span></a></li>
						<li><a href="ui.html"><i class="fa-icon-eye-open"></i><span class="hidden-tablet"> News</span></a></li>
						<li><a href="ui.html"><i class="fa-icon-eye-open"></i><span class="hidden-tablet"> Settings</span></a></li>
						<li><a href="ui.html"><i class="fa-icon-eye-open"></i><span class="hidden-tablet"> Statistics</span></a></li>
					</ul>
				</div>
			</div>
			<!-- end: Main Menu -->
