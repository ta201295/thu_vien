@extends('account.index')

@section('content')
<div class="wrapper">
	<div class="container" id="background">
		<div id="borrowed-list">
			<h1>Danh sách mượn</h1>

			<table class="table">
				<thead>
					<tr>
						<th>Tên sách</th>
						<th id="number">Số lượng</th>
						<th id="status">Trạng thái</th>
						<th id="expired_time">Hạn trả</th>
					</tr>
				</thead>

				<tbody>
					@foreach($bookStudents as $bookStudent)
						<tr>
							<td>{{ $bookStudent->book->title }}</td>
							<td>{{ $bookStudent->number }}</td>
							<td style="color: {{ App\Models\BookStudent::STATUS_CLASS[$bookStudent->status] }}">{{ App\Models\BookStudent::STATUS_TEXT[$bookStudent->status]}}</td>
							<td>{{ $bookStudent->expired_time }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			{{ $bookStudents->links() }}
		</div>
	</div>
</div>
@include('students.style')
@stop
