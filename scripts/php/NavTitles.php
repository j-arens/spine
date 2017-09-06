<?php

namespace Spine\scripts\php;

class NavTitles {

  public function __construct() {
    add_action('admin_init', [$this, 'addNavMetaBox']);
  }

  public function addNavMetaBox() {
    \add_meta_box(
      'custom_menu_heading',
      __('Menu Titles'),
      [$this, 'navMenuCb'],
      'nav-menus',
      'side',
      'core'
    );
  }

  public function navMenuCb() {
    wp_enqueue_script( 'dpi-nav-meta-js', get_template_directory_uri() . '/scripts/js/nav-titles.js', ['jquery'], true );
    ?>
    <div id="customheadingdiv" class="taxonomydiv">
  		<div class="tabs-panel tabs-panel-active">
  			<ul class="categorychecklist form-no-clear">
  				<li>
  					<label class="menu-item-title">
  						<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1">Add A Custom Title
  					</label>
  				</li>
  			</ul>
  		</div>
  		<p class="button-controls">
  			<span class="add-to-menu">
  				<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-customheadingdiv">
  				<span class="spinner"></span>
  			</span>
  		</p>
  	</div>
    <?php
  }
}

if (is_admin()) {
  global $pagenow;
  
  if ($pagenow === 'nav-menus.php') {
    new NavTitles();
  }
}
