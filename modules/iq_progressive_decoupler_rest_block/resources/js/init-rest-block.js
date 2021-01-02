(function ($) {
  $(document).on('iq-progressive-decoupler-after-init', function (e) {
    Object.keys(drupalSettings.progressive_decoupler).forEach(function(blockId){
      var $target = $('#' + blockId).find('[data-target]');
      var template = Twig.twig({data: drupalSettings.progressive_decoupler[blockId].template});
      var pattern = drupalSettings.progressive_decoupler[blockId].ui_pattern;
      $.get(drupalSettings.progressive_decoupler[blockId].api_endpoint, function(response){
        response.forEach(function(item){
          var $item = $(template.render(item));
          $(document).trigger('iq-progressive-decoupler-after-item-rendered[' + pattern + ']', $item);
          $target.append($item);
        });
        $(document).trigger('iq-progressive-decoupler-after-block-rendered[' + pattern + ']', $target);
      });
    });
  });
}(jQuery));
