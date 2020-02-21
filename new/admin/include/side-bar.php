<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Edu is Fun</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
		<li class="active treeview">
		      <li><a href="dashboard.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
		</li>
		<?php if($dashboardbox{'Manage_User'}==true){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-user.php"><i class="fa fa-circle-o"></i> View User</a></li> 
          </ul>
        </li>
		<?php } ?>
		<?php if($dashboardbox{'Manage_Call_Me'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Call Me</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-callme.php"><i class="fa fa-circle-o"></i> View Call Me</a></li> 
          </ul>
        </li>
		<?php } ?>
		<?php if($dashboardbox{'Manage_Invite_Friends'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Invite Friends</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-invite.php"><i class="fa fa-circle-o"></i> View Invite Friends</a></li> 
          </ul>
        </li>
		<?php } ?>
		<?php if($dashboardbox{'Manage_Lead'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Lead</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-lead.php"><i class="fa fa-circle-o"></i> View lead</a></li> 
          </ul>
        </li>
		<?php } ?>
		<?php if($dashboardbox{'Manage_Paid_User'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Paid User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-paiduser.php"><i class="fa fa-circle-o"></i> View Paid User</a></li> 
          </ul>
        </li>
		<?php } ?>
		
		<?php if($dashboardbox{'Manage_Aop_User'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Ace Of Pace</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-aopuser.php"><i class="fa fa-circle-o"></i> View Ace Of Pace</a></li> 
          </ul>
        </li>
		<?php } ?>
		
		<?php if($dashboardbox{'Manage_Free_User'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Free User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-freeuser.php"><i class="fa fa-circle-o"></i> View Free User</a></li> 
          </ul>
        </li>
		<?php } ?>
		
		<?php if($dashboardbox{'Manage_User_Password'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage User Password</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-userpassword.php"><i class="fa fa-circle-o"></i> View User Password</a></li> 
          </ul>
        </li>
		<?php } ?>
		
		<?php if($dashboardbox{'Manage_chapter'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Chapter</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-sound.php"><i class="fa fa-circle-o"></i>Sound</a></li> 
            <li><a href="view-friction.php"><i class="fa fa-circle-o"></i>Friction</a></li> 
            <li><a href="view-forcepressure.php"><i class="fa fa-circle-o"></i>Force And Pressure</a></li> 
            <li><a href="view-natural-phenomena.php"><i class="fa fa-circle-o"></i>Natural Phenomena I</a></li> 
            <li><a href="view-natural-phenomenaII.php"><i class="fa fa-circle-o"></i>Natural Phenomena II</a></li> 
			<li><a href="solar-systemI.php"><i class="fa fa-circle-o"></i>Solar System I</a></li> 
			<li><a href="solar-systemII.php"><i class="fa fa-circle-o"></i>Solar System II</a></li> 
			<li><a href="solar-systemIII.php"><i class="fa fa-circle-o"></i>Solar System III</a></li> 
			<li><a href="lightI.php"><i class="fa fa-circle-o"></i>Light I</a></li> 
			<li><a href="lightII.php"><i class="fa fa-circle-o"></i>Light II</a></li> 
			<li><a href="chemical-effects.php"><i class="fa fa-circle-o"></i>Chemical Effects</a></li> 
          </ul>
        </li>
		<?php } ?>
		<!--<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Free User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="view-freeuser.php"><i class="fa fa-circle-o"></i> View Free User</a></li> 
          </ul>
        </li>-->
		<?php if($dashboardbox{'Manage_Subject'}==true){ ?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Subject</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="add-subject.php"><i class="fa fa-circle-o"></i>Add Subject</a></li> 
			 <li><a href="view-subject.php"><i class="fa fa-circle-o"></i>View Subject</a></li> 
          </ul>
        </li>
		<?php } ?>
		<!--<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Manage Chapter</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="add-chapter.php"><i class="fa fa-circle-o"></i>Add Chapter</a></li> 
			 <li><a href="view-chapter.php"><i class="fa fa-circle-o"></i>View Chapter</a></li> 
          </ul>
        </li>-->
		
		
		
		
	
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="control-sidebar-bg"></div>