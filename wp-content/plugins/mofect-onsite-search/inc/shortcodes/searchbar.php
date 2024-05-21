<?php
/**
 * Search bar Shortcode
 * @package Mofect On-Site Search
 * @since 1.0.0
 */

if(!class_exists('MOSS_SearchBar')){
    class MOSS_SearchBar extends MOSS_Shortcode{
        public static $shortcode_name = 'mofect_searchbar',
                      $element_name = 'Mofect Search Bar';

        public function __construct() {
            parent::__construct(self::$shortcode_name);
        }

        /** 
         * Define Shortcode Parameters 
         * Shortcode: [mofect_searchbar]
         */
        public function register_shortcode($atts, $content = null){

          $element_vars = shortcode_atts(apply_filters('moss_searchbar_args',
                            array(
                              'label'         =>  'Search',
                              'placeholder'   =>  'Enter Keyword',
                              'layout'        =>  'list',
                              'post_type'     =>  '',
                              'posts_per_page'=>  '12',
                              'orderby'       =>  'date',
                              'order'         =>  'desc',
                              'category'      =>  '',
                              'extra_class'   =>  ''
                            )
                          ), $atts);

          ob_start();
          $this->shortcode_template($element_vars);
          return ob_get_clean();
        }

        /** 
         * Integrate into WPBakery page builder 
         */
        public function wpb_map_options(){
            if(function_exists('vc_map')){
                vc_map( apply_filters('moss_searchbar_wpb_info', array(
                        "name"            => self::$element_name,
                        "base"            => self::$shortcode_name,
                        "content_element" => true,
                        "category"        => 'Mofect',
                        "show_settings_on_create" => true,
                        "admin_enqueue_css" => array(MOSS_URI.'/inc/admin/assets/css/page-builder.css'),
                        "class"           => "moss_searchbar_wpb_icon",
                        "icon"            => MOSS_URI.'/inc/admin/assets/img/icon-blue.png',
                        "params"          => apply_filters('moss_searchbar_wpb_options',array(

                                  array(
                                      "type"        => "dropdown",
                                      "holder"      => "span",
                                      "param_name"  => "post_type",
                                      "class"       => "moss_post_type_param",
                                      "heading"     => esc_html__("Post Type","moss"),
                                      "value"       => array(
                                        esc_html__('All', "moss")    => '',
                                        esc_html__('Post', "moss")   => 'post',
                                        esc_html__('Product', "moss")=> 'product'
                                      ),
                                  ),

                                  array(
                                      "type"        => "dropdown",
                                      "holder"      => "span",
                                      "param_name"  => "layout",
                                      "class"       => "moss_layout_param",
                                      "heading"     => esc_html__("Layout","moss"),
                                      "value"       => array(
                                        esc_html__('List', "moss")    => 'list',
                                        esc_html__('Grid', "moss")    => 'grid',
                                      ),
                                  ),

                                  array(
                                      "type"        => "textfield",
                                      "holder"      => "span",
                                      "param_name"  => "label",
                                      "class"       => "moss_label_param",
                                      "heading"     => esc_html__("Label Text","moss"),
                                      'description' => __('If leave it empty, the label text will be hidden.', "moss"),
                                      "value"       => "Search",
                                  ),

                                  array(
                                      "type"          => "textfield",
                                      "holder"        => "span",
                                      "class"         => "moss_placeholder_param",
                                      "param_name"    => "placeholder",
                                      "heading"       => esc_html__("Placeholder Text","moss"),
                                      'description'   => __('If leave it empty, the placeholder text will be hidden.', "moss"),
                                      "value"         => "Enter Keyword",
                                  ),

                                  array(
                                      "type"        => "textfield",
                                      "holder"      => "div",
                                      "class"       => "moss_posts_per_page_param",
                                      "param_name"  => "posts_per_page",
                                      "heading"     => esc_html__("Number of Results per Page","moss"),
                                      "value"       => "12",
                                  ),
                          
                                  array(
                                      "type"        => "textfield",
                                      "holder"      => "span",
                                      "class"       => "moss_category_param",
                                      "param_name"  => "category",
                                      "heading"     => esc_html__("Category","moss"),
                                      'description' => __('If you want to limit the search result within the specific categories, please add the category slugs here, multiple categories should be separated by English comma.', "moss"),
                                      "value"       => "",
                                  ),

                                  array(
                                      "type"        => "dropdown",
                                      "param_name"  => "order",
                                      "class"       => "moss_order_param",
                                      "holder"      => "span",
                                      "heading"     => esc_html__("Order","moss"),
                                      "value"       => array(
                                        esc_html__('From Newsest to Oldest By Publish Time', "moss")    => 'desc',
                                        esc_html__('From Oldest to Newest By Publish Time', "moss")     => 'asc',
                                      ),
                                  ),
                        ))
                  )) 
              );
            } 
        }//VC End


        /* Integrate into King Composer 
         * @filter moss_searchbar_kc_info / moss_searchbar_kc_options
         */
        public function kc_map_options(){
              if(function_exists('kc_add_map')){
                  kc_add_map(
                      array(
                        self::$shortcode_name  => apply_filters('moss_searchbar_kc_info', array(
                              "name" => self::$element_name,
                              "category" => 'Mofect',
                              "icon"   => 'moss-kc-icon',
                              'assets' => array(
                                  'styles' => array(
                                    'moss-kc' => MOSS_URI.'/inc/admin/assets/css/page-builder.css',                      
                                  )
                              ),
                              "params" => apply_filters('moss_searchbar_kc_options', array(
                                  'General' => array(

                                      array(
                                          "type"     => "dropdown",
                                          "name"     => "post_type",
                                          "label"    => esc_html__("Post Type","moss"),
                                          "value"    => '',
                                          "options"  => array(
                                            ''         =>  esc_html__('All', "moss"),
                                            'post'     => esc_html__('Post', "moss"),
                                            'product'  => esc_html__('Product', "moss")
                                          ),
                                      ),

                                      array(
                                          "type"        => "dropdown",
                                          "name"        => "layout",
                                          "label"       => esc_html__("Layout","moss"),
                                          "value"       => '',
                                          "options"     => array(
                                            'list'  => esc_html__('List', "moss"),
                                            'grid'  => esc_html__('Grid', "moss"),
                                          ),
                                      ),

                                      array(
                                          "type"        => "textfield",
                                          "name"        => "label",
                                          "label"       => esc_html__("Label Text","moss"),
                                          'description' => __('If leave it empty, the label text will be hidden.', "moss"),
                                          "value"       => "Search",
                                      ),

                                      array(
                                          "type"  => "textfield",
                                          "name"  => "placeholder",
                                          "label" => esc_html__("Placeholder Text","moss"),
                                          'description' => __('If leave it empty, the placeholder text will be hidden.', "moss"),
                                          "value" => "Enter Keyword",
                                      ),

                                      array(
                                          "type"  => "textfield",
                                          "name"  => "posts_per_page",
                                          "label" => esc_html__("Number of Results per Page","moss"),
                                          "value" => "12",
                                      ),
                              
                                      array(
                                          "type"        => "textfield",
                                          "label"       => "category",
                                          "heading"     => esc_html__("Category","moss"),
                                          'description' => __('If you want to limit the search result within the specific categories, please add the category slugs here, multiple categories should be separated by English comma.', "moss"),
                                          "value"       => "",
                                      ),

                                      array(
                                          "type"      => "dropdown",
                                          "name"      => "order",
                                          "label"     => esc_html__("Order","moss"),
                                          "value"     => '',
                                          "options"   => array(
                                            'desc'  => esc_html__('From Newsest to Oldest By Publish Time', "moss"),
                                            'asc'   => esc_html__('From Oldest to Newest By Publish Time', "moss"),
                                          ),
                                      ),
                                  ),//General Group End

                              ))//Params End
                        ))//Shortcode End
                    )
                  );
              } 
          }//KC End

    }

    new MOSS_SearchBar();
}