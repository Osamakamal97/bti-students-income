<?php

/**
 * Plugin Name: BTI's Students Income Sheet
 * Description: This plugin developed for BTI to help them to calculate and analyze Trainee Jobs and Groups.
 * Version: 1.0
 * Requires at least: 5.7
 * Requires PHP: 7.2
 * Author: OsamaKJ
 */

// This use to specify with the cohort that is active
define('ACTIVE_COHORT', get_option('active_cohort'));

include __DIR__ . '/admin-pages/jobs/student_jobs.php';
include_once __DIR__ . '/admin-pages/functions.php';

// Main Page for Plugin
function os_bti_index_html()
{
    include __DIR__ . '/admin-pages/index.php';
}

function os_bti_settings_index_html()
{
    include __DIR__ . '/admin-pages/settings.php';
}

// Groups Page that is show all groups for specific cohort
function os_groups_index_html()
{
    include __DIR__ . '/admin-pages/groups/index.php';
}

// Edit page for specific group - there is no good used for it
function os_groups_edit_html()
{
    include __DIR__ . '/admin-pages/groups/edit.php';
}

// Show all cohorts with their groups
function os_cohorts_html()
{
    $_GET['taxonomy'] = 'cohort';
    include __DIR__ . '/admin-pages/taxonomy/index.php';
}

// Show all cohorts with their groups
function os_trainers_html()
{
    include __DIR__ . '/admin-pages/trainers/index.php';
}

function os_repoorts_index_html()
{
    // include __DIR__ . '/admin-pages/reports/test.php';

    include __DIR__ . '/admin-pages/reports/index.php';
}

//Student Page

function os_create_student_html()
{
    include __DIR__ . '/admin-pages/trainees/create.php';
}

function os_trainees_index_html()
{
    include __DIR__ . '/admin-pages/trainees/index.php';
}

// Create BTI main menu with other submenus
function os_create_groups_menu()
{
    add_menu_page(
        'BTI',
        'BTI',
        'delete_others_pages',
        'os-bti',
        'os_bti_index_html',
        'dashicons-groups',
        20
    );

    add_submenu_page(
        'os-bti',
        'Trainees',
        'Trainees',
        'administrator',
        'os-trainees',
        'os_trainees_index_html'
    );

    add_submenu_page(
        'os-bti',
        'Trainers',
        'Trainers',
        'administrator',
        'os-trainers',
        'os_trainers_html'
    );

    add_submenu_page(
        'os-bti',
        'Groups',
        'Groups',
        'delete_others_pages',
        'os-groups',
        'os_groups_index_html'
    );

    add_submenu_page(
        'os-bti',
        'Cohorts',
        'Cohorts',
        'administrator',
        // 'os-cohorts',
        'edit-tags.php?taxonomy=cohort'
    );

    add_submenu_page(
        'os-bti',
        'Countries',
        'Countries',
        'administrator',
        // 'os-cohorts',
        'edit-tags.php?taxonomy=country'
    );

    add_submenu_page(
        'os-bti',
        'Platforms',
        'Platforms',
        'administrator',
        'edit-tags.php?taxonomy=platform'
    );

    $slug = add_submenu_page(
        'os-bti',
        'Reports',
        'Reports',
        'administrator',
        'os-reports',
        'os_repoorts_index_html'
    );

    add_action('load-' . $slug, 'export');

    add_submenu_page(
        'os-bti',
        'Settings',
        'Settings',
        'administrator',
        'os-settings',
        'os_bti_settings_index_html'
    );

}

//Create Main toxonomy for cohort with childerns as groups or courses
function os_register_taxonomy_cohorts()
{
    $labels = array(
        'name' => _x('Cohorts', 'Cohorts'),
        'singular_name' => _x('Cohorts', 'cohort'),
        'search_items' => __('Search Cohorts'),
        'all_items' => __('All Cohorts'),
        'parent_item' => __('Parent Cohorts'),
        'parent_item_colon' => __('Parent Cohort:'),
        'edit_item' => __('Edit Cohort'),
        'update_item' => __('Update Cohort'),
        'add_new_item' => __('Add New Cohort'),
        'new_item_name' => __('New Cohort Name'),
        'menu_name' => __('Cohorts'),
    );

    $args = array(
        'hierarchical' => true, // make it hierarchical (like categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_menu' => true,
        'capabilities' => ['manage_terms' => 'administrator'],
    );

    register_taxonomy('cohort', 'post', $args);
}

