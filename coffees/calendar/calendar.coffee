$(document).ready ->
  $calendar = $('#calendar');
  id = 10;
  
  unixtimeToDate = (ut) ->
    tD = new Date( ut * 1000 );
    tD.setTime( tD.getTime() + (60*60*1000) );
    return tD;
  
  updateEvent = (calEvent, $event) ->
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
      close: ->
        $dialogContent.dialog("destroy");
        $dialogContent.hide();
        $('#calendar').weekCalendar("removeUnsavedEvents");
      buttons: {
        '保存' : ->
          post_data = {
            event_id: calEvent.id
            start : startField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
            end : endField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
            title : titleField.val()
            body : bodyField.val()
            category : categoryField.val()
          }
          
          $.ajax({
            data: post_data
            type: 'post'
            url: '/calendar/event/update.json'
            success: (data) ->
              if data?.success? && data.success is true
                calEvent.id = data.event.id;
                calEvent.start = new Date(data.event.start)
                calEvent.end = new Date(data.event.end)
                calEvent.title = data.event.title
                calEvent.body = data.event.body
                calEvent.category = data.event.category
                
                $calendar.weekCalendar("removeUnsavedEvents");
                $calendar.weekCalendar("updateEvent", calEvent);
                $dialogContent.dialog("close");
              else
                if data?.errors?
                  errorHtml = ''
                  for err in data.errors
                    errorHtml += '<p>' + err + '</p>'
                    $('.dialog_errors').html(errorHtml)
          })
        'キャンセル' : ->
          $dialogContent.dialog("close");
      }
    }).show();
    $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start));
    setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
  
  $calendar.weekCalendar({
    dateFormat: 'Y/m/d',
    timeFormat: 'H:i',
    shortMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    longMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    shortDays: ['日', '月', '火', '水', '木', '金', '土'],
    longDays: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
    use24Hour: true,
    displayOddEven:true,
    firstDayOfWeek : 1,
    timeslotsPerHour: 4,
    height: ($calendar) ->
      return $(window).height() - 60;
    eventRender : (calEvent, $event) ->
      if calEvent?.category? and parseInt(calEvent.category) > 0
        $event.addClass('wc-cal-event-color' + calEvent.category)
    eventNew : (calEvent, $event) ->
      updateEvent(calEvent, $event)
    eventClick: (calEvent, $event) ->
      updateEvent(calEvent, $event)
    eventResize : (calEvent, $event) ->
      post_data = {
        event_id: calEvent.id
        start : calEvent.start.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
        end : calEvent.end.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
        title : calEvent.title
        body : calEvent.body
        category : calEvent.category
      }
      
      $.ajax({
        data: post_data
        type: 'post'
        url: '/calendar/event/update.json'
        success: (data) ->
          if data?.success? && data.success is false
            $('<p>時間の変更の保存に失敗しました<br>リロードしてやり直してください</p>').dialog({
              title: '保存に失敗しました',
              height: 150,
              buttons: {
                'リロードする': ->
                  location.href = location.href
              }
            });
      })
    data: (start, end, callback) ->
      post_data = 
      
      $.ajax({
        data: {
          start : start.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
          end : end.toString().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
        }
        type: 'post'
        url: '/calendar/event/events.json'
        success: (data) ->
          init = {'events' : []}
          if data?.events?
            init.events = data.events
            
          callback(init)
        error: ->
          callback([])
      })
    buttonText: {
      lastWeek: '前週',
      today: '今日',
      nextWeek: '次週'
    }
  });

  $("#data_source").change(->
    $calendar.weekCalendar("refresh");
    updateMessage();
  );

  updateMessage = ->
    dataSource = $("#data_source").val();
    $("#message").fadeOut(->
      if dataSource is "1"
        $("#message").text("Displaying event data set 1 with timeslots per hour of 4 and timeslot height of 20px");
      else if dataSource is "2"
        $("#message").text("Displaying event data set 2 with timeslots per hour of 3 and timeslot height of 30px");
      else if dataSource is "3"
        $("#message").text("Displaying event data set 3 with allowEventDelete enabled. Events before today will not be deletable. A confirmation dialog is opened when you delete an event.");
      else
        $("#message").text("Displaying no events.");
      $(this).fadeIn();
    );
  
  $timestampsOfOptions = {start:[],end:[]};
  setupStartAndEndTimeFields = ($startTimeField, $endTimeField, calEvent, timeslotTimes) ->
      $startTimeField.empty();
      $endTimeField.empty();
      for time in timeslotTimes
        startTime = time.start;
        endTime = time.end;
        startSelected = "";
        if startTime.getTime() is calEvent.start.getTime()
          startSelected = "selected=\"selected\"";
        endSelected = "";
        if endTime.getTime() is calEvent.end.getTime()
          endSelected = "selected=\"selected\"";
        $startTimeField.append("<option value=\"" + startTime + "\" " + startSelected + ">" + time.startFormatted + "</option>");
        $endTimeField.append("<option value=\"" + endTime + "\" " + endSelected + ">" + time.endFormatted + "</option>");

        $timestampsOfOptions.start[time.startFormatted] = startTime.getTime();
        $timestampsOfOptions.end[time.endFormatted] = endTime.getTime();

      $endTimeOptions = $endTimeField.find("option");
      $startTimeField.trigger("change");

  updateMessage();
  
  
  true
