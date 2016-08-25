<h2>Site map:</h2>
<form method='post' id="add_map">
	<textarea type='text' name='hgh_site_contact_map'><?= stripslashes(get_option('hgh_site_contact_map')) ?></textarea>
	<span class='description'>Code map in Google Maps</span>
	<input type='submit' class='button' name='save' value='Save' />
</form>
<h2>Site contacst:</h2>
<span class='description'>Please do not use the code <b>'map'</b> because it is used for a <b>'Site map'</b> to avoid problems</span>
<form method='post' id="add_contact" >
	<table class="bordered">
		<thead>
			<tr>
				<th>Code_name</th>        
				<th>Title</th>
				<th>Value</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr class='menu' style="position: relative; z-index: 1;">
				<td>
					<input type='text' name='code' pattern="[a-z0-9-_]{1,55}" required title="Letters, numbers, hyphens, and underscores"   maxlength="48"/>
					<span class="error" style="display: none;"></span>
				</td>
				<td>
					<input type='text' name='title' required maxlength="64"/>
					<span class="error" style="display: none;"></span>
				</td>
				<td>
					<input type='text' name='value' required  maxlength="224"/>
					<span class="error" style="display: none;"></span>
				</td>
				<td colspan=2><input type='submit' name='save' value='create' /></td>
			</tr>
		</tbody>
		<tbody id="rows">
		 
			<?php 
				global $db;
				foreach ($db->select() as $value)
				{
			?>
				<tr data-contact-id="<?= $value->id ?>">
					<td><?= $value->code ?></td>
					<td><?= $value->title ?></td>
					<td><?= $value->value ?></td>
					<td><button class="edit">edit</button></td>
					<td><button class="delete">delete</button></td>
				</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</form>