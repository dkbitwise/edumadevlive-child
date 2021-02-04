<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpdocs_remove_menus() {
	remove_menu_page( 'js_composer' );    //Pages
}

add_action( 'admin_menu', 'wpdocs_remove_menus' );

function wpb_remove_screen_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	return true;
}

add_filter( 'screen_options_show_screen', 'wpb_remove_screen_options' );
add_action( 'admin_head', 'mytheme_remove_help_tabs' );
function mytheme_remove_help_tabs() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
function enqueue_styles() {
	wp_deregister_style( 'thim-style-options' );
	wp_register_style( 'thim-style-options', trailingslashit( get_stylesheet_directory_uri() ) . 'autoptimize.css', array(), 11 );
	//wp_register_style( 'thim-style-options', trailingslashit( get_template_directory_uri() ) . 'autoptimize.css', array(  ),11 );
	wp_enqueue_style( 'thim-style-options' );
}

add_action( 'wp_enqueue_scripts', 'enqueue_styles', 1111 );

if ( ! function_exists( 'chld_thm_cfg_parent_css' ) ):
	function chld_thm_cfg_parent_css() {
		//wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array(  ) );
		wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array() );
	}
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 1001 );

// END ENQUEUE PARENT ACTION
function renameToSubscribe( $content ) {
	//return "<a class='join' href='https://bitwise.academy/bitwise/register/'>Join Now</a> ";
	return '';
}

//To buy a course:
add_filter( "learn_press_course_price_html_free", 'renameToSubscribe' );

//To add joined after student count
function renameToJoined( $enrolled ) {
	return $enrolled . ' Joined';
}

add_filter( "learn_press_count_users_enrolled", 'renameToJoined' );

/* Lectures to  Lessons change */
if ( ! function_exists( 'thim_course_info' ) ) {
	function thim_course_info() {
		$course    = LP()->global['course'];
		$course_id = get_the_ID();

		$course_skill_level = get_post_meta( $course_id, 'thim_course_skill_level', true );
		$course_language    = get_post_meta( $course_id, 'thim_course_language', true );
		$course_duration    = get_post_meta( $course_id, 'thim_course_duration', true );

		$course_grade        = get_post_meta( $course_id, 'thim_course_grade', true );
		$course_prerequisite = get_post_meta( $course_id, 'thim_course_prerequisite', true ); ?>
        <div class="thim-course-info">
            <h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
            <ul>
                <li class="lectures-feature">
                    <i class="fa fa-files-o"></i>
                    <span class="label"><?php esc_html_e( 'Units', 'eduma' ); ?></span>
                    <span style="font-size: 12px;" class="value"><?php echo $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0; ?></span>
                </li>
                <li class="quizzes-feature">
                    <i class="fa fa-puzzle-piece"></i>
                    <span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
                    <span style="font-size: 12px;" class="value"><?php echo count( $course->get_curriculum_items( 'lp_lesson' ) ); ?></span>
                </li>

				<?php if ( ! empty( $course_duration ) ): ?>
                    <li class="duration-feature">
                        <i class="fa fa-clock-o"></i>
                        <span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
                        <span style="font-size: 12px;" class="value"><?php echo $course_duration; ?></span>
                    </li>
				<?php endif; ?>

				<?php if ( ! empty( $course_grade ) ): ?>
                    <li class="grade">
                        <i class="fa fa-trophy"></i>
                        <span class="label"><?php esc_html_e( 'Grade', 'eduma' ); ?></span>
                        <span style="font-size: 12px;" class="value"><?php echo esc_html( $course_grade ); ?></span>
                    </li>
				<?php endif; ?>
				<?php if ( ! empty( $course_skill_level ) ): ?>
                    <li class="skill-feature">
                        <i class="fa fa-level-up"></i>
                        <span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
                        <span style="font-size: 12px;" class="value"><?php echo esc_html( $course_skill_level ); ?></span>
                    </li>
				<?php endif;
				thim_course_certificate( $course_id ); ?>
                <li class="assessments-feature">
                    <i class="fa fa-sticky-note-o"></i>
                    <span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
                    <span style="font-size: 12px;" class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
                </li>
                <li class="assessments-feature">
                    <i class="fa fa-check-square-o"></i>
                    <span class="label"><?php esc_html_e( 'Homework', 'eduma' ); ?></span>
                    <span style="font-size: 12px;" class="value"><?php esc_html_e( 'Yes', 'eduma' ); ?></span>
                </li>
                <li class="assessments-feature">
                    <i class="fa fa-sticky-note-o"></i>
                    <span class="label"><?php esc_html_e( 'Mock Exam', 'eduma' ); ?></span>
                    <span style="font-size: 12px;" class="value"><?php esc_html_e( 'Yes', 'eduma' ); ?></span>
                </li>
				<?php if ( ! empty( $course_prerequisite ) ): ?>
                    <li style="border:none!important" class="skill-feature">
                        <i class="fa fa-level-up"></i>
                        <span class="label"><?php esc_html_e( 'Prerequisite', 'eduma' ); ?></span>
                        <span class="value" style="font-size: 12px; padding: 2px"><?php
							$myArray = explode( ',', $course_prerequisite );
							foreach ( $myArray as $my_Array ) {
								echo $my_Array . '<br>';
							}
							?></span>
                    </li>
				<?php endif; ?>
            </ul>
			<?php do_action( 'thim_after_course_info' ); ?>
        </div>
		<?php
	}
}

