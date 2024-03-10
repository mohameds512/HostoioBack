<?php


if (!function_exists('saveRequestFile')) {
    function saveRequestFile($file, $name, $folder)
    {

        $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        Storage::disk('local')->putFileAs($folder, $file, "$name.$extension");

        return "$title.$extension";
    }
}

if (!function_exists('findFiles')) {
    function findFiles($folder, $findFileName)
    {

        $allFilesPaths = Storage::disk('local')->files($folder);
        $filesPaths = [];
        foreach ($allFilesPaths as $filePath) {
            $fileName = pathinfo($filePath, PATHINFO_FILENAME);
            if ($fileName == $findFileName) {
                $filesPaths[] = $filePath;
            }
        }

        return $filesPaths;
    }
}
if (!function_exists('responseFile')) {
    function responseFile($filePath, $fileName)
    {

        $file = Storage::disk('local')->get($filePath);
        $mimeType = Storage::disk('local')->mimeType($filePath);

        $seconds_to_cache = 3600;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";

        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Expires' => "$ts",
            'Pragma' => 'cache',
            'Cache-Control' => "max-age=$seconds_to_cache",
            'Content-Disposition' => "inline; filename='$fileName'",
        ]);

        return false;
    }
}
