@extends('layout.index')

@section('custom_top_script')
@stop

@section('content')
<div class="content">
    <div class="module">
        <div class="module-head">
            <h3>Thêm sách</h3>
        </div>
        <div class="module-body">
            <form class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class="control-label">Tiêu Đề Sách</label>
                    <div class="controls">
                        <input type="text" id="title" data-form-field="title" placeholder="Nhập tên sách vào đây..." class="span8">
                        <input type="hidden"  data-form-field="token"  value="{{ csrf_token() }}">
                        <input type="hidden"  data-form-field="auth_user"  value="{{ auth()->user()->id }}">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Tên tác giả</label>
                    <div class="controls">
                        <input type="text" id="author" data-form-field="author" placeholder="Nhập tên tác giả của cuốn sách ở đây..." class="span8">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="basicinput">Mô tả sách</label>
                    <div class="controls">
                        <textarea class="span8" id="description" data-form-field="description" rows="5" placeholder="Nhập vài dòng về cuốn sách vào đây"></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="basicinput">Loại</label>
                    <div class="controls">
                        <select tabindex="1" id="category" data-form-field="category" data-placeholder="Chọn loại sách.." class="span8">
                            @foreach($categories_list as $category)
                                <option value="{{ $category->id }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Số lượng</label>
                    <div class="controls">
                        <input type="total" id="total" data-form-field="total" placeholder="Số lượng?" class="span8">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="button" class="btn btn-inverse" id="addbooks">Thêm sách</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('custom_bottom_script')

    <script type="text/javascript" src="{{ asset('static/custom/js/script.addbook.js') }}"></script>

@stop
