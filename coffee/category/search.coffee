###
簡易検索
###
$ =>
  $searchBoxes = $('.searchbox .search-query')
  $searchBoxes.each(->
    $this = $(@)
    $target = $this.parents('.inner').find('.categoryList')
    $this.on('keyup', ->
      keywords = $this.val().trim().replace(/(\s|　)+/g, ' ').split(' ')
      $lists = $('li', $target)
      $filterList = $lists
      for keyword in keywords
        if keyword? and keyword != ''
          $filterList = $filterList.filter(':contains(' + keyword + ')')
      
      $lists.hide()
      $filterList.show()
    )
  )
