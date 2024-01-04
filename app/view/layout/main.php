<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= \vendor\libs\meta\Meta::getTitle();?></title>
    <meta name="description" content="<?= \vendor\libs\meta\Meta::getDescription()?>">
    <?= \vendor\libs\meta\Meta::getKeywords()?>
    <?= \vendor\libs\meta\Meta::getCanoncial();?>
    <!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
      <script>
        var e = ("article,aside,figcaption,figure,footer,header,hgroup,nav,section,time").split(',');
        for (var i = 0; i < e.length; i++) {
          document.createElement(e[i]);
        }
      </script>
    <![endif]-->

    <?php foreach (\app\Controller::getCss() as $Kcss => $Vcss):?>
        <link rel="stylesheet" type="text/css" href="<?= $Vcss;?>" media="all">
    <?php endforeach;?>
    <?php foreach (\app\Controller::getJs() as $Kjs => $Vjs):?>
    <script defer type="text/javascript" src="<?= $Vjs;?>"></script>
<?php endforeach;?>
    <style>
        .row, .column{
            display: flex;
        }
        .row{
            flex-direction: row;
        }
        .column{
            flex-direction: column;
        }
        .wrapper {
            min-height: 100%;
        }
        .content{
            flex: 1 0 auto;
        }
        footer{
            flex: 0 0 auto;
        }
        .just_center{
            justify-content: center;
        }
        .just_arround{
            justify-content: space-around;
        }
        .just_between
        {
            justify-content: space-between;
        }

    </style>
</head>
<body>
<div class="wrapper column">
    <header>

    </header>
    <div class="content">
        <?= $content;?>
    </div>
    <footer>

    </footer>
</div>
</body>
</body>
</html>
