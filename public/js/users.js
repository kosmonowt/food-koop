can.Component.extend({
  tag: 'members-app',
  scope: {
    members: new Member.List({}),
    memberGroups: new MemberGroup.List({}),
    memberStatus: new MemberStatus.List({}),
    memberUsers: new can.List({}),
    select: function(members){
      this.attr('selectedMember', members);
    },
    delete: function(member) {
      if (confirm("Willst Du "+member.name+" wirklich löschen?")) member.destroy();
    },
    save: function(member) {
      member.save();
      this.removeAttr('selectedMember');
    },
    submitMember: function(scope,el,ev) {
      ev.preventDefault();
      // Auto assign all formfields
      var data = {};
      el.find("input, select").each(function(i,x){
        eval("data."+$(this).attr("name")+" = '"+$(this).val()+"';"); // Save Data
      });
      var member = new Member(data);
      member.save(
        function(member){ scope.members.push(member);},  // Success
        handleRestError // Error
        );

    },

    filterUsersByMember: function(m,el,ev) { this.memberUsers.replace(m.user); },

    /** 
     * Edit functions.
     * Has to move to a global prototype soon.
     */  
    editAttr: function(m,el,ev) { el.parent().toggle().siblings().toggle(); },
    editSubmit: function(m,el,ev) {
      /* This Function needs a structure: button MUST be sibling of input or other form field, ATTR value must be defined by name attribute in input or form field */
      el.parent().toggle().siblings().toggle();
      var input = el.siblings('.editValue');
      var val = input.val();
      var attrName = input.attr("name");

      if (typeof(el.data("scope")) != "undefined") {
        // for <Select>, need to do like this unless I found way to ascend the sections
        var modelList = eval("this."+el.data("scope"));
        var modelId = el.parents(".can-id").data("id");
        var modelRelated = el.parents(".can-id").data("model"); // the name of the current model (i.E. "member_group" when we want to change "member")

        modelList.each(function(model, index) {
          if (model.id == modelId) {
            model.attr(attrName,el.prev().val());
            model.attr(modelRelated,m);
            model.save();
          }
          return false;
        });
      } else {
        // for <Input>, quite easy
        m.attr(attrName,val);
        if (typeof(m.save)!= "undefined" ) {
          m.save();
        } else {
          //retrieve model
          var modelName = el.parents(".modelSection").data("model");
          eval("var u = new "+modelName+"(m)");
          u.save();
          // SAVE
        }
      }
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);
