@extends('account.index')

@section('content')

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="module module-login span4 offset4">
				<form class="form-vertical" action="{{ route('students.store') }}" method="POST">
					@csrf
					<div class="module-head">
						<h3>Đăng ký tài khoản sinh viên</h3>
					</div>
					<div class="module-body">
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="text" placeholder="Email" name="email" value="{{ old('email') }}">
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
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu">
								@if($errors->has('password_confirmation'))
									{{ $errors->first('password_confirmation')}}
								@endif
							</div>
						</div>
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="text" placeholder="Họ" name="last_name" value="{{ old('last_name') }}">
								@if($errors->has('last_name'))
									{{ $errors->first('last_name')}}
								@endif
							</div>
						</div>
						<div class="control-group">
							<div class="controls row-fluid">
								<input class="span12" type="text" placeholder="Tên" name="first_name" value="{{ old('first_name') }}">
								@if($errors->has('first_name'))
									{{ $errors->first('first_name')}}
								@endif
							</div>
						</div>
					</div>
					<div class="module-foot">
						<div class="control-group">
							<div class="controls clearfix">
								<button type="submit" class="btn btn-info pull-right">Tạo tài khoản</button>
							</div>
						</div>
						<a href="{{ route('main') }}">Bạn đã có tài khoản?</a>
					</div>
					@if(session()->has('system_error'))
						<div class="alert alert-error">
							{{ session()->get('system_error') }}
						</div>
					@endif
				</form>
			</div>
		</div>
	</div>
</div>
@include('account.style')
@stop
