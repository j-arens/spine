<?php

namespace Spine\Scripts\PHP;

class CustomLogin {

    private $logoLink;
    private $pathToStylesheet;

    public function setLogoLink($str) {
        if (gettype($str) === 'string') {
            $this->logoLink = $str;
        } else {
            throw new Exception('DPI_CUSTOM_LOGIN: setLogoLink expects a string. You passed a(n)' . gettype($str) . '.');
            return false;
        }
    }

    public function setPathToStylesheet($str) {
        if (gettype($str) === 'string') {
            $this->pathToStylesheet = $str;
        } else {
            throw new Exception('DPI_CUSTOM_LOGIN: setStylesheetPath expects a string. You passed a(n)' . gettype($str) . '.');
            return false;
        }
    }

    public function init() {
        add_filter('login_headerurl', [$this, 'customizeLogoLink']);
        add_action('login_enqueue_scripts', [$this, 'loadLoginStylesheet']);
    }

    public function customizeLogoLink() {
        return esc_url($this->logoLink);
    }

    public function loadLoginStylesheet() {
        if (!empty($this->pathToStylesheet)) {
            echo '<style type="text/css">' . file_get_contents($this->pathToStylesheet) . '</style>';
        } else {
            return false;
        }
    }
}

add_action('init', function() {
    $customLogin = new CustomLogin();
    $customLogin->setLogoLink('/');
    $customLogin->setPathToStylesheet(get_template_directory() . '/login.css');
    $customLogin->init();
});
