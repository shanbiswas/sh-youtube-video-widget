<?php 
    //The final step to your Post Products plugin is to create your uninstall.php file:
    
    // If uninstall/delete not called from WordPress then exit 
    if( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();
    // Delete options array from options table
    delete_option( 'shytvw_widget' );
?>