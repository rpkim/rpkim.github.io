(function($){
	$.widget('ajoumap.DaumMap1',{
		options:{
			width : 500,
			height : 500,
			_container_id: "",
			_container: {},
			_map_id: "",
			_map: {},
			_label: {},
			_points: {},
			_movements: {}
		},

		_create:function(){
			var self = this;
			var container = self.options._container = $(self.element);
			var container_id = self.options._container_id = container.attr("id");
	        var map_id = self.options._map_id = container_id + "_map";
			var map = self.options._map = $("<div/>").attr("id", map_id).appendTo(container);

			//div¸¦ °¡Á®¿È.
			var self = this;	
			$("#"+map_id).css("height",self.options.height).css("width",self.options.width);

			var position = new daum.maps.LatLng(37.537123, 127.005523);

			var map = new daum.maps.Map(document.getElementById(map_id), {
				center: position,
				level: 4,
				mapTypeId: daum.maps.MapTypeId.HYBRID
			});

			var marker = new daum.maps.Marker({
				position: position
			});

			marker.setMap(map);

			var infowindow = new daum.maps.InfoWindow({
				content: 'Hello, World!'
			});
			infowindow.open(map, marker);
		},

		_init:function(){
		

		},



		destory:function(){
			//return Resource
			$.Widget.prototype.destroy.apply(this,arguments);	//default destory
		},
		
	});
})(jQuery);