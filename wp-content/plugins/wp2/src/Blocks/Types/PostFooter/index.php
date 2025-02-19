<?php
// Path: wp-content/plugins/wp2/src/Blocks/Types/PostFooter/index.php

namespace WP2\Blocks\Types\PostFooter;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'footer',
    'wp2-post-footer'
);
echo $inner_blocks;