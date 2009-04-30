var MooPrompt = box = new Class({
	setOptions: function(options){
		this.options = {
			buttons: 1,
			width: 300, // Set width of the box
			height: 0, // Set height of the box (0 = sized to content)
			maxHeight: 500, // Maximum height of the dialog box
			vertical: 'middle', // top middle bottom
			horizontal: 'center', // left center right
			delay: 0, // Delay before closing (0=no delay)
			overlay: true, // Cover the page
			effect: 'grow'
				// 'grow' - Expands box from a middle point and fades in content
				// 'slide' - Slides in the box from the nearest side.
			// button1: 'Ok' --- supply this for setting button text
			// onButton1: function --- supply function for button action
		};
		Object.extend(this.options, options || {});
	},
	
	initialize: function(title, content, options){
		this.setOptions(options);
		this.title = title;
		this.text = content;
		if (this.options.overlay) {
			this.overlay = new Element('div').setProperty('class', 'cbOverlay');
			this.overlay.setStyles({
				'position': 'absolute', 'top': 0, 'left': 0, 'width': '100%', 'visibility': 'hidden'
			}).injectInside(document.body);
		}
		this.container = new Element('div').setProperty('class', 'cbContainer');
		this.container.setStyles({
			'position': 'absolute', 'visibility': 'hidden'
		}).injectInside(document.body);
		this.box = new Element('div').setProperty('class', 'cbBox');
		this.box.setStyles({
			'width': this.options.width+'px',
			'overflow': 'auto'
		}).injectInside(this.container);
		if (this.box.getStyle('background-color') == '' || this.box.getStyle('background-color') == 'transparent') {
			this.box.setStyle('background-color', this.container.getStyle('background-color'));
		}
		this.header = new Element('h3').setProperty('class', 'cbHeader').appendText(this.title).injectInside(this.box);
		this.content = new Element('div').setProperty('class', 'cbContent').injectInside(this.box);
		if ($type(content) == 'element' ) {
			content.injectInside(this.content);
		} else {
			this.content.appendText(this.text);
		}
		this.buttons = new Element('div').setProperty('class', 'cbButtons').injectInside(this.box);
		if (this.buttons.getStyle('background-color') == '' || this.buttons.getStyle('background-color') == 'transparent') {
			this.buttons.setStyle('background-color', this.box.getStyle('background-color'));
		}
		for (var i = 1; i <= this.options.buttons; i++) {
			if (typeof(this.options['button'+i]) == 'undefined') {
				this.options['button'+i] = 'Button';
			}
			if ($type(this.options['button'+i]) == 'element') {
				this['button'+i] = this.options['button'+i]
				this['button'+i].injectInside(this.buttons);
			} else {
				this['button'+i] = new Element('input').setProperties({type: 'button', value: this.options['button'+i]}).injectInside(this.buttons);
			}
			if (typeof(this.options['button'+i]) == 'undefined') {
				this.options['onButton'+i] = Class.empty;
			}
			this['button'+i].onclick = this.close.pass([this.options['onButton'+i]], this);
		}
		this.boxHeight = (this.box.offsetHeight < this.options.maxHeight) ? this.box.offsetHeight : this.options.maxHeight;
		this.boxHeight = (this.options.height > 0) ? this.options.height : this.boxHeight;
		this.position();
		this.eventPosition = this.position.bind(this);
		window.addEvent('scroll', this.eventPosition).addEvent('resize', this.eventPosition);
		this.box.setStyle('display', 'none');
		if (this.options.overlay) {
			this.fx1 = new Fx.Style(this.overlay, 'opacity', {duration:500}).custom(0, .8);
		}
		if (this.options.effect == 'grow') {
			this.container.setStyle('top', (Window.getScrollTop()+(Window.getHeight()/2))+'px');
			var style = {}; style.height = 0; style.width = 0;
			if (this.options.horizontal != 'center') {
				style[this.options.horizontal] = (this.options.width/2)+'px';
			}
			if (this.options.vertical == 'top') {
				style[this.options.vertical] = (Window.getScrollTop()+(this.boxHeight/2))+'px';
			} else if (this.options.vertical == 'bottom') {
				style.top = (Window.getScrollTop()+Window.getHeight()-(this.boxHeight/2)-25)+'px';
			}
			this.container.setStyles(style);
			this.container.setStyle('visibility', '');
			this.fx2 = new Fx.Styles(this.container, {duration: 500});
			this.fx2.custom({
				'width': [0, this.options.width], 'margin-left': [0, -this.options.width/2], 'margin-right': [0, -this.options.width/2],
				'height': [0, this.boxHeight], 'margin-top': [0, -this.boxHeight/2], 'margin-bottom': [0, -this.boxHeight/2]
			}).chain(function() {
				this.box.setStyles({
					'visibility': 'hidden', 'display': '', 'height': this.boxHeight+'px'
				});
				new Fx.Style(this.box, 'opacity', {duration: 500}).custom(0, 1).chain(function() {
					if (this.options.delay > 0) {
						var fn = function () {
							this.close()
						}.bind(this).delay(this.options.delay);
					}
				}.bind(this));
			}.bind(this));
		} else {
			this.container.setStyles({
				'height': this.boxHeight, 'width': this.options.width,
				'left': '', 'visibility': 'hidden'
			});
			this.box.setStyles({
				'visibility': '', 'display': '', 'height': this.boxHeight+'px'
			});
			this.fx2 = new Fx.Styles(this.container, {duration: 500});
			var special = {};
			if (this.options.horizontal != 'center') {
				special[this.options.horizontal] = [-this.options.width, 0];
			} else {
				this.container.setStyles({
					'left': '50%', 'margin-left': (-this.options.width/2)+'px', 'margin-right': (-this.options.width/2)+'px'
				});
			}
			if (this.options.vertical == 'top') {
				special[this.options.vertical] = [Window.getScrollTop()-this.boxHeight, Window.getScrollTop()];
			} else if (this.options.vertical == 'bottom') {
				special.top = [Window.getScrollTop()+Window.getHeight(), Window.getScrollTop()+Window.getHeight()-this.boxHeight-25];
			} else {
				this.container.setStyles({
					'top': (Window.getScrollTop()+(Window.getHeight()/2))+'px', 'margin-top': (-this.boxHeight/2)+'px', 'margin-bottom': (-this.boxHeight/2)+'px'
				});
			}
			special.opacity = [0, 1];
			this.fx2.custom(special).chain(function() {
				if (this.options.delay > 0) {
					var fn = function () {
						this.close()
					}.bind(this).delay(this.options.delay);
				}
			}.bind(this));
		}
	},
	
	position: function() {
		var wHeight = (Window.getScrollHeight() > Window.getHeight()) ? Window.getScrollHeight() : Window.getHeight();
		//var bHeight = this.container.getStyle('height').toInt();
		var lr = (this.options.effect == 'grow') ? this.options.width/2 : 0;
		var tb = (this.options.effect == 'grow') ? this.boxHeight/2 : 0;
		if (this.options.overlay) {
			this.overlay.setStyles({height: wHeight+'px'});
		}
		switch(this.options.vertical) {
			case 'top':
				this.container.setStyle('top', (Window.getScrollTop()+tb)+'px');
				break;
			case 'middle':
				this.container.setStyle('top', (Window.getScrollTop()+(Window.getHeight()/2))+'px');
				break;
			case 'bottom':
				this.container.setStyle('top', (Window.getScrollTop()+Window.getHeight()-this.boxHeight+tb-25)+'px');
				break;
		}
		if (this.options.horizontal == 'center') {
			this.container.setStyle('left', '50%');
		} else {
			this.container.setStyle(this.options.horizontal, lr+'px');
		}
	},
	
	close: function(fn) {
		for (var i = 1; i <= this.options.buttons; i++) {
			this['button'+i].onclick = null;
		}
		if (this.options.overlay) {this.fx1.clearTimer();}
		this.fx2.clearTimer();
		if (typeof(fn) == 'function') {
			fn();
		}
		if (this.options.overlay) {new Fx.Style(this.overlay, 'opacity', {duration:250}).custom(.8, 0);}
		new Fx.Style(this.container, 'opacity', {
			duration:250,
			onComplete: function() {
				window.removeEvent('scroll', this.eventPosition).removeEvent('resize', this.eventPosition);
				if (this.options.overlay) {
					this.overlay.remove();
					}
				this.container.remove();
			}.bind(this)
		}).custom(1, 0);
	}
});

