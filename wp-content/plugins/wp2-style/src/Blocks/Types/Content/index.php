<?php
// Path: wp-content/plugins/wp2-style/src/Blocks/Types/Content/index.php

namespace WP2_Style\Blocks\Types\Content;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-content'
);
echo $inner_blocks;