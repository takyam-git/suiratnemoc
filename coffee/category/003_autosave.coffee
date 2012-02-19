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

  saveInterval = 1000 * 2  #保存間隔(ms)

  saveFunc = =>
    clearTimeout(timer)
    if @modified is true
      $items = $('li', @favoriteList)
      ids = []
      $items.each(->
        ids.push($(this).attr('data-id'))
      )
      $.ajax({
        url: '/category/action/favorite.json'
        type: 'post'
        data: {
          categories: ids
        }
        dataType: 'json'
        success: (data) =>
          if !data.success
            message = 'お気に入りの保存に失敗しました'
          else
            message = data.time + ' お気に入りを保存しました'
            $message.text('2012/02/03 15:34:30 お気に入りを保存しました')
            $dialog.stop().show(1000)
            setTimeout(->
              $dialog.hide(1000)
              setTimeout(saveFunc, saveInterval)
            , 1000 * 2)
            @modified = false
      })
    else
      #setTimeout(saveFunc, saveInterval)

  #timer = setTimeout(saveFunc, saveInterval)
