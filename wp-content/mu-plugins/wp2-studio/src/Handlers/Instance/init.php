<?php

namespace WP2_Daemon\WP2_Studio\Handlers\Instance;

class Controller
{
    private $transient_name       = 'wp2_studio_instances';
    private $transient_expiration = 600; // 10 minutes

    public function __construct()
    {
        add_action('init', [$this, 'initialize_instances'], 100);
    }

    public function initialize_instances()
    {
        // Check and clear stale directories using transient data before proceeding.
        $this->clear_stale_instances();

        $directories = $this->get_directory_data();
        $directories = $this->filter_disabled_directories($directories);

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                \Blockstudio\Build::init([
                    'dir' => $dir,
                ]);
            } else {
                error_log("[WP2 Instance] Registered directory '{$dir}' no longer exists.");
            }
        }
    }

    public function get_directory_data()
    {
        $directories = get_transient($this->transient_name);
        return is_array($directories) ? $directories : [];
    }

    public function add_new_directory(string $dir)
    {
        $directories = $this->get_directory_data();

        $dir = trailingslashit($dir);
        if (!in_array($dir, $directories)) {
            $directories[] = $dir;
            $directories = array_unique($directories);
            set_transient($this->transient_name, $directories, $this->transient_expiration);
        }
    }

    public function register_directories(array $directories)
    {
        foreach ($directories as $dir) {
            $this->register_directory($dir);
        }
    }

    public function register_directory(string $dir): bool
    {
        $dir = trailingslashit($dir);

        if (empty($dir)) {
            error_log('[WP2 Instance] Empty directory passed for registration.');
            return false;
        }

        if (!is_dir($dir)) {
            error_log("[WP2 Instance] Directory '{$dir}' does not exist. Registration skipped.");
            return false;
        }

        $this->add_new_directory($dir);
        return true;
    }

    private function plugin_filter(array $directories): array
    {
        $active_plugins = get_option('active_plugins', []);

        $active_plugin_dirs = array_map(function ($plugin) {
            $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;
            return trailingslashit(dirname($plugin_path));
        }, $active_plugins);

        return array_filter($directories, function ($dir) use ($active_plugin_dirs) {
            if (strpos($dir, WP_PLUGIN_DIR) !== 0) {
                return false;
            }

            $dir_trail = trailingslashit($dir);

            foreach ($active_plugin_dirs as $active_dir) {
                if (strpos($dir_trail, $active_dir) === 0) {
                    return false;
                }
            }

            return true;
        });
    }

    private function theme_filter(array $directories): array
    {
        $active_stylesheet = get_option('stylesheet');
        $active_template   = get_option('template');

        $active_theme_dirs = [];
        if ($active_stylesheet) {
            $active_theme_dirs[] = trailingslashit(WP_CONTENT_DIR . '/themes/' . $active_stylesheet);
        }
        if ($active_template && $active_template !== $active_stylesheet) {
            $active_theme_dirs[] = trailingslashit(WP_CONTENT_DIR . '/themes/' . $active_template);
        }

        $themes_base = WP_CONTENT_DIR . '/themes/';

        return array_filter($directories, function ($dir) use ($active_theme_dirs, $themes_base) {
            if (strpos($dir, $themes_base) !== 0) {
                return false;
            }

            $dir_trail = trailingslashit($dir);
            foreach ($active_theme_dirs as $active_dir) {
                if (strpos($dir_trail, $active_dir) === 0) {
                    return false;
                }
            }
            return true;
        });
    }

    private function filter_disabled_directories(array $directories): array
    {
        $disabled_plugin_dirs = $this->plugin_filter($directories);
        $disabled_theme_dirs  = $this->theme_filter($directories);
        $exclusions = array_unique(array_merge($disabled_plugin_dirs, $disabled_theme_dirs));

        return array_filter($directories, function ($dir) use ($exclusions) {
            $dir_trail = trailingslashit($dir);
            foreach ($exclusions as $exclude) {
                if (strpos($dir_trail, $exclude) === 0) {
                    return false;
                }
            }
            return true;
        });
    }

    public function clear_stale_instances()
    {
        $directories = $this->get_directory_data();
        $valid_directories = array_filter($directories, function ($dir) {
            return is_dir($dir);
        });

        if (count($directories) !== count($valid_directories)) {
            set_transient($this->transient_name, array_values($valid_directories), $this->transient_expiration);
            error_log('[WP2 Instance] Cleared stale directories from the registry.');
        }
    }
}

new Controller();