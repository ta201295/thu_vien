@extends('layout.index')

@section('custom_top_script')
@stop

@section('content')
<div class="content">
    <div class="module">
        <div class="module-head">
            <h3>Phát hành một cuốn sách mới</h3>
        </div>
        <div class="module-body">
            <form class="form-horizontal row-fluid" action="{{ route('issue-log.store') }}" method="POST">
                @csrf
                <div class="control-group">
                    <label class="control-label" for="basicinput">Chọn mẫu đã duyệt</label>
                    <div class="controls">
                        <select tabindex="1" name="book_student_id" class="span8">
                            <option value="">Chọn mẫu</option>
                            @foreach($approvedBooks as $approvedBook)
                                <option value="{{ $approvedBook->id }}">{{ $approvedBook->book->title }} - {{ $approvedBook->student->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-inverse">Sách phát hành</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="module">
        <div class="module-head">
            <h3>Trả lại sách đã phát hành</h3>
        </div>
        <div class="module-body">
            <form class="form-horizontal row-fluid" action="{{ route('issue-log.return') }}" method="POST">
                @csrf
                <div class="control-group">
                    <label class="control-label" for="basicinput">Chọn sách đã phát hành</label>
                    <div class="controls">
                        <select tabindex="1" name="book_student_id" class="span8">
                            <option value="">Chọn sách</option>
                            @foreach($borrowedBooks as $borrowedBook)
                                <option value="{{ $borrowedBook->id }}">{{ $borrowedBook->book->title }} - {{ $borrowedBook->student->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-inverse">Trả sách</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('custom_bottom_script')
<script type="text/javascript" src="{{asset('static/custom/js/script.logs.js') }}"></script>
<script type="text/template" id="all_logs_display">
    @include('underscore.all_logs_display')
</script>
@stop
