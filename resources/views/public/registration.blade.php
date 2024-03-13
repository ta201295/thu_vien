@extends('account.index')

@section('content')

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="module module-login span8 offset2">
				<form class="form-vertical" action="{{ route('book-student.store') }}" method="POST">
					<div class="module-head">
						<h3>Mẫu đăng ký mượn</h3>
					</div>
					<div class="module-body">
						<div class="control-group">
							<div class="controls row-fluid">
								<select class="span8" style="margin-bottom: 0;" name="book">
									<option value="0">select book</option>
									@foreach($book as $book)
				                        <option value="{{ $book->id }}">{{ $book->title }}</option>
				                    @endforeach
								</select>
								@if($errors->has('book'))
									{{ $errors->first('book')}}
								@endif

								<input class="span4" type="number" placeholder="Số cuốn" name="rollnumber" value="{{ Request::old('rollnumber') }}" />
								@if($errors->has('rollnumber'))
									{{ $errors->first('rollnumber')}}
								@endif
							</div>
						</div>

					</div>
					<div class="module-foot">
						<div class="control-group">
							<div class="controls clearfix">
								<button type="submit" class="btn btn-info pull-right">Đăng ký mượn sách</button>
								@csrf
							</div>
						</div>
						<a href="{{ route('search-book') }}">Quay lại!</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@include('account.style')

@stop
