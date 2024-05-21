<?php
/**
 * CMB2 Extensions
 * Load extensions file
 * @package cmb2
 */

final class CMB2_Extensions {
    
    /**
     * Initalizes any extensions found in the extensions directory.
     */
    static public function init()
    {
        $extensions = glob( MOSS_DIR . '/inc/metabox/extensions/*' );

        if ( ! is_array( $extensions ) ) {
            return;
        }
        
        foreach ( $extensions as $extension ) {
            
            if ( ! is_dir( $extension ) ) {
                continue;   
            }
            $path = trailingslashit( $extension ) . basename( $extension ) . '.php';
            if ( file_exists( $path ) ) {
                require_once $path;
            }
        }
    }
}

CMB2_Extensions::init();