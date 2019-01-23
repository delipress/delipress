#!/usr/bin/env php
<?php
date_default_timezone_set("Europe/Paris");

class Colors {
		private $foreground_colors = array();
		private $background_colors = array();

		public function __construct() {
			// Set up shell colors
			$this->foreground_colors['black'] = '0;30';
			$this->foreground_colors['dark_gray'] = '1;30';
			$this->foreground_colors['blue'] = '0;34';
			$this->foreground_colors['light_blue'] = '1;34';
			$this->foreground_colors['green'] = '0;32';
			$this->foreground_colors['light_green'] = '1;32';
			$this->foreground_colors['cyan'] = '0;36';
			$this->foreground_colors['light_cyan'] = '1;36';
			$this->foreground_colors['red'] = '0;31';
			$this->foreground_colors['light_red'] = '1;31';
			$this->foreground_colors['purple'] = '0;35';
			$this->foreground_colors['light_purple'] = '1;35';
			$this->foreground_colors['brown'] = '0;33';
			$this->foreground_colors['yellow'] = '1;33';
			$this->foreground_colors['light_gray'] = '0;37';
			$this->foreground_colors['white'] = '1;37';

			$this->background_colors['black'] = '40';
			$this->background_colors['red'] = '41';
			$this->background_colors['green'] = '42';
			$this->background_colors['yellow'] = '43';
			$this->background_colors['blue'] = '44';
			$this->background_colors['magenta'] = '45';
			$this->background_colors['cyan'] = '46';
			$this->background_colors['light_gray'] = '47';
		}

		// Returns colored string
		public function getColoredString($string, $foreground_color = null, $background_color = null) {
			$colored_string = "";

			// Check if given foreground color found
			if (isset($this->foreground_colors[$foreground_color])) {
				$colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
			}
			// Check if given background color found
			if (isset($this->background_colors[$background_color])) {
				$colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
			}

			// Add string and end coloring
			$colored_string .=  $string . "\033[0m";

			return $colored_string . PHP_EOL;
		}

		// Returns all foreground color names
		public function getForegroundColors() {
			return array_keys($this->foreground_colors);
		}

		// Returns all background color names
		public function getBackgroundColors() {
			return array_keys($this->background_colors);
		}
	}

class DirectoryHelper  {

    public static function replaceStringInFiles($dir, $search, $replace, $function_exec = ''){
        $files = array_diff(scandir($dir), array('.','..')); 

        foreach ($files as $file): 
            if(is_dir("$dir/$file")):
                DirectoryHelper::replaceStringInFiles("$dir/$file",$search,$replace,$function_exec);
            else:

                $fileModification = @file_get_contents("$dir/$file");
                if($fileModification !== false):

                    // Callback
                    if(!empty($function_exec)):
                        $newFile =  str_replace($search, call_user_func($function_exec,$replace), $fileModification);
                    else:
                        $newFile =  str_replace($search, $replace, $fileModification);
                    endif;
                    
                    file_put_contents("$dir/$file", $newFile);
                endif;
            endif;
        endforeach;
   
    }

    public static function replaceStringInFile($dirfile, $search, $replace,$function_exec = ''){
        $fileModification = @file_get_contents("$dirfile");
        if($fileModification !== false):

            // Callback
            if(!empty($function_exec)):
                $newFile =  str_replace($search, call_user_func($function_exec,$replace), $fileModification);
            else:
                $newFile =  str_replace($search, $replace, $fileModification);
            endif;
            
            return file_put_contents("$dirfile", $newFile);
        endif;
    }


}

class ExtendZipArchive extends \ZipArchive{

    /**
     * Add a Dir with Files and Subdirs to the archive
     *
     * @version 1.0
     * @since 1.0
     * 
     * @param string $location Real Location
     * @param string $name Name in Archive
     * 
     * 
     **/
 
    public function addDir($location, $name) {
        $this->addEmptyDir($name);
 
        $this->addDirDo($location, $name);
     }
 
    /**
     * Add Files & Dirs to archive.
     *
     * @version 1.0
     * @since 1.0
     * 
     * @param string $location Real Location
     * @param string $name Name in Archive
     * 
     * @access private
     **/
 
    private function addDirDo($location, $name) 
    {
        $name .= '/';
        $location .= '/';
 
        // Read all Files in Dir
        $dir = opendir ($location);
        while ($file = readdir($dir))
        {
            if ($file == '.' || $file == '..') continue;

            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
        }
    }
}

$colors = new Colors();

$dirDeploy = "./delipress";
$zipName   = "./delipress.zip";
$mainFile  = "./delipress.php";

$directoriesCp = array(
    "emails",
    "languages",
    "public",
    "src",
    "templates",
    "vendor",
    "plugins_tinymce",
    "wpgod"
);

$filesCp = array(
    "delipress.php",
    "delipress_helpers.php",
    "delipress_functions.php",
    "php_compatibility.php",
    "readme.txt",
    "contributors.txt"
);

$options = getopt("v:m:");
if(empty($options) && !array_key_exists("v", $options)) {
    echo "Miss version deploy \nUse : -v \n";
    echo $colors->getColoredString("Miss version deploy ", "white", "red");
    echo $colors->getColoredString("Use : -v ", "red");
    exit;
}

echo $colors->getColoredString("Remove node_modules", "green");
exec("rm -rf node_modules");
echo $colors->getColoredString("Composer update - version PHP : " . phpversion(), "green");
exec("composer update");
echo $colors->getColoredString("Yarn install", "green");
exec("yarn");
echo $colors->getColoredString("Brunch build --production", "green");
exec("NODE_ENV=production brunch build --production");
echo $colors->getColoredString("Webpack build", "green");
exec("NODE_ENV=production webpack");

echo $colors->getColoredString("Remove dir useless", "green");
if(file_exists($dirDeploy)){
    exec("rm -rf $dirDeploy");
}

if(file_exists($zipName)){
    exec("rm -rf $dirDeploy");
}

echo $colors->getColoredString("Prepare directory", "green");
exec("mkdir $dirDeploy");

echo $colors->getColoredString("Copy directory", "green");
foreach ($directoriesCp as $key => $dir) {
    exec("cp -R ./$dir $dirDeploy/");
}

echo $colors->getColoredString("Files copy", "green");
foreach ($filesCp as $key => $file) {
    exec("cp ./$file $dirDeploy/");
}


foreach (glob("**/.DS_Store") as $filename) {
    exec("rm -rf $filename");
}

foreach (glob(".DS_Store") as $filename) {
    exec("rm -rf $filename");
}


exec("rm -rf $dirDeploy/public/css/*.map");
exec("rm -rf $dirDeploy/public/js/*.map");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/.gitattributes");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/.gitignore");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/.travis.yml");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/changelog.txt");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/Examples");
exec("rm -rf $dirDeploy/vendor/phpoffice/phpexcel/unitTests");

echo $colors->getColoredString("Change version by : " . $options["v"], "green");
DirectoryHelper::replaceStringInFile("$dirDeploy/$mainFile", "{VERSION}", $options["v"]);
DirectoryHelper::replaceStringInFile("$dirDeploy/$mainFile", 'define("DELIPRESS_LOGS", true);', 'define("DELIPRESS_LOGS", false);');


$zip      = new ExtendZipArchive();
$res      = $zip->open($zipName, \ZipArchive::CREATE);

if($res === TRUE){
    $zip->addDir($dirDeploy, ".");
    $zip->close();    
}

exec("rm -rf delipress");
