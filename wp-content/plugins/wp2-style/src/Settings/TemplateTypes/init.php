<?php

namespace WP2_Style\Settings\TemplateTypes;

class Controller
{
    /**
     * Holds all template type definitions.
     *
     * @var array
     */
    private $template_types = [];

    /**
     * The taxonomy used to retrieve template types.
     *
     * @var string
     */
    private $taxonomy = 'wp2_style_theme_template';

    /**
     * Sets up the filter to override template types.
     *
     * @return void
     */
    public function init()
    {
        // Hook our method into the correct filter.
        add_filter('default_template_types', [$this, 'register_template_types'], 30);
    }

    /**
     * Replaces/merges default template types with taxonomy-based data.
     *
     * @param array $default_template_types The default template types.
     * @return array Updated template types.
     */
    public function register_template_types($default_template_types): array
    {
        // Get taxonomy-based template types.
        $updated_template_types = $this->process_template_types();

        // Merge taxonomy types with the defaults.
        return array_merge($default_template_types, $updated_template_types);
    }

    /**
     * Retrieves all terms for the template type taxonomy.
     *
     * @return array
     */
    public function get_template_type_terms()
    {
        $terms = get_terms($this->taxonomy, [
            'hide_empty' => true,
        ]);

        if (is_wp_error($terms)) {
            return [];
        }
        return $terms;
    }

    /**
     * Processes taxonomy terms into a template types array.
     *
     * Uses the term's slug as the key, term name as the title, and term description.
     *
     * @return array
     */
    private function process_template_types(): array
    {
        $terms = $this->get_template_type_terms();
        $template_types = [];

        foreach ($terms as $term) {
            $template_types[$term->slug] = [
                'title'       => $term->name,
                'description' => $term->description ?? '',
            ];
        }

        $this->template_types = $template_types;
        return $template_types;
    }
}

$controller = new Controller();
$controller->init();