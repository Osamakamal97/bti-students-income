<?php

$trainer_id = $_GET['trainer_id'];

$trainer = get_userdata($trainer_id);

$groups = active_cohort_groups();

// 1 -> Technical, 2 -> others
if (isset($_POST['submit'])) {
    $selected_group_id = $_POST['group'];
    $selected_trainer_type = $_POST['type'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $old = [
        'username' => $username,
        'email' => $email,
        'type' => $selected_trainer_type,
        'group' => $selected_group_id,
    ];
    // Sign old data to session called old
    $_SESSION['old'] = $old;
    // Validations
    if ($username == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Username is required.', 'error');
        settings_errors('user-is-exsists');
    } else if (username_exists($username)) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This user data is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if (preg_match('/[^A-Za-z0-9]/', $username)) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'User name should only be a english characters.', 'error');
        settings_errors('user-is-exsists');
    } else if ($email == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Email is required.', 'error');
        settings_errors('user-is-exsists');
    } else if (email_exists($email)) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This email is exsist.', 'error');
        settings_errors('user-is-exsists');
       
    } else if ($password == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Password is required.', 'error');
        settings_errors('user-is-exsists');
    } else {
        if ($selected_trainer_type == 1) {

            // Check if there is only one technical trainer for single group
            $all_this_group = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta
                                             WHERE meta_key='group_id'
                                             AND meta_value='$selected_group_id'");

            $trainers_type = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta
                                             WHERE meta_key='trainer_type'
                                             AND meta_value=1");

            // Check if there is only one technical trainer for a group
            $is_group_name_taken = false;
            foreach ($all_this_group as $g) {
                foreach ($trainers_type as $tk) {
                    // if there is user id in a groups equal to user id from technical trainers this mean there is a tech trainer already
                    if ($g->user_id == $tk->user_id) {
                        $is_group_name_taken = true;
                        break;
                    }
                }
            }

            if (!$is_group_name_taken) {

                $trainer_id = wp_insert_user([
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_nicename' => $username,
                    'user_email' => $email,
                    'role' => 'editor',
                ]);
                add_user_meta($trainer_id, 'group_id', $selected_group_id);
                add_user_meta($trainer_id, 'trainer_type', 1);

                add_settings_error('update-trainner', 'update-trainner', 'Trianer create succesfully.', 'success');
                settings_errors('update-trainner');
                $_SESSION['old'] = null;
            } else {
                add_settings_error('update-trainner', 'update-trainner', 'Technical trianer can not be at two groups.', 'error');
                settings_errors('update-trainner');
            }

            } else {

            $trainer_id = wp_insert_user([
                'user_login' => $username,
                'user_pass' => $password,
                'user_nicename' => $username,
                'user_email' => $email,
                'role' => 'editor',
            ]);
            add_user_meta($trainer_id, 'group_id', $selected_group_id);
            add_user_meta($trainer_id, 'trainer_type', 2);

            add_settings_error('update-trainner', 'update-trainner', 'Trianer Updated succesfully.', 'success');
            settings_errors('update-trainner');
        }

    }
}

?>
<h1>Create Trainer <?=$trainer->user_nicename?></h1>
<hr class="wp-header-end">
<form action="" id="creategroup" method="post" class="validate">

   <table class="form-table" role="presentation">
      <tbody>
        <tr class="form-field form-required">
        <th scope="row"><label for="username">Name <span class="description">(required)</span></label></th>
        <td>
            <input type="text" name="username" id="username" value="<?php echo $_SESSION['old']['username'] ?>">
        </td>
        </tr>
        <tr class="form-field form-required">
        <th scope="row"><label for="email">email <span class="description">(required)</span></label></th>
        <td>
            <input type="text" name="email" id="" value="<?php echo $_SESSION['old']['email'] ?>">
        </td>
        </tr>
        <tr class="form-field form-required">
        <th scope="row"><label for="user_login">password <span class="description">(required)</span></label></th>
        <td>
            <input type="text" name="password" id="">
        </td>
        </tr>
        <tr class="form-field form-required">
        <th scope="row"><label for="user_login">Trainer <span class="description">(required)</span></label></th>
        <td>
            <select name="group" id="group">
                <option>Select Group</option>
                <?php foreach ($groups as $group): ?>
                <option value="<?=$group->term_id?>" <?php if ($_SESSION['old']['group'] == $group->term_id): ?> selected <?php endif?>>
                    <?=$group->name?></option>
                <?php endforeach;?>
            </select>
        </td>
        </tr>
        <tr class="form-field form-required">
        <th scope="row"><label for="user_login">Trainer <span class="description">(required)</span></label></th>
        <td>
            <select name="type" id="type">
                <option>Select Trainer Type</option>
                <option value="1" <?php if ($_SESSION['old']['type'] == 1): ?> selected <?php endif?>>Technical</option>
                <option value="2" <?php if ($_SESSION['old']['type'] == 2): ?> selected <?php endif?>>Other</option>
            </select>
        </td>
        </tr>
      </tbody>
   </table>
	<?php submit_button(__('Create Trainer'), 'primary', 'submit', true, array('id' => 'addusersub'));?>
</form>