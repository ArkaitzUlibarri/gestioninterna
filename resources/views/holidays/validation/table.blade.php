<table>

	<thead>
		<tr>
			<th>Employee</th>
			<th v-for="(day,index) in days">
				@{{ weekdaysShort[index] }}, @{{ day.substr(5, 5) }}
			</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>
				<strong>Username</strong> 
				<p>Card Details</p>
			</td>

			<td v-for="day in days">
				<div>@{{day}}</div>
			</td>
		</tr>
	</tbody>
	
</table>