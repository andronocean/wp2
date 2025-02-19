<?php
// Path: wp-content/themes/wp2/blockstudio/types/PrimaryHeader/index.php

namespace WP2\Blocks\PrimaryHeader;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'header',
    'wp2-primary-header'
);
echo $inner_blocks;