//Create Main toxonomy for countries
function os_register_taxonomy_countries()
{

    $labels = array(
        'name' => _x('Countries', 'Countries'),
        'singular_name' => _x('Countries', 'country'),
        'search_items' => __('Search Countries'),
        'all_items' => __('All Countries'),
        'parent_item' => __('Parent Countries'),
        'parent_item_colon' => __('Parent Country:'),
        'edit_item' => __('Edit Cohort'),
        'update_item' => __('Update Cohort'),
        'add_new_item' => __('Add New Cohort'),
        'new_item_name' => __('New Cohort Name'),
        'menu_name' => __('Countries'),
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_menu' => true,
        'capabilities' => ['manage_terms' => 'administrator'],

    );

    register_taxonomy('country', 'post', $args);
}

//Create Main toxonomy for cohort with childerns as groups or courses
function os_register_taxonomy_platforms()
{

    $labels = array(
        'name' => _x('Platforms', 'Platforms'),
        'singular_name' => _x('Platforms', 'platform'),
        'search_items' => __('Search Platforms'),
        'all_items' => __('All Platforms'),
        'parent_item' => __('Parent Platforms'),
        'parent_item_colon' => __('Parent Platform:'),
        'edit_item' => __('Edit Cohort'),
        'update_item' => __('Update Cohort'),
        'add_new_item' => __('Add New Cohort'),
        'new_item_name' => __('New Cohort Name'),
        'menu_name' => __('Platforms'),
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_menu' => true,
        'capabilities' => ['manage_terms' => 'administrator'],
    );

    register_taxonomy('platform', 'post', $args);
}

add_action('admin_menu', 'os_create_groups_menu');

add_action('init', 'os_register_taxonomy_cohorts');

add_action('init', 'os_register_taxonomy_countries');

add_action('init', 'os_register_taxonomy_platforms');

// Limit media library access
add_filter('ajax_query_attachments_args', 'wpb_show_current_user_attachments');

// Make author or student see his attachments only
function wpb_show_current_user_attachments($query)
{
    $user_id = get_current_user_id();
    if ($user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts')) {
        $query['author'] = $user_id;
    }
    return $query;
}

// make author or student see his own posts only
function posts_for_current_author($query)
{

    global $pagenow, $wpdb;

    if ('edit.php' != $pagenow || !$query->is_admin) {

        return $query;
    }

    if (!current_user_can('edit_others_posts')) {
        global $user_ID;

        $query->set('author', $user_ID);
    } else if (current_user_can('editor')) {
// Get jobs for trainer under his group only
        $id = get_current_user_id();

        $group_id = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}usermeta
            WHERE user_id=$id AND meta_key='group_id'")[0]->meta_value;

        $posts_id = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts
                JOIN {$wpdb->prefix}usermeta
                ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                WHERE  {$wpdb->prefix}posts.post_status='publish'
                AND {$wpdb->prefix}usermeta.meta_key='group_id'
                AND {$wpdb->prefix}usermeta.meta_value=$group_id");

        foreach ($posts_id as $post_id) {
            $group_posts_id[] += $post_id->ID;
        }

        $query->set('post__in', $group_posts_id);

    }
    return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');

function myplugin_load_admin_scripts($hook)
{
    global $typenow;
    // if ($typenow == 'myplugin_custom_post_type') {
    wp_enqueue_media();

    // Registers and enqueues the required javascript.
    wp_register_script('meta-box-image', plugins_url('myplugin-media.js', __FILE__), array('jquery'));

    // wp_register_script('meta-box-image', plugins_url('myplugin-media.js', __FILE__), array('jquery'));
    wp_localize_script('meta-box-image', 'meta_image',
        array(
            'title' => __('Choose or Upload Media', 'jobs'),
            'button' => __('Use this media', 'jobs'),
        )
    );
    wp_enqueue_script('meta-box-image');
    // }
}
add_action('admin_enqueue_scripts', 'myplugin_load_admin_scripts', 10, 1);

