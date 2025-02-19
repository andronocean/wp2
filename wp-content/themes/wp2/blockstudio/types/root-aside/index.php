<?php
// Path: wp-content/themes/wp2/blockstudio/types/RootAside/index.php

namespace WP2\Blocks\RootAside;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-root-aside'
);
echo $inner_blocks;