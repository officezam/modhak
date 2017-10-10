@extends('backend.layouts.app')
@section('content')
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Subscribe User</h3>
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
              <h2>Subscribe <small> Users</small></h2>
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
              @if (Session::get('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @endif
              @if (Session::get('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
              @endif
              <br>
              <form action="{{ route('save_subscriber') }}" method="post" class="form-horizontal form-label-left" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Name <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" id="name" name="name" placeholder="Write Subscriber Name" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">User Phone<span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" id="phone" name="phone" placeholder="Write Subscriber Phone e.g(+15876046444)" required="required"  class="form-control col-md-7 col-xs-12">
                      <p class="alert-info">Please Enter Valid Phone Number Like <b style="color: black">+15876046444</b></p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Mosque</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="heard" class="form-control" required="" name="m_id">
                      @foreach($mosque as $masjid)
                      <option value="{{ $masjid->id }}">{{ $masjid->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Subscribe</button>
                  </div>
                </div>

              </form>
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