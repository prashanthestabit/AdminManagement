<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    @include("adminmanagement::partials.header")
    @stack('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}"
          alt="AdminLTELogo" height="60" width="60">
  </div>

    @include('adminmanagement::partials.navbar')

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    @include('adminmanagement::partials.sidebar')
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    @include('adminmanagement::partials.alert')

    @yield('content')

  </div>

<!-- ./wrapper -->
@include("adminmanagement::partials.footer")
@stack('script')
</body>
</html>
