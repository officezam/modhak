@extends('backend.layouts.app')
@section('content')
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Add Mosque</h3>
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
              <h2>Form Design <small>different form elements</small></h2>
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
              <form action="{{ route('add_mosque') }}" method="post" class="form-horizontal form-label-left" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Mosque Name <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" id="mosque_name" name="mosque_name" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">City Name <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" id="city" name="city" required="required" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Date</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='myDatepicker'>
                        <input type='text' class="form-control" name="date" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Fajar Namaz Time</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='fajar_time_picker'>
                        <input type='text' class="form-control" name="fajar_time" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Zuhar Namaz Time</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='zuhar_time_picker'>
                        <input type='text' class="form-control" name="zuhar_time" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Asar Namaz Time</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='asar_time_picker'>
                        <input type='text' class="form-control" name="asar_time" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Magrib Namaz Time</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='magrib_time_picker'>
                        <input type='text' class="form-control" name="magrib_time" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Esha Namaz Time</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <div class='input-group date' id='esha_time_picker'>
                        <input type='text' class="form-control" name="esha_time" required="required"  />
                        <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button class="btn btn-primary" type="button">Cancel</button>
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
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