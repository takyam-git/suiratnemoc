$ ->
  $baseCategoryList = $('.baseCategoryList');
  $favoriteList = $('#favoriteCategoryList')
  $globalList = $('#globalCategory .categoryList')
  $myList = $('#myCategory .categoryList')
  $addMyCategoryBtn = $('a#add-my-category-btn')
  
  listFavoriteClassName = 'addedToFavorite';
  
  draggingFlag = false
  
  listDraggableOption = {
    connectToSortable: "#favoriteCategoryList"
    helper: ->
      $this = $(@)
      $helper = $this.clone()
      $('a', $helper).remove()
      return $helper
    revert: "invalid"
    start: ->
      draggingFlag = true
    stop: (event, ui) ->
      $this = $(@)
      $this.draggable('destroy')
      $this.addClass(listFavoriteClassName)
      #$this.click(unFavoriteClickEvent)
      $newFavorite = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $favoriteList)
      $('i', $newFavorite).remove()
      $newFavorite.append('<i class="icon-remove icon-white pull-right category-config-icon"></i>')
      $newFavorite.on('click', ->
        if draggingFlag is false
          $this = $(@)
          $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList)
            .removeClass(listFavoriteClassName).draggable(listDraggableOption)
          $this.remove()
        return false;
      )
      draggingFlag = false
  }
  
  unFavoriteClickEvent = 
  
  $modifyCategoryDialog = $('#modifyCategoryDialog').on('shown', ->
    console.log 'open'
  )
  
  $favoriteList.sortable({
    revert: true
    start: ->
      draggingFlag = true
      null
    stop: ->
      draggingFlag = false
      null
  })
  $('li', $baseCategoryList).draggable(listDraggableOption).disableSelection().on('click', ->
    #open modify dialog
    $modifyCategoryDialog.modal()
    
    return false
  )
  
  
  $addMyCategoryDialog = $('#modifyCategoryDialog').on('shown', ->
    console.log('add')
  )
  $addMyCategoryBtn.click(->
    $addMyCategoryDialog.modal()
    
    return false
  )
