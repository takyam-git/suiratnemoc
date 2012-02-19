(function() {
  var _this = this;

  String.prototype.trim = function() {
    return this.replace(/^(\s|　)+|(\s|　)+$/g, '');
  };

  String.prototype.htmldecode = function() {
    return this.replace(/\&amp\;/g, '&').replace(/\&quot\;/g, '"').replace(/\&\#039\;/g, '\'').replace(/\&lt\;/g, '<').replace(/\&gt\;/g, '>');
  };

  $(document).ready(function() {
    var $calendar, $timestampsOfOptions, id, modifyEvent, setupStartAndEndTimeFields, unixtimeToDate, updateEvent, updateMessage;
    $calendar = $('#calendar');
    id = 10;
    _this.$dialogContent = $("#event_edit_container");
    _this.$removeButton = $('a#removeButton', _this.$dialogContent);
    _this.$saveButton = $('a#saveButton', _this.$dialogContent);
    _this.$categorySet = $('#categorySet', $dialogContent);
    _this.categorySetDefault = _this.$categorySet.text();
    unixtimeToDate = function(ut) {
      var tD;
      tD = new Date(ut * 1000);
      tD.setTime(tD.getTime() + (60 * 60 * 1000));
      return tD;
    };
    updateEvent = function(calEvent, $event) {
      var $dialogContent, $errorField, $removeCategoryDialog, $removeCategoryDoButton, $removeCategoryError, categoryField, endField, startField, titleField;
      $dialogContent = _this.$dialogContent;
      $errorField = $('#categoryDialogError', $dialogContent);
      $dialogContent.find("input").val("");
      $dialogContent.find("textarea").val("");
      startField = $dialogContent.find("select[name='start']").val(calEvent.start);
      endField = $dialogContent.find("select[name='end']").val(calEvent.end);
      categoryField = $dialogContent.find("input[name='category']").val(calEvent.category);
      titleField = $dialogContent.find("textarea[name='title']").val(calEvent.title.htmldecode());
      if (calEvent.category_name != null) {
        _this.$categorySet.text(calEvent.category_name);
      } else {
        _this.$categorySet.text(_this.categorySetDefault);
      }
      $removeCategoryDialog = $('#removeCategoryDialog');
      $removeCategoryDoButton = $('#categoryDialogRemoveDoButton', $removeCategoryDialog);
      $removeCategoryError = $('#removeCategoryDialogError', $removeCategoryDialog);
      $dialogContent.off('shown').off('hidden').on('shown', function() {
        var isNewEvent;
        $errorField.html('').hide();
        isNewEvent = !((calEvent.id != null) && parseInt(calEvent.id) > 0);
        if (isNewEvent) {
          _this.$removeButton.hide();
        } else {
          _this.$removeButton.show();
        }
        _this.$removeButton.off('click').on('click', function() {
          $removeCategoryError.html('').hide();
          $dialogContent.modal('hide');
          $removeCategoryDialog.off('shown').on('shown', function() {
            return $('#categoryDialogRemoveDoButton', this.$removeButton).off('click').on('click', function() {
              $.ajax({
                url: '/calendar/event/remove.json',
                type: 'post',
                data: {
                  id: calEvent.id
                },
                dataType: 'json',
                success: function(data) {
                  var ary, err, errors, key, _i, _len, _ref;
                  if (!data.success) {
                    errors = [];
                    _ref = data.errors;
                    for (key in _ref) {
                      ary = _ref[key];
                      for (_i = 0, _len = ary.length; _i < _len; _i++) {
                        err = ary[_i];
                        errors.push(err);
                      }
                    }
                    return $removeCategoryError.append($('<p>' + errors.join('<br>') + '</p>')).show();
                  } else {
                    $calendar.weekCalendar("removeEvent", calEvent.id);
                    return $removeCategoryDialog.modal('hide');
                  }
                },
                complete: function(data) {}
              });
              return false;
            });
          }).on('hidden', function() {}).modal();
          return false;
        });
        return _this.$saveButton.off('click').on('click', function() {
          var post_data;
          $errorField.html('').hide();
          post_data = {
            event_id: calEvent.id,
            start: startField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
            end: endField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
            title: titleField.val(),
            category: categoryField.val()
          };
          $.ajax({
            data: post_data,
            type: 'post',
            url: '/calendar/event/update.json',
            success: function(data) {
              var err, errorHtml, _i, _len, _ref;
              if (((data != null ? data.success : void 0) != null) && data.success === true) {
                calEvent.id = data.event.id;
                calEvent.start = new Date(data.event.start);
                calEvent.end = new Date(data.event.end);
                calEvent.title = data.event.title;
                calEvent.category = data.event.category;
                calEvent.colorset = data.event.colorset;
                calEvent.category_name = data.event.category_name;
                $calendar.weekCalendar("removeUnsavedEvents");
                $calendar.weekCalendar("updateEvent", calEvent);
                return $dialogContent.modal("hide");
              } else {
                if ((data != null ? data.errors : void 0) != null) {
                  errorHtml = '';
                  _ref = data.errors;
                  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    err = _ref[_i];
                    errorHtml += '<p>' + err + '</p>';
                  }
                  return $errorField.html(errorHtml).show();
                }
              }
            }
          });
          return false;
        });
      }).on('hidden', function() {
        $dialogContent.dialog("destroy");
        $dialogContent.hide();
        return $('#calendar').weekCalendar("removeUnsavedEvents");
      }).modal();
      $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start) + ' ' + $calendar.weekCalendar("formatTime", calEvent.start) + ' - ' + $calendar.weekCalendar("formatTime", calEvent.end));
      return setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
    };
    modifyEvent = function(calEvent, $event) {
      var post_data;
      post_data = {
        event_id: calEvent.id,
        start: calEvent.start.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
        end: calEvent.end.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
        title: calEvent.title,
        body: calEvent.body,
        category: calEvent.category
      };
      return $.ajax({
        data: post_data,
        type: 'post',
        url: '/calendar/event/update.json',
        success: function(data) {
          if (((data != null ? data.success : void 0) != null) && data.success === false) {
            return $('<p>時間の変更の保存に失敗しました<br>リロードしてやり直してください</p>').dialog({
              title: '保存に失敗しました',
              height: 150,
              buttons: {
                'リロードする': function() {
                  return location.href = location.href;
                }
              }
            });
          }
        }
      });
    };
    $calendar.weekCalendar({
      dateFormat: 'Y/m/d',
      timeFormat: 'H:i',
      shortMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
      longMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
      shortDays: ['日', '月', '火', '水', '木', '金', '土'],
      longDays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
      use24Hour: true,
      displayOddEven: true,
      firstDayOfWeek: 1,
      timeslotsPerHour: 4,
      businessHours: {
        start: 9,
        end: 19,
        limitDisplay: false
      },
      height: function($calendar) {
        return $(window).height() - 60;
      },
      timeSeparator: ' - ',
      newEventText: '',
      eventBody: function(calEvent, $event) {
        var text;
        text = '';
        if ((calEvent != null ? calEvent.category_name : void 0) != null) {
          text += '<strong>' + calEvent.category_name + '</strong><br><br>';
        }
        text += calEvent.title.replace(/(\r\n|\r|\n)/g, '<br>');
        return text;
      },
      eventRender: function(calEvent, $event) {
        if (((calEvent != null ? calEvent.colorset : void 0) != null) && parseInt(calEvent.colorset) > 0) {
          return $event.addClass('wc-cal-event-color' + calEvent.colorset);
        }
      },
      eventNew: function(calEvent, $event) {
        return updateEvent(calEvent, $event);
      },
      eventClick: function(calEvent, $event) {
        return updateEvent(calEvent, $event);
      },
      eventDrop: function(calEvent, $event) {
        return modifyEvent(calEvent, $event);
      },
      eventResize: function(calEvent, $event) {
        return modifyEvent(calEvent, $event);
      },
      data: function(start, end, callback) {
        return $.ajax({
          data: {
            start: start.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
            end: end.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
          },
          type: 'post',
          url: '/calendar/event/events.json',
          success: function(data) {
            var init;
            init = {
              'events': []
            };
            if ((data != null ? data.events : void 0) != null) {
              $.each(data.events, function(key, value) {
                var _ref, _ref2, _ref3;
                if (((_ref = value.category) != null ? _ref.color_set : void 0) != null) {
                  data.events[key].colorset = value.category.color_set;
                } else {
                  data.events[key].colorset = 0;
                }
                if (((_ref2 = value.category) != null ? _ref2.name : void 0) != null) {
                  data.events[key].category_name = value.category.name;
                } else {
                  data.events[key].category_name = '';
                }
                if (((_ref3 = value.category) != null ? _ref3.id : void 0) != null) {
                  return data.events[key].category = value.category.id;
                } else {
                  return data.events[key].category = 0;
                }
              });
              init.events = data.events;
            }
            return callback(init);
          },
          error: function() {
            return callback([]);
          }
        });
      },
      buttonText: {
        lastWeek: '前週',
        today: '今日',
        nextWeek: '次週'
      }
    });
    $("#data_source").change(function() {
      $calendar.weekCalendar("refresh");
      return updateMessage();
    });
    updateMessage = function() {
      var dataSource;
      dataSource = $("#data_source").val();
      return $("#message").fadeOut(function() {
        if (dataSource === "1") {
          $("#message").text("Displaying event data set 1 with timeslots per hour of 4 and timeslot height of 20px");
        } else if (dataSource === "2") {
          $("#message").text("Displaying event data set 2 with timeslots per hour of 3 and timeslot height of 30px");
        } else if (dataSource === "3") {
          $("#message").text("Displaying event data set 3 with allowEventDelete enabled. Events before today will not be deletable. A confirmation dialog is opened when you delete an event.");
        } else {
          $("#message").text("Displaying no events.");
        }
        return $(this).fadeIn();
      });
    };
    $timestampsOfOptions = {
      start: [],
      end: []
    };
    setupStartAndEndTimeFields = function($startTimeField, $endTimeField, calEvent, timeslotTimes) {
      var $endTimeOptions, endSelected, endTime, startSelected, startTime, time, _i, _len;
      $startTimeField.empty();
      $endTimeField.empty();
      for (_i = 0, _len = timeslotTimes.length; _i < _len; _i++) {
        time = timeslotTimes[_i];
        startTime = time.start;
        endTime = time.end;
        startSelected = "";
        if (startTime.getTime() === calEvent.start.getTime()) {
          startSelected = "selected=\"selected\"";
        }
        endSelected = "";
        if (endTime.getTime() === calEvent.end.getTime()) {
          endSelected = "selected=\"selected\"";
        }
        $startTimeField.append("<option value=\"" + startTime + "\" " + startSelected + ">" + time.startFormatted + "</option>");
        $endTimeField.append("<option value=\"" + endTime + "\" " + endSelected + ">" + time.endFormatted + "</option>");
        $timestampsOfOptions.start[time.startFormatted] = startTime.getTime();
        $timestampsOfOptions.end[time.endFormatted] = endTime.getTime();
      }
      $endTimeOptions = $endTimeField.find("option");
      return $startTimeField.trigger("change");
    };
    updateMessage();
    return true;
  });

  $(function() {
    var $colorsContainer, $triggerButton, $valueField, defaultButtonText;
    $triggerButton = $('#categorySet');
    defaultButtonText = $triggerButton.text();
    $valueField = $('#category');
    $colorsContainer = $('#categoryList');
    return $triggerButton.menu({
      content: $colorsContainer.clone().html(),
      backLinkText: '戻る',
      crumbDefaultText: '',
      maxHeight: 300,
      onSelect: function($item) {
        var colorSetID;
        colorSetID = $item.data('id');
        if (colorSetID === 'cancel') {
          $triggerButton.text(defaultButtonText);
          $valueField.val('');
        } else {
          colorSetID = parseInt(colorSetID);
          if (colorSetID > 0) {
            $triggerButton.text($item.text());
            $valueField.val(colorSetID);
          }
        }
        return true;
      }
    });
  });

}).call(this);
