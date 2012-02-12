@modified = false
$ =>
  $dialog = $('<div></div>')
  $message = $('<p>test</p>')
  $dialog.append($message)
  $dialog.css({
    color: '#fff'
    display: 'none'
    position: 'fixed'
    bottom: 0
    right: 0
    padding: '5px 10px'
    'z-index': 9999
    'background-color': 'rgba(0,0,0,0.8)'
  })
  $message.css({
    margin: 0
    padding: 0
  })
  $('body').append($dialog)

  saveInterval = 1000 * 10  #保存間隔(ms)

  saveFunc = =>
    clearTimeout(timer)
    if @modified is true
      ##ここからAjaxに置き換える
      $message.text('2012/02/03 15:34:30 変更を保存しました')
      $dialog.stop().show('slow')
      setTimeout(->
        $dialog.hide('slow')
      , 1000 * 5)
      setTimeout(saveFunc, saveInterval)
      ##ここまでAjaxに置き換える
      @modified = false
    else
      setTimeout(saveFunc, saveInterval)

  timer = setTimeout(saveFunc, saveInterval)
