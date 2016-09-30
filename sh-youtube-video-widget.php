<?php
    /* 
        Plugin Name: sh youtube video widget
        Plugin URI: 
        Description: "sh youtube video widget" is simple, easy to integrate widget to show YouTube video in your widget area.
        Version: 1.1
        Author: Shan
        Author URI: https://in.linkedin.com/in/santanubiswas925
    
        Copyright 2016  Shan
        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        You should have received a copy of the GNU General Public License 
        along with this program; if not, write to the Free Software 
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */
?>

<?php

    // Plugin version
    if ( ! defined( 'sh_youtube_video_widget_version' ) ) {
        define( 'sh_youtube_video_widget_version', '1.1' );
    }

    // Action hook to create plugin widget 
    add_action( 'widgets_init', 'shytvw_register_widgets' );
    
    //register our widget 
    function shytvw_register_widgets() {
        register_widget( 'shytvw_widget' );
    }
    
    class shytvw_widget extends WP_Widget{
        function __construct() {
            $widget_ops = array(
                                'classname'     => 'shytvw_widget',
                                'description'   => 'Easily embed YouTube video in your widget area'
                            );
            parent::__construct(
                                // Base ID of the Widget
                                'shytvw_widget',
                                // Name
                                'YouTube Video',
                                $widget_ops
                            );
            add_action( 'admin_enqueue_scripts', array($this, 'load_scripts') );
            add_action( 'wp_enqueue_scripts', array($this, 'load_scripts') );
        }
        
        function load_scripts() {
            wp_enqueue_style( 'shyt_style_sheet', plugin_dir_url(__FILE__).'scpl_style.css', '' );
            wp_enqueue_script( 'shyt_upload_jquery', plugin_dir_url(__FILE__).'shytvw_script.js', array( 'jquery' ), sh_youtube_video_widget_version, true );
        }
        
        // build our widget settings form
        function form($instance) {
            $defaults = array(
                                'title'            => 'YouTube video ID',
                                'yt_video_id'      => ''
                            );
            $instance = wp_parse_args( (array) $instance, $defaults );
            
            $title          = isset($instance['title']) ? esc_attr($instance['title']) : '';
            $yt_video_id    = isset($instance['yt_video_id']) ? esc_attr($instance['yt_video_id']) : '';
            
            ?>
            <table class="title_container">
                <tr valign="top">
                    <td><?php _e( 'Widget Title', 'sh-youtube-video' ); ?></td>
                    <td><input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" /></td>
                </tr>
                <tr valign="top">
                    <td><?php _e( 'Category ID(s) (comma separated)', 'sidebar-custom-post-list' ); ?></td>
                    <td><input type="text" id="<?php echo $this->get_field_id('yt_video_id'); ?>" name="<?php echo $this->get_field_name('yt_video_id'); ?>" value="<?php echo $yt_video_id; ?>" /></td>
                </tr>
            </table>
            
                
            <?php
        }
        
        // save or update our Widget settings
        function update( $new_instance, $old_instance ) {
            $instance   = $old_instance;
            $instance['title']          = strip_tags($new_instance['title']);
            $instance['yt_video_id']    = strip_tags($new_instance['yt_video_id']);
            
            return $instance;
        }
        
        function widget( $args, $instance ) {
            extract($args);
            
            // Get the data
            $title          = apply_filters( 'widget_title', $instance['title'] );
            if( empty($title) ) {
                $title = $before_title . "Youtube Video" . $after_title;
            }
            else {
                $title = $before_title . $title . $after_title;
            }
            
            $yt_video_id    = $instance['yt_video_id'];
            
            if( empty($posts_per_page) )
                $posts_per_page = 5;
                
            // Dsiplay our widget
            echo $before_widget;
            
            if( $yt_video_id )
            {
                _e( '<h2 class="widget-title">'.$title.'</h2>', 'textdomain' );
                _e('<div class="youtube-player" data-id="'.$yt_video_id.'"></div>', 'textdomain');
            }
            
            echo $after_widget;
        }
    }
    