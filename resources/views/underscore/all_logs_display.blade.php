<tr>
	<td><%= obj.id %></td>
	<td><%= obj.book_student_id %></td>
	<td><%= obj.book_student.book.title %></td>
	<td><%= obj.book_student.student.id %></td>
	<td><%= obj.book_student.student.full_name %></td>
	<td><%= obj.created_at %></td>
	<td><%= obj.return_time %></td>
</tr>