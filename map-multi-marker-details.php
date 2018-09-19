<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://convoymedia.com
 * @since             1.0.0
 * @package           Map_Details
 *
 * @wordpress-plugin
 * Plugin Name:       Map Multi Marker Details
 * Plugin URI:        http://convoymedia.com
 * Description:       Extension for Map Multi Marker
 * Version:           1.0.0
 * Author:            Convoy Media
 * Author URI:        http://convoymedia.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       map-details
 * Domain Path:       /languages
 */

    function map_multi_marker_details( $atts ) {
        // Attributes
        $atts = shortcode_atts(
            array(
                "maps" => "none"
            ),
            $atts
        );
        
        if ($atts['maps'] === "none") {
            echo "No Map Selected";
        }
        else {
            global $wpdb;
            $maps = explode(",", $atts['maps']);
            ?>
            <div class="marker-maps">
            <?php
                foreach ($maps as $map) {
                    $m = $wpdb->get_results( "SELECT * FROM wp_mapmarker_option WHERE map_id = '" . trim($map) . "'", ARRAY_A );
                    if ($m) {
                        ?>
                        <div id="map-holder-<?php echo $m[0]['map_id']; ?>" class="map-holder-<?php echo strtolower($m['map_name']); ?>">
                            <?php 
                                echo do_shortcode('[map-multi-marker id="' . $m[0]['map_id'] . '"]'); 
                                $pins = $wpdb->get_results( "SELECT * FROM wp_mapmarker_marker WHERE marker_id = '" . $m[0]['map_id'] . "'", ARRAY_A );
                                ?>
                                <div class="marker-descriptions">
                                    <?php
                                        foreach ($pins as $pin) {
                                            ?>
                                            <div id="marker-<?php echo $pin['id'] ?>" class="marker-description-holder">
                                                <h2><?php echo $pin['titre']; ?></h2>
                                                <p class="marker-description"><?php echo $pin['description']; ?></p>
                                                <p class="marker-address"><?php echo $pin['adresse']; ?></p>
                                                <p class="marker-telephone"><?php echo $pin['telephone']; ?></p>
                                                <a href="<?php echo $pin['weblink']; ?>" target="_blank"><?php echo $pin['weblink']; ?></a>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            ?>
                        </div>
                        <?php
                    }
                }
            ?></div><?php
        }
    }
    add_shortcode( 'map-multi-marker-details', 'map_multi_marker_details' );
?>