<?php

/**
 * Template Controller.
 *
 * This class initializes template definitions and provides methods to retrieve templates.
 *
 * @package WP2_Style\Helpers\Template
 */

namespace WP2_Style\Helpers\Template;

/**
 * Template Controller class.
 */
class Controller
{

    /**
     * Type of template.
     *
     * @var string
     */
    private $kind = 'templates';

    /**
     * Array of templates.
     *
     * @var array
     */
    private $templates = array();

    /**
     * Template definitions.
     *
     * All template definitions have been moved here to make editing easier.
     *
     * @var array
     */
    private $template_definitions = array(
        '404'        => array(
            'name'        => '404',
            'title'       => '404',
            'description' => 'The 404 template of the site.',
        ),
        'archive'    => array(
            'name'        => 'archive',
            'title'       => 'Archive',
            'description' => 'The archive template of the site.',
        ),
        'author'     => array(
            'name'        => 'author',
            'title'       => 'Author',
            'description' => 'The author template of the site.',
        ),
        'attachment' => array(
            'name'        => 'attachment',
            'title'       => 'Attachment',
            'description' => 'The attachment template of the site.',
        ),
        'date'       => array(
            'name'        => 'date',
            'title'       => 'Date',
            'description' => 'The date template of the site.',
        ),
        'front-page' => array(
            'name'        => 'front-page',
            'title'       => 'Front Page',
            'description' => 'The front page template of the site.',
        ),
        'index'      => array(
            'name'        => 'index',
            'title'       => 'Index',
            'description' => 'The index template of the site.',
        ),
        'page'       => array(
            'name'        => 'page',
            'title'       => 'Page',
            'description' => 'The page template of the site.',
        ),
        'taxonomy'   => array(
            'name'        => 'taxonomy',
            'title'       => 'Taxonomy',
            'description' => 'The taxonomy template of the site.',
        ),
        'search'     => array(
            'name'        => 'search',
            'title'       => 'Search',
            'description' => 'The search template of the site.',
        ),
        'single'     => array(
            'name'        => 'single',
            'title'       => 'Single',
            'description' => 'The single template of the site.',
        ),
    );

    /**
     * Constructor.
     *
     * Initializes template definitions and adds the 'init' action hook.
     */
    public function __construct()
    {
        $this->define_templates();
        add_action('init', array($this, 'init'), 101);
    }

    /**
     * Initializes the controller.
     *
     * This method is hooked to WordPress's 'init' action.
     *
     * @return void
     */
    public function init() {}

    /**
     * Defines templates based on the template definitions.
     *
     * Populates the $templates property using the definitions provided in the
     * $template_definitions property. It also sets the 'template_name' key for each template.
     *
     * @return void
     */
    private function define_templates()
    {
        foreach ($this->template_definitions as $template) {
            $template['template_name']         = $template['name'];
            $this->templates[$template['name']] = $template;
        }
    }

    /**
     * Retrieves all templates.
     *
     * @return array Array of templates.
     */
    public function get_templates()
    {
        return $this->templates;
    }
}

new Controller();