/* free to Subscribe Now*/
if ( ! function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses    = thim_get_related_courses( 5 );
		$theme_options_data = get_theme_mods();
		$style_content      = isset( $theme_options_data['thim_layout_content_page'] ) ? $theme_options_data['thim_layout_content_page'] : 'normal';

		if ( $related_courses ) {
			$layout_grid = get_theme_mod( 'thim_learnpress_cate_layout_grid', '' );
			$cls_layout  = ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) ? ' cls_courses_2' : ' '; ?>
            <div class="thim-ralated-course <?php echo $cls_layout; ?>">

				<?php if ( $style_content == 'new-1' ) { ?>
                    <div class="sc_heading clone_title  text-left">
                        <h2 class="title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h2>
                        <div class="clone"><?php esc_html_e( 'You May Like', 'eduma' ); ?></div>
                    </div>
				<?php } else { ?>
                    <h3 class="related-title">
						<?php esc_html_e( 'You May Like', 'eduma' ); ?>
                    </h3>
				<?php } ?>

                <div class="thim-course-grid">
                    <div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1" data-pagination="1">
						<?php foreach ( $related_courses as $course_item ) : ?>
							<?php
							$course      = learn_press_get_course( $course_item->ID );
							$is_required = $course->is_required_enroll();
							?>
                            <article class="lpr_course">
                                <div class="course-item">
                                    <div class="course-thumbnail">
                                        <a class="thumb" href="<?php echo get_the_permalink( $course_item->ID ); ?>">
											<?php
											if ( $layout_grid != '' && $layout_grid != 'layout_courses_1' ) {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 320, 220, get_the_title( $course_item->ID ) );
											} else {
												echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, get_the_title( $course_item->ID ) );
											}
											?>
                                        </a>
										<?php do_action( 'thim_inner_thumbnail_course' ); ?>
										<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course_item->ID ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
                                    </div>
                                    <div class="thim-course-content">
                                        <div class="course-author">
											<?php echo get_avatar( $course_item->post_author, 40 ); ?>
                                            <div class="author-contain">
                                                <div class="value">
                                                    <a href="<?php echo esc_url( learn_press_user_profile_link( $course_item->post_author ) ); ?>">
														<?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="course-title">
                                            <a rel="bookmark"
                                               href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
                                        </h2> <!-- .entry-header -->
                                        <div class="course-meta">
											<?php
											$count_student = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0;
											?>
                                            <div class="course-students">
                                                <label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
												<?php do_action( 'learn_press_begin_course_students' ); ?>

                                                <div class="value"><!--<i class="fa fa-group"></i>
                                                    <?php echo esc_html( $count_student ); ?>-->
                                                </div>
												<?php do_action( 'learn_press_end_course_students' ); ?>

                                            </div>
											<?php thim_course_ratings_count( $course_item->ID ); ?>
											<?php if ( $price = $course->get_price_html() ) {

												$origin_price = $course->get_origin_price_html();
												$sale_price   = $course->get_sale_price();
												$sale_price   = isset( $sale_price ) ? $sale_price : '';
												$class        = '';
												if ( $course->is_free() || ! $is_required ) {
													$class .= ' free-course';
													$price = esc_html__( 'Enroll Now', 'eduma' );
												} ?>
                                                <div class="course-price" itemprop="offers" itemscope
                                                     itemtype="http://schema.org/Offer">
                                                    <div class="value<?php echo $class; ?>" itemprop="price">
														<?php
														if ( $sale_price ) {
															echo '<span class="course-origin-price">' . $origin_price . '</span>';
														}
														?>
														<?php echo $price; ?>
                                                        <a href="https://devlive.bitwise.academy/choose-plan/?courseid=<?php echo $course->get_id(); ?>" class="btn btn-md btn-default sp-btn pull-right ml">JOIN
                                                            NOW</a>
                                                    </div>
                                                    <meta itemprop="priceCurrency"
                                                          content="<?php echo learn_press_get_currency(); ?>"/>
                                                </div>
												<?php
											}
											?>
                                        </div>
                                    </div>
                                </div>
                            </article>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
			<?php
		}
	}
}
add_filter( 'woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text_cart' );
function custom_woocommerce_product_add_to_cart_text_cart( $content ) {
	global $product;
	$product_in_cat = has_term( 'in-person-course', 'product_cat', $product->id );

	return ( $product_in_cat ) ? 'Join Now' : $content;
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text' );
function custom_woocommerce_product_add_to_cart_text( $content ) {
	global $product;
	$product_in_cat = has_term( 'in-person-course', 'product_cat', $product->id );

	return ( $product_in_cat ) ? "Join Now" : $content;
}

/**
 * Add a custom product data tab
 */
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
	global $product;
	$product_in_cat = has_term( 'in-person-course', 'product_cat', $product->id );

	if ( $product_in_cat ) {
		// Adds the new tab
		$tabs['curriculum_tab'] = array(
			'title'    => __( 'Curriculum', 'woocommerce' ),
			'priority' => 50,
			'callback' => 'woo_curriculum_product_tab_content'
		);
	}

	return $tabs;
}

