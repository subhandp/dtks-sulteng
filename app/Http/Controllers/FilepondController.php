<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilepondController extends Controller
{

    private function beautify_filename($filename) {
        // reduce consecutive characters
        $filename = preg_replace(array(
            // "file   name.zip" becomes "file-name.zip"
            '/ +/',
            // "file___name.zip" becomes "file-name.zip"
            '/_+/',
            // "file---name.zip" becomes "file-name.zip"
            '/-+/'
        ), '-', $filename);
        $filename = preg_replace(array(
            // "file--.--.-.--name.zip" becomes "file.name.zip"
            '/-*\.-*/',
            // "file...name..zip" becomes "file.name.zip"
            '/\.{2,}/'
        ), '.', $filename);
        // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
        $filename = mb_strtolower($filename, mb_detect_encoding($filename));
        // ".file-name.-" becomes "file-name"
        $filename = trim($filename, '.-');
        return $filename;
    }

    private function filter_filename($filename, $beautify=true) {
        // sanitize filename
        $filename = preg_replace(
            '~
            [<>:"/\\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
            [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
            [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
            [#\[\]@!$&\'()+,;=]|     # URI reserved https://www.rfc-editor.org/rfc/rfc3986#section-2.2
            [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
            ~x',
            '-', $filename);
        // avoids ".", ".." or ".hiddenFiles"
        $filename = ltrim($filename, '.-');
        // optional beautification
        if ($beautify) $filename = $this->beautify_filename($filename);
        // maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = mb_strcut(pathinfo($filename, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($filename)) . ($ext ? '.' . $ext : '');
        return $filename;
    }



    private function rrmdir($dir)
    {
        if (is_dir($dir))
        {
        $objects = scandir($dir);

        foreach ($objects as $object)
        {
            if ($object != '.' && $object != '..')
            {
                if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
                else {unlink($dir.'/'.$object);}
            }
        }

        reset($objects);
        rmdir($dir);
        }
    }


    public function revert(Request $request){
        $path = json_decode(request()->getContent());
        $this->rrmdir(storage_path('app/'.$path->filepath));
        return $path->filepath;
    }

     public function process (Request $request)
     {
        try {
            //  return $request->post();
            if($request->hasFile('upload')){
                $files = $request->file('upload');
                $folder = uniqid().'-'.now()->timestamp;
                foreach($files as $file){
                    $filename = $this->filter_filename($file->getClientOriginalName());
                    $allowed = array('csv');
                    $ext = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    $mimetypes = $file->getClientMimeType();
                    if (!in_array($ext, $allowed)) {
                        $filename = $filename.'.csv';
                    }
                    $file->storeAs('tmp/'.$folder,$filename);
                    // return 'tes';
                }

                $data = [
                    'filename' => $filename,
                    'extension' => $ext,
                    'disk' => 'public',
                    'filepath' => 'tmp/'.$folder,
                    'mimetypes' => $mimetypes,
                    'size' => $size
                ];
                return json_encode($data);
            }
                return json_encode(['status' => 'file tdk ada']);
         } catch (\Exception $e) {
            return response($e->getMessage(), 500);
         }

    
     }

    
}
