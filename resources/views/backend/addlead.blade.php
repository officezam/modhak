@extends('backend.layouts.app')

<!--page level css -->
@section('pagecss')
    <!-- daterange picker -->
    <link href="{{ asset('vendors/daterangepicker/css/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />
    <!--select css-->
    <link href="{{ asset('vendors/select2/select2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2-bootstrap.css') }}" />
    <!--clock face css-->
    <link href="{{ asset('vendors/iCheck/skins/all.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/pages/formelements.css') }}" rel="stylesheet" />
    <!--end of page level css-->
@endsection
<!--end of page level css-->

@section('content')

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <section class="content-header">
            <h1>Register New Member</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">
                        <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                        Dashboard
                    </a>
                </li>
                <li>Members</li>
                <li class="active">Register New Member</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Member Form</h3>
                            <span class="pull-right">
                                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form class="form-horizontal" method="POST" action="#" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="col-md-6">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="firstName">First Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" id="firstName" placeholder="First Name">
                                                    @if ($errors->has('first_name'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('first_name') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="inputEmail">Email:</label>
                                                <div class="col-md-9">
                                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="inputEmail" placeholder="Email">
                                                    @if ($errors->has('email'))
                                                        <div class=" has-error">
                                                    <span class="control-label has-error">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="inputPassword">Password:</label>
                                                <div class="col-md-9">
                                                    <input type="password" class="form-control" id="inputPassword" name="password" value="" placeholder="Password">
                                                    @if ($errors->has('password'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Date of Birth:</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="dob" name="dob" value="{{ old('dob') }}">
                                                    </div>
                                                    @if ($errors->has('dob'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('dob') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="inputPassword">Postal Code:</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" id="postalcode" name="postalcode" value="" placeholder="postal code">
                                                    @if ($errors->has('password'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('postalcode') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="ZipCode">Profile Image:</label>
                                                <div class="col-md-9">
                                                    <input type="file" class="form-control" name="profile_image" name="profile_image" id="profile_image" placeholder="profile Image">
                                                    @if ($errors->has('profile_image'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('profile_image') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="lastName">Last Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="last_name" id="lastName" placeholder="Last Name" value="{{ old('last_name') }}">
                                                    @if ($errors->has('last_name'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('last_name') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="phoneNumber">Phone:</label>
                                                <div class="col-md-9">
                                                    <input type="tel" class="form-control" id="phoneNumber" name="phone" placeholder="Write Member Phone e.g(+15876046444)" value="{{ old('phone') }}" >
                                                    <p class="alert-info">Please Enter Valid Phone Number Like <b style="color: black">+15876046444</b></p>
                                                    @if ($errors->has('phone'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="cpassword">Confirm Password:</label>
                                                <div class="col-md-9">
                                                    <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password">
                                                    @if ($errors->has('cpassword'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('cpassword') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="cpassword">Gender:</label>
                                                <div class="col-md-9">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" id="gender" value="male" checked="">Male</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" id="gender" value="female">Female</label>
                                                    @if ($errors->has('gender'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('gender') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3">Expiration Date:</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}">
                                                    </div>
                                                    @if ($errors->has('expiration_date'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('expiration_date') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="postalAddress">Address:</label>
                                                <div class="col-md-9">
                                                    <textarea rows="3" name="address" class="form-control" id="postalAddress" placeholder="Postal Address">{{ old('address') }}</textarea>
                                                    @if ($errors->has('address'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group" id="addMoreFields">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-success" onclick="addMoreAnsFields();" type="button">Add More Member  </button>
                                            <button class="btn btn-danger hide" id="removebtn"  onclick="removeFields()" type="button">Remove Last Member  </button>
                                            <button type="submit" class="btn btn-primary">Save Member Record</button>                                        &nbsp;
                                            <input type="reset" class="btn btn-info hidden-xs" value="Reset">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--row end-->
        </section>
    </aside>
@endsection
<!-- begining of page level js -->
@section('pagejs')

    <!-- InputMask -->
    <script src="{{ asset('vendors/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/input-mask/jquery.inputmask.extensions.js')}}" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('vendors/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/select2/select2.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/iCheck/icheck.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/iCheck/demo/js/custom.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/autogrow/js/jQuery-autogrow.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('vendors/card/jquery.card.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/formelements.js')}}" type="text/javascript"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- end of page level js -->

    <script>

        $('#dob').datepicker({ dateFormat: 'yy-mm-dd' });
        $('#expiration_date').datepicker({ dateFormat: 'yy-mm-dd' });

        var totalFields = 0;
        function addMoreAnsFields() {

            totalFields++;
            //fieldName = 'A:' + totalFields;
            if (totalFields > 5) {
                alert('you can only add 5 Member fields in each Form');
                return false;
            }
//            var inputField = getAnsFieldHTML(fieldName , totalFields);
            var inputField = getMemberField( totalFields);
            $(inputField).insertBefore("#addMoreFields");
            if(totalFields > 0){ $('#removebtn').removeClass('hide')}
        }

        function removeFields() {
            $(".remove"+totalFields).remove();
            totalFields--;
            if(totalFields == 0){ $('#removebtn').addClass('hide')}
        }

        function getAnsFieldHTML(fieldName) {

            var html = '';
            html += '<div class="remove'+totalFields+'">';
            html += '<div class="hr-line-dashed"></div>';
            html += '<div class="form-group">';
            html += '<label for="a1" class="col-sm-2 control-label">' + fieldName + '</label>';
            html += '<div class="col-sm-6">';
            html += '<input type="text" name="form_a[]" class="form-control"';
            html += 'placeholder="Required"';
            html += '</div>';
            html += '</div>';
            html += '<i class="fa fa-minus-square-o" style="font-size:35px;color:red" onclick="removeFields()"></i>';
            html += '</div>';

            return html;
        }

        function getMemberField(fieldName)
        {
            var html = '';
            html += '<div class="clearfix remove'+totalFields+'">';
            html += '<div class="row">';
            html += '<div class="col-md-4">';
            html += '<div class="form-group">';
            html += '<div class="col-md-12">';
            html += '<label class="control-labe" for="firstName">First Name:</label>';
            html += '<input type="text" name="M_first_name[]" required value="" class="form-control" id="M_first_name" placeholder="First Name">';

            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4">';
            html += '<div class="form-group">';
            html += '<div class="col-md-12">';
            html += '<label class="control-labe" for="Middle Name">Middle Name:</label>';
            html += '<input type="text" name="M_middle_name[]" required value="" class="form-control" id="M_middle_name" placeholder="Middle Name">';

            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4">';
            html += '<div class="form-group">';
            html += '<div class="col-md-12">';
            html += '<label class="control-labe" for="firstName">Last Name:</label>';
            html += '<input type="text" name="M_last_name[]" required value="" class="form-control" id="M_last_name" placeholder="Last Name">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            return html;
        }
    </script>

@endsection
<!-- end of page level js -->
