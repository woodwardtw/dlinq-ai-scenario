<?php 
/*
Plugin Name: DLINQ AI Scenarios
Plugin URI:  https://github.com/
Description: For listing AI scenarios with shortcode [scenarios cat="slug"]
Version:     1.0
Author:      DLINQ
Author URI:  https://dlinq.middcreate.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_shortcode( 'scenarios', 'dlinq_ai_scenario_list' );

function dlinq_ai_scenario_list($atts){
   // WP QUERY LOOP
   
   $html = "";
   $args = array(
         'posts_per_page' => 50,
         'post_type'   => 'post', 
         'post_status' => 'publish', 
         'nopaging' => false,         
                       );
   if (array_key_exists('cat',$atts)){
      $args["category_name"] = $atts['cat'];
   }
     $the_query = new WP_Query( $args );
                       if( $the_query->have_posts() ): 
                         while ( $the_query->have_posts() ) : $the_query->the_post();
                          //DO YOUR THING
                           $post_id = get_the_ID();
                           $title = get_the_title();
                           $scenario = get_field('scenario',$post_id);
                           $img = get_field('image', $post_id);
                           $link = "https://frau-lustig-d95c323ff760.herokuapp.com/?id=".$post_id;
                           $html .= "<a href='{$link}' class='scenario-link'>
                                       <div class='scenario'>
                                       <img src='{$img}' class='scenario-img' alt='An image representing the main character.''>
                                       <h2>{$title}</h2>
                                      
                                       <h3>Directions for the AI</h3>
                                       <p>$scenario</p>                                    
                                       </div>
                                    </a>   
                                    ";
                            endwhile;
                     endif;
               wp_reset_query();  // Restore global post data stomped by the_post().
      return $html;
   }                    




add_action('wp_enqueue_scripts', 'dlinq_ai_scenario_load_scripts');

function dlinq_ai_scenario_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;    
    wp_enqueue_style( 'dlinq-ai-scenario-main-css', plugin_dir_url( __FILE__) . 'css/dlinq-ai-scenario-main.css');
}

   //save acf json
      add_filter('acf/settings/save_json', 'dlinq_ai_scenario_json_save_point');
       
      function dlinq_ai_scenario_json_save_point( $path ) {
          
          // update path
          $path = plugin_dir_path(__FILE__) . '/acf-json'; //replace w get_stylesheet_directory() for theme          
          
          // return
          return $path;
          
      }

      // load acf json
      add_filter('acf/settings/load_json', 'dlinq_ai_scenario_json_load_point');

      function dlinq_ai_scenario_json_load_point( $paths ) {
          
          // remove original path (optional)
          unset($paths[0]);
          
          
          // append path
          $paths[] = plugin_dir_path(__FILE__)  . '/acf-json';//replace w get_stylesheet_directory() for theme
          
          
          // return
          return $paths;
          
      }


//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

  //print("<pre>".print_r($a,true)."</pre>");
