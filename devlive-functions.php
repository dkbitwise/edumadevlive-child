<?php
include_once 'tim-zone-list.php';

define( 'booking_list_page', '/bookings' );
define( 'booking_page', '/book-online-coaching' );

/**
 * Changing message text on login page
 *
 * @param $errors
 *
 * @return mixed
 */
add_action( 'admin_menu', 'remove_admin_menu_items' );

function remove_admin_menu_items() {

	$user = wp_get_current_user();
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'lp_teacher', $user->roles ) || in_array( 'subscriber', $user->roles ) ) {
			remove_menu_page( 'index.php' );
			remove_menu_page( 'edit.php' );
			remove_menu_page( 'edit.php?post_type=our_team' );
			remove_menu_page( 'edit.php?post_type=aoc_popup' );
			remove_menu_page( 'edit.php?post_type=portfolio' );
			remove_menu_page( 'edit.php?post_type=testimonials' );
			remove_menu_page( 'edit.php?post_type=elementor_library' );
			remove_menu_page( 'edit.php?post_type=gb_xapi_content' );
			remove_menu_page( 'admin.php?page=bookme-calender' );
			remove_submenu_page( 'bookme-menu', 'bookme-calender' );
			remove_submenu_page( 'bookme-menu', 'bookme-bookings' );
			//remove_submenu_page('admin.php?page=bookme-bookings');
			remove_menu_page( 'learn_press' );
			remove_menu_page( 'woocommerce' );
			remove_menu_page( 'upload.php' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'wpcf7' );
			remove_menu_page( 'profile.php' );
			remove_menu_page( 'tools.php' );
		}
	}
}

function inline_css() {
	$user = wp_get_current_user();
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'lp_teacher', $user->roles ) ) {
			echo "<style>.menu-item-19434{display:none!important}</style>";
		}
	}
}

add_action( 'wp_head', 'inline_css' );

function bitwise_admin_notices() {
	$user = wp_get_current_user();
	if ( in_array( 'lp_teacher', $user->roles ) ) {
		echo '<div class="updated"><p><strong>Welcome</strong> ' . $user->display_name . '</p></div>';
	}
}

add_action( 'admin_notices', 'bitwise_admin_notices' );

function update_message( $errors ) {
	if ( isset( $errors->errors ) ) {
		if ( isset( $errors->errors['registered'] ) && ! empty( $errors->errors['registered'] ) ) {
			if ( isset( $_GET['checkemail'] ) && 'registered' == $_GET['checkemail'] ) {
				if ( tml_allow_user_passwords() ) {
					/* if password allow on registration from Theme my login plugin setting */
					//$errors->errors['registered'][0]='Registration complete. You may now log in.';
				} else {
					/* if password not allow on registration from Theme my login plugin setting */
					$errors->errors['registered'][0] = __( 'Registration confirmation will be emailed to you from there you have to click on the link and set the password' );
				}
			}
		}
	}

	return $errors;
}

add_filter( 'wp_login_errors', 'update_message' );

/**
 *  Redirect instructor to course page if they open booking page
 *
 *
 * */

add_action( 'template_redirect', 'check_instructor_role' );


function check_instructor_role() {

	if ( is_page( 'choose-plan' ) && is_user_logged_in() ) {
		$user = wp_get_current_user();
		if ( in_array( 'lp_teacher', $user->roles ) ) {
			wp_redirect( site_url( 'live-courses' ) );
			exit;
		}

	}

	if ( is_page( 'lostpassword' ) && is_user_logged_in() ) {
		wp_redirect( site_url( 'live-courses' ) );
		exit;

	}
	if ( is_page( 'courses' ) && ! is_user_logged_in() ) {
		wp_redirect( site_url( 'live-courses' ) );
		exit;

	}

	if ( is_page( 'login' ) && is_user_logged_in() ) {
		wp_redirect( site_url( 'live-courses' ) );
		exit;

	}
	if ( is_page( 'dashboard' ) && is_user_logged_in() ) {
		wp_redirect( site_url( 'live-courses' ) );
		exit;

	}

	if ( is_page( 'my-account' ) && ! is_user_logged_in() ) {
		wp_redirect( site_url( 'login' ) );
		exit;

	}

	if ( is_page( 'profile' ) && ! is_user_logged_in() ) {
		wp_redirect( site_url( 'login' ) );
		exit;

	}

}


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Check is Theme my plugin activate
 * Because we are adding extra registration fields through Theme my login plugin
 */
