<?php

/* ---------------------------------
Create Custom Post type
--------------------------------*/

function os_student_job_post()
{
    register_post_type('job', array(
        'labels' => array(
            'name' => __('Jobs', 'vicodemedia'),
            'singular_name' => __('Job', 'vicodemedia'),
            'add_new' => __('Add New', 'vicodemedia'),
            'add_new_item' => __('Add New Job', 'vicodemedia'),
            'edit_item' => __('Edit Job', 'vicodemedia'),
            'new_item' => __('New Job', 'vicodemedia'),
            'view_item' => __('View Job', 'vicodemedia'),
            'view_items' => __('View Jobs', 'vicodemedia'),
            'search_items' => __('Search Jobs', 'vicodemedia'),
            'not_Found' => __('No Jobs found', 'vicodemedia'),
            'not_found_in_trash' => __('No Jobs found in a trash', 'vicodemedia'),
            'all_items' => __('All Jobs', 'vicodemedia'),
            'archives' => __('Job Archives', 'vicodemedia'),
            'insert_into_item' => __('Insert into Job', 'vicodemedia'),
            'uploaded_to_this_item' => __('Uploaded to this Jobs', 'vicodemedia'),
            'filter_items_list' => __('Filter Jobs List', 'vicodemedia'),
            'items_list_navigation' => __('Jobs List Navigation', 'vicodemedia'),
            'items_list' => __('Jobs List', 'vicodemedia'),
            'item_published' => __('Jobs Published', 'vicodemedia'),
            'item_published_privately' => __('Jobs Published Privately', 'vicodemedia'),
            'item_reverted_to draft' => __('Jobs reverted to draft', 'vicodemedia'),
            'item_scheduled' => __('Jobs scheduled', 'vicodemedia'),
            'item_updated' => __('Jobs Updated', 'vicodemedia'),
        ),
        'has_archive' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show in menu' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'revision', 'custom-field', 'revision'),
        'can_export' => true,

    ));
}

add_action('init', 'os_student_job_post');


///////////////////////////////////////////////////////

// add job Describe field to job post type

function os_add_post_meta_boxes_describe()
{
    add_meta_box(
        "post_metadata_jobs_post_describe",
        "Job Describe",
        "post_meta_box_jobs_post_Describe",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_describe');

// save field value
function os_save_post_meta_box_describe()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_describe", sanitize_text_field($_POST["_job_describe"]));

}
add_action('save_post', 'os_save_post_meta_box_describe');

//callback function to render field
function post_meta_box_jobs_post_describe()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_describe"][0];
    echo "<input type='text'name='_job_describe'value='" . $fieldData . "'placeholder='Job Describe'>";
}

///////////////////////////////////////////////////////////////

// add job country field to job post type

