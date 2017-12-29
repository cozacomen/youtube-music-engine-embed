
<!DOCTYPE html>
<html>
  <head>
    <!-- YOUTUBE-MUSIC-EMBED : https://github.com/cozacomen/youtube-music-engine-embed -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="cleartype" content="on">
    <!-- IE Specific to remove tap highlight -->    
    <!-- Powered By <?php echo $this->config->item("title"); ?>  - <?php echo base_url(); ?> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
    <script src="//code.jquery.com/jquery-2.1.4.min.js" charset="utf-8" ></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jplayer/2.9.2/jplayer/jquery.jplayer.min.js" type="text/javascript" charset="utf-8" ></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">    
    <link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <script>
    var type = '<?php echo $type; ?>';
    var song = '<?php echo $song; ?>';
    var playlist = <?php echo json_encode($playlist); ?>;
    var autoplay = '<?php echo $autoplay; ?>';
    var link = '<?php echo $link; ?>';
    </script>
    <style>
    <?php
      $color1= $this->config->item("embed_color1"); 
      $color2= $this->config->item("embed_color2"); 
      $color3= $this->config->item("embed_color3"); 
      $color4= $this->config->item("embed_color4"); 
      $color5= $this->config->item("embed_color5"); 
      $color6= $this->config->item("embed_color6"); 
      
    ?>
    body
    {
      background: <?php echo $color1; ?>; 
    }
    #player
    {       
      
      background: <?php echo $color1; ?>; /* Old browsers */
      background:<?php echo $color1; ?>;
      background:-moz-linear-gradient(top,<?php echo $color2; ?> 0%,<?php echo $color1; ?> 100%);
      background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $color2; ?>),color-stop(100%,<?php echo $color1; ?>));
      background:-webkit-linear-gradient(top,<?php echo $color2; ?> 0%,<?php echo $color1; ?> 100%);
      background:-o-linear-gradient(top,<?php echo $color2; ?> 0%,<?php echo $color1; ?> 100%);
      background:-ms-linear-gradient(top,<?php echo $color2; ?> 0%,<?php echo $color1; ?> 100%);
      background:linear-gradient(to bottom,<?php echo $color2; ?> 0%,<?php echo $color1; ?> 100%);      
        
      }

      #player.playlist ul#playlist
      { 
          background-color: <?php echo $color1; ?>;
      }
      #player.playlist ul#playlist li
      {
          background-color:  <?php echo $color2; ?>;
            color:  <?php echo $color4; ?>;
      }

      #player.playlist ul#playlist li.active
      {
         color: <?php echo $color6; ?>;
         font-weight: bold;
      }
      #player.playlist ul#playlist li:hover
      {
        color: <?php echo $color5; ?>;
      }
      .jp-audio
      {
         color: <?php echo $color4; ?>;
      }
      .jp-audio .jp-progress .jp-seek-bar .jp-play-bar 
        {
          background-color: <?php echo $color3; ?>;
        }


    </style>
    <link href='<?php echo base_url(); ?>assets/css/themes/musik/embed/style.css' rel='stylesheet' type='text/css'>
    <script src="<?php echo base_url(); ?>assets/js/themes/musik/embed/script.js" charset="utf-8" ></script>    
  </head>
  <body>
<div id="player" class="<?php echo $type; ?>" title="<?php echo $this->config->item("title"); ?>&#013;&#xA;<?php echo base_url(); ?>">
  <div class="avatar link" style="background-image: url('<?php echo $avatar; ?>');"></div>
  <div class="player">
     <div id="jquery_jplayer_1" class="jp-jplayer"></div>
      <div id="jp_container_1" class="jp-audio">
        <div class="jp-controls">
          <a class="jp-play"><i class="fa fa-play"></i></a>
          <a class="jp-pause"><i class="fa fa-pause"></i></a>
        </div>
        <div class="jp-progress">
          <div class="jp-seek-bar">
            <div class="jp-play-bar">
            </div>
          </div>          
        </div>        
        <div class="jp-no-solution">
          Media Player Error<br>
          Update your browser or Flash plugin
        </div>
       <div class="jp-current-time"></div>    
        <div class="jp-data truncate link">  
          <div class="truncate jp-title-artist" onClick="window.open('https://music.ivlis.kr/artist/<?php echo $artist; ?>');"><?php echo $artist; ?></div>     
          <div class="truncate jp-title-track" onClick="window.open('https://music.ivlis.kr/?artist=<?php echo $artist; ?>&track=<?php echo $track; ?>');"><?php echo $track; ?></div>     
        </div>

      </div>
      </div>
      <ul id="playlist" class='truncate'>        
      </ul>
    </div>
    <?php if($this->config->item("title_embed")){ ?>
    <div class="poweredby" title="<?php echo base_url(); ?>">
        <i class="fa fa-times"></i> <a href="<?php echo base_url(); ?>" target="_blank"></a>
    </div>
    <?php } ?>
    <div class="brand" title="<?php echo $this->config->item("title"); ?>">
      <a target="_blank" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/favicon.png"></a>
    </div>


  </body>
</html>
