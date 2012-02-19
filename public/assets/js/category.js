(function() {
  var _this = this;

  String.prototype.trim = function() {
    return this.replace(/^(\s|　)+|(\s|　)+$/g, '');
  };

  String.prototype.htmldecode = function() {
    return this.replace(/\&amp\;/g, '&').replace(/\&quot\;/g, '"').replace(/\&\#039\;/g, '\'').replace(/\&lt\;/g, '<').replace(/\&gt\;/g, '>');
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
    var $addGlobalCategoryBtn, $addMyCategoryBtn, $baseCategoryList, $baseCategoryListItems, $categoryColorError, $categoryColorField, $categoryColorGroup, $categoryColorScreen, $categoryDialog, $categoryDialogError, $categoryDialogRemoveButton, $categoryDialogSaveButton, $categoryDialogTitle, $categoryNameError, $categoryNameField, $categoryNameGroup, $categoryNameScreen, $colorsContainer, $favoriteList, $favoriteListItems, $globalList, $myList, $newItemBase, $newItemBaseIcon, $removeCategoryDialog, $removeCategoryDoButton, $removeCategoryError, $removeIcon, $triggerButton, changeCategoryItem, defaultButtonText, draggingFlag, favoriteItemClickFunc, glob, isAdmin, itemClickFunc, listDraggableOption, listFavoriteClassName, openCategoryDialog, saveMyFavorites;
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
    _this.favoriteList = $favoriteList;
    saveMyFavorites = function() {
      var $items, ids,
        _this = this;
      $items = $('li', glob.favoriteList);
      ids = [];
      $items.each(function() {
        return ids.push($(this).attr('data-id'));
      });
      return $.ajax({
        url: '/category/action/favorite.json',
        type: 'post',
        data: {
          categories: ids
        },
        dataType: 'json',
        success: function(data) {}
      });
    };
    $removeIcon = $('<i class="icon-remove icon-white pull-right category-config-icon"></i>');
    favoriteItemClickFunc = function() {
      var $this;
      if (draggingFlag === false) {
        $this = $(this);
        $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList).removeClass(listFavoriteClassName).draggable(listDraggableOption);
        $this.remove();
        saveMyFavorites();
        glob.modified = true;
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
      scroll: true,
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
        } else {
          ui.helper.remove();
        }
        draggingFlag = false;
        return null;
      }
    };
    $favoriteList.sortable({
      revert: true,
      placeholder: 'sortable-placeholder',
      start: function() {
        draggingFlag = true;
        return null;
      },
      stop: function() {
        draggingFlag = false;
        glob.modified = true;
        saveMyFavorites();
        return null;
      }
    });
    $baseCategoryListItems.draggable(listDraggableOption).disableSelection();
    $categoryDialog = $('#modifyCategoryDialog');
    $categoryDialogError = $('#categoryDialogError', $categoryDialog);
    $categoryDialogTitle = $('#modifyCategoryDialogTitle', $categoryDialog);
    $categoryDialogSaveButton = $('#categoryDialogSaveButton', $categoryDialog);
    $categoryDialogRemoveButton = $('#categoryDialogRemoveButton', $categoryDialog);
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
    $removeCategoryDialog = $('#removeCategoryDialog');
    $removeCategoryDoButton = $('#categoryDialogRemoveDoButton', $removeCategoryDialog);
    $removeCategoryError = $('#removeCategoryDialogError', $removeCategoryDialog);
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
        name = $item.text().trim().htmldecode();
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
        $categoryDialogError.hide();
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
        $categoryDialogRemoveButton.off('click').on('click', function() {
          $removeCategoryError.html('').hide();
          $categoryDialog.modal('hide');
          $removeCategoryDialog.off('shown').on('shown', function() {
            return $removeCategoryDoButton.off('click').on('click', function() {
              $.ajax({
                url: '/category/action/remove.json',
                type: 'post',
                data: {
                  id: id
                },
                dataType: 'json',
                success: function(data) {
                  var ary, err, errors, key, _i, _len, _ref;
                  if (!data.success) {
                    errors = [];
                    _ref = data.errors;
                    for (key in _ref) {
                      ary = _ref[key];
                      for (_i = 0, _len = ary.length; _i < _len; _i++) {
                        err = ary[_i];
                        errors.push(err);
                      }
                    }
                    return $removeCategoryError.append($('<p>' + errors.join('<br>') + '</p>')).show();
                  } else {
                    $('li[data-type="' + type + '"][data-id="' + id + '"]').slideUp('fast', function() {
                      return $(this).remove();
                    });
                    return $removeCategoryDialog.modal('hide');
                  }
                },
                complete: function(data) {}
              });
              return false;
            });
          }).on('hidden', function() {}).modal();
          return false;
        });
        return $categoryDialogSaveButton.on('click', function() {
          var hasError, newCategoryColorSetID, newCategoryName;
          newCategoryName = $categoryNameField.val().trim();
          newCategoryColorSetID = $categoryColorField.val();
          $categoryDialogError.html('').hide();
          $categoryNameGroup.removeClass('error');
          $categoryColorGroup.removeClass('error');
          $categoryNameError.html('');
          $categoryColorError.html('');
          hasError = false;
          if (!(newCategoryName.length > 0)) {
            $categoryNameGroup.addClass('error');
            $categoryNameError.text('入力必須項目です');
            hasError = true;
          }
          if (hasError) return false;
          $.ajax({
            url: '/category/action/save.json',
            type: 'post',
            data: {
              name: newCategoryName,
              colorset: newCategoryColorSetID,
              type: type,
              id: id
            },
            dataType: 'json',
            success: function(data) {
              if (!data.success) {
                console.log(data.errors);
                if ((data.errors._common != null) && data.errors._common.length > 0) {
                  $categoryDialogError.html(data.errors._common.join('<br />')).show();
                }
                if (data.errors.name != null) {
                  $categoryNameGroup.addClass('error');
                  $categoryNameError.html(data.errors.name.join('<br />'));
                }
                if (data.errors.colorset != null) {
                  $categoryColorGroup.addClass('error');
                  return $categoryColorError.html(data.errors.colorset.join('<br />'));
                }
              } else {
                changeCategoryItem(type, data.category.id, data.category.name, data.category.color_set);
                $categoryDialogSaveButton.off('click');
                return $categoryDialog.modal('hide');
              }
            },
            complete: function(data) {}
          });
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
      maxHeight: 250,
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

  this.modified = false;

  $(function() {
    var $dialog, $message, saveFunc, saveInterval;
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
    saveInterval = 1000 * 2;
    return saveFunc = function() {
      var $items, ids;
      clearTimeout(timer);
      if (_this.modified === true) {
        $items = $('li', _this.favoriteList);
        ids = [];
        $items.each(function() {
          return ids.push($(this).attr('data-id'));
        });
        return $.ajax({
          url: '/category/action/favorite.json',
          type: 'post',
          data: {
            categories: ids
          },
          dataType: 'json',
          success: function(data) {
            var message;
            if (!data.success) {
              return message = 'お気に入りの保存に失敗しました';
            } else {
              message = data.time + ' お気に入りを保存しました';
              $message.text('2012/02/03 15:34:30 お気に入りを保存しました');
              $dialog.stop().show(1000);
              setTimeout(function() {
                $dialog.hide(1000);
                return setTimeout(saveFunc, saveInterval);
              }, 1000 * 2);
              return _this.modified = false;
            }
          }
        });
      } else {

      }
    };
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
