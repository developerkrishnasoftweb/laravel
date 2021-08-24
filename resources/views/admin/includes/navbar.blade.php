  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if(Auth::user()->profile_image)
          <img src="{{ asset(Auth::user()->profile_image) }}" class="img-circle elevation-2" alt="User Image">
          @else
          <img src="{{ asset('assets/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
          @endif
        </div>
        <div class="info">
          <a href="{{ route('admin.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @php
            $navbars = DB::table('navbars')->where('parent_nav_id', 0)->get();
            foreach($navbars as $navmenu) {
              $navmenu->submenu = DB::table('navbars')->where('parent_nav_id', $navmenu->id)->get();
              foreach($navmenu->submenu as $submenu) {
                $submenu->isActive = request()->is(trim($submenu->url, '/')) || request()->is(trim($submenu->url, '/').'/*');
              }
              $navmenu->isActive = request()->is(trim($navmenu->url, '/')) || request()->is(trim($navmenu->url, '/').'/*');
            }
          @endphp

          @foreach($navbars as $navmenu)
            @if($navmenu->submenu->isNotEmpty())
              <li class="nav-item {{ $navmenu->isActive ? 'menu-open' : '' }}">
                <a href="{{ url($navmenu->url) }}" class="nav-link {{ $navmenu->isActive ? 'active' : '' }}">
                  <i class="nav-icon {{ $navmenu->icon }}"></i>
                  <p>{{ $navmenu->title }} <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  @foreach($navmenu->submenu as $submenu)
                    <li class="nav-item">
                      <a href="{{ url($submenu->url) }}" class="nav-link {{ $submenu->isActive ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $submenu->title }}</p>
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
            @else
              <li class="nav-item">
                <a href="{{ url($navmenu->url) }}" class="nav-link {{ $navmenu->isActive ? 'active' : '' }}">
                  <i class="nav-icon {{ $navmenu->icon }}"></i>
                  <p>{{ $navmenu->title }}</p>
                </a>
              </li>
            @endif
          @endforeach
          <li class="nav-header">SETTINGS</li>
          <li class="nav-item">
            <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>