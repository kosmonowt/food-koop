can.Component.extend({
	tag: 'contents-app',
	scope : {
		contents: new Content.List({}),
		allContents: new can.List(),
		publicContents: new can.List(),
		memberContents: new can.List(),
		presentationContents: new can.List(),
		currentContent: new Content(),
		contentTypes: new ContentType.List({}),
		createContent: function(scope,el,ev) {
			ev.preventDefault();
			var content = new Content();
			var contents = this.contents;
			var s = this;

			el.find("input, select, button, textarea").each(function(i,x){
				if ( $(this).attr("type") != "radio" || ($(this).attr("type") == "radio" && $(this).attr("checked")) ) {
					content.attr($(this).attr("name"),$(this).val());
				}
			});
			var id = content.id;
			content.save().then(function(data,xhr){
				if (id < 1) {
					// Update Main List and Replace all List Elements
					contents.push(data);
					s.allContents.replace(contents);
					s.publicContents.replace(new can.List());
					s.memberContents.replace(new can.List());
					s.presentationContents.replace(new can.List());
					$("#btn-all-contents").trigger('click');
					handleRestCreate("Erfolg:","Der Eintrag wurde erstellt.");
				} else {
					handleRestCreate("Erfolg:","Der Eintrag wurde gespeichert.");
				}
			},handleRestError);
		},
		editContent: function(scope,el,ev) {
			$("#tabNewControl").trigger('click'); // Open Create Tab.
			for (var attr in scope.attr()) this.currentContent.attr(attr,eval("scope."+attr)); // Copy all Attributes into this object
		},
		delete: function(content) {
			if (confirm("Willst Du diesen Beitrag wirklich löschen?")) {
        		var contents = this.contents;
        		var id = content.attr("id");
        		content.destroy().then(function(){
          		handleRestDestroy("Gelöscht:","Der Beitrag wurde gelöscht.");
          		contents.replace(contents.filter(function(i,x,l){return i.attr("id") != id;}));
        		},handleRestError);
      		}
		},
		/**
		 * Triggered by the selection button to select a particular contentType
		 **/
		contentTypeSelect: function(scope, el, ev) {
			if (el.hasClass('active')) return false;
			el.parent().children('.active').removeClass('active');
			el.addClass('active');
			if (!scope.allContents.length &&
				!scope.publicContents.length &&
				!scope.memberContents.length &&
				!scope.presentationContents.length) {
				scope.contents.forEach(function(e,i,l){
					scope.allContents.push(e); // Fill list with original content data
					if (e.type_id == 1) scope.publicContents.push(e); // Fill only PublicContents here
					else if (e.type_id == 2) scope.memberContents.push(e); // Fill only Membercontents here
					else if (e.type_id == 3) scope.presentationContents.push(e); // Fill only Presentation Content here
				});
			}
			// Replace the main list with the content-type specific.
			var list;
			if (el.data("val") == 0) list = scope.allContents;
			else if (el.data("val") == 1) list = scope.publicContents;
			else if (el.data("val") == 2) list = scope.memberContents;
			else if (el.data("val") == 3) list = scope.presentationContents;
			scope.contents.replace(list);
		}
	},
});

var template = can.view("appMustache");
$("body").html(template);