<?php
global $wpdb;

include_once __DIR__ . '/../functions.php';

// Get All groups
$groups = active_cohort_groups();

$countries = $wpdb->get_results("SELECT {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
            LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id
            WHERE {$wpdb->prefix}term_taxonomy.taxonomy='country'");

$platforms = $wpdb->get_results("SELECT {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
            LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id
            WHERE {$wpdb->prefix}term_taxonomy.taxonomy='platform'");

// *********************************** Extraction *********************************

function csv_export($data)
{

    ob_start();
    $domain = $_SERVER['SERVER_NAME'];
    $filename = 'export.csv';
    ob_end_clean();
    $fh = @fopen('php://output', 'w');
    fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename={$filename}");
    header('Expires: 0');
    header('Pragma: public');
    foreach ($data as $data_row) {
        fputcsv($fh, $data_row);
    }
    fclose($fh);
    ob_end_flush();

    return;
}

// if (isset($_GET['extract'])) {

//     // Excel file name for download
//     $array = [$fileName];
//     add_action('send_headers', 'downloadtocvs', 5);
//     array_walk($array, 'downloadtocvs');

// }

// *********************************** End Extraction *********************************


// ****************************** Platforms Reports Functions *************************

// $jobs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type='job' AND post_status='publish'");

function get_total_group_jobs_from_platform($platform)
{
    global $wpdb;

    return $wpdb->get_results("SELECT COUNT({$wpdb->prefix}postmeta.post_id) as total
        FROM {$wpdb->prefix}postmeta
        JOIN {$wpdb->prefix}posts
        ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
        WHERE {$wpdb->prefix}postmeta.meta_key='_job_platform'
        AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $platform . "'")[0]->total;
}

function get_total_group_income_from_platform($platform)
{

    global $wpdb;

    $ids = $wpdb->get_results("SELECT {$wpdb->prefix}postmeta.post_id as id
            FROM {$wpdb->prefix}postmeta
            JOIN {$wpdb->prefix}posts
            ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
            WHERE {$wpdb->prefix}postmeta.meta_key='_job_platform'
            AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $platform . "'");
    $sum = 0;
    foreach ($ids as $id) {
        $id = $id->id;
        $sum += $wpdb->get_results("SELECT meta_value as cost
        FROM {$wpdb->prefix}postmeta
        WHERE post_id=" . $id . " AND meta_key='_job_cost'")[0]->cost;
    }
    return $sum;

}

// ****************************** Contries Reports Functions *************************

// function get_total_group_jobs_from_country($country)
// {
//     global $wpdb;

//     return $wpdb->get_results("SELECT COUNT({$wpdb->prefix}postmeta.post_id) as total
//         FROM {$wpdb->prefix}postmeta
//         JOIN {$wpdb->prefix}posts
//         ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
//         WHERE {$wpdb->prefix}postmeta.meta_key='_job_country'
//         AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $country . "'")[0]->total;
// }

// function get_total_group_income_from_country($country)
// {

//     global $wpdb;

//     $ids = $wpdb->get_results("SELECT {$wpdb->prefix}postmeta.post_id as id
//             FROM {$wpdb->prefix}postmeta
//             JOIN {$wpdb->prefix}posts
//             ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
//             WHERE {$wpdb->prefix}postmeta.meta_key='_job_country'
//             AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $country . "'");
//     $sum = 0;
//     foreach ($ids as $id) {
//         $id = $id->id;
//         $sum += $wpdb->get_results("SELECT meta_value as cost
//         FROM {$wpdb->prefix}postmeta
//         WHERE post_id=" . $id . " AND meta_key='_job_cost'")[0]->cost;
//     }
//     return $sum;

// }
ob_end_flush();
?>
<div class="wrap">

<h1 class="wp-heading-inline">Reports</h1>
<!-- admin_url( 'wp-admin/admin.php?page=os-reports&extract=extract')  -->
<a href="<?php menu_page_url('os-reports', true)?>&extract=extract">Extract</a>
        <!------------ This is Groups reports  ------------>
    <h2>Groups</h2>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th>Group Name</th>
                <th>Total Trainee No.</th>
                <!-- <th>Trainer name</th> -->
                <th>Total jobs</th>
                <th>Total income</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td><?=$group->name?></td>
                    <td><?=get_trainee_number($group->term_id)?></td>
                    <!-- <td><?=get_trainer_name($group->term_id)?></td> -->
                    <td><?=get_total_group_jobs($group->term_id)?></td>
                    <td><?=get_total_group_income($group->term_id)?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <td>total</td>
                <td><?=get_total_trainees_number()?></td>
                <td><?=get_total_jobs_number()?></td>
                <td><?=get_total_jobs_income()?></td>
            </tr>
        </tfoot>
    </table>
    <!------------------ This is Platforms Reports  ------------------------------->
    <h2>Platforms</h2>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th>Platform Name</th>
                <th>Total jobs</th>
                <th>Total income</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($platforms as $platform): ?>
                <tr>
                    <td><?=$platform->name?></td>
                    <td><?=get_total_group_jobs_from_platform($platform->name)?></td>
                    <td><?=get_total_group_income_from_platform($platform->name)?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <!------------------ This is Conutry Reports  ------------------------------->
    <h2>Countries</h2>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <th>Platform Name</th>
                <th>Total jobs</th>
                <th>Total income</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($countries as $country): ?>
                <tr>
                    <td><?=$country->name?></td>
                    <td><?=get_total_group_jobs_from_country($country->name)?></td>
                    <td><?=get_total_group_income_from_country($country->name)?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>