function filterData($str)
{

    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

function export()
{

    global $wpdb;

    $fileName = "members_export_data-" . date('Ymd') . ".xlsx";

    if (isset($_GET['extract'])) {
        include_once 'xlsxwriter.class.php';
        $writer = new XLSXWriter();
        // Get records from the database
        $groups = active_cohort_groups();

        $row = array(
            '#',
            'Group Name',
            "Mentor's Name",
            'No. of Trainees',
            'No. of Trainees got Jobs',
            'No. Of Female got Jobs',
            'Total No. of Jobs',
            '# of jobs from Local Market',
            '# of jobs from Arabic Countries',
            '# of jobs from Arabic Gulf',
            '# of jobs from Asia Market',
            '# of jobs from European Market',
            '# of jobs from North Amarica',
            'Others',
            'Total earnings($)',
        );

        $data[] = $row;

        if (count($groups) > 0) {
            // Output each row of the data
            $i = 1;
            foreach ($groups as $group) {
                $rowData = array(
                    $i,
                    $group->name,
                    get_trainer_name($group->term_id),
                    get_trainee_number($group->term_id),
                    get_total_group_completed_jobs($group->term_id),
                    get_tatol_jobs_for_female_in_group($group->term_id),
                    get_total_group_jobs($group->term_id),
                    get_total_jobs_for_group_by_country($group->term_id, 'local market'),
                    get_total_jobs_for_group_by_country($group->term_id, 'arabic countries'),
                    get_total_jobs_for_group_by_country($group->term_id, 'arabic gulf'),
                    get_total_jobs_for_group_by_country($group->term_id, 'asia'),
                    get_total_jobs_for_group_by_country($group->term_id, 'european market'),
                    get_total_jobs_for_group_by_country($group->term_id, 'north america'),
                    get_total_jobs_for_group_by_country($group->term_id, 'others'),
                    get_total_group_income($group->term_id),
                );
                array_walk($rowData, 'filterData');
                $data[] = $rowData;

            }
            $row = array(
                'total',
                'total',
                "total",
                get_total_trainees_number(),
                get_total_complete_jobs_number(),
                get_tatol_jobs_for_females(),
                get_total_jobs_number(),
                get_total_jobs_by_country('local market'),
                get_total_jobs_by_country('arabic countries'),
                get_total_jobs_by_country('arabic gulf'),
                get_total_jobs_by_country('asia'),
                get_total_jobs_by_country('european market'),
                get_total_jobs_by_country('north america'),
                get_total_jobs_by_country('others'),
                get_total_jobs_income(),
            );
            $data[] = $row;

        } else {
        }

        $writer->writeSheet($data);
        $writer->writeToFile($fileName);
        downloadtocvs($fileName);
    }
}

function downloadtocvs($fileName)
{

    global $wpdb;

    // Headers for download
    ob_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=" . basename($fileName));
    header("Content-Transfer-Encoding: binary");
    header("Expires: 0");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Length: ' . filesize($fileName));
    readfile($fileName);
    ob_end_flush();
    unlink($fileName);
    exit;

}

// Remove some of menu nav from dashboard for trainees
add_action('admin_init', 'my_remove_menu_pages');
function my_remove_menu_pages()
{

    global $user_ID;

    if (current_user_can('author')) {
        remove_menu_page('edit.php');
        remove_menu_page('upload.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');
        remove_menu_page('index.php');

    }
}

// Edit redirect after login for trainee
function admin_default_page()
{
    if (current_user_can('author')) {
        return admin_url() . 'edit.php?post_type=job';
    } else {
        return admin_url() . 'index.php';
    }
}

add_filter('login_redirect', 'admin_default_page');

// Edit bar menu for trainees
function wpb_custom_toolbar_link($wp_admin_bar)
{
    if (current_user_can('author')) {
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('new-content');
        $args = array(
            'id' => 'site-name',
            'href' => admin_url() . 'edit.php?post_type=job',
            'title' => 'Dashboard',
        );
        $wp_admin_bar->add_node($args);
        $args = array(
            'id' => 'site-name',
            'href' => admin_url() . 'edit.php?post_type=job',
            'title' => 'Dashboard',
        );
        $wp_admin_bar->add_node($args);
        $wp_admin_bar->remove_node('dashboard');

    }
}
add_action('admin_bar_menu', 'wpb_custom_toolbar_link', 999);
