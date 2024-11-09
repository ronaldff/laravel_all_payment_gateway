<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <style>
      /* Ensuring the footer is at the bottom of the page */
      .wrapper {
          min-height: 100vh;
          display: flex;
          flex-direction: column;
      }

      .content {
          flex: 1;
      }

      .footer {
          background-color: #343a40!important;
          padding: 10px;
          text-align: center;
          color: #ffffff;
      }
  </style>
</head>

<body class="bg-light">
  <div class="wrapper">
    <div class="content">
      @include('layouts.navbar')
      @yield('main')
    </div>

    <footer class="footer">
      &copy; 2023-{{ date('Y') }} Created By Piyush Shyam
    </footer>
  </div>
  
  
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

  <script src="{{ asset('assets/js/fontawesome.min.js') }}"></script>

  <!-- Page-specific Scripts -->
  @yield('scripts')
</body>

</html>
