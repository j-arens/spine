<div id="js-search-modal" class="search-modal search-modal--is-hidden">
    <button class="search-modal__toggle js-search-toggle">
        <?= get_template_part('svg/close') ?>
        Close
    </button>
    <div class="search-modal__container">
        <?= get_search_form() ?>
    </div>
</div>