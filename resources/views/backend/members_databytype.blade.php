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
          <h3>Members Records</h3>
        </div>

        <div class="pull-right">
          <a href="{{ route('addMember') }}" >
            <button type="button" class="btn btn-success">Add New Member</button>
          </a>
          <a href="{{ route('addMemberbyExcel') }}" >
            <button type="button" class="btn btn-success">Save Excel Sheet</button>
          </a>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="row">
        {{--{{ dd($getData) }}--}}

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Memebrs <small></small></h2>
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
                  <th>Members Name</th>
                  <th>Members Phone</th>
                  <th>Members Category</th>
                  <th>Members Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Country</th>
                  <th>Zip Code</th>
                  <th>Members Email</th>
                  <th>Action</th>
                </tr>
                </thead>

                <tbody>

                @if($getData)
                  @foreach($getData as $members)
                    <tr>
                      @if($members->type == 'Excell')
                        <td>{{ $members->first_name }} {{ $members->last_name }}</td>
                      @else
                        <td>{{ $members->name }}</td>
                      @endif
                      <td>{{ $members->phone }}</td>
                      <th>{{ $membersType->type }}</th>
                      <th>{{ $members->address }}</th>
                      <th>{{ $members->city }}</th>
                      <th>{{ $members->state }}</th>
                      <th>{{ $members->country }}</th>
                      <th>{{ $members->zip_code }}</th>
                      <th>{{ $members->email }}</th>
                      <td>
                        {{--<a href="{{ route('edit-template-data', $membersCategory->id) }}" ><button type="button" class="btn btn-danger">Edit</button></a>--}}
                        @if($members->type == 'Excell')
                          <a href="{{ route('delete-member-data-excel', $members->id) }}" ><button type="button" class="btn btn-danger">Delete</button></a>
                        @else
                          <a href="{{ route('delete-member-data', $members->id) }}" ><button type="button" class="btn btn-danger">Delete</button></a>

                        @endif
                      </td>
                    </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @stop
  @section('pagejs')
    <!-- Datatables -->
      <script src="{{asset('/admin/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  {{--<script src="{{asset('/admin/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/jszip/dist/jszip.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/pdfmake/build/pdfmake.min.js')}}"></script>--}}
  {{--<script src="{{asset('/admin/vendors/pdfmake/build/vfs_fonts.js')}}"></script>--}}
@endsection