<?php

/**
 * Controller class for handling layouts.
 *
 * This class initializes layout definitions and sets up the appropriate action hook.
 *
 * @package WP2_Style\Helpers\Layout
 */

namespace WP2_Style\Helpers\Layout;

/**
 * Controller class.
 */
class Controller
{

    /**
     * Type of layout.
     *
     * @var string
     */
    private $kind = 'layouts';

    /**
     * Array of layouts.
     *
     * @var array
     */
    private $layouts = [];

    /**
     * Post type for entities.
     *
     * @var string
     */
    private $option_name  = 'wp2_style_entities';

    /**
     * Layout definitions.
     *
     * All layout definitions have been moved here to make editing easier.
     *
     * @var array
     */
    private $layout_definitions = array(
        '404'     => array(
            'name'        => '404',
            'title'       => '404',
            'description' => 'The 404 layout of the site.',
        ),
        'archive' => array(
            'name'        => 'archive',
            'title'       => 'Archive',
            'description' => 'The archive layout of the site.',
        ),
        'home'    => array(
            'name'        => 'home',
            'title'       => 'Home',
            'description' => 'The home layout of the site.',
        ),
        'page'    => array(
            'name'        => 'page',
            'title'       => 'Page',
            'description' => 'The page layout of the site.',
        ),
        'single'  => array(
            'name'        => 'single',
            'title'       => 'Single',
            'description' => 'The single layout of the site.',
        ),
        'search'  => array(
            'name'        => 'search',
            'title'       => 'Search',
            'description' => 'The search layout of the site.',
        ),
    );

    /**
     * Constructor.
     *
     * Adds the 'init' action hook to initialize layout definitions.
     */
    public function __construct()
    {
        $this->get_layouts();

        add_action('init', array($this, 'init'), 104);
    }

    /**
     * Initializes the controller.
     *
     * This method is hooked to WordPress's 'init' action and sets up the layouts.
     *
     * @return void
     */
    public function init()
    {
        $entities = get_option($this->option_name, []);
        $entities[$this->kind] = $this->layouts;
        update_option($this->option_name, $entities);
    }

    /**
     * Retrieves the layout corresponding to a given template.
     *
     * Maps many template names to one layout.
     *
     * @param string $template Template name.
     * @return array|null Layout array if found, null otherwise.
     */
    public function get_template_layout($template)
    {
        // Map many template names to one layout.
        if (
            strpos($template, 'archive') === 0 ||
            strpos($template, 'author') === 0 ||
            strpos($template, 'category') === 0 ||
            strpos($template, 'tag') === 0 ||
            strpos($template, 'taxonomy') === 0 ||
            strpos($template, 'date') === 0
        ) {
            return $this->get_layout('archive');
        }

        if (strpos($template, 'front-page') === 0) {
            return $this->get_layout('home');
        }

        if (strpos($template, 'page') === 0) {
            return $this->get_layout('page');
        }

        if (
            strpos($template, 'index') === 0 ||
            strpos($template, 'single') === 0 ||
            strpos($template, 'attachment') === 0
        ) {
            return $this->get_layout('single');
        }

        if (strpos($template, '404') === 0) {
            return $this->get_layout('404');
        }

        if (strpos($template, 'search') === 0) {
            return $this->get_layout('search');
        }

        // Fallback default.
        return $this->get_layout('single');
    }

    /**
     * Retrieves a specific layout by name.
     *
     * @param string $name Layout name.
     * @return array|null Layout array if found, null otherwise.
     */
    public function get_layout($name)
    {
        if (isset($this->layout_definitions[$name])) {
            return $this->layout_definitions[$name];
        }
        return null;
    }

    /**
     * Retrieves all layouts.
     *
     * @return array Array of layouts.
     */
    public function get_layouts()
    {
        // Set layouts from layout definitions.
        $this->layouts = $this->define_layouts();
        return $this->layouts;
    }

    /**
     * Defines layouts based on the layout definitions.
     *
     * Populates the layouts array using the definitions provided in the
     * layout_definitions property.
     *
     * @return array Array of layouts.
     */
    private function define_layouts()
    {
        $layouts = array();
        foreach ($this->layout_definitions as $layout) {
            $layout['layout_name'] = $layout['name'];
            $layouts[$layout['name']] = $layout;
        }
        return $layouts;
    }
}
$layout_controller = new Controller();