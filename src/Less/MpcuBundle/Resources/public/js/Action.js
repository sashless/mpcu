function Action(config){
	this.type = null;
	this.handling = null;
	this.time = null;
	
	this.init(config);
}

Action.prototype.init = function(config){
	this.type = config.type ? config.type : null;
	this.handling = config.handling ? config.handling : null;
	this.time = config.time ? config.time : null;
}