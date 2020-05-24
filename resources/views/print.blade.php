<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>u-Portal | Invoice</title>
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
        <router-view></router-view>
    </div>
    <!-- ./wrapper -->
    <script src="/js/app.js"></script>
    <script type="text/javascript">
        window.addEventListener("load", window.print());
    </script>
</body>

</html>