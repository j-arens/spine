<?php

// custom fields example

use Carbon_Fields\Container;
use Carbon_Fields\Field;


// hide editor on certain templates
add_action('admin_init', function() {
    if (!isset($_GET['post'])) return;

    $hideOn = [
       'templates/front-page.php'
   ];
   
   $template = get_post_meta($_GET['post'], '_wp_page_template', 1);
    
    if (in_array($template, $hideOn)) {
        remove_post_type_support('page', 'editor');
    }
});

// create fields here
add_action('carbon_register_fields', function() {

    // complex field on homepage template
    Container::make('post_meta', 'Homepage Slider')
        ->show_on_post_type('page')
        ->show_on_template('templates/front-page.php')
        ->add_fields([
            Field::make('complex', 'Slider')
                ->set_max(3)
                ->add_fields('Slide', [
                    Field::make('text', 'Title'),
                    Field::make('image', 'Photo')
                ])
        ]);

});