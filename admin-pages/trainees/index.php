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
    wp_delete_user($_GET['trainee_id']);

} else {

    $page = '/table.php';

    $trainees = get_user_by_role_name('author');
    
}

?>

<div class="wrap">
<?php include_once __DIR__ . $page;?>
</div>