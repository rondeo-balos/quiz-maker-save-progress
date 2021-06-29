<?php

    add_shortcode('quiz-maker-list-saves','qmrb_list_saves');
    function qmrb_list_saves(){
        ob_start();
        $current_user = get_current_user_id();
        global $wpdb;
        $table_name = $wpdb->prefix.QMRB_TABLE_NAME;

        $sql = "SELECT * FROM $table_name WHERE user_id = $current_user";
        $results = $wpdb->get_results($sql);

        foreach($results as $row){
            //display a table like or something
            ?>
                <div class="qrmb-container">
                    <strong><?php echo esc_html($row->quiz_title) ?></strong> <a href="<?php echo esc_html($row->quiz_link) ?>">Resume Quiz</a>
                </div>
            <?php
        }

        ?>
            <style>
                .qmrb-container{
                    min-height: 350px;
                    width: 400px;
                    background-color: #ffffff;
                    background-position: center center;
                    border-radius: 3px 3px 0px 0px;
                    box-shadow: 0px 0px 15px 1px rgb(0 0 0 / 40%);
                    border: none;
                }
            </style>
        <?php

        return ob_get_clean();
    }

?>