if ( is_plugin_active( 'theme-my-login/theme-my-login.php' ) ) {


	/**
	 * Add Student Registration extra fields from here
	 */
	function add_tml_registration_form_fields() {
		if ( ! tml_allow_user_passwords() ) {
			tml_add_form_field( 'register', 'reg_passmail', array(
				'type'     => 'custom',
				'content'  => '<p id="reg_passmail">' . __( 'Registration confirmation will be emailed to you from there you have to click on the link and set the password' ) . '</p>',
				'priority' => 30,
			) );
		}
		tml_add_form_field( 'register', 'parent_heading', array(
			'type'     => 'custom',
			'content'  => 'Parent Details',
			'priority' => 1,
		) );
		tml_add_form_field( 'register', 'parent_f_name', array(
			'type'     => 'text',
			'value'    => tml_get_request_value( 'parent_f_name', 'post' ),
			'label'    => 'First Name',
			//'description' => 'Enter your parent first name.',
			'priority' => 5,
		) );
		tml_add_form_field( 'register', 'parent_l_name', array(
			'type'     => 'text',
			'value'    => tml_get_request_value( 'parent_f_name', 'post' ),
			'label'    => 'Last name',
			// 'description' => 'Enter your parent last name.',
			'priority' => 5,
		) );
		tml_add_form_field( 'register', 'parent_email', array(
			'type'     => 'email',
			'value'    => tml_get_request_value( 'parent_email', 'post' ),
			//	'error'=>'Provide valid details',
			'label'    => 'Email',
			// 'description' => 'Enter your parent email.',
			'priority' => 5,

		) );
		tml_add_form_field( 'register', 'parent_cnf_email', array(
			'type'     => 'email',
			'value'    => tml_get_request_value( 'parent_cnf_email', 'post' ),
			'label'    => 'Confirm Email',
			//'description' => 'Re-enter your parent email.',
			'priority' => 5,
		) );
		tml_add_form_field( 'register', 'parent_phone', array(
			'type'     => 'tel',
			'value'    => tml_get_request_value( 'parent_phone', 'post' ),
			'label'    => 'Phone',
			'id'       => 'parent_phone',
			//'description' => 'Also add country code like. ( +91XXXXXXXXXX, +44XXXXXXXXX, +1XXXXXXXXXX )',
			'priority' => 5,
		) );
		tml_add_form_field( 'register', 'parent_phone_code', array(
			'type'     => 'hidden',
			'value'    => '',
			'id'       => 'parent_phone_code',
			'priority' => 5,
		) );
		tml_add_form_field( 'register', 'student_heading', array(
			'type'     => 'custom',
			'content'  => 'Student Details',
			'priority' => 6,
		) );
		tml_add_form_field( 'register', 'first_name', array(
			'type'     => 'text',
			'value'    => tml_get_request_value( 'first_name', 'post' ),
			'label'    => 'First Name',
			// 'description' => 'Enter your first name.',
			'priority' => 6,
		) );
		tml_add_form_field( 'register', 'last_name', array(
			'type'     => 'text',
			'value'    => tml_get_request_value( 'last_name', 'post' ),
			'label'    => 'Last Name',
			//'description' => 'Enter your first name.',
			'priority' => 6,
			'error'    => '',
		) );
		tml_add_form_field( 'register', 'phone', array(
			'type'     => 'tel',
			'value'    => tml_get_request_value( 'phone', 'post' ),
			'label'    => 'Phone',
			'id'       => 'phone',
			//'description' => 'Also add country code like. ( +91XXXXXXXXXX, +44XXXXXXXXX, +1XXXXXXXXXX )',
			'priority' => 6,
		) );
		tml_add_form_field( 'register', 'phone_code', array(
			'type'     => 'hidden',
			'value'    => '',
			'id'       => 'phone_code',
			'priority' => 6,
		) );

		tml_add_form_field( 'register', 'timezone', array(
			'type'     => 'dropdown',
			'label'    => 'Timezone',
			'options'  => get_timezone_list(),
			'id'       => 'timezone',
			'priority' => 10,
		) );


	}

	add_action( 'init', 'add_tml_registration_form_fields' );


	/**
	 * Validate registration fields
	 *
	 * @param $errors
	 *
	 * @return mixed
	 */
	function validate_tml_registration_form_fields( $errors ) {

		if ( empty( $_POST['parent_f_name'] ) ) {
			$errors->add( 'empty_parent_f_name', '<strong>ERROR</strong>: Please enter your parent first name.' );
		}
		if ( empty( $_POST['parent_l_name'] ) ) {
			$errors->add( 'empty_parent_l_name', '<strong>ERROR</strong>: Please enter your parent last name.' );
		}
		if ( empty( $_POST['parent_email'] ) ) {
			$errors->add( 'empty_parent_email', '<strong>ERROR</strong>: Please enter your parent email.' );
		}
		if ( $_POST['parent_email'] != $_POST['parent_cnf_email'] ) {
			$errors->add( 'empty_parent_email', '<strong>ERROR</strong>: Parent email & confirm parent email should be same.' );
		}

		//List of not allowed domains
		$allowed = [ 'edu', 'net', 'us' ];
		// Separate string by @ characters (there should be only one)
		$parts = explode( '@', $_POST['parent_email'] );

		$domain    = array_pop( $parts );
		$domainend = explode( '.', $domain );

		// Remove and return the last part, which should be the domain
		$domainend1 = array_pop( $domainend );
		if ( in_array( $domainend1, $allowed ) ) // Check if the domain is in our list
		{
			$yes = 3;
		} else if ( $domainend1 != 'com' ) { //Check the domain have .com or not
			$yes = 4;
		} else {
			$yes = 0;
		}
		if ( $yes == 3 || $yes == 4 ) {
			$errors->add( 'empty_parent_email', '<strong>ERROR</strong>:Most schools districts do not allow message delivery to their email addresses. So please use a general email address like GMail or Yahoo Mail.' );
		}

		if ( empty( $_POST['parent_phone'] ) ) {
			$errors->add( 'empty_parent_phone', '<strong>ERROR</strong>: Please enter your parent phone.' );
		}
		if ( empty( $_POST['first_name'] ) ) {
			$errors->add( 'empty_first_name', '<strong>ERROR</strong>: Please enter your first name.' );
		}
		if ( empty( $_POST['last_name'] ) ) {
			$errors->add( 'empty_last_name', '<strong>ERROR</strong>: Please enter your last name.' );
		}
		if ( empty( $_POST['phone'] ) ) {
			$errors->add( 'empty_phone', '<strong>ERROR</strong>: Please enter your phone.' );
		}
		if ( empty( $_POST['timezone'] ) ) {
			$errors->add( 'empty_timezone', '<strong>ERROR</strong>: Please select your timezone.' );
		}


		return $errors;
	}

	//add_filter( 'registration_errors', 'validate_tml_registration_form_fields' );


	/**
	 * Save extra registration fields
	 *
	 * @param $user_id
	 */
	function save_tml_registration_form_fields( $user_id ) {
		if ( isset( $_POST['parent_f_name'] ) ) {
			update_user_meta( $user_id, 'parent_f_name', sanitize_text_field( $_POST['parent_f_name'] ) );
		}
		if ( isset( $_POST['parent_l_name'] ) ) {
			update_user_meta( $user_id, 'parent_l_name', sanitize_text_field( $_POST['parent_l_name'] ) );
		}
		if ( isset( $_POST['parent_email'] ) ) {
			update_user_meta( $user_id, 'parent_email', sanitize_text_field( $_POST['parent_email'] ) );
		}
		if ( isset( $_POST['parent_phone'] ) ) {
			update_user_meta( $user_id, 'parent_phone', sanitize_text_field( '+' . $_POST['parent_phone_code'] . $_POST['parent_phone'] ) );
		}
		if ( isset( $_POST['first_name'] ) ) {
			update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
		}
		if ( isset( $_POST['last_name'] ) ) {
			update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
		}
		if ( isset( $_POST['phone'] ) ) {
			update_user_meta( $user_id, 'phone', sanitize_text_field( '+' . $_POST['phone_code'] . $_POST['phone'] ) );
		}
		if ( isset( $_POST['timezone'] ) ) {
			update_user_meta( $user_id, 'timezone', sanitize_text_field( $_POST['timezone'] ) );
		}
	}

	//add_action( 'user_register', 'save_tml_registration_form_fields' );


	/**
	 * Adding some css for registration page
	 */
	function registration_page_css() {
		?>
        <style>
            #tab-overview .thim-course-content {
                border-right: none !important;
            }

            #tab-overview .thim-course-info {
                border-left: 1px solid #eee;
            }

            .tml-register {

                margin-top: 18px;
            }

            .learn-press-user-profile {

                display: none;
            }

            .tml-lostpassword {

                width: 50%;
                margin: auto;
            }

            .course-item-title :first-letter {
                text-transform: uppercase;
            }

            .info_wrapp .nav.nav-pills .nav_link {
                padding: 3px 13px;
            }

            .info_wrapp .nav-pills > li.active > a.nav_link {
                background-color: #ff8503;
                border-radius: 0;
                color: #fff;
                margin-left: 3px;
            }

            .info_wrapp .info_close, .info_wrapp .info_close:focus, .info_wrapp .info_close:hover {
                background-color: #000;
                color: #fff;
                right: 100%;
                opacity: 0;
                padding: 11px 13px;
                position: fixed;
                text-shadow: 0 0;
                top: 0;
                transition: all 0.5s ease 0s;
            }

            .select2-container--default .select2-selection--single {
                border-color: #38c5b6 !important;
            }

            .woocommerce form .form-row input.input-text {
                border-color: #38c5b6 !important;
            }

            .variation-Booking > p {

                text-align: left;
            }

            .newsocial > a {


                padding: 10px;
            }

            .info_wrapp .sidebar_list li {
                display: block;
            }

            .sidebar_list > li {
                border-top: 1px solid #e3e3e3;
                display: inline-block;
                list-style: outside none none;
                padding: 6px 0;
            }

            .info_wrapp.active_side_bar {
                transform: translateX(0);
            }

            .info_wrapp {
                background-color: white;
                box-shadow: 0 0 3px -1px #000000;
                height: 100%;
                right: 0;
                padding: 10px;
                position: fixed;
                top: 14%;
                transform: translateX(100%);
                transition: all 0.5s ease 0s !important;
                width: 30%;
                z-index: 13;
            }

            .thumbnail {
                display: block;
                padding: 4px;
                margin-bottom: 20px;
                line-height: 1.42857143;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                -webkit-transition: border .2s ease-in-out;
                -o-transition: border .2s ease-in-out;
                transition: border .2s ease-in-out;
            }

            .thumbnail {
                display: block;
                padding: 4px;
                margin-bottom: 20px;
                line-height: 1.42857143;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                -webkit-transition: border .2s ease-in-out;
                -o-transition: border .2s ease-in-out;
                transition: border .2s ease-in-out;
            }

            .thumbnail {
                display: block;
                padding: 4px;
                margin-bottom: 20px;
                line-height: 1.42857143;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                -webkit-transition: border .2s ease-in-out;
                -o-transition: border .2s ease-in-out;
                transition: border .2s ease-in-out;
            }

            .thumbnail {
                border: 0px solid #ddd !important;
            }

            .close {

                font-size: 21px;
                font-weight: 700;
                line-height: 1;
                color: #000;
                text-shadow: 0 1px 0 #fff;
                filter: alpha(opacity=20);
                opacity: .2;
            }

            .bttn.help_btn, .bttn.help_btn:hover, .bttn.help_btn:focus {
                background-color: #2ec4b6;
            }

            .content_btns {
                display: flex;
                right: -25.5%;
                position: fixed;
                top: 50%;
                transform: rotateZ(90deg);
                transform-origin: 4% 57% 0;
                width: 28%;
                z-index: 9;
            }

            .btn121 {
                background: #4e9a05;
                position: fixed;
                top: 230px;
                right: -45px;
            }

            .content_btns .bttn {

                text-align: center;
                padding: 6px 12px;
                border: 1px solid transparent;
                border-radius: 0;
                color: #fff;
                /* font-weight: 600; */
                text-transform: uppercase;
                display: inline-block;
                margin-bottom: 0;
                font-size: 14px;
                line-height: 1.42857143;
                white-space: nowrap;
                vertical-align: middle;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                background-image: none;
            }

            .error {
                color: red;
                display: inline-block;
                font-size: 14px !important;
            }

            .studentdetails {
                padding-top: 2px;
                clear: both;

            }

            .return-to-shop {
                display: none !important;
            }

            .tml-parent_f_name-wrap {
                width: 49%;
                display: inline-block;
                float: left;
                margin-right: 0.5%;
            }

            .tml-parent_l_name-wrap {
                width: 49%;
                display: inline-block;
                float: right;
                margin-left: 0.5%;

            }

            .tml-parent_email-wrap {
                width: 49%;
                display: inline-block;
                float: left;
                margin-right: 0.5%;
                clear: both;

            }


            .tml-parent_cnf_email-wrap {
                width: 49%;
                display: inline-block;
                float: right;
                margin-left: 0.5%;
                /*clear: both;*/

            }

            .tml-user_pass1-wrap {
                width: 49%;
                display: inline-block;
                float: left;
                margin-right: 0.5%;


            }

            .tml-user_pass2-wrap {
                width: 49%;
                display: inline-block;
                float: right;
                margin-left: 0.5%;

            }

            .tml-first_name-wrap {
                width: 49%;
                display: inline-block;
                float: left;
                margin-right: 5px;

            }

            .tml-last_name-wrap {
                width: 49%;
                display: inline-block;
                float: right;
                margin-left: 0.5%;

            }

            .tml-register-link {
                display: none;
            }

            .tml-register .tml-lostpassword-link {

                display: none;
            }

            .tml-register .tml-login-link {
                display: none;
            }

            .tml .tml-error {
                color: #dc3232;
            }

            .tml-student_heading-wrap {
                margin-top: 35px;

            }

            .tml-indicator-wrap {
                margin-top: 12px;
            }

            .tml-rememberme-wrap {

                display: none !important;
            }

            .tml-submit-wrap {

                margin-top: 9px;
            }

            .tml .tml-field {
                border-color: #272323;
                color: black;
            }

            .thumb img {
                height: auto !important;
            }

            .course-summary .course-thumbnail img {
                height: 512px;
            }

            .tml-login {
                width: 50%;
                margin: auto;
            }

            .tml-register {
                width: 50%;
                margin: auto;
            }

            .indicator-hint {
                display: none;
            }

            .tml .tml-field-wrap {
                margin-bottom: 0px !important;
            }

            .tml .tml-user_login-wrap, .tml-user_email-wrap, .tml-parent_phone-wrap, .tml-phone-wrap, .tml-timezone-wrap {
                width: 100% !important;
                display: inline-block !important;
            }

            .tml-user_email-wrap, .tml-user_pass1-wrap, .tml-user_pass2-wrap, .tml-phone-wrap {
                padding-bottom: 0px !important;
            }

            #pass-strength-result {
                width: 14em;
            }

            .tml-parent_heading-wrap, .tml-student_heading-wrap {
                font-size: 29px;
                font-weight: bold;
                border-bottom: 1px solid;
                padding-bottom: 10px;
            }

            input.tml-field {
                width: 100%;
            }

            label.tml-field-wrap.tml-last_name-wrap {
                width: 100%;
            }

            .tml-user_email-wrap,
            .tml-user_pass1-wrap,
            .tml-user_pass2-wrap,
            .tml-phone-wrap {
                padding-bottom: 21px;
            }

            p#reg_passmail {
                color: #414141;
                padding: 15px 16px;
                font-weight: bold;
                background: #ededed;
                margin-bottom: 13px;
            }


            /* intl-tel-input styling on register page */
            .tml-field-wrap .intl-tel-input {
                width: 100% !important;
            }

            span.parent_phone_msg.success, span.phone_msg.success {
                color: green;
                font-weight: bold;
            }

            span.parent_phone_msg.error, span.phone_msg.error {
                color: red;
                font-weight: bold;
            }
        </style>
		<?php
	}

	add_action( 'wp_footer', 'registration_page_css' );

}


