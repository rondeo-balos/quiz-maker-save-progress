<?php
/**
 * Plugin Name:     Quiz Maker - Save Progress
 * Description:     A plugin that Saves Quiz Maker Progress
 * Author:          Rondeo Balos
 * Version:         1.0.1
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
function qmrb_save_progress() {

    global $wpdb;
    $table_name = $wpdb->prefix.QMRB_TABLE_NAME;
    $save_details = sanitize_text_field($_POST["save_details"]);
    $data = array(
        'user_id'=>$save_details['user_id'],
        'quiz_id'=>$save_details['quiz_id'],
        'quiz_link'=>$save_details['quiz_link'],
        'quiz_title'=>$save_details['quiz_title'],
        'last_step'=>$save_details['last_step'],
        'answer_ids'=>$save_details['answer_ids']
    );
    $fields = '`' . implode('`,`' array_keys($data)) . '`';
    $format = "'" . implode("', '") . "'";
    $sql = "INSERT INTO `$table_name` ($fields) VALUES ($format) ON DUPLICATE KEY UPDATE last_step = '$save_details[last_step]', answer_ids = '$save_details[answer_ids]'";
    $wpdb->query($sql);

}

// this action may be deprecated in the future
// This is already deprecated
/*add_action('wp_ajax_qmrb_load_progress','qmrb_load_progress');
function qmrb_load_progress(){
    global $wpdb;
    $table_name = $wpdb->prefix.QMRB_TABLE_NAME;
    
    $sql = "SELECT * FROM $table_name WHERE ID = $_POST[ID]";
    //please check if there's data before encoding
    $load = $wpdb->get_row($sql);
    echo json_encode($load);
    wp_die();
}*/

add_action('wp_ajax_qmrb_check_progress','qmrb_check_progress');
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