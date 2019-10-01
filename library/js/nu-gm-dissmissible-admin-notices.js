(function($){
  $(document).ready(function () {
    $(document).on( 'click', '.nu-gm-is-dismissible .notice-dismiss', function(e) {
      var noticeId = $(e.target).parent('.nu-gm-is-dismissible').data('nu-gm-admin-notice-id');
      $.ajax({
        url: nu_gm_dismiss_admin_notice.ajax_url,
        type : 'post',
        data: {
          action: 'nu_gm_dismiss_admin_notice',
          notice_id: noticeId
        }
      });
    });
  });
})(jQuery);