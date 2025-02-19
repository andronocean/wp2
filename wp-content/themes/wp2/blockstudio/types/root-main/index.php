<?php
// Path: wp-content/themes/wp2/blockstudio/types/RootMain/index.php

namespace WP2\Blocks\RootMain;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-root-main'
);
echo $inner_blocks;