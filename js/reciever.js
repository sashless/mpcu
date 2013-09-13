
var reciever = {
	fields: {'content': $('content'), 'status': $('status') },
	ajax: {'url': 'ajax.php', 'method': 'get'},
	actions: new Array(),
	request: undefined,
	interval: undefined,
	initTime: new Date().getTime(),
	curentAction: 0,
	
	init: function(){
		var that = this;
		that.setupInterval(that);
		
	},
	setupInterval: function(that){
		that.interval = that.getNextAction.periodical(250,that);
	},
	getNextAction: function(){
		var that = this;		
		clearInterval(this.interval)
		var onRequest = function(){
			console.log("send");
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
					if(response[0].data){
						that.fields.status.set('text',"performing action!");
						that.processResponse(JSON.parse(response[0].data));
					}else if(response[0].data.status && response[0].data.status == 'error'){
						that.fields.status.set('text',"error while loading! " + response[0].data.msg);
						that.setupInterval(that);
					}
				}else{
					that.fields.status.set('text',"response was empty!");
					that.setupInterval(that);
				}
			}
		};
		var onError = function(error){
			that.fields.status.set('text',"error while saving!");
		};
		
		var action =  { 'time': this.getLastAction() ? this.getLastAction().time : this.initTime, 'get_next':'get_next'};
		
		var req = new Request.HTML({
			link: 'ignore',
			timeout: 5000,
			method: that.ajax.method,
			url: that.ajax.url,
			data: {'action' : action},
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
//		var date = new Date();
		console.log("process");
		for(var i = 0; i < data.length; i++){
			if(reciever.actions.indexOf(data[i]) != '-1'){
				console.log("double found");
			}else{
				reciever.actions.push(data[i]);
//				date.setTime(data[i].time);
//				console.log("saved. "+date.toString());
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
		p.inject(this.fields.content);
	},
	zoom: function(handling){
		/*
		 * todo: zoom page
		 */
	}
};
