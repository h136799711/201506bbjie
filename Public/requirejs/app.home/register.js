define(["jquery",""],function(require,exports,module){
	
	console.log(exports);
	console.log(module);
	var path = require.resolve('jquery');
	console.log(path);
	
	function init(){
		console.log("init");
	}
	
	exports.init = init;
	
})