function os_add_post_meta_boxes_country()
{
    add_meta_box(
        "post_metadata_jobs_post_country",
        "Job country",
        "post_meta_box_jobs_post_country",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_country');

// save field value
function os_save_post_meta_box_country()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta($post->ID, "_job_country", sanitize_text_field($_POST["_job_country"]));

}
add_action('save_post', 'os_save_post_meta_box_country');

//callback function to render field
function post_meta_box_jobs_post_country()
{
    global $post, $wpdb;

    $countries = $wpdb->get_results("Select {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
                    LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id
                    WHERE {$wpdb->prefix}term_taxonomy.taxonomy='country'
                    AND {$wpdb->prefix}term_taxonomy.parent=0");

    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_country"][0];
    // echo "<input type='text'name='_job_country'value='" . $fieldData . "'placeholder='Job Country'>";
    echo "<input list='countries' name='_job_country' value='" . $fieldData . "' placeholder='Job Country'>
        <datalist id='countries'>";
    foreach ($countries as $country) {
        echo "<option name='$country->term_id' value='$country->name'>";
    }
    echo "</datalist>";
}

///////////////////////////////////////////////////////////////

// add job platform field to job post type

function os_add_post_meta_boxes_platform()
{
    add_meta_box(
        "post_metadata_jobs_post_platform",
        "Job Platform",
        "post_meta_box_jobs_post_platform",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_platform');

// save field value
function os_save_post_meta_box_platform()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_platform", sanitize_text_field($_POST["_job_platform"]));

}
add_action('save_post', 'os_save_post_meta_box_platform');

//callback function to render field
function post_meta_box_jobs_post_platform()
{
    global $post, $wpdb;

    $platforms = $wpdb->get_results("Select {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
                            LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id
                            WHERE {$wpdb->prefix}term_taxonomy.taxonomy='platform'
                            AND {$wpdb->prefix}term_taxonomy.parent=0");

    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_platform"][0];
    echo "<input list='platforms' name='_job_platform' value='" . $fieldData . "' placeholder='Job platform'>
    <datalist id='platforms'>";
    foreach ($platforms as $platform) {
        echo "<option name='$platform->term_id' value='$platform->name'>";
    }
    echo "</datalist>";
}

///////////////////////////////////////////////////////////////

// add job Attachment field to job post type

function os_add_post_meta_boxes_attachment()
{
    add_meta_box(
        "post_metadata_jobs_post_attachment",
        "Job Attachment",
        "myplugin_render_metabox",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_attachment');

// save field value
function os_save_post_meta_box_attachment()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_attachment", sanitize_text_field($_POST["_job_attachment"]));

}
add_action('save_post', 'os_save_post_meta_box_attachment');

function update_edit_form()
{
    echo ' enctype="multipart/form-data"';
} // end update_edit_form
add_action('post_edit_form_tag', 'update_edit_form');

//callback function to render field
function post_meta_box_jobs_post_attachment()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_attachment"][0];
    echo "<input type='file'name='_job_attachment' value='" . $fieldData . "'placeholder='Job attachment'>";
}

/**
 * Render the metabox
 */
function myplugin_render_metabox()
{

    // Variables
    global $post;
    $saved = get_post_meta($post->ID, '_job_attachment', true);

    ?>

			<fieldset>

				<div>
    <?php
/**
     * The label for the media field
     */
    ?>
					<label for="myplugin_media"><?php _e('Field Label', 'events')?></label><br>

					<?php
/**
     * The actual field that will hold the URL for our file
     */
    ?>
					<input type="text" class="large-text" name="_job_attachment" id="myplugin_media"
                    value="<?php echo esc_attr($saved); ?>"><br>

					<?php
/**
     * The button that opens our media uploader
     * The `data-media-uploader-target` value should match the ID/unique selector of your field.
     * We'll use this value to dynamically inject the file URL of our uploaded media asset into your field once successful (in the myplugin-media.js file)
     */
    ?>
					<button type="button" class="button" id="events_video_upload_btn" data-media-uploader-target="#myplugin_media"><?php _e('Upload Media', 'myplugin')?></button>
				</div>

			</fieldset>

		<?php

    // Security field
    wp_nonce_field('myplugin_form_metabox_nonce', 'myplugin_form_metabox_process');

}

///////////////////////////////////////////////////////////////

// add job Type field to job post type

function os_add_post_meta_boxes_job_type()
{
    add_meta_box(
        "post_metadata_jobs_post_job_type",
        "Job Type",
        "post_meta_box_jobs_post_job_type",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_job_type');

// save field value
function os_save_post_meta_box_job_type()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_type", sanitize_text_field($_POST["_job_type"]));

}
add_action('save_post', 'os_save_post_meta_box_job_type');

//callback function to render field
function post_meta_box_jobs_post_job_type()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_type"][0];
    echo "<input list='job_type' name='_job_type'value='" . $fieldData . "'placeholder='Job Type'>
    <datalist id='job_type'>
    <option name='full-time'value='Full time'>
    <option name='contract' value='Contract'>
    <option name='part-time' value='Part time'>
    <option name='internship' value='Internship'>
    <option name='temporary' value='Temporary'>
    </datalist>";
}

//////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////

// add job Status field to job post type

function os_add_post_meta_boxes_status()
{
    add_meta_box(
        "post_metadata_jobs_post_status",
        "Job Status",
        "post_meta_box_jobs_post_status",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_status');

// save field value
function os_save_post_meta_box_status()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_status", sanitize_text_field($_POST["_job_status"]));

}
add_action('save_post', 'os_save_post_meta_box_status');

//callback function to render field
function post_meta_box_jobs_post_status()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_status"][0];
    echo "<input list='status' name='_job_status'value='" . $fieldData . "'placeholder='Job status'>
    <datalist id='status'>
    <option name='inprogress'value='InProgress'>
    <option name='complete' value='Completed'>
    <option name='cancelled' value='Cancelled'>
    </datalist>";
}

///////////////////////////////////////////////////////////////

// add job Cost field to job post type

function os_add_post_meta_boxes_cost()
{
    add_meta_box(
        "post_metadata_jobs_post_cost",
        "Job Cost ($)",
        "post_meta_box_jobs_post_cost",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_cost');

// save field value
function os_save_post_meta_box_cost()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_cost", sanitize_text_field($_POST["_job_cost"]));

}
add_action('save_post', 'os_save_post_meta_box_cost');

//callback function to render field
function post_meta_box_jobs_post_cost()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_cost"][0];
    echo "<input type='text'name='_job_cost'value='" . $fieldData . "'placeholder='Job Cost in dollar'>";
}

///////////////////////////////////////////////////////////////

// add job Start Date field to job post type

function os_add_post_meta_boxes_startDate()
{
    add_meta_box(
        "post_metadata_jobs_post_startDate",
        "Job Start Date",
        "post_meta_box_jobs_post_startDate",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_startDate');

// save field value
function os_save_post_meta_box_startDate()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_startDate", sanitize_text_field($_POST["_job_startDate"]));

}
add_action('save_post', 'os_save_post_meta_box_startDate');

//callback function to render field
function post_meta_box_jobs_post_startDate()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_startDate"][0];
    echo "<input type='date'name='_job_startDate'value='" . $fieldData . "'placeholder='Job Start Date'>";
}

///////////////////////////////////////////////////////////////

// add job End Date field to job post type

function os_add_post_meta_boxes_endDate()
{
    add_meta_box(
        "post_metadata_jobs_post_endDate",
        "Job End Date",
        "post_meta_box_jobs_post_endDate",
        "job",
        "side",
        "low"
    );
}

add_action('admin_init', 'os_add_post_meta_boxes_endDate');

// save field value
function os_save_post_meta_box_endDate()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta($post->ID, "_job_endDate", sanitize_text_field($_POST["_job_endDate"]));

}
add_action('save_post', 'os_save_post_meta_box_endDate');

//callback function to render field
function post_meta_box_jobs_post_endDate()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $fieldData = $custom["_job_endDate"][0];
    echo "<input type='date'name='_job_endDate'value='" . $fieldData . "'placeholder='Job End Date'>";
}
