<?php


/**
 * Plugin Name: Dynamic Sticky Header
 * Plugin URI:
 * Description: Make your sticky header navigation move dynamically away from the field of view when scrolling.
 * Version: 1.0.0
 * Text Domain:
 * Author: Jarmo Lääkkö
 * Author URI: jamotech.vetohazard.fi
 */


if( ! defined( 'ABSPATH' ) ) exit;

/*
*This program is free software: you can redistribute it and/or modify it under the terms of the
 GNU General Public License as published by the Free Software Foundation, either version GPLv2 of the License, or
 any later version.
*This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 See the GNU General Public License for more details.
*You should have received a copy of the GNU General Public License along with this program.
If not, see <https://www.gnu.org/licenses/>.
*/

Class DynamicStickyHeader {

    function __construct() {
         //init database name
        global $wpdb;
        $this->charset = $wpdb->get_charset_collate();
        $this->tablename = $wpdb->prefix . "DynamicHeader";
        //Create new settings menu item and call onActivate
        add_action('admin_menu', array($this, 'dynamicStickyHeaderSettingsMenu'));
        add_action('wp_loaded', array($this, 'dynamicStickyHeaderOnActivate'));

    }

    function dynamicStickyHeaderSettingsMenu(){

        add_options_page('Dynamic Sticky Header Settings', __('Dynamic Sticky Header Settings', 'wcpdomain'), 'manage_options', 'Dynamic-Header-Settings-page', array($this, 'dynamicStickyHeaderEditorHTML'));
    }

    function dynamicStickyHeaderOnActivate() {

      //Make database connection
      global $wpdb;
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta("CREATE TABLE $this->tablename (
        setting_id INT(2) NOT NULL,
        Dynamic_Header_On_Off varchar(60) NOT NULL DEFAULT '',
        PRIMARY KEY  (setting_id)
      ) $this->charset;");

      $table = $wpdb->prefix.'DynamicHeader';
      $true_or_false = $wpdb->get_var( "SELECT Dynamic_Header_On_Off FROM $table");

      if($true_or_false == 'true') {
        $dynamic_sticky_header_path = plugins_url( 'js/Dynamic_Sticky_header.js', __FILE__ );


        //enqueue script
        wp_enqueue_script(
        "DynamicStickyHeader",
        $dynamic_sticky_header_path

        );

      }
    }


    function dynamicStickyHeaderHandleForm() {

        global $wpdb;
        $table = $wpdb->prefix.'DynamicHeader';
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table");

      if(isset($_POST['enableDynamicStickyHeader'])){
        //check if record exists in db

          if($count > 0) {
            global $wpdb;
            $table = $wpdb->prefix.'DynamicHeader';
            $format = array('%d','%s');
            $where = [ 'setting_id' => '1' ];
            $data = array('setting_id' => '1','Dynamic_Header_On_Off' => "true");
            $wpdb->update($table,$data,$where,$format);

            ?>
              <div class="updated">
            <p>Your Dynamic Sticky Header is turned on</p>
          </div>

            <?php

          }

        //if database has no record insert data to db
        else {

            global $wpdb;
            $table = $wpdb->prefix.'DynamicHeader';
            $format = array('%d','%s');
            $data = array('setting_id' => "1", 'Dynamic_Header_On_Off' => "true");
            $wpdb->insert($table,$data,$format);


            ?>
            <div class="updated">
          <p>Your Dynamic Sticky Header is turned on</p>
        </div>

          <?php
          }
     }  //if database has no record and user sends an empty input, set dynamic header to false
        if(empty($_POST['enableDynamicStickyHeader'])) {

          if ($count == 0) {


            global $wpdb;
            $table = $wpdb->prefix.'Dynamic_Header_On_Off';
            $format = array('%d','%s');
            $data = array('setting_id' => "1", 'Dynamic_Header_On_Off' => "false");
            $wpdb->insert($table,$data,$format);

            ?>
            <div class="updated">
          <p>Your Dynamic Sticky Header is turned off</p>
        </div>

          <?php
          } //if db has a record and user sends an empty input, set dynamic header to false
          else {

            global $wpdb;
            $table = $wpdb->prefix.'DynamicHeader';
            $format = array('%d','%s');
            $where = [ 'setting_id' => '1' ];
            $data = array('setting_id' => '1','Dynamic_Header_On_Off' => "false");
            $wpdb->update($table,$data,$where,$format);

            ?>
            <div class="updated">
          <p>Your Dynamic Sticky Header is turned off</p>
        </div>

          <?php
          }
        }
    }


function dynamicStickyHeaderEditorHTML(){

    $dynamic_sticky_header_css_path = plugins_url( 'CSS/DynamicStickyHeader.css', __FILE__ );
    wp_enqueue_style('DynamicStickyHEaderCSS',$dynamic_sticky_header_css_path, false);
        ?>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <body>

    <?php
     global $wpdb;
    if ($_POST['justsubmitted'] == "true") $this->dynamicStickyHeaderHandleForm() ?>
    <form method="POST">
          <input type="hidden" name="justsubmitted" value="true">
            <?php wp_nonce_field('Our-Classic-Editor_plugin', 'ourNonce');

            //Give db info for HTML elements

            $table = $wpdb->prefix.'DynamicHeader';
            $true_or_false = $wpdb->get_var( "SELECT Dynamic_Header_On_Off FROM $table");

            if($true_or_false == 'true') {

            ?>
            <h2>Disable Dynamic Sticky Header</h2>
                  <label class="switch">
                  <input type="checkbox" name="enableDynamicStickyHeader" value="Dynamic_Sticky_Header_On" checked>
                  <span class="slider round"></span>
            </label> <?php }

            else {?>
              <h2>Enable Dynamic Sticky Header</h2>
            <label class="switch">
                  <input type="checkbox" name="enableDynamicStickyHeader" value="dynamicstickyheaderon">
                  <span class="slider round"></span>
            </label> <?php
            }
              ?>
            <table>
              <tbody>
              </tbody>
            </table>
            </div>


            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
          </form>


    </body>



        <?php

          }
}

$ourDynamicHeader= new DynamicStickyHeader();
 ?>
