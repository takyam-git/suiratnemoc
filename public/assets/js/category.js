(function() {

  $(function() {
    var $addMyCategoryBtn, $addMyCategoryDialog, $baseCategoryList, $favoriteList, $globalList, $modifyCategoryDialog, $myList, draggingFlag, listDraggableOption, listFavoriteClassName, unFavoriteClickEvent;
    $baseCategoryList = $('.baseCategoryList');
    $favoriteList = $('#favoriteCategoryList');
    $globalList = $('#globalCategory .categoryList');
    $myList = $('#myCategory .categoryList');
    $addMyCategoryBtn = $('a#add-my-category-btn');
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
        $this.draggable('destroy');
        $this.addClass(listFavoriteClassName);
        $newFavorite = $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $favoriteList);
        $('i', $newFavorite).remove();
        $newFavorite.append('<i class="icon-remove icon-white pull-right category-config-icon"></i>');
        $newFavorite.on('click', function() {
          if (draggingFlag === false) {
            $this = $(this);
            $('li[data-type="' + $this.data('type') + '"][data-id="' + $this.data('id') + '"]', $baseCategoryList).removeClass(listFavoriteClassName).draggable(listDraggableOption);
            $this.remove();
          }
          return false;
        });
        return draggingFlag = false;
      }
    };
    unFavoriteClickEvent = $modifyCategoryDialog = $('#modifyCategoryDialog').on('shown', function() {
      return console.log('open');
    });
    $favoriteList.sortable({
      revert: true,
      start: function() {
        draggingFlag = true;
        return null;
      },
      stop: function() {
        draggingFlag = false;
        return null;
      }
    });
    $('li', $baseCategoryList).draggable(listDraggableOption).disableSelection().on('click', function() {
      $modifyCategoryDialog.modal();
      return false;
    });
    $addMyCategoryDialog = $('#modifyCategoryDialog').on('shown', function() {
      return console.log('add');
    });
    return $addMyCategoryBtn.click(function() {
      $addMyCategoryDialog.modal();
      return false;
    });
  });

  $(function() {
    var $triggerButton, $valueField, defaultButtonText;
    $triggerButton = $('#selectColorSet');
    defaultButtonText = $triggerButton.text();
    $valueField = $('#selectedColorID');
    $triggerButton.menu({
      content: $('#colorSetList').clone().html(),
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
    return $(document).on('click', 'a.colorSetCategoryLink', function() {
      console.log('hoge');
      return false;
    });
  });

}).call(this);
