<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <title><?=$_studio['store_name']??''?></title>
    <meta name="keywords" content="<?=systemSetting('general_site_keywords')?>">
    <meta name="description" content="<?=$_studio['store_desc']??''?>">
    <?php if(!empty($og_tag_type)){ ?>
        <meta property="og:type" content="<?=$og_tag_type??'website'?>" />
        <meta property="og:title" content="<?=$og_tag_title??''?>" />
        <meta property="og:url" content="<?=$og_tag_url??''?>" />
        <meta property="og:image" content="<?=$og_tag_image??''?>" />
        <meta property="og:description" content="<?=$og_tag_description??''?>" />
    <?php } ?>
    <link rel="stylesheet" href="/static/mp.ionic/css/reset.css?<?=STATIC_VER?>">
    <link rel="stylesheet" href="/static/mp.ionic/3rd/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="/static/mp.ionic/css/common.css?<?=STATIC_VER?>">
    <link rel="stylesheet" href="/static/mp.ionic/css/page.css?<?=STATIC_VER?>">
    <link rel="stylesheet" href="/static/mp.ionic/dist8/css/ionic.bundle.css" />
    <link rel="stylesheet" href="/static/mp.ionic/css/report.css?<?=STATIC_VER?>">
    <script>
        var SITE_URL = '<?=SITE_URL?>';
    </script>
    <style>
        html {
        --ion-dynamic-font: var(--ion-default-dynamic-font);
        }
    </style>
</head>
<body style="max-width:750px; margin:0 auto; position:static;">
<div id="loader-wrapper">
	<div id="loader">
	</div>
</div>