/**
 * Creating custom shortcode
 * If user is login then display bookme form otherwise display login or registration link
 * @return string
 */
function bookme_with_login_or_signup() {
	if ( is_user_logged_in() ) {
		return do_shortcode( '[bookme]' );
	} else {
		return 'Please <a href="' . site_url( '/' ) . get_option( 'tml_login_slug' ) . '">Login</a> or <a href="' . site_url( '/' ) . get_option( 'tml_register_slug' ) . '">Register</a> for booking';
	}
}

add_shortcode( 'login_bookme', 'bookme_with_login_or_signup' );


/**
 * Adding script for auto fill student information in bookme form
 */
function auto_fill_bookme_fields() {
	?>
    <script>
        jQuery(document).ready(function ($) {

            $("body").on("click", ".bookme_step2#book_appointment", function () {
                var data = {
                    'action': 'get_bookme_user_fields'
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post('<?= admin_url( 'admin-ajax.php' ) ?>', data, function (response) {
                    var data = JSON.parse(response);
                    window.setInterval(function () {
                        if ($("#pname").length != 0) {
                            if ($("#pname").val() == '') {
                                $("#pname").val(data['data']['name']);
                            }
                        }
                        if ($("#email").length != 0) {
                            if ($("#email").val() == '') {
                                $("#email").val(data['data']['email']);
                            }
                        }
                        if ($("#phone").length != 0) {
                            if ($("#phone").val() == '') {
                                $("#phone").val(data['data']['phone']).trigger('keyup');
                            }
                        }
                    }, 1000);
                });
            })

        });

    </script>
	<?php
}

add_action( 'wp_footer', 'auto_fill_bookme_fields' );


function add_custom_style_devlive() {
	?>
    <style>
        /* Hide quantity from woocommerce cart  */
        .woocommerce-cart-form td.product-quantity, .woocommerce-cart-form th.product-quantity {
            display: none;
        }

        /* hide notes field from bookme step-3 */
        #bookme_container .note-field {
            display: none;
        }
    </style>
	<?php
}

add_action( 'wp_head', 'add_custom_style_devlive' );

/**
 * Sending AJAX responce for auto fill request comes from "auto_fill_bookme_fields" function
 * @return string
 */
function ajax_get_bookme_user_fields() {
	if ( is_user_logged_in() ) {
		global $current_user;
		$data = array(
			'name'  => get_user_meta( get_current_user_id(), 'first_name', true ) . ' ' . get_user_meta( get_current_user_id(), 'last_name', true ),
			'email' => $current_user->user_email,
			'phone' => get_user_meta( get_current_user_id(), 'phone', true )
		);
		echo json_encode( array( 'status' => 'success', 'data' => $data ) );
	} else {
		echo json_encode( array( 'status' => 'fail', 'message' => 'user not login' ) );
	}
	wp_die();
}

add_action( 'wp_ajax_get_bookme_user_fields', 'ajax_get_bookme_user_fields' );
add_action( 'wp_ajax_nopriv_get_bookme_user_fields', 'ajax_get_bookme_user_fields' );


add_filter( 'woocommerce_my_account_my_orders_query', 'custom_my_account_orders', 10, 1 );

function custom_my_account_orders( $args ) {
	$args['posts_per_page'] = 10;

	return $args;
}


if ( ! function_exists( 'thim_get_login_page_url' ) ) {
	/**
	 * Overriding thim functions
	 * Because this function are forcing redirecting Thme my login register page to thim register page
	 * @return string
	 */
	function thim_get_login_page_url() {
		return site_url( '/' ) . get_option( 'tml_register_slug' );
	}
}

if ( ! function_exists( 'thim_register_failed' ) ) {
	function thim_register_failed( $sanitized_user_login, $user_email, $errors ) {
	}

	add_action( 'register_post', 'thim_register_failed', 99, 3 );
}


/**
 * Overriding thim functions Because this function are forcing auto login
 *
 * @param $user_id
 *
 * @return mixed
 */
function thim_register_extra_fields( $user_id ) {
	return $user_id;
}

add_action( 'user_register', 'thim_register_extra_fields', 1000 );


/**
 * Login redirect after login to booking list page
 *
 * @param $redirect_to
 *
 * @return string
 */
/*function redirect_to_booking_list_page($redirect_to) {
    if( isset($_POST['log']) ){
        $email = strtolower(sanitize_user($_POST['log']));
        $user = get_user_by( 'email', $email );
        if( !empty($user) ){
            if( in_array('subscriber',$user->roles)){
				$redirect = site_url().'/my-courses/';
				return $redirect;
        		// return $redirect_to; //=site_url().booking_page;
            }
            else
            {
				$redirect = site_url().'/wp-admin/';
				return $redirect;
            }
        }

    }
    return $redirect_to;
}*/

