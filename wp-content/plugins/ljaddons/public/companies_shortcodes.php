<?php

    function companies_carousel_shortcode($atts = array()){
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        $default = array(
        'category' => null,
        'per_page' => -1,
        );
        $attributes = shortcode_atts($default, $atts);
        
        $args = array(
                    'post_type'      => 'companies',
                    'posts_per_page' => $attributes['per_page'],
                    'publish_status' => 'published',
                 );
                 
        if ( ! is_null($attributes['category']) ) {
            $args['tax_query'] = array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'departments',
                            'field' => 'id',
                            'terms' => $attributes['category'],
                        )
                    );
        }
        
        $query = new WP_Query($args);
        $result = '<div class="row"><div class="container"><section class="ljaddon_companies-carousel slider" data-arrows="true">';
        if($query->have_posts()) :
  
            while($query->have_posts()) :
                
               $query->the_post();
               $result .= '<div class="slide"><img src="'. get_the_post_thumbnail_url(get_the_ID()) .'" alt="'. get_the_title() .'"></div>';
                
            endwhile;
      
            wp_reset_postdata();
  
        endif;   
        
        $result .= "</section></div></div>";
        
        return $result;
    }
    
   
 
    function callback_for_companies_setting_up_scripts() {
        
        global $post;
        
        wp_register_style( 'ljaddons_companies_css_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
        wp_register_style( 'ljaddons_companies_css_slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
        
        wp_register_style('ljaddons_companies_shortcode', plugins_url('css/ljaddons_companies_shortcode.css',__FILE__ ));
        
        wp_register_script( 'ljaddons_companies_js_jquery', 'https://code.jquery.com/jquery-3.4.1.min.js');
        wp_register_script( 'ljaddons_companies_js_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
        wp_register_script( 'ljaddons_companies_js_slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');
        
        wp_register_script( 'ljaddons_companies_shortcode', plugins_url('js/ljaddons_companies_shortcode.js',__FILE__ ));
        
        if(isset($post->post_content) and (has_shortcode( $post->post_content, 'companies_carousel') || has_shortcode( $post->post_content, 'get_companies_boxed')) && ( is_single() || is_page() ) ){
            wp_enqueue_style( 'ljaddons_companies_css_bootstrap' );
            wp_enqueue_style( 'ljaddons_companies_css_slick' );
            wp_enqueue_style( 'ljaddons_companies_shortcode' );
            if ( ! wp_script_is( 'jquery', 'enqueued' )) {
                wp_enqueue_script( 'ljaddons_companies_js_jquery');
            }
            wp_enqueue_script( 'ljaddons_companies_js_bootstrap');
            wp_enqueue_script( 'ljaddons_companies_js_slick');
            wp_enqueue_script( 'ljaddons_companies_shortcode');
        }
    }
    add_action('wp_enqueue_scripts', 'callback_for_companies_setting_up_scripts');
    
  
    
    
    function companies_shortcode($atts = array()){
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        $default = array(
        'category' => null,
        'per_page' => -1,
        );
        $attributes = shortcode_atts($default, $atts);
        
        $args = array(
                    'post_type'      => 'companies',
                    'posts_per_page' => $attributes['per_page'],
                    'publish_status' => 'published',
                    'meta_key'          => 'sale',
                    'orderby'           => 'meta_value',
                    'order'             => 'ASC'
                 );
                 
        if ( ! is_null($attributes['category']) ) {
            $args['tax_query'] = array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'departments',
                            'field' => 'id',
                            'terms' => $attributes['category'],
                        )
                    );
        }
        
        $query = new WP_Query($args);
        $result = '<div class="row align-items-center lj_companies_list m-auto">';
        if($query->have_posts()) :
  
            while($query->have_posts()) :
                
               $query->the_post();
               $result .= '<div class="col-md-2 mt-3 mb-3"><img src="'. get_the_post_thumbnail_url(get_the_ID()) .'" alt="'. get_the_title() .'"></div>';
                
            endwhile;
      
            wp_reset_postdata();
  
        endif;   
        
        $result .= "</div>";
        
        return $result;
    }
    
    function companies_boxed_shortcode($atts = array()){
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        $default = array(
        'category' => null,
        'per_page' => -1,
        );
        $attributes = shortcode_atts($default, $atts);
        
        $args = array(
                    'post_type'      => 'companies',
                    'posts_per_page' => $attributes['per_page'],
                    'publish_status' => 'published',
                    'meta_key'          => 'sale',
                    'orderby'           => 'meta_value',
                    'order'             => 'DESC'
                 );
                 
        if ( ! is_null($attributes['category']) ) {
            $args['tax_query'] = array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'departments',
                            'field' => 'id',
                            'terms' => $attributes['category'],
                        )
                    );
        }
        
        $terms = get_terms([
            'taxonomy' => 'departments',
            'hide_empty' => false,
        ]);
        
        $cat_html = '<div class="container offtop"><div class="row "><div class="col-md-3"><div class="mb-4 catSection">
		<h3>Categories</h3>
		<div class="list-group partners-cats dam border-none">';
		
		
        foreach($terms as $term){
            $cat_html .= '<a href="#" value="'. $term->term_id .'" class="list-group-item text-dark list-group-item-action pl-2" style="border:none!important; " ><span style="border-radius:50%; padding:0px 5px; margin-right:5px; border:1px solid black;">+</span>'. $term->name .'</a>';
        }
        
        $cat_html .= '</div></div></div>';
        $query = new WP_Query($args);
        $result = $cat_html . '<div class="col-md-9 pl-0"><div class="row align-items-center lj_companies_list m-auto">';
        if($query->have_posts()) :
  
      while ($query->have_posts()) :
    $query->the_post();

    $img_result = get_field('cover_image') ? '<img src="' . get_field('cover_image') . '" style="border-radius:30px 30px 0 0; height:300px; object-fit:cover;" class="card-img-top" alt="' . get_the_title() . '">' : '';
    $sale_result = get_field('sale') ? '<hr class="mb-2 w-80 mt-0 ml-3 mr-3" style="background-color:black;"><span class="text-right text-dark mb-3 mr-3">Sale: <strong>' . get_field('sale') . '</strong></span>' : '';
    $department_terms = get_the_terms(get_the_ID(), 'departments');
