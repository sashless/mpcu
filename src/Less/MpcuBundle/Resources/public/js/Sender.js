
var Sender = {
	fields: { 'container': $('touchport'), 'status': $('status') },
	ajax: {'url': 'ajax.php', 'method': 'get'},
	actions: new Array(),
	request: new Array(),
	touchport: undefined,
	curentPage: 0,
	maxPage: 5,
	
	init: function(config){
		var that = this;
		config = JSON.parse(unescape(config));
		this.ajax = config.ajax;
		
		// resiz to actual browser size
		this.fields.container.set('style','position: absolute;width: '+document.width+'px; height: '+document.height+'px;')
		this.touchport = new Hammer(this.fields.container); 
		this.touchport.onswipe = function(event){
			that.switchPage({'direction': event.direction});
		};
		this.touchport.ontransform = function(event){
			console.log(event);
		};
	},
	saveAction: function(action){
		// save to resend again on fail
		this.actions.push(action);
		var that = this;		
		var onRequest = function(){
			that.fields.status.set('text',"saving action..");
		};
		var onComplete = function(response){
			if(response.length > 0){
				var data = JSON.parse( unescape(response[0].data));
				if(data.status == 'saved' ){
					that.fields.status.set('text',"action saved!");
					that.removeAction(action);
				}else if(data.status == 'error'){
					that.fields.status.set('text',"error while saving! " + data.message);
				}
			}else{
				that.fields.status.set('text',"response was empty!");
			}
		};
		console.log(action.handling);
		var req = new Request.HTML({
			method: that.ajax.method,
			url: that.ajax.url,
			data: action,
			onRequest: onRequest,
			onComplete: onComplete
			}).send();
	},
	getLastAction: function(){
		if(this.actions.length > 1){
			return this.actions[this.actions.length-1];
		}else{
			return false;
		}
	},
	processResponse: function(data){
		// check for error and resend if failed
		
	},
	prevPage: function(){
		if(this.curentPage > 0) return --this.curentPage ; 
		else if( this.curentPage == 0){
			this.curentPage = this.maxPage;
			return this.curentPage;
		}
	},
	nextPage: function(){
		if(this.curentPage < this.maxPage) return ++this.curentPage ; 
		else if( this.curentPage == this.maxPage){
			this.curentPage = 0;
			return this.curentPage;
		}
	},
	switchPage: function(handling){
		var page_id = 0;
		
		if(handling.direction == 'right'){// backward
			page_id = this.prevPage()
		}else if(handling.direction == 'left'){
			page_id = this.nextPage();	
		}
		
		var action = {'type': 'switch_page', 'handling': page_id };
		this.saveAction(action);
	},
	removeAction: function(action){
		this.actions.splice(this.actions.indexOf(action));
	},
	zoom: function(handling){
		/*
		 * todo: zoom page
		 */
	}
};