function redirect_to_booking_list_page( $redirect_to ) {
	$user = wp_get_current_user();
	if ( ! empty( $user ) ) {
		if ( in_array( 'subscriber', $user->roles ) ) {
			$redirect = site_url() . '/my-courses/';

			return $redirect;
		} else {
			$redirect = site_url() . '/wp-admin/admin.php?page=bookme-dashboard';

			return $redirect;
		}
	}

	return $redirect_to;
}

add_filter( 'login_redirect', 'redirect_to_booking_list_page', 10, 2 );


/**
 * Set studnet role after signup through theme my login plugin
 *
 * @param $redirect_to
 * @param $user_data
 *
 * @return mixed
 */
function set_student_role_after_signup( $redirect_to, $user_data ) {
	$user = new WP_User( $user_data->ID );
	$user->add_role( 'student' );

	return $redirect_to = site_url() . '/my-courses';
	// return $redirect_to;
}

add_filter( 'tml_registration_redirect', 'set_student_role_after_signup', 10, 2 );

/**
 * Shorcode for displaying booking
 * @return string
 */
function bookme_cu_booking_display_fun() {
	global $wpdb;
	if ( ! is_admin() ) {
		if ( is_user_logged_in() ) {

			global $current_user;
			$user_email = $current_user->user_email;

			$user_id = get_current_user_id();

			$table_book_category         = $wpdb->prefix . 'bookme_category';
			$table_book_service          = $wpdb->prefix . 'bookme_service';
			$table_all_employee          = $wpdb->prefix . 'bookme_employee';
			$table_current_booking       = $wpdb->prefix . 'bookme_current_booking';
			$table_customers             = $wpdb->prefix . 'bookme_customers';
			$table_customer_booking      = $wpdb->prefix . 'bookme_customer_booking';
			$table_customer_ref          = $wpdb->prefix . 'bookme_customer_booking_ref';
			$table_payments              = $wpdb->prefix . 'bookme_payments';
			$table_settings2             = $wpdb->prefix . 'bookme_settings';
			$table_custom_fields         = $wpdb->prefix . 'bookme_custom_field';
			$table_booking_custom_fields = $wpdb->prefix . 'bookme_current_booking_fields';

			/*if(in_array('lp_teacher', $current_user->roles))
			{
				$bookings = $wpdb->get_results( $wpdb->prepare("SELECT b.ser_id,b.date,b.time,s.name,cb.no_of_person FROM $table_customer_booking cb LEFT JOIN $table_customers c ON cb.customer_id = c.id LEFT JOIN $table_current_booking b ON cb.booking_id = b.id LEFT JOIN $table_payments p ON cb.payment_id = p.id LEFT JOIN $table_book_service s ON b.ser_id = s.id LEFT JOIN $table_all_employee e ON b.emp_id = e.id where e.email=%s GROUP BY b.ser_id ORDER BY b.date",$user_email) );
			}
			else
			{
				$bookings = $wpdb->get_results( $wpdb->prepare("SELECT cb.*, c.name, c.phone, c.email, c.notes, b.id booking_id, b.duration, b.date, b.time, p.id payment_id, p.price, p.type, p.status payment_status, p.discount_price, s.name ser_name, s.id ser_id, e.name emp_name, cb.no_of_person person  FROM $table_customer_booking cb LEFT JOIN $table_customers c ON cb.customer_id = c.id LEFT JOIN $table_current_booking b ON cb.booking_id = b.id LEFT JOIN $table_payments p ON cb.payment_id = p.id LEFT JOIN $table_book_service s ON b.ser_id = s.id LEFT JOIN $table_all_employee e ON b.emp_id = e.id where c.email=%s ORDER BY b.date",$user_email) );
			}*/
			if ( in_array( 'subscriber', $current_user->roles ) ) {
				$tz       = get_user_meta( get_current_user_id(), 'timezone', true );
				$dateTime = new DateTime();
				$dateTime->setTimeZone( new DateTimeZone( 'UTC' ) );
				$tz_date = $dateTime->format( 'Y-m-d' );
				$tz_time = $dateTime->format( 'U' );

				if ( isset( $_GET['type'] ) && $_GET['type'] == 'completed' ) {
					$bookings = $wpdb->get_results( "SELECT cb.*, c.name, c.phone, c.email, c.notes, b.id booking_id, b.duration, b.date, b.time, p.id payment_id, p.price, p.type, p.status payment_status, p.discount_price, s.name ser_name, s.id ser_id, e.name emp_name, cb.no_of_person person  FROM $table_customer_booking cb LEFT JOIN $table_customers c ON cb.customer_id = c.id LEFT JOIN $table_current_booking b ON cb.booking_id = b.id LEFT JOIN $table_payments p ON cb.payment_id = p.id LEFT JOIN $table_book_service s ON b.ser_id = s.id LEFT JOIN $table_all_employee e ON b.emp_id = e.id where c.email='$user_email' and UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(b.date, ' ', b.time),'%Y-%m-%d %h:%i%p')) < $tz_time ORDER BY b.date" );
				} else {
					$bookings = $wpdb->get_results( "SELECT cb.*, c.name, c.phone, c.email, c.notes, b.id booking_id, b.duration, b.date, b.time, p.id payment_id, p.price, p.type, p.status payment_status, p.discount_price, s.name ser_name, s.id ser_id, e.name emp_name, cb.no_of_person person  FROM $table_customer_booking cb LEFT JOIN $table_customers c ON cb.customer_id = c.id LEFT JOIN $table_current_booking b ON cb.booking_id = b.id LEFT JOIN $table_payments p ON cb.payment_id = p.id LEFT JOIN $table_book_service s ON b.ser_id = s.id LEFT JOIN $table_all_employee e ON b.emp_id = e.id where c.email='$user_email' and UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(b.date, ' ', b.time),'%Y-%m-%d %h:%i%p')) > $tz_time  ORDER BY b.date" );
				}
				/*echo "<div class='row'><div class='col-sm-6'><select id='bookdet' style='margin: 15px 0px; border: 2px solid #38c5b6!important; background: #fff!important; border-radius: 5px;'>";
				?>
				<option <?php if(isset($_GET['type']) && $_GET['type'] == 'upcoming') { echo "selected"; } ?> value='upcoming'>Current/Upcoming</option>
				<option <?php if(isset($_GET['type']) && $_GET['type'] == 'completed') { echo "selected"; } ?> value='completed'>Completed</option>
				<?php
				echo "</select></div><div class='col-sm-6'><a href='".site_url('courses-2')."'><button style='margin: 15px 0px;' type='button' class='btn btn-primary pull-right'><i class='fa fa-plus-circle' aria-hidden='true'></i> Book</button></a></div></div>";*/
			}

			?>
            <script>
                jQuery(function ($) {


                    $.fn.dataTableExt.oSort['time-date-sort-pre'] = function (value) {
                        return Date.parse(value);
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-asc'] = function (a, b) {
                        return a - b;
                    };
                    $.fn.dataTableExt.oSort['time-date-sort-desc'] = function (a, b) {
                        return b - a;
                    };


                    $("#bookingTable_in").DataTable({
                        "order": [],
                        "language": {
							<?php if(isset( $_GET['type'] ) && $_GET['type'] == 'completed'){ ?>emptyTable: "No tutoring sessions have been completed"
							<?php }else{ ?>emptyTable: "No tutoring packages have been purchased"<?php } ?>,
                            search: "",
                            searchPlaceholder: "Search..."
                        },
                        "dom": '<"row"<"col-sm-2"l><"col-sm-4"f><"col-sm-4"<"bwtoolfilter">><"col-sm-2"<"bwtoolbook">>>rtip',
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        initComplete: function () {
                            //var title = $('#bookingTable_in thead th').eq( $(this).index() ).text();
                            var co = 0;
                            this.api().columns().every(function () {
                                var column = this;
                                var title = $('#bookingTable_in thead th').eq(co++).text();
                                if (co == 4) {
                                    var select = $('<button style="padding: 4px 10px!important" name="clearbtn" id="clearbtn" class="btn btn-primary pull-right"><i class="fa fa-remove"></i>Clear</button>')
                                        .appendTo($(column.footer()).empty())
                                } else {
                                    var select = $('<select><option value="">Filter by ' + title + '</option></select>')
                                        .appendTo($(column.footer()).empty())
                                        .on('change', function () {
                                            var val = $.fn.dataTable.util.escapeRegex(
                                                $(this).val()
                                            );

                                            column
                                                .search(val ? '^' + val + '$' : '', true, false)
                                                .draw();
                                        });

                                    column.data().unique().sort().each(function (d, j) {
                                        select.append('<option value="' + d + '">' + d + '</option>')
                                    });
                                }
                            });
                        }
                    });


                    $('#clearbtn').click(function () {
                        $('#bookingTable_in_filter input[type=search]').val('');
                        $("#bookingTable_in select").val($("#bookingTable_in select option:first").val());
                        $("#bookingTable_in select").trigger('change');
                    });

                    $("div.bwtoolbook").html("<!--<a href='#'><button style='padding: 4px 10px!important' type='button' class='btn btn-primary pull-right'><i class='fa fa-calendar' aria-hidden='true'></i> Calendar</button></a>-->");
                    $("div.bwtoolfilter").html("<select id='bookdet' style='border: 1px solid #ccc; padding: 4px 10px; width: 100%!important; background: #fff!important; border-radius: 5px;'><option <?php if ( isset( $_GET['type'] ) && $_GET['type'] == 'upcoming' ) {
						echo "selected";
					} ?> value='upcoming'>Current/Upcoming</option><option <?php if ( isset( $_GET['type'] ) && $_GET['type'] == 'completed' ) {
						echo "selected";
					} ?> value='completed'>Completed</option></select>");

                    $('#bookdet').on('change', function () {
                        var url = "?type=" + $(this).val();
                        if (url) {
                            window.location = url;
                        }
                        return false;
                    });
                    $('#student_select').on('change', function () {
                        var stid = $(this).val();
                        var s_stid = stid.split("_");
                        var url = "?pid=" + s_stid[0] + "&sid=" + s_stid[1] + "";
                        if (url) {
                            window.location = url;
                        }
                        return false;
                    });

                });
            </script>
            <style>
                tfoot {
                    display: table-header-group;
                }

                div.dataTables_filter {
                    float: left;
                    text-align: left;
                    width: 100% !important;
                }

                div.dataTables_filter input {
                    width: 100% !important;
                }

                div.dataTables_filter label {
                    display: block !important;
                }

                #bookingTable_in_wrapper .row {
                    padding: 10px;
                }

                #bookingTable_in span {
                    display: none;
                }
            </style>

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#table">Table View</a></li>
                <li><a data-toggle="tab" href="#calendar">Calendar View</a></li>
            </ul>

            <div class="tab-content">
                <div id="table" class="tab-pane fade in active">
                    <br>
					<?php
					echo '<table id="bookingTable_in" class="display nowrap table table-hover dataTable table-striped width-full border-table" data-tablesaw-mode="stack" data-child="tr" data-selectable="selectable">';
					if ( in_array( 'lp_teacher', $current_user->roles ) ) {
						echo "<tfoot><tr><th>Course</th><th>Tutor</th><th>Month</th><th>Time</th></tr></tfoot><thead><tr><th>Course</th><th>Tutor</th><th>Month</th><th>Time</th></tr></thead><tbody>";
					} else {
						echo "<tfoot><tr><th style='width: 25%'>Package</th><th style='width: 25%'>Student</th><th style='width: 25%'>Tutor</th><!--<th style='width: 20%'>Month</th>--><th>Date & Time</th></tr></tfoot><thead><tr><th>Package</th><th>Student</th><th>Tutor</th><!--<th>Month</th>--><th>Date & Time</th></tr></thead><tbody>";
					}
					global $student_country;
					foreach ( $bookings as $booking ) {

						$bk_reftable = $wpdb->prefix . 'bookme_customer_booking_ref';
						$table       = $wpdb->prefix . 'bwlive_students';
						$bk_id       = $booking->booking_id;

						$stu_id = $wpdb->get_var( "SELECT student_id FROM $bk_reftable WHERE booking_id=$bk_id" );

						$sql     = "SELECT student_id,student_fname,student_lname FROM $table where student_id='" . $stu_id . "' and parent_id=" . get_current_user_id() . " LIMIT 1";
						$results = $wpdb->get_results( $sql );

						$student_datetime = get_student_timezone( $booking->date . ' ' . $booking->time );
						$booking->date    = $student_datetime->format( 'jS F Y' );
						$booking->time    = $student_datetime->format( 'g:i A' );

						$sort_time = $booking->date . " " . $booking->time;
						$sort_time = strtotime( $sort_time );

						if ( in_array( 'lp_teacher', $current_user->roles ) ) {
							echo "<tr><td>" . $booking->name . "</td><td>" . $booking->name . "</td><td>" . date( "F Y", strtotime( $booking->date ) ) . "</td><td>" . date( "jS F Y", strtotime( $booking->date ) ) . " " . date( "g:i A", strtotime( $booking->time ) ) . "</td></tr>";
						} else {
							echo "<tr><td>" . $booking->ser_name . "</td><td>" . $results[0]->student_fname . " " . $results[0]->student_lname . "</td><td>" . $booking->emp_name . "</td><!--<td>" . date( "M Y", strtotime( $booking->date ) ) . "</td>--><td><span>" . $sort_time . "</span>" . date( "M d, Y - ", strtotime( $booking->date ) ) . " " . date( "g:i A", strtotime( $booking->time ) ) . "</td></tr>";
						}

					}
					echo "</tbody></table>";
					?>

                </div>
                <div id="calendar" class="tab-pane fade">
                    <br>
                    <div id="calendar_div">
						<?php echo getCalender(); ?>
                    </div>

                </div>
            </div>

			<?php
		} else {
			echo 'Please <a href="' . site_url( '/' ) . get_option( 'tml_login_slug' ) . '">Login</a> or <a href="' . site_url( '/' ) . get_option( 'tml_register_slug' ) . '">Register</a> for view bookings';
		}
	}

}

