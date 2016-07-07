<h1>Telefonkönyv</h1>

<form method="post" action="index.php?r=person/new">
	<table>
		<thead>
			<tr>
				<th colspan="2">Új személy felvétele</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Név:</td>
				<td><input type="text" name="person[name]"></td>
			</tr>
			<tr>
				<td>Cím:</td>
				<td><input type="text" name="person[address]"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" name="form_submitted" value="Mentés">
				</td>
			</tr>
		</tbody>
	</table>
</form>

<table>
	<tbody>
		<?php
			foreach ($Args["people"] as $Person) {
				print '
					<tr>
						<td>
							<a href="index.php?r=phonenumber/list&person_id='.$Person["person_id"].'">'.$Person["name"].'</a>
							<br>
							'.$Person["address"].'
						</td>
						<td style="width: 50px;">
							<a href="index.php?r=person/delete&person_id='.$Person["person_id"].'">[Törlés]</a>
						</td>
					</tr>
				';
			}
		?>
	</tbody>
</table>