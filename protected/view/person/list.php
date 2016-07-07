<h1>Telefonkönyv</h1>

<form method="post" action="index.php?r=person/new">
	<table>
		<thead>
			<tr>
				<th>Azonosító</th>
				<th>Név</th>
				<th>Cím</th>
				<th>Műveletek</th>
			</tr>
		</thead>
		
		<tbody>
			<tr>
				<td></td>
				<td><input type="text" name="person[name]"></td>
				<td><input type="text" name="person[address]"></td>
				<td><input type="submit" name="form_submitted" value="Mentés"></td>
			</tr>
		
			<?php
				foreach ($Args["people"] as $Person) {
					print '
						<tr>
							<td>'.$Person["person_id"].'</td>
							<td>'.$Person["name"].'</td>
							<td>'.$Person["address"].'</td>
							<td>
								<a href="index.php?r=phonenumber/list&person_id='.$Person["person_id"].'">[Telefonszámok]</a>
								<a href="index.php?r=person/delete&person_id='.$Person["person_id"].'">[Törlés]</a>
							</td>
						</tr>
					';
				}
			?>
		</tbody>
	</table>
</form>