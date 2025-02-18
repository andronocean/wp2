<?php
// Path: wp-content/plugins/wp2-style/src/Blocks/Types/Focus/index.php

namespace WP2_Style\Blocks\Types\Focus;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-focus'
);
echo $inner_blocks;