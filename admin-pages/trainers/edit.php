<?php

$trainer_id = $_GET['trainer_id'];

$trainer = get_userdata($trainer_id);

$groups = active_cohort_groups();

$trainer_group = get_usermeta($trainer_id, 'group_id');

// 1 -> Technical, 2 -> others
$trainer_type = get_usermeta($trainer_id, 'trainer_type');

if (isset($_POST['submit'])) {
    $selected_group_id = $_POST['group'];
    $selected_trainer_type = $_POST['type'];
    if ($selected_trainer_type == 1) {

        // Check if there is only one technical trainer for single group

        $all_this_group = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta
                                             WHERE user_id <> $trainer_id
                                             AND meta_key='group_id'
                                             AND meta_value=$selected_group_id");

        $trainers_type = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta
                                             WHERE user_id <> $trainer_id
                                             AND meta_key='trainer_type'
                                             AND meta_value=1");
        $is_group_name_taken = false;

        foreach ($all_this_group as $g) {
            foreach ($trainers_type as $tk) {
                if ($g->user_id == $tk->user_id) {
                    $is_group_name_taken = true;
                    add_settings_error('update-trainner', 'update-trainner', 'Technical trianer can not be at two groups.', 'error');
                    settings_errors('update-trainner');
                    break;
                }
            }
        }

        if (!$is_group_name_taken) {
            $name = $_POST['name'];
            $email = $_POST['email'];

            wp_update_user([
                'ID' => $trainer_id,
                'user_nicename' => $name,
                'user_email' => $email,
            ]);
            update_usermeta($trainer_id, 'group_id', $selected_group_id);
            update_usermeta($trainer_id, 'trainer_type', 1);

            add_settings_error('update-trainner', 'update-trainner', 'Trianer Updated succesfully.', 'success');
            settings_errors('update-trainner');
        }

    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];

        wp_update_user([
            'ID' => $trainer_id,
            'user_nicename' => $name,
            'user_email' => $email,
        ]);
        update_usermeta($trainer_id, 'group_id', $selected_group_id);
        update_usermeta($trainer_id, 'trainer_type', 2);

        add_settings_error('update-trainner', 'update-trainner', 'Trianer Updated succesfully.', 'success');
        settings_errors('update-trainner');
    }

}

?>
<h1>Edit <?=$trainer->user_nicename?></h1>
<hr class="wp-header-end">
<br>

<form action="" id="creategroup" method="post" class="validate">

   <table class="form-table" role="presentation">
      <tbody>
         <tr class="form-field form-required">
            <th scope="row"><label for="user_login">Name <span class="description">(required)</span></label></th>
            <td>
                  <input type="text" name="name" value="<?=$trainer->user_nicename?>">
            </td>
         </tr>
         <tr class="form-field form-required">
            <th scope="row"><label for="user_login">email <span class="description">(required)</span></label></th>
            <td>
                  <input type="text" name="email" value="<?=$trainer->user_email?>">
            </td>
         </tr>
         <tr class="form-field form-required">
            <th scope="row"><label for="user_login">Trainer <span class="description">(required)</span></label></th>
            <td>
               <select name="group" id="group">
                  <option value="0">Select Group</option>
                  <?php foreach ($groups as $group): ?>
                  <option value="<?=$group->term_id?>"
                     <?php if ($trainer_group == $group->term_id): ?> selected="selected" <?php endif;?> >
                     <?=$group->name?></option>
                  <?php endforeach;?>
               </select>
            </td>
         </tr>
         <tr class="form-field form-required">
            <th scope="row"><label for="user_login">Trainer <span class="description">(required)</span></label></th>
            <td>
               <select name="type" id="type">
                  <option value="1"
                     <?php if ($trainer_type == 1): ?> selected="selected" <?php endif;?> >Technical</option>
                    <option value="2"
                     <?php if ($trainer_type == 2): ?> selected="selected" <?php endif;?> >Other</option>
               </select>
            </td>
         </tr>
      </tbody>
   </table>
	<?php submit_button(__('Edit Trainer'), 'primary', 'submit', true, array('id' => 'addusersub'));?>
</form>