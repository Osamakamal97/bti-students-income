<h1 class="wp-heading-inline">Trianers</h1>
<a href="<?php echo admin_url('admin.php?page=os-trainers&action=create'); ?>"
    class="page-title-action">Add New</a>
<hr class="wp-header-end">
<br>
<table class="wp-list-table widefat fixed striped table-view-list users">
    <thead>
        <th>Trainer name</th>
        <th>Trainer type</th>
        <th>Trainee specification</th>
    </thead>
    <tbody>
        <?php foreach ($trainers as $trainer): ?>
            <tr>
                <td><strong><?=$trainer->user_nicename?></strong>
                    <div class="row-actions">
                    <?php if (user_can(get_current_user_id(), 'administrator')): ?>
                        <span class="edit">
                        <a href="<?php echo admin_url("admin.php?page=os-trainers&action=edit&trainer_id=$trainer->id"); ?>">Edit</a>
                        |
                        <a href="<?php echo admin_url("admin.php?page=os-trainers&action=delete&trainer_id=$trainer->ID"); ?>"
                        style="color: #b32d2e" onclick="return confirm('are you sure?')">Delete</a>
                        <form action="" method="POST" style="display: inline;" id="delete-form" onsubmit="return confirm('are you sure?')">
                            <input type="hidden" name="trainee_id" value="<?=$trainer->ID?>">
                            <input type="hidden" name="action" value="delete">
                        </form>
                    <?php endif?>
                       </div>
                </td>
                <td><?=get_trainer_type($trainer->id)?></td>
                <td><?=get_trainer_group($trainer->id)?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>