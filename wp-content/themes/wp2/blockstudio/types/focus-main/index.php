<?php
// Path: wp-content/themes/wp2/blockstudio/types/FocusMain/index.php

namespace WP2\Blocks\FocusMain;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-focus-main'
);
echo $inner_blocks;