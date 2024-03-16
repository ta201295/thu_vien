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
							@if ($bookStudent->status == App\Models\BookStudent::STATUS_BORROWED && Carbon\Carbon::now()->gt($bookStudent->expired_time))
								<td style="color:red">
									{{ $bookStudent->expired_time }}<form action="{{route('book-student.extend', ['bookStudent' => $bookStudent->id])}}"
										><button type="submit" style="background-color: #3cbc8d">Gia Hạn</button></form>
								</td>
							@else
								<td>{{ $bookStudent->expired_time }}</td>
							@endif
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
