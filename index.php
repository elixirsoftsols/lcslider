<?php
/* 
Plugin Name: Learn Codez Slider
Plugin URI: http://learncodez.com/
Description: Slider Component for WordPress
Version: 1.0
Author: Sachin Yadav
Author URI: http://learncodez.com/
*/
/*Some Set-up*/
define('LCS_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('LCS_NAME', "Learn Codez Slider");
define ("LCS_VERSION", "1.0");

/*Files to Include*/
require_once('slider-img-type.php');
include_once "inc/settings.php";

/*Add the Javascript/CSS Files!*/
wp_enqueue_script('flexslider', LCS_PATH.'/js/jquery.flexslider-min.js', array('jquery'));
wp_enqueue_style('flexslider_css', LCS_PATH.'/css/flexslider.css');

/*Add the Hooks to place the javascript in the header*/
function lcs_script(){
    $data =  get_option( 'my_option_name' );
    print '<script type="text/javascript" charset="utf-8">
      jQuery(window).load(function() {
        jQuery(\'.flexslider\').flexslider({
        animation: "fade",
        pauseOnHover: true,
        keyboard: true,
        direction: "horizontal"   

    });
      });
    </script>';
 
}
 
add_action('wp_head', 'lcs_script');


function lcs_get_slider(){
    $data =  get_option( 'my_option_name' );

    echo $data['mode'];
    $slider= '<div class="flexslider">
      <ul class="slides">';
 
    $lcs_query= "post_type=slider-image";
    query_posts($lcs_query);
     
     
    if (have_posts()) : while (have_posts()) : the_post(); 
        $img= get_the_post_thumbnail( $post->ID, 'large' );
         
        $slider.='<li>'.$img;

        $slider.= 'This is text </li>';
             
    endwhile; endif; wp_reset_query();
 
 
    $slider.= '</ul>
    </div>';
     
    return $slider; 
 
}
 
 
/**add the shortcode for the slider- for use in editor**/
 
function lcs_insert_slider($atts, $content=null){
 
    $slider= lcs_get_slider(); 
    return $slider; 
}
 
 
add_shortcode('lc_slider', 'lcs_insert_slider');
 
 
 
/**add template tag- for use in themes**/
 
function lcs_slider(){
 
    print lcs_get_slider();
}




?>