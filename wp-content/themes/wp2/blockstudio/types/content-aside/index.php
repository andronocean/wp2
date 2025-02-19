<?php
// Path: wp-content/themes/wp2/blockstudio/types/ContentAside/index.php

namespace WP2\Blocks\ContentAside;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-content-aside'
);
echo $inner_blocks;