function woo_curriculum_product_tab_content() {
	// The Curriculum tab content
	$c1          = "/product/introduction-to-data-science-and-statistics-live-course/";
	$currentpage = $_SERVER['REQUEST_URI'];

	if ( $c1 == $currentpage ) {
		echo '<h2>Curriculum</h2>';
		echo '<hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">1</span><strong style="color:#000;padding:0px 0px 0px 25px;">Exploring Statistics</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">2</span><strong style="color:#000;padding:0px 0px 0px 25px;">Research Design</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">3</span><strong style="color:#000;padding:0px 0px 0px 25px;">Probability</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">4</span><strong style="color:#000;padding:0px 0px 0px 25px;">Normal Distributions</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">5</span><strong style="color:#000;padding:0px 0px 0px 25px;">Sampling Distributions</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">6</span><strong style="color:#000;padding:0px 0px 0px 25px;">Estimation</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">7</span><strong style="color:#000;padding:0px 0px 0px 25px;">Tests of Significance</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">8</span><strong style="color:#000;padding:0px 0px 0px 25px;">Regression</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">9</span><strong style="color:#000;padding:0px 0px 0px 25px;">Introduction to Data Science</strong></p>

    <hr style="margin:-10px;"><br><p><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">10</span><strong style="color:#000;padding:0px 0px 0px 25px;">Introduction to Machine Learning</strong></p>';
	} else {
		echo '<h2>Curriculum</h2>';
		echo '  <div class="panel-group">
    <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse1"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">1</span><strong style="color:#000;padding:0px 0px 0px 25px;">Object-Oriented Program Design</strong></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse">
        <ul class="list-group" style="list-style: none;">
          <li class="list-group-item pd-lf-100" style="list-style: none;padding-left:100px;"><strong>1.1</strong> Program and class design</li>
        </ul>
      </div>
    </div>
        <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse2"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">2</span><strong style="color:#000;padding:0px 0px 0px 25px;">Program Implementation</strong></a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <ul class="list-group" style="list-style: none;">
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>2.1</strong> Implementation techniques</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>2.2</strong> Programming constructs</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>2.3</strong>  Java library classes and interfaces included in the AP Java Subset</li>
        </ul>
      </div>
    </div>
    <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse3"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">3</span><strong style="color:#000;padding:0px 0px 0px 25px;">Program Analysis</strong></a>
          
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <ul style="list-style: none;" class="list-group">
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.1</strong> Testing</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.2</strong> Debugging</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.3</strong> Runtime exceptions</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.4</strong> Program correctness</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.5</strong> Algorithm analysis</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>3.6</strong> Numerical representations of integers</li>
        </ul>
      </div>
    </div>
      <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse4"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">4</span><strong style="color:#000;padding:0px 0px 0px 25px;">Standard Data Structures</strong></a>
        </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse">
        <ul style="list-style: none;" class="list-group">
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>4.1</strong> Primitive data types (int, boolean, double)</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>4.2</strong> Strings</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>4.3</strong> Classes</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>4.4</strong> Lists</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>4.5</strong> Arrays (1-dimensional and 2-dimensional)</li>
        </ul>    
      </div>
    </div>
    <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse5"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">5</span><strong style="color:#000;padding:0px 0px 0px 25px;">Standard Operations and Algorithms</strong></a>
        </h4>
      </div>
      <div id="collapse5" class="panel-collapse collapse">
        <ul style="list-style: none;" class="list-group">
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>5.1</strong> Operations on data structures</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>5.2</strong> Searching</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>5.3</strong> Sorting</li>
        </ul>
      </div>
    </div>
      <div class="panel panel-default" style="margin: 0 0 0em;padding: 0;">
      <div class="panel-heading" style="color: #333;background-color: #FFF;border-color: #DDD;">
        <h4 class="panel-title">
          <hr style="margin:-10px;"><br><a  data-toggle="collapse" href="#collapse6"><span class="fa fa-file-o" style="color:#ffb606;"> </span><span style="font-size:10px;color:#999;padding:0px 5px 0px 10px;">Lesson</span><span style="font-size:13px;">6</span><strong style="color:#000;padding:0px 0px 0px 25px;">Computing in Context</strong></a>
        </h4>
      </div>
      <div id="collapse6" class="panel-collapse collapse">
        <ul style="list-style: none;" class="list-group">
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>6.1</strong> System reliability</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>6.2</strong> Privacy</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>6.3</strong> Legal issues and intellectual property</li>
          <li style="list-style: none;padding-left:100px;" class="list-group-item pd-lf-100"><strong>6.4</strong> Social and ethical ramifications of computer use</li>
        </ul>
      </div>
    </div>
  </div>';
	}
}

function woo_instructor_product_tab_content() {
	// The Instructor tab content
	$c1 = "/product/";
	$c2 = "/product/ap-computer-";

	$currentpage = $_SERVER['REQUEST_URI'];

	if ( $c1 == $currentpage ) {
		// echo '<h2>Instructor</h2>';
		echo '<p><strong style="color:#000;">G Venkat is the instructor for the AP Computer Science course.</strong>  He is a well-regarded Scientist, Researcher, and Corporate Executive with over 25 years of industry experience.  He has taught computer science to students of all ages.  Venkat has undertaken research on grants awarded from NASA and served as an advisor and consultant to Fortune 1000 companies and startups. Venkat’s book on Java Modernization was published by McGraw-Hill in 2017 with the foreword written by Thomas Kurian, President, Oracle.  Venkat is regularly invited to present at JavaOne and Oracle Open World.  Venkat holds a B.Tech. degree in Aerospace Engineering from The Indian Institute of Technology (IIT-M), Madras, India, MS and Ph.D. (ABD) in Interdisciplinary Studies (Computer Science and Aerospace) from The University of Alabama, Tuscaloosa.</p>';
	} elseif ( $c2 == $currentpage ) {
		// echo '<h2>Instructor</h2>';
		echo '<p><strong style="color:#000;">G Venkat is the instructor for the AP Computer Science course.</strong>  He is a well-regarded Scientist, Researcher, and Corporate Executive with over 25 years of industry experience.  He has taught computer science to students of all ages.  Venkat has undertaken research on grants awarded from NASA and served as an advisor and consultant to Fortune 1000 companies and startups. Venkat’s book on Java Modernization was published by McGraw-Hill in 2017 with the foreword written by Thomas Kurian, President, Oracle.  Venkat is regularly invited to present at JavaOne and Oracle Open World.  Venkat holds a B.Tech. degree in Aerospace Engineering from The Indian Institute of Technology (IIT-M), Madras, India, MS and Ph.D. (ABD) in Interdisciplinary Studies (Computer Science and Aerospace) from The University of Alabama, Tuscaloosa.</p>';
	} else {
		// echo '<h2>Instructor</h2>';
		echo '<p><strong style="color:#000;">G Venkat</strong> is a well-regarded Scientist, Researcher, Educator, and Corporate Executive with over 25 years of industry experience. He has taught computer science to students of all ages. Venkat has undertaken research on grants awarded from NASA and served as an advisor and consultant to Fortune 1000 companies and startups. Venkat’s book on Java Modernization was published by McGraw-Hill in 2017 with the foreword written by Thomas Kurian, CEO of Google Cloud and ex-President, Oracle. Venkat is regularly invited to present at JavaOne and Oracle Open World. Venkat holds a B.Tech. degree in Aerospace Engineering from The Indian Institute of Technology (IIT-M), Madras, India, MS and Ph.D. (ABD) in Interdisciplinary Studies (Computer Science and Aerospace) from The University of Alabama, Tuscaloosa.</p>';
	}
}

$c1          = "https://bitwiseacademy.com";
$currentpage = site_url();
if ( $c1 === $currentpage ) {
	add_action( 'wp_head', 'socialMedia_script' );
	function socialMedia_script() { ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-115410368-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', 'UA-115410368-1');
        </script>
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '500785053666077');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=500785053666077&ev=PageView&noscript=1"
            /></noscript>

        <!-- Pinterest Tag -->
        <script>
            !function (e) {
                if (!window.pintrk) {
                    window.pintrk = function () {
                        window.pintrk.queue.push(Array.prototype.slice.call(arguments))
                    };
                    var
                        n = window.pintrk;
                    n.queue = [], n.version = "3.0";
                    var
                        t = document.createElement("script");
                    t.async = !0, t.src = e;
                    var
                        r = document.getElementsByTagName("script")[0];
                    r.parentNode.insertBefore(t, r)
                }
            }("https://s.pinimg.com/ct/core.js");
            pintrk('load', '2619086023069', {em: '<user_email_address>'});
            pintrk('page');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none;" alt=""
                 src="https://ct.pinterest.com/v3/?event=init&tid=2619086023069&pd[em]=<hashed_email_address>&noscript=1"/>
        </noscript>
        <!-- end Pinterest Tag -->

		<?php
	}
}

