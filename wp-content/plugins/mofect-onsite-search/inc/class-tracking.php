<?php
/**
 * user tracking.
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

use UAParser\Parser;

if (!class_exists('MOSS_tracking')) {
	class MOSS_tracking {
        /**
         * client ip address.
         */
        public $ip = '';

        public $city= '';

        public $region= '';

        public $country= '';

        public $latitude= '';
        
        public $longitude= '';

        /**
         * client user agent.
         * {
         *  ua: {
         *      major: "11",
         *      minor: "0",
         *      patch: null,
         *      family: "Mobile Safari",
         *  },
         *  os: {
         *      major: "11",
         *      minor: "0",
         *      patch: null,
         *      family: "iOS",
         *  },
         *  device: {
         *      brand: "Apple",
         *      model: "iPad",
         *      family: "iPad"
         *  }
         * }
         */
        public $user_agent;

		public function __construct() {
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $this->ip = $_SERVER['HTTP_CLIENT_IP'];
            }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $this->ip = $_SERVER['REMOTE_ADDR'];
            }

            $country_short_names_data = file_get_contents(__DIR__.'/ua-parser/names.json');
            $country_short_names_mapping = json_decode($country_short_names_data);

            if(!empty($this->ip)){  
                try{
                    $details = json_decode( file_get_contents("https://ipinfo.io/{$this->ip}/json"));
                    $country = $details->country;

                    $this->city = $details->city;
                    $this->region = $details->region;
                    $this->country = $country_short_names_mapping->$country;
                    $loc = $details->loc;

                    if(!empty($loc)){
                        $attr = explode(',', $loc);
                        if(sizeof($attr) == 2){
                            $this->latitude = $attr[0];
                            $this->longitude = $attr[1];
                        }
                    }
                }catch(Exception $err){
                    //var_dump($$err);
                }
            }
            
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $parser = Parser::create();
            $result = $parser->parse($user_agent);
            $this->user_agent = $result;
        }
        
        static function time_to_date( $time ) {
            date_default_timezone_set('UTC');
            return gmdate( 'd, F, Y', $time );
        }
    
        static function now() {
            return self::time_to_date(time());
        }
    
        static function date_to_time( $date ) {
            return strtotime( $date . ' GMT' );
        }

        static function retrive_user_track_data(){
            $args = array(
                'post_type' => 'moss-keyword',
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);
        }

        static function retrive_rencent_user_track_data(){
            $args = array(
                'post_type' => 'moss-keyword',
                'orderby'   => 'date',
                'order'     => 'DESC', //ASC
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);
        }

        static function retrive_user_track_data_week(){
            $args = array(
                'post_type' => 'moss-keyword',
                'date_query' => array(
                    array(
                        'year' => date( 'Y' ),
                        'week' => date( 'W' ),
                    ),
                ),
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);;
        }

        static function retrive_user_track_data_today(){
            $today = getdate();
            $args = array(
                'post_type' => 'moss-keyword',
                'date_query' => array(
                    array(
                        'year'  => $today['year'],
                        'month' => $today['mon'],
                        'day'   => $today['mday'],
                    ),
                ),
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

           return MOSS_tracking::_retrive_data_by_args($args);
        }

        static function retrive_user_track_data_month(){
            $today = getdate();
            $args = array(
                'post_type' => 'moss-keyword',
                'date_query' => array(
                    array(
                        'year'  => $today['year'],
                        'month' => $today['mon']
                    ),
                ),
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);
        }

        static function retrive_user_track_data_yesterday(){
            //$today = getdate();
            $yesterday = date("F j, Y", strtotime("yesterday"));
            $args = array(
                'post_type' => 'moss-keyword',
                'date_query' => array(
                    array(
                        'year'  => $yesterday['year'],
                        'month' => $yesterday['mon'],
                        'day'   => $yesterday['mday'],
                    ),
                ),
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);
        }

        static function retrive_user_track_data_year(){
            $today = getdate();
            $args = array(
                'post_type' => 'moss-keyword',
                'date_query' => array(
                    array(
                        'year'  => $today['year']
                    ),
                ),
                'posts_per_page' => -1 //we need retrive all of search keywords for statistic
            );

            return MOSS_tracking::_retrive_data_by_args($args);
        }

        private static function _retrive_data_by_args($args){
            $ret = array();
            $keyword_query = new WP_Query($args); 

            if ($keyword_query->have_posts()){
                while ($keyword_query->have_posts()){
                    $keyword_query->the_post();
                    $data = get_post_meta(get_the_ID(), '_moss_keyword_statistics', true);

                    $keyword_buffer = array(
                        'keyword' => get_the_title(),
                        'post_id' => get_the_ID(),
                        'data' => $data
                    );
                    $ret[] = $keyword_buffer;
                } 
                wp_reset_postdata();
            }

            return $ret;
        }
    }
}