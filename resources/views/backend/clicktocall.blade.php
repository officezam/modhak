@extends('backend.layouts.app')
@section('pagecss')
@endsection
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Enter Number and Call</h3>
                </div>

            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        @if (Session::get('empty'))
                            <div class="alert alert-danger">{{ Session::get('empty') }}</div>
                        @endif
                        @if (Session::get('send'))
                            <div class="alert alert-success">{{ Session::get('send') }}</div>
                        @endif
                        <div class="x_title">
                            <h2>Enter Message <small>Send Message to Idvidual</small></h2>
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
                            <form class="form-horizontal" method="POST" action="{{ route('createcall') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-4 control-label">Member Phone</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Enter Phone" required autofocus>
                                        <p class="alert-info">Please Enter Valid Phone Number Like <b style="color: black">+11212343412</b></p>
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Click to call This Number
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @stop
    @section('pagejs')
        <!-- Datatables -->
@endsection