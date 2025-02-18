<?php
// Path: wp-content/plugins/wp2-style/src/Blocks/Types/Primary/index.php

namespace WP2_Style\Blocks\Types\Primary;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-primary'
);
echo $inner_blocks;