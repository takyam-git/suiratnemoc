$ =>
  @$search_start = $('#search_start')
  @$remove_start = $('#remove_start')
  @$search_end = $('#search_end')
  @$remove_end = $('#remove_end')
  
  @$error_box = $('#search_error')
  @$submit = $('#go_summary')
  
  @base_url = @$submit.attr('href')
  
  @$error_box.hide()
  
  $('.datepicker').datepicker({
    dateFormat: 'yy-mm-dd'
    dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日']
    dayNamesMin: ['日', '月', '火', '水', '木', '金', '土']
    dayNamesShort: ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜']
    monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
    monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
    yearSuffix: '年'
    showMonthAfterYear: true
  })
  
  @$remove_start.on('click', =>
    @$search_start.val('')
    false
  )
  @$remove_end.on('click', =>
    @$search_end.val('')
    false
  )
  
  setError = (message) =>
    @$error_box.append('<div>' + message + '</div>').show()
  
  clearError = =>
    @$error_box.html('').hide()
    
  @date_regexp = new RegExp('^[0-9]{4}(-|\/)[0-9]{1,2}(-|\/)[0-9]{1,2}$')
  
  @$submit.on('click', =>
    clearError()
    
    start = $search_start.val()
    end = $search_end.val()
    
    hasError = false
    
    if start.length is 0
      setError('開始日は入力必須項目です')
      hasError = true
    
    if start.length > 0 and start.match(@date_regexp) is null
      setError('開始日の書式が誤っています')
      hasError = true
    
    if end.length > 0 and end.match(@date_regexp) is null
      setError('終了日の書式が誤っています')
      hasError = true
      
    if !hasError
      url = @base_url + start.replace('/', '-')
      if end.length > 0
        url = url + '/' + end.replace('/', '-')
      
      location.href = url
      
    false
  )

  