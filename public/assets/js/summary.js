(function() {
  var _this = this;

  $(function() {
    var clearError, setError;
    _this.$search_start = $('#search_start');
    _this.$remove_start = $('#remove_start');
    _this.$search_end = $('#search_end');
    _this.$remove_end = $('#remove_end');
    _this.$error_box = $('#search_error');
    _this.$submit = $('#go_summary');
    _this.base_url = _this.$submit.attr('href');
    _this.$error_box.hide();
    $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
      dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
      dayNamesShort: ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'],
      monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
      monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
      yearSuffix: '年',
      showMonthAfterYear: true
    });
    _this.$remove_start.on('click', function() {
      _this.$search_start.val('');
      return false;
    });
    _this.$remove_end.on('click', function() {
      _this.$search_end.val('');
      return false;
    });
    setError = function(message) {
      return _this.$error_box.append('<div>' + message + '</div>').show();
    };
    clearError = function() {
      return _this.$error_box.html('').hide();
    };
    _this.date_regexp = new RegExp('^[0-9]{4}(-|\/)[0-9]{1,2}(-|\/)[0-9]{1,2}$');
    return _this.$submit.on('click', function() {
      var end, hasError, start, url;
      clearError();
      start = $search_start.val();
      end = $search_end.val();
      hasError = false;
      if (start.length === 0) {
        setError('開始日は入力必須項目です');
        hasError = true;
      }
      if (start.length > 0 && start.match(_this.date_regexp) === null) {
        setError('開始日の書式が誤っています');
        hasError = true;
      }
      if (end.length > 0 && end.match(_this.date_regexp) === null) {
        setError('終了日の書式が誤っています');
        hasError = true;
      }
      if (!hasError) {
        url = _this.base_url + start.replace('/', '-');
        if (end.length > 0) url = url + '/' + end.replace('/', '-');
        location.href = url;
      }
      return false;
    });
  });

}).call(this);
