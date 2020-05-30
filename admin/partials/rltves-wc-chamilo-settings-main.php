<?php
$propulsionTypes = array(
    'unknown'   => __( '', 'rltves-wc-chamilo' ),
    'light_speed'   => __( 'Light Speed', 'rltves-wc-chamilo' ),
    'ftl_speed'   => __( 'Faster Than Light', 'rltves-wc-chamilo' ),
);

$settings = array(
        array(
            'name' => __( 'Chamilo Database Configuration', 'rltves-wc-chamilo' ),
            'type' => 'title',
            'id'   => $prefix . 'chamilo_database_config_settings'
        ),
        array(
            'id'        => $prefix . 'host',
            'name'      => __( 'Hostname', 'rltves-wc-chamilo' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' The name of the mysql server host. (IP or dns name) ', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'port',
            'name'      => __( 'Port', 'rltves-wc-chamilo' ), 
            'type'      => 'number',
            'desc_tip'  => __( ' The numeric value of the Mysql tcp-ip port.', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'databasename',
            'name'      => __( 'Database name', 'rltves-wc-chamilo' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' The name of database in mysql server. ', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'username',
            'name'      => __( 'Username', 'rltves-wc-chamilo' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' The username to use for mysql server authentication. ', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'password',
            'name'      => __( 'Password', 'rltves-wc-chamilo' ), 
            'type'      => 'text',
            'desc_tip'  => __( ' The password to use for mysql server authentication. ', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => '',
            'name'      => __( 'Chamilo Database Configuration', 'rltves-wc-chamilo' ),
            'type'      => 'sectionend',
            'desc'      => '',
            'id'        => $prefix . 'chamilo_database_config_settings'
        ),
        /*
        array(
            'name' => __( 'Flagship Settings', 'rltves-wc-chamilo' ),
            'type' => 'title',
            'id'   => $prefix . 'flagship_settings',
        ),
        array(
            'id'        => $prefix . 'ship_propulsion_type',
            'name'      => __( 'Propulsion Type', 'rltves-wc-chamilo' ), 
            'type'      => 'select',
            'class'     => 'wc-enhanced-select',
            'options'   => $propulsionTypes,
            'desc_tip'  => __( ' The primary propulsion type utilized by this flagship.', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'ship_length',
            'name'      => __( 'Length', 'rltves-wc-chamilo' ), 
            'type'      => 'number',
            'desc_tip'  => __( ' The length in meters of this ship.', 'rltves-wc-chamilo')
        ),
        array(
            'id'        => $prefix . 'ship_in_service',
            'name'      => __( 'In Service?', 'rltves-wc-chamilo' ),
            'type'      => 'checkbox',
            'desc'  => __( 'Uncheck this box if the ship is out of service.', 'rltves-wc-chamilo' ),
            'default'   => 'yes'
        ),             
        array(
            'id'        => '',
            'name'      => __( 'Flagship Settings', 'rltves-wc-chamilo' ),
            'type'      => 'sectionend',
            'desc'      => '',
            'id'        => $prefix . 'flagship_settings',
        ),
        */                        
    );
?>