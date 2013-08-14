<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();


// get params
$title           = $this->params->get('Title');
$description    = $this->params->get('Tagline');
$bg        = $this->params->get('background');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/default.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/badge.css" type="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<link rel="canonical" href=""><!-- http://goo.gl/wKFDI -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.5.3/modernizr.min.js"></script>
</head>
<body id="<?php echo $bg; ?>">
<!--[if IE]><div class="ie"><![endif]-->
<div id="wrapper">
  <header class="clearfix container">
      <h1><a href="/"><?php echo $title; ?></a></h1>
      <h2><?php echo $description; ?></h2>
    <nav role="navigation">
    <jdoc:include type="modules" name="top-menu" style="none" />
    </nav>
  </header>
  <div class="container">
  <aside role="complimentary" id="complimentary">
    <jdoc:include type="modules" name="side" style="craft" />
  </aside><!-- complimentary -->
    <div id="content-before"><!-- Should be empty --></div>
  <div role="main" id="main">
      <jdoc:include type="message" />
      <jdoc:include type="component" />     
  </div>
  <div id="content-after"><!-- Should be empty --></div>
  </div>
  <footer>
   <jdoc:include type="modules" name="footer" style="xhtml" />
  </footer>
</div><!-- #wrapper -->

<!--[if IE]></div><![endif]-->
  <!--Place your JavaScript down here-->
  <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>js/fontloader.js"></script><!-- Load custom online fonts http://goo.gl/Deqf0 -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</body>
</html>
