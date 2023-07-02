<?php

$customPagHTML = "";

$query = "SELECT {$wpdb->prefix}users.ID, {$wpdb->prefix}users.user_nicename,
            {$wpdb->prefix}users.user_email from {$wpdb->prefix}users
            JOIN {$wpdb->prefix}usermeta
            ON {$wpdb->prefix}users.ID={$wpdb->prefix}usermeta.user_id
            WHERE {$wpdb->prefix}usermeta.meta_key LIKE '%capabilities%'
            AND {$wpdb->prefix}usermeta.meta_value LIKE '%author%'";
$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var($total_query);
$items_per_page = 10;
$this_page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
$offset = ($this_page * $items_per_page) - $items_per_page;
$result = $wpdb->get_results($query . " ORDER BY ID DESC LIMIT ${offset}, ${items_per_page}");
$totalPage = ceil($total / $items_per_page);

// ---------------------------------------------------------------

if (isset($_GET['search'])) {
    $search = $_GET['search_input'];

    $query = "SELECT {$wpdb->prefix}users.ID, {$wpdb->prefix}users.user_nicename,
                    {$wpdb->prefix}users.user_email from {$wpdb->prefix}users
                    JOIN {$wpdb->prefix}usermeta
                    ON {$wpdb->prefix}users.ID={$wpdb->prefix}usermeta.user_id
                    WHERE {$wpdb->prefix}usermeta.meta_key LIKE '%capabilities%'
                    AND {$wpdb->prefix}usermeta.meta_value LIKE '%author%'
                    AND {$wpdb->prefix}users.user_nicename LIKE '%$search%'";
    $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
    $total = $wpdb->get_var($total_query);
    $items_per_page = 10;
    $this_page = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
    $offset = ($this_page * $items_per_page) - $items_per_page;
    $result = $wpdb->get_results($query . " ORDER BY ID DESC LIMIT ${offset}, ${items_per_page}");
    $totalPage = ceil($total / $items_per_page);
}
?>
<h1 class="wp-heading-inline">Students</h1>
<a href="<?php echo admin_url("admin.php?page=os-trainees&action=create"); ?>"
    class="page-title-action">Add New</a>
<hr class="wp-header-end">

<form action="" method="GET">
<p class="search-box">
	<label class="screen-reader-text" for="user-search-input">Search Users:</label>
    <input type="hidden" name="page" value="os-trainees">
	<input type="search" id="user-search-input" name="search_input" value="">
    <input type="submit" id="search-submit" class="button" value="search" name="search">
</p>
</form>
<?php
if ($totalPage > 1) {
    $customPagHTML = "<div class='tablenav-pages' style='padding-top:5px'><span class='displaying-num' >  $total items </span>" . '<span class="pagination-links">' . paginate_links(array(
        'base' => add_query_arg('cpage', '%#%'),
        'format' => '',
        'prev_text' => "<span class='prev-page button'>" . __('&lt;') . "</span>",
        'next_text' => "<span class='next-page button'>" . __('&gt;') . "</span>",
        'total' => $totalPage,
        'current' => $this_page,
    )) . "</span></div>";
}
echo $customPagHTML;

?>
<br>
<table class="wp-list-table widefat fixed striped table-view-list users">
    <thead>
        <th>Student name</th>
        <th>Gender</th>
        <th>Student email</th>
        <th>Mobile No.</th>
        <th>National number</th>
    </thead>
    <tbody>
        <?php foreach ($result as $trainee): ?>
            <tr>
                <td><strong><?=$trainee->user_nicename?></strong>
                    <div class="row-actions">
                    <?php if (user_can(get_current_user_id(), 'administrator')): ?>
                        <span class="edit">
                        <a href="<?php echo admin_url("admin.php?page=os-trainees&action=edit&trainee_id=$trainee->ID"); ?>">Edit</a>
                        |
                            <a href="<?php echo admin_url("admin.php?page=os-trainees&action=delete&trainee_id=$trainee->ID"); ?>"
                            style="color: #b32d2e" onclick="return confirm('are you sure?')">Delete</a>
                        <form action="" method="POST" style="display: inline;" id="delete-form" onsubmit="return confirm('are you sure?')">
                            <input type="hidden" name="trainee_id" value="<?=$trainee->ID?>">
                            <input type="hidden" name="action" value="delete">
                        </form>
                    <?php endif?>
                       </div>
                </td>
                <td><?=get_user_meta($trainee->ID, 'gender', true)?></td>
                <td><?=$trainee->user_email?></td>
                <td><?=get_user_meta($trainee->ID, 'mobile_number', true)?></td>
                <td><?=get_user_meta($trainee->ID, 'national_number', true)?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
<?php
?>
</table>