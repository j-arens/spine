<?php

namespace Spine\scripts\php;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action('admin_head', function() {
    ?>
        <style>
            .field-holder label[for*="carbon-"] {
                background: #f5f5f5;
                display: flex !important;
                align-items: center;
                padding: 1em 0.5em !important;
                margin-bottom: 0.25em;
            }
            .carbon-drag-handle {
                background-color: aliceblue !important;
            }
            .carbon-hide-editor {
                max-height: 0;
                overflow: hidden;
                position: absolute;
            }
        </style>
    <?php
});

add_action('admin_enqueue_scripts', function() {
    $screen = get_current_screen();
    if ($screen->post_type === 'page' && $screen->base === 'post') {
        wp_enqueue_script(
            'carbon-fields-helper-js',
            assetPath('scripts/js/carbonHelper.js'),
            null,
            filemtime(get_template_directory() . '/scripts/js/carbonHelper.js'),
            true
        );
    }
});

// this class should never be instantiated, only extended by other classes creating custom fields
// the methods within this class are meant to automate and remove some of the boilerplate with setting up carbon fields
abstract class CustomFields {

    /**
    * Init, do some magical stuff
    */
    public function __construct() {
        if (!is_admin()) 
            return;

        add_action('wp_default_editor', function() {return 'tinymce';});
        add_action('admin_init', [$this, 'hideEditor']);
        add_action('admin_head', [$this, 'localizeTemplates']);
        $this->registerCallbacks();
    }

    /**
    * Register field funcs with carbon fields
    */
    public function registerCallbacks() {
        foreach(get_class_methods($this) as $method) {
            if (preg_match('/(Fields)$/', $method)) {
                add_action('carbon_register_fields', [$this, $method]);
            }
        }
    }

    /**
    * Create/add to global js var containing all the templates to hide the editor on
    */
    public function localizeTemplates() {
        if (property_exists($this, 'hideEditorOn') && !empty($this->hideEditorOn)) {
            ?>
                <script type="text/javascript">
                    if (!window.hasOwnProperty('carbonTemplates')) window.carbonTemplates = [];
                    window.carbonTemplates.push(<?= json_encode($this->hideEditorOn) ?>);
                </script>
            <?php
        }
    }

    /**
    * Remove support for the mce editor if on a certain template
    */
    public function hideEditor() {
        if (!isset($_GET['post']) || !property_exists($this, 'hideEditorOn') || empty($this->hideEditorOn)) {
            return;
        }

        $template = get_post_meta($_GET['post'], '_wp_page_template', 1);

        if (in_array($template, $this->hideEditorOn)) {
            remove_post_type_support('page', 'editor');
        }
    }
}