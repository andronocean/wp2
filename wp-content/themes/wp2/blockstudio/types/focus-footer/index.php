<?php
// Path: wp-content/themes/wp2/blockstudio/types/FocusFooter/index.php

namespace WP2\Blocks\FocusFooter;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'footer',
    'wp2-focus-footer'
);
echo $inner_blocks;