add_shortcode( 'bookme_cu_booking_display', 'bookme_cu_booking_display_fun' );


function add_intlTelInput_register() {
	global $post;
	$post_slug = isset( $post->post_name ) ? $post->post_name : '';

	if ( get_option( 'tml_register_slug' ) == $post_slug ) {
		//wp_enqueue_script('intlTelInput-js', get_stylesheet_directory_uri() . '/assets/js/utils.js', array( 'jquery' ));
		//wp_enqueue_script('intlTelInput-js', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/js/intlTelInput.min.js', array( 'jquery' ));
		//wp_enqueue_style('intlTelInput','https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/6.4.1/css/intlTelInput.css');

		wp_enqueue_script( 'intlTelInput-js', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.6/js/intlTelInput.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'intlTelInput', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.6/css/intlTelInput.css' );
	}
}

add_action( 'wp_enqueue_scripts', 'add_intlTelInput_register' );

add_action( 'wp_footer', 'add_script_abc' );
function add_script_abc() {
	global $post;
	$post_slug = isset( $post->post_name ) ? $post->post_name : '';

	if ( get_option( 'tml_register_slug' ) == $post_slug ) {
		?>
        <script>


            jQuery(document).ready(function ($) {
                var parent_phone = $("#parent_phone"),
                    phone = $("#phone"),
                    phone1 = $("#phone1"),
                    phone2 = $("#phone2");

                parent_phone.intlTelInput({
                    nationalMode: true,
                    //separateDialCode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    allowDropdown: true,
                    //initialCountry: "us",
                    onlyCountries: ["us"],
                    preferredCountries: ["us"]
                });

                parent_phone.keyup(function () {
                    if ($(".parent_phone_msg").length == 0) {
                        $('.tml-label[for="parent_phone"]').append(' <span class="parent_phone_msg"></span>')
                    }
                    if ($.trim(parent_phone.val())) {
                        if (parent_phone.intlTelInput("isValidNumber")) {
                            var getCode = parent_phone.intlTelInput('getSelectedCountryData').dialCode;
                            $(".parent_phone_msg").html('✓ Valid');
                            $(".parent_phone_msg").removeClass('error').addClass('success');
                            $(".parent_phone-error").css('display', 'none');
                            $("#parent_phone_code").val(getCode);
                        } else {
                            var getCode = parent_phone.intlTelInput('getSelectedCountryData').dialCode;
                            $(".parent_phone_msg").html('Invalid');
                            $(".parent_phone_msg").removeClass('success').addClass('error');
                            $(".parent_phone-error").css('display', 'block');
                            $("#parent_phone_code").val('');
                        }
                    }
                });

                phone.intlTelInput({
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    allowDropdown: true,
                    //initialCountry: "us",
                    onlyCountries: ["us"],
                    preferredCountries: ["us"]
                });
                phone.keyup(function () {
                    if ($(".phone_msg").length == 0) {
                        $('.tml-label[for="phone"]').append(' <span class="phone_msg"></span>')
                    }
                    if ($.trim(phone.val())) {
                        if (phone.intlTelInput("isValidNumber")) {
                            var getCode = phone.intlTelInput('getSelectedCountryData').dialCode;
                            $(".phone_msg").html('✓ Valid');
                            $(".phone_msg").removeClass('error').addClass('success');
                            $(".phone-error").css('display', 'none');
                            $("#phone_code").val(getCode);
                        } else {
                            $(".phone_msg").html('Invalid');
                            $(".phone_msg").removeClass('success').addClass('error');
                            $(".phone-error").css('display', 'block');
                            $("#phone_code").val('');
                        }
                    }
                });

                phone1.intlTelInput({
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    allowDropdown: true,
                    //initialCountry: "us",
                    onlyCountries: ["us"],
                    preferredCountries: ["us"]
                });
                phone1.keyup(function () {
                    if ($(".phone1_msg").length == 0) {
                        $('.tml-label[for="phone1"]').append(' <span class="phone1_msg"></span>')
                    }
                    if ($.trim(phone1.val())) {
                        if (phone1.intlTelInput("isValidNumber")) {
                            var getCode = phone1.intlTelInput('getSelectedCountryData').dialCode;
                            $(".phone1_msg").html('✓ Valid');
                            $(".phone1_msg").removeClass('error').addClass('success');
                            $("#phone1_code").val(getCode);
                            $(".phone1-error").css('display', 'none');
                        } else {
                            $(".phone1_msg").html('Invalid');
                            $(".phone1_msg").removeClass('success').addClass('error');
                            $(".phone1-error").css('display', 'block');
                            $("#phone1_code").val('');
                        }
                    }
                });

                phone2.intlTelInput({
                    nationalMode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    allowDropdown: true,
                    //initialCountry: "us",
                    onlyCountries: ["us"],
                    preferredCountries: ["us"]
                });
                phone2.keyup(function () {
                    if ($(".phone2_msg").length == 0) {
                        $('.tml-label[for="phone2"]').append(' <span class="phone2_msg"></span>')
                    }
                    if ($.trim(phone2.val())) {
                        if (phone2.intlTelInput("isValidNumber")) {
                            var getCode = phone2.intlTelInput('getSelectedCountryData').dialCode;
                            $(".phone2_msg").html('✓ Valid');
                            $(".phone2_msg").removeClass('error').addClass('success');
                            $("#phone2_code").val(getCode);
                            $(".phone2-error").css('display', 'none');
                        } else {
                            $(".phone2_msg").html('Invalid');
                            $(".phone2_msg").removeClass('success').addClass('error');
                            $(".phone2-error").css('display', 'block');
                            $("#phone2_code").val('');
                        }
                    }
                });


            })
        </script>
		<?php
	}

}

function cfwc_create_custom_field() {
	$args = array(
		'id'          => 'custom_text_field_title',
		'label'       => __( 'Classes', 'cfwc' ),
		'class'       => 'cfwc-custom-field',
		'desc_tip'    => true,
		'description' => __( 'Classes', 'ctwc' ),
	);
	woocommerce_wp_text_input( $args );
}

add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field' );

function cfwc_save_custom_field( $post_id ) {
	$product = wc_get_product( $post_id );
	$title   = isset( $_POST['custom_text_field_title'] ) ? $_POST['custom_text_field_title'] : '';
	$product->update_meta_data( 'custom_text_field_title', sanitize_text_field( $title ) );
	$product->save();
}

add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );
//add_action( 'user_register', 'auto_login_new_user' );
function wdm_mail_new( $to, $subject, $message, $headers = '', $attachments = '' ) {
	$schooluser_email = $to;

	// inject CSS rules for text and image alignment
	$css     = wdm_get_html_email_css_new();
	$message = $css . $message;

	global $woocommerce;

	$mailer = $woocommerce->mailer();

	$message = $mailer->wrap_message( $subject, $message );

	$mailer->send( $to, $subject, $message, $headers, $attachments );

	return true;
}

