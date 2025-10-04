<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<script>
(function($)
{
$.fn.loadScrollData = function (start, options) {
var settings = $.extend({
limit: 1,
listingId: '',
loadMsgId: '',
ajaxUrl: '',
loadingMsg: '<div class="alert alert-warning p-1 text-center"><i class="fa fa-fw fa-spin fa-spinner"></i>Please Wait ...!</div>',
loadingSpeed: 1
}, options);

action = "inactive";

$.ajax({
method: "POST",
data: {
'getData': 'OKDATA',
'limit': settings.limit,
'start': start
},
url: settings.ajaxUrl,
success: function (data) {
$(settings.listingId).append(data);
if (data == '') {
$(settings.loadMsgId).html('');
action = 'active';
} else{
$(settings.loadMsgId).html(settings.loadingMsg);
action = "inactive";
}
}
});

if (action == 'inactive'){
action = 'active';
}

$(window).scroll(function () {
if ($(window).scrollTop() + $(window).height() > $(settings.listingId).height() && action == 'inactive') {
action = 'active';
start = parseInt(start) + parseInt(settings.limit);
setTimeout(function(){
$.fn.loadScrollData(start, options);
}, settings.loadingSpeed);
}
});

};
}(jQuery));
</script>
