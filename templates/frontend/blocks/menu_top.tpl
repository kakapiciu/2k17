
  

<ul id="top_menu" class="nav navbar-nav">

	<li class="{SEL_PAGE_CODING-STANDARD}">
		<a href="{SITE_URL}/gag/list-by-rank">Trend</a> 
	</li>
	<li class="{SEL_PAGE_CODING-STANDARD}">
		<a href="{SITE_URL}/gag/list-by-date">New</a> 
	</li>
	<!-- BEGIN top_menu_not_logged -->
	<li class="{SEL_USER_LOGIN}">
		<a href="{SITE_URL}/user/login">Log In</a>
	</li>
	<li class="{SEL_USER_REGISTER}">
		<a href="{SITE_URL}/user/register">Register</a>
	</li>
	<!-- END top_menu_not_logged -->
	<!-- BEGIN top_menu_logged -->
		<li class="{SEL_PAGE_CODING-STANDARD}">
		<a href="{SITE_URL}/gag/list-by-user">My Gag`s</a> 
	</li>
	<li class="{SEL_USER_ACCOUNT}">
		<a href="{SITE_URL}/user/account">{USERNAME}</a>
	</li>
	<li class="dropdown dropdown-notifications">
            <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
              <i data-count="10" class="glyphicon glyphicon-bell notification-icon"></i>
            </a>

            <div class="dropdown-container">

              <div class="dropdown-toolbar">
                <div class="dropdown-toolbar-actions">
                  <a href="#">Mark all as read</a>
                </div>
                <h3 class="dropdown-toolbar-title">Notifications (2)</h3>
              </div><!-- /dropdown-toolbar -->

              <ul class="dropdown-menu">
                  ...
              </ul>

              <div class="dropdown-footer text-center">
                <a href="#">View All</a>
              </div><!-- /dropdown-footer -->

            </div><!-- /dropdown-container -->
          </li><!-- /dropdown -->
	<li>
		<a href="{SITE_URL}/user/logout">Log Out</a>
	</li>
	<!-- END top_menu_logged -->
</ul>

