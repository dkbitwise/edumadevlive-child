<?php
/**
 * Template for displaying price of course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/price.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
$class = ( $course->has_sale_price() ) ? ' has-origin' : '';
if ( $course->is_free() ) {
    $class .= ' free-course';
}
if ( is_user_logged_in() ){
global $wpdb;
	$customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_value' => get_current_user_id(),
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
    ));
    
 $Order_Array = []; //
    foreach ($customer_orders as $customer_order) {
        $orderq = wc_get_order($customer_order);
        $Order_Array[] = [
            "ID" => $orderq->get_id(),
            "Value" => $orderq->get_total(),
            "Date" => $orderq->get_date_created()->date_i18n('Y-m-d'),
        ];
        
        
        $order = new WC_Order( $orderq->get_id() );
$items = $order->get_items();
$mappingtable= $wpdb->prefix.'course_mapping';
foreach ( $items as $item ) {
     $product_name = $item['name'];
     $product_id = $item['product_id'];
  
   $ordercourses = $wpdb->get_results( $wpdb->prepare("SELECT course_id FROM $mappingtable WHERE `product_ids` LIKE '%$product_id%'") ); 
   $allcourses[]= ($ordercourses[0]->course_id);
}

    }
    }

?>

<!--<div class="course-price" test itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
    <?php if ( $price_html = $course->get_price_html() ) { ?>`
        <div class="value <?php echo $class; ?>" itemprop="price">
            <?php if ( $course->get_origin_price() != $course->get_price() ) { ?>
                <?php $origin_price_html = $course->get_origin_price_html(); ?>
                <span class="course-origin-price"><?php echo $origin_price_html; ?></span>
            <?php } ?>
            <?php echo $price_html; ?>
        </div>
        <meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency(); ?>" />
    <?php } ?>
</div>-->
<?php if ( is_user_logged_in() ){
if(in_array($course->get_id(),$allcourses)){ ?>
<div class="course-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
 <a href="<?php echo get_permalink($available_course->ID).'?enroll-course='.$course->get_id(); ?>" class="btn btn-md btn-default sp-btn pull-right ml">GET STARTED</a>
</div>
	
<?php }else{ ?>
<div class="course-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
 <a href="https://devlive.bitwise.academy/choose-package/?courseid=<?php echo $course->get_id();?>" class="btn btn-md btn-default sp-btn pull-right ml">ENROLL NOW</a>
</div>
<?php }
 }else{ ?>
<div class="course-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
 <a href="https://devlive.bitwise.academy/choose-package/?courseid=<?php echo $course->get_id();?>" class="btn btn-md btn-default sp-btn pull-right ml">ENROLL NOW</a>
</div>
<?php }?>
