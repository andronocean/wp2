        <?php
        // Path: wp-content/plugins/wp2-directory/src/Catalogs/Flows/Welcome/index.php

        namespace WP2_Directory\Catalogs\Flows\Welcome;

        $inner_blocks = sprintf(
            '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
            'div',
            'wp2-listing wp2-listing--flow'
        );

        echo $inner_blocks;
