@extends('layout.index')

@section('custom_top_script')
@stop

@section('content')
<div class="content">
    <div class="module">
        <div class="module-head">
            <h3>Sinh viên chờ phê duyệt để truy cập Thư viện</h3>
        </div>
        <div class="module-body">
            <div class="controls">
                <select class="span3" id="category_select">
                    <option value="0">Tất cả danh mục</option>
                    @foreach($book_categories as $book_category)
                        <option value="{{ $book_category->id }}">{{ $book_category->category }}</option>
                    @endforeach
                </select>

            </div>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ</th>
                        <th>Tên</th>
                        <th>Số lượng</th>
                        <th>Tên sách</th>
                        <th>Danh mục</th>
                        <th>Chấp thuận</th>
                    </tr>
                </thead>
                <tbody id="approval-table">
                    <tr class="text-center">
                        <td colspan="99"><i class="icon-spinner icon-spin"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="" id="branches_list" value="{{ json_encode($branch_list) }}">
    <input type="hidden" name="" id="student_categories_list" value="{{ json_encode($book_categories) }}">
    <input type="hidden" id="_token"  data-form-field="token"  value="{{ csrf_token() }}">

</div>

@stop

@section('custom_bottom_script')
<script type="text/javascript">
    var branches_list = $('#branches_list').val(),
        categories_list = $('#student_categories_list').val(),
        _token = $('#_token').val();
</script>
<script type="text/javascript" src="{{asset('static/custom/js/script.student-approval.js') }}"></script>
<script type="text/template" id="approvalstudents_show">
    @include('underscore.approvalstudents_show')
</script>
@stop
