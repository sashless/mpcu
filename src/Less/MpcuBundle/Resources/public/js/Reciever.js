
var Reciever = {
	fields: {'log': $('log'), 'status': $('status') },
	ajax: {'url': '', 'method': ''},
	actions: new Array(),
	request: undefined,
	interval: undefined,
	initTime: undefined,
	curentAction: 0,
	
	init: function(config){
		var that = this;
		config = JSON.parse(unescape(config));
		this.initTime = config.initTime;
		this.ajax = config.ajax;
		this.setupInterval(that);
		
	},
	setupInterval: function(that){
		that.interval = that.getNextAction.periodical(250,that);
	},
	getNextAction: function(){
		var that = this;		
		clearInterval(this.interval)
		var onRequest = function(){
			//console.log("send");
			that.fields.status.set('text',"waiting for action..");
		};
		
		var onFailure = function(xhr){
			console.log(xhr);
			setTimeout(function(){
					req.send();
					},250);
		};
		var onException = function(headerName, value){
			console.log(headerName + " - " +value);			
			setTimeout(function(){
					req.send();
					},250);
		};
		var onComplete = function(response){
			if(typeof response == 'undefined'){
				setTimeout(function(){
					req.send();
					},250);
			}else{
				if(response.length > 0){	
					var data = JSON.parse(unescape(response[0].data));
					
					if(data.status == 'ok'){
						that.fields.status.set('text',"performing action!");
						that.processResponse(data.actions);
					}else if(data.status == 'error'){
						that.fields.status.set('text',"error while loading! " + response[0].data.message);
						that.setupInterval(that);
					}
				}else{
					that.fields.status.set('text',"response was empty!");
					that.setupInterval(that);
				}
			}
		};
		var onError = function(error){
			that.fields.status.set('text',"error while ajax request!");
		};
		
		var action =  { 'time': this.getLastAction() ? this.getLastAction().time : this.initTime};
		
		var req = new Request.HTML({
			link: 'ignore',
			timeout: 5000,
			method: that.ajax.method,
			url: that.ajax.url,
			data: action,
			onRequest: onRequest,
			onComplete: onComplete,
			onFailure: onFailure,
			onException: onException
			}).send();
	},
	getLastAction: function(){
		if(this.actions.length > 1){
			return this.actions[this.actions.length-1];
		}else{
			return false;
		}
	},
	getFirstAction: function(){
		if(this.actions.length > 1){
			return this.actions[0];
		}else{
			return false;
		}
	},
	processResponse: function(data){
		var isClone = function(a,b){
			for(var o in a){
				if(a.hasOwnProperty(o) && b.hasOwnProperty(o)){
					if(a[o] !== b[o]){
						return false
					}
				}else{
					return false;
				}
			}
			return true;
		}
		for(var i = 0; i < data.length; i++){
			var action = new Action(data[i]);
			if(typeof this.actions[i] != 'undefined' && isClone(this.actions[i], action)){
				console.log("double found");
			}else{
				this.actions.push(action);
			} 
		}
		this.setupInterval(this);
		this.performActions();
	},
	performActions: function(){
		for(; this.curentAction < this.actions.length;){
			var action = this.actions[this.curentAction]
			if(action){
				switch (action.type) {
					case "switch_page":
						this.switchPage(action.handling);
						break;
					case "zoom":
						this.zoom(action.handling);
						break;
					default:
						break;
				}
				this.curentAction++;
			}
		}
	},
	switchPage: function(handling){
		var p = new Element('p',{
			'html': "SwitchPage to: " + handling
		});
		p.inject(this.fields.log);
	},
	zoom: function(handling){
		/*
		 * todo: zoom page
		 */
	}
};
