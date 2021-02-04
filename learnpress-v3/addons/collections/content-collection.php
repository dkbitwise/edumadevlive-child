<?php
/**
 * Template for displaying collection content within the loop
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$class = ' collection-item col-xs-4'
?>
<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>

	<?php do_action( 'learn_press_collections_before_loop_item' ); ?>

	<div class="item">
		<div style="margin-bottom: 45px" class="thumbnail">
			<?php
			echo '<a href="' . esc_url( get_the_permalink() ) . '" >'; 
			echo thim_get_feature_image( get_post_thumbnail_id( get_the_ID() ), 'full', apply_filters( 'thim_collection_item_thumbnail_width', 450 ), apply_filters('thim_collection_item_thumbnail_height', 230), get_the_title() );
			echo '</a>';
			?>
			<a style="/*position: absolute; top: 30%; color: #333; line-height: 20px; padding: 5px 25px; z-index: 90; left: 37%; right: auto; margin: auto; width: auto; font-size: 13px; font-weight: 700; text-transform: uppercase; margin-left: 0px!important*/" class="collection-readmore btn btn-default" href="<?php echo esc_url( get_the_permalink() ) ?>">Read More</a>
			<a class="title" style="font-size: 19px!important; background: #fff!important; border-left: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5; font-weight: 100!important; text-decoration: none!important" href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
		</div>
	</div>
<p style="height: 45px; padding: 5px; border-left: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5"><i class="fa fa-group"></i>  <?php $no = get_post_meta( get_the_ID(), '_lp_student_count' ); echo $no[0] ?><a href="https://staging.bitwise.academy/register" class="btn btn-default sp-btn pull-right">Join Now</a></p>

	<?php do_action( 'learn_press_collections_after_loop_item' ); ?>

</div>
