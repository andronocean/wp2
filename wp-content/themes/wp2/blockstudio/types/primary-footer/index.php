<?php
// Path: wp-content/themes/wp2/blockstudio/types/PrimaryFooter/index.php

namespace WP2\Blocks\PrimaryFooter;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'footer',
    'wp2-primary-footer'
);
echo $inner_blocks;