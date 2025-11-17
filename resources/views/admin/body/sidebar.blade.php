<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark text-decoration-none">
                    <span class="logo-lg">
                        <span class="h3 fw-bold mb-0">ZONE</span>
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <li>
                    <a href="#sidebarContent" data-bs-toggle="collapse">
                        <i data-feather="database"></i>
                        <span> Content Data </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarContent">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('categories.index') }}" class="tp-link">Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('contents.index') }}" class="tp-link">Contents</a>
                            </li>
                            <li>
                                <a href="{{ route('objectives.index') }}" class="tp-link">Objectives</a>
                            </li>
                            <li>
                                <a href="{{ route('tasks.index') }}" class="tp-link">Tasks</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarProductivity" data-bs-toggle="collapse">
                        <i data-feather="briefcase"></i>
                        <span> Productivity </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarProductivity">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.speedrun') }}" class="tp-link">Speed Run</a>
                            </li>
                            <li>
                                <a href="{{ route('todos.index') }}" class="tp-link">Todo_List</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>