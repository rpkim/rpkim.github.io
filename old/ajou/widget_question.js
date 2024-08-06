(
function($,undefined){
	$.widget('CBB.OneDayQuestion',{
		options:{
			disabled : false,
			label : "Today Question"
		},

		//template css style for my widget
		defaultStyle:{
			backgroundColor : "gray",
				fontColor:"White",
			fontWeight : "bolder",
				width: "500px",
				height: "200px"
		},

		disableStyle:{
			backgroundColor : "Blue",
			width : "0px",
			height : "0px"
		},

		_create:function(){
			this.element.addClass("ui-widget");
			this._createbt();
		},

		_init:function(){
			this._setStyle();
			this._eventConnect();
		},

		//rpkim
		_setStyle:function(){
			if(!this.options.disabled)
			{
				alert('k');
				this.element.css(this.defaultStyle);

			}
			else
			{
				alert('b');
				this.element.css(this.disableStyle);
			}
		},
		//rpkim
		_eventConnect:function(){
			var self = this;
			this.element.bind("click.widget",function(e){
				self._click(e);
			});
		},

		//rpkim
		_click:function(e){
			console.log("_clickEvent");
		},

		destory:function(){
			//return Resource
			$.Widget.prototype.destroy.apply(this,arguments);	//default destory

		},
		

		//rpkim
		_createbt:function(){
			alert('c');
			this.element.html("<span></span>");
			this.element.text(this.options.label);
			this.element.css(this.defaultStyle);
		}

	});

})(jQuery);