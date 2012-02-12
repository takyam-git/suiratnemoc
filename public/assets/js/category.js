(function() {
  var _this = this;

  this.modified = false;

  $(function() {
    var $dialog, $message, saveFunc, saveInterval, timer;
    $dialog = $('<div></div>');
    $message = $('<p>test</p>');
    $dialog.append($message);
    $dialog.css({
      color: '#fff',
      display: 'none',
      position: 'fixed',
      bottom: 0,
      right: 0,
      padding: '5px 10px',
      'z-index': 9999,
      'background-color': 'rgba(0,0,0,0.8)'
    });
    $message.css({
      margin: 0,
      padding: 0
    });
    $('body').append($dialog);
    saveInterval = 1000 * 10;
    saveFunc = function() {
      clearTimeout(timer);
      if (_this.modified === true) {
        $message.text('2012/02/03 15:34:30 変更を保存しました');
        $dialog.stop().show('slow');
        setTimeout(function() {
          return $dialog.hide('slow');
        }, 1000 * 5);
        setTimeout(saveFunc, saveInterval);
        return _this.modified = false;
      } else {
        return setTimeout(saveFunc, saveInterval);
      }
    };
    return timer = setTimeout(saveFunc, saveInterval);
  });

  String.prototype.trim = function() {
    return this.replace(/^(\s|　)+|(\s|　)+$/g, '');
  };

  $(window).load(function() {
    var $categoryHeaders, maxCategoryHeaderHeight;
    $categoryHeaders = $('.categoryHeader');
    maxCategoryHeaderHeight = 0;
    $categoryHeaders.each(function() {
      var myHeight;
      myHeight = $(this).height();
      if (maxCategoryHeaderHeight < myHeight) {
        return maxCategoryHeaderHeight = myHeight;
      }
    });
    return $categoryHeaders.height(maxCategoryHeaderHeight);
  });

  $(function() {
    var $addGlobalCategoryBtn, $addMyCategoryBtn, $baseCategoryList, $baseCategoryListItems, $categoryColorError, $categoryColorField, $categoryColorGroup, $categoryColorScreen, $categoryDialog, $categoryDialogSaveButton, $categoryDialogTitle, $categoryNameError, $categoryNameField, $categoryNameGroup, $categoryNameScreen, $colorsContainer, $favoriteList, $favoriteListItems, $globalList, $myList, $newItemBase, $newItemBaseIcon, $removeIcon, $triggerButton, changeCategoryItem, defaultButtonText, draggingFlag, favoriteItemClickFunc, glob, isAdmin, itemClickFunc, listDraggableOption, listFavoriteClassName, openCategoryDialog;
    glob = _this;
    isAdmin = $('#is_admin_flag').data('isadmin') === 1;
    $baseCategoryList = $('.baseCategoryList');
    $baseCategoryListItems = $('li', $baseCategoryList);
    $favoriteList = $('#favoriteCategoryList');
    $favoriteListItems = $('li', $favoriteList);
    $globalList = $('#globalCategory .categoryList');
    $myList = $('#myCategory .categoryList');
    $addMyCategoryBtn = $('a#add-my-category-btn');
    $addGlobalCategoryBtn = $('a#add-global-category-btn');
    $removeIcon = $('<i class="icon-remove icon-white pull-right category-config-icon"></i>');
    favoriteItemClickFunc = function() {
      var $this;
      if (draggingFlag === false) {
        $this = $(this);
        $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList).removeClass(listFavoriteClassName).draggable(listDraggableOption);
        $this.remove();
      }
      return false;
    };
    listFavoriteClassName = 'addedToFavorite';
    draggingFlag = false;
    listDraggableOption = {
      connectToSortable: "#favoriteCategoryList",
      helper: function() {
        var $helper, $this;
        $this = $(this);
        $helper = $this.clone();
        $('a', $helper).remove();
        return $helper;
      },
      revert: "invalid",
      start: function() {
        return draggingFlag = true;
      },
      stop: function(event, ui) {
        var $newFavorite, $this;
        $this = $(this);
        $newFavorite = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $favoriteList);
        if ($newFavorite.length > 0) {
          $this.draggable('destroy');
          $this.addClass(listFavoriteClassName);
          $('i', $newFavorite).remove();
          $newFavorite.append($removeIcon.clone());
          $newFavorite.on('click', favoriteItemClickFunc);
          glob.modified = true;
        } else {
          ui.helper.remove();
        }
        draggingFlag = false;
        return null;
      }
    };
    $favoriteList.sortable({
      revert: true,
      start: function() {
        draggingFlag = true;
        return null;
      },
      stop: function() {
        draggingFlag = false;
        glob.modified = true;
        return null;
      }
    });
    $baseCategoryListItems.draggable(listDraggableOption).disableSelection();
    $categoryDialog = $('#modifyCategoryDialog');
    $categoryDialogTitle = $('#modifyCategoryDialogTitle', $categoryDialog);
    $categoryDialogSaveButton = $('#categoryDialogSaveButton', $categoryDialog);
    $categoryNameGroup = $('#categoryNameGroup', $categoryDialog);
    $categoryNameField = $('#categoryName', $categoryNameGroup);
    $categoryNameError = $('#categoryNameError', $categoryNameGroup);
    $categoryNameScreen = $('#categoryNameScreen', $categoryDialog);
    $categoryColorGroup = $('#categoryColorGroup', $categoryDialog);
    $categoryColorField = $('#selectedColorID', $categoryColorGroup);
    $categoryColorError = $('#categoryColorError', $categoryColorGroup);
    $categoryColorScreen = $('#categoryColorScreen', $categoryDialog);
    $colorsContainer = $('#colorSetList');
    $triggerButton = $('#selectColorSet');
    defaultButtonText = $triggerButton.text();
    openCategoryDialog = function(type, id) {
      var $item, $list, $selectedColor, colorSetID, mode, name, titlePrefix, titleSuffix;
      if (type === 'global') {
        titlePrefix = 'グローバル';
        $list = $globalList;
      } else if (type === 'local') {
        titlePrefix = 'マイ';
        $list = $myList;
      } else {
        return false;
      }
      if ((id != null) && (id = parseInt(id)) > 0) {
        mode = 'edit';
        titleSuffix = '編集';
        $item = $('li[data-type="' + type + '"][data-id="' + id + '"]', $list);
        if (!($item.length > 0)) return false;
        name = $item.text();
        colorSetID = parseInt($item.data('color'));
      } else {
        mode = 'add';
        titleSuffix = '追加';
        id = 0;
        name = '';
        colorSetID = 0;
      }
      $categoryNameField.val(name);
      $categoryColorField.val(colorSetID);
      $selectedColor = $('li a[data-id="' + colorSetID + '"]', $colorsContainer);
      if ($selectedColor.length > 0) {
        $triggerButton.text($selectedColor.data('name'));
      } else {
        $triggerButton.text(defaultButtonText);
      }
      $categoryDialogTitle.text(titlePrefix + 'カテゴリの' + titleSuffix);
      return $categoryDialog.off('shown').on('shown', function() {
        $categoryNameGroup.removeClass('error');
        $categoryColorGroup.removeClass('error');
        $categoryNameError.text('');
        $categoryColorError.text('');
        $categoryNameScreen.text($categoryNameField.val());
        $categoryColorScreen.text($triggerButton.text());
        if (type === 'global' && isAdmin === false) {
          $categoryNameScreen.show();
          $categoryColorScreen.show();
          $categoryNameField.hide();
          $triggerButton.hide();
          $categoryDialogSaveButton.hide();
        } else {
          $categoryNameScreen.hide();
          $categoryColorScreen.hide();
          $categoryNameField.show();
          $triggerButton.show();
          $categoryDialogSaveButton.show();
        }
        return $categoryDialogSaveButton.click(function() {
          var hasError, newCategoryColorSetID, newCategoryName;
          newCategoryName = $categoryNameField.val().trim();
          newCategoryColorSetID = $categoryColorField.val();
          hasError = false;
          if (!(newCategoryName.length > 0)) {
            $categoryNameGroup.addClass('error');
            $categoryNameError.text('入力必須項目です');
            hasError = true;
          }
          if (hasError) return false;
          if (!(id != null) || id === 0) id = 999;
          changeCategoryItem(type, id, newCategoryName, newCategoryColorSetID);
          $categoryDialogSaveButton.off('click');
          $categoryDialog.modal('hide');
          return false;
        });
      }).on('hidden', function() {
        return $categoryDialogSaveButton.off('click');
      }).modal();
    };
    $newItemBase = $('<li></li>');
    $newItemBaseIcon = $('<i class="icon-cog icon-white pull-right category-config-icon"></i>');
    changeCategoryItem = function(type, id, name, color) {
      var $beenItem, $item, $target, isSetted;
      if (!((id != null) && (id = parseInt(id)) > 0)) return false;
      if (type === 'global') {
        $target = $globalList;
      } else if (type === 'local') {
        $target = $myList;
      } else {
        return false;
      }
      $beenItem = $('li[data-id="' + id + '"]', $target);
      isSetted = $beenItem.length > 0;
      if (!isSetted) {
        $item = $newItemBase.clone().attr('id', type + 'Category-' + id).attr('data-id', id).attr('data-type', type).draggable(listDraggableOption).on('click', itemClickFunc);
      } else {
        $item = $beenItem;
      }
      $item.data('color', color).text(name).prepend($newItemBaseIcon.clone());
      if (!isSetted) $item.hide().prependTo($target).slideDown();
      glob.modified = true;
      return null;
    };
    $addMyCategoryBtn.on('click', function() {
      openCategoryDialog('local', null);
      return false;
    });
    $addGlobalCategoryBtn.on('click', function() {
      openCategoryDialog('global', null);
      return false;
    });
    itemClickFunc = function() {
      var $this;
      $this = $(this);
      openCategoryDialog($this.data('type'), $this.data('id'));
      return false;
    };
    $baseCategoryListItems.on('click', itemClickFunc);
    return $favoriteListItems.each(function() {
      var $target, $this;
      $this = $(this);
      $target = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList);
      if (!($target.length > 0)) {
        $this.remove();
        return true;
      }
      $target.draggable('destroy');
      $target.addClass(listFavoriteClassName);
      return $this.on('click', favoriteItemClickFunc);
    });
  });

  $(function() {
    var $colorsContainer, $triggerButton, $valueField, defaultButtonText;
    $triggerButton = $('#selectColorSet');
    defaultButtonText = $triggerButton.text();
    $valueField = $('#selectedColorID');
    $colorsContainer = $('#colorSetList');
    return $triggerButton.menu({
      content: $colorsContainer.clone().html(),
      backLinkText: '戻る',
      crumbDefaultText: '',
      flyOut: true,
      onSelect: function($item) {
        var colorSetID;
        colorSetID = $item.data('id');
        if (colorSetID === 'cancel') {
          $triggerButton.text(defaultButtonText);
          $valueField.val('');
        } else {
          colorSetID = parseInt(colorSetID);
          if (colorSetID > 0) {
            $triggerButton.text($item.data('name'));
            $valueField.val(colorSetID);
          }
        }
        return true;
      }
    });
  });

  /*
  簡易検索
  */

  $(function() {
    var $searchBoxes;
    $searchBoxes = $('.searchbox .search-query');
    return $searchBoxes.each(function() {
      var $target, $this;
      $this = $(this);
      $target = $this.parents('.inner').find('.categoryList');
      return $this.on('keyup', function() {
        var $filterList, $lists, keyword, keywords, _i, _len;
        keywords = $this.val().trim().replace(/(\s|　)+/g, ' ').split(' ');
        $lists = $('li', $target);
        $filterList = $lists;
        for (_i = 0, _len = keywords.length; _i < _len; _i++) {
          keyword = keywords[_i];
          if ((keyword != null) && keyword !== '') {
            $filterList = $filterList.filter(':contains(' + keyword + ')');
          }
        }
        $lists.hide();
        return $filterList.show();
      });
    });
  });

}).call(this);
