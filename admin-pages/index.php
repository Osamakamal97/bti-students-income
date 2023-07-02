<?php

global $wpdb;

// Get all cohorts with there groups
$cohorts = $wpdb->get_results("Select {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
    LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id
    WHERE {$wpdb->prefix}term_taxonomy.taxonomy='cohort'
    AND {$wpdb->prefix}term_taxonomy.parent=0");

/**
 * Chech if there is any group in cohort, this mainlly will be trigger with the first time plugin is work
 * This should be trigger with plugin activation to add some defualt data but because need some prossess to work
 * specially for something like custom taxonomy it will be a bit hard
 */
if (sizeof($cohorts) == 0) {

    // 'التصميم الجرافيكي- Graphic Design',
    // 'التسويق الالكتروني - Digital Marketing',
    // 'تصميم واجهات المستخدم - UX/UI',
    // 'الموشن جرافيك- Motion Graphic',
    // 'بناء مواقع الانترنت بتقنية  WordPress',
    // 'الترجمة - Translation',
    // 'بناء تطبيقات الموبايل - iOS Mobile Development',
    // 'بناء تطبيقات الموبايل - Flutter Mobile Development',
    // 'المساعد الافتراضي - Virtual Assistant',
    // 'التعليق الصوتي - Voice Over',
    $courses = [
        'Graphic Design',
        'Digital Marketing',
        'UX/UI',
        'Motion Graphic',
        'WordPress',
        'Translation',
        'iOS Mobile Development',
        'Flutter Mobile Development',
        'Virtual Assistant',
        'Voice Over',
    ];

    $slugs = [
        'graphic-design',
        'digital-marketing',
        'ux-ui',
        'motion-graphic',
        'wordpress',
        'translation',
        'ios-mobile-development',
        'flutter-mobile-development',
        'virtual-assistant',
        'voice-over',
    ];

    // for ($i = 1; $i < 2; $i++) {
    wp_insert_term("Login", 'cohort', array('parent' => 0));
    $cohort_id = get_term_by('name', "Login", 'cohort')->term_id;
    foreach ($courses as $key => $course) {
        wp_insert_term($course, 'cohort', array(
            'parent' => $cohort_id,
            'slug' => $slugs[$key],
        ));
    }

    // }
}

$countries = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy='country'");
// Add countries in countries custom category
if (sizeof($countries) == 0) {
    $countries = [
        'Local Markit',
        'Asia',
        'Arabic Countries',
        'Arabic Galf',
        'European Market',
        'North America',
        'Others',
    ];

    foreach ($countries as $country) {
        wp_insert_term($country, 'country');
    }
}

$platforms = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy='platform'");
// Add platforms in platforms custom category
if (sizeof($platforms) == 0) {
    $platforms = [
        'UpWork',
        'Fiverr',
        'Freelancer',
        'Mostaqel',
        'peoplePerHour',
        'Khamsat',
        'LinkedIn',
        'Socail Media',
        'Others',
    ];
    foreach ($platforms as $platform) {
        wp_insert_term($platform, 'platform');
    }
}

$id = get_current_user_id();

// var_dump(get_total_jobs_by_country(3, 'Arab Countries'));

?>
<div class="wrap">

</div>
