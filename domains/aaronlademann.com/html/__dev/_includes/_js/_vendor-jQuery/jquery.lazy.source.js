(function($) {
	var status = {}
	$.lazy = function(url,name)
	{
		var name = name;
		var uri = url;	
		var object = {};
		object[name] = function () {
				var self = this;
				var arg = arguments;
				
				if(status[name] == 'loaded') {
					$.each(this,function(){
						$(this)[name].apply(self,arg);
					})
				}
				else if(status[name] == 'loading'){
					setTimeout(function() { object[name].apply(self,arg) }, 5);
				} else {
					status[name] = 'loading';
					$.getScript(uri,function(){
						status[name] = 'loaded';
						if(typeof self == 'object'){
							self.each(function(){
								if(arg.length > 0)
									$(this)[name].apply(self,arg);
								else
									$(this)[name]();
							});
						} else {
							$[name].apply(null,arg);
						}
					});
				}
		};
		jQuery.fn.extend(object);
		jQuery.extend(object);
	};

})(jQuery);