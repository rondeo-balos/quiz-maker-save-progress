<?php

class QuizMakerSaveProgressSettings {
	private $quiz_maker_save_progress_settings_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'quiz_maker_save_progress_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'quiz_maker_save_progress_settings_page_init' ) );
	}

	public function quiz_maker_save_progress_settings_add_plugin_page() {
		add_options_page(
			'Quiz Maker - Save Progress Settings', // page_title
			'Quiz Maker - Save Progress Settings', // menu_title
			'manage_options', // capability
			'quizz-maker-save-progress-settings', // menu_slug
			array( $this, 'quiz_maker_save_progress_settings_create_admin_page' ) // function
		);
	}

	public function quiz_maker_save_progress_settings_create_admin_page() {
		$this->quiz_maker_save_progress_settings_options = get_option( 'quiz_maker_save_progress_settings_option_name' ); ?>

		<div class="wrap">
			<h2>Quiz Maker - Save Progress Settings</h2>
			<p>Settings for Saving Quiz Maker Progress such as redirects and short codes</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'quiz_maker_save_progress_settings_option_group' );
					do_settings_sections( 'quiz-maker-save-progress-settings-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function quiz_maker_save_progress_settings_page_init() {
		register_setting(
			'quiz_maker_save_progress_settings_option_group', // option_group
			'quiz_maker_save_progress_settings_option_name', // option_name
			array( $this, 'quiz_maker_save_progress_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'quiz_maker_save_progress_settings_setting_section', // id
			'Settings', // title
			array( $this, 'quiz_maker_save_progress_settings_section_info' ), // callback
			'quiz-maker-save-progress-settings-admin' // page
		);

		add_settings_field(
			'qmrb_url_that_redirects_after_saving_quiz_0', // id
			'URL that redirects after saving quiz', // title
			array( $this, 'qmrb_url_that_redirects_after_saving_quiz_0_callback' ), // callback
			'quiz-maker-save-progress-settings-admin', // page
			'quiz_maker_save_progress_settings_setting_section' // section
		);

		add_settings_field(
			'qmrb_finish_later_button_shortcode_1', // id
			'"Finish Later" button (shortcode)', // title
			array( $this, 'qmrb_finish_later_button_shortcode_1_callback' ), // callback
			'quiz-maker-save-progress-settings-admin', // page
			'quiz_maker_save_progress_settings_setting_section' // section
		);

		add_settings_field(
			'qmrb_display_unfinished_quiz_shortcode_2', // id
			'Display unfinished quiz (shortcode)', // title
			array( $this, 'qmrb_display_unfinished_quiz_shortcode_2_callback' ), // callback
			'quiz-maker-save-progress-settings-admin', // page
			'quiz_maker_save_progress_settings_setting_section' // section
		);
	}

	public function quiz_maker_save_progress_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['qmrb_url_that_redirects_after_saving_quiz_0'] ) ) {
			$sanitary_values['qmrb_url_that_redirects_after_saving_quiz_0'] = sanitize_text_field( $input['qmrb_url_that_redirects_after_saving_quiz_0'] );
		}

		if ( isset( $input['qmrb_finish_later_button_shortcode_1'] ) ) {
			$sanitary_values['qmrb_finish_later_button_shortcode_1'] = sanitize_text_field( $input['qmrb_finish_later_button_shortcode_1'] );
		}

		if ( isset( $input['qmrb_display_unfinished_quiz_shortcode_2'] ) ) {
			$sanitary_values['qmrb_display_unfinished_quiz_shortcode_2'] = sanitize_text_field( $input['qmrb_display_unfinished_quiz_shortcode_2'] );
		}

		return $sanitary_values;
	}

	public function quiz_maker_save_progress_settings_section_info() {
		
	}

	public function qmrb_url_that_redirects_after_saving_quiz_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="quiz_maker_save_progress_settings_option_name[qmrb_url_that_redirects_after_saving_quiz_0]" id="qmrb_url_that_redirects_after_saving_quiz_0" value="%s" placeholder="Leave empty to redirect on the same page">',
			isset( $this->quiz_maker_save_progress_settings_options['qmrb_url_that_redirects_after_saving_quiz_0'] ) ? esc_attr( $this->quiz_maker_save_progress_settings_options['qmrb_url_that_redirects_after_saving_quiz_0']) : ''
		);
	}

	public function qmrb_finish_later_button_shortcode_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="quiz_maker_save_progress_settings_option_name[qmrb_finish_later_button_shortcode_1]" id="qmrb_finish_later_button_shortcode_1" value="%s" readonly>',
			"[quiz-maker-save-progress]"//isset( $this->quiz_maker_save_progress_settings_options['qmrb_finish_later_button_shortcode_1'] ) ? esc_attr( $this->quiz_maker_save_progress_settings_options['qmrb_finish_later_button_shortcode_1']) : ''
		);
	}

	public function qmrb_display_unfinished_quiz_shortcode_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="quiz_maker_save_progress_settings_option_name[qmrb_display_unfinished_quiz_shortcode_2]" id="qmrb_display_unfinished_quiz_shortcode_2" value="%s" readonly>',
			"[quiz-maker-list-saves]"//isset( $this->quiz_maker_save_progress_settings_options['qmrb_display_unfinished_quiz_shortcode_2'] ) ? esc_attr( $this->quiz_maker_save_progress_settings_options['qmrb_display_unfinished_quiz_shortcode_2']) : ''
		);
	}

}
if ( is_admin() )
	$quiz_maker_save_progress_settings = new QuizMakerSaveProgressSettings();

/* 
 * Retrieve this value with:
 * $quiz_maker_save_progress_settings_options = get_option( 'quiz_maker_save_progress_settings_option_name' ); // Array of All Options
 * $qmrb_url_that_redirects_after_saving_quiz_0 = $quiz_maker_save_progress_settings_options['qmrb_url_that_redirects_after_saving_quiz_0']; // URL that redirects after saving quiz
 * $qmrb_finish_later_button_shortcode_1 = $quiz_maker_save_progress_settings_options['qmrb_finish_later_button_shortcode_1']; // \"Finish Later\" button (shortcode)
 * $qmrb_display_unfinished_quiz_shortcode_2 = $quiz_maker_save_progress_settings_options['qmrb_display_unfinished_quiz_shortcode_2']; // Display unfinished quiz (shortcode)
 */

add_action( 'in_admin_footer', 'qmrb_admin_footer');
function qmrb_admin_footer($a){
	if(isset($_REQUEST['page'])){
		if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), QMRB_PLUGIN_NAME)){
			?>
			<p style="font-size:13px;text-align:center;font-style:italic;">
				
				<span><?php echo __( "If you love my plugin, please do big favor and rate us on", QMRB_PLUGIN_NAME); ?></span> 
				<a target="_blank" href='https://wordpress.org/support/plugin/quiz-maker-save-progress/reviews/'>WordPress.org</a>
				
			</p>
		<?php
		}
	}
}