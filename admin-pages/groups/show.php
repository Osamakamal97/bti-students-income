<?php

global $wpdb;

include_once __DIR__ . '/../functions.php';

$group_id = $_GET['group_id'];

$group_name = $wpdb->get_results("SELECT name FROM {$wpdb->prefix}TERMS WHERE term_id=$group_id")[0]->name;

$args = array(
    'role' => 'author',
    'orderby' => 'user_nicename',
    'order' => 'ASC',
    'meta_key' => 'group_id',
    'meta_value' => $group_id,
);

$trainees = get_users($args);

?>

<h1 class="wp-heading-inline"><?=$group_name?> Group <?php if (user_can(get_current_user_id(), 'adminstrator')): ?>
 (<?=get_trainer_name($group_id);?>) <?php endif?></h1>
<hr class="wp-header-end">
<br>
<table class="wp-list-table widefat fixed striped table-view-list users">
    <thead>
        <th>Trainee name</th>
        <th>Trainee email</th>
        <th>Trainee phone No.</th>
        <th>Total Jobs</th>
        <th>Total Income</th>
    </thead>
    <tbody>
        <?php foreach ($trainees as $trainee): ?>
        <?php echo $trainee->user_id; ?>
        <?php if (user_can($trainee->id, 'author')): ?>
            <tr>
                <td><strong><?=$trainee->user_nicename?></strong>
                    <div class="row-actions">
                        <!-- <span class="edit">
                        <a href="<?php echo admin_url("admin.php?page=os-trainees&action=edit&trainee_id=$trainee->id"); ?>">Edit</a>
                        |
                            <a href="<?php echo admin_url("admin.php?page=os-trainees&action=delete&trainee_id=$trainee->ID"); ?>"
                            style="color: #b32d2e" onclick="return confirm('are you sure?')">Delete</a>
                        <form action="" method="POST" style="display: inline;" id="delete-form" onsubmit="return confirm('are you sure?')">
                            <input type="hidden" name="trainee_id" value="<?=$trainee->ID?>">
                            <input type="hidden" name="action" value="delete">
                        </form> -->
                    </div>
                </td>
                <td><?=$trainee->user_email?></td>
                <td><?=get_user_meta($trainee->id, 'mobile_number', true)?></td>
                <td><?=get_total_trainee_jobs($trainee->id)?></td>
                <td><?=get_total_trainee_income($trainee->id)?></td>
            </tr>
            <?php endif;?>
        <?php endforeach;?>
    </tbody>
</table>