$ ->
  $triggerButton = $('#selectColorSet')
  defaultButtonText = $triggerButton.text()
  $valueField = $('#selectedColorID')
  $triggerButton.menu({
    content: $('#colorSetList').clone().html()
    backLinkText: '戻る'
    crumbDefaultText: ''
    flyOut: true
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
  
  $(document).on('click', 'a.colorSetCategoryLink', ->
    console.log 'hoge'
    false
  )
