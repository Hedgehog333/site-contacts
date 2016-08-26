<h2><?php _e('Site map', 'site-contacts') ?>:</h2>
<form method='post' id="add_map">
	<textarea type='text' name='hgh_site_contact_map'><?= stripslashes(get_option('hgh_site_contact_map')) ?></textarea>
	<span class='description'><?php _e('Code map in Google Maps', 'site-contacts') ?></span>
	<input type='submit' class='button' name='save' value="<?php _e('Save', 'site-contacts') ?>" />
</form>
<h2><?php _e('Site contacst', 'site-contacts') ?>:</h2>
<span class='description'><?php _e('Please do not use the code', 'site-contacts') ?> <b>'map'</b> <?php _e("because it is used for a <b>'Site map'</b> to avoid problems", 'site-contacts') ?></span>
<form method='post' id="add_contact" >
	<table class="bordered">
		<thead>
			<tr>
				<th>Code_name</th>        
				<th><?php _e('Title', 'site-contacts') ?></th>
				<th><?php _e('Value', 'site-contacts') ?></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr class='menu' style="position: relative; z-index: 1;">
				<td>
					<input type='text' name='code' pattern="[a-z0-9-_]{1,55}" required title="<?php _e('Letters, numbers, hyphens, and underscores', 'site-contacts') ?>"   maxlength="48"/>
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
				<td colspan=2><input type='submit' name='save' value='<?php _e('create', 'site-contacts') ?>' /></td>
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
					<td><button class="edit"><?php _e('edit', 'site-contacts') ?></button></td>
					<td><button class="delete"><?php _e('delete', 'site-contacts') ?></button></td>
				</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</form>