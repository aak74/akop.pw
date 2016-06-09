;(function() {
api = {
	// tasks: [],
	params: [],

	getCurrentUser: function (cb) {
		api.getRow('user.current', {}, cb);
	},

	getRow: function (entity, params, cb) {
		// console.log('getRow', entity, params);
		BX24.callMethod(
			entity,
			params,
			function(result) {
				if ( err = result.error() ) {
					cb(err.status, null)
					console.error('getRow error', err);
				} else {
					cb(null, result.data());
				}
			}
		);

	},

	getList: function (entity, params, cb) {
		// console.log('getList', entity, params);
		var res = [];
		BX24.callMethod(
			entity,
			params,
			function(result) {
				if ( err = result.error() ) {
					// displayErrorMessage('К сожалению, произошла ошибка получения данных');
					cb(err.status, null)
					console.error('getList error', err);
				} else {
					var data = result.data();
					// console.log('data portion', entity);
					// console.log('getList data portion размер данных: ' + data.length, (entity == 'user.current' ? data : ''));

					for ( var key in data ) {
						res.push(data[key]);
					}

					if ( result.more() ) {
						result.next();
					} else {
						// console.log('getList Получены все данные размер данных: ' + res.length, (entity == 'user.current' ? res : ''));
						if (res && res.length == 1) {
							// console.log('getList Получены все данные', res);
						}
						cb(null, res);

					}

				}
			}
		);
	},

	start: function () {
		api.params = BX24.getAuth();
		// console.log('start api.params', api.params);

	}



}

// console.log('js/api.js loaded');
})();