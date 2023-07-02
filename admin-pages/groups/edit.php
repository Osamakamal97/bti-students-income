<?php

// Get All Trianers
$trianers = get_user_by_role_name('editor');

$group_id = $_GET['group_id'];

$group_trianer = $wpdb->get_results("SELECT {$wpdb->prefix}users.user_nicename, {$wpdb->prefix}users.id from {$wpdb->prefix}users
            LEFT JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}users.ID={$wpdb->prefix}usermeta.user_id
            WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
            AND {$wpdb->prefix}usermeta.meta_value={$group_id}")[0]->id;

// Get group name
$group_name = $wpdb->get_results("SELECT name FROM {$wpdb->prefix}TERMS WHERE term_id=$group_id")[0]->name;

if (isset($_POST['trianer'])) {
    $user_id = $_POST['trianer'];
    $new_trianer = $wpdb->get_results("SELECT * from {$wpdb->prefix}usermeta WHERE user_id=$user_id AND meta_key='group_id'");
    if ($new_trianer[0] == null) {
        $wpdb->query("INSERT INTO {$wpdb->prefix}usermeta (USER_ID, META_KEY, META_VALUE) VALUES ($user_id, 'group_id', $group_id)");
        add_settings_error('group-get-trainner', 'group-get-trainner', 'Trianer get group succesfully.', 'success');
        settings_errors('group-get-trainner');
    } else {
        add_settings_error('group-has-trainner', 'group-has-trainner', 'This trianer has a group.', 'error');
        settings_errors('group-has-trainner');
    }
}

?>

<h1 class="wp-heading-inline">Edit <?=$group_name?> Group</h1>

<form action="" id="creategroup" method="post" class="validate">

   <table class="form-table" role="presentation">
      <tbody>
         <tr class="form-field form-required">
            <th scope="row"><label for="user_login">Trainer <span class="description">(required)</span></label></th>
            <td>
               <select name="trianer" id="trianer">
                  <option selected="selected" value="0">Select Trainer</option>
                  <?php foreach ($trianers as $trianer): ?>
                  <option value="<?=$trianer->id?>"
                     <?php if ($group_trianer == $trianer->id): ?> selected="selected" <?php endif;?> >
                     <?=$trianer->user_nicename?></option>
                  <?php endforeach;?>
               </select>
            </td>
         </tr>
      </tbody>
   </table>
   <p class="submit"><input type="submit" name="createuser" id="createusersub" class="button button-primary" value="Add New User"></p>
</form>