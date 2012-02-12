
String.prototype.trim = ->
  return @.replace(/^(\s|　)+|(\s|　)+$/g, '')

$(window).load ->
  #categoryHeaderの高さを揃える
  $categoryHeaders = $('.categoryHeader')
  maxCategoryHeaderHeight = 0
  $categoryHeaders.each(->
    myHeight = $(this).height()
    if maxCategoryHeaderHeight < myHeight
      maxCategoryHeaderHeight = myHeight
  )
  $categoryHeaders.height(maxCategoryHeaderHeight)

$ =>
  glob = @
  
  isAdmin = $('#is_admin_flag').data('isadmin') == 1
  
  #DOM Objectの取得
  $baseCategoryList = $('.baseCategoryList')
  $baseCategoryListItems = $('li', $baseCategoryList)
  $favoriteList = $('#favoriteCategoryList')
  $favoriteListItems = $('li', $favoriteList)
  $globalList = $('#globalCategory .categoryList')
  $myList = $('#myCategory .categoryList')
  $addMyCategoryBtn = $('a#add-my-category-btn')
  $addGlobalCategoryBtn = $('a#add-global-category-btn')
  
  #DOM Objectの生成
  $removeIcon = $('<i class="icon-remove icon-white pull-right category-config-icon"></i>')
  
  #お気に入りクリック時に削除するイベント
  favoriteItemClickFunc = ->
    #ドラッグ/ソート中は無視する
    if draggingFlag is false
      $this = $(@)
      $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList)
        .removeClass(listFavoriteClassName).draggable(listDraggableOption)
      $this.remove()
    return false;
  
  #お気に入り追加済みのグローバル/マイカテゴリのクラス
  listFavoriteClassName = 'addedToFavorite';
  
  #ドラッグ/ソート中はクリックイベントを走らせないようにするためのフラグ
  draggingFlag = false
  
  #ドラッグオプション
  listDraggableOption = {
    connectToSortable: "#favoriteCategoryList"
    helper: ->  #コンフィグアイコンを削除した状態でヘルパーを返す
      $this = $(@)
      $helper = $this.clone()
      $('a', $helper).remove()
      return $helper
    revert: "invalid"
    start: ->
      draggingFlag = true
    stop: (event, ui) ->
      $this = $(@)
      #お気に入りに追加したイベントを確認
      $newFavorite = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $favoriteList)
      
      #お気に入りに追加されていれば、追加処理を実施
      if $newFavorite.length > 0
        
        #お気に入りに追加したイベントを追加済み状態に変更する
        $this.draggable('destroy')  #dragできなくする
        $this.addClass(listFavoriteClassName) #見た目を変更
        
        #新しく追加されたお気に入りリスト内のイベント
        $('i', $newFavorite).remove() #コンフィグアイコンを削除
        $newFavorite.append($removeIcon.clone())  #リムーブアイコンを追加
        
        #クリックしたらお気に入りから削除して、元イベントの状態をもとに戻す
        $newFavorite.on('click', favoriteItemClickFunc)
        glob.modified = true
      else
        ui.helper.remove()
      
      draggingFlag = false
      
      null  #nullを返さないとdraggableが消える
  }
  
  #カテゴリのFavoriteへの追加処理
  $favoriteList.sortable({
    revert: true
    start: ->
      draggingFlag = true
      null #null返さないとsortableがコケる
    stop: ->
      draggingFlag = false
      glob.modified = true
      null #null返さないとsortableがコケる
  })
  
  $baseCategoryListItems.draggable(listDraggableOption).disableSelection()

  #カテゴリダイアログ関連のDOMの取得と設定
  $categoryDialog = $('#modifyCategoryDialog')
  $categoryDialogTitle = $('#modifyCategoryDialogTitle', $categoryDialog)
  $categoryDialogSaveButton = $('#categoryDialogSaveButton', $categoryDialog)
  $categoryNameGroup = $('#categoryNameGroup', $categoryDialog)
  $categoryNameField = $('#categoryName', $categoryNameGroup)
  $categoryNameError = $('#categoryNameError', $categoryNameGroup)
  $categoryNameScreen = $('#categoryNameScreen', $categoryDialog)
  $categoryColorGroup = $('#categoryColorGroup', $categoryDialog)
  $categoryColorField = $('#selectedColorID', $categoryColorGroup)
  $categoryColorError = $('#categoryColorError', $categoryColorGroup)
  $categoryColorScreen = $('#categoryColorScreen', $categoryDialog)
  $colorsContainer = $('#colorSetList')
  $triggerButton = $('#selectColorSet')
  defaultButtonText = $triggerButton.text()
  
  #カテゴリ変更ダイアログ　クリック時にダイアログを開く
  openCategoryDialog = (type, id) ->
    
    #gloabl|localはOKでそれ以外はダイアログを表示させない。基本的に無いパターン
    if type is 'global'
      titlePrefix = 'グローバル'
      $list = $globalList
    else if type is 'local'
      titlePrefix = 'マイ'
      $list = $myList
    else
      return false
    
    #IDを取得できた場合 = 編集、そうでない場合 = 追加
    if id? && (id = parseInt(id)) > 0
      mode = 'edit'
      titleSuffix = '編集'
      
      $item = $('li[data-type="' + type + '"][data-id="' + id + '"]', $list)
      if !($item.length > 0)
        return false
      
      name = $item.text()
      colorSetID = parseInt($item.data('color'))
      
    else
      mode = 'add'
      titleSuffix = '追加'
      id = 0
      
      name = ''
      colorSetID = 0
    
    #ダイアログの各フィールドを更新
    $categoryNameField.val(name)
    $categoryColorField.val(colorSetID)
    
    #カラー選択ボタンの表示テキストを更新
    $selectedColor = $('li a[data-id="' + colorSetID + '"]', $colorsContainer)
    if $selectedColor.length > 0
      $triggerButton.text($selectedColor.data('name'))
    else
      $triggerButton.text(defaultButtonText)
    
    #ダイアログのタイトルの変更
    $categoryDialogTitle.text(titlePrefix + 'カテゴリの' + titleSuffix)
    
    #保存ボタンクリック時の動作
    $categoryDialog.off('shown').on('shown', ->
      $categoryNameGroup.removeClass('error')
      $categoryColorGroup.removeClass('error')
      $categoryNameError.text('')
      $categoryColorError.text('')

      $categoryNameScreen.text($categoryNameField.val())
      $categoryColorScreen.text($triggerButton.text())
      
      if type is 'global' and isAdmin is false
        $categoryNameScreen.show()
        $categoryColorScreen.show()
        $categoryNameField.hide()
        $triggerButton.hide()
        $categoryDialogSaveButton.hide()
      else
        $categoryNameScreen.hide()
        $categoryColorScreen.hide()
        $categoryNameField.show()
        $triggerButton.show()
        $categoryDialogSaveButton.show()
      
      $categoryDialogSaveButton.click(->
        newCategoryName = $categoryNameField.val().trim()
        newCategoryColorSetID = $categoryColorField.val()
        
        #localValidation
        hasError = false
        if !(newCategoryName.length > 0)
          $categoryNameGroup.addClass('error')
          $categoryNameError.text('入力必須項目です')
          hasError = true
        
        if hasError
          return false
        
        #ここでajaxでvalidateとsaveの処理
        
        #成功した場合カテゴリアイテムを更新する
        id = 999 if !(id?) || id is 0 #this is for debug
        changeCategoryItem(type, id, newCategoryName, newCategoryColorSetID)
        
        $categoryDialogSaveButton.off('click')
        
        $categoryDialog.modal('hide')
        false
      )
    ).on('hidden', ->
      $categoryDialogSaveButton.off('click')
    ).modal()

  #DOM的にリストに新しくアイテムを追加する処理
  #<li id="localCategory-9" data-type="local" data-id="9"><i class="icon-cog icon-white pull-right category-config-icon"></i>local9</li>
  $newItemBase = $('<li></li>')
  $newItemBaseIcon = $('<i class="icon-cog icon-white pull-right category-config-icon"></i>')
  changeCategoryItem = (type, id, name, color) ->
    #IDが取得できない場合何もしない
    if !(id? && (id = parseInt(id)) > 0)
      return false
    
    #追加先のULを取得
    if type is 'global'
      $target = $globalList
    else if type is 'local'
      $target = $myList
    else
      return false
    
    $beenItem = $('li[data-id="' + id + '"]', $target)
    isSetted = $beenItem.length > 0
    
    if !isSetted
      $item = $newItemBase.clone()
        .attr('id', type + 'Category-' + id)
        .attr('data-id', id)
        .attr('data-type', type)
        .draggable(listDraggableOption)
        .on('click', itemClickFunc)
    else
      $item = $beenItem
    
    $item.data('color', color)
      .text(name)
      .prepend($newItemBaseIcon.clone())
    
    if !isSetted
      $item
        .hide()
        .prependTo($target)
        .slideDown()
          
    glob.modified = true
    
    null
    
  #マイカテゴリ追加ボタンクリック時の動作  
  $addMyCategoryBtn.on('click', ->
    openCategoryDialog('local', null)
    return false
  )
  $addGlobalCategoryBtn.on('click', ->
    openCategoryDialog('global', null)
    return false
  )
  
  #アイテムクリック時にダイアログを開く
  itemClickFunc = ->
    $this = $(this)
    openCategoryDialog($this.data('type'), $this.data('id'))
    return false
  $baseCategoryListItems.on('click', itemClickFunc)

  #最初からお気に入りに追加済みのものを追加済み状態する
  $favoriteListItems.each(->
    $this = $(@)
    $target = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList)
    if !($target.length > 0)
      $this.remove()
      return true #continue
    
    $target.draggable('destroy')  #dragできなくする
    $target.addClass(listFavoriteClassName) #見た目を変更
    
    #クリックしたらお気に入りから削除して、元イベントの状態をもとに戻す
    $this.on('click', favoriteItemClickFunc)
  )























