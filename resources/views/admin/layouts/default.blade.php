<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard 3</title>

  @include('admin.includes.links')
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('admin.includes.header')
  @include('admin.includes.navbar')

  @yield('body')

  @include('admin.includes.footer')
</div>
<!-- ./wrapper -->
@include('admin.includes.scripts')
@stack('scripts')
</body>
</html>
