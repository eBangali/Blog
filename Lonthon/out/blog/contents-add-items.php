<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (eblogin.'/session-inc.php'); ?>
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<?php include_once (eblayout.'/a-common-header-title-one.php'); ?>
<style>
        #WhatToDoFrame, #HowToDoFrame {
            width: 100%;
            height: 200px;
            border: 1px solid #ccc;
        }
        #WhatToDoTextArea, #HowToDoTextArea {
            display: none;
        }
        .toolbareditor {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f5f5f5;
        }
        .toolbareditor button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 5px;
        }
        .active {
            background-color: #eee;
        }
</style>
<?php include_once (eblayout.'/a-common-header-meta-scripts.php'); ?>
<?php include_once (eblayout.'/a-common-page-id-start.php'); ?>
<?php include_once (eblayout.'/a-common-header.php'); ?>
<nav>
  <div class='container'>
    <div>
      <?php include_once (eblayout.'/a-common-navebar.php'); ?>
      <?php include_once (eblayout.'/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>
<?php include_once (eblayout.'/a-common-page-id-end.php'); ?>
<?php include_once (ebaccess."/access-permission-online-minimum.php"); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class="well">
<h2 title='Add a New Post'>Add a New Post</h2>
</div>
<?php include_once (ebblog.'/blog.php'); ?>
<script language='javascript' type='text/javascript'>
/* Select B from A */
$(document).ready(function()
{
$("#contents_category").change(function()
{
var pic_name = $(this).val();
if(pic_name != '')  
{
$.ajax
({
type: "POST",
url: "contents-select-b-from-b.php",
data: "pic_name="+ pic_name,
success: function(option)
{
$("#contents_sub_category").html("<option value=''>Please Select</option>"+option);
}
});
}
else
{
$("#contents_sub_category").html("<option value=''>Please Select</option>");
}
return false;
});
});

</script>
<?php
$merchant = new ebapps\blog\blog();
/* Initialize valitation */
$error = 0;
$contents_category_error = "";
$contents_sub_category_error = "";
$contents_og_image_title_error = "";
$contents_og_image_what_to_do_error = "";
$contents_og_image_how_to_solve_error = "";
$contents_affiliate_link_error = "";
$contents_github_link_error = "";
$contents_preview_link_error = "";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['contents_add_items']))
{
extract($_REQUEST);
/* contents_category */
if(empty($_REQUEST["contents_category"]))
{
$contents_category_error = "<b class='text-warning'>Category required</b>";
$error =1;
} 
/* valitation contents_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_category))
{
$contents_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
else 
{
$contents_category = $sanitization -> test_input($_POST["contents_category"]);
}
/* contents_sub_category */
if (empty($_REQUEST["contents_sub_category"]))
{
$contents_sub_category_error = "<b class='text-warning'>Sub category required</b>";
$error =1;
} 
/* valitation contents_sub_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_sub_category))
{
$contents_sub_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
else 
{
$contents_sub_category = $_POST["contents_sub_category"];
}
/* contents_og_image_title */
if (empty($_REQUEST["contents_og_image_title"]))
{
$contents_og_image_title_error = "<b class='text-warning'>Title required</b>";
$error =1;
} 
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{5,59}$/u",$_POST["contents_og_image_title"]))
{
    $contents_og_image_title_error = "<b class='text-warning'>Single or double quotes, certain special characters are not allowed. Minimum characters 5 maximum characters 59</b>";
    $error =1;
}

/* SEO valitation contents_og_image_title */
elseif (strpos($_POST["contents_og_image_title"], $contents_sub_category) === false)
{
$contents_og_image_title_error = "<b class='text-warning'>Use mimimum one keyword as $contents_sub_category required</b>";
$error =1;
}
else 
{
$contents_og_image_title = $sanitization -> test_input($_POST["contents_og_image_title"]);
}

// Validate contents_og_image_what_to_do
if (empty($_POST["contents_og_image_what_to_do"]))
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Can not be empty.</b>";
$error = 1;
}

elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["contents_og_image_what_to_do"]))
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Only some tags are allowed.</b>";
$error = 1;
}

