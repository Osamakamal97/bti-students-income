<?php

global $wpdb;

include_once __DIR__ . '/functions.php';

// Loop at cohorts to select active cohort
$cohorts = $wpdb->get_results("Select {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
    LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id
    WHERE {$wpdb->prefix}term_taxonomy.taxonomy='cohort'
    AND {$wpdb->prefix}term_taxonomy.parent=0");

// ---------- Import file with trainee data ----------

// Table name
$tablename = $wpdb->prefix . "users";

// for test
// Import CSV
if (isset($_POST['submit'])) {
    if (isset($_FILES['import_file'])) {
        $table_name = $wpdb->prefix . "users";
        $done = false;

        // File extension
        $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
        // If file extension is 'csv'
        if (!empty($_FILES['import_file']['name']) && $extension == 'csv') {
            $totalInserted = 0;

            // Open file in read mode
            $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
            fgetcsv($csvFile); // Skipping header row

            // Read file
            while (($csvData = fgetcsv($csvFile)) !== false) {
                // $csvData = array_map("utf8_encode", $csvData);

                // Row column length
                $dataLen = count($csvData);
                // Skip row if length != 4

                // Assign value to variables
                $username = trim($csvData[0]);
                $arabic_name = trim($csvData[1]);
                $age = trim($csvData[2]);
                $gender = trim($csvData[3]);
                $email = trim($csvData[4]);
                $mobile_number = trim($csvData[5]);
                $national_number = trim($csvData[6]);
                $group_name = trim($csvData[7]);

                // Check record already exists or not
                $cntSQL = "SELECT count(*) as count FROM $tablename where user_email='$email'";
                $record = $wpdb->get_results($cntSQL, OBJECT);

                if ($record[0]->count == 0) {
                    // Check if variable is empty or not
                    if (!empty($username) && !empty($email) && !empty($mobile_number)) {
                        // Get gender as English or id - this is needed not a english one -
                        if ($gender == 'ذكر') {
                            $gender = 'male';
                        } else {
                            $gender = 'female';
                        }

                        // Get group id from name
                        $group_id = get_group_id_by_group_name($group_name);

                        // Insert Record
                        // $wpdb->insert($tablename, array(
                        //     'name' => $username,
                        //     'age' => $age,
                        //     'username' => $username,
                        //     'email' => $email,
                        //     // 'role' => 'author',
                        // ));
                        // var_dump($wpdb->insert_id);
                        // $check_insert = $wpdb->insert($table_name, [
                        //     'user_pass' => wp_hash_password($mobile_number),
                        //     'user_login' => $username,
                        //     'user_nicename' => $username,
                        //     'user_email' => $email,
                        // ]);
                        $student_id = wp_insert_user([
                            'user_pass' => $mobile_number,
                            'user_login' => $username,
                            'user_email' => $email,
                            'role' => 'author',
                        ]);
                        $wpdb->update($tablename,
                            array('user_nicename' => $arabic_name),
                            array('ID' => $student_id)
                        );
                        add_user_meta($student_id, 'gender', $gender);
                        add_user_meta($student_id, 'national_number', $national_number);
                        add_user_meta($student_id, 'mobile_number', $mobile_number);
                        add_user_meta($student_id, 'group_id', $group_id);

                        if ($wpdb->insert_id > 0) {
                            wp_update_user(['ID' => $wpdb->insert_id, 'role' => 'author']);
                            $totalInserted++;
                        }
                        if ($wpdb->insert_id == 0) {
                            $done = true;
                        }
                    }
                }

            }
            echo "<h3 style='color: green;'>Total record Inserted : " . $totalInserted . "</h3>";

        } else {
            echo "<h3 style='color: red;'>Invalid Extension</h3>";
        }

    }
    if ($done) {
        add_settings_error('user-is-exsists', 'user-is-exsists', 'Trainees add successfully.', 'success');
        settings_errors('user-is-exsists');
    }
    // Change active cohort from list of cohorts
    if (isset($_POST['active_cohort'])) {
        update_option('active_cohort', $_POST['active_cohort']);
        add_settings_error('update_active_cohort', 'update_active_cohort', 'Cohort update succesfully.', 'success');
        settings_errors('update_active_cohort');
    }
}

function get_group_id_by_group_name($name)
{

    global $wpdb;
    $group_id = $wpdb->get_results("SELECT {$wpdb->prefix}terms.term_id from {$wpdb->prefix}terms
            LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}terms.term_id={$wpdb->prefix}term_taxonomy.term_id
		    WHERE parent=" . ACTIVE_COHORT . " AND {$wpdb->prefix}terms.name LIKE '%$name%'")[0]->term_id;

    return $group_id;
}
?>
<div class="wrap">
    <h2>Settings</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row"><label for="active_cohort">Active Cohort</label></th>
                <td>
                    <select name="active_cohort" id="active_cohort">
                    <?php foreach ($cohorts as $cohort): ?>
            <option value="<?=$cohort->term_id?>"
                <?php if (ACTIVE_COHORT == $cohort->term_id): ?> selected <?php endif?>>
                <?=$cohort->name?></option>
                    <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
            <th><label for="active_cohort">Upload students file</label></th>
            <td><input type="file" name="import_file" ></td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>
