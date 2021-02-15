(function ($) {
  $(document).on('iq-progressive-decoupler-after-init', function (e) {
    Object.keys(drupalSettings.progressive_decoupler).forEach(function(blockId){
      let blockData = drupalSettings.progressive_decoupler[blockId];
      let $target = $('#' + blockId).find('[data-target]');
      let template = Twig.twig({data: blockData.template});
      let pattern = blockData.ui_pattern;
      $.get(blockData.api_endpoint, function(response){
        response.forEach(function(item){
          let fieldMapper = new iq_progressive_decoupler_FieldMapper(item, blockData.field_mapping);
          let $item = $(template.render(fieldMapper.applyMappging()));
          $(document).trigger('iq-progressive-decoupler-after-item-rendered[' + pattern + ']', $item);
          $target.append($item);
        });
        $(document).trigger('iq-progressive-decoupler-after-block-rendered[' + pattern + ']', $target);
      });
    });
  });
}(jQuery));
