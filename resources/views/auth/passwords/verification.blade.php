@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Enter Verification Number</div>
                <div class="panel-body">
                    @if(Session::has('code-sent'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('code-sent') }}</p>
                    @endif
                    @if(Session::has('verify-error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('verify-error') }}</p>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('verification_code') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="email" value="{{ $userEmail }}" >
                        <input type="hidden" name="password" value="{{ $password }}" >
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="verification_code" class="col-md-4 control-label">Verification Code</label>
                            <div class="col-md-6">
                                <input id="verification_code" placeholder="Enter Verificarion Code" type="text" class="form-control" name="verification_code" >
                                @if ($errors->has('verification_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('verification_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="submit" class="btn btn-info" name="resend" value="1">
                                    Resend Verification Code
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
