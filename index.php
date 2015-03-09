<?php
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('CONTENT_DIR', ROOT_DIR .'mcdwiki/'); //change this to change which folder you want your content to be stored in

// Change this to your strapdown.js location before using! Edit the theme tag below to use different Bootswatch themes.
// It is recommended that you serve strapdown.js locally, rather than from strapdownjs' website:
$strapdown_location = "/strapdown/v/0.2/strapdown.js";
// $strapdown_location = "http://strapdownjs.com/v/0.2/strapdown.js";
$bootswatch_theme = "spacelab"; //choose any bootstrap theme included in strapdown.js!
$file_format = ".md"; //change this to choose a file type, be sure to include the period

// Get request url and script url
$url = '';
$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
	
// Get our url path and trim the / of the left and the right
if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');

// Get the file path
if($url) $file = strtolower(CONTENT_DIR . $url);
else $file = CONTENT_DIR .'index';

// Load the file
if(is_dir($file)) {
  $path = $url;
  $file = CONTENT_DIR . $url .'/index' . $file_format;
} else {
  $path = substr($url, 0, strrpos($url, "/"));
  $file .=  $file_format;
}

// Show 404 if file cannot be found
if(file_exists($file)) $content = file_get_contents($file);
else $content = file_get_contents(CONTENT_DIR .'404' . $file_format);
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $url; ?></title>
</head>

<xmp theme="<?php echo $bootswatch_theme; ?>" style="display:none;">

<div class="btn-toolbar" role="toolbar" aria-label="grouping">
  <div class="btn-group btn-group-xs" role="group" aria-label="home">
     <button type="button" class="btn btn-default"><a href="/">home</a></button>
  </div>
  <div class="btn-group btn-group-xs" role="group" aria-label="thisDir">
<?php
$dir = new DirectoryIterator(CONTENT_DIR . $path);
foreach ($dir as $fileinfo) {
  $displayName = explode($file_format, $fileinfo)[0];
  if (!$fileinfo->isDot() && ("." . $fileinfo->getExtension() == $file_format || $fileinfo->getExtension() == "") && ($displayName != "404")) {
    ?>
    <button type="button" class="btn btn-default"><a href="/<?php if($path != "") {echo $path; echo "/";} echo $displayName; ?>"><?php echo $displayName; if ($fileinfo->isDir()) echo "/"; ?></a></button>
    <?php
  }
}
?>
  </div>
  <div class="btn-group btn-group-xs" role="group" aria-label="up">
    <button type="button" class="btn btn-default"><a href="../">up</a></button>
  </div>
</div>

<?php echo $content; ?>
</xmp>
<script src="<?php echo $strapdown_location; ?>"></script>
</html>