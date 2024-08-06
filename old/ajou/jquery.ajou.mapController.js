/**
 * @author Dong-seob Park
 *
 */
(function($) {
  var Queue = function() {
    var _items = [];
    this.enqueue = function(item) {
      _items.push(item);
    };
    this.dequeue = function() {
      if(_items.length > 0) {
        var item = _items[0];
        _items.splice(0,1);
        return item;
      } else {
        return null;
      }
    };
  };

  var Task = function(callback) {
    var self = this;
    
    this.execute = function() {
      if($.isFunction(callback)) {
       callback.apply(self);
      }
    }
  };

  var Schedular = function() {
    var _queue = new Queue();
    var self = this;
    var _isExecute = false;

    this.addTask = function(task) {
      _queue.enqueue(task);
    };
    
    this.execute = function(timesec) {
      _isExecute = true;
      window.setTimeout(function() {
        var task = _queue.dequeue();
        if(task != null) {
          task.execute();
        }
        if(_isExecute) {
          self.execute(timesec);
        }
      }, timesec);
    };

    this.stop = function() {
      _isExecute = false;
    }
  };

  $.widget("ajou.map", {
    options: {
      map_type: "",
      options: {},
      _map: {}
    },
    _init: function() {
      var self = this;
      var map_type = self.options.map_type;
      var options = self.options.options;
      var map = self.options._map = $(self.element);

      if(typeof map_type == "string" && map[map_type] != undefined) {
        if(!(typeof options == "object")) {
          options = {};
        }
        map[map_type](options);
      }
    },
    setSize: function(width, height) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("setSize", width, height);
    },
    getSize: function() {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("getSize");
    },
    addPoint: function(point) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;

      return map[map_type]("addPoint", point);
    },
    hasPoint: function(point) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("hasPoint", point);
    },
    getPoints: function() {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      return map[map_type]("getPoints");
    },
    getPointTimestamps: function() {
      var self = this;
      var ts = []
      var map = self.options._map;
      var map_type = self.options.map_type;
      return map[map_type]("getPointTimestamps");
    },
    updatePoint: function(point) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      return map[map_type]("updatePoint", point);
    },
    removePoint: function(point) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
    
      return map[map_type]("removePoint", point);
    },
    addMovement: function(movement) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;

      return map[map_type]("addMovement", movement);
    },
    hasMovement: function(movement) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("hasMovement", movement);
    },
    getMovements: function() {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      return map[map_type]("getMovements");
    },
    getMovementIds: function() {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      return map[map_type]("getMovementIds");
    },
    updateMovement: function(movement) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("updateMovement", movement);
    },
    removeMovement: function(movement) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("removeMovement", movement);
    },
    clear: function() {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      
      return map[map_type]("clear");
    },
    setBound: function(points) {
      var self = this;
      var map = self.options._map;
      var map_type = self.options.map_type;
      if(points == undefined || points.constructor != Array) {
        points = [];
      }
      
      return map[map_type]("setBound", points);
    }
  });
  $.widget("ajou.mapController", {
    options: {
      url: "",
      params: {},
      map_prefix: "map_",
      schedular_time_sec: 1000,
      on_initialize: function(map_list) {},
      _schedular: {},
      _maps: {},
      _container: {},
      _container_id: "",
      _previous_received_data: null
    },
    _create: function() {
      var self = this;
			var container = self.options._container = $(self.element);
			var container_id = self.options._container_id = container.attr("id");
    },
    _init: function() {
      var self = this;
      var schedular = self.options._schedular = new Schedular();

      var ajax_task = self._create_ajax_task();
      schedular.addTask(ajax_task);
      schedular.execute(self.options.schedular_time_sec);
    },
    _create_ajax_task: function() {
      var self = this;
      var schedular = self.options._schedular;
      var task = new Task(
        function() {
          $.ajax({
            url: self.options.url,
            data: self.options.params,
            dataType: "json"
          }).success(function(data, text, jqXHR) {
            //if it is first data
            if(self.options._previous_received_data == null) {
              $.each(data, function(i) {
                var map = data[i];
                self._add_map(map);
              });
              if($.isFunction(self.options.on_initialize)) {
                var maps = self.options._maps;
                var map_list = [];
                for(var map_id in maps) {
                  var map = maps[map_id];
                  map_list.push(map);
                }
                self.options.on_initialize.apply(self, [map_list]);
              }
            //if it is not first data
            } else {
              //add, update maps and aggregate map_id
              var map_ids = [];
              $.each(data, function(i) {
                var map = data[i];
                map_ids.push(map.id);
                
                if(self._has_map(map.id)) {
                  self._update_map(map);
                } else {
                  self._add_map(map);
                }
              });
              //remove maps
              var maps = self.options._maps;
              for(var map_id in maps) {
                if(-1 == $.inArray(parseInt(map_id), map_ids)) {
                  alert("remove");
                  self._remove_map(map_id);
                }
              }
            }
            self.options._previous_received_data = data;
          }).error(function(jqXHR, textStatus, errorThrown) {
            alert("Error : Can't access to URL(" + self.options.url + ")");
          });
          schedular.addTask(task);
        }
      );
      return task;
    },
    _has_map: function(map_id) {
      var self = this;
      if(self.options._maps[map_id] != undefined) {
        return true;
      } else {
        return false;
      }
    },
    _add_map: function(map_info) {
      var self = this;
      var container = self.options._container;
      
      var map_element_id = self.options.map_prefix + map_info.id;

      var map = $("<div></div>");
      map.attr("id", map_element_id);
      container.append(map);

      self.options._maps[map_info.id] = map;
    },
    _update_map: function(map_info) {
      var self = this;
      var map = self.options._maps[map_info.id];
      var points = map_info.points;
      var point_timestamps = [];
      $.each(points, function(i) {
        point_timestamps.push(points[i].t);
        if(!map.ajouMap("hasPoint", points[i])) {
          map.ajouMap("addPoint", points[i]);
        } else {
          map.ajouMap("updatePoint", points[i]);
        }
      });
      
      var map_point_timestamps = map.ajouMap("getPointTimestamps");
      $.each(map_point_timestamps, function(i) {
        var t = map_point_timestamps[i];
        if(-1 == $.inArray(t, point_timestamps)) {
          map.ajouMap("removePoint", {t: t});
        }
      });
      
      var movements = map_info.movements;
      var movement_ids = [];
      $.each(movements, function(i) {
        movement_ids.push(movements[i].id);
        if(!map.ajouMap("hasMovement", movements[i])) {
          map.ajouMap("addMovement", movements[i]);
        } else {
          map.ajouMap("updateMovement", movements[i]);
        }
      });
      
      var map_movement_ids = map.ajouMap("getMovementIds");
      $.each(map_movement_ids, function(i) {
        var movement_id = map_movement_ids[i];
        if(-1 == $.inArray(parseInt(movement_id), movement_ids)) {
          map.ajouMap("removeMovement", {id: movement_id});
        }
      });

    },
    _remove_map: function(map_id) {
      var self = this;
      var map_element_id = self.options.map_prefix + map_id;

      $("#" + map_element_id).remove();

      delete self.options._maps[map_id];
    }
  });
	$.widget.bridge("ajouMap", $.ajou.map);
	$.widget.bridge("ajouMapController", $.ajou.mapController);
})(jQuery); 
