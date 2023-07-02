<?php

$trainee_id = $_GET['trainee_id'];

$trainee = get_userdata($trainee_id);

$groups = active_cohort_groups();

if ($_POST['submit']) {

    // New Trainees data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $arabic_name = $_POST['arabic_name'];
    $national_number = $_POST['national_number'];
    $mobile_number = $_POST['mobile_number'];
    $gender = $_POST['gender'];

    $group_id = $_POST['group'];

    // Check if national number is exsist
    $args = array(
        'role' => 'author',
        'orderby' => 'user_nicename',
        'order' => 'ASC',
    );
    // Check if there is any username equal to this username
    $check_username = false;
    $usernames = $wpdb->get_results("SELECT ID, user_login FROM {$wpdb->prefix}users WHERE user_login='$username'");
    foreach ($usernames as $e_username) {
        if ($e_username->ID != $trainee_id && $e_username->user_login == $username) {
            $check_username = true;
        }
    }
    // Check if there is any email equal to this user
    $check_email = false;
    $emails = $wpdb->get_results("SELECT ID, user_email FROM {$wpdb->prefix}users WHERE user_email='$email'");
    foreach ($emails as $e_email) {
        if ($e_email->ID != $trainee_id && $e_email->user_email == $email) {
            $check_email = true;
        }
    }
    // Check if there is any national number equal to this username
    $check_national_number = false;
    $national_numbers = $wpdb->get_results("SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key='national_number'");
    foreach ($national_numbers as $e_national_number) {
        if ($e_national_number->user_id != $trainee_id && $e_national_number->meta_value == $national_number) {
            $check_national_number = true;
        }
    }
    // Check if there is any mobile number equal to this username
    $check_mobile_number = false;
    $mobile_numbers = $wpdb->get_results("SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key='mobile_number'");
    foreach ($mobile_numbers as $e_mobile_number) {
        if ($e_mobile_number->user_id != $trainee_id && $e_mobile_number->meta_value == $mobile_number) {
            $check_mobile_number = true;
        }
    }

    if ($check_username) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This username is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if (preg_match('/[^A-Za-z0-9]/', $username)) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'User name should only be a English characters.', 'error');
        settings_errors('user-is-exsists');
    } else if ($check_email) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Email is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if ($check_national_number) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This national number is exsits.', 'error');
        settings_errors('user-is-exsists');
    } else if ($check_mobile_number) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Mobile number is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if (is_numeric($mobile_number) && strlen($mobile_number) != 10) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'The mobile number should be numbers with 10 digits.', 'error');
        settings_errors('user-is-exsists');
    } else if ($email == null || $national_number == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'There is some required fields need to be felled.', 'error');
        settings_errors('user-is-exsists');
    } else {
        $wpdb->update("{$wpdb->prefix}users", [
            'user_login' => $username,
            'user_nicename' => $arabic_name,
            'user_email' => $email,
        ],
            array('ID' => $trainee_id)
            // , array('%s', '%d'), array('%d')
        );
        update_user_meta($trainee_id, 'national_number', $national_number);
        update_user_meta($trainee_id, 'mobile_number', $mobile_number);
        update_user_meta($trainee_id, 'gender', $gender);
        update_user_meta($trainee_id, 'group_id', $group_id);

        add_settings_error('update-trainner', 'update-trainner', 'Trianer Updated succesfully.', 'success');
        settings_errors('update-trainner');

    }

}

?>
<div class="wrap">
  <h1>Edit <?=$trainee->user_nicename?> Information </h1>
  <hr class="wp-header-end">
  <form name="Student" method="post" action="" class="validate">
    <table class="form-table" role="presentation">
      <tbody>
      <tr class="form-field form-required">
          <th scope="row"><label for="username">User Name <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="username" id="username" class="regular-text" value="<?=$trainee->user_login?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="arabic_name">Arabic name </label></th>
          <td>
            <input type="text" name="arabic_name" id="arabic_name" class="regular-text" value="<?=$trainee->user_nicename?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Email <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="email" value="<?=$trainee->user_email?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="national_number">National number <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="national_number" id="national_number" class="regular-text"
            value="<?=get_user_meta($trainee->ID, 'national_number', true)?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="mobile_number">Mobile number </label></th>
          <td>
            <input type="text" name="mobile_number" id="mobile_number" class="regular-text"
            value="<?=get_user_meta($trainee->ID, 'mobile_number', true)?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Gender </label></th>
          <td>
          <!-- <input type="text" list="group" name="group" placeholder="Select group Student"> -->
          <select name="gender">
          <!-- <option value="" disabled selected>Select a group</option> -->
            <option value="male" <?php if (get_user_meta($trainee_id, 'gender', true) == 'male'): ?> selected <?php endif?>>Male</option>
            <option value="female" <?php if (get_user_meta($trainee_id, 'gender', true) == 'female'): ?> selected <?php endif?>>Female </option>
          </select>
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Group <span class="description">(required)</span></label></th>
          <td>
          <!-- <input type="text" list="group" name="group" placeholder="Select group Student"> -->
          <select name="group">
            <option value="" disabled selected>Select a group</option>
            <?php foreach ($groups as $group): ?>
              <option value="<?=$group->term_id?>"
              <?php if ($group->term_id == get_usermeta($trainee_id, 'group_id', true)): ?> selected <?php endif?>><?=$group->name?> </option>
            <?php endforeach?>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <?php submit_button(__('Edit trainee'), 'primary', 'submit', true, array('id' => 'addnewstudent'));?>
  </form>
</div>
