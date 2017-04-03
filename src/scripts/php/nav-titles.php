<?php

class DPI_Nav_Meta {

  public function __construct() {
    add_action('admin_init', [$this, 'add_nav_meta_box']);
  }

  public function add_nav_meta_box() {
    add_meta_box(
      'custom_menu_heading',
      __('Menu Titles'),
      [$this, 'nav_menu_cb'],
      'nav-menus',
      'side',
      'core'
    );
  }

  public function nav_menu_cb() {
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

new DPI_Nav_Meta();
