<?php
// Path: wp-content/themes/wp2/blockstudio/types/ContentFooter/index.php

namespace WP2\Blocks\ContentFooter;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'footer',
    'wp2-content-footer'
);
echo $inner_blocks;