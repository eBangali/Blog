<?php include_once(dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once(eblogin.'/session-inc.php'); ?>
<?php include_once(eblayout.'/a-common-header-icon.php'); ?>
<?php include_once(eblayout.'/a-common-header-title-one.php'); ?>
<?php include_once(eblayout.'/a-common-header-meta-scripts.php'); ?>
<?php include_once(eblayout.'/a-common-page-id-start.php'); ?>
<?php include_once(eblayout.'/a-common-header.php'); ?>
<nav>
  <div class='container'>
    <div>
      <?php include_once(eblayout.'/a-common-navebar.php'); ?>
      <?php include_once(eblayout.'/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>
<?php include_once(eblayout.'/a-common-page-id-end.php'); ?>
<?php include_once(ebaccess.'/access-permission-administration-minimum.php'); ?>	

<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'></div>

    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
      <div class='well'>
        <h2 title='Upload video:'>Upload video:</h2>
        <p>Upload video: .mp4</p>
      </div>

      <div class='well'>
        <?php include_once(ebblog.'/blog.php'); ?>
        <?php 
        $merchant = new ebapps\blog\blog(); 
        $merchant->content_upload_video();
        ?>
        
        <?php if ($merchant->eBData) { foreach($merchant->eBData as $val) { extract($val); ?>
        <form class="uploadFormBlog" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="contents_id" value="<?php echo $contents_id; ?>">
          <input type="file" name="file" accept="video/mp4">
          <input type="submit" value="Upload">
          <div class="progressWrapper" style="margin-top:10px;">
            <div class="progressBar" style="width:0%; background:#4caf50; height:20px; color:#fff; text-align:center;"></div>
          </div>
          <div class="status" style="margin-top:5px; color:#555;"></div>
        </form>
        <hr>
        <?php }} ?>
      </div>
    </div>

    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
      <?php include_once(ebcontents."/contents-my-account.php"); ?>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('.uploadFormBlog').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        var fileInput = form.find('input[type="file"]')[0];
        var file = fileInput.files[0];
        var contents_id = form.find('input[name="contents_id"]').val();
        var progressBar = form.find('.progressBar');
        var status = form.find('.status');

        if (!file) {
            status.text('No file selected.');
            return;
        }

        if (file.type !== 'video/mp4') {
            status.text('Only .mp4 files are allowed.');
            return;
        }

        var formData = new FormData();
        formData.append('file', file);
        formData.append('contents_id', contents_id);

        $.ajax({
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        progressBar.css('width', percentComplete + '%').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: 'contents-video-upload-scripts.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                status.text(response);
              //
                setTimeout(function(){
                window.location.replace('contents-items-status.php');
                }, 3000);
                setTimeout();
                //
            },
            error: function(xhr, statusText, error) {
                status.text('Error: ' + error);
            }
        });
    });
});
</script>

<?php include_once(eblayout.'/a-common-footer.php'); ?>
