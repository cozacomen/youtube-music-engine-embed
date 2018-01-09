$(function () {
  $(".link").on('click',  function(event) {
    event.preventDefault();
    //top.location = link;
    console.log("link");
  }); 

  $(".poweredby i").on('click',  function(event) {
      $(".poweredby").fadeOut(250);      
  }); 

  $(document).on('click','#playlist li',  function(event) {
    event.preventDefault();
    playNext($(this).index());
  });
  

});
var current = 0;
$(function () {
  if(type = 'playlist')
  {

    song = playlist[0].song;
    $(".jp-title-artist").text(playlist[0].artist);
    $(".jp-title-track").text(playlist[0].track);

    if(playlist[0].cover)
    {
      $('.avatar').css("background-image", "url('"+playlist[0].cover+"')");  
    }
    $("#playlist").empty();
    $.each(playlist, function(index, val) {
       $("#playlist").append("<li title='"+val.artist + " - "+val.track+"' class='truncate'>"+val.artist + " - "+val.track+"</li>");
    });
     $('ul#playlist li').eq(0).addClass("active");
  }  
});


function hhmmss(secs)
{
  var sec_num = parseInt(secs, 10); // don't forget the second parm
  var hours   = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
  var seconds = sec_num - (hours * 3600) - (minutes * 60);
  if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  //var time    = hours+':'+minutes+':'+seconds;
  var time    = minutes+':'+seconds;
  return time;                     
}



  var tag = document.createElement('script');
  tag.src = "//www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  
  var yt_player_1;
  function onYouTubeIframeAPIReady(){
    yt_player_1 = new YT.Player('jquery_jplayer_1',{
      height    : '200',
      width   : '200',
      videoId   : song,      
      playerVars:{
        'autohide':     1,
        'autoplay':     autoplay,
        'controls':     0,
        'fs':       1,
        'disablekb':    0,
        'modestbranding': 1,
        // 'cc_load_policy': 1, // forces closed captions on
        'iv_load_policy': 3, // annotations, 1=on, 3=off
        // 'playlist': videoID, videoID, videoID, etc,
        'rel':        0,
        'showinfo':     0,
        'theme':      'light',  // dark, light
        'color':      'white',  // red, white
        'webkit-playsinline': '1',
        'allowfullscreen' : '0'
        
        },
      events:{
        'onReady': onPlayerReady,
        'onPlaybackQualityChange': onPlayerPlaybackQualityChange,
        'onPlaybackRateChange' : onPlaybackRateChange,
        'onStateChange': onPlayerStateChange,
        'onError': onPlayerError
        }
      });

        }
  
  function onPlayerReady(data){
    initializeJplayerControls();
    yt_player_1.setVolume(80);
    $('#jp_container_1 .jp-volume-bar .jp-volume-bar-value').width( '80%' );

      if(type = 'playlist' && autoplay==1)
      {
        
        playNext();        
      } 
      if(type = 'playlist' && autoplay==0)
      {
        current = 1;        
      }


  } // END FUNCTION
  

  function playNext(target)
  {
    if(target)
      current = target;
    if(current<playlist.length)
    {
      $('ul#playlist li').removeClass("active");
      $('ul#playlist li').eq(current).addClass("active");
      yt_player_1.loadVideoById(playlist[current].song, 0,'small');
      $(".jp-title-artist").text(playlist[current].artist);
      $(".jp-title-track").text(playlist[current].track);
      if(playlist[current].cover)
      {
        $('.avatar').css("background-image", "url('"+playlist[current].cover+"')");  
      }

      current++;
    }
    else
    {
      current = 0;
    }
    
  }
  function onPlayerPlaybackQualityChange(quality){
  } // END FUNCTION
    
  function onPlaybackRateChange(rate){
  } // END FUNCTION
  
  function onPlayerStateChange(state){
    switch(state.data){
      case -1: //unstarted
        /* do something */
        break;
      case 0: // ended
        $('#jp_container_1 .jp-pause').show();
        $('#jp_container_1 .jp-play').hide();
          if(type = 'playlist')
          {
            playNext();          
          }
        break;
      case 1: // playing
        $('#jp_container_1 .jp-pause').show();
        $('#jp_container_1 .jp-play').hide();
        startYoutubeTime();
        break;
      case 2: // paused
        $('#jp_container_1 .jp-pause').hide();
        $('#jp_container_1 .jp-play').show();
        break;
      case 3: // buffering
        /* do something */
        break;
      case 5: // video cued
        /* do something */
        break;
      default:
        // do nothing
      }
  } // END FUNCTION
  
  function onPlayerError(error){
    console.log(error);
  } // END FUNCTION
  
  function youtubeFeedCallback(data){
    jQuery(document).ready(function(){
      $('.jp-track-title').text( data.entry['title'].$t /* +' - from: '+data.entry["author"][0].name.$t */ );
      $('#jp_container_1 .jp-duration').text(hhmmss(yt_player_1.getDuration()));
    });
  } // END FUNCTION
  
  var youTubeFrequency = 100;
  var youTubeInterval = 0;
  function startYoutubeTime(){
    if(youTubeInterval > 0) clearInterval(youTubeInterval);  // stop
    youTubeInterval = setInterval( "updateYoutubeTime()", youTubeFrequency );  // run
  } // END FUNCTION
  function updateYoutubeTime(){
    
    
      $('#jp_container_1 .jp-current-time').text(hhmmss(yt_player_1.getCurrentTime()) + " / "+hhmmss(yt_player_1.getDuration()) );
    
      
    
    
    $('#jp_container_1 .jp-progress .jp-play-bar').width( Math.round((yt_player_1.getCurrentTime()/yt_player_1.getDuration())*100)+'%' );
  } // END FUNCTION
  function FormatNumberLength(num,length){var r=""+num;while(r.length<length){r="0"+r;}return r;} // END FUNCTION
    
  function initializeJplayerControls(){
    $('#jp_container_1 .jp-pause').hide();
    $('#jp_container_1 .jp-unmute').hide();
    $('#jp_container_1 .jp-restore-screen').hide();
    
    $('#jp_container_1 .jp-play').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-pause').show();
      yt_player_1.playVideo();
    });
    $('#jp_container_1 .jp-pause').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-play').show();
      yt_player_1.pauseVideo();
    });
    $('#jp_container_1 .jp-full-screen').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-restore-screen').show();
      $('#jp_container_1').addClass('jp-video-full');
      $('#jp_container_1 .jp-jplayer, #jp_container_1 #yt_player_1').css({'width':'100%','height':'100%'});
    });
    $('#jp_container_1 .jp-restore-screen').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-full-screen').show();
      $('#jp_container_1').removeClass('jp-video-full');
      $('#jp_container_1 .jp-jplayer, #jp_container_1 #yt_player_1').removeAttr('style');
      
      $('#jp_container_1 .jp-gui').show();
      clearTimeout(fullScreenHoverTime);
    });
    $('#jp_container_1 .jp-mute').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-unmute').show();
      $('#jp_container_1 .jp-volume-bar .jp-volume-bar-value').hide();
      yt_player_1.mute();
    });
    $('#jp_container_1 .jp-unmute').on('click',function(){
      $(this).hide();
      $('#jp_container_1 .jp-mute').show();
      $('#jp_container_1 .jp-volume-bar .jp-volume-bar-value').show();
      yt_player_1.unMute();
    });
    $('#jp_container_1 .jp-volume-bar').click(function(e){
      var posX = $(this).offset().left, posWidth = $(this).width();
      posX = (e.pageX-posX)/posWidth;
      $('#jp_container_1 .jp-volume-bar .jp-volume-bar-value').width( (posX*100)+'%' ).show();
      yt_player_1.setVolume(posX*100);
      
      $('#jp_container_1 .jp-unmute').hide();
      $('#jp_container_1 .jp-mute').show();
    });
    $('#jp_container_1 .jp-seek-bar').click(function(e){
      var posX = $(this).offset().left, posWidth = $(this).width();
      posX = (e.pageX-posX)/posWidth;
      $('#jp_container_1 .jp-progress .jp-play-bar').width( (posX*100)+'%' );
      posX = Math.round((posX)*yt_player_1.getDuration());
      yt_player_1.seekTo(posX, true);
    });
    $("#jp_container_1.jp-video-full .jp-gui").on('click',function(e){
      if(e.target != this) return;
      if( $('#jp_container_1 .jp-play').is(':visible') ){
        $('#jp_container_1 .jp-play').click();
      }else{
         $('#jp_container_1 .jp-pause').click();
      }
    });
    
    var fullScreenHoverTime;
    $("#jp_container_1.jp-video-full").on('mouseover',function(){
      $('.jp-gui', this).show();
      clearTimeout(fullScreenHoverTime);
      fullScreenTimeout();
    });
    function fullScreenTimeout(){
      fullScreenHoverTime = setTimeout(function(){
        $('#jp_container_1 .jp-gui').hide();
      },5000);
    }
            
    
  }
