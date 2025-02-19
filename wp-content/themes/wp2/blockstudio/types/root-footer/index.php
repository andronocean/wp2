<?php
// Path: wp-content/themes/wp2/blockstudio/types/RootFooter/index.php

namespace WP2\Blocks\RootFooter;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'footer',
    'wp2-root-footer'
);
echo $inner_blocks;