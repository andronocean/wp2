<?php
// Path: wp-content/themes/wp2/blockstudio/types/FocusHeader/index.php

namespace WP2\Blocks\FocusHeader;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'header',
    'wp2-focus-header'
);
echo $inner_blocks;