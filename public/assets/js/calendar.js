(function() {

  $(document).ready(function() {
    var $calendar, $timestampsOfOptions, id, setupStartAndEndTimeFields, unixtimeToDate, updateEvent, updateMessage;
    $calendar = $('#calendar');
    id = 10;
    unixtimeToDate = function(ut) {
      var tD;
      tD = new Date(ut * 1000);
      tD.setTime(tD.getTime() + (60 * 60 * 1000));
      return tD;
    };
    updateEvent = function(calEvent, $event) {
      var $dialogContent, bodyField, categoryField, endField, startField, titleField;
      $dialogContent = $("#event_edit_container");
      $dialogContent.find("input").val("");
      $dialogContent.find("textarea").val("");
      startField = $dialogContent.find("select[name='start']").val(calEvent.start);
      endField = $dialogContent.find("select[name='end']").val(calEvent.end);
      categoryField = $dialogContent.find("select[name='category']").val(calEvent.category);
      titleField = $dialogContent.find("input[name='title']").val(calEvent.title);
      bodyField = $dialogContent.find("textarea[name='body']").val(calEvent.body);
      $dialogContent.dialog({
        modal: true,
        title: '新しいイベントの登録',
        close: function() {
          $dialogContent.dialog("destroy");
          $dialogContent.hide();
          return $('#calendar').weekCalendar("removeUnsavedEvents");
        },
        buttons: {
          '保存': function() {
            var post_data;
            post_data = {
              event_id: calEvent.id,
              start: startField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
              end: endField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, ''),
              title: titleField.val(),
              body: bodyField.val(),
              category: categoryField.val()
            };
            return $.ajax({
              data: post_data,
              type: 'post',
              url: '/calendar/event/update.json',
              success: function(data) {
                var err, errorHtml, _i, _len, _ref, _results;
                if (((data != null ? data.success : void 0) != null) && data.success === true) {
                  calEvent.id = data.event.id;
                  calEvent.start = new Date(data.event.start);
                  calEvent.end = new Date(data.event.end);
                  calEvent.title = data.event.title;
                  calEvent.body = data.event.body;
                  calEvent.category = data.event.category;
                  $calendar.weekCalendar("removeUnsavedEvents");
                  $calendar.weekCalendar("updateEvent", calEvent);
                  return $dialogContent.dialog("close");
                } else {
                  if ((data != null ? data.errors : void 0) != null) {
                    errorHtml = '';
                    _ref = data.errors;
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                      err = _ref[_i];
                      errorHtml += '<p>' + err + '</p>';
                      _results.push($('.dialog_errors').html(errorHtml));
                    }
                    return _results;
                  }
                }
              }
            });
          },
          'キャンセル': function() {
            return $dialogContent.dialog("close");
          }
        }
      }).show();
      $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
      return setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
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
      height: function($calendar) {
        return $(window).height() - 60;
      },
      eventRender: function(calEvent, $event) {
        if (((calEvent != null ? calEvent.category : void 0) != null) && parseInt(calEvent.category) > 0) {
          return $event.addClass('wc-cal-event-color' + calEvent.category);
        }
      },
      eventNew: function(calEvent, $event) {
        return updateEvent(calEvent, $event);
      },
      eventClick: function(calEvent, $event) {
        return updateEvent(calEvent, $event);
      },
      eventResize: function(calEvent, $event) {
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
      },
      data: function(start, end, callback) {
        var post_data;
        return post_data = $.ajax({
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

}).call(this);
