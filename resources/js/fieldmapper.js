/**
 * Field mapper class for rendering patterns.
 *
 * @constructor
 * @param {Object} item - object containing the data to be rendered
 * @param {Object} mapping  - object containing the mapping
 */

iq_progessive_decoupler_FieldMapper = function (item, mapping) {
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
   * Apply mapping to item
   */
  self.mapField = function (item, mapping) {
    if (mapping.type == 'static') {
      return mapping.value;
    }
    if (mapping.type == 'array') {
      var arrayItem = eval(mapping.value);
      arrayItem = arrayItem.map(function(item){
        return self.applyMappging(item, mapping.mapping);
      });
      return arrayItem;
    }
    return eval(mapping.value);
  };

}
