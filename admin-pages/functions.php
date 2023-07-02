<?php

// This for get technical trainers name
function get_trainer_name($group_id)
{

    global $wpdb;

    $users = $wpdb->get_results("SELECT {$wpdb->prefix}users.user_nicename, {$wpdb->prefix}users.id from {$wpdb->prefix}users
							LEFT JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}users.ID={$wpdb->prefix}usermeta.user_id
							WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
							AND {$wpdb->prefix}usermeta.meta_value={$group_id}");

    foreach ($users as $user) {
        if (user_can($user->id, 'editor') && get_user_meta($user->id, 'trainer_type', true) == 1) {
            return $user->user_nicename;
        }

    }
    return 'UnKnown';
}

// Get number of trainees in a group
function get_trainee_number($group_id)
{

    global $wpdb;
    $total = 0;
    $users = $wpdb->get_results("SELECT {$wpdb->prefix}users.user_nicename, {$wpdb->prefix}users.id from {$wpdb->prefix}users
						LEFT JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}users.ID={$wpdb->prefix}usermeta.user_id
						WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
						AND {$wpdb->prefix}usermeta.meta_value={$group_id}");

    foreach ($users as $user) {
        if (user_can($user->id, 'author')) {
            $total++;
        }

    }

    return $total;

}

// Get number of jobs in a group
function get_total_group_jobs($group_id)
{
    global $wpdb;
    $jobs = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                        AND {$wpdb->prefix}usermeta.meta_value=$group_id
                        AND {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'");
    return sizeof($jobs);
}

function get_total_group_completed_jobs()
{
    global $wpdb;
    $jobs = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.id={$wpdb->prefix}postmeta.post_id
                        WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                        AND {$wpdb->prefix}usermeta.meta_value=$group_id
                        AND {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'
                        AND {$wpdb->prefix}postmeta.meta_key='_job_status'
                        AND {$wpdb->prefix}postmeta.meta_value='completed'");
    return sizeof($jobs);
}

// Get group income by id
function get_total_group_income($group_id)
{
    global $wpdb;

    $income = $wpdb->get_results("SELECT SUM({$wpdb->prefix}postmeta.meta_value) As total FROM {$wpdb->prefix}usermeta
				INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}usermeta.user_id={$wpdb->prefix}posts.post_author
				INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
				WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
				AND {$wpdb->prefix}usermeta.meta_value=$group_id
				AND {$wpdb->prefix}postmeta.meta_key='_job_cost'")[0]->total;

    return is_numeric($income) ? $income : 0;

}

// Get total number of trainee job by trainee id
function get_total_trainee_jobs($group_id)
{
    global $wpdb;
    // $group_id = $_GET['group_id'];
    $jobs = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                        AND {$wpdb->prefix}usermeta.meta_value=$group_id
                        AND {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'
                        AND {$wpdb->prefix}posts.post_author=$id");
    return sizeof($jobs);
}

// Get total trainee income by id
function get_total_trainee_income($group_id)
{
    global $wpdb;
    // $group_id = $_GET['group_id'];

    $income = $wpdb->get_results("SELECT SUM({$wpdb->prefix}postmeta.meta_value) As total FROM {$wpdb->prefix}usermeta
				INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}usermeta.user_id={$wpdb->prefix}posts.post_author
				INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
				WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
				AND {$wpdb->prefix}usermeta.meta_value=$group_id
				AND {$wpdb->prefix}postmeta.meta_key='_job_cost'
                AND {$wpdb->prefix}posts.post_author=$id")[0]->total;

    return is_numeric($income) ? $income : 0;
}

// Get trainee group
function get_trainee_group($id)
{

    global $wpdb;

    $trainee_group = get_user_meta($id, 'group_id', true);

    $groups = active_cohort_groups();

    foreach ($groups as $group) {
        if ($group->term_id == $trainee_group) {
            return $group->name;
        }
    }

    return "None";

}

function get_user_by_role_name($role_name)
{
    $args = array(
        'role' => $role_name,
        'orderby' => 'user_login',
        'order' => 'ASC',
    );

    return get_users($args);
}

// Get Trainer type by trainer id from usermeta
function get_trainer_type($id)
{
    $type = get_user_meta($id, 'trainer_type', true);
    if ($type == 1) {
        return "Technecal";
    }

    return "Others";
}

// Get Trainer group name by group id
function get_trainer_group($group_id)
{
    global $wpdb;

    $trainer_group = get_user_meta($group_id, 'group_id', true);

    $groups = active_cohort_groups();

    foreach ($groups as $group) {
        if ($group->term_id == $trainer_group) {
            return $group->name;
        }
    }

    return "None";

}

// Get active cohort groups
function active_cohort_groups()
{

    global $wpdb;

    return $wpdb->get_results("SELECT {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
            LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_taxonomy_id
            WHERE parent=" . ACTIVE_COHORT);
}

//
function get_tatol_jobs_for_female_in_group($group_id)
{
    global $wpdb;
    $jobs = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_author AS trainee_id FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                        AND {$wpdb->prefix}usermeta.meta_value=$group_id
                        AND {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'");

    $females = $wpdb->get_results("SELECT user_id AS id FROM {$wpdb->prefix}usermeta
                        WHERE meta_key='gender'
                        AND meta_value='female'");
    // return $jobs;
    $no_of_females = 0;
    foreach ($jobs as $job) {
        foreach ($females as $female) {
            if ($job->trainee_id == $female->id) {
                $no_of_females++;
            }

        }
    }
    return $no_of_females;
}

function get_total_jobs_for_group_by_country($group_id, $country)
{
    global $wpdb;
    $jobs = $wpdb->get_results("SELECT COUNT({$wpdb->prefix}posts.ID) AS total
                            FROM {$wpdb->prefix}posts
                            JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                            JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id={$wpdb->prefix}posts.ID
                            WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                            AND {$wpdb->prefix}usermeta.meta_value=$group_id
                            AND {$wpdb->prefix}posts.post_type='job'
                            AND {$wpdb->prefix}posts.post_status='publish'
                            AND {$wpdb->prefix}postmeta.meta_key='_job_country'
                            AND {$wpdb->prefix}postmeta.meta_value='$country'")[0]->total;

    return $jobs;
}

function get_total_jobs_income()
{
    global $wpdb;

    return $wpdb->get_results("SELECT SUM(meta_value) as total FROM {$wpdb->prefix}postmeta WHERE meta_key='_job_cost'")[0]->total;

}

function get_total_trainees_number()
{

    return sizeof(get_user_by_role_name('author'));
}

function get_total_jobs_number()
{
    global $wpdb;

    return sizeof($wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type='job' AND post_status='publish'"));

}

function get_total_complete_jobs_number()
{
    global $wpdb;
    return $wpdb->get_results("SELECT COUNT({$wpdb->prefix}posts.ID) AS total FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.id={$wpdb->prefix}postmeta.post_id
                        WHERE {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'
                        AND {$wpdb->prefix}postmeta.meta_key='completed'")[0]->totla;
}

function get_tatol_jobs_for_females()
{
    global $wpdb;
    return $wpdb->get_results("SELECT COUNT({$wpdb->prefix}posts.ID) AS total FROM {$wpdb->prefix}posts
                        JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                        WHERE {$wpdb->prefix}usermeta.meta_key='gender'
                        AND {$wpdb->prefix}usermeta.meta_value='female'
                        AND {$wpdb->prefix}posts.post_type='job'
                        AND {$wpdb->prefix}posts.post_status='publish'")[0]->total;
}

function get_total_jobs_by_country($country)
{
    global $wpdb;

    return $wpdb->get_results("SELECT COUNT({$wpdb->prefix}postmeta.post_id) as total
        FROM {$wpdb->prefix}postmeta
        JOIN {$wpdb->prefix}posts
        ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
        WHERE {$wpdb->prefix}postmeta.meta_key='_job_country'
        AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $country . "'")[0]->total;
}

function get_total_jobs_income_for_group($group_id)
{
    global $wpdb;

    return $wpdb->get_results("SELECT SUM({$wpdb->prefix}postmeta.meta_value) as total FROM {$wpdb->prefix}postmeta
                            JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}posts.post_author={$wpdb->prefix}usermeta.user_id
                            JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id={$wpdb->prefix}posts.ID
                            WHERE {$wpdb->prefix}usermeta.meta_key='group_id'
                            AND {$wpdb->prefix}usermeta.meta_value=$group_id
                            AND {$wpdb->prefix}postmeta.meta_key='_job_cost'")[0]->total;

}


function get_total_group_income_from_country($country)
{

    global $wpdb;

    $ids = $wpdb->get_results("SELECT {$wpdb->prefix}postmeta.post_id as id
            FROM {$wpdb->prefix}postmeta
            JOIN {$wpdb->prefix}posts
            ON {$wpdb->prefix}posts.ID={$wpdb->prefix}postmeta.post_id
            WHERE {$wpdb->prefix}postmeta.meta_key='_job_country'
            AND {$wpdb->prefix}postmeta.meta_value LIKE '" . $country . "'");
    $sum = 0;
    foreach ($ids as $id) {
        $id = $id->id;
        $sum += $wpdb->get_results("SELECT meta_value as cost
        FROM {$wpdb->prefix}postmeta
        WHERE post_id=" . $id . " AND meta_key='_job_cost'")[0]->cost;
    }
    return $sum;

}