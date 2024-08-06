/**
 * @author rpkim
 *
 */
(function($) {
	$.widget('google.maps', {
    _context: {
			container_id: "",
      map_id: "",
      map: {}
		},
		_ui: {
			container: {},
      map: {}
		},
		options: {
      width: 500,
      height: 500
		},
		_create: function() {
			var self = this;
			var container = self._ui.container = $(self.element);
			var container_id = self._context.container_id = container.attr("id");
            var map_id = self._context.map_id = container_id + "_map";
			var map = self._ui.map = $("<div/>").attr("id", map_id).appendTo(container);
		},
		_init: function() {
		
      var self = this;
   
      var myOptions = {
      zoom: 8,
      center: new google.maps.LatLng(-34.397, 150.644)
      ,mapTypeId: google.maps.MapTypeId.HYBRID
     };
 
      var map = self._context.map = new google.maps.Map(self._context.map_id,myOptions);   
	},
    setSize: function(width, height) {
      this._context.map.setSize({width: width, height: height});
    },
    getSize: function() {
      return this._context.map.getSize();
    }
	});
	$.widget.bridge("googleMaps", $.google.maps);
})(jQuery);