add_filter( 'woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect', 9 );
function custom_add_to_cart_redirect() {
	// var_dump(print_r($content, true));exit;
	//Get product ID
	if ( isset( $_POST['add-to-cart'] ) ) {
		// var_dump(print_r($_POST['add-to-cart'], true));exit;
		$product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', $_POST['add-to-cart'] );

		//Check if product ID is in the proper taxonomy and return the URL to the redirect product
		// if ( has_term( 'in-person', 'course_category', $product_id ) )
		if ( has_term( 'in-person-course', 'product_cat', $product_id ) ) {
			return get_permalink( 9538 );
		}
	}

	global $product;
	if ( isset( $product->id ) ) {
		$product_in_cat = has_term( 'in-person-course', 'product_cat', $product->id );
	}
	if ( isset( $product_in_cat ) ) {
		return get_permalink( 9538 );
	}
}

// To enquque our custom javascripts
add_action( 'wp_enqueue_scripts', 'wdm_enqueue_scripts', 10, true );
function wdm_enqueue_scripts() {
	wp_enqueue_script( 'notify-me-script', get_stylesheet_directory_uri() . '/assets/js/notify-me-script.js' );
	$localize_data['action_function'] = "wdm_sendy_add_list_process";
	wp_localize_script( 'notify-me-script', "ajax_data", $localize_data );
}

add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_company'] );

	return $fields;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//Change Buy this course to join now
add_filter( 'learn-press/course-external-link-text', 'change_text' );
function change_text( $text ) {
	return 'Enroll Now';
}

//get value from multiform
add_action( 'msfp_register', 'form_values', 3 );
function form_values( $reg, $data, $id ) {
	error_log( var_dump( $id ) );
	error_log( var_dump( $data ) );
	error_log( var_dump( $reg ) );

	/* error_log(print_r($id, true));
	 error_log(print_r($data, true));
	 error_log(print_r($reg, true));*/
	exit();
}

//to lock down the user enumeration
if ( ! is_admin() ) {
// default URL format
	if ( preg_match( '/author=([0-9]*)/i', $_SERVER['QUERY_STRING'] ) ) {
		die();
	}
	add_filter( 'redirect_canonical', 'shapeSpace_check_enum', 10, 2 );
}

function shapeSpace_check_enum( $redirect, $request ) {
	// permalink URL format
	if ( preg_match( '/\?author=([0-9]*)(\/*)/i', $request ) ) {
		die();
	} else {
		return $redirect;
	}
}

//Block WordPress xmlrpc.php requests on function.php
add_filter( 'xmlrpc_enabled', '__return_false' );
function add_async_attribute( $tag, $handle ) {
	// add script handles to the array below
	$scripts_to_async = array( 'jquery-migrate.min.js', 'bootstrap-script' );

	foreach ( $scripts_to_async as $async_script ) {
		if ( $async_script === $handle ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}
	}

	return $tag;
}

add_filter( 'script_loader_tag', 'add_async_attribute', 10, 2 );

require_once 'devlive-functions.php';
require_once 'custom.php';

/**********************************************************************/
//Add country for  update the currency converter by suresh on 18-5-2020
/**********************************************************************/
add_action( 'wp_login', 'set_billing_country' );
function set_billing_country( $user_login ) {

	$user = get_user_by( 'login', $user_login );
	// Get the user object.
	$user = get_userdata( $user->ID );

	// Check if the role you're interested in, is present in the array.
	$location = WC_Geolocation::geolocate_ip();
	$cou      = $location['country'];

	$havemeta        = get_user_meta( $user->ID, 'billing_country', true );
	$customer_orders = get_posts( array(
		'numberposts' => - 1,
		'meta_key'    => '_customer_user',
		'meta_value'  => $user->ID,
		'post_type'   => 'shop_order', // WC orders post type
		'post_status' => 'wc-completed' // Only orders with status "completed"
	) );

	if ( $havemeta && count( $customer_orders ) > 0 ) { //if the user have order means do nothing

	} elseif ( $havemeta && count( $customer_orders ) == 0 ) { //if the user have no order means update the meta

		if ( $cou == 'IN' ) {
			update_user_meta( $user->ID, 'billing_country', $cou );
		} else {
			update_user_meta( $user->ID, 'billing_country', 'US' );
		}
	} else {
		if ( $cou == 'IN' ) {
			add_user_meta( $user->ID, 'billing_country', $cou );
		} else {
			add_user_meta( $user->ID, 'billing_country', 'US' );
		}
	}
}

