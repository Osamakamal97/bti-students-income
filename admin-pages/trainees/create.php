<?php

global $wpdb;

$groups = active_cohort_groups();

if ($_POST['submit']) {

    // New Trainees data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $arabic_name = $_POST['arabic_name'];
    $national_number = $_POST['national_number'];
    $mobile_number = $_POST['mobile_number'];
    $group_id = $_POST['group'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    // Check if national number is exsist
    $args = array(
        'role' => 'author',
        'orderby' => 'user_nicename',
        'order' => 'ASC',
    );
    // Add enterd value to old value that will be signed to session called old
    $old = [
        'username' => $username,
        'email' => $email,
        'arabic_name' => $arabic_name,
        'national_number' => $national_number,
        'mobile_number' => $mobile_number,
        'gender' => $grogenderup_id,
        'group_id' => $group_id,
    ];
    // Sign old data to session called old
    $_SESSION['old'] = $old;
    // Get all trainees or students
    $trainees = get_users($args);
    // Variable to make sure that national number is not exsist in DB
    $is_national_number_exsists = false;
    foreach ($trainees as $trainee) {
        $n = get_user_meta($trainee->ID, 'national_number', true);
        if ($n == $national_number) {
            $is_national_number_exsists = true;
            break;
        }
    }

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
    } else if (email_exists($username)) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This email is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if ($national_number == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'National number is required.', 'error');
        settings_errors('user-is-exsists');
    } else if ($is_national_number_exsists) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'This national number is exsist.', 'error');
        settings_errors('user-is-exsists');
    } else if (is_numeric($national_number) && strlen($national_number) != 9) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'The national number should be numbers with 9 digits.', 'error');
        settings_errors('user-is-exsists');
    } else if (is_numeric($mobile_number) && strlen($mobile_number) != 10) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'The mobile number should be numbers with 10 digits.', 'error');
        settings_errors('user-is-exsists');
    } else if ($password == null) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Password is required.', 'error');
        settings_errors('user-is-exsists');
    } else {
        // $user_id = wp_create_user($username, $password, $email);
        $check_insert = $wpdb->insert("{$wpdb->prefix}users", [
            'user_pass' => wp_hash_password($password),
            'user_login' => $username,
            'user_nicename' => $arabic_name,
            'user_email' => $email,
        ]);
        if ($check_insert == 1) {
            $student_id = $wpdb->insert_id;
            wp_update_user(['ID' => $student_id, 'role' => 'author']);
            add_user_meta($student_id, 'gender', $gender);
            add_user_meta($student_id, 'national_number', $national_number);
            add_user_meta($student_id, 'mobile_number', $mobile_number);
            add_user_meta($student_id, 'group_id', $group_id);
            add_settings_error('user-is-exsists', 'user-is-exsists', 'Trainee create successfully.', 'success');
            settings_errors('user-is-exsists');
            $_SESSION['old'] = null;
        } else {
            add_settings_error('user-is-exsists', 'user-is-exsists', 'Error in insert student.', 'error');
            settings_errors('user-is-exsists');
        }
    }

}

?>
<div class="wrap">
  <h1>Add Trainee Information </h1>
  <hr class="wp-header-end">

  <form name="Student" method="post" action="" class="validate">
    <table class="form-table" role="presentation">
      <tbody>
        <tr class="form-field form-required">
          <th scope="row"><label for="username">User Name <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="username" id="username" class="regular-text"
            value="<?php echo $_SESSION['old']['username'] ?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="arabic_name">Arabic name </label></th>
          <td>
            <input type="text" name="arabic_name" id="arabic_name" class="regular-text"
            value="<?php echo $_SESSION['old']['arabic_name'] ?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Email <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="email"  value="<?php echo $_SESSION['old']['email'] ?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="national_number">National number <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="national_number" id="national_number" class="regular-text"
            value="<?php echo $_SESSION['old']['national_number'] ?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="mobile_number">Mobile number </label></th>
          <td>
            <input type="text" name="mobile_number" id="mobile_number" class="regular-text"
            value="<?php echo $_SESSION['old']['mobile_number'] ?>">
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Gender </label></th>
          <td>
          <!-- <input type="text" list="group" name="group" placeholder="Select group Student"> -->
          <select name="gender">
          <!-- <option value="" disabled selected>Select a group</option> -->
            <option value="male" <?php if ($_SESSION['old']['gender'] == 'male'): ?> selected <?php endif?>>Male</option>
            <option value="female" <?php if ($_SESSION['old']['gender'] == 'female'): ?> selected <?php endif?>>Female </option>
          </select>
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Group </label></th>
          <td>
          <select name="group">
          <option value="" disabled selected>Select a group</option>
          <?php foreach ($groups as $group): ?>
            <option value="<?=$group->term_id?>" <?php if ($group->term_id == $_SESSION['old']['group_id']): ?> selected <?php endif?>><?=$group->name?> </option>
          <?php endforeach?>
          </select>
          </td>
        </tr>
        <tr class="form-field form-required">
          <th scope="row"><label for="user_login">Password <span class="description required">*</span></label></th>
          <td>
            <input type="text" name="password">
          </td>
        </tr>
      </tbody>
    </table>
    <?php submit_button(__('Create trainee'), 'primary', 'submit', true, array('id' => 'addnewstudent'));?>
  </form>
</div>

