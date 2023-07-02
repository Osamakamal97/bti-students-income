<?php

global $wpdb;

include_once __DIR__ . '/../functions.php';

if ($_GET['action'] == 'edit') {
    // Select which page to switch at
    $page = '/edit.php';
} else if ($_GET['action'] == 'create') {

    $page = '/create.php';
} else if ($_GET['action'] == 'delete') {
    // usermeta - groups - jobs
    wp_delete_user($_GET['trainer_id']);

    $page = '/table.php';

    $trainers = get_user_by_role_name('editor');

    $cohorts = get_taxonomy('cohorts');

    $groups = active_cohort_groups();
} else {

    $page = '/table.php';

    $trainers = get_user_by_role_name('editor');

    $cohorts = get_taxonomy('cohorts');

    $groups = active_cohort_groups();
}

?>

<div class="wrap">
<?php include_once __DIR__ . $page;?>
</div>