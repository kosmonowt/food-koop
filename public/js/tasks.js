can.Component.extend({
	tag: 'members-app',
	scope : {
		openNewTaskTab : function(scope, ev, el) {
			$("div.tab-pane.active").removeClass('active');
			$('#taskTypeEditor').addClass('active');
		}
	}
});

var template = can.view("appMustache");
$("body").html(template);