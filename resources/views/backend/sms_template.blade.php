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
              <h2>Send SMS<small>Template</small></h2>
              <div class="clearfix"></div>
            </div>
            <form action="{{ route('tempalte_update') }}" method="post" class="form-horizontal form-label-left" >
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <textarea name="sms_template" >{{ $getData->template }}</textarea>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                  <button type="submit" class="btn btn-success">Update Sms Template</button>
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

  <script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace( 'sms_template' );
  </script>
@endsection