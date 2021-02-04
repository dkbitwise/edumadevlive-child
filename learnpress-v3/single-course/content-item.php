<?php
/**
 * Template for displaying item content in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/content-item.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.9
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = LP_Global::user();
$course_item = LP_Global::course_item();
$course      = LP_Global::course();
$can_view_item  = $user->can_view_item( $course_item->get_id(), $course->get_id() );
?>

<div id="learn-press-content-item">

	<?php do_action( 'learn-press/course-item-content-header' ); ?>

    <div class="content-item-scrollable">

        <div class="content-item-wrap">

			<?php
			/**
			 * @deprecated
			 */
			do_action( 'learn-press/before_course_item_content' );

			/**
			 * @since 3.0.0
			 *
			 */
			do_action( 'learn-press/before-course-item-content' );

            if ( $can_view_item ) {
				/**
				 * @deprecated
				 */
				do_action( 'learn_press_course_item_content' );

				/**
				 * @since 3.0.0
				 */
				do_action( 'learn-press/course-item-content' );
				
				
			
			//The following code added by suresh for sidebar materials
			
					wp_enqueue_script( 'bitwise_sidebar_content_public_js', plugins_url() . '/bitwise-sidebar-content/public/js/bitwise-sidebar-content-public.js', array( 'jquery' ), '1.0.0', false );
					wp_enqueue_script( 'bitwise_lightbox_html5', plugins_url() . '/bitwise-sidebar-content/public/html5lightbox/html5lightbox.js', array(), '1.0.0', false );
			
				global $wpdb;
                    	$contenttable= $wpdb->prefix.'bitscr_content';

						$contentvideolist = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $contenttable  where sfwd_course_id=%s and type='Video'",$course->get_id()) );
						$contendocumenttlist = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $contenttable  where sfwd_course_id=%s and type='Help'",$course->get_id()) );
						
				?>
				 <div class="bit_info_btn btn121" data-toggle="tooltip" data-placement="left">
        		 <div style="top: 30%!important;" class="content_btns">
                         <a href="https://devtools.bitwise.academy/?authentication=5e56173e17c85" /*href="javascript:void(0);"*/ style="background:#138075!important" class="bttn hh_btn html5lightbox" data-tab="videos">HOMEWORK HELPER</a>
    			 <a href="javascript:void(0);" class="bttn help_btn" data-tab="videos">STUDY MATERIALS</a>
    			 </div>
    			</div>
    			
    			<div class="info_wrapp">
        <div class="thumbnail">
            <a href="javascript:void(0);" class="com_close close bt_box_shadow info_close"><i class="fa fa-times" aria-hidden="true"></i></a>
              <ul class="nav nav-pills" style="margin-left:0px;">
                <li class="active"><a class="nav_link" data-toggle="pill" href="#videos">VIDEOS</a></li>
                <li><a class="nav_link" data-toggle="pill" href="#help">DOCUMENTS</a></li> 
               
            </ul>
            <div class="tab-content">
                 <div id="videos" class="tab-pane fade active in">
                    
                    <p><small style="color:#4e9a06;"><strong><?php echo count($contentvideolist);?></strong> MATERIAL(S) CURRENTLY AVAILABLE</small></p>
                    <ul class="sidebar_list" style="margin-left:0px;">
                    	<?php 
                    
                  		foreach ($contentvideolist as $video ) {
							 ?>
                            <li>
                                <a data-group="video_content" class="html5lightbox" href="<?php echo $video->content_url; ?>" title="<?php echo $video->name; ?>">
                                    <h3><?php echo $video->name; ?></h3>
                                </a>
                            </li>
							<?php
						} ?>
	                   </ul>
                </div>
                    <div id="help" class="tab-pane fade">
                    
                    <p><small style="color:#4e9a06;"><strong><?php echo count( $contendocumenttlist ); ?></strong> HELPS CURRENTLY AVAILABLE</small></p>
                    <ul class="sidebar_list" style="margin-left:0px;">
						<?php $hcount =0;
						foreach ( $contendocumenttlist as $help ) {
						    $hcount++; ?>
                            <li>
                            	<?php 
                            	$content = $help->content_url;
                            	if ( has_shortcode( $content, 'real3dflipbook' ) ) {
								?>
								<a  class="html5lightbox" href="#pdfviewer<?php echo  $help->id; ?>">
								<?php
                            	}
                            	else
                            	{
                            	?>
                                <a title="<?php echo $help->name; ?>" data-group="help_content" class="example-image-link html5lightbox" href="<?php echo $help->content_url ?>">
                                <?php
                            	}
                            	?>
                                    <h3><?php echo  $help->name; ?></h3>
                                </a>
                            </li>
                             	<div id="pdfviewer<?php echo  $help->id	; ?>" style="display:none;">
					
					
				
				<div class="lightboxcontainer">
				
				<h3 class="notestitle"> <?php echo  $help->name; ?></h3>
				<div class="notescontent"><?php echo do_shortcode( $help->content_url ); ?>
				</div>
				</div>
					</div>
                            <?php
						} ?>
                    </ul>
                </div>
               
               
							
			</div>
            
        </div>
</div>
				<?php
				
				//Suresh code End

			} else {
				learn_press_get_template( 'single-course/content-protected.php', array( 'can_view_item'=>$can_view_item ) );
			}

			/**
			 * @since 3.0.0
			 */
			do_action( 'learn-press/after-course-item-content' );

			/**
			 * @deprecated
			 */
			do_action( 'learn_press_after_content_item' );
			?>

        </div>

    </div>

	<?php do_action( 'learn-press/course-item-content-footer' ); ?>

</div>