function wdm_get_html_email_css_new() {
	$css = '<style type="text/css"> .alignleft {float: left;margin: 5px 20px 5px 0;}.alignright {float: right;margin: 5px 0 5px 20px;}.aligncenter {display: block;margin: 5px auto;}img.alignnone {margin: 5px 0;}' . 'blockquote,q {quotes: none;}blockquote:before,blockquote:after,q:before,q:after {content: "";content: none;}' . 'blockquote {font-size: 24px;font-style: italic;font-weight: 300;margin: 24px 40px;}' . 'blockquote blockquote {margin-right: 0;}blockquote cite,blockquote small {font-size: 14px;font-weight: normal;text-transform: uppercase;}' . 'cite {border-bottom: 0;}abbr[title] {border-bottom: 1px dotted;}address {font-style: italic;margin: 0 0 24px;}' . 'del {color: #333;}ins {background: #fff9c0;border: none;color: #333;text-decoration: none;}' . 'sub,sup {font-size: 75%;line-height: 0;position: relative;vertical-align: baseline;}' . 'sup {top: -0.5em;}sub {bottom: -0.25em;}</style>';

	return $css;
}

function getCalender( $sId = '', $year = '', $month = '' ) {
	$stu_id               = ( $sId != '' ) ? $sId : 0;
	$dateYear             = ( $year != '' ) ? $year : date( "Y" );
	$dateMonth            = ( $month != '' ) ? $month : date( "m" );
	$date                 = $dateYear . '-' . $dateMonth . '-01';
	$currentMonthFirstDay = date( "N", strtotime( $date ) );

	$totalDaysOfMonth        = cal_days_in_month( CAL_GREGORIAN, $dateMonth, $dateYear );
	$totalDaysOfMonthDisplay = ( $currentMonthFirstDay == 1 ) ? ( $totalDaysOfMonth ) : ( $totalDaysOfMonth + ( $currentMonthFirstDay - 1 ) );
	$boxDisplay              = ( $totalDaysOfMonthDisplay <= 35 ) ? 35 : 42;
	$prevMonth               = date( "m", strtotime( '-1 month', strtotime( $date ) ) );
	$prevYear                = date( "Y", strtotime( '-1 month', strtotime( $date ) ) );
	$totalDaysOfMonth_Prev   = cal_days_in_month( CAL_GREGORIAN, $prevMonth, $prevYear );
	?>
    <main class="calendar-contain">
        <section class="title-bar">
            <a href="javascript:void(0);" class="title-bar__prev" onclick="getCalendar('calendar_div','','<?php echo date( "Y", strtotime( $date . ' - 1 Month' ) ); ?>','<?php echo date( "m", strtotime( $date . ' - 1 Month' ) ); ?>');"></a>

            <div class="title-bar__month">
                <select class="month-dropdown">
					<?php echo getMonthList( $dateMonth ); ?>
                </select>
                <select class="stu-dropdown">
					<?php echo getStudentList( $sId ); ?>
                </select>
            </div>
            <div class="title-bar__year">
                <select class="year-dropdown">
					<?php echo getYearList( $dateYear ); ?>
                </select>
            </div>

            <a href="javascript:void(0);" class="title-bar__next" onclick="getCalendar('calendar_div','','<?php echo date( "Y", strtotime( $date . ' + 1 Month' ) ); ?>','<?php echo date( "m", strtotime( $date . ' + 1 Month' ) ); ?>');"></a>
        </section>

        <aside class="calendar__sidebar" id="event_list">
			<?php echo getEvents(); ?>
        </aside>

        <section class="calendar__days">
            <section class="calendar__top-bar">
                <span class="top-bar__days">Mon</span>
                <span class="top-bar__days">Tue</span>
                <span class="top-bar__days">Wed</span>
                <span class="top-bar__days">Thu</span>
                <span class="top-bar__days">Fri</span>
                <span class="top-bar__days">Sat</span>
                <span class="top-bar__days">Sun</span>
            </section>

			<?php
			$dayCount = 1;
			$eventNum = 0;

			echo '<section class="calendar__week">';
			for ( $cb = 1; $cb <= $boxDisplay; $cb ++ ) {
				if ( ( $cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1 ) && $cb <= ( $totalDaysOfMonthDisplay ) ) {
					// Current date
					$currentDate = $dateYear . '-' . $dateMonth . '-' . $dayCount;

					// Get number of events based on the current date
					global $wpdb;
					$cur_book_table    = $wpdb->prefix . "bookme_current_booking";
					$cus_book_table    = $wpdb->prefix . "bookme_customer_booking";
					$cus_bookref_table = $wpdb->prefix . "bookme_customer_booking_ref";
					$cus_table         = $wpdb->prefix . "bookme_customers";

					$user      = wp_get_current_user();
					$cus_email = $user->user_email;
					$cus_id    = $wpdb->get_var( $wpdb->prepare( " select id from $cus_table where email=%s ", $cus_email ) );

					if ( isset( $sId ) && $sId != 0 ) {
						$ssid = $sId;
					} else {
						$ssid = get_current_user_id() . "_1";
					}

					$result = $wpdb->get_results( "SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id inner join $cus_bookref_table as cusref_book on cusref_book.booking_id = cur_book.id where cusref_book.student_id='$ssid' and cur_book.date='$currentDate' and cus_book.status='Approved' and cus_book.customer_id=$cus_id" );

					/*else
					{
						$result = $wpdb->get_results("SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id where cur_book.date='$currentDate' and cus_book.status='Approved' and cus_book.customer_id=$cus_id");
					}*/
					$eventNum = count( $result );

					if ( $eventNum > 1 ) {
						$label = "Sessions";
					} else if ( $eventNum == 1 ) {
						$label = "Session";
					} else {
						$label    = "";
						$eventNum = "";
					}

					$cus_holtable = $wpdb->prefix . "custom_holidays";
					$cus_hol      = $wpdb->get_var( $wpdb->prepare( " select status from $cus_holtable where date=%s ", $currentDate ) );

					$empid           = $result[0]->emp_id;
					$bookme_holtable = $wpdb->prefix . "bookme_holidays";
					$bookme_hol      = $wpdb->get_var( $wpdb->prepare( " select count(*) as no from $bookme_holtable where holi_date=%s ", $currentDate ) );

					$cr_date   = strtotime( $currentDate );
					$dt_p      = strtotime( "-7 day", $cr_date );
					$prev_date = date( 'Y-m-d', $dt_p );

					$dt_n      = strtotime( "+7 day", $cr_date );
					$next_date = date( 'Y-m-d', $dt_n );

					$result_prev = $wpdb->get_results( "SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id where cur_book.date='$prev_date' and cus_book.status='Approved' and cus_book.customer_id=$cus_id" );
					$result_next = $wpdb->get_results( "SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id where cur_book.date='$next_date' and cus_book.status='Approved' and cus_book.customer_id=$cus_id" );

					if ( $cus_hol == 1 ) {
						$cus_hol_name = $wpdb->get_var( $wpdb->prepare( " select name from $cus_holtable where date=%s ", $currentDate ) );

						echo ' 
                                <div class="calendar__day hday" onclick="getEvents(\'' . $currentDate . '\',\'ajx\');"> 
                                    <span style="color:#fff!important" class="calendar__date">' . $dayCount . '</span> 
                                    <span style="color:#fff!important" class="calendar__task">' . $cus_hol_name . '</span> 
                                </div> 
                            ';

					} else if ( $bookme_hol == 1 && count( $result_prev ) == 1 && count( $result_next ) == 1 ) {
						echo ' 
                                <div class="calendar__day doff" onclick="getEvents(\'' . $currentDate . '\',\'ajx\');"> 
                                    <span style="color:#000!important" class="calendar__date">' . $dayCount . '</span> 
                                    <span style="color:#000!important" class="calendar__task">Tutor DayOff</span> 
                                </div> 
                            ';
					} else {
						// Define date cell color
						$time_zone = get_user_meta( get_current_user_id(), 'timezone', true );
						//date_default_timezone_set("".$time_zone."");

						$dateTime = new DateTime();
						$dateTime->setTimeZone( new DateTimeZone( '' . $time_zone . '' ) );
						$tz_dt = $dateTime->format( 'Y-m-d' );


						if ( strtotime( $currentDate ) == strtotime( $tz_dt ) ) {
							echo ' 
	                                <div class="calendar__day today" onclick="getEvents(\'' . $currentDate . '\',\'ajx\');"> 
	                                    <span class="calendar__date">' . $dayCount . '</span> 
	                                    <span class="calendar__task calendar__task--today">' . $eventNum . ' ' . $label . '</span> 
	                                </div> 
	                            ';
						} elseif ( $eventNum > 0 ) {
							echo ' 
	                                <div class="calendar__day event" onclick="getEvents(\'' . $currentDate . '\',\'ajx\');"> 
	                                    <span class="calendar__date">' . $dayCount . '</span> 
	                                    <span class="calendar__task">' . $eventNum . ' ' . $label . '</span> 
	                                </div> 
	                            ';
						} else {
							echo ' 
	                                <div class="calendar__day no-event" onclick="getEvents(\'' . $currentDate . '\',\'ajx\');"> 
	                                    <span class="calendar__date">' . $dayCount . '</span> 
	                                    <span class="calendar__task">' . $eventNum . ' ' . $label . '</span> 
	                                </div> 
	                            ';
						}
					}
					$dayCount ++;
				} else {
					if ( $cb < $currentMonthFirstDay ) {
						$inactiveCalendarDay = ( ( ( $totalDaysOfMonth_Prev - $currentMonthFirstDay ) + 1 ) + $cb );
						$inactiveLabel       = '';
					} else {
						$inactiveCalendarDay = ( $cb - $totalDaysOfMonthDisplay );
						$inactiveLabel       = '';
					}
					echo ' 
                            <div class="calendar__day inactive"> 
                                <span class="calendar__date">' . $inactiveCalendarDay . '</span> 
                                <span class="calendar__task">' . $inactiveLabel . '</span> 
                            </div> 
                        ';
				}
				echo ( $cb % 7 == 0 && $cb != $boxDisplay ) ? '</section><section class="calendar__week">' : '';
			}
			echo '</section>';
			?>
        </section>
    </main>

    <script>
        function getCalendar(target_div, stu, year, month) {
            if (stu == '') {
                stu = jQuery('.stu-dropdown').val();
            }
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                data: 'action=get_calendar_event&func=getCalender&stu=' + stu + '&year=' + year + '&month=' + month,
                success: function (html) {
                    jQuery('#' + target_div).html(html);
                }
            });
        }

        function getEvents(date) {
            var stuid = jQuery('.stu-dropdown').val();
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                data: 'action=get_calendar_event&func=getEvents&stu=' + stuid + '&date=' + date,
                success: function (html) {
                    jQuery('#event_list').html(html);
                }
            });
        }

        jQuery(document).ready(function () {
            jQuery('.month-dropdown').on('change', function () {
                getCalendar('calendar_div', jQuery('.stu-dropdown').val(), jQuery('.year-dropdown').val(), jQuery('.month-dropdown').val());
            });

            jQuery('.stu-dropdown').on('change', function () {
                getCalendar('calendar_div', jQuery('.stu-dropdown').val(), jQuery('.year-dropdown').val(), jQuery('.month-dropdown').val());
            });

            jQuery('.year-dropdown').on('change', function () {
                getCalendar('calendar_div', jQuery('.stu-dropdown').val(), jQuery('.year-dropdown').val(), jQuery('.month-dropdown').val());
            });
        });
    </script>
	<?php
}

