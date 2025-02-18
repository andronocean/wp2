<?php

/**
 * Plugin Name: WP2 Style
 * Description: The style system for WP2. Extends Blockstudio with a modular bootstrapping experience.
 * Version: 1.0
 * Author: WP2S
 *
 * @package WP2_Style
 */

namespace WP2_Style;

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

use WP2_Daemon\WP2_Studio\Handlers\Instance\Controller as StudioController;

/**
 * Class Plugin
 *
 * Main class for the WP2 Style plugin. This class defines required constants,
 * registers directories for block types, and initializes the integration with Blockstudio.
 *
 * @package WP2_Style
 */
class Module
{

    /**
     * Associative array of constants to define.
     *
     * @var array
     */
    private $constants = [];

    /**
     * Directories to be registered with the Studio Controller.
     *
     * @var array
     */
    private $directories = [];

    /**
     * Studio controller instance.
     *
     * @var StudioController|null
     */
    private $studio_controller;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        // Set constant values using plugin_dir_path and get_template_theme functions.
        $this->constants['WP2_STYLE_DIR'] = plugin_dir_path(__FILE__);
        $this->constants['WP2_STYLE_URL'] = plugin_dir_url(__FILE__);
        $this->constants['WP2_THEME_DIR'] = get_template_directory();
        $this->constants['WP2_THEME_URL'] = get_template_directory_uri();

        // Define the constants.
        $this->define_constants();

        // Define directories.
        $this->define_directories();

        // Initialize the plugin functionality.
        add_action('init', [$this, 'init_module'], 110);
    }

    /**
     * Defines directories to be registered with the Studio Controller.
     * 
     * @return void
     */
    private function define_directories(): void
    {
        $this->directories = [
            WP2_STYLE_DIR . '/src/Blocks/Extensions',
            WP2_STYLE_DIR . '/src/Blocks/Types',
            WP2_STYLE_DIR . '/src/Helpers',
            WP2_STYLE_DIR . '/src/Syncs',
            WP2_STYLE_DIR . '/src/Settings',
            WP2_STYLE_DIR . '/src/Types',
        ];
    }

    /**
     * Defines required constants.
     *
     * @return void
     */
    private function define_constants(): void
    {
        foreach ($this->constants as $name => $value) {
            if (! defined($name)) {
                define($name, $value);
            }
        }
    }

    /**
     * Initializes the plugin functionality.
     *
     * This method instantiates the Studio Controller and registers directories
     * containing block definitions. It is called on the 'init' hook.
     *
     * @return void
     */
    public function init_module(): void
    {
        // Instantiate the Studio Controller.
        $this->studio_controller = new StudioController();

        // Register directories with the Studio Controller.
        $this->studio_controller->register_directories($this->directories);
    }
}

new Module();