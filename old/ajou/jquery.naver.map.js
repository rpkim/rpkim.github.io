/**
 * @author Dong-seob Park
 *
 */

(function($) {
	$.widget('naver.map', {
		options: {
      width: 500,
      height: 500,
      _container_id: "",
      _container: {},
      _map_id: "",
      _map: {},
      _label: {},
      _points: {},
      _movements: {}
		},
		_create: function() {
			var self = this;
			var container = self.options._container = $(self.element);
			var container_id = self.options._container_id = container.attr("id");
      var map_id = self.options._map_id = container_id + "_map";
			var map = self.options._map = $("<div/>").attr("id", map_id).appendTo(container);
		},

		_init: function() {
      var self = this;
      var map = self.options._map = new nhn.api.map.Map(self.options._map_id, {
        size: new nhn.api.map.Size(self.options.width, self.options.height)
      });

      var mapZoom = new nhn.api.map.ZoomControl();
      mapZoom.setPosition({left: 10, bottom: 20});
      map.addControl(mapZoom);

      var mapTypeBtn = new nhn.api.map.MapTypeBtn();
      mapTypeBtn.setPosition({left: 40, bottom: 195});
      map.addControl(mapTypeBtn);

      var label = self.options._label = new nhn.api.map.MarkerLabel();
      map.addOverlay(label)
      map.attach('mouseenter', function(e) {
        if(e.target instanceof nhn.api.map.Marker) {
          label.setVisible(true, e.target);
        }
      });
      map.attach('mouseleave', function(e) {
        if(e.target instanceof nhn.api.map.Marker) {
          label.setVisible(false);
        }
      });
		},
    _createIcon:function(imgSrc) {
      return new nhn.api.map.Icon(imgSrc, new nhn.api.map.Size(28,37), new nhn.api.map.Size(14,37));
    },
    setSize: function(width, height) {
      this.options._map.setSize({width: width, height: height});
    },
    getSize: function() {
      return this.options._map.getSize();
    },
    addPoint: function(point_info) {
      var self = this;
      var map = self.options._map;

      var point = new nhn.api.map.LatLng(point_info.latitude, point_info.longitude);
      var marker = new nhn.api.map.Marker(self._createIcon("http://static.naver.com/maps2/icons/pin_spot2.png"), {title: point_info.desc});
      marker.setPoint(point);
      map.addOverlay(marker);

      self.options._points[point_info.id] = marker;
      //self.options._points[point_info.t] = marker;
    },
    hasPoint: function(point_info) {
      var self = this;
      
      //if(self.options._points[point_info.t] != null) {
      if(self.options._points[point_info.id] != null) {
        return true;
      } else {
        return false;
      }
    },
    getPoints: function() {
      var self = this;
      var points = [];
      for(var t in self.options._points) {
        var point = self.options._points[t];
        points.push(point);
      }
      return points;
    },
    getPointTimestamps: function() {
      var self = this;
      var point_timestamps = [];
      for(var t in self.options._points) {
        point_timestamps.push(t);
      }
      return point_timestamps;
    },
    updatePoint: function(point_info) {
      var self = this;
      
      var marker = self.options._points[point_info.id];
      marker.setTitle(point_info.desc);
      marker.setPoint(new nhn.api.map.LatLng(point_info.latitude, point_info.longitude));

      self.options._points[point_info.id] = marker;
    },
    removePoint: function(point_info) {
      var self = this;
      if(self.hasPoint(point_info)) {
        self.options._map.removeOverlay(self.options._points[point_info.id]);
        delete self.options._points[point_info.id];
      }
    },
    addMovement: function(movement_info) {
      var self = this;
      var map = self.options._map;
      
      var movement = new nhn.api.map.Polyline([], {
        strokeColor: "#F00",
        strokeOpacity: 0.5,
        strokeWidth: 5
      });
      map.addOverlay(movement);

      var max_id = 0;
      var points = [];
      var markers = [];
      $.each(movement_info.points, function(i) {
        var point_info = movement_info.points[i];
        if(point_info.id > max_id) {
          var point = new nhn.api.map.LatLng(point_info.latitude, point_info.longitude);
          var marker = new nhn.api.map.Marker(self._createIcon("http://static.naver.com/maps2/icons/pin_spot2.png"), {title: point_info.desc});
          marker.setPoint(point);
          map.addOverlay(marker);

          points.push(point);
          markers.push(marker);
        }
      });
      var map_points = movement.getPoints();
      map_points = map_points.concat(points);
      movement.setPoints(map_points);

      self.options._movements[movement_info.id] = {
        max_id: max_id,
        polyline:movement,
        markers: markers
      };
    },
    getMovements: function() {
      var self = this;
      var movements = [];
      for(var movement_id in self.options._movements) {
        var movement = self.options._movements[movement_id];
        movements.push(movement);
      }
      return movements;
    },
    getMovementIds: function() {
      var self = this;
      var movement_ids = [];
      for(var movement_id in self.options._movements) {
        movement_ids.push(movement_id);
      }
      return movement_ids;
    },
    updateMovement: function(movement_info) {
      var self = this;
      var map = self.options._map;
      var movement = self.options._movements[movement_info.id];

      var map_points = movement.polyline.getPoints();
      var points = movement_info.points;
      $.each(points, function(i) {
        var point_info = points[i];
        
        if(point_info.id > movement.max_id) {
          var point = new nhn.api.map.LatLng(point_info.latitude, point_info.longitude);
          var marker = new nhn.api.map.Marker(self._createIcon("http://static.naver.com/maps2/icons/pin_spot2.png"), {title: point_info.desc});
          marker.setPoint(point);
          map.addOverlay(marker);

          movement.max_id = point_info.id;
          
          movement.markers.push(marker);
          map_points.push(point);
        }
      });
      movement.polyline.setPoints(map_points);

      self.options._movements[movement_info.id] = movement;
    },
    hasMovement: function(movement_info) {
      var self = this;
      
      if(self.options._movements[movement_info.id] != null) {
        return true;
      } else {
        return false;
      }
    },
    removeMovement: function(movement_info) {
      var self = this;
      if(self.hasMovement(movement_info)) {
        var movement = self.options._movements[movement_info.id];
        $.each(movement.markers, function(i) {
          var marker = movement.markers[i];
          self.options._map.removeOverlay(marker);
        });
        self.options._map.removeOverlay(movement.polyline);
        delete self.options._movements[movement_info.id];
      }
    },
    clear: function() {
      var self = this;
      self.options._map.clearOverlay();
    },
    setBound: function(points) {
      var self = this;
      var bound_points = [];
      if(points == undefined || (points.constructor == Array && points.length == 0)) {
        for(var t in self.options._points) {
          var marker = self.options._points[t];
          var point = marker.getPoint();
          bound_points.push(point);
        }
        for(var id in self.options._movements) {
          var movement = self.options._movements[id].polyline;
          var movement_points = movement.getPoints();
          bound_points = bound_points.concat(movement_points);
        }
      } else {
        $.each(points, function(i) {
          var point_info = points[i];
          var point = new nhn.api.map.LatLng(point_info.latitude, point_info.longitude);
          bound_points.push(point);
        });
      }
      if(bound_points.length > 0) {
        self.options._map.setBound(bound_points);
      }
    }
	});
	$.widget.bridge("naverMap", $.naver.map);
})(jQuery);
