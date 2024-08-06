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
  
  $.widget("ajou.mapProxy", {
    options: {
      map_info:{},
      schedular:{},
      data_provider_url: "",
      data_provider_params: {},
      map_type: "",
      width: 500,
      height: 500,
      _nodes: [],
      _max_marker_id: 0
    },
    _ui: {
      map: {}
    },
    initialize: function(map_type) {
      var self = this;
      self._ui.map = $(self.element);
      self.options.map_type = map_type;

      var map_info = self.options.map_info;
      var schedular = self.options.schedular;

      self._ui.map[map_type]({
        width: self.options.width,
        height: self.options.height
      });

      if(map_info.realtime) {
        var realtime_task = self._create_realtime_task();
        schedular.addTask(realtime_task);
      }
      self.sync();
    },
    addMarker: function(marker_info) {
      var self = this;
      self.options._nodes.push(marker_info);
      self._ui.map[self.options.map_type]('addMarker', marker_info);
    },
    hasMarker: function(marker_id) {
      var self = this;
      return self._ui.map[self.options.map_type]('hasMarker', marker_id);
    },
    removeMarker: function(marker_id) {
      var self = this;
      $.each(self.options._nodes, function(i) {
        if(marker_id == self.options._nodes[i].id) {
          delete self.options._nodes[i].id;
        }
      });

      self._ui.map[self.options.map_type]('removeMarker', marker_id);
    },
    clearMarkers: function() {
      var self = this;
      self._ui.map[self.options.map_type]('clearMarkers');  
    },
    setBound: function(points) {
      var self = this;
      self._ui.map[self.options.map_type]('setBound', points);
    },
    sync: function() {
      var self = this;
      var params = {};
      $.extend(params, self.options.data_provider_params);
      params.map_id = self.options.map_info.id;
     
      $.ajax({
        async: false,
        url: self.options.data_provider_url,
        data: params,
        dataType: "json"
      }).success(function(data, text, jqXHR) {
        $.each(data, function(i) { 
          var marker_info = data[i];
          if(marker_info.id > self.options._max_marker_id) {
            self.options._max_marker_id = marker_info.id;
            self.addMarker(marker_info);
          }
        });
      }).error(function(jqXHR, textStatus, errorThrown) {
        alert("Error : " + errorThrown);
      });
    },
    trace: function() {
      var self = this;
      self._ui.map[self.options.map_type]('trace', self.options._nodes);
    },
    _create_realtime_task: function() {
      var self = this;
      var task = new Task(
        function() {
          self.sync();
          self.options.schedular.addTask(task);
        }
      );
      return task;
    }
  });

  $.widget("ajou.mapContainer", {
		options: {
		  map_provider_url: "",
      map_provider_params: {},
      node_provider_url: "",
      node_provider_params: {},
      map_prefix: "map_",
      schedular_time_sec: 1000,
      on_initialize: function(){},
      _container_id: "",
      _schedular: {}
    },
    _ui: {
      container: {}
    },
		_create: function() {
      var self = this;
			var container = self._ui.container = $(self.element);
			var container_id = self.options.container_id = container.attr("id");
    },
		_init: function() {
      var self = this;
      var params = {};
      $.extend(params, self.options.map_provider_params);

      self.options._schedular = new Schedular();
      $.ajax({
        url: self.options.map_provider_url,
        data: params, 
        dataType: "json"
      }).success(function(data, text, jqXHR) {
        var n = data.length;
        for(var i = 0; i < n; i++) {
          var map_info = data[i];
          var map = self._create_map_object(map_info);
          self._ui.container.append(map);
        }
        if($.isFunction(self.options.on_initialize)) {
          self.options.on_initialize($("#maps").children());
        }
        self.options._schedular.execute(self.options.schedular_time_sec);
      }).error(function(jqXHR, textStatus, errorThrown) {
        alert("Error : " + errorThrown);
      });
    },
    _create_map_object: function(map_info) {
      var self = this;
      var map_id = self.options.map_prefix + map_info.id;
      var map = $("<div></div>");
      map.attr("id", map_id);
      map.ajouMapProxy({
        map_info: map_info,
        schedular: self.options._schedular,
        data_provider_url: self.options.node_provider_url,
        data_provider_params: self.options.node_provider_params
      });
      return map;
    }
	});
	$.widget.bridge("ajouMapContainer", $.ajou.mapContainer);
	$.widget.bridge("ajouMapProxy", $.ajou.mapProxy);
})(jQuery);
