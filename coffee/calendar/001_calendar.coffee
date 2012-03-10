String.prototype.trim = ->
  return @.replace(/^(\s|　)+|(\s|　)+$/g, '')

String.prototype.htmldecode = ->
  return @.replace(/\&amp\;/g,'&')
          .replace(/\&quot\;/g,'"')
          .replace(/\&\#039\;/g,'\'')
          .replace(/\&lt\;/g,'<')
          .replace(/\&gt\;/g,'>');

$(document).ready =>
  $calendar = $('#calendar');
  id = 10;
  
  #DOM Settings
  @$dialogContent = $("#event_edit_container")
  @$removeButton = $('a#removeButton', @$dialogContent)
  @$saveButton = $('a#saveButton', @$dialogContent)
  @$categorySet = $('#categorySet', $dialogContent)
  @categorySetDefault = @$categorySet.text()
  unixtimeToDate = (ut) ->
    tD = new Date( ut * 1000 )
    tD.setTime( tD.getTime() + (60*60*1000) )
    return tD
  
  updateEvent = (calEvent, $event) =>
    $dialogContent = @$dialogContent
    $errorField = $('#categoryDialogError', $dialogContent)
    $dialogContent.find("input").val("");
    $dialogContent.find("textarea").val("");
    startField = $dialogContent.find("select[name='start']").val(calEvent.start);
    endField = $dialogContent.find("select[name='end']").val(calEvent.end);
    categoryField = $dialogContent.find("input[name='category']").val(calEvent.category);
    titleField = $dialogContent.find("textarea[name='title']").val(calEvent.title.htmldecode());
    
    if calEvent.category_name?
      @$categorySet.text(calEvent.category_name)
    else
      @$categorySet.text(@categorySetDefault)
    
    #bodyField = $dialogContent.find("textarea[name='body']").val(calEvent.body);

    # $dialogContent.dialog({
      # modal: true,
      # title: '新しいイベントの登録',
      # close: ->
        # $dialogContent.dialog("destroy");
        # $dialogContent.hide();
        # $('#calendar').weekCalendar("removeUnsavedEvents");
      # buttons: {
        # '保存' : ->       post_data = {
    $removeCategoryDialog = $('#removeCategoryDialog');
    $removeCategoryDoButton =$('#categoryDialogRemoveDoButton', $removeCategoryDialog)
    $removeCategoryError = $('#removeCategoryDialogError', $removeCategoryDialog)
    
    $dialogContent.off('shown').off('hidden').on('shown', =>
      $errorField.html('').hide()
      isNewEvent = !(calEvent.id? && parseInt(calEvent.id) > 0)
      
      if isNewEvent
        @$removeButton.hide()
      else
        @$removeButton.show()
      
      #イベントの削除
      @$removeButton.off('click').on('click', ->
        $removeCategoryError.html('').hide();
        $dialogContent.modal('hide')
        $removeCategoryDialog.off('shown').on('shown', ->
          $('#categoryDialogRemoveDoButton', @$removeButton).off('click').on('click', ->
            $.ajax({
              url: '/calendar/event/remove.json'
              type: 'post'
              data: {
                id: calEvent.id
              }
              dataType: 'json'
              success: (data) ->
                if !data.success
                  errors = []
                  for key, ary of data.errors
                    for err in ary
                      errors.push(err)
                  $removeCategoryError.append($('<p>' + errors.join('<br>') + '</p>')).show()
                else
                  $calendar.weekCalendar("removeEvent", calEvent.id)
                  $removeCategoryDialog.modal('hide')
              complete: (data) ->
                
            })
            
            false
          )
        ).on('hidden', ->
        ).modal();
        
        false
      )
      
      saveButtonProcessing = false #簡易連投防止
      @$saveButton.off('click').on('click', ->
        #簡易連投防止
        if saveButtonProcessing is true
          return false
        #簡易連投防止
        saveButtonProcessing = true
        
        $errorField.html('').hide()
        
        post_data = {
          event_id: calEvent.id
          start : startField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
          end : endField.val().replace(/GMT.*$/, '').replace(/(^\s+|\s+$)/g, '')
          title : titleField.val()
          #body : bodyField.val()
          category : categoryField.val()
        }
        
        $.ajax({
          data: post_data
          type: 'post'
          url: '/calendar/event/update.json'
          success: (data) ->
            if data?.success? && data.success is true
              calEvent.id = data.event.id;
              calEvent.start = new Date(data.event.start.replace(/-/g, '/'))  #firefoxが - 区切りの日付はDateに変換できないので / に変える
              calEvent.end = new Date(data.event.end.replace(/-/g, '/'))
              calEvent.title = data.event.title
              #calEvent.body = data.event.body
              calEvent.category = data.event.category
              calEvent.colorset = data.event.colorset
              calEvent.category_name = data.event.category_name
              
              $calendar.weekCalendar("removeUnsavedEvents");
              $calendar.weekCalendar("updateEvent", calEvent);
              $dialogContent.modal("hide");
            else
              if data?.errors?
                errorHtml = ''
                for err in data.errors
                  errorHtml += '<p>' + err + '</p>'
                $errorField.html(errorHtml).show()
          complete: ->
            #簡易連投防止
            saveButtonProcessing = false
            
        })
        
        false
      )
    ).on('hidden', ->
      $dialogContent.dialog("destroy");
      $dialogContent.hide();
      $('#calendar').weekCalendar("removeUnsavedEvents");
    ).modal()
        # 'キャンセル' : ->
          # $dialogContent.dialog("close");
      # }
    # }).show();
    $dialogContent.find(".date_holder").text($calendar.weekCalendar("formatDate", calEvent.start) + ' ' + $calendar.weekCalendar("formatTime", calEvent.start) + ' - ' + $calendar.weekCalendar("formatTime", calEvent.end));
    setupStartAndEndTimeFields(startField, endField, calEvent, $calendar.weekCalendar("getTimeslotTimes", calEvent.start));
  
  modifyEvent = (calEvent, $event) ->
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
    businessHours: {start: 9, end: 19, limitDisplay: false},
    height: ($calendar) ->
      return $(window).height() - 60;
    timeSeparator: ' - '
    newEventText: ''  
    eventBody : (calEvent, $event) ->
      text = ''
      if calEvent?.category_name?
        text += '<strong>' + calEvent.category_name + '</strong><br><br>'
      text += calEvent.title.replace(/(\r\n|\r|\n)/g, '<br>')
      return text
    eventRender : (calEvent, $event) ->
      if calEvent?.colorset? and parseInt(calEvent.colorset) > 0
        $event.addClass('wc-cal-event-color' + calEvent.colorset)
    eventNew : (calEvent, $event) ->
      updateEvent(calEvent, $event)
    eventClick: (calEvent, $event) ->
      updateEvent(calEvent, $event)
    eventDrop: (calEvent, $event) ->
      modifyEvent(calEvent, $event)
    eventResize : (calEvent, $event) ->
      modifyEvent(calEvent, $event)
    data: (start, end, callback) ->
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
            $.each(data.events, (key, value)->
              if value.category?.color_set?
                data.events[key].colorset = value.category.color_set
              else
                data.events[key].colorset = 0
                
              if value.category?.name?
                data.events[key].category_name = value.category.name
              else
                data.events[key].category_name = ''
                
              if value.category?.id?
                data.events[key].category = value.category.id
              else
                data.events[key].category = 0
                 
            )
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
