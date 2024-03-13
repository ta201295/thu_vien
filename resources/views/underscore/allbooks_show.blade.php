<tr>
	<td><%= obj.book_id %></td>
	<td><%= obj.title %></td>
	<td><%= obj.author %></td>
	<td><%= obj.description %></td>
	<td><%= obj.book_category.category %>
	</td>
	<td><a class="btn btn-success"><%= obj.total_active %></a></td>
	<td><a class="btn btn-inverse"><%= obj.total %></a></td>
</tr>