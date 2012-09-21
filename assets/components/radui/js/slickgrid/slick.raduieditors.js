/***
 * Contains basic SlickGrid editors for MODX RAD-UI
 * @module Editors
 * @namespace Slick
 */
var JSONCache = [];
(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Editors": {
        "Text": TextEditor,
        "Integer": IntegerEditor,
        "Date": DateEditor,
        "YesNoSelect": YesNoSelectEditor,
        "BasicSelect": BasicSelectEditor,
        "Checkbox": CheckboxEditor,
        "PercentComplete": PercentCompleteEditor,
        "LongText": LongTextEditor
      }
    }
  });

  function TextEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-text' />")
          .appendTo(args.container)
          .bind("keydown.nav", function (e) {
            if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
              e.stopImmediatePropagation();
            }
          })
          .focus()
          .select();
    };

    this.destroy = function () {
      $input.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.getValue = function () {
      return $input.val();
    };

    this.setValue = function (val) {
      $input.val(val);
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field] || "";
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      if (args.column.validator) {
        var validationResults = args.column.validator($input.val());
        if (!validationResults.valid) {
          return validationResults;
        }
      }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function IntegerEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-text' />");

      $input.bind("keydown.nav", function (e) {
        if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
          e.stopImmediatePropagation();
        }
      });

      $input.appendTo(args.container);
      $input.focus().select();
    };

    this.destroy = function () {
      $input.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return parseInt($input.val(), 10) || 0;
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      if (isNaN($input.val())) {
        return {
          valid: false,
          msg: "Please enter a valid integer"
        };
      }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function DateEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;
    var calendarOpen = false;

    this.init = function () {
      if(typeof args != "undefined"){
		$input = $("<INPUT type=text class='editor-text' />");
      	$input.appendTo(args.container);
	  }
	  else {
		$input = $("input[type=text][data-editor*='Slick.Editors.Date']");
	  }
      $input.datepicker();
      $input.width($input.width() - 18);
    };

    this.destroy = function () {
      $.datepicker.dpDiv.stop(true, true);
      $input.datepicker("hide");
      $input.datepicker("destroy");
      $input.remove();
    };

    this.show = function () {
      if (calendarOpen) {
        $.datepicker.dpDiv.stop(true, true).show();
      }
    };

    this.hide = function () {
      if (calendarOpen) {
        $.datepicker.dpDiv.stop(true, true).hide();
      }
    };

    this.position = function (position) {
      if (!calendarOpen) {
        return;
      }
      $.datepicker.dpDiv
          .css("top", position.top + 30)
          .css("left", position.left);
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }
  /**
   * Custom Function:
   */
    function BasicSelectEditor(args) {
        var $select;
        var defaultValue;
        var scope = this;
    
        this.init = function() {
            option_str = '';
			if(typeof args != "undefined"){
			    if ( typeof args.column != "undefined" && jQuery.isPlainObject(args.column.selectOptions) && args.column.selectType == 'JSON') {
			        var JSON_data = '';
			        // console.log("JSON attempt: " + args.column.selectOptions.url);
			        // selectoptions = {"value": "value", "display": "Display", "url": "URL?n=v"}
			        if ( typeof JSONCache[args.column.selectOptions.url] != "undefined" ) {
			             JSON_data = JSONCache[args.column.selectOptions.url];
			        } else {
			            /* 
			            $.getJSON(args.column.selectOptions.url, function(data) {
                            console.log("Data: " + data); //uncomment this for debug
                            //alert (data.item1+" "+data.item2+" "+data.item3); //further debug
                            JSON_data = data.data;
                            console.log("JSON_Data: " + data.data); //uncomment this for debug
                            JSONCache[args.column.selectOptions.url] = JSON_data;
                        }); */
                        $.ajax({
                             type: "GET",
                             url: args.column.selectOptions.url,
                             async: false,
                             /*beforeSend: function(x) {
                                  if(x && x.overrideMimeType) {
                                   x.overrideMimeType("application/j-son;charset=UTF-8");
                                  }
                             },*/
                             dataType: "json",
                             success: function(data){
                                //do your stuff with the JSON data
                                //console.log("Data: " + data); //uncomment this for debug
                                //alert (data.item1+" "+data.item2+" "+data.item3); //further debug
                                JSON_data = data.data;
                                //console.log("JSON_Data: " + data.data); //uncomment this for debug
                                JSONCache[args.column.selectOptions.url] = JSON_data;
                             }
                        });
                        
                        
			        }
			        var len = JSON_data.length;
			        var v = args.column.selectOptions.value;
			        var n = args.column.selectOptions.display;
			        //console.log("JSON SELECT V: " + v + " -- " + n + " -- L: " + len + " ID: "+ JSON_data[1].id + " Display: " +JSON_data[i][n] );
                    for ( i=0; i < len; i++ ) {
                        option_str += '<option value="'+JSON_data[i][v]+'">'+JSON_data[i][n] +"</option>";
                    }
                } else if ( typeof args.column != "undefined" && jQuery.isPlainObject(args.column.selectOptions) &&  jQuery.isArray(args.column.selectOptions.array)) {
                    //console.log("Object attempt: " + args.column.selectType );
                    var len = args.column.selectOptions.array.length;
                    for ( i=0; i < len; i++ ) {
                        option_str += '<option value="'+args.column.selectOptions.array[i].v+'">'+args.column.selectOptions.array[i].n+"</option>";
                    }
                } else if ( typeof args.column != "undefined" &&  jQuery.isArray(args.column.selectOptions) ) {
                    //console.log("Array attempt");
                    // for (var key in object) {    print(object[key]);}
                    //var myArray = new Array();
                    //for each( args.column.selectOptions as key => value) {
                    // http://www.openjs.com/articles/for_loop.php
                    for ( var key in args.column.selectOptions) {
                        option_str += '<option value="'+key+'">'+args.column.selectOptions[key]+"</option>";
                    }
                } else {
                    if ( typeof args.column != "undefined" &&  args.column.selectOptions){
                        //console.log("String");
                        opt_values = args.column.selectOptions.split(',');
                    } else{
                        opt_values ="Yes,No".split(',');
                    }
                    for( i in opt_values ){
                        v = opt_values[i];
                        option_str += '<option value="'+v+'">'+v+"</option>";
                    }
                }
    			// The reason it was not appending right is because of the args.container already has a select, so it's trying to append a select to another select.
    			if ( typeof args.existingID != "undefined" ) {
    			    // form editors:
                    $select = $(option_str);
                    $select.appendTo('#'+args.existingID);
                } else {
                    // inline
                    $select = $('<select tabIndex="0" class="editor-select">'+ option_str +"</select>");
                    //console.log("O: " + option_str + " - C: " + args.container);
    				$select.appendTo(args.container);
    				$select.focus();
                }
			  } else {
			      // no arg set:
				$select = $("<OPTION value='Y'>Yes</OPTION><OPTION value='N'>No</OPTION>");
				var $container = $("select[data-editor*='Slick.Editors.BasicSelect']");
				$container.html($select);
			  }
            
        };

        /*this.init = function () {
          $select = $("<SELECT tabIndex='0' class='editor-yesno'><OPTION value='yes'>Yes</OPTION><OPTION value='no'>No</OPTION></SELECT>");
          $select.appendTo(args.container);
          $select.focus();
        };**/

        this.destroy = function () {
            $select.remove();
        };

        this.focus = function () {
            $select.focus();
        };

        this.loadValue = function (item) {
            $select.val(defaultValue = item[args.column.field]);
            $select.select();
        };

        this.serializeValue = function () {
            if ( args.column.selectOptions ){
                return $select.val();
            } else {
                return ($select.val() == "Yes");
            }
        };

        this.applyValue = function (item, state) {
            item[args.column.field] = state;
        };
    
        this.isValueChanged = function () {
            return ($select.val() != defaultValue);
        };

        this.validate = function () {
            return {
                valid: true,
                msg: null
            };
        };

        this.init();
    }
    /**
     * 
     */
  function YesNoSelectEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<SELECT tabIndex='0' class='editor-yesno'><OPTION value='yes'>Yes</OPTION><OPTION value='no'>No</OPTION></SELECT>");
	  if(typeof args != "undefined"){
      	$select.appendTo(args.container);
	  }
	  else {
		
		$select = $("<OPTION value='Y'>Yes</OPTION><OPTION value='N'>No</OPTION>");
		var $container = $("select[data-editor*='Slick.Editors.YesNoSelect']");
		$container.html($select);
	  }
      $select.focus();
    };

    this.destroy = function () {
      $select.remove();
    };

    this.focus = function () {
      $select.focus();
    };

    this.loadValue = function (item) {
      $select.val((defaultValue = item[args.column.field]) ? "yes" : "no");
      $select.select();
    };

    this.serializeValue = function () {
      return ($select.val() == "yes");
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return ($select.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function CheckboxEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<INPUT type=checkbox value='true' class='editor-checkbox' hideFocus>");
      $select.appendTo(args.container);
      $select.focus();
    };

    this.destroy = function () {
      $select.remove();
    };

    this.focus = function () {
      $select.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      if (defaultValue) {
        $select.attr("checked", "checked");
      } else {
        $select.removeAttr("checked");
      }
    };

    this.serializeValue = function () {
      return $select.attr("checked");
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return ($select.attr("checked") != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function PercentCompleteEditor(args) {
    var $input, $picker;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-percentcomplete' />");
      $input.width($(args.container).innerWidth() - 25);
      $input.appendTo(args.container);

      $picker = $("<div class='editor-percentcomplete-picker' />").appendTo(args.container);
      $picker.append("<div class='editor-percentcomplete-helper'><div class='editor-percentcomplete-wrapper'><div class='editor-percentcomplete-slider' /><div class='editor-percentcomplete-buttons' /></div></div>");

      $picker.find(".editor-percentcomplete-buttons").append("<button val=0>Not started</button><br/><button val=50>In Progress</button><br/><button val=100>Complete</button>");

      $input.focus().select();

      $picker.find(".editor-percentcomplete-slider").slider({
        orientation: "vertical",
        range: "min",
        value: defaultValue,
        slide: function (event, ui) {
          $input.val(ui.value)
        }
      });

      $picker.find(".editor-percentcomplete-buttons button").bind("click", function (e) {
        $input.val($(this).attr("val"));
        $picker.find(".editor-percentcomplete-slider").slider("value", $(this).attr("val"));
      })
    };

    this.destroy = function () {
      $input.remove();
      $picker.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      $input.val(defaultValue = item[args.column.field]);
      $input.select();
    };

    this.serializeValue = function () {
      return parseInt($input.val(), 10) || 0;
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ((parseInt($input.val(), 10) || 0) != defaultValue);
    };

    this.validate = function () {
      if (isNaN(parseInt($input.val(), 10))) {
        return {
          valid: false,
          msg: "Please enter a valid positive number"
        };
      }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  /*
   * An example of a "detached" editor.
   * The UI is added onto document BODY and .position(), .show() and .hide() are implemented.
   * KeyDown events are also handled to provide handling for Tab, Shift-Tab, Esc and Ctrl-Enter.
   */
  function LongTextEditor(args) {
    var $input, $wrapper;
    var defaultValue;
    var scope = this;

    this.init = function () {
      var $container = $("body");

      $wrapper = $("<DIV style='z-index:10000;position:absolute;background:white;padding:5px;border:3px solid gray; -moz-border-radius:10px; border-radius:10px;'/>")
          .appendTo($container);

      $input = $("<TEXTAREA hidefocus rows=5 style='backround:white;width:250px;height:80px;border:0;outline:0'>")
          .appendTo($wrapper);

      $("<DIV style='text-align:right'><BUTTON>Save</BUTTON><BUTTON>Cancel</BUTTON></DIV>")
          .appendTo($wrapper);

      $wrapper.find("button:first").bind("click", this.save);
      $wrapper.find("button:last").bind("click", this.cancel);
      $input.bind("keydown", this.handleKeyDown);

      scope.position(args.position);
      $input.focus().select();
    };

    this.handleKeyDown = function (e) {
      if (e.which == $.ui.keyCode.ENTER && e.ctrlKey) {
        scope.save();
      } else if (e.which == $.ui.keyCode.ESCAPE) {
        e.preventDefault();
        scope.cancel();
      } else if (e.which == $.ui.keyCode.TAB && e.shiftKey) {
        e.preventDefault();
        grid.navigatePrev();
      } else if (e.which == $.ui.keyCode.TAB) {
        e.preventDefault();
        grid.navigateNext();
      }
    };

    this.save = function () {
      args.commitChanges();
    };

    this.cancel = function () {
      $input.val(defaultValue);
      args.cancelChanges();
    };

    this.hide = function () {
      $wrapper.hide();
    };

    this.show = function () {
      $wrapper.show();
    };

    this.position = function (position) {
      $wrapper
          .css("top", position.top - 5)
          .css("left", position.left - 5)
    };

    this.destroy = function () {
      $wrapper.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      $input.val(defaultValue = item[args.column.field]);
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }
})(jQuery);