add_action( 'wp', 'register_form' );
function register_form() {
	global $wpdb;
	$password      = 'Password@123';
	$student_table = $wpdb->prefix . 'bwlive_students';
	if ( isset( $_POST['formtype'] ) && $_POST['formtype'] == 'parent_register' ) {
		if ( isset( $_POST['user_login'] ) && isset( $_POST['parent_email'] ) && isset( $_POST['user_pass1'] ) && isset( $_POST['parent_phone'] ) && isset( $_POST['parent_l_name'] ) && isset( $_POST['parent_f_name'] ) && isset( $_POST['timezone'] ) && $_POST['timezone'] != 'NA' ) {
			$user_login = $_POST['user_login'];
			$user_email = $_POST['parent_email'];
			$user_pass  = $_POST['user_pass1'];
			$first_name = $_POST['parent_f_name'];
			$last_name  = $_POST['parent_l_name'];
			$mobile     = $_POST['parent_phone'];

			$new_user = wp_create_user( $user_login, $user_pass, $user_email );

			if ( is_wp_error( $new_user ) ) {
				$error = $new_user->get_error_message();
				echo $error;
			} else {
				$cuuser  = get_user_by( 'id', $new_user );
				$user_id = $new_user;

				update_user_meta( $user_id, 'first_name', $first_name );
				update_user_meta( $user_id, 'last_name', $last_name );
				add_user_meta( $user_id, 'parent_phone', $mobile );
				add_user_meta( $user_id, 'timezone', $_POST['timezone'] );
				add_user_meta( $user_id, 'country_code', $_POST['co_code'] );

				$cnt = count( $_POST['first_name'] );
				for ( $i = 0; $i < $cnt; $i ++ ) {
					$co      = $i + 1;
					$stud_id = $user_id . "_" . $co;
					if ( $_POST['first_name'][ $i ] != '' ) {
						$student_email = $_POST['user_email'][ $i ];
						$ins           = $wpdb->insert( $student_table, array(
							'parent_id'      => $user_id,
							'student_id'     => $stud_id,
							'student_fname'  => $_POST['first_name'][ $i ],
							'student_lname'  => $_POST['last_name'][ $i ],
							'student_mobile' => $_POST['phone'][ $i ],
							'student_email'  => $student_email,
							'student_slot'   => $_POST['timezone']
						) );
					}

					if ( username_exists( $student_email ) == null && email_exists( $student_email ) == false ) {
						$user_id = wp_create_user( $student_email, $password, $student_email );
						$user    = get_user_by( 'id', $user_id );
						$user->remove_role( 'subscriber' );
						$user->add_role( 'student' );
						update_user_meta( $user_id, 'first_name', $_POST['first_name'][ $i ] );
						update_user_meta( $user_id, 'last_name', $_POST['last_name'][ $i ] );

						$sql = "UPDATE $student_table SET `user_id`=$user_id WHERE `student_id`=$stud_id";
						$wpdb->query( $sql );
					}
				}

				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );
				wp_redirect( site_url( 'my-courses' ) );
				exit;
			}
		}
	} else if ( isset( $_POST['formtype'] ) && $_POST['formtype'] == 'parent_update' ) {
		if ( isset( $_POST['parent_id'] ) ) {
			$user_id = $_POST['parent_id'];

			update_user_meta( $user_id, 'first_name', $_POST['parent_f_name'] );
			update_user_meta( $user_id, 'last_name', $_POST['parent_l_name'] );
			update_user_meta( $user_id, 'parent_phone', $_POST['parent_phone'] );
			update_user_meta( $user_id, 'country_code', $_POST['co_code'] );
			update_user_meta( $user_id, 'timezone', $_POST['timezone'] );
			$stud_id = $user_id . "_1";

			$student_email = $_POST['user_email'];
			$student_fname = $_POST['first_name'];
			$student_lname = $_POST['last_name'];

			$upd = $wpdb->update( $student_table, array(
				'student_id'     => $stud_id,
				'student_fname'  => $student_fname,
				'student_lname'  => $student_lname,
				'student_mobile' => $_POST['phone'],
				'student_email'  => $student_email,
				'student_slot'   => $_POST['timezone']
			), array( 'parent_id' => $user_id ) );


			if ( username_exists( $student_email ) == null && email_exists( $student_email ) == false ) {
				$user_id = wp_create_user( $student_email, $password, $student_email );
				$user    = get_user_by( 'id', $user_id );
				$user->remove_role( 'subscriber' );
				$user->add_role( 'student' );
				update_user_meta( $user_id, 'first_name', $student_fname );
				update_user_meta( $user_id, 'last_name', $student_lname );

				$sql = "UPDATE $student_table SET `user_id`=$user_id WHERE `student_id`=$stud_id";
				$wpdb->query( $sql );
			}

		}
	}
}

add_action( 'wp_logout', 'redirect_after_logout' );
function redirect_after_logout() {
	wp_redirect( 'login' );
	exit();
}

function auto_login_new_user( $user_id ) {
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id );
	wp_redirect( site_url( 'my-courses' ) );
	exit;
}

add_action( 'load-profile.php', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		exit( wp_safe_redirect( admin_url( 'my-profile' ) ) );
	}
} );

add_filter( 'learn-press/profile-tabs', 'reptro_learn_press_profile_tabs' );
function reptro_learn_press_profile_tabs( $tabs ) {
	if ( function_exists( 'LP' ) ) {
		$settings = LP()->settings;
		$profile  = LP_Profile::instance();

		unset( $tabs['courses'] );
		unset( $tabs['gradebook'] );
		unset( $tabs['orders'] );
		unset( $tabs['quizzes'] );
		unset( $tabs['wishlist'] );
		unset( $tabs['assignments'] );
	}

	return $tabs;
}

/**********************************************/
//Add country code during new user registration
/**********************************************/
add_action( 'user_register', 'billing_country_update' );
function billing_country_update( $user_login ) {
	$location = WC_Geolocation::geolocate_ip();
	$cou      = $location['country'];

	$user = get_userdata( $user_login );
	if ( in_array( 'subscriber', $user->roles ) || in_array( 'lp_teacher', $user->roles ) ) {
		if ( $cou == 'IN' ) {
			add_user_meta( $user_login, 'billing_country', $cou );
		} else {
			add_user_meta( $user_login, 'billing_country', 'US' );
		}
	}
}

add_filter( 'wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3 );
function custom_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
	$key        = get_password_reset_key( $user );
	$user_login = stripslashes( $user->user_login );
	$user_email = stripslashes( $user->user_email );
	$login_url  = wp_login_url();
	$message    = sprintf( __( "Welcome to %s!" ), get_option( 'blogname' ) ) . "\r\n\r\n";
	$message    .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n";
	$message    .= sprintf( __( 'Email: %s' ), $user_email ) . "\r\n\n";
	$message    .= sprintf( __( 'To set your password, visit the following address:' ) ) . "\r\n\n";
	$message    .= '<a href=' . site_url( "wp-login.php?action=rp&key=$key&login=" . $user_login, 'login' ) . ">Set Password</a>\r\n\r\n";

	$message .= '<a href=' . site_url( "login" ) . ">Login</a>\r\n\r\n";
	$message .= sprintf( __( 'If you have already set your own password, you may disregard this email and use the password you have already set.' ) ) . "\r\n\n";

	$title                                     = $wp_new_user_notification_email['subject'] = sprintf( '[%s] Login Details', $blogname );
	$wp_new_user_notification_email['message'] = $message;
	wdm_mail_new( $user_email, $title, $message );

}

add_filter( 'wp_new_user_notification_email_admin', 'custom_wp_new_user_notification_admin_email', 10, 3 );

