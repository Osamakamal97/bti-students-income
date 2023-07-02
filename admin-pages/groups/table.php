<h1 class="wp-heading-inline">Groups</h1>
<hr class="wp-header-end">
<br>
<table class="wp-list-table widefat fixed striped table-view-list users">
    <thead>
        <th>Group name</th>
        <th>Trainer name</th>
        <th>Trainee No.</th>
        <th>Total Jobs</th>
        <th>Total Income</th>
    </thead>
    <tbody>
        <?php foreach($groups as $group) : ?>
            <tr>
                <td><strong><?= $group->name?></strong> 
                    <div class="row-actions">
                    <?php if( user_can( get_current_user_id(), 'administrator' )): ?>
                        <span class="edit">
                        <a href="<?php echo admin_url( "admin.php?page=os-groups&action=edit&group_id=$group->term_id" ); ?>">Edit</a> 
                        |
                    <?php endif ?>
                        <span class="show">
                        <a href="<?php echo admin_url( "admin.php?page=os-groups&action=show&group_id=$group->term_id" ); ?>">Show</a> 
                    </div>
                </td>
                <td><?= get_trainer_name($group->term_id); ?></td>
                <td><?= get_trainee_number($group->term_id) ?></td>
                <td><?= get_total_group_jobs($group->term_id) ?></td>
                <td><?= get_total_group_income($group->term_id) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>