<?php

add_shortcode('quiz-maker-save-progress','qmrb_save_progress_shortcode');
function qmrb_save_progress_shortcode(){
    ob_start();
    $current_user = get_current_user_id();
    $quiz_maker_save_progress_settings_options = get_option( 'quiz_maker_save_progress_settings_option_name' );
    $url_that_redirects_after_saving_quiz_0 = $quiz_maker_save_progress_settings_options['url_that_redirects_after_saving_quiz_0'];
    ?>
    <center>
        <form id="save_progress" action="#" method="POST" data-url="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_html($current_user) ?>">

            <div class="form-group">
                <button type="submit" class="btn btn-primary action-button">Finish Later</button>
            </div>
        </form>
    </center>

    <style>
        #save_progress{
            opacity: 0;
            transition: 0.5s ease;
        }
        .save_progress_display{
            opacity: 1 !important;
        }
    </style>

    <script>

    if(typeof jQuery != 'undefined'){
        (function($){
            $(document).ready(function(){
                $('.ays_next.start_button.action-button').click(function(){
                    $('#save_progress').addClass('save_progress_display');
                });
            });
            $("#save_progress").on("submit", function (event) {
                event.preventDefault();

                var form = $(this);
                var ajaxurl = form.data("url");
                var last = '';
                var steps = document.querySelectorAll('.step');
                steps.forEach(function(e,i){
                    if(i!=0){
                        if(e.classList.contains('active-step'))
                            last = i;
                    }
                });
                var ids = [];
                $('input:radio:checked').each(function(i,e){
                    ids.push($(e).attr('id'))
                });
                var detail_info = {
                    user_id: form.find("#user_id").val(),
                    quiz_id: $('input[name="ays_quiz_id"]').val(),
                    quiz_link: window.location.href,
                    quiz_title: $('.ays-fs-title').html(),
                    last_step: last,
                    answer_ids: ids.join(",")
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        save_details : detail_info,
                        action: 'qmrb_save_progress'
                    },
                    error: function(error) {
                        alert("Progress Save Failed" + error.toString());
                    },
                    success: function(response) {
                        console.log(response);
                        document.location = '<?php echo esc_html($url_that_redirects_after_saving_quiz_0) ?>';
                    }
                });
            });
        })(jQuery);
    }
    
    </script>
    <?php
    return ob_get_clean();
}

?>