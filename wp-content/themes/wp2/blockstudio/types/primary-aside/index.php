<?php
// Path: wp-content/themes/wp2/blockstudio/types/PrimaryAside/index.php

namespace WP2\Blocks\PrimaryAside;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-primary-aside'
);
echo $inner_blocks;