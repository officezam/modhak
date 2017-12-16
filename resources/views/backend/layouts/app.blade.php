<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bulk SMS</title>

    <!-- Bootstrap -->
    <link href="{{asset('/admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/admin/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('/admin/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    {{--<!-- bootstrap-daterangepicker -->--}}
    <link href="{{asset('/admin/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    {{--<!-- bootstrap-datetimepicker -->--}}
    <link href="{{asset('/admin/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    {{--<!-- Ion.RangeSlider -->--}}
    {{--<link href="{{asset('/admin/vendors/normalize-css/normalize.css')}}" rel="stylesheet">--}}
    {{--<link href="{{asset('/admin/vendors/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">--}}
    {{--<link href="{{asset('/admin/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet">--}}
    {{--<!-- Bootstrap Colorpicker -->--}}
    {{--<link href="{{ asset('/admin/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">--}}

    {{--<link href="{{asset('/admin/vendors/cropper/dist/cropper.min.css')}}" rel="stylesheet">--}}

    @yield('pagecss')

    <!-- Custom Theme Style -->
    <link href="{{asset('/admin/build/css/custom.min.css')}}" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">

        <!-- Sidebar navigation -->
        @include('backend.layouts.sidebar');
        <!-- /Sidebar navigation -->

        <!-- top navigation -->
        @include('backend.layouts.header');
        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')
        <!-- /page content -->

        <!-- footer content -->
        @include('backend.layouts.footer');
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('/admin/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('/admin/vendors/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('/admin/vendors/nprogress/nprogress.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('/admin/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('/admin/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
{{--<!-- bootstrap-datetimepicker -->--}}
<script src="{{asset('/admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
{{--<!-- Ion.RangeSlider -->--}}
{{--<script src="{{asset('/admin/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>--}}
{{--<!-- Bootstrap Colorpicker -->--}}
{{--<script src="{{asset('/admin/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>--}}
{{--<!-- jquery.inputmask -->--}}
{{--<script src="{{asset('/admin/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js')}}"></script>--}}
{{--<!-- jQuery Knob -->--}}
{{--<script src="{{asset('/admin/vendors/jquery-knob/dist/jquery.knob.min.js')}}"></script>--}}
{{--<!-- Cropper -->--}}
{{--<script src="{{asset('/admin/vendors/cropper/dist/cropper.min.js')}}"></script>--}}

  <script src="{{asset('/admin/build/js/custom.min.js')}}"></script>

@yield('pagejs')
</body>
</html>
