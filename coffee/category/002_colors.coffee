$ =>
  $triggerButton = $('#selectColorSet')
  defaultButtonText = $triggerButton.text()
  $valueField = $('#selectedColorID')
  $colorsContainer = $('#colorSetList')
  $triggerButton.menu({
    content: $colorsContainer.clone().html()
    backLinkText: '戻る'
    crumbDefaultText: ''
    maxHeight: 250
    #flyOut: true
    onSelect: ($item) ->
      colorSetID = $item.data('id')
      if colorSetID == 'cancel'
        $triggerButton.text(defaultButtonText)
        $valueField.val('')
      else
        colorSetID = parseInt(colorSetID)
        if colorSetID > 0
          $triggerButton.text($item.data('name'))
          $valueField.val(colorSetID)
      true
  });