<tr data-book-student-id="<%= obj.id %>">
	<td><%= obj.student.id %></td>
	<td><%= obj.student.last_name %></td>
	<td><%= obj.student.first_name %></td>
	<td><%= obj.number %></td>
	<td><%= obj.book.title %></td>
	<td><%= obj.book.book_category.category %></td>
	<td>
		<a class="btn btn-success student-status" data-status="1">Approve</a>
		<a class="btn btn-danger student-status" data-status="2">Reject</a>
	</td>
</tr>
