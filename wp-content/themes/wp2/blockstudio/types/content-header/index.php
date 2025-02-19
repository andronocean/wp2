<?php
// Path: wp-content/themes/wp2/blockstudio/types/ContentHeader/index.php

namespace WP2\Blocks\ContentHeader;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'header',
    'wp2-content-header'
);
echo $inner_blocks;