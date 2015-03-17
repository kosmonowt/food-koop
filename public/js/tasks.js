can.Component.extend({
	tag: 'members-app',
	scope : {
		memberGroups: new MemberGroup.List({}),
		members: new Member.List({}),
		weekList: new WeekList.List({}),
		taskTypes: new TaskType.List({}),
		taskType: new TaskType(),
		openNewTaskTab : function(scope, el, ev) {
			$("div.tab-pane.active").removeClass('active');
			$('#taskTypeEditor').addClass('active');
		},
		saveTaskType: function(scope, el, ev) {
			ev.preventDefault();
			var taskType = this.taskType;
			var taskTypes = this.taskTypes;
			$(el).find("input, select, textarea").each(function(){
				var name = $(this).attr("name");
				var val = $(this).val();
				taskType.attr(name,val);
			});
			taskType.save().then(function(data,xhr){
				taskTypes.push(JSON.parse(data));
				handleRestUpdate("Dienst hinzugefügt.");
				$("div.tab-pane.active").removeClass('active');
				$('#tabTaskTypes').addClass('active');
			},handleRestError);
		},
		deleteTaskType: function(scope, el, ev) {
			ev.preventDefault();
			if (confirm("Möchtest Du diesen Dienst vollständig löschen?")) scope.destroy().then(function(){handleRestDestroy("Gelöscht:","Die Dienstart wurde gelöscht.")},handleRestError);
		},
		toggleTaskTypeState: function(scope, el, ev) {
			ev.preventDefault();
			// Only Toggle When confirmed or not active yet
			if ((scope.attr("active") && 
				 confirm("Wenn Du den Status auf Inaktiv stellst werden alle zukünftigen Dienste (die zu diesem Dienst gehören) auf inaktiv gestellt.")
				|| (!scope.attr("active")))) {
				scope.attr("active",(scope.attr("active")+1)%2);
				new TaskType(scope).save().then(function(){handleRestUpdate("Erfolg","Dienstartstatus geändert.")},handleRestError);
			}
		},
		updateTask: function(scope, el, ev) {
			new Task(scope).save().then(function(){handleRestUpdate("Erfolg","Dienst Aktualisiert")}, handleRestError);
		}
	}
});

var template = can.view("appMustache");
$("body").html(template);