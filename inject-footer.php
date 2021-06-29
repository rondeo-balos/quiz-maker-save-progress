<?php

add_action("wp_footer", "qmrb_inject_code");
function qmrb_inject_code(){
    /*if(isset($_GET["resume-quiz"])){
        // the code in this condition may be deprecated in the future
        ?>
            <script>
                $(document).ready(function(){
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            ID : '<?php echo $_GET["resume-quiz"]; ?>',
                            action: 'qmrb_load_progress'
                        },
                        error: function(error) {
                            document.location = '';
                        },
                        success: function(response) {
                            var load = JSON.parse(response);
                            document.querySelector('.ays_next.start_button.action-button').innerHTML = 'Resume';
                            document.querySelector('.ays_next.start_button.action-button').onclick = function(){
                                setTimeout(function(){
                                    aysAnimateStep('fade', $($('.step')[1]), $($('.step')[load['last_step']]));
                                    var answers = load['answer_ids'].split(',');
                                    answers.forEach(function(e,i){
                                        document.querySelector('#'+e).checked = true;
                                    });
                                }, 500);
                            }
                        }
                    });
                });
            </script>
        <?php
    }else{*/
        $current_user = get_current_user_id();
        $ajax_url = admin_url('admin-ajax.php');
        ?>
            <script>
                if(typeof jQuery != 'undefined'){
                    (function($){
                        $(document).ready(function(){
                            var quiz = $('input[name="ays_quiz_id"]').val();
                            if(quiz != null){
                                $.ajax({
                                    url: '<?php echo esc_html($ajax_url) ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'qmrb_check_progress',
                                        user_id: '<?php echo esc_html($current_user) ?>',
                                        quiz_id: quiz
                                    },
                                    success: function(response) {
                                        var load = JSON.parse(response);
                                        $('.ays_next.start_button.action-button').val('Resume');
                                        $('.ays_next.start_button.action-button').click(function(){
                                            setTimeout(function(){
                                                aysAnimateStep('fade', $($('.step')[1]), $($('.step')[load['last_step']]));
                                                var answers = load['answer_ids'].split(',');
                                                answers.forEach(function(e,i){
                                                    document.querySelector('#'+e).checked = true;
                                                });
                                            }, 500);
                                        });
                                    }
                                });
                            }
                            
                        });
                    })(jQuery);
                }
            </script>
        <?php
    //}
}

?>