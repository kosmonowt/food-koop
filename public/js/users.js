can.Component.extend({
  tag: 'members-app',
  scope: {
    myself: new Myself.findOne(),
    members: new Member.List({}),
    memberGroups: new MemberGroup.List({}),
    memberStatus: new MemberStatus.List({}),
    memberUsers: new can.List({}),
    userGroups: new UserGroup.List({}),
    currentMember: new can.Map(),
    currentLedger: new can.List(),
    select: function(members){
      this.attr('selectedMember', members);
    },
    delete: function(member) {
      if (confirm("Willst Du "+member.name+" wirklich löschen?")) member.destroy().then(function(){handleRestDestroy("Gelöscht:","Das Mitglied wurde gelöscht."),handleRestError});
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
        function(member){
          member.attr("ledger_balance",null);
          scope.members.push(member);
          handleRestCreate("Erfolg","Mitglied wurde erfolgreich angelegt");
          el.find("input").each(function(i,x){$(this).val("");});
          $("#tabIndexControl").trigger("click");
        },  // Success
        handleRestError // Error
        );
    },
    /**************************/
    /****** USER SECTION ******/
    /**************************/
    filterUsersByMember: function(m,el,ev) { this.memberUsers.replace(m.user); this.currentMember.attr("id", m.id);  },
    /** Create User **/
    userCreate: function(scope,el,ev) {
      ev.preventDefault();
      var data = {};
      $("#userCreateForm").find("input, select").each(function(i,x){
        eval("data."+$(this).attr("name")+" = '"+$(this).val()+"';"); // Save Data
      });
      var user = new User(data);
      var memberUsers = this.memberUsers;
      user.save(
        function(user){ 
          // Assign User to member
          member = scope.members.filter(function(member,ix,list) {return member.id == user.member_id;});
          memberUsers.push(user);
          handleRestCreate("Erfolg","Benutzer wurde erfolgreich angelegt.");
          $("#userCreateForm .form-control").val("");
        }, handleRestError // Error
      );
    },
    /** Delete User **/
    userDelete: function(user) {
      if (confirm("Willst Du "+user.name+" wirklich entfernen?")) 
        var u = new User(user);
        var id = u.attr("id");
        var memberUsers = this.memberUsers;
        u.destroy().then(function(){
          memberUsers.replace(memberUsers.filter(function(i,x,l){return i.attr("id") != id;}));
          handleRestDestroy("Gelöscht:","Der Benutzer wurde gelöscht.");
        },handleRestError);
    },    
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
          m.save(function(){},handleRestError);
        } else {
          //retrieve model
          var modelName = el.parents(".modelSection").data("model");
          eval("var u = new "+modelName+"(m)");
          u.save(function(){
            handleRestCreate("Erfolg","Benutzer wurde erfolgreich bearbeitet.");
          },handleRestError);
          // SAVE
        }
      }
    },
    /*******************************************************************/
    /******************* LEDGER SECTION ********************************/
    /*******************************************************************/
    calculateTotal: function() {
      var currentMember = this.currentMember;
      currentMember.attr("balance",0.0);
      var currentLedger = this.currentLedger;
      console.log(currentLedger);
      currentLedger.each(function(m,x){
        console.log(currentMember.attr("balance"));
        console.log(m.attr("balance"));
        var newBalance = parseFloat(currentMember.attr("balance")) + parseFloat(m.attr("balance"))
        console.log(currentMember.attr("balance"));
        currentMember.attr("balance",newBalance);
      });
    },
    checkBalanceValue: function(scope, el, ev) {
      if ($(el).val() < 0 && $("#memberLedgerVwz").val() == "Einzahlung") $("#memberLedgerVwz").val("Belastung");
      else if ($(el).val() > 0 && $("#memberLedgerVwz").val() == "Belastung") $("#memberLedgerVwz").val("Einzahlung");
    },
    openLedger: function(scope, el, ev) {
      var currentMember = this.currentMember;
      var controller = this;
      currentMember.attr("name",scope.name);
      currentMember.attr("id",scope.id);

      var currentLedger = this.currentLedger;
      can.$.get(sUrl+"memberLedger/member/"+scope.id).then(
        function(data,xhr){ 
          currentLedger.replace(JSON.parse(data));
          controller.calculateTotal();
        }, handleRestError);
      $('.tab-pane').removeClass('active');
      $('#tabNav li.active').removeClass('active');
      $('#ledger').addClass("active");
      $('#memberLedgerBalance').trigger("focus");
      $('#memberLedgerDate').val(new Date().toDateInputValue());
    },
    submitLedgerTransaction: function(scope, el, ev) {
      ev.preventDefault();
      var currentLedger = this.currentLedger;
      var controller = this;
      var data = {};
      data.balance = parseFloat($("#memberLedgerBalance").val());
      data.vwz = $("input[name='memberLedgerVwz']").val();
      data.date = $("input[name='memberLedgerDate']").val();
      data.member_id = this.currentMember.attr("id");
      
      var l = new MemberLedger(data);
      l.save().then(function(data,xhr){
        currentLedger.push(data);
        controller.calculateTotal();
      },handleRestError);
      $("#memberLedgerBalance").val("");
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);
