<?php

add_filter('tiny_mce_before_init', function($opts) {

    // sets the paste as text btn to on by default, any content pasted into the editor is treated as raw text by default
    $opts['paste_text_use_dialog'] = true;
    $opts['paste_text_sticky'] = true;
    $opts['paste_auto_cleanup_on_paste'] = true;
    $opts['paste_text_use_dialog'] = false;

    // strip any styles from pasted content
    $opts['paste_remove_styles'] = true;

    // only allow basic controls on the toolbar and remove the 2nd toolbar
    $opts['toolbar1'] = 'bold italic strikethrough | formatselect | alignleft aligncenter alignright | bullist numlist indent outdent | blockquote hr table | link unlink | undo redo';
    $opts['toolbar2'] = '';

    // only allow p's and heading tags
    $opts['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';

    return $opts;
});