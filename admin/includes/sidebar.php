<div class="sidebar-menu">
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <!-- logo -->
            <div class="logo">
                <a href="/" class="admin-logo">InferCMS</a>
            </div>
            <!-- logo collapse icon -->
            <div class="sidebar-collapse">
                <a href="#" class="sidebar-collapse-icon">
                    <!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition --><i class="entypo-menu"></i> </a>
            </div>
            <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
            <div class="sidebar-mobile-menu visible-xs">
                <a href="#" class="with-animation">
                    <!-- add class "with-animation" to support animation --><i class="entypo-menu"></i> </a>
            </div>
        </header>
        <ul id="main-menu" class="main-menu">
            <li <?php if(current_page_is('/admin/')) { echo 'class="active"'; } ?>><a href="/admin/"><i class="entypo-gauge"></i><span class="title">Dashboard</span></a></li>
            <li <?php if(current_page_model_is('myItems')) { echo 'class="active"'; } ?>><a href="/admin/edit_all.php?model=myItems"><i class="entypo-monitor"></i><span class="title">Manage My Items</span></a></li>
            <!-- ADDITIONAL MODEL LINKS HERE -->
            <li><a href="/action.php?a=logout"><i class="entypo-logout"></i><span class="title">Log Out</span></a></li>


        </ul>
    </div>
</div>
