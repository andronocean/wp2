<?php
// Path: wp-content/themes/wp2/blockstudio/types/FocusAside/index.php

namespace WP2\Blocks\FocusAside;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-focus-aside'
);
echo $inner_blocks;