$department_name = $department_terms ? $department_terms[0]->name : ''; // Get the name of the first department term, if available
// Get the card background image URL from the department term
$card_background_url = "https://escard.ge/wp-content/uploads/2024/02/ფონები-21.png";
if ($department_terms) {
    $card_background_url = get_field('card_background', $department_terms[0]);
}
    $single_result = '<div class="card position-relative" style="border-radius:30px; background:url(https://via.placeholder.com/150);">
        ' . $img_result . '
        <div class="category position-absolute m-4 " style="border-radius:15px; padding:5px 10px; background:white; color:black; max-width:115px;">'. $department_name .'</div>
        <div class="logo position-absolute m-4 right" style="border-radius:50%; padding:5px 10px; right:0px; background:white;">
            <img src="'. get_the_post_thumbnail_url(get_the_ID()) .'" width="40" height="50" style="border-radius:50%; height:50px; object-fit:contain;">
        </div>
        <div class="card-body position-relative" style="top:8px;">
            <img src="'. $card_background_url .'" class="position-absolute" style="z=index:1; left:0; bottom:0px;">
            <h5 class="card-title new-card-title position-absolute text-white font-weight-bold" style="bottom:100%;">' . get_the_title() . '</h5>
            <p class="card-text new-card-sale font-weight-bold text-white position-absolute" style="font-size:24px; bottom:50%">' . get_field('sale') . '</p>
        </div>
    </div>';

    $website_url = get_field('website_url');
    if ($website_url) {
        $single_result = '<a href="' . $website_url . '" target="_blank" class="col-md-4 p-0" id="' . get_the_terms(get_the_ID(), 'departments')[0]->term_id . '"><div class="col-md-12 mt-3 mb-3">' . $single_result . '</div></a>';
    } else {
        $single_result = '<div class="col-md-4 mt-3 mb-3 lj_company_boxed_card" id="' . get_the_terms(get_the_ID(), 'departments')[0]->term_id . '">' . $single_result . '</div>';
    }

    $result .= $single_result;
