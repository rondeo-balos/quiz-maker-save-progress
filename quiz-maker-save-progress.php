<?php
/**
 * Plugin Name:     Quiz Maker - Save Progress
 * Plugin URI:      https://github.com/rondeo-balos/quiz-maker-save-progress
 * Description:     A plugin that Saves Quiz Maker Progress
 * Version:         0.2.1
 * Author:          Rondeo Balos
 * Author URI:      https://github.com/rondeo-balos/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quiz-makker-save-progress
*/

include 'constants.php';
include 'save-progress.php';
include 'list-saves.php';
include 'inject-footer.php';

add_action('plugins_loaded','qmrb_activate_quiz_maker_save_progress');
function qmrb_activate_quiz_maker_save_progress(){
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS `wordpress`.`wp_quiz_maker_saved_progress`(
        `ID` INT NULL AUTO_INCREMENT ,
        `user_id` TEXT NOT NULL ,
        `quiz_id` TEXT NOT NULL ,
        `quiz_link` TEXT NOT NULL ,
        `quiz_title` TEXT NOT NULL ,
        `last_step` TEXT NOT NULL ,
        `answer_ids` TEXT NOT NULL ,
        PRIMARY KEY (`ID`), UNIQUE (`quiz_id`)) ENGINE = InnoDB;";
    $wpdb->query($sql);
}

add_action('wp_ajax_qmrb_save_progress','qmrb_save_progress');
add_action('wp_ajax_nopriv_qmrb_save_progress','qmrb_save_progress');
function qmrb_save_progress() {

    global $wpdb;
    $table_name = $wpdb->prefix.QMRB_TABLE_NAME;
    $user_id = sanitize_key($_POST["save_details"]["user_id"]);
    $quiz_id = sanitize_text_field($_POST["save_details"]["quiz_id"]);
    $quiz_link = esc_url_raw($_POST["save_details"]["quiz_link"]);
    $quiz_title = sanitize_text_field($_POST["save_details"]["quiz_title"]);
    $last_step = sanitize_text_field($_POST["save_details"]["last_step"]);
    $answer_ids = sanitize_text_field($_POST["save_details"]["answer_ids"]);
    $data = array(
        'user_id'=>$user_id,
        'quiz_id'=>$quiz_id,
        'quiz_link'=>$quiz_link,
        'quiz_title'=>$quiz_title,
        'last_step'=>$last_step,
        'answer_ids'=>$answer_ids
    );
    //wp_die();
    $fields = '`' . implode('`,`', array_keys($data)) . '`';
    $format = "'" . implode("', '", $data) . "'";
    $sql = "INSERT INTO `$table_name` ($fields) VALUES ($format) ON DUPLICATE KEY UPDATE last_step = '$last_step', answer_ids = '$answer_ids'";
    $wpdb->query($sql);
    wp_die();
}

add_action('wp_ajax_qmrb_check_progress','qmrb_check_progress');
add_action('wp_ajax_nopriv_qmrb_check_progress','qmrb_check_progress');
function qmrb_check_progress(){
    global $wpdb;
    $table_name = $wpdb->prefix.QMRB_TABLE_NAME;
    $user_id = sanitize_key($_POST["user_id"]);
    $quiz_id = sanitize_text_field($_POST["quiz_id"]);

    $sql = "SELECT * FROM $table_name WHERE user_id='$user_id' AND quiz_id='$quiz_id'";
    //please check if there's data before encoding
    $load = $wpdb->get_row($sql);
    echo json_encode($load);
    wp_die();
}

?>