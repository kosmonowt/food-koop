can.Component.extend({
	tag: 'members-app',
	scope : {
		memberGroups: new MemberGroup.List({}),
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
				handleRestSuccess("Dienst hinzugefügt.");
				$("div.tab-pane.active").removeClass('active');
				$('#tabTaskTypes').addClass('active');
			},handleRestError);
		},
		deleteTaskType: function(scope, el, ev) {
			ev.preventDefault();
			if (confirm("Möchtest Du diesen Dienst vollständig löschen?")) scope.destroy().then(function(){handleRestDestroy("Gelöscht:","Die Dienstart wurde gelöscht.")},handleRestError);
		}
	}
});

var template = can.view("appMustache");
$("body").html(template);