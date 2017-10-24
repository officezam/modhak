@extends('backend.layouts.app')
@section('pagecss')
  <!-- Datatables -->
  {{--<link href="{{asset('/admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">--}}
  {{--<link href="{{asset('/admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">--}}
  {{--<link href="{{asset('/admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">--}}
  {{--<link href="{{asset('/admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">--}}
  {{--<link href="{{asset('/admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">--}}
@endsection
@section('content')
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Mosque Record</h3>
        </div>

        <div class="pull-right">
          <a href="{{ route('add_time_form') }}" >
            <button type="button" class="btn btn-success">Add Mosque</button>
          </a>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Mosque <small>Record</small></h2>
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
              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                  <th>Mosque Name</th>
                  <th>Mosque Keyword</th>
                  <th>Deatil</th>
                  <th>Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach($mosqueData as $mosque)
                  <tr>
                    <td>{{ $mosque->name }}</td>
                    <td>{{ $mosque->keyword }}</td>
                    <td><a href="{{ route('updae-time', $mosque->id) }}" ><button type="button" class="btn btn-success">View Namaz Time Detail </button></a></td>
                    <td>

                      <button type="button" id="copy_mosque_data" onclick="mosque_data({{ $mosque->id }})" class="btn btn-primary">Copy Mosque Data</button>
                      <a href="{{ route('updae-time', $mosque->id) }}" ><button type="button" class="btn btn-info">Edit</button></a>
                      <a href="{{ route('delete-mosque-data', $mosque->id) }}" ><button type="button" class="btn btn-danger">Delete</button></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div id="Copy_Mosque" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel2">Copy Mosque Data</h4>
          </div>
          <form id="antoform2" class="form-horizontal calender" action="{{ route('copy_mosque') }}" method="post" role="form">
          <div class="modal-body">

            <div id="testmodal2" style="padding: 5px 20px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="m_id" id="m_id" value="">
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mosque Name <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="mosque_mame" class="form-control col-md-7 col-xs-12" value="" data-validate-length-range="15" data-validate-words="2" name="mosque_mame" placeholder="Mosque name(s) e.g Masjid Faisal" required="required" type="text">
                  </div>
                </div>
                <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mosque Keyword <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="mosque_keyword" class="form-control col-md-7 col-xs-12" value="" data-validate-length-range="15" data-validate-words="2" name="mosque_keyword" placeholder="Mosque Keyword(s) e.g Masjid Faisal" required="required" type="text">
                  </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default antoclose2" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary antosubmit2">Save changes</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <div id="click_Copy_Mosque" data-toggle="modal" data-target="#Copy_Mosque"></div>



    @stop
    @section('pagejs')
      <script>
          function mosque_data(value) {
              $("#m_id").val(value);
              $('#Copy_Mosque').modal('show');
          }

      </script>
      <!-- Datatables -->
      <script src="{{asset('/admin/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>



@endsection