/**
 * @author Dong-seob Park
 *
 */

(function($) {
	$.widget('naver.map', {
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
      var map = self._context.map = new nhn.api.map.Map(self._context.map_id, {
        size: new nhn.api.map.Size(self.options.width, self.options.height)
      });
		},
    setSize: function(width, height) {
      this._context.map.setSize({width: width, height: height});
    },
    getSize: function() {
      return this._context.map.getSize();
    }
	});
	$.widget.bridge("naverMap", $.naver.map);
})(jQuery);
