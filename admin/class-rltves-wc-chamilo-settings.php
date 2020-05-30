<?php
/**
 * Extends the WC_Settings_Page class
 *
 * @link        https://relato.com.br
 * @since       1.0.0
 *
 * @package     Rltves_Wc_Chamilo
 * @subpackage  Rltves_Wc_Chamilo/admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Rltves_WC_Chamilo_Settings' ) ) {

    /**
     * Settings class
     *
     * @since 1.0.0
     */
    class Rltves_WC_Chamilo_Settings extends WC_Settings_Page {

        /**
         * Constructor
         * @since  1.0
         */
        public function __construct() {
                
            $this->id    = 'rltves-wc-chamilo';
            $this->label = __( 'Chamilo Integration', 'rltves-wc-chamilo' );

            // Define all hooks instead of inheriting from parent                    
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
            add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
            add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
            
        }


        /**
         * Get sections.
         *
         * @return array
         */
        public function get_sections() {
            $sections = array(
                '' => __( 'Settings', 'rltves-wc-chamilo' ),
                'log' => __( 'Log', 'rltves-wc-chamilo' ),
                'notes' => __( 'Notes', 'rltves-wc-chamilo' )
            );

            return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
        }


        /**
         * Get settings array
         *
         * @return array
         */
        public function get_settings() {

            global $current_section;
            $prefix = 'rltves-wc-chamilo_';

            switch ($current_section) {
                case 'log':
                    $settings = array(                              
                            array()
                    );
                    break;
                default:
                    include 'partials/rltves-wc-chamilo-settings-main.php';
            }   

            return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );                   
        }

        /**
         * Output the settings
         */
        public function output() {              
            
            global $current_section;

            switch ($current_section) {
                case 'notes':
                    include 'partials/rltves-wc-chamilo-settings-notes.php';
                    break;
                default:
                    $settings = $this->get_settings();
                    WC_Admin_Settings::output_fields( $settings );
            }               

        }

        /**
         * Save settings
         *
         * @since 1.0
         */
        public function save() {                    
            $settings = $this->get_settings();

            WC_Admin_Settings::save_fields( $settings );
        }

    }

}


return new Rltves_WC_Chamilo_Settings();