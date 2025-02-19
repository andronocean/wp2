<?php

namespace WP2_Style\Syncs;

use WP2_Style\Syncs\Entity\Controller as EntityController;
use WP2_Style\Syncs\Template\Controller as TemplateSync;
use WP2_Style\Syncs\TemplatePart\Controller as TemplatePartSync;
use WP2_Style\Syncs\Block\Controller as BlockSync;

// Instantiate both sync processes.
new EntityController();
new TemplateSync();
new TemplatePartSync();
new BlockSync();

/**
 * Scheduled callback function for entity sync.
 *
 * This function is hooked to the async action.
 */
function wp2_style_try_entities_sync(): void
{
    $controller = new EntityController();
    $controller->execute_sync();
}

add_action('wp2_style_entities_sync', __NAMESPACE__ . '\\wp2_style_try_entities_sync');

/**
 * Scheduled callback for template sync.
 *
 * @return void
 */
function wp2_style_try_templates_sync(): void
{
    $sync = new TemplateSync();
    $sync->execute_sync();
}

add_action('wp2_style_templates_sync', __NAMESPACE__ . '\\wp2_style_try_templates_sync');

/**
 * Scheduled callback for template part sync.
 *
 * @return void
 */
function wp2_style_try_template_parts_sync(): void
{
    $sync = new TemplatePartSync();
    $sync->execute_sync();
}

add_action('wp2_style_template_parts_sync', __NAMESPACE__ . '\\wp2_style_try_template_parts_sync');


/**
 * Scheduled callback for template part sync.
 *
 * @return void
 */
function wp2_style_try_blocks_sync(): void
{
    $sync = new BlockSync();
    $sync->execute_sync();
}

add_action('wp2_style_blocks_sync', __NAMESPACE__ . '\\wp2_style_try_blocks_sync');


/**
 * Run all syncs. This is used for the initial sync.
 * 
 * @return void
 */
function wp2_style_try_full_sync(): void
{
    wp2_style_try_entities_sync();
    wp2_style_try_templates_sync();
    wp2_style_try_template_parts_sync();
    wp2_style_try_blocks_sync();
}

add_action('wp2_style_full_sync', __NAMESPACE__ . '\\wp2_style_try_full_sync');
