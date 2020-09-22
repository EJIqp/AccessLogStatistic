<?php

namespace App\Helpers;

class FileService
{   
    /**
     * Указатель на открытый файл
     * @var resource
     */
    private $fileDescriptor = null;

    /**
     * Проверяет существование указанного файла или каталога
     * @param  string $filename Полное имя файла
     * @return bool             Возвращает true, если файл или каталог существует, иначе false
     */
    public function fileExists(string $filename): bool{   
        return (bool)file_exists($filename);
    }

    /**
     * Открывает файл
     * @param  string $filename Полное имя файла
     * @param  string $mode     Тип доступа
     * @return bool             Успешность открытия файла
     */
    public function openFile(string $filename, string $mode): bool{   
        
        $this->fileDescriptor = fopen($filename,$mode);
        
        if($this->fileDescriptor === false){
            return false;
        }

        return true;
    }


    /**
     * Чтение строки из файла
     * @return string       Прочитанная строка в случае успеха
     */
    public function readLine(): ?string{   
        
        if (feof($this->fileDescriptor)){
            return null;
        }

        $char = '';
        $line = '';

        while (!feof($this->fileDescriptor) && $char != "\n") {
            $char = fread($this->fileDescriptor, 1);
            $line .= $char;
        }

        return rtrim($line, "\n");
    }


    /**
     * Закрытие файла
     * @return bool     Результат выполнения
     */
    public function closeFile(): bool{

        $result = is_resource($this->fileDescriptor) ? fclose($this->fileDescriptor) : true;

        return (bool)$result;
    }

    /**
     * Проверят достигнут ли конец файла
     * @return bool     Результат выполнения true - если конец или нет файла вовсе
     */
    public function isReachedEOF(): bool{

        $result = is_resource($this->fileDescriptor) ? feof($this->fileDescriptor) : true;

        return (bool)$result;
    }
}