@extends('account.index')

@section('content')

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="module module-login span4 offset1">
				<form class="form-vertical" action="{{ route('students.post_login') }}" method="POST">
					@csrf
					<div class="module-head">
						<h3>Đăng nhập</h3>
					</div>
					<div class="module-body">
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="text" name="email" placeholder="Email" value="{{ Request::old('email') }}" autofocus>
								@if($errors->has('email'))
									{{ $errors->first('email')}}
								@endif
							</div>
						</div>
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="password" name="password" placeholder="Mật khẩu">
								@if($errors->has('password'))
									{{ $errors->first('password')}}
								@endif
							</div>
						</div>
					</div>
					<div class="module-foot">
						<div class="control-group">
							<div class="controls clearfix">
								<button type="submit" class="btn btn-primary pull-right">Đăng nhập</button>
								<label class="checkbox">
									<input type="checkbox" name="remember" id="remember"> Nhớ mật khẩu
								</label>
							</div>
						</div>
						<a href="{{ route('students.create') }}">Đăng ký tài khoản</a>
					</div>
				</form>
			</div>
			<div class="module module-login span4 offset2">
				<div class="module-head">
					<h3>Student Section</h3>
				</div>
				<div class="module-body">
                    <p><a href="{{ URL::route('search-book') }}"><strong>Tìm kiếm sách</strong></a></p>
				</div>
			</div>
        </div>
	</div>
</div>
@include('account.style')
@stop
