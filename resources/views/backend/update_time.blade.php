@extends('backend.layouts.app')
@section('pagecss')
    <!-- FullCalendar -->
    <link href="{{asset('/admin/vendors/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/vendors/fullcalendar/dist/fullcalendar.print.css')}}" rel="stylesheet" media="print">
@endsection
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Add Mosque and Time</h3>
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
                            <h2>Set Monthly Prayer Time <small>Click any date to set Time</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content well">
                            <form class="form-horizontal form-label-left" novalidate="">

                                <span class="section">Mosque Info</span>
                                <div class="alert alert-danger hidden" id="writefirst">Please write mosque name and keyword First</div>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mosque Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="mosque_mame" class="form-control col-md-7 col-xs-12" value="{{ $mosqueData->name }}" data-validate-length-range="15" data-validate-words="2" name="mosque_mame" placeholder="Mosque name(s) e.g Masjid Faisal" required="required" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mosque Keyword <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="mosque_keyword" class="form-control col-md-7 col-xs-12" value="{{ $mosqueData->keyword }}" data-validate-length-range="15" data-validate-words="2" name="mosque_keyword" placeholder="Mosque Keyword(s) e.g Masjid Faisal" required="required" type="text">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="x_content well">
                            <br>
                            <div id='calendar'></div>
                        </div>
                        <br>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- calendar modal -->
    <div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Set Namaz Timing </h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger hidden" id="FillAllTime">Please select all Namaz time </div>
                    <div id="testmodal" style="padding: 5px 20px;">
                        <form id="antoform" class="form-horizontal calender" role="form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m_id" value="{{ $mosqueData->id }}" id="m_id">
                            <input type="hidden" name="m_name" value="" id="m_name">
                            <input type="hidden" name="m_keyword" value="" id="m_keyword">
                            <input type="hidden" name="namaz_date" value="" id="namaz_date">
                            <div class="form-group">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Fajar Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='fajar_time_picker'>
                                            <input type='text' class="form-control" name="fajar_time" id="fajar_time" required="required" placeholder="Select Time"  />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group zuhar">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Zuhar Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='zuhar_time_picker'>
                                            <input type='text' class="form-control" name="zuhar_time" id="zuhar_time" required="required" placeholder="Select Time"  />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group friday">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Friday Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='friday_time_picker'>
                                            <input type='text' class="form-control" name="friday_time" id="friday_time" required="required" placeholder="Select Time"  />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Asar Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='asar_time_picker'>
                                            <input type='text' class="form-control" name="asar_time" id="asar_time" required="required" placeholder="Select Time"  />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Magrib Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='magrib_time_picker'>
                                            <input type='text' class="form-control" name="magrib_time" id="magrib_time" required="required" placeholder="Select Time" />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">Esha Namaz Time</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="form-group">
                                        <div class='input-group date' id='esha_time_picker'>
                                            <input type='text' class="form-control" name="esha_time" id="esha_time" required="required" placeholder="Select Time"  />
                                            <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary antosubmit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div id="CalenderModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel2">Edit Calendar Entry</h4>
                </div>
                <div class="modal-body">

                    <div id="testmodal2" style="padding: 5px 20px;">
                        <form id="antoform2" class="form-horizontal calender" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="title2" name="title2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" style="height:55px;" id="descr2" name="descr"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default antoclose2" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary antosubmit2">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>
    <div id="fc_edit" data-toggle="modal" data-target="#CalenderModalEdit"></div>
    <!-- /calendar modal -->


