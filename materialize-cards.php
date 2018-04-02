<?php
/**
 * Plugin Name: Materialize Cards
 * Description: Display images with captions as Materialize Cards. Cards are a convenient means of displaying content composed of different types of objects. They’re also well-suited for presenting similar objects whose size or supported actions can vary considerably, like photos with captions of variable length.
 * Plugin URI:  https://github.com/ajatamayo/materialize-cards
 * Version:     1.0
 * Author:      AJ Tamayo
 * Author URI:  https://github.com/ajatamayo
 * License:     GPL
 * Text Domain: materialize-cards
 * Domain Path: /languages
 *
 */

add_action( 'plugins_loaded', array( Materialize_Cards::get_instance(), 'plugin_setup' ) );

class Materialize_Cards {
    protected static $instance = NULL;
    public $plugin_url = '';
    public $plugin_path = '';

    /**
     *
     * @since 1.0
     */
    public function __construct() {}

    /**
     *
     * @since 1.0
     */
    public function load_language( $domain ) {
        load_plugin_textdomain(
            $domain,
            FALSE,
            $this->plugin_path . '/languages'
        );
    }

    /**
     *
     * @since 1.0
     */
    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }

    /**
     *
     * @since 1.0
     */
    public function plugin_setup() {
        $this->plugin_url    = plugins_url( '/', __FILE__ );
        $this->plugin_path   = plugin_dir_path( __FILE__ );
        $this->load_language( 'materialize-cards' );

        // Change caption shortcode to use cards
        add_filter( 'img_caption_shortcode', array( &$this, 'convert_to_image_card' ), 10, 3 );
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 10 );
    }

    /**
     *
     * @since 1.1
     */
    function convert_to_image_card( $output, $attr, $content ) {
        $id = (int) str_replace( 'attachment_', '', $attr['id'] );
        $description = get_post_field( 'post_content', $id );

        ob_start();

        ?>

        <div class="card" style="max-width: <?php echo $attr['width']; ?>px;">
            <div class="card-image">
                <?php echo $content; ?>
            </div>
            <div class="card-content">
                <p><?php echo $attr['caption']; ?></p>
                <?php if ( !empty( $description ) ) : ?>
                    <p class="description"><?php echo $description; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php

        return ob_get_clean();
    }

    /**
     *
     * @since 1.0
     */
    function enqueue_scripts() {
        wp_enqueue_style( 'materialize-cards', $this->plugin_url . "public/styles/cards.css", array(), '1.0' );
    }
}

?>
