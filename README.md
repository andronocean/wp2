# WP2

> [!CAUTION]
> This is a work in progress. The information provided is subject to change and the project is not yet ready for production use.

## Overview

WP2 is an innovative project designed to simplify the creation and management of WordPress websites. Built on top of the WordPress platform and Blockstudio, WP2 offers a powerful yet straightforward environment for developing custom solutions using PHP-based blocks.

## Project Structure

WP2 mirrors the structure of WordPress core by combining the wp2 repository with a wp-content folder. Every new solution is built as a module that spans across the mu-plugins, plugins, themes, or uploads folders. Together, these elements form the blueprint for a comprehensive, turnkey WordPress solution.

## Usage

WP2 is optimized for one-click installation and cloning experiences. WP2 Modules can be initialized and configured to meet your organization’s specific needs, offering a robust boilerplate that serves as the best starting point for any project. This approach allows you to hit the ground running while still enabling further customization as your project evolves.

## Features

- **Modular Architecture:** WP2 Modules are self-contained, reusable pieces of functionality that can be easily added or removed from your WordPress site, ensuring scalability and maintainability.
- **PHP-Only Block Creation:** Develop custom WordPress blocks using PHP with the standardized block.json format.
- **Core Focused:** Utilize WordPress core and Gutenberg components without any hidden complexities.
- **Server-Side Rendering:** Benefit from PHP templates for dynamic blocks, while enjoying enhanced editor interactivity through JSX-like tags.
- **File System-Based Registration:** Automatically register blocks and their assets (CSS, JS, templates) based on a structured file system.
- **Zero-Setup Workflow:** Seamlessly enqueue assets and support various template engines (e.g., Twig) by simply renaming file extensions.
- **Customizable:** Tailor the boilerplate to your organization’s needs, ensuring a perfect fit for your projects.
- **Documentation:** Access comprehensive documentation directly from the WordPress admin interface.
- **Version Control:** Easily manage and track changes to your modules using Git.

## Documentation

Each directory within WP2 comes with its own Markdown-based README.md file to keep documentation current and easily accessible. Additionally, the WP2 Wiki module aggregates this documentation into a virtualized format that you can access directly from WordPress.

Explore the project by visiting each directory and reviewing the corresponding README.md files for detailed insights into every module and feature.

## Contact

Contact Vinny Green for more information on how to get started with WP2.

## Sponsor

If you find WP2 useful, please consider sponsoring the project to help support its development and maintenance.