endwhile;
         
      
            wp_reset_postdata();
  
        endif;   
        
        $result .= "</div></div></div>";
        
        return $result;
    }
    
    function companies_home_shortcode($atts = array()){
    $atts = array_change_key_case((array) $atts, CASE_LOWER);
    $default = array(
        'category' => null,
        'per_page' => 3,
    );
    $attributes = shortcode_atts($default, $atts);
    
    $args = array(
        'post_type'      => 'companies',
        'posts_per_page' => $attributes['per_page'],
        'publish_status' => 'published',
        'orderby'        => 'rand', // Order randomly
    'order'          => 'DESC' // Descending order for random
    );
                 
    if (!is_null($attributes['category'])) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'departments',
                'field'    => 'id',
                'terms'    => $attributes['category'],
            )
        );
    }
    
    $terms = get_terms([
        'taxonomy'   => 'departments',
        'hide_empty' => false,
    ]);
    $cat_html = '<div class="container p-0 offtop" style="max-width:1200px; overflow-x:hidden;"><div class="row ">';
    $query = new WP_Query($args);
    $result = $cat_html . '<div class="col-md-12 pl-0"><div class="row align-items-center lj_companies_list m-auto">';
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            $img_result = get_field('cover_image') ? '<img src="' . get_field('cover_image') . '" style="border-radius:30px 30px 0 0; height:400px; object-fit:cover;" class="card-img-top" alt="' . get_the_title() . '">' : '';
            $sale_result = get_field('sale') ? '<hr class="mb-2 w-80 mt-0 ml-3 mr-3" style="background-color:black;"><span class="text-right text-dark mb-3 mr-3">Sale: <strong>' . get_field('sale') . '</strong></span>' : '';
            $department_terms = get_the_terms(get_the_ID(), 'departments');
            $department_name = $department_terms ? $department_terms[0]->name : '';
            $card_background_url = "https://escard.ge/wp-content/uploads/2024/02/ფონები-21.png";
            if ($department_terms) {
                $card_background_url = get_field('card_background', $department_terms[0]);
            }
            $single_result = '<div class="card position-relative" style="border-radius:30px; left:5px; background:url(https://via.placeholder.com/150);">
                ' . $img_result . '
                <div class="category position-absolute m-4 " style="border-radius:15px; padding:5px 10px; background:white; color:black; max-width:150px;">' . $department_name . '</div>
                <div class="logo position-absolute m-4 right" style="border-radius:50%; padding:5px 10px; right:0px; background:white;">
                    <img src="/wp-content/uploads/2024/03/arrowC.png" width="40" height="50" style="border-radius:50%; height:50px; object-fit:contain;">
                </div>
                <div class="card-body position-relative" style="top:8px; width:100.5%">
                    <img src="' . $card_background_url . '" class="position-absolute" style="z=index:1; left:0; bottom:0px;">
                    <h4 class="card-title new-card-title position-absolute text-white font-weight-bold" style="bottom:150%; font-size:26px;">' . get_the_title() . '</h4>
                    <p class="card-text new-card-sale font-weight-bold text-white position-absolute" style="font-size:30px; bottom:70%">' . get_field('sale') . '</p>
                </div>
            </div>';

            $website_url = get_field('website_url');
            if ($website_url) {
                $single_result = '<a href="' . $website_url . '" target="_blank" class="col-md-4 p-0" id="' . get_the_terms(get_the_ID(), 'departments')[0]->term_id . '"><div class="col-md-12 mt-3 mb-3">' . $single_result . '</div></a>';
            } else {
                $single_result = '<div class="col-md-4 mt-3 mb-3 lj_company_boxed_card" id="' . get_the_terms(get_the_ID(), 'departments')[0]->term_id . '">' . $single_result . '</div>';
            }

            $result .= $single_result;
        endwhile;
         
        wp_reset_postdata();
  
    endif;   
        
    $result .= "</div></div></div>";
    
    return $result;
}

    
    $settings = ljaddons_get_settings();
    if($settings['companies_enabled']){
        add_shortcode('companies_carousel', 'companies_carousel_shortcode');
        add_shortcode('get_companies', 'companies_shortcode');
        add_shortcode('get_companies_boxed', 'companies_boxed_shortcode');
        add_shortcode('get_companies_home', 'companies_home_shortcode');
    }

    function callback_for_get_companies_setting_up_scripts() {
        
        global $post;
        
        wp_register_style( 'ljaddons_companies_css_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );
        wp_register_style('ljaddons_companies_shortcode', plugins_url('css/ljaddons_companies_shortcode.css',__FILE__ ));
        wp_register_script( 'ljaddons_companies_js_bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');

        if(isset($post->post_content) and (has_shortcode( $post->post_content, 'get_companies') || has_shortcode( $post->post_content, 'get_companies_boxed') ) ){
            wp_enqueue_style( 'ljaddons_companies_css_bootstrap' );
            if ( ! wp_script_is( 'jquery', 'enqueued' )) {
                wp_enqueue_script( 'ljaddons_companies_js_jquery');
            }
            wp_enqueue_style( 'ljaddons_companies_shortcode' );
            wp_enqueue_script( 'ljaddons_companies_js_bootstrap');
        }
    }
    add_action('wp_enqueue_scripts', 'callback_for_get_companies_setting_up_scripts');

    