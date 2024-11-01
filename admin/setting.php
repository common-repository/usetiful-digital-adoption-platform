<div class="usetiful-details-section">
	<table class="form-table">
		<tr>
			<th><label for="uf_key"><?php echo __('Usetiful Key'); ?><small>(<?php echo __('Add your Usetiful Key', 'usetiful'); ?>)</small></label></th>
			<td>
				<input type="text" name="usetiful_key" value="<?php if ($settings['usetiful_key']) echo $settings['usetiful_key']; ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th><label for="uf_key"><?php echo __('Backend Usetiful Key'); ?><small>(<?php echo __('Add Your Usetiful Key', 'usetiful'); ?>)</small></label></th>
			<td>
				<input type="text" name="admin_usetiful_key" value="<?php if ($settings['admin_usetiful_key']) echo $settings['admin_usetiful_key']; ?>" class="regular-text">
			</td>
		</tr>
	</table>
</div>