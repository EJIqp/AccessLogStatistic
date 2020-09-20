<?php

namespace App\Helpers;

class FileService
{    
    /**
     * Открывает файл
     * @param  string $filename Полное имя файла
     * @param  string $mode     Тип доступа
     * @return resource           Указатель на файл в случае успешной работы, или FALSE
     */
    public  function openFile(string $filename, string $mode): resource{   
        $fileDescriptor =  fopen($filename,$mode);
        if($fileDescriptor === false){
            return null;
        }

        return $fileDescriptor;
    }

    /**
     * Чтение строки из файла
     * @param  resource $fileDescriptor Указатель на файл
     * @return string                   Прочитанная строка в случае успеха
     */
    public  function readLine(resource $fileDescriptor): ?string{   
        
        if (feof($fileDescriptor)){
            return null;
        }


        $char = '';
        $line = '';

        while (!feof($fileDescriptor) && $char != "\n") {
            $char = fread($fileDescriptor, 1);
            $line .= $char;
        }
        return rtrim($line, "\n");
    }

    /**
     * Закрытие файла
     * @param  resource $fileDescriptor Указатель на файл
     * @return bool                     Результат выполнения
     */
    public  function closeFile(resource $fileDescriptor): bool{   
        $result = fclose ( $fileDescriptor );
        return (bool)$result;
    }
}