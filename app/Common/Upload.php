<?php

namespace App\Common;

class Upload
{
    /**
     * @param array $file
     * @return null|array
     */
    public static function move(array $files, array $isValid): array
    {
        $data = [];
        foreach ($files as $file) {
            if (!empty($file->getType())) {
                if (!in_array($file->getMime(), $isValid)) {
                    $data['validation'] = 'A Extensão não permitida no arquivo: ' . $file->getFilename();
                    return $data;
                }

                $name = uniqid(self::resolveFilename($file->getFilename())) . '.' . $file->getExtension();
                $data['files'][] = [
                    'file_name' => $name,
                    'file_path' => '/storage/uploads/' . $name,
                    'file_mime' => $file->getMime(),
                    'file_extension' => $file->getExtension(),
                ];

                $file->move(__DIR__ . pathOs(env('CONFIG_PATH_UPLOAD')) . '/' . $name);
            }
        }
        return $data;
    }

    private static function resolveFilename(string $name): string
    {
        $str = preg_replace('/\..*$/', '$1', $name);
        $str = str_replace(' ', '_', $str);
        return $str;
    }
}
