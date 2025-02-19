<?php
// Path: wp-content/plugins/wp2-style/src/Syncs/Block/init.php

namespace WP2_Style\Syncs\Block;

use WP2_Style\Helpers\Block\Controller as BlockHelper;

class Controller
{

    private $destination_path = WP_CONTENT_DIR . '/themes/wp2/blockstudio/types/';

    /**
     * Executes the actual sync process.
     *
     * This method should be called by something like the scheduled action callback.
     *
     * @return void
     */
    public function execute_sync(): void
    {
        error_log('Executing block sync...');
        $block_helper = new BlockHelper();
        $blocks = $block_helper->get_blocks();

        foreach ($blocks as $block) {
            $this->sync_block($block);
        }
    }

    /**
     * Syncs a single block.
     *
     * @param array $block
     * @return void
     */
    private function sync_block(array $block): void
    {
        $this->create_block_json($block);
        $this->create_index_php($block);
        $this->create_readme_md($block);
    }

    /**
     * Creates the block.json file.
     *
     * @param array $block
     * @return void
     */
    private function create_block_json(array $block): void
    {
        $file_path = $this->destination_path . $block['slug'] . '/block.json';
        $this->create_file($file_path, $block['block_json']);
    }

    /**
     * Creates the index.php file.
     *
     * @param array $block
     * @return void
     */
    private function create_index_php(array $block): void
    {
        $file_path = $this->destination_path . $block['slug'] . '/index.php';
        $this->create_file($file_path, $block['index_php']);
    }

    /**
     * Creates the readme.md file.
     *
     * @param array $block
     * @return void
     */
    private function create_readme_md(array $block): void
    {
        $file_path = $this->destination_path . $block['slug'] . '/README.md';
        $this->create_file($file_path, $block['readme_md']);
    }

    private function create_file(string $file_path, string $contents): void
    {
        // Ensure the directory exists
        $dir = dirname($file_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // Write contents to the file
        file_put_contents($file_path, $contents);
    }
}