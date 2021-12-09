/**
 * Field mapper class for rendering patterns.
 *
 * @constructor
 * @param {Object} item - object containing the data to be rendered
 * @param {Object} mapping  - object containing the mapping
 */

iq_progressive_decoupler_FieldMapper = function (item, mapping) {
  var self = this;
  self.item = item;
  self.mapping = mapping;

  /**
   * Apply mapping to item
   */
  self.applyMappging = function (item = self.item, mapping = self.mapping) {
    let output = {};
    Object.keys(mapping).forEach(function(key){
      output[key] = self.mapField(item, mapping[key]);
    });
    return output;
  };

  /**
   * Map all of the item's fields to values
   */
  self.mapField = function (item, mapping) {
    if (mapping.type == 'static') {
      return mapping.value;
    }
    if (mapping.type == 'array') {
      var arrayItem = self.getObjectValueByPath(item, mapping.value);
      arrayItem = arrayItem.map(function(item){
        return self.applyMappging(item, mapping.mapping);
      });
      return arrayItem;
    }

    if (self.getObjectValueByPath(item, mapping.value)) {
      return self.getObjectValueByPath(item, mapping.value);
    }

    return self.getObjectValueByPath(item, mapping.fallback);

  };

  /**
   * Read object value form path
   */
  self.getObjectValueByPath = function(o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1');
    s = s.replace(/^\./, '');
    var a = s.split('.');
    for (var i = 0, n = a.length; i < n; ++i) {
        var k = a[i];
        if (o && k && k in o) {
            o = o[k];
        } else {
            return;
        }
    }
    return o;
  }

}
