<?php

global $wpdb;

include_once __DIR__ . '/../functions.php';

if ($_GET['action'] == 'edit' && user_can(get_current_user_id(), 'administrator')) {
    // Select which page to switch at
    $page = '/edit.php';

} else if (user_can(get_current_user_id(), 'editor')) {
    $group_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta WHERE user_id=" . get_current_user_id()
        . " AND meta_key='group_id'")[0]->meta_value;
    $_GET['group_id'] = $group_id;
    $page = '/show.php';
} else if ($_GET['action'] == 'show') {
    $page = '/show.php';
} else {
    // Select which page to switch at
    $page = '/table.php';

    // Get All groups
    $groups = active_cohort_groups();

}

?>
<div class="wrap">
<?php
include __DIR__ . $page;
?>
</div>
