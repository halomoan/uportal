<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

  <link rel="stylesheet" href="/css/app.css">



</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm">
  <div class="wrapper" id="app">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-lightblue">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" @keyup.enter="searchhit" v-model="searchText"
          placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchhit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            @guest

            @else
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            @endguest
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link" exact-active-class="active">
                <i class="nav-icon fas fa-tachometer-alt text-cyan"></i>
                <p>
                  Dashboard

                </p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link to="/invoices" class="nav-link" exact-active-class="active">
                <i class="nav-icon fas fa-file-invoice-dollar text-green"></i>
                <p>
                  Invoices
                  <span class="right badge badge-danger">New</span>
                </p>
              </router-link>
            </li>

            @can('isAdmin')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog text-grey"></i>
                <p>
                  Management
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    <i class="far fa-user nav-icon"></i>
                    <p>Users</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/gentoken" class="nav-link">
                    <i class="fas fa-key nav-icon"></i>
                    <p>Generate Token</p>
                  </router-link>
                </li>
              </ul>
            </li>
            @endcan

            <li class="nav-item">
              <router-link to="/profile" class="nav-link" exact-active-class="active">
                <i class="nav-icon far fa-id-card text-yellow"></i>
                <p>
                  Profile
                </p>
              </router-link>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off text-red"></i>
                <p>
                  {{ __('Logout') }}
                </p>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>

          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2019 <a href="http://uportal.test">u-Portal</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
      </div>
    </footer>

    <!-- Control Sidebar -->
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  @auth
  <script>
    window.user = @json(auth()->user()) 
  </script>
  @endauth

  <script src="/js/app.js"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

</body>

</html>