MooPrompt.implement(new Chain);

var MooFloater = new Class({
	setOptions: function(options){
		this.options = {
			width: '200px',
			height: '30px',
			position: 'top-right',
			glidespeed: 6,
			offsetx: 10,
			offsety: 10
		};
		Object.extend(this.options, options || {});
	},
	
	initialize: function(id, options){
		this.setOptions(options);
		this.container = id;
		this.container.setStyle('position','absolute');
		this.container.setStyle('width',this.options.width);
		this.container.setStyle('height',this.options.height);
		position = this.options.position.split('-');
		switch(position[0]) {
			case 'top':
				this.container.setStyle('top', this.options.offsety+'px');
				break;
			case 'bottom':
				this.container.setStyle('bottom', this.options.offsety+'px');
				break;
		}
		switch(position[1]) {
			case 'left':
				this.container.setStyle('left', this.options.offsetx+'px');
				break;
			case 'right':
				this.container.setStyle('right', this.options.offsetx+'px');
				break;
		}
		this.floater = this.startFloat.bind(this).delay(100, window);
		window.addEvent('scroll', this.onScrollHandler.bind(this));
		window.addEvent('resize', this.onScrollHandler.bind(this));
	},

	onScrollHandler: function(){
		if (this.floater == null){
			this.floater = this.startFloat.bind(this).delay(100, window);
		}else{
			this.floater = $clear(this.floater);
			this.floater = null;
			this.floater = this.startFloat.bind(this).delay(100, window);
		}
	},
	
	startFloat: function(){
		glidespeed = (this.options.glidespeed*100);
		position = this.options.position.split('-');
		switch(position[0]) {
			case 'top':
				var floatTop = new Fx.Style(this.container, 'top', {duration:glidespeed});
				floatTop.custom(this.container.getStyle('top').toInt(), Window.getScrollTop()+this.options.offsety);
				break;
			case 'bottom':
				var floatBottom = new Fx.Style(this.container, 'bottom', {duration:glidespeed});
				floatBottom.custom(this.container.getStyle('bottom').toInt(), -Window.getScrollTop()+this.options.offsety);
				break;
		}
		switch(position[1]) {
			case 'left':
				var floatLeft = new Fx.Style(this.container, 'left', {duration:glidespeed});
				floatLeft.custom(this.container.getStyle('left').toInt(), Window.getScrollLeft()+this.options.offsetx);
				break;
			case 'right':
				var floatRight = new Fx.Style(this.container, 'right', {duration:glidespeed});
				floatRight.custom(this.container.getStyle('right').toInt(), -Window.getScrollLeft()+this.options.offsetx);
				break;
		}
	}
});

MooFloater.implement(new Chain);

var MooTicker = new Class({
	setOptions: function(options){
		this.options = {
			width: '',
			height: '',
			interval: 3000
		};
		Object.extend(this.options, options || {});
	},
	
	initialize: function(id,options){
		this.setOptions(options);
		this.container = id;
		this.container.setStyle('width',this.options.width);
		this.container.setStyle('height',this.options.height);
		this.messages  = $$('div.mooticker');
		this.number_of_messages = this.messages.length;
		
		if(this.number_of_messages == 0){
			this.showError();
			return false;
		}else if(this.number_of_messages == 1){
			return false;		
		}

		this.current_message = 0;
		this.previous_message = this.current_message;
		this.hideMessages();
		this.showMessage();
		this.timer = setInterval(this.showMessage.bind(this), this.options.interval);
	},

	hideMessages: function(){
		this.messages.each(function(message){
			message.setStyle('display', 'none');
			new Fx.Style(message, 'opacity').set(0);			
		})
	},

	showMessage: function(){
		this.messages[this.current_message].setStyle('display', '');
		this.message = new Fx.Style(this.messages[this.current_message], 'opacity').start(0,1);
		this.timer = setTimeout(this.fadeMessage.bind(this), this.options.interval-1000);
	},

	fadeMessage: function(){
		new Fx.Style(this.messages[this.current_message], 'opacity').addEvent('onComplete', function(){
    		this.messages[this.current_message].setStyle('display', 'none');
			if (this.current_message < this.number_of_messages-1){
				this.previous_message = this.current_message;
				this.current_message = this.current_message + 1;
			} else {
				this.current_message = 0;
				this.previous_message = this.number_of_messages - 1;
			}		
		}.bind(this)).start(1,0);
	},
	
	showError: function(){
		this.errorMessage = new Element('div').addClass('mooticker');
		this.errorMessage.injectInside(this.container);
		this.errorSpan = new Element('span').addClass('error').appendText('Could not retrieve data');
		this.errorSpan.injectInside(this.errorMessage);
	}			
});

MooTicker.implement(new Chain);