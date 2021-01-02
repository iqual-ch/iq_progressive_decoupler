(function ($) {

  $(document).on('iq-progressive-decoupler-init-twig-functions', function (e) {
    // Some twig filters that are used in pattern
    Twig.extendFilter('t', function (value) {
      return Drupal.t(value);
    });

    Twig.extendFilter('trans', function (value) {
      return Drupal.t(value);
    });

    Twig.extendFilter('render', function (value) {
      if (value && typeof value == 'object') {
        return value[0];
      }
      return value;
    });
  });

}(jQuery));
