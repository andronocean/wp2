<?php
// Path: wp-content/plugins/wp2/src/Blocks/Types/PostContent/index.php

namespace WP2\Blocks\Types\PostContent;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-post-content'
);
echo $inner_blocks;