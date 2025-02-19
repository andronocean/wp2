<?php
// Path: wp-content/themes/wp2/blockstudio/types/RootHeader/index.php

namespace WP2\Blocks\RootHeader;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'header',
    'wp2-root-header'
);
echo $inner_blocks;