add_action( 'wp_ajax_get_calendar_event', 'get_calendar_event' );

function get_calendar_event() {
	if ( isset( $_POST['func'] ) && ! empty( $_POST['func'] ) ) {
		switch ( $_POST['func'] ) {
			case 'getCalender':
				getCalender( $_POST['stu'], $_POST['year'], $_POST['month'] );
				wp_die();
				break;
			case 'getEvents':
				getEvents( $_POST['stu'], $_POST['date'], 'ajx' );
				wp_die();
				break;
			default:
				break;
		}
	}
}

function getStudentList( $selected = '' ) {

	global $wpdb;
	$table_stu   = $wpdb->prefix . 'bwlive_students';
	$sql_stu     = "SELECT student_id,student_fname,student_lname FROM $table_stu where parent_id=" . get_current_user_id() . "";
	$results_stu = $wpdb->get_results( $sql_stu );
	foreach ( $results_stu as $stu ) {
		$sid         = $stu->student_id;
		$selectedOpt = ( $sid == $selected ) ? 'selected' : '';
		$options     .= "<option value=" . $sid . " " . $selectedOpt . ">" . $stu->student_fname . " " . $stu->student_lname . "</option>";
	}

	return $options;
}

/* 
 * Generate months options list for select box 
 */
function getMonthList( $selected = '' ) {
	$options = '';
	for ( $i = 1; $i <= 12; $i ++ ) {
		$value       = ( $i < 10 ) ? '0' . $i : $i;
		$selectedOpt = ( $value == $selected ) ? 'selected' : '';
		$options     .= '<option value="' . $value . '" ' . $selectedOpt . ' >' . date( "F", mktime( 0, 0, 0, $i + 1, 0, 0 ) ) . '</option>';
	}

	return $options;
}