@stop
@section('pagejs')
    <!-- Initialize datetimepicker -->
    <script>
        $('#myDatepicker').datetimepicker({  format: 'DD.MM.YYYY' });
        $('#fajar_time_picker').datetimepicker({format: 'hh:mm A'});
        $('#zuhar_time_picker').datetimepicker({format: 'hh:mm A'});
        $('#friday_time_picker').datetimepicker({format: 'hh:mm A'});
        $('#asar_time_picker').datetimepicker({format: 'hh:mm A'});
        $('#magrib_time_picker').datetimepicker({format: 'hh:mm A'});
        $('#esha_time_picker').datetimepicker({format: 'hh:mm A'});

    </script>
    <script src="{{asset('/admin/vendors/fullcalendar/dist/fullcalendar.min.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <!-- Custom Theme Scripts -->
    {{--  <script src="{{asset('/admin/build/js/custom.min.js')}}"></script>--}}
    <script>
        function init_calendar() {
            if ("undefined" != typeof $.fn.fullCalendar) {
                //console.log("init_calendar");
                var e, f, a = new Date,
                    b = a.getDate(),
                    c = a.getMonth(),
                    d = a.getFullYear(),
                    g = $("#calendar").fullCalendar({
                        dayClick: function(date, jsEvent, view) {

                            // console.log(view);
                            // alert('Clicked on: ' + date.format());
                            var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
                            var a = new Date(date.format());
                            var day = weekday[a.getDay()];
                            if(day == "Friday"){
                                $(".zuhar").hide();
                                $(".friday").show();
                            }else{
                                $(".friday").hide();
                                $(".zuhar").show();
                            }
                            $('#myModalLabel').html( "Namaz Timing For "+day+", "+date.format('DD MM YYYY'));
                            $('#namaz_date').val(date.format());
                            var mosque_mame    = $('#mosque_mame').val();
                            var mosque_keyword = $('#mosque_keyword').val();
                            if(!(mosque_mame) || !(mosque_keyword)){
                                $("#writefirst").removeClass('hidden');
                                return false;
                            }else{
                                $("#writefirst").addClass('hidden');
                                $("#emptymosquename").addClass('hidden');
                                $("#fc_create").click()
                            }
                            // change the day's background color just for fun
                            //$(this).css('background-color', 'red');

                        },
                        header: {
                            left: "prev,next today",
                            center: "title",
                            right: "month,agendaWeek,agendaDay,listMonth"
                        },
                        selectable: !0,
                        selectHelper: !0,
                        select: function(a, b, c) {
                            $(".antosubmit").on("click", function()
                            {
                                $("#m_name").val($('#mosque_mame').val());
                                $("#m_keyword").val($('#mosque_keyword').val());
                                var fajar_time  = $("#fajar_time").val();
                                var asar_time   = $("#asar_time").val();
                                var magrib_time = $("#magrib_time").val();
                                var esha_time   = $("#esha_time").val();

                                if(!(fajar_time) || !(asar_time) || !(magrib_time)|| !(esha_time)){
                                    $("#FillAllTime").removeClass('hidden');
                                }else{
                                    $("#FillAllTime").addClass('hidden');
                                    var datastring = $("#antoform").serialize();
                                    $.ajax({
                                        method: 'POST', // Type of response and matches what we said in the route
                                        url: '/save-namaz-time', // This is the url we gave in the route
                                        data: datastring, // a JSON object to send back
                                        success: function(response){ // What to do if we succeed
                                            console.log(response);
                                            $('#antoform')[0].reset();
                                            //g.fullCalendar("renderEvents",response);
                                            $(".antoclose").click();
                                            window.location.href = "{{ url('update-time')}}"+response;
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                            console.log(JSON.stringify(jqXHR));
                                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                        }
                                    });
                                }
                            })
                        },
                        eventClick: function(a, b, c) {
                            //console.log(a);
//                          console.log(d);
//                          console.log(c);
                            $("#fc_create").click()
                            /*$("#fc_edit").click(),*/ $("#title2").val(a.title), f = $("#event_type").val(), $(".antosubmit2").on("click", function() {

                                a.title = $("#title2").val(), g.fullCalendar("updateEvent", a), $(".antoclose2").click()
                            }), g.fullCalendar("unselect")
                        },
                        editable: !0,

                        events:
                            [

                                    @if($dataArray)
                                    @foreach($dataArray  as $namazTime)
                                {
                                    title:  "{{ $namazTime['title'] }}",
                                    start: "{{ $namazTime['start'] }}"
                                },
                                @endforeach
                                @endif
                            ] ,
                        timeFormat:  'h:mm T',  // uppercase H for 24-hour clock
                        "color": "green",   // an option!
                        "textColor": "black" // an option!
                    })
            }
        }
        $(document).ready(function() {
            init_calendar() });
    </script>

@endsection