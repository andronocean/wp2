<?php

namespace WP2_Style\Helpers\Entity;

class Controller
{
    private $post_type   = 'wp2_style_entity';
    private $option_name = 'wp2_style_entities';
    private $entities;
    private $posts;
    private $template_parts;

    public function __construct()
    {
        add_action('init', [$this, 'init'], 101);
    }

    public function init()
    {
        $this->entities = get_option($this->option_name, []);
        $posts = $this->prepare_entities_as_posts();
    }

    public function get_entity_posts()
    {
        return $this->posts;
    }

    public function prepare_entities_as_posts()
    {
        $template_parts = $this->entities['template_parts'] ?? [];
        $formatted = $this->format_parts_as_entities($template_parts);
        $this->posts = $formatted;
        return $formatted;
    }

    /**
     * Converts template part data into the entity format expected by the sync process.
     *
     * @param array $template_parts Array of template parts.
     * @return array Array of formatted entity posts.
     */
    private function format_parts_as_entities($template_parts)
    {
        $entities = [];

        foreach ($template_parts as $part) {
            $entities[] = [
                'post_name'     => $part['template_part'] ?? '',
                'post_title'    => $part['title'] ?? '',
                'post_excerpt'  => $part['description'] ?? '',
                'template'      => $part['template'] ?? '',
                'zone_area'     => $part['zone_area'] ?? '',
                'zone'          => $part['zone'] ?? '',
                'area'          => $part['area'] ?? '',
                'layout'        => $part['layout'] ?? '',
                'template_part' => $part['template_part'] ?? '',
            ];
        }

        return $entities;
    }
}

new Controller();