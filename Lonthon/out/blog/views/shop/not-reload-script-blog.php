<script>
function buTTonSelectBlog()
{
$('form').on('submit',function(eBevent)
{
eBevent.preventDefault();
var dataStringBlog = $(this).serialize();
var contentID = $(this).attr('id');
$.ajax({
url: '<?php echo outContentsRequest.'/blog-pages.php'; ?>',
type: 'POST',
data: dataStringBlog,
dataType:'JSON',
async: false,
success: function(xX)
{
$('.'+contentID).html(xX.postArticleLike);
}
});
});
};
</script>