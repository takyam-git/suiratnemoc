$ =>
  $triggerButton = $('#categorySet')
  defaultButtonText = $triggerButton.text()
  $valueField = $('#category')
  $colorsContainer = $('#categoryList')
  $triggerButton.menu({
    content: $colorsContainer.clone().html()
    backLinkText: '戻る'
    crumbDefaultText: ''
    #flyOut: true
    maxHeight: 300
    onSelect: ($item) ->
      colorSetID = $item.data('id')
      if colorSetID == 'cancel'
        $triggerButton.text(defaultButtonText)
        $valueField.val('')
      else
        colorSetID = parseInt(colorSetID)
        if colorSetID > 0
          $triggerButton.text($item.text())
          $valueField.val(colorSetID)
      true
  });