function custom_wp_new_user_notification_admin_email( $wp_new_user_notification_email, $user, $blogname ) {

	$blogname   = get_option( 'blogname' );
	$user_login = stripslashes( $user->user_login );
	$user_email = stripslashes( $user->user_email );
	$login_url  = wp_login_url();
	$message    = sprintf( "%s ( %s ) has registerd to your blog %s.", $user->user_login, $user->user_email, $blogname );

	$title                                     = $wp_new_user_notification_email['subject'] = sprintf( '[%s] New User Registration', $blogname );
	$wp_new_user_notification_email['message'] = $message;
	$admin_email                               = get_option( 'admin_email' );

	wdm_mail_new( $admin_email, $title, $message );

}

add_action( 'wp_footer', 'disable_btn' );
function disable_btn() {
	?>
    <script>

        jQuery(document).ready(function ($) {

            if (localStorage.getItem("timezone") === null || localStorage.getItem("timezone") === "") {
                localStorage.setItem("timezone", "<?php echo get_user_meta( get_current_user_id(), 'timezone', true ) ?>");
            }
        });
    </script>
	<?php
}

add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );

	if ( $order->has_status( 'processing' ) ) {
		$order->update_status( 'completed' );
	}
}

add_filter( 'woocommerce_form_field', 'bbloomer_checkout_fields_in_label_error', 10, 4 );

function bbloomer_checkout_fields_in_label_error( $field, $key, $args, $value ) {
	if ( strpos( $field, '</label>' ) !== false && $args['required'] ) {
		$error = '<span class="error" style="display:none">';
		$error .= sprintf( __( '%s is a required field.', 'woocommerce' ), $args['label'] );
		$error .= '</span>';
		$field = substr_replace( $field, $error, strpos( $field, '</label>' ), 0 );
	}

	return $field;
}

function deregister_woocommerce_assets() {
	// array of script handlers that we can disable so that we can
	// build them with grunt manually
	$scripts_to_remove = array(
		'wc-address-i18n',
	);

	foreach ( $scripts_to_remove as $script ) {
		wp_deregister_script( $script );
		wp_register_script( $script, null, array( 'jquery' ), null, true );
	}
}

add_action( 'wp_print_scripts', 'deregister_woocommerce_assets', 100 );

function divi_engine_remove_required_fields_checkout( $fields ) {
	$fields['billing_first_name']['required'] = true;
	$fields['billing_last_name']['required']  = true;
	$fields['billing_phone']['required']      = true;
	$fields['billing_email']['required']      = true;
	$fields['billing_country']['required']    = true;
	$fields['billing_state']['required']      = true;
	$fields['billing_city']['required']       = true;
	$fields['billing_postcode']['required']   = true;
	$fields['billing_state']['label']         = 'State';

	return $fields;
}

add_filter( 'woocommerce_billing_fields', 'divi_engine_remove_required_fields_checkout' );

// Removes Order Notes Title - Additional Information & Notes Field
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

// Remove Order Notes Field
add_filter( 'woocommerce_checkout_fields', 'remove_order_notes' );

function remove_order_notes( $fields ) {
	unset( $fields['order']['order_comments'] );

	return $fields;
}

add_shortcode( 'woo_cart_but', 'woo_cart_but' );
/**
 * Create Shortcode for WooCommerce Cart Menu Item
 */
function woo_cart_but() {
	ob_start();

	//$cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
	$cart_count = count( WC()->cart->get_cart() );
	$cart_url   = wc_get_cart_url();  // Set Cart URL

	?>
    <li><a style="padding: 10px 20px 5px!important" class="menu-item cart-contents" href="<?php echo $cart_url; ?>" title="<?php _e( 'View your shopping cart' ); ?>">
			<?php
			if ( $cart_count > 0 ) {
				?>
                <span class="cart-contents-count"><?php echo $cart_count; ?></span>
				<?php
			}
			?>
        </a></li>
	<?php

	return ob_get_clean();

}

add_filter( 'woocommerce_add_to_cart_fragments', 'woo_cart_but_count' );
/**
 * Add AJAX Shortcode when cart contents update
 */
function woo_cart_but_count( $fragments ) {
	ob_start();

	//$cart_count = WC()->cart->cart_contents_count;
	$cart_count = count( WC()->cart->get_cart() );
	$cart_url   = wc_get_cart_url(); ?>
    <a style="padding: 10px 20px 5px!important" class="cart-contents menu-item" href="<?php echo $cart_url; ?>" title="<?php _e( 'View your shopping cart' ); ?>">
		<?php
		if ( $cart_count > 0 ) { ?>
            <span class="cart-contents-count"><?php echo $cart_count; ?></span>
			<?php
		}
		?></a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}

/*Added by Vignesh R on 29-10-2020 to modify existing password reset mail template*/
add_filter( 'retrieve_password_message', 'bw_retrieve_password_message', 10, 4 );
function bw_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$user_email = stripslashes( $user_data->user_email );
	$fname      = stripslashes( $user_data->first_name );
	$lname      = stripslashes( $user_data->last_name );
	$name       = $fname . " " . $lname;

	// Start with the default content.
	$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	$message   = sprintf( __( 'Dear %s' ), $name ) . "\r\n\r\n";
	$message   .= sprintf( __( 'Someone requested a password reset for the user %s' ), $user_login ) . "\r\n\r\n";
	$message   .= __( 'If you want to reset your password, click the link below.' ) . "\r\n";
	$message   .= '<a href=' . site_url( "wp-login.php?action=rp&key=$key&login=" . $user_login, 'login' ) . ">Reset Password</a>\r\n\r\n";
	$message   .= __( 'If you did not request a password reset, please ignore the reset instructions and forward this email to support@bitwiseacademy.com' ) . "\r\n\r\n";

	$title                                     = $wp_new_user_notification_email['subject'] = sprintf( 'Password Reset' );
	$wp_new_user_notification_email['message'] = $message;
	wdm_mail_new( $user_email, $title, $message );
}

if ( is_user_logged_in() ) {
	$user = wp_get_current_user();
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {

		/*If subscriber*/
		if ( in_array( 'subscriber', $user->roles ) ) {
			if ( ! isset( $_SESSION['ref'] ) ) {
				$_SESSION['ref'] = '/live-courses/';
			}

			if ( ! isset( $_SESSION['tzone'] ) ) {
				$tz       = get_user_meta( get_current_user_id(), 'timezone', true );
				$dateTime = new DateTime();
				$dateTime->setTimeZone( new DateTimeZone( '' . $tz . '' ) );
				$tz_ab             = $dateTime->format( 'T' );
				$_SESSION['tzone'] = $tz_ab;
			}
		}
	}
}

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'custom_empty_cart_message', 10 );

