<h1><?php print $Args["person"]["name"]; ?> telefonszámai</h1>

<a href="index.php?r=person/list">[Vissza]</a>

<form method="post" action="index.php?r=phonenumber/new&person_id=<?php print $Args["person"]["person_id"]; ?>">
	<table>
		<thead>
			<tr>
				<th>Telefonszám hozzáadása</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<input type="text" name="phone[phone]">
					<input type="hidden" name="phone[person_id]" value="<?php print $Args["person"]["person_id"]; ?>">
				</td>
			</tr>
			<tr>
				<td style="text-align: center;">
					<input type="submit" name="form_submitted" value="Mentés">
				</td>
			</tr>
		</tbody>
	</table>
</form>

<table>
	<thead>
		<tr>
			<th>Telefonszám</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
		<?php
			foreach ($Args["numbers"] as $Number) {
				print '
					<tr>
						<td>'.$Number["phone"].'</td>
						<td>
							<a href="index.php?r=phonenumber/delete&phonenumber_id='.$Number["phonenumber_id"].'">[Törlés]</a>
						</td>
					</tr>
				';
			}
		?>
	</tbody>
</table>