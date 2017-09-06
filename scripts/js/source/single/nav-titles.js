(function($) {

  var $menuItemList;

  function observe() {
    if ($menuItemList.get(0)) {
      var observer = new MutationObserver(changeMeta);

      var observerConfig = {
        attributes: true,
        childList: true,
        characterData: true
      }

      observer.observe($menuItemList.get(0), observerConfig);
    }
  }

  function changeMeta() {
    $('.menu-item-').each(function() {
      const $target = $(this);
      $target.find('.item-type').text('Custom Title');
      $target.find('.field-link-target').css({display: 'none'});
      $target.find('.menu-item-data-type').val('title');
    });
  }

  function cacheDom() {
    $menuItemList = $('#menu-to-edit');
  }

  function init() {
    cacheDom();
    changeMeta();
    observe();
  }

  init();

})(jQuery)
