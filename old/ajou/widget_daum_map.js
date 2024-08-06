(function($){
	$.widget('ajoumap.DaumMap',{
		options:{
			width : 500,
			height : 500
		},

		_create:function(){

			//div를 가져옴.
			var self = this;

			var map_id ="daum_map";
	
			//what is this?
			var container = $(self.element);
			//appendTo  what is this?
			var map = $("<div/>").attr("id", map_id).appendTo(container);


			//options에 있는 width값과 height값을 맵에 지정해 준다.
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