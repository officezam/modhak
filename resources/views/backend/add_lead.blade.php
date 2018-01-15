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
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Member Form</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form class="form-horizontal" method="POST" action="{{ route('saveLead') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="col-md-6">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="firstName">Lead Name:</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" required value="{{ old('name') }}" class="form-control" id="name" placeholder="Lead Name">
                                                    @if ($errors->has('name'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('name') }}</strong>
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
                                                <label class="control-label col-md-4" for="postalAddress">Lead Description:</label>
                                                <div class="col-md-8">
                                                    <textarea rows="3" name="description" class="form-control" id="description" placeholder="Lead Description">{{ old('description') }}</textarea>
                                                    @if ($errors->has('description'))
                                                        <div class=" has-error">
                                                        <span class="control-label has-error">
                                                            <strong>{{ $errors->first('description') }}</strong>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-labe" for="firstName">Question:</label>
                                                        <input type="text" name="question[]" required="" value="" class="form-control" id="question" placeholder="Lead Question">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-labe" for="Middle Name">Answer:</label>
                                                        <input type="text" name="answer[]" required="" value="" class="form-control" id="answer" placeholder="Answer">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-labe" for="firstName">Static Reply(Optional):</label>
                                                        <input type="text" name="static_reply[]" value="" class="form-control" id="static_reply" placeholder="Static Reply">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-labe" for="firstName">Question No</label>
                                                        <input type="number" name="question_no[]" value="" required class="form-control" id="question_no" placeholder="Select Question Number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="addMoreFields">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-success" onclick="addMoreAnsFields();" type="button">Add Lead Question</button>
                                            <button class="btn btn-danger" id="removebtn"  onclick="removeFields()" type="button">Remove Last Question</button>
                                            <button type="submit" class="btn btn-primary">Save Lead Record</button>                                        &nbsp;
                                            <input type="reset" class="btn btn-info hidden-xs" value="Reset">
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
            <script>
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
                    html += '<div class="col-md-3">';
                    html += '<div class="form-group">';
                    html += '<div class="col-md-12">';
                    html += '<label class="control-labe" for="firstName">Question:</label>';
                    html += '<input type="text" name="question[]" required value="" class="form-control" id="question" placeholder="Lead Question">';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-3">';
                    html += '<div class="form-group">';
                    html += '<div class="col-md-12">';
                    html += '<label class="control-labe" for="Middle Name">Answer:</label>';
                    html += '<input type="text" name="answer[]" required value="" class="form-control" id="answer" placeholder="Answer">';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-3">';
                    html += '<div class="form-group">';
                    html += '<div class="col-md-12">';
                    html += '<label class="control-labe" for="firstName">Static Reply(Optional):</label>';
                    html += '<input type="text" name="static_reply[]" value="" class="form-control" id="static_reply" placeholder="Static Reply">';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    html += '<div class="col-md-3">';
                    html += '<div class="form-group">';
                    html += '<div class="col-md-12">';
                    html += '<label class="control-labe" for="firstName">Question No</label>';
                    html += '<input type="number" name="question_no[]" value="" class="form-control" id="question_no" placeholder="Select Question Number">';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    return html;
                }
            </script>

@endsection