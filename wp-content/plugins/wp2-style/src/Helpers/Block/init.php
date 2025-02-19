<?php
// Path: wp-content/plugins/wp2-style/src/Helpers/Block/init.php

namespace WP2_Style\Helpers\Block;

class Controller
{
    private $taxonomy = 'wp2_style_theme_part';

    /**
     * Get all terms from the taxonomy once processed.
     * 
     * @return array
     */
    public function get_blocks(): array
    {
        $terms = get_terms([
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms)) {
            return [];
        }

        $terms = array_filter($terms, function ($term) {
            return $term->slug !== 'undefined';
        });

        $blocks = $this->process_terms_as_blocks($terms);

        return $blocks;
    }


    /**
     * Process the terms as blocks.
     *
     * @param array $terms
     * @return array
     */
    private function process_terms_as_blocks(array $terms): array
    {
        $blocks = [];

        foreach ($terms as $term) {
            $order = get_term_meta($term->term_id, 'wp2_style_order', true);
            $blocks[] = [
                'id'          => $term->term_id,
                'name'        => $term->name,
                'slug'        => $term->slug,
                'description' => $term->description,
                'order'       => $order,
                'block_json'  => $this->generate_block_json($term),
                'index_php'   => $this->generate_index_php($term),
                'readme_md'   => $this->generate_readme_md($term),
            ];
        }

        return $blocks;
    }


    /**
     * Generate the block.json file content.
     *
     * @param object $term
     * @return string
     */
    private function generate_block_json(object $term): string
    {
        $block_json = [
            '$schema'    => 'https://app.blockstudio.dev/schema',
            'apiVersion' => 2,
            'name'       => 'wp2/' . $term->slug,
            'title'      => $term->name,
            'category'   => 'wp2-style',
            'icon'       => 'align-full-width',
            'description' => $term->description,
            'parent'     => ['core/template-part'],
            'attributes' => [
                'className' => [
                    'type'    => 'string',
                    'default' => '',
                ],
                'metadata'  => [
                    'type'    => 'object',
                    'default' => [
                        'name' => $term->name,
                    ],
                ],
                'lock'      => [
                    'type'    => 'object',
                    'default' => [
                        'move'   => true,
                        'remove' => true,
                    ],
                ],
                'layout'    => [
                    'type'    => 'object',
                    'default' => [
                        'type' => 'constrained',
                    ],
                ],
                'align'     => [
                    'type'    => 'string',
                    'default' => 'full',
                ],
            ],
            'supports' => [
                'className'      => false,
                'customClassName' => false,
                'renaming'       => true,
                'reusable'       => false,
                'lock'           => true,
                'html'           => false,
                'inserter'       => false,
                'multiple'       => false,
                'align'          => [
                    'full',
                ],
            ],
            'blockstudio' => [
                "attributes" => [
                    [
                        "id"         => "option",
                        "type"       => "select",
                        "label"      => "Option",
                        "allowNull"  => "Select Option",
                        "options"    => [
                            [
                                "label"       => "Default",
                                "value"       => "0",
                                "innerBlocks" => [
                                    [
                                        "name"       => "core/group",
                                        "attributes" => [
                                            "metadata" => [
                                                "name" => "Default",
                                            ],
                                            "layout"   => [
                                                "type" => "constrained",
                                            ],
                                            "align"    => "full",
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return json_encode($block_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Generate the index.php file content.
     *
     * @param object $term
     * @return string
     */
    private function generate_index_php(object $term): string
    {

        $term_name = str_replace(' ', '', $term->name);

        $term_slug = sanitize_title($term->slug);

        $html_tag = get_term_meta($term->term_id, 'wp2_style_html_tag', true);

        $contents = <<<PHP
<?php
// Path: wp-content/themes/wp2/blockstudio/types/{$term_name}/index.php

namespace WP2\Blocks\\{$term_name};

\$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    '{$html_tag}',
    'wp2-{$term_slug}'
);
echo \$inner_blocks;
PHP;

        return $contents;
    }

    /**
     * Generate the readme.md file content.
     *
     * @param object $term
     * @return string
     */
    private function generate_readme_md(object $term): string
    {
        // Provided two placeholders in the format string; using term description for the second placeholder.
        $readme_md = sprintf(
            "# %s\n\n%s\n",
            $term->name,
            $term->description
        );

        return $readme_md;
    }
}