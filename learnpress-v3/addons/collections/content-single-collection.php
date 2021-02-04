<?php
/**
 * Template for displaying single collection content
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php the_content(); ?>
<?php

global $wp;
$curl = home_url( $wp->request )

?>
<br>
<div class="thim-widget-social"><div class="thim-social">
        <h3 class="widget-title">Share:</h3>    <ul class="social_link">
                <li><a class="facebook hasTooltip" href="https://www.facebook.com/sharer.php?u=<?php echo $curl ?>" target="_self"><i class="fa fa-facebook"></i>Facebook</a></li><li>
<a class="twitter hasTooltip" href="https://www.twitter.com/share?url=<?php echo $curl ?>" target="_self"><i class="fa fa-twitter"></i>Twitter</a></li>
<li><a class="linkedin hasTooltip" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $curl ?>" target="_self">
<i class="fa fa-linkedin"></i>LinkedIn</a></li><li><a class="pinterest hasTooltip" href="http://pinterest.com/pin/create/button/?url=<?php echo $curl?>" target="_self">
<i class="fa fa-pinterest"></i>Pinterest</a></li>       </ul>
</div></div>

