<?php
// Path: wp-content/themes/wp2/blockstudio/types/PrimaryMain/index.php

namespace WP2\Blocks\PrimaryMain;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-primary-main'
);
echo $inner_blocks;