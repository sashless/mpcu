
var sender = {
	fields: { 'container': $('touchport'), 'status': $('status') },
	ajax: {'url': 'ajax.php', 'method': 'get'},
	actions: new Array(),
	request: new Array(),
	touchport: undefined,
	curentPage: 0,
	maxPage: 5,
	
	init: function(){
		var that = this;
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
		var that = this;		
		var onRequest = function(){
			that.fields.status.set('text',"saving action..");
		};
		var onComplete = function(response){
			if(response.length > 0){
				var data = JSON.parse( response[0].data);
				if(data.status && data.status == 'saved'){
					that.fields.status.set('text',"action saved!");
					that.removeAction(action);
				}else if(data.status && data.status == 'error'){
					that.fields.status.set('text',"error while saving! " + data.msg);
				}
			}else{
				that.fields.status.set('text',"response was empty!");
			}
		};
		console.log(action.time + " " + action.handling);
		var req = new Request.HTML({
			method: that.ajax.method,
			url: that.ajax.url,
			data: {'action' : action},
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
		var action = {'time': new Date().getTime(), 'type': 'switch_page' , 'save_action': 'save_action'};
		if(handling.direction == 'right'){// backward
			action.handling = this.prevPage()
			this.saveAction(action);
		}else if(handling.direction == 'left'){
			action.handling = this.nextPage();
			this.saveAction(action);
		}
		
		this.actions.push(action);
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