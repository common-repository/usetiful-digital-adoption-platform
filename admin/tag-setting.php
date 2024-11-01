<?php $settings = get_option('usetiful_plugin_settings');

$usetiful_user_id = get_current_user_id();
$user_data        = get_userdata($usetiful_user_id);
$usetiful_role    = $user_data->roles[0];
$usetiful_fname   = $user_data->user_firstname;
$usetiful_lname   = $user_data->user_lastname;
?>
<div class="usetiful-tag-section">
    <h2 class="tagheding"><?php echo __('Tags', 'usetiful'); ?></h2>
    <table id="tags-tabel" cellpadding="5">
        <tr>
            <th><?php echo __('Tag name', 'usetiful'); ?></th>
            <th><?php echo __('Variable in WordPress', 'usetiful'); ?></th>
            <th align="center"><?php echo __('Action', 'usetiful'); ?></th>
        </tr>
        <?php
        if (!empty($settings['usetiful_tags_option'])) {
            $itr = 1;
            foreach ($settings['usetiful_tags_option'] as $ps_key => $ps_data) :

        ?>
                <tr class="tr-content" id="append_field_tag">
                    <td><input type="text" name="usetiful_option[<?php echo $ps_key ?>][tag]" class="add_tags" placeholder="<?php echo __('Add tag', 'usetiful'); ?>" value="<?php echo $ps_data['tag']; ?>"></td>
                    <td><input type="text" name="usetiful_option[<?php echo $ps_key ?>][tag_value]" class="add_tag_value" placeholder="<?php echo __('Add Variable in WordPress', 'usetiful'); ?>" value="<?php echo $ps_data['tag_value']; ?>"></td>
                    <?php if ($itr > 1) { ?>
                        <td align="center">
                            <button type="button" class="rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                        </td>
                    <?php } else {
                    ?>
                        <td align="center">
                            <button type="button" name="cls" class="cls rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                        </td>
                    <?php } ?>
                </tr>
            <?php
                $itr++;
            endforeach;
        } else {
            ?> <tr class="tr-content" data-id="1" id="append_field_tag">
                <td><input type="text" name="usetiful_option[0][tag]" class="add_tags" value="userId" placeholder="<?php echo __('Add tag', 'usetiful'); ?>"></td>
                <td><input type="text" name="usetiful_option[0][tag_value]" class="add_tag_value" placeholder="<?php echo __('Add Variable in WordPress', 'usetiful'); ?>" value="<?php echo (string)$usetiful_user_id; ?>"></td>
                <td align="center">
                    <button type="button" name="cls" class="cls rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                </td>
            </tr>
            <tr class="tr-content" data-id="1" id="append_field_tag">
                <td><input type="text" name="usetiful_option[1][tag]" class="add_tags" value="role" placeholder="<?php echo __('Add tag', 'usetiful'); ?>"></td>
                <td><input type="text" name="usetiful_option[1][tag_value]" class="add_tag_value" placeholder="<?php echo __('Add Variable in WordPress', 'usetiful'); ?>" value="<?php echo esc_html($usetiful_role); ?>"></td>
                <td align="center">
                    <button type="button" name="cls" class="cls rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                </td>
            </tr>
            <tr class="tr-content" data-id="1" id="append_field_tag">
                <td><input type="text" name="usetiful_option[2][tag]" class="add_tags" value="firstName" placeholder="<?php echo __('Add tag', 'usetiful'); ?>"></td>
                <td><input type="text" name="usetiful_option[2][tag_value]" class="add_tag_value" placeholder="<?php echo __('Add Variable in WordPress', 'usetiful'); ?>" value="<?php echo esc_html($usetiful_fname); ?>"></td>
                <td align="center">
                    <button type="button" name="cls" class="cls rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                </td>
            </tr>
            <tr class="tr-content" data-id="1" id="append_field_tag">
                <td><input type="text" name="usetiful_option[3][tag]" class="add_tags" value="lastName" placeholder="<?php echo __('Add tag', 'usetiful'); ?>"></td>
                <td><input type="text" name="usetiful_option[3][tag_value]" class="add_tag_value" placeholder="<?php echo __('Add Variable in WordPress', 'usetiful'); ?>" value="<?php echo esc_html($usetiful_lname); ?>"></td>
                <td align="center">
                    <button type="button" name="cls" class="cls rmv_field"><?php echo __('Remove', 'usetiful'); ?></button>
                </td>
            </tr>

        <?php } ?>
    </table>
    <span id="error_msg"></span>
    <button type="button" id="add_new_tag_field"><?php echo __('+ Add new', 'usetiful'); ?></button>
</div>