/* 
 * Generate years options list for select box 
 */
function getYearList( $selected = '' ) {

	$time_zone = get_user_meta( get_current_user_id(), 'timezone', true );
	$dateTime  = new DateTime();
	$dateTime->setTimeZone( new DateTimeZone( '' . $time_zone . '' ) );
	$tz_yr = $dateTime->format( 'Y-m-d' );

	$yearInit = ! empty( $selected ) ? $selected : $tz_yr;
	$yr       = $selected - $tz_yr;
	if ( $selected == '2021' ) {
		$yr = $yr + 1;
	}
	$yearPrev = ( $yearInit - $yr );
	$yearNext = ( $yearInit + 5 );
	$options  = '';
	for ( $i = $yearPrev; $i <= $yearNext; $i ++ ) {
		$selectedOpt = ( $i == $selected ) ? 'selected' : '';
		$options     .= '<option value="' . $i . '" ' . $selectedOpt . ' >' . $i . '</option>';
	}

	return $options;
}

/* 
 * Generate events list in HTML format 
 */
function getEvents( $stu = '', $date = '', $type = '' ) {

	$time_zone = get_user_meta( get_current_user_id(), 'timezone', true );
	$dateTime  = new DateTime();
	$dateTime->setTimeZone( new DateTimeZone( '' . $time_zone . '' ) );
	$tz_dt = $dateTime->format( 'Y-m-d' );

	$date = $date ? $date : $tz_dt;
	$type = $type ? $type : 'nrm';
	$stu  = $stu ? $stu : 0;

	$time_zone = get_user_meta( get_current_user_id(), 'timezone', true );
	//date_default_timezone_set("".$time_zone."");

	$eventListHTML = '<h2 class="sidebar__heading">' . date( "l", strtotime( $tz_dt ) ) . '<br>' . date( "F d", strtotime( $tz_dt ) ) . '</h2>';

	// Fetch events based on the specific date
	global $wpdb;
	$cur_book_table    = $wpdb->prefix . "bookme_current_booking";
	$cus_book_table    = $wpdb->prefix . "bookme_customer_booking";
	$cus_bookref_table = $wpdb->prefix . "bookme_customer_booking_ref";
	$cus_table         = $wpdb->prefix . "bookme_customers";
	$cat_table         = $wpdb->prefix . "bookme_category";
	$ser_table         = $wpdb->prefix . "bookme_service";

	$user      = wp_get_current_user();
	$cus_email = $user->user_email;
	$cus_id    = $wpdb->get_var( $wpdb->prepare( " select id from $cus_table where email=%s ", $cus_email ) );

	if ( isset( $_GET['pid'] ) && isset( $_GET['pid'] ) ) {
		$ssid = $_GET['pid'] . "_" . $_GET['sid'];
	}

	if ( isset( $stu ) && $stu != 0 ) {
		$ssid = $stu;
	} else {
		$ssid = get_current_user_id() . "_1";
	}

	$result = $wpdb->get_results( "SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id inner join $cus_bookref_table as cusref_book on cusref_book.booking_id = cur_book.id where cusref_book.student_id='$ssid' and cur_book.date='$date' and cus_book.status='Approved' and cus_book.customer_id=$cus_id", ARRAY_A );

	/*else
	{
	    $result = $wpdb->get_results("SELECT cur_book.id,cur_book.cat_id,cur_book.ser_id,cur_book.emp_id,cur_book.date,cur_book.time,cus_book.customer_id,cus_book.no_of_person,cus_book.status FROM $cur_book_table as cur_book inner join $cus_book_table as cus_book on cus_book.booking_id = cur_book.id where cur_book.date='$date' and cus_book.status='Approved' and cus_book.customer_id=$cus_id",ARRAY_A); 
	}*/

	if ( count( $result ) > 0 ) {
		if ( count( $result ) == 1 ) {
			$eventListHTML .= '<h3>Session</h3>';
		} else {
			$eventListHTML .= '<h3>Sessions</h3>';
		}

		$eventListHTML .= '<ul class="sidebar__list">';
		$i             = 0;
		foreach ( $result as $row ) {
			$i ++;

			$student_datetime = get_student_timezone( $row['date'] . ' ' . $row['time'] );
			$time             = $student_datetime->format( 'g:i A' );

			$cat           = $wpdb->get_var( $wpdb->prepare( " select name from $cat_table where id=%d ", $row['cat_id'] ) );
			$ser           = $wpdb->get_var( $wpdb->prepare( " select name from $ser_table where id=%d ", $row['ser_id'] ) );
			$eventListHTML .= '<li class="sidebar__list-item"><!--<span class="list-item__time">' . $i . '.</span>-->' . $cat . ' - ' . $ser . ' (' . $time . ')</li>';
		}
		$eventListHTML .= '</ul>';
	}
	echo $eventListHTML;
	if ( $type == 'ajx' ) {
		wp_die();
	}
}


function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return site_url( 'wp-admin' );
		} else if ( in_array( 'lp_teacher', $user->roles ) ) {
			return site_url( '/wp-admin/admin.php?page=bookme-dashboard' );
		} else if ( in_array( 'subscriber', $user->roles ) ) {
			return site_url( 'my-courses' );
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


function tutor_grid_view() {

	global $wpdb;
	$emp_table = $wpdb->prefix . 'bookme_employee';
	$ser_table = $wpdb->prefix . 'bookme_service';
	$html      = '<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"><script>function check(id){
		if(id == 2)
		{
	    document.getElementById("emp2").style.display="block";
		}
		else
		{
	    document.getElementById("emp3").style.display="block";
		}
	}function close_check(id){
		if(id == 2)
		{
	    document.getElementById("emp2").style.display="none";
		}
		else
		{
	    document.getElementById("emp3").style.display="none";
		}
	}</script><div class="row">';

	$result = $wpdb->get_results( "SELECT * from $emp_table" );
	foreach ( $result as $emp ) {

		$emp_id = $emp->id;
		$html   .= '<div class="col-md-3" style="margin: 0.5% 0%; padding: 0px 5px!important"><div class="card" style="padding: 10px; border: 2px solid #38c5b6;border-radius: 25px; text-align: center"><img class="card-img-top" src="' . $emp->img . '" alt="Card image cap"><div class="card-body"><h3 style="font-weight: bold" class="card-title">' . $emp->name . '</h3><p class="card-text">' . $emp->info . '</p>';

		$html .= '<a onclick="return check(' . $emp_id . ')" class="w3-button w3-black">Details</a>';

		$html .= '<div id="emp' . $emp_id . '" class="w3-modal"><div class="w3-modal-content"><div style="padding: 10px" class="w3-container">';

		$html .= '<span onclick="return close_check(' . $emp_id . ')" class="w3-button w3-display-topright">&times;</span>';

		$html .= '<h3>Subjects They Tutor</h3>';

		$result_ser = $wpdb->get_results( "SELECT * from $ser_table where staff=$emp_id order by name,catId asc" );
		foreach ( $result_ser as $ser ) {
			//$serc[] = $ser->name;
			if ( strpos( $ser->name, '-' ) == true ) {
				//$serc[] = "<span class='cl-list'>".$ser->name."</span>";
			} else {
				$serc[] = "<span class='cl-list'>" . $ser->name . "</span>";
			}
		}
		$list = implode( " ", $serc );
		$html .= '<p>' . $list . '</p>';
		$html .= '</div></div></div>';
		$html .= '</div></div></div>';
	}
	$html .= '</div>';
	return $html;
}
add_shortcode( 'tutor_grid', 'tutor_grid_view' );
