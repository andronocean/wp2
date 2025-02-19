<?php
// Path: wp-content/themes/wp2/blockstudio/types/ContentMain/index.php

namespace WP2\Blocks\ContentMain;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'main',
    'wp2-content-main'
);
echo $inner_blocks;