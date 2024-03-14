@extends('account.index')

@section('content')

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="module module-login span8 offset2">
				<form class="form-vertical" action="{{ route('book-student.store') }}" method="POST">
					<div class="module-head">
						<h3>Mẫu đăng ký mượn sách</h3>
					</div>
					<div class="module-body">
						<div class="control-group">
							<div class="controls row-fluid">
								<select class="span8" style="margin-bottom: 0;" name="book_id">
									<option value="0">select book</option>
									@foreach($books as $book)
				                        <option value="{{ $book->book_id }}">{{ $book->title }}</option>
				                    @endforeach
								</select>
								@if($errors->has('book_id'))
									{{ $errors->first('book_id')}}
								@endif

								<input class="span4" type="number" placeholder="Số cuốn" name="number" value="{{ old('number') }}" min="1" max="5"/>
								@if($errors->has('number'))
									{{ $errors->first('number')}}
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