elseif(stripos($_POST["contents_og_image_what_to_do"], $contents_sub_category) === false)
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Use minimum one keyword: $contents_sub_category required.</b>";
$error = 1;
}
else
{
$contents_og_image_what_to_do_filtered = $sanitization->testArea($_POST["contents_og_image_what_to_do"]);
}

/*  Validate contents_og_image_how_to_solve */
if (empty($_POST["contents_og_image_how_to_solve"]))
{
$error = 0;
}

elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["contents_og_image_how_to_solve"]))
{
$contents_og_image_how_to_solve_error = "<b class='text-warning'>Only some tags are allowed.</b>";
$error = 1;
}

elseif(stripos($_POST["contents_og_image_how_to_solve"], $contents_sub_category) === false)
{
$contents_og_image_how_to_solve_error = "<b class='text-warning'>Use minimum one keyword: $contents_sub_category required.</b>";
$error = 1;
}
else
{
$contents_og_image_how_to_solve_filtered = $sanitization->testArea($_POST["contents_og_image_how_to_solve"]);
}

/* contents_affiliate_link */ 
if(!empty($_REQUEST["contents_affiliate_link"]))
{
/* valitation contents_affiliate_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_affiliate_link))
{
$contents_affiliate_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_affiliate_link = $sanitization -> test_input($_POST["contents_affiliate_link"]);
}
}

/* contents_github_link */ 
if (!empty($_REQUEST["contents_github_link"]))
{
/* valitation contents_github_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_github_link))
{
$contents_github_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_github_link = $sanitization -> test_input($_POST["contents_github_link"]);
}
}

/* contents_preview_link */ 
if (!empty($_REQUEST["contents_preview_link"]))
{
/* valitation contents_preview_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_preview_link))
{
$contents_preview_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_preview_link = $sanitization -> test_input($_POST["contents_preview_link"]);
}
}
/* Submition form */
if($error == 0)
{
extract($_REQUEST);
$merchant->submit_new_contents_item($contents_category, $contents_sub_category, $contents_og_image_title, $contents_og_image_what_to_do_filtered, $contents_og_login_required, $contents_og_image_how_to_solve_filtered, $contents_affiliate_link, $contents_github_link, $contents_preview_link);
}
//
}
?>
<div class="well">
<?php
if(isset($_SESSION['memberlevel']))
{
?>
<form method="post" accept-charset="UTF-8">
<fieldset class='group-select'>
Select Category: <?php echo $contents_category_error;  ?>
<select class='form-control' id='contents_category' name='contents_category' required><option value=''>Please Select</option><?php $merchant->select_contents_category(); ?></select>
Select Sub Category: <?php echo $contents_sub_category_error;  ?>
<select class='form-control' id='contents_sub_category' name='contents_sub_category' required><option value=''>Please Select</option></select>
Title/ Item Name: <?php echo $contents_og_image_title_error;  ?>
<input class='form-control' type="text" name="contents_og_image_title" placeholder="Single or double quotes, certain special characters are not allowed." required autofocus />
<div>
    What to do? <?php echo $contents_og_image_what_to_do_error; ?>
    <div class="toolbareditor" data-editor="WhatToDoFrame">
        <!-- p -->
        <button type="button" onclick="formatText('p', 'WhatToDoFrame')">
            <i class="fa fa-paragraph" aria-hidden="true"></i>
        </button>
        <!-- bold -->
        <button type="button" onclick="formatText('bold', 'WhatToDoFrame')">
            <i class="fa fa-bold" aria-hidden="true"></i>
        </button>
        <!-- italic -->
        <button type="button" onclick="formatText('italic', 'WhatToDoFrame')">
            <i class="fa fa-italic" aria-hidden="true"></i>
        </button>
        <!-- underline -->
        <button type="button" onclick="formatText('underline', 'WhatToDoFrame')">
            <i class="fa fa-underline" aria-hidden="true"></i>
        </button>
        <!-- ordered list -->
        <button type="button" onclick="formatText('insertOrderedList', 'WhatToDoFrame')">
            <i class="fa fa-list-ol" aria-hidden="true"></i>
        </button>
        <!-- unordered list -->
        <button type="button" onclick="formatText('insertUnorderedList', 'WhatToDoFrame')">
            <i class="fa fa-list-ul" aria-hidden="true"></i>
        </button>

        <!-- New heading buttons h2-h6 -->
        <button type="button" onclick="formatText('h2', 'WhatToDoFrame')">H2</button>
        <button type="button" onclick="formatText('h3', 'WhatToDoFrame')">H3</button>
        <button type="button" onclick="formatText('h4', 'WhatToDoFrame')">H4</button>
        <button type="button" onclick="formatText('h5', 'WhatToDoFrame')">H5</button>
        <button type="button" onclick="formatText('h6', 'WhatToDoFrame')">H6</button>

        <!-- Definition list buttons -->
        <button type="button" onclick="formatText('dt', 'WhatToDoFrame')">DT</button>
        <button type="button" onclick="formatText('dd', 'WhatToDoFrame')">DD</button>

        <!-- source view -->
        <button type="button" onclick="toggleSourceView('WhatToDoFrame', this)" name="active">
            <i class="fa fa-code" aria-hidden="true"></i>
        </button>
    </div>
    <iframe id="WhatToDoFrame" class="form-control"></iframe>
    <textarea id="WhatToDoTextArea" class="form-control" name="contents_og_image_what_to_do"></textarea>
</div>
Log In required to read more? <select class='form-control' name='contents_og_login_required' required><?php if($_SESSION['memberlevel'] <= 1 ){ echo "<option value='YES'>YES</option>"; } else { echo "<option value='YES'>YES</option><option value='NO'>NO</option>"; } ?></select>
<div>
    How to do?: <?php echo $contents_og_image_how_to_solve_error; ?>
    <div class="toolbareditor" data-editor="HowToDoFrame">
        <!-- p -->
        <button type="button" onclick="formatText('p', 'HowToDoFrame')">
            <i class="fa fa-paragraph" aria-hidden="true"></i>
        </button>
        <!-- bold -->
        <button type="button" onclick="formatText('bold', 'HowToDoFrame')">
            <i class="fa fa-bold" aria-hidden="true"></i>
        </button>
        <!-- italic -->
        <button type="button" onclick="formatText('italic', 'HowToDoFrame')">
            <i class="fa fa-italic" aria-hidden="true"></i>
        </button>
        <!-- underline -->
        <button type="button" onclick="formatText('underline', 'HowToDoFrame')">
            <i class="fa fa-underline" aria-hidden="true"></i>
        </button>
        <!-- ordered list -->
        <button type="button" onclick="formatText('insertOrderedList', 'HowToDoFrame')">
            <i class="fa fa-list-ol" aria-hidden="true"></i>
        </button>
        <!-- unordered list -->
        <button type="button" onclick="formatText('insertUnorderedList', 'HowToDoFrame')">
            <i class="fa fa-list-ul" aria-hidden="true"></i>
        </button>

        <!-- New heading buttons h2-h6 -->
        <button type="button" onclick="formatText('h2', 'HowToDoFrame')">H2</button>
        <button type="button" onclick="formatText('h3', 'HowToDoFrame')">H3</button>
        <button type="button" onclick="formatText('h4', 'HowToDoFrame')">H4</button>
        <button type="button" onclick="formatText('h5', 'HowToDoFrame')">H5</button>
        <button type="button" onclick="formatText('h6', 'HowToDoFrame')">H6</button>

        <!-- Definition list buttons -->
        <button type="button" onclick="formatText('dt', 'HowToDoFrame')">DT</button>
        <button type="button" onclick="formatText('dd', 'HowToDoFrame')">DD</button>

        <!-- source view -->
        <button type="button" onclick="toggleSourceView('HowToDoFrame', this)" name="active">
            <i class="fa fa-code" aria-hidden="true"></i>
        </button>
    </div>

    <iframe id="HowToDoFrame" class="form-control"></iframe>
    <textarea id="HowToDoTextArea" class="form-control" name="contents_og_image_how_to_solve"></textarea>
</div>
Affiliate Link: <?php echo $contents_affiliate_link_error;  ?>
<input class='form-control' placeholder="amazon.com/abc/" type="text" name="contents_affiliate_link" />
Download Link: <?php echo $contents_github_link_error;  ?>
<input class='form-control' placeholder="github.com/abc/" type="text" name="contents_github_link" />
Preview Link: <?php echo $contents_preview_link_error;  ?>
<input class='form-control'  placeholder="domain.com/abc/" type="text" name="contents_preview_link" />
<div class='buttons-set'>
<button type='submit' onclick="transferContent()" name='contents_add_items' title='Submit' class='button submit'> <span> Submit </span> </button></div>
</fieldset>
</form>
<?php
}
?>
<script>
document.addEventListener("DOMContentLoaded", initEditors);

function initEditors() {
    const editors = ['WhatToDoFrame', 'HowToDoFrame'];

    editors.forEach(id => {
        const iframe = document.getElementById(id);
        const doc = iframe.contentDocument || iframe.contentWindow.document;

        doc.designMode = 'on';
        doc.body.innerHTML = '<p><br></p>';

        doc.addEventListener('keypress', e => {
            if(e.key === 'Enter') {
                e.preventDefault();
                insertParagraph(doc);
            }
        });

        doc.addEventListener('paste', e => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            const paragraphs = text.split(/\r?\n/).filter(line => line.trim() !== '');
            paragraphs.forEach(line => {
                const p = doc.createElement('p');
                p.textContent = line;
                doc.body.appendChild(p);
            });
        });
    });
}

function insertParagraph(doc) {
    const selection = doc.getSelection();
    if (!selection.rangeCount) return;
    const range = selection.getRangeAt(0);
    const p = doc.createElement('p');
    p.innerHTML = '<br>';
    let currentNode = range.startContainer;
    while(currentNode && currentNode.nodeName.toLowerCase() !== 'p') {
        currentNode = currentNode.parentNode;
    }
    if(currentNode && currentNode.parentNode) {
        currentNode.parentNode.insertBefore(p, currentNode.nextSibling);
        const newRange = doc.createRange();
        newRange.setStart(p,0);
        newRange.collapse(true);
        selection.removeAllRanges();
        selection.addRange(newRange);
    }
}

function transferContent() {
    const map = {"WhatToDoFrame":"WhatToDoTextArea", "HowToDoFrame":"HowToDoTextArea"};
    for(const frameId in map){
        const iframe = document.getElementById(frameId);
        const textarea = document.getElementById(map[frameId]);
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        let html = doc.body.innerHTML;
        html = html.replace(/<p><br><\/p>/gi,'').replace(/<p>\s*<\/p>/gi,'');
        textarea.value = html.trim();
    }
    return true;
}

function formatText(command, frameId){
    const iframe = document.getElementById(frameId);
    const doc = iframe.contentDocument || iframe.contentWindow.document;
    const headingTags = ['h2','h3','h4','h5','h6','dt','dd','p'];

    if(headingTags.includes(command)){
        const selection = doc.getSelection();
        if(selection.rangeCount > 0){
            const range = selection.getRangeAt(0);
            const wrapper = doc.createElement(command);
            wrapper.appendChild(range.extractContents());
            range.insertNode(wrapper);
            selection.removeAllRanges();
            const newRange = doc.createRange();
            newRange.selectNodeContents(wrapper);
            selection.addRange(newRange);
        }
    } else {
        doc.execCommand(command,false,null);
    }
    iframe.contentWindow.focus();
}

function toggleSourceView(frameId, btn){
    const iframe = document.getElementById(frameId);
    const doc = iframe.contentDocument || iframe.contentWindow.document;
    const body = doc.body;
    const isSource = body.getAttribute('data-source') === 'true';
    if(isSource){
        body.innerHTML = body.textContent;
        body.setAttribute('data-source','false');
        btn.style.background='';
    } else {
        body.textContent = body.innerHTML;
        body.setAttribute('data-source','true');
        btn.style.background='#ccc';
    }
}
</script>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once(ebcontents."/contents-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>