<?php
/**
 * Template for displaying title of section in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = learn_press_get_current_user();
$course      = learn_press_get_the_course();
$user_course = $user->get_course_data( get_the_ID() );

/**
 * @var LP_Course_Section $section
 */
if ( ! isset( $section ) ) {
	return;
}

$title = $section->get_title();
?>

<?php if ( $title ) { ?>

<?php  if ( $user->has_enrolled_course( $section->get_course_id() ) ) {  // Code added by suresh for remove space in section title
?>
    <h4 class="section-header">
    	
    	<?php }else{?>
    	 <h4 class="section-header" style="margin: 0px 0 7px;">
    	<?php } ?>
        <span class="collapse"></span>
        <?php echo $title; ?>
       
    </h4>
    <?php if ( $description = $section->get_description() ) { ?>
        <p class="section-desc"><?php echo $description; ?></p>
    <?php } ?>
<?php } ?>

