<?php
// Path: wp-content/plugins/wp2/src/Themes/Syncs/Entity/init.php

namespace WP2_Style\Syncs\Entity;

use WP2\Helpers\Event\ActionScheduler\Controller as ActionScheduler;
use WP2_Style\Helpers\Entity\Controller as EntityController;
use WP2_Style\Helpers\TemplatePart\Controller as TemplatePartController;

class Controller
{
    /**
     * Custom post type for WP2 style entities.
     *
     * @var string
     */
    private $post_type = 'wp2_style_entity';

    /**
     * Executes the actual sync process.
     *
     * This method should be called by the scheduled action callback.
     *
     * @return void
     */
    public function execute_sync(): void
    {
        // Regenerate template parts to ensure our entities are up to date.
        $templatePartController = new TemplatePartController();
        $templatePartController->init(); // This updates the option with new template parts.

        // Now, instantiate the entity controller and re-read the updated entities.
        $entity_controller = new EntityController();

        $entity_controller->init();

        $entities = $entity_controller->get_entity_posts();

        // Process each entity (e.g., upsert as posts).
        foreach ($entities as $entity) {
            $this->handle_entity_upsert($entity);
        }
    }

    /**
     * Handles the upsert operation for an entity.
     *
     * @param array $payload The payload for the upsert.
     * @return void
     */
    public function handle_entity_upsert($payload)
    {
        $this->handle_upsert($payload);
    }

    /**
     * Upserts an entity post.
     *
     * @param array $process_payload The payload for the upsert.
     * @return void
     */
    protected function handle_upsert($process_payload)
    {
        $post_id    = null;
        $post_type  = $this->post_type;
        $post_name  = 'wp2-style-' . $process_payload['post_name'];
        $post_title = $process_payload['post_title'];
        $post_excerpt = $process_payload['post_excerpt'];

        $post_data = [
            'post_name'    => $post_name,
            'post_type'    => $post_type,
            'post_status'  => 'publish',
            'post_title'   => $post_title,
            'post_content' => '<!-- wp:group {"lock":{"move":true,"remove":true},"align":"full","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull"></div><!-- /wp:group -->',
            'post_excerpt' => $post_excerpt,
        ];

        $meta = [
            'template'      => $process_payload['template'],
            'zone_area'     => $process_payload['zone_area'],
            'zone'          => $process_payload['zone'],
            'area'          => $process_payload['area'],
            'layout'        => $process_payload['layout'],
            'template_part' => $process_payload['template_part'],
        ];

        // Construct meta keys for the upsert operation.
        $post_meta = $this->construct_meta_keys('wp2_style_entity', $process_payload);

        $existing_post = $this->get_by_post_name($post_name);
        if ($existing_post) {
            $post_id         = $existing_post->ID;
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if (is_wp_error($post_id)) {
            error_log("Error upserting: " . $post_id->get_error_message());
            return;
        }

        $this->update_post_meta($post_id, $post_meta);
        $this->determine_taxonomy_terms($post_id, $process_payload);
    }

    /**
     * Retrieves an existing post by its post_name.
     *
     * @param string $post_name The post name to search for.
     * @return \WP_Post|null The found post object or null.
     */
    protected function get_by_post_name($post_name)
    {
        $args = [
            'name'        => $post_name,
            'post_type'   => $this->post_type,
            'post_status' => 'any',
            'numberposts' => 1,
        ];

        $posts = get_posts($args);
        return ! empty($posts) ? $posts[0] : null;
    }

    /**
     * Constructs meta keys for the upsert operation.
     *
     * @param string $prefix The prefix for the meta keys.
     * @param array  $args   The input arguments.
     * @return array Constructed meta key/value pairs.
     */
    protected function construct_meta_keys($prefix, $args)
    {
        $meta = [];
        foreach ($args as $key => $value) {
            $meta[$prefix . '_' . $key] = $value;
        }
        return $meta;
    }

    /**
     * Updates post meta data.
     *
     * @param int   $post_id The post ID.
     * @param array $meta    The meta data to update.
     * @return void
     */
    protected function update_post_meta($post_id, $meta)
    {
        foreach ($meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
    }

    /**
     * Determines taxonomy terms for a post and assigns them.
     *
     * @param int   $post_id The post ID.
     * @param array $payload The payload containing taxonomy data.
     * @return void
     */
    protected function determine_taxonomy_terms($post_id, $payload)
    {
        $taxonomies = [
            'wp2_style_theme_template'  => $payload['template'],
            'wp2_style_theme_part'      => $payload['zone_area'],
            'wp2_style_theme_zone'      => $payload['zone'],
            'wp2_style_theme_area'      => $payload['area'],
            'wp2_style_theme_layout'    => $payload['layout'],
        ];

        foreach ($taxonomies as $taxonomy => $term) {
            wp_set_object_terms($post_id, $term, $taxonomy);
        }
    }
}

// Instantiate the plugin sync controller.
new Controller();