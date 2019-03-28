<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Image_convert{


	protected function compress($source_old, $destination, $quality) {
	
		$source = basename($source_old);
	    $info = getimagesize($source_old);

	    if ($info['mime'] == 'image/jpeg') 
	        $image = imagecreatefromjpeg($source_old);

	    elseif ($info['mime'] == 'image/gif') 
	        $image = imagecreatefromgif($source_old);

	    elseif ($info['mime'] == 'image/png') 
	        $image = imagecreatefrompng($source_old);

	    $process = imagejpeg($image, $destination, $quality);
		if($process){
	    	return true;
	    }else{
	    	return false;
	    }
	}

	protected function generate_random_string($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
		    $randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	protected function get_file_arrays(&$file_post) {

	    $file_ary = array();
	    $file_count = count($file_post['name']); //count the files
	    $file_keys = array_keys($file_post); //get the array keys

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}

	public function do_image_convert($image,$quality,$destination){
		if(empty($image['tmp_name'])) {
			echo $error =  '<i class="text-danger">Please select an image or multiple</i>';
		}elseif($quality ===''){
			echo $error = '<i class="text-danger">Please enter the image quality you want</i>';
		}else{
			$allowed =  array('png','jpg','jpeg','gif','JPG'); //allowed extentions
			$file_ary = $this->get_file_arrays($image);
			foreach($file_ary as $source_old){
				$ext = pathinfo($source_old['name'], PATHINFO_EXTENSION);
				if(in_array($ext,$allowed)){ //if the extention is allowed, the script will do the conversion else it will skip it. You else the commented  else statement below to notify the user of wrong extention but remember this will break the loop.
						
						$d = $this->compress($source_old['tmp_name'], $destination.$this->generate_random_string(4).'.'.$ext, $quality);
					
				}
				/*else{
					echo $error = '<i class="text-danger">Only '.json_encode($allowed).' extentions are allowed</i>'; 
				}*/
			}
			if($d){
						echo $success = '<i class="text-success">Conversion was successful. Check destination folder</i>'; //tell us process is successful
			}
		}

		
	}
}


if(isset($_POST['do_conv'])){ //lets listen and get a post do_conv when it happens
$img_conv = new Image_convert; //get the class
$newfile_name = 'new_file'; //specify new file name; Theere is a ramdom method above that adds extra file name incase of duplicate names
$destination = 'images_converted/'.$newfile_name; // the destination folder location and new file name
$img_conv->do_image_convert($_FILES['img'],$_POST['quality'],$destination);	//this send the action to the class for processing
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Convert Images</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


</head>
<body>


<section class="section">


<div class="header col-md-6">
	
	<form action="" method="POST" class="form-herizontal" enctype="multipart/form-data">
		
		<div class="col-md-12 form-group">
			
		<input type="file" class="form-control" multiple="" name="img[]">
		</div>
		<div class="col-md-12 form-group">
		<input type="text" class="form-control" name="quality">
		</div>
		<div class="col-md-12 form-group">
		<button type="submit" class="form-control btn btn-primary" name="do_conv">Convert Image</button>
	</div>

	</form>
</div>
</section>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">$('input[type=file]').change(function () {
    console.log(this.files[0].mozFullPath);
});</script>
</body>
</html>