<?php
// Path: wp-content/plugins/wp2/src/Blocks/Types/PostHeader/index.php

namespace WP2\Blocks\Types\PostHeader;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'header',
    'wp2-post-header'
);
echo $inner_blocks;