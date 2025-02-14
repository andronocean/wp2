        <?php
        // Path: wp-content/plugins/wp2-directory/src/Catalogs/Integrations/Miro/index.php

        namespace WP2_Directory\Catalogs\Integrations\Miro;

        $inner_blocks = sprintf(
            '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
            'div',
            'wp2-listing wp2-listing--integration'
        );

        echo $inner_blocks;
