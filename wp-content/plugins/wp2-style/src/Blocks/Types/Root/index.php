<?php
// Path: wp-content/plugins/wp2-style/src/Blocks/Types/Root/index.php

namespace WP2_Style\Blocks\Types\Root;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-root'
);
echo $inner_blocks;