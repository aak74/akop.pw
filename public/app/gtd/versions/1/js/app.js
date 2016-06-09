;(function() {
// var app.pathName = "/bitrix/tools/akop.tasks/";

app = {
	userId: false,
	path: "/api/v1/",
	tasks: [],
	taskIds: "",
	taskFolders: [],
	extData: {
		tasks: []
	},
	folderNames: [],
	folders: [
		{"type": "INBOX", "name": "Входящие", "kind": "system"}, // 1
		{"type": "ALL", "name": "Все", "kind": "system"}, // 1
		{"type": "TODAY", "name": "Сегодня", "kind": "till"}, // 2
		{"type": "TOMORROW", "name": "Завтра", "kind": "till"}, // 2
		{"type": "WEEK", "name": "Неделя", "kind": "till"}, // 2
		{"type": "FOCUS", "name": "Важно", "kind": "importance"}, // 3
		{"type": "NORMAL", "name": "Стандарт", "kind": "importance"}, // 3
		{"type": "MAYBE", "name": "Возможно?", "kind": "importance"}, // 3
		{"type": "WAITINGFOR", "name": "Ждем", "kind": "folder"}, // 4
		{"type": "FAST", "name": "Быстрые дела", "kind": "folder"}, // 4
		{"type": "STUPID", "name": "Рутина", "kind": "folder"}, // 4
	],

	getTasks: function (cb) {
		api.getList(
			'task.item.list',
			[
				{PRIORITY: 'desc', DEADLINE : 'asc,nulls', ID : 'asc'},
				{'!STATUS': '5'},
				/* {'!STATUS': '5', 'SUBORDINATE_TASKS': 'Y'},
				 Такое условие должно работать, чтобы для админа отдавались только его задачи.
				 Сейчас приходится накладывать дополнительные фильтры.
			 	*/
			],
			function (err, res){
				if (!err) {
					// console.log("tasks loaded", res.length);
					app._normalizeTasks(res);
					app.getTaskFolders(app.showTasks);
					if (cb) {
						cb(null, res);
					}

				} else {
					console.log("getTasks error");
					if (cb) {
						cb(err, null);
					}
				}
			}
		);
	},




	// Перемещает задачи в другую папку
	moveToFolder: function () {
		// app.action.call(this, "moveToFolder");
		var taskId = app.getTaskId(this);
		params = {
			user_id: app.userId,
			task_id: taskId,
			folder: app.folders[ this.dataset.folder ]["type"],
			member_id: api.params.member_id
		};

		// console.log("moveToFolder", this, taskId, params, app.getNumberById(taskId));
	    $.post(app.path + "taskFolders", params)
	        .done(function(res) {
	        	// console.log('moveToFolder ok', res);
				app.updateFolder(res.task_id, res.folder)
				app.fadeOutTask(res.task_id);
        	})
	        .fail(function(err) {
	        	console.log('moveToFolder fail', err);
	        });
	},


	getTaskFolders: function (cb) {
		// cb(null, null);

	    $.get(app.path + "taskFolders/" + app.userId, {member_id: api.params.member_id, taskIds: app.taskIds})
	        .done(function(res) {
				app.taskFolders = [];
				for (var key in res) {
					app.updateFolder(res[key]['task_id'], res[key].folder);
				}
	        	cb(null, res);
	      	})
	        .fail(function(err) {
	          	console.log("getTasks fail", err);
	        	cb(err.status, null);
	        });

    },

	getTaskDiv: function (obj) {
		return $(obj).closest(".task");
	},

	getId: function ($taskDiv) {
		return $($taskDiv).attr("data-id");
	},

	getNumber: function ($taskDiv) {
		return $($taskDiv).attr("data-number");
	},

	getNumberById: function (taskId) {
		return $('[data-id="' + taskId + '"]').attr("data-number");
	},

	updateFolder: function (taskId, folder) {
		app.taskFolders[ 't' + taskId ] = folder;
	},

	getTaskId: function (obj) {
		return app.getId( app.getTaskDiv(obj) );
	},

	action: function (action) {
		var $taskDiv = app.getTaskDiv(this);
		var taskId = app.getId($taskDiv);
		// console.log("action", this, taskId, app.getTask(taskId));
		// var taskNumber = app.getNumber($taskDiv);
		params = {task_id: taskId};
		switch (action) {
		    case "moveToFolder":
		    	app.moveToFolder.call(this);
				// params["folder"] = app.folders[ this.dataset.folder ]["type"];
				// console.log("action", filename, this, params);
				// app.tasks[ app.getNumberById(taskId) ].FOLDER = params["folder"];

				// console.log('folder', app.tasks[ app.getNumberById(taskId) ].FOLDER, app.getNumberById(taskId));

				break;
		    case "defer":
				// params["days"] = this.dataset.days;
				var deferTo = moment().add(this.dataset.days, 'days').format("DD.MM.YYYY H:mm");
				// console.log("deferTo", deferTo);
				api.getRow(
					"task.item.update",
					[
						taskId,
						{DEADLINE: deferTo}
					],
					function(err, res) {
						if (!err) {
							var task = app.getTask(taskId);
							task.DEADLINE = moment(deferTo, "DD.MM.YYYY H:mm");
							app.updateTask(taskId, app._normalizeTask(task));
							app.showTasks();
						}
					}
				);

				break;
		    case "complete":
				api.getRow("task.item.complete", [taskId],
					function(err, res) {
						if (!err) {
							app.removeTask(taskId);
							app.fadeOutTask(taskId);
						}
					}
				);
				break;
		    case "approve":
				api.getRow("task.item.approve", [taskId],
					function(err, res) {
						if (!err) {
							app.removeTask(taskId);
							app.fadeOutTask(taskId);
						}
					}
				);
				break;
		    case "disapprove":
				api.getRow("task.item.disapprove", [taskId],
					function(err, res) {
						if (!err) {
							api.getRow(
								"task.item.list",
								[
									{PRIORITY: 'asc', ID : 'desc'},
									{ID: taskId}
								],
								function(err, res) {
								if (!err) {
									// console.log("before updateTask", taskId, res);
									app.updateTask(taskId, res[0]);
									app.showTasks();
								}
							});
							// app.updateTask(taskId, {STATUS: 3});
						}
					}
				);
				break;
			default:
				break
		}
		// app.showTasks();
	},

	fadeOutTask: function (taskId) {
		$("[data-id='" + taskId + "']").fadeOut("slow", app.showTasks);
	},

	afterAction: function () {
		console.log("afterAction", this)
	},

	removeTask: function (taskId) {
		app.tasks = app.tasks.filter( function(d) {
			return ( d.ID != taskId );
		});
	},


	getTask: function (taskId) {
		return app.tasks.filter( function(d) {
			return ( d.ID == taskId );
		});
	},


	updateTask: function (taskId, params) {
		app.tasks = app.tasks.map( function(d) {
			if ( d.ID == taskId ) {
				// console.log("updateTask", taskId, params, d);
				for (var key in params) {
					d[key] = params[key];
				}
			}
			return d;
		});
	},


	getName: function (lastname, name, login) {
		result = lastname + " " + name;
		if (result == " ") result = "(" + login + ")";
		return result;
	},

	// Показывает задачи
	showTasks: function () {
		// app.getTasks();
		var folderName = $("#nav-tasks .active").attr("data-folder-name");
		// console.log('showTasks folderName', folderName);

		/* Обнуляем все счетчики */
		var counters = [];
		counters["ALL"] = 0;
		for (var key in app.folders) {
			counters[app.folders[key].type] = 0;
		}


		var str = "";
		for (var i = 0; i < app.tasks.length; i++) {
			var d = app.tasks[i];
			d.FOLDER = ( ( app.taskFolders['t' + d.ID] )
				? app.taskFolders['t' + d.ID]
				: "INBOX"
			);

			counters["ALL"]++;
			counters[d.FOLDER]++;
			counters[d.FOLDER_TILL]++;

			// console.log('showTasks', folderName, d.FOLDER)

			if (
				((d.FOLDER == folderName) || (d.FOLDER_TILL == folderName) || (folderName == "ALL")) // filter by folder
				|| (d.FOLDER === undefined) && (folderName === "INBOX") // filter by folder
			) {

				str += '<div class="task well row priority' + d.PRIORITY
					+ ((d.STATUS == 5) ? " closed" : "")
					+ ((d.STATUS == 4) ? " almost-closed" : "")
					+ '"'
					+ " data-number=\"" + i + "\""
					+ ' data-id="' + d.ID + '"'
					+ '>';
					// + d.TITLE
					// + "</div>";

		/* Task title */
				str += "<div class=\"task-main col-md-5 col-sm-4\">"
				 	+ "<span class=\"label label-"
				 	+ ( (d.PRIORITY > 1) ? "danger" : "success" ) + "\">" + d.ID + "</span>"
					+ "<div class=\"task-title\">"
					+ "<a href=\"//" +  api.params.domain + "/company/personal/user/" + app.userId + "/tasks/task/view/" + d.ID + "/\" target='_blank'>" + d.TITLE + "</a>"
					+ "</div>"
					+ ( (folderName == "ALL")
						? "<div class=\"task-title-folder\">"
							+ app.folderNames[d.FOLDER]
							+ ( (d.FOLDER_TILL !== "")
								?  " / " + app.folderNames[d.FOLDER_TILL]
								: ""
							)
							+ "</div>"
						: ""
					)
					+ "</div>";


				/* Actions */
				str += "<div class=\"task-tools col-md-3 col-sm-3\">";

				// if (!d.ACTION_APPROVE && !d.ACTION_DISAPPROVE && (d.RESPONSIBLE_ID == app.userId))
				// if (!d.ALLOWED_ACTIONS["ACTION_APPROVE"] && !d.ALLOWED_ACTIONS["ACTION_DISAPPROVE"]) {
				if (d.ALLOWED_ACTIONS.ACTION_COMPLETE) {
					str += "<a href=\"#\" class=\"\" data-function=\"complete\"><span class=\"glyphicon glyphicon-flag\">"
						+ "</span>&nbsp;Завершить задачу</a><br/>";
				}

				if (d.ALLOWED_ACTIONS.ACTION_APPROVE) {
					str += "<a href=\"#\" class=\"\" data-function=\"approve\">"
						+ "<span class=\"glyphicon glyphicon-thumbs-up\"></span>&nbsp;Принять работу</a><br/>";
				}

				if (d.ALLOWED_ACTIONS.ACTION_DISAPPROVE) {
					str += "<a href=\"#\" class=\"\" data-function=\"disapprove\">"
						+ "<span class=\"glyphicon glyphicon-thumbs-down\"></span>&nbsp;Доделать</a><br/>";
				}

				if (d.ALLOWED_ACTIONS.ACTION_CHANGE_DEADLINE) {
			        str += "<div><a id=\"dropdownMenuDefer\" href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">"
			          	+ "<span class=\"glyphicon glyphicon-time\"></span>&nbsp;Отложить <b class=\"caret\"></b></a>"
						+ "<ul class=\"dropdown-menu\" id=\"#defer\" role=\"menu\">"
						+ "<li><a href=\"#\" data-function=\"defer\" data-days=\"1\">На завтра</a></li>"
						+ "<li><a href=\"#\" data-function=\"defer\" data-days=\"7\">На неделю</a></li>"
						+ "</ul></div>";
					}

		        str +=
					"<div><a id=\"dropdownMenuFolder\" href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;Переместить в папку <b class=\"caret\"></b></a>"
					+ "<ul class=\"dropdown-menu\" role=\"menu\">" ;

				for (var key in app.folders) {
					if ( (app.folders[key].kind === "folder") || (app.folders[key].kind === "importance")  ) {
						str += "<li><a href=\"#\" data-function=\"moveToFolder\" data-folder=\"" + key + "\">" + app.folders[key]["name"] + "</a></li>";
					}
				}
				str += "</ul></div></div>";

				/* Deadline */
				// if (d.DEADLINE !== '') deadline = d.DEADLINE.split('T');
				str += "<div class=\"task-deadline-col col-md-2 col-sm-3\">"
					+ ( (d.DEADLINE)
						? "<div class=\"task-deadline" + ((d.OVERDUED) ? " task-status-overdue" : "") + "\">"
							+ "<span class=\"glyphicon glyphicon-time\"></span>&nbsp;"
							+ "<span class=\"task-deadline-date\">" + d.DEADLINE + "</span>"
							+ "</div>"
						: "")
					+ ((d.CLOSED_DATE && (d.STATUS == 4))
						? "<div><span class=\"glyphicon glyphicon-flag\"></span>&nbsp;"  + d.CLOSED_DATE + "</div>"
						: ""
					)
					+ "</div>";

				/* Task team */
				str += "<div class=\"task-team col-md-2 col-sm-2\">" + app.getName(d.CREATED_BY_LAST_NAME, d.CREATED_BY_NAME, d.CREATED_BY_LOGIN) + "<br/>"
					+ "<span class=\"glyphicon glyphicon-hand-down\"></span>&nbsp;" + "<br/>"
					+ app.getName(d.RESPONSIBLE_LAST_NAME, d.RESPONSIBLE_NAME, d.RESPONSIBLE_LOGIN) + "</div>";

				str += "</div>";
			}




			// if (groups.currentId == -1) {
			// 	str += ((d.GROUP_ID > 0) ? "<div class=\"task-group\">"
			// 		+ "<a href=\"/workgroups/group/" + d.GROUP_ID + "/\">" + groups.list[d.GROUP_ID] + "</a>"
			// 		+ "</div>" : "")
			// }


			// if ("ALL_TODAY_TOMORROW_WEEK".indexOf(folderName) > -1) {
			// 	str += "<span class=\"glyphicon glyphicon-folder-close\"></span>&nbsp;" + app.folders[d.FOLDER]["name"];
			// }

			str += "</div>";
		};
		$(".tasks").html(str);
		// console.log('counters', counters);
		for (var key in counters) {
			if ( counters[key] > 0) {
				$('#tab_' + key + ' span').text(counters[key]);
			}
		};
		app.setListener4TaskTool();
		app.resizeFrame();
	},

	// Формирует и показывает меню из типов задач
	showTasksTypes: function () {
		// var foldersAction = app.folders.concat( app.foldersActionTill.concat(app.foldersAction) );
		var str = "", currentKind;
		for (var i = 0; i < app.folders.length; i++) {
			if ( currentKind !== app.folders[i].kind ) {
				currentKind = app.folders[i].kind;
				if ( i !== 0) {
					str += "</ul>";
				}
				str += '<ul class="folder-kind folder-kind-' + currentKind + ' nav nav-pills nav-stacked col-md-3 col-sm-3 col-xs-6">';
			}
			str += '<li id="tab_' + app.folders[i].type + '"'
				+ ( ( app.folders[i].type == "INBOX" ) ? ' class="active"' : '' )
				+ ' data-folder-name="' + app.folders[i].type + '"'
				+ ' data-folder-kind="' + app.folders[i].kind
				+ '">'
				+ ' <a href="#">'
				+ app.folders[i].name
				+ '&nbsp;<span class="label label-default"></span>'
				+ '</a></li>';

			if ( i == app.folders.length) {
				str += "</ul>";
			}

		};
		$("#nav-tasks").html(str);
		$("#nav-tasks li").on("click", app.setActive)
	},

	setListener4TaskTool: function () {

		$(".task-tools a")
			.off()
			.on("click", function(e) {
				// console.log(".task-tools a onclick", e, this.dataset);
				e.preventDefault();
				if ((this.dataset) && (this.dataset.function)) {
					app.action.call(this, this.dataset.function);
				}
			});

		$(".task-deadline-date")
			.off()
			.on("click", function(e) {
				e.preventDefault();
				// console.log(".task-deadline-date onclick", e, this.dataset);
			});

	},

	// Устанавливает активность меню (типа задач)
	setActive: function (e) {
		// console.log('setActive', e, e.target);
		$("#nav-tasks li").removeClass("active");
		$(e.target).closest("li").addClass("active");
		app.showTasks();

	},

	start: function () {
		// console.log("start");
		api.start();
		api.getCurrentUser(function(err, res) {
			if (!err) {
				app.userId = res.ID;
				// console.log('userId=' + app.userId);
				app.getTasks();
			} else {
				app._showError(err, 'Ошибка при получении данных о номере текущего пользователя');
			}
		});
		app.showTasksTypes();
		for (var key in app.folders) {
			// console.log('app.folders[key]', key, app.folders[key]);
			app.folderNames[app.folders[key].type] = app.folders[key].name;
		}
		app.saveFrameWidth();
	},

	_showError: function (err, msg) {
		console.error(msg, err);
	},

	/**
	 * Дополняет задачи свойствами принадлежности к папкам "ДО".
	 * Плюс устанавливает некоторые другие свойства.
	 * @param  {[type]}
	 * @param  {[type]}
	 * @return {[type]}
	 */
	_normalizeTasks: function (res) {
		// console.log('_normalize start');
		// console.log(res.length);
		app.tasks = [];
		app.taskIds = "";
		var task = {};

		/* Фильтр задач для админа. BX24 админу отдает ВСЕ задачи, никакие SUBORDINATE_TASKS не помогают */
		res = res.filter(function(d) {
			return (
				( d.RESPONSIBLE_ID == app.userId )
				|| ( d.CREATED_BY == app.userId )
				|| ( d.AUDITORS.indexOf(app.userId) > -1 )
				|| ( d.ACCOMPLICES.indexOf(app.userId) > -1 )
			);
		});
		for (var i in res) {
			task = app._normalizeTask(res[i]);
			app.tasks.push(task);
			app.taskIds += task.ID + "+";
		};
		// console.log('_normalize end');
	},

	_normalizeTask: function (task) {
		task.FOLDER_TILL = "";
		if (task.DEADLINE !== "" ) {
			var deadline = moment(task.DEADLINE);
			var today = moment().endOf('day');
			var tommorow = moment().endOf('day').add(1, 'days');
			var week = moment().endOf('day').add(7, 'days');
			if ( moment(deadline).isSameOrBefore(today) ) {
				task.FOLDER_TILL = "TODAY";
				task.OVERDUED = true;
			} else if ( moment(deadline).isSameOrBefore(tommorow) ) {
				task.FOLDER_TILL = "TOMORROW";
			} else if ( moment(deadline).isSameOrBefore(week) ) {
				task.FOLDER_TILL = "WEEK";
			};
			task.DEADLINE = deadline.format("DD.MM.YYYY H:mm");
		}
		if (task.CLOSED_DATE) {
			task.CLOSED_DATE = moment(task.CLOSED_DATE).format("DD.MM.YYYY H:mm")
		}
		return task;
	},

	resizeFrame: function () {

		var currentSize = BX24.getScrollSize();
		// console.log('resizeFrame currentSize', currentSize)
		minHeight = currentSize.scrollHeight;

		if (minHeight < 1800) minHeight = 1800;
		BX24.resizeWindow(currentSize.scrollWidth, minHeight);
		// BX24.resizeWindow(this.FrameWidth, minHeight);
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
})();