function custom_empty_cart_message() {
	$html = '<div class="col-12 col-md-12"><div style="margin-bottom: 10px" class="btn-group pull-right" role="group">
  <a href="/live-courses/" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Back To Courses</a>
  <a href="' . $_SESSION['ref'] . '" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Back To Packages</a>
</div><br><br><p class="cart-empty">';
	$html .= wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Cart has no packages.', 'woocommerce' ) ) );
	echo $html . '</p></div>';
}

function custom_woocommerce_my_account_my_orders_columns( $columns ) {
	//custom code here
	$columns['order-number'] = 'Order No.';
	$columns['order-date']   = 'Order Date';

	return $columns;
}

//add the action
add_filter( 'woocommerce_my_account_my_orders_columns', 'custom_woocommerce_my_account_my_orders_columns', 10, 1 );

add_action( 'woocommerce_after_order_itemmeta', 'order_meta_customized_display', 10, 3 );
function order_meta_customized_display( $item_id, $item, $product ) {
	$all_meta_data = get_metadata( 'order_item', $item_id, "", "" );
	$useless       = array(
		"_qty",
		"_tax_class",
		"_variation_id",
		"_product_id",
		"_line_subtotal",
		"_line_total",
		"_line_subtotal_tax",
		"bookme"
	);// Add key values that you want to ignore

	$customized_array = array();
	foreach ( $all_meta_data as $data_meta_key => $value ) {
		if ( ! in_array( $data_meta_key, $useless ) ) {
			$newKey                      = ucwords( str_replace( '_', " ", $data_meta_key ) );//To remove underscrore and capitalize
			$customized_array[ $newKey ] = ucwords( str_replace( '_', " ", $value[0] ) ); // Pushing each value to the new array
		}
	}
	if ( ! empty( $customized_array ) ) { ?>
        <div class="order_data_column" style="float: left; width: 50%; padding: 0 5px;">
			<?php

			$bookme = wc_get_order_item_meta( $item_id, 'bookme', true );
			global $wpdb;
			$tb = $wpdb->prefix . 'bookme_current_booking';
			foreach ( $bookme['booking_id'] as $bid ) {
				$ids[] = $bid;
			}

			$bids        = implode( ",", $ids );
			$sql_stu     = "SELECT date,time FROM $tb where id in (" . $bids . ")";
			$results_stu = $wpdb->get_results( $sql_stu );

			foreach ( $results_stu as $stu ) {
				$dates[]    = $stu->date;
				$start_time = $stu->time;
			}
			$end_time = strtotime( $start_time ) + 4500;
			$dateTime = new DateTime();
			$dateTime->setTimeZone( new DateTimeZone( 'UTC' ) );
			$dateTime->setTimestamp( $end_time );
			$end_time = $dateTime->format( 'g:i A' );

			echo "<br>";
			echo "<b>Session Details (";
			echo "UTC";
			echo "):</b>";
			echo "<br>";
			echo "Time: ";
			echo strtoupper( $start_time );
			echo " - ";
			echo $end_time;
			echo "<br>";
			echo "Dates (";
			echo date( "D", strtotime( $bookme['dates'] ) );
			echo "): ";

			$ijk            = 0;
			$totalmonth_utc = 0;
			$newdatenew_utc = "";
			foreach ( $dates as $m_dates ) {
				if ( isset( $dates[ $ijk ] ) ) {

					if ( $ijk == 0 ) {
						$newdatenew_utc .= "<br><span><b>" . date( 'M', strtotime( $dates[0] ) ) . " " . date( 'Y', strtotime( $dates[0] ) ) . "</b>";
					}

					if ( $ijk != 0 ) {
						$newdate_utc = date( 'M', strtotime( $dates[ $ijk ] ) );
						$olddate_utc = date( 'M', strtotime( $dates[ $ijk - 1 ] ) );

						if ( $newdate_utc != $olddate_utc ) {
							$newdatenew_utc .= "<b><span>" . $newdate_utc . " " . date( 'Y', strtotime( $dates[ $ijk ] ) ) . "</span></b>";
						}
					}

					if ( $ijk == 0 ) {
						$newdatenew_utc .= " (" . date( 'd', strtotime( $dates[ $ijk ] ) ) . ",";
					} else {
						$newdate_utc = date( 'M', strtotime( $dates[ $ijk ] ) );
						$olddate_utc = date( 'M', strtotime( $dates[ $ijk - 1 ] ) );

						if ( $newdate_utc != $olddate_utc ) {
							$newdatenew_utc .= " (" . date( 'd', strtotime( $dates[ $ijk ] ) ) . ",";
						} else {
							$newdatenew_utc .= "" . date( 'd', strtotime( $dates[ $ijk ] ) ) . ",";
						}
					}

					++ $ijk;

					if ( $ijk != 0 ) {

						$newdate_utc = date( 'M', strtotime( $dates[ $ijk ] ) );
						$olddate_utc = date( 'M', strtotime( $dates[ $ijk - 1 ] ) );

						if ( $newdate_utc != $olddate_utc ) {
							$newdatenew_utc .= " )</span><br>";
						}
					}
				}
			}

			$newd_utc = str_replace( ", )", ")", $newdatenew_utc );
			echo $newd_utc;

			$pr       = explode( "_", $bookme['student'] );
			$tz       = get_user_meta( $pr[0], 'timezone', true );
			$dateTime = new DateTime();
			$dateTime->setTimeZone( new DateTimeZone( '' . $tz . '' ) );
			$tz_ab = $dateTime->format( 'T' );

			echo "<br>";
			echo "<b>Session Details (";
			echo $tz_ab;
			echo "):</b>";
			echo "<br>";
			echo "Time: ";
			echo $bookme['appointstart'];
			echo " - ";
			echo $bookme['appointend'];
			echo "<br>";
			echo "Dates (";
			echo date( "D", strtotime( $bookme['dates'] ) );
			echo "): ";
			$ij         = 0;
			$totalmonth = 0;
			$newdatenew = "";
			foreach ( $bookme['month_date'] as $m_date ) {
				if ( isset( $bookme['month_date'][ $ij ] ) ) {

					if ( $ij == 0 ) {
						$newdatenew .= "<br><span><b>" . date( 'M', strtotime( $bookme['month_date'][0] ) ) . " " . date( 'Y', strtotime( $bookme['month_date'][0] ) ) . "</b>";
					}

					if ( $ij != 0 ) {
						$newdate = date( 'M', strtotime( $bookme['month_date'][ $ij ] ) );
						$olddate = date( 'M', strtotime( $bookme['month_date'][ $ij - 1 ] ) );

						if ( $newdate != $olddate ) {
							$newdatenew .= "<b><span>" . $newdate . " " . date( 'Y', strtotime( $bookme['month_date'][ $ij ] ) ) . "</span></b>";
						}
					}

					if ( $ij == 0 ) {
						$newdatenew .= " (" . date( 'd', strtotime( $bookme['month_date'][ $ij ] ) ) . ",";
					} else {
						$newdate = date( 'M', strtotime( $bookme['month_date'][ $ij ] ) );
						$olddate = date( 'M', strtotime( $bookme['month_date'][ $ij - 1 ] ) );

						if ( $newdate != $olddate ) {
							$newdatenew .= " (" . date( 'd', strtotime( $bookme['month_date'][ $ij ] ) ) . ",";
						} else {
							$newdatenew .= "" . date( 'd', strtotime( $bookme['month_date'][ $ij ] ) ) . ",";
						}
					}

					++ $ij;

					if ( $ij != 0 ) {

						$newdate = date( 'M', strtotime( $bookme['month_date'][ $ij ] ) );
						$olddate = date( 'M', strtotime( $bookme['month_date'][ $ij - 1 ] ) );

						if ( $newdate != $olddate ) {
							$newdatenew .= " )</span><br>";
						}
					}
				}
			}

			$newd = str_replace( ", )", ")", $newdatenew );
			echo $newd;

			$dateTime_in = new DateTime();
			$dateTime_in->setTimeZone( new DateTimeZone( 'Asia/Kolkata' ) );
			$tz_ab_in = $dateTime_in->format( 'T' );

			foreach ( $results_stu as $stu ) {
				$start_date_in = $stu->date;
				$start_time_in = $stu->time;

				$dtime = new DateTime( '' . $start_date_in . ' ' . $start_time_in . '', new DateTimeZone( 'UTC' ) );
				$dtime->setTimezone( new DateTimeZone( 'Asia/Kolkata' ) );

				$end_date_ind   = $dtime->format( 'Y-m-d' );
				$start_time_ind = $dtime->format( 'g:i A' );

				$dates_in[] = $end_date_ind;
			}

			$end_time_ind = strtotime( $start_time_in ) + 4500;
			//$dtime->setTimestamp($end_time_ind);
			//$end_time_ind = $dtime->format('g:i A');

			$dateTimei = new DateTime();
			$dateTimei->setTimeZone( new DateTimeZone( 'Asia/Kolkata' ) );
			$dateTimei->setTimestamp( $end_time_ind );
			$end_time_ind = $dateTimei->format( 'g:i A' );

			echo "<br>";
			echo "<b>Session Details (";
			echo $tz_ab_in;
			echo "):</b>";
			echo "<br>";
			echo "Time: ";
			echo $start_time_ind;
			echo " - ";
			echo $end_time_ind;
			echo "<br>";
			echo "Dates (";
			echo date( "D", strtotime( $end_date_ind ) );
			echo "): ";

			$ijk            = 0;
			$totalmonth_ind = 0;
			$newdatenew_ind = "";
			foreach ( $dates_in as $m_dates ) {
				if ( isset( $dates_in[ $ijk ] ) ) {

					if ( $ijk == 0 ) {
						$newdatenew_ind .= "<br><span><b>" . date( 'M', strtotime( $dates_in[0] ) ) . " " . date( 'Y', strtotime( $dates_in[0] ) ) . "</b>";
					}

					if ( $ijk != 0 ) {

						$newdate_ind = date( 'M', strtotime( $dates_in[ $ijk ] ) );
						$olddate_ind = date( 'M', strtotime( $dates_in[ $ijk - 1 ] ) );

						if ( $newdate_ind != $olddate_ind ) {
							$newdatenew_ind .= "<b><span>" . $newdate . " " . date( 'Y', strtotime( $dates_in[ $ijk ] ) ) . "</span></b>";
						}
					}

					if ( $ijk == 0 ) {
						$newdatenew_ind .= " (" . date( 'd', strtotime( $dates_in[ $ijk ] ) ) . ",";
					} else {
						$newdate_ind = date( 'M', strtotime( $dates_in[ $ijk ] ) );
						$olddate_ind = date( 'M', strtotime( $dates_in[ $ijk - 1 ] ) );

						if ( $newdate_ind != $olddate_ind ) {
							$newdatenew_ind .= " (" . date( 'd', strtotime( $dates_in[ $ijk ] ) ) . ",";
						} else {
							$newdatenew_ind .= "" . date( 'd', strtotime( $dates_in[ $ijk ] ) ) . ",";
						}
					}

					++ $ijk;

					if ( $ijk != 0 ) {
						$newdate_ind = date( 'M', strtotime( $dates_in[ $ijk ] ) );
						$olddate_ind = date( 'M', strtotime( $dates_in[ $ijk - 1 ] ) );
						if ( $newdate_ind != $olddate_ind ) {
							$newdatenew_ind .= " )</span><br>";
						}
					}
				}
			}

			$newd_ind = str_replace( ", )", ")", $newdatenew_ind );
			echo $newd_ind; ?>
        </div>
	<?php }
}

add_filter( 'woocommerce_email_headers', 'new_order_reply_to_admin_header', 20, 3 );

function new_order_reply_to_admin_header( $headers = '', $id = '', $order ) {
	$wc_email = new WC_Email();
	$headers  = 'Content-Type: ' . $wc_email->get_content_type() . "\r\n";

	return $headers;
}

add_filter( 'woocommerce_countries', 'change_country_order_in_checkout_form' );
function change_country_order_in_checkout_form( $countries ) {
	$usa = $countries['US']; // Store the data for "US" key
	unset( $countries["US"] ); // Remove "US" entry from the array

	// Return "US" first in the countries array
	return array( 'US' => $usa );
	//return array('US' => $usa ) + $countries;
}