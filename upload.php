<?php

if (!isset($_FILES['myFile'])) {
	die("There is no file to upload");
}
if (!isset($_POST)) {
	die("There is no file data given");
}

$filePath = $_FILES['myFile']['tmp_name'];
$fileSize = filesize($filePath);
$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
$fileType = finfo_file($fileInfo, $filePath);

if ($fileSize === 0) {
	die("The file is empty");
}

if ($fileSize > 3145728) { //Check if file is greater than 3MB
	die("The file is too large");
}

$allowedTypes = [
	'image/png' => 'png',
	'image/jpeg' => 'jpg'
];

if (!in_array($fileType, array_keys($allowedTypes))) {
	die("Files of this type are not allowed");
}

//Name of the file..you can also use your preffered name here
if (isset($_POST['name'])) {
	$fileName = htmlentities($_POST['name']);
}else{
	$fileName = basename($filePath); 
}
$extension = $allowedTypes[$fileType];
$targetDirectory = __DIR__ . "/file-uploads";//Directory where files are to be saved(__DIR__ is the current directory of this file)

$newFilePath = $targetDirectory . "/" . $fileName . "." . $extension;

if (!copy($filePath, $newFilePath)) { //copy the file, returns false incase of an error/failed
	die("Could not move file");
}

unlink($filePath);//Delete the temp file

echo "File Uploaded Successfully";
?>