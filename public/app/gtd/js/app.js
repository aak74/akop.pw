;(function() {
// var tasks.pathName = "/bitrix/tools/akop.tasks/";

tasks = {
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
					tasks._normalizeTasks(res);
					tasks.getTaskFolders(tasks.showTasks);
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
		// tasks.action.call(this, "moveToFolder");
		var taskId = tasks.getTaskId(this);
		params = {
			user_id: tasks.userId,
			task_id: taskId,
			folder: tasks.folders[ this.dataset.folder ]["type"],
			member_id: api.params.member_id
		};

		// console.log("moveToFolder", this, taskId, params, tasks.getNumberById(taskId));
	    $.post(tasks.path + "taskFolders", params)
	        .done(function(res) {
	        	// console.log('moveToFolder ok', res);
				tasks.updateFolder(res.task_id, res.folder)
				tasks.fadeOutTask(res.task_id);
        	})
	        .fail(function(err) {
	        	console.log('moveToFolder fail', err);
	        });
	},


	getTaskFolders: function (cb) {
		// cb(null, null);

	    $.get(tasks.path + "taskFolders/" + tasks.userId, {member_id: api.params.member_id, taskIds: tasks.taskIds})
	        .done(function(res) {
				tasks.taskFolders = [];
				for (var key in res) {
					tasks.updateFolder(res[key]['task_id'], res[key].folder);
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
		tasks.taskFolders[ 't' + taskId ] = folder;
	},

	getTaskId: function (obj) {
		return tasks.getId( tasks.getTaskDiv(obj) );
	},

	action: function (action) {
		var $taskDiv = tasks.getTaskDiv(this);
		var taskId = tasks.getId($taskDiv);
		// console.log("action", this, taskId, tasks.getTask(taskId));
		// var taskNumber = tasks.getNumber($taskDiv);
		params = {task_id: taskId};
		switch (action) {
		    case "moveToFolder":
		    	tasks.moveToFolder.call(this);
				// params["folder"] = tasks.folders[ this.dataset.folder ]["type"];
				// console.log("action", filename, this, params);
				// tasks.tasks[ tasks.getNumberById(taskId) ].FOLDER = params["folder"];

				// console.log('folder', tasks.tasks[ tasks.getNumberById(taskId) ].FOLDER, tasks.getNumberById(taskId));

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
							var task = tasks.getTask(taskId);
							task.DEADLINE = moment(deferTo, "DD.MM.YYYY H:mm");
							tasks.updateTask(taskId, tasks._normalizeTask(task));
							tasks.showTasks();
						}
					}
				);

				break;
		    case "complete":
				api.getRow("task.item.complete", [taskId],
					function(err, res) {
						if (!err) {
							tasks.removeTask(taskId);
							tasks.fadeOutTask(taskId);
						}
					}
				);
				break;
		    case "approve":
				api.getRow("task.item.approve", [taskId],
					function(err, res) {
						if (!err) {
							tasks.removeTask(taskId);
							tasks.fadeOutTask(taskId);
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
									tasks.updateTask(taskId, res[0]);
									tasks.showTasks();
								}
							});
							// tasks.updateTask(taskId, {STATUS: 3});
						}
					}
				);
				break;
			default:
				break
		}
		// tasks.showTasks();
	},

	fadeOutTask: function (taskId) {
		$("[data-id='" + taskId + "']").fadeOut("slow", tasks.showTasks);
	},

	afterAction: function () {
		console.log("afterAction", this)
	},

	removeTask: function (taskId) {
		tasks.tasks = tasks.tasks.filter( function(d) {
			return ( d.ID != taskId );
		});
	},


	getTask: function (taskId) {
		return tasks.tasks.filter( function(d) {
			return ( d.ID == taskId );
		});
	},


	updateTask: function (taskId, params) {
		tasks.tasks = tasks.tasks.map( function(d) {
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
		// tasks.getTasks();
		var folderName = $("#nav-tasks .active").attr("data-folder-name");
		// console.log('showTasks folderName', folderName);

		/* Обнуляем все счетчики */
		var counters = [];
		counters["ALL"] = 0;
		for (var key in tasks.folders) {
			counters[tasks.folders[key].type] = 0;
		}


		var str = "";
		for (var i = 0; i < tasks.tasks.length; i++) {
			var d = tasks.tasks[i];
			d.FOLDER = ( ( tasks.taskFolders['t' + d.ID] )
				? tasks.taskFolders['t' + d.ID]
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
					+ "<a href=\"//" +  api.params.domain + "/company/personal/user/" + tasks.userId + "/tasks/task/view/" + d.ID + "/\" target='_blank'>" + d.TITLE + "</a>"
					+ "</div>"
					+ ( (folderName == "ALL")
						? "<div class=\"task-title-folder\">"
							+ tasks.folderNames[d.FOLDER]
							+ ( (d.FOLDER_TILL !== "")
								?  " / " + tasks.folderNames[d.FOLDER_TILL]
								: ""
							)
							+ "</div>"
						: ""
					)
					+ "</div>";


				/* Actions */
				str += "<div class=\"task-tools col-md-3 col-sm-3\">";

				// if (!d.ACTION_APPROVE && !d.ACTION_DISAPPROVE && (d.RESPONSIBLE_ID == tasks.userId))
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

				for (var key in tasks.folders) {
					if ( (tasks.folders[key].kind === "folder") || (tasks.folders[key].kind === "importance")  ) {
						str += "<li><a href=\"#\" data-function=\"moveToFolder\" data-folder=\"" + key + "\">" + tasks.folders[key]["name"] + "</a></li>";
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
				str += "<div class=\"task-team col-md-2 col-sm-2\">" + tasks.getName(d.CREATED_BY_LAST_NAME, d.CREATED_BY_NAME, d.CREATED_BY_LOGIN) + "<br/>"
					+ "<span class=\"glyphicon glyphicon-hand-down\"></span>&nbsp;" + "<br/>"
					+ tasks.getName(d.RESPONSIBLE_LAST_NAME, d.RESPONSIBLE_NAME, d.RESPONSIBLE_LOGIN) + "</div>";

				str += "</div>";
			}




			// if (groups.currentId == -1) {
			// 	str += ((d.GROUP_ID > 0) ? "<div class=\"task-group\">"
			// 		+ "<a href=\"/workgroups/group/" + d.GROUP_ID + "/\">" + groups.list[d.GROUP_ID] + "</a>"
			// 		+ "</div>" : "")
			// }


			// if ("ALL_TODAY_TOMORROW_WEEK".indexOf(folderName) > -1) {
			// 	str += "<span class=\"glyphicon glyphicon-folder-close\"></span>&nbsp;" + tasks.folders[d.FOLDER]["name"];
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
		tasks.setListener4TaskTool();
		tasks.resizeFrame();
	},

	// Формирует и показывает меню из типов задач
	showTasksTypes: function () {
		// var foldersAction = tasks.folders.concat( tasks.foldersActionTill.concat(tasks.foldersAction) );
		var str = "", currentKind;
		for (var i = 0; i < tasks.folders.length; i++) {
			if ( currentKind !== tasks.folders[i].kind ) {
				currentKind = tasks.folders[i].kind;
				if ( i !== 0) {
					str += "</ul>";
				}
				str += '<ul class="folder-kind folder-kind-' + currentKind + ' nav nav-pills nav-stacked col-md-3 col-sm-3 col-xs-6">';
			}
			str += '<li id="tab_' + tasks.folders[i].type + '"'
				+ ( ( tasks.folders[i].type == "INBOX" ) ? ' class="active"' : '' )
				+ ' data-folder-name="' + tasks.folders[i].type + '"'
				+ ' data-folder-kind="' + tasks.folders[i].kind
				+ '">'
				+ ' <a href="#">'
				+ tasks.folders[i].name
				+ '&nbsp;<span class="label label-default"></span>'
				+ '</a></li>';

			if ( i == tasks.folders.length) {
				str += "</ul>";
			}

		};
		$("#nav-tasks").html(str);
		$("#nav-tasks li").on("click", tasks.setActive)
	},

	setListener4TaskTool: function () {

		$(".task-tools a")
			.off()
			.on("click", function(e) {
				// console.log(".task-tools a onclick", e, this.dataset);
				e.preventDefault();
				if ((this.dataset) && (this.dataset.function)) {
					tasks.action.call(this, this.dataset.function);
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
		tasks.showTasks();

	},

	start: function () {
		// console.log("start");
		api.start();
		api.getCurrentUser(function(err, res) {
			if (!err) {
				tasks.userId = res.ID;
				// console.log('userId=' + tasks.userId);
				tasks.getTasks();
			} else {
				tasks._showError(err, 'Ошибка при получении данных о номере текущего пользователя');
			}
		});
		tasks.showTasksTypes();
		for (var key in tasks.folders) {
			// console.log('tasks.folders[key]', key, tasks.folders[key]);
			tasks.folderNames[tasks.folders[key].type] = tasks.folders[key].name;
		}
		tasks.saveFrameWidth();
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
		tasks.tasks = [];
		tasks.taskIds = "";
		var task = {};

		/* Фильтр задач для админа. BX24 админу отдает ВСЕ задачи, никакие SUBORDINATE_TASKS не помогают */
		res = res.filter(function(d) {
			return (
				( d.RESPONSIBLE_ID == tasks.userId )
				|| ( d.CREATED_BY == tasks.userId )
				|| ( d.AUDITORS.indexOf(tasks.userId) > -1 )
				|| ( d.ACCOMPLICES.indexOf(tasks.userId) > -1 )
			);
		});
		for (var i in res) {
			task = tasks._normalizeTask(res[i]);
			tasks.tasks.push(task);
			tasks.taskIds += task.ID + "+";
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

	tasks.start();
});
})();