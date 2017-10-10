@extends('backend.layouts.app')
@section('content')
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>SMS Sending</h3>
        </div>

        {{--<div class="title_right">--}}
        {{--<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">--}}
        {{--<div class="input-group">--}}
        {{--<input type="text" class="form-control" placeholder="Search for...">--}}
        {{--<span class="input-group-btn">--}}
        {{--<button class="btn btn-default" type="button">Go!</button>--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
      </div>

      <div class="clearfix"></div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Send SMS <small>Today Namaz SMS Sending</small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>

            </div>
            <div class="x_content">
              <br>
              @if (Session::get('empty'))
                <div class="alert alert-danger">{{ Session::get('empty') }}</div>
              @endif
              @if (Session::get('send'))
                <div class="alert alert-success">{{ Session::get('send') }}</div>
              @endif
              <br>
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">
                <a href="{{ route('sending_sms') }}">
                <button type="button" class="btn btn-success btn-lg">Start Sending Sms To All Subscriber</button>
                </a>
              </label>
            </div>
          </div>
        </div>




      </div>

    </div>
  </div>
@stop
@section('pagejs')
  <!-- Initialize datetimepicker -->
  <script>
    $('#myDatepicker').datetimepicker({  format: 'DD.MM.YYYY' });
    $('#fajar_time_picker').datetimepicker({format: 'hh:mm A'});
    $('#zuhar_time_picker').datetimepicker({format: 'hh:mm A'});
    $('#asar_time_picker').datetimepicker({format: 'hh:mm A'});
    $('#magrib_time_picker').datetimepicker({format: 'hh:mm A'});
    $('#esha_time_picker').datetimepicker({format: 'hh:mm A'});

//    $('#myDatepicker2').datetimepicker({
//      format: 'DD.MM.YYYY'
//    });
//
//    $('#myDatepicker3').datetimepicker({
//      format: 'hh:mm A'
//    });
//
//    $('#myDatepicker4').datetimepicker({
//      ignoreReadonly: true,
//      allowInputToggle: true
//    });
//
//    $('#datetimepicker6').datetimepicker();
//
//    $('#datetimepicker7').datetimepicker({
//      useCurrent: false
//    });
//
//    $("#datetimepicker6").on("dp.change", function(e) {
//      $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
//    });
//
//    $("#datetimepicker7").on("dp.change", function(e) {
//      $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
//    });
  </script>
@endsection