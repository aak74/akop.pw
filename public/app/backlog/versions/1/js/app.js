;(function(document) {
'use strict';

var app = {

	setGroups: function(items) {
		// var gp = document.querySelector('groups-projects');
		var gp = document.querySelector('#akop-projects');
		console.log('gp', gp);
		console.log('gp.items', gp.items);
		// gp.items = items.slice(0);
		console.log('gp.items 2', gp.items);
		console.log('items', items);
		gp.setItems( items );

	},


	afterLoad: function(err, res) {
		console.log('afterLoad', err, res);
	},

	getGroups: function() {

		// return api.getList('sonet_group.get', {});
/*
		return [
            { name: "Home", route: "home", url: "", icon: "home" },
            { name: "Приложения", route: "apps", url: "apps", icon: "apps" }
        ];
*/
	},

	resizeFrame: function () {

		var currentSize = BX24.getScrollSize();
		// console.log('resizeFrame currentSize', currentSize)
		minHeight = currentSize.scrollHeight;

		if (minHeight < 1800) minHeight = 1800;
		BX24.resizeWindow(currentSize.scrollWidth, minHeight);
		// BX24.resizeWindow(this.FrameWidth, minHeight);
	},

	start: function () {
		api.getList(
			'sonet_group.get',
			{
    			'ORDER': {
    				'NAME': 'ASC'
				}
			},
			function (err, res) {
			if (!err) {
				app.setGroups( res );
			}

		});

		// document.querySelector('#portals').generateRequest();
		//


	},

	saveFrameWidth: function () {
		// this.FrameWidth = document.getElementById("app").offsetWidth;
	}
}


BX24.init(function (e) {
	// console.log("init", e);
	// console.log("tasks start");

	app.start();

});
})(document);