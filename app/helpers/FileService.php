<?php

namespace App\Helpers;

class FileService
{   
    /**
     * Указатель на открытый файл
     * @var resource
     */
    private $fileDescriptor = null;
    private $fileIsOpen = false;

    /**
     * Проверяет существование указанного файла или каталога
     * @param  string $filename Полное имя файла
     * @return bool             Возвращает true, если файл или каталог существует, иначе false
     */
    public function fileExists(string $filename): bool{   
        return (bool)file_exists($filename);
    }

    /**
     * Открывает новый файл
     * @param  string $filename Полное имя файла
     * @param  string $mode     Тип доступа
     * @return void
     */
    public function openFile(string $filename, string $mode): void{

        if($this->fileIsOpen){
            $this->closeFile();
        }

        $this->fileDescriptor = fopen($filename,$mode);
        $this->fileIsOpen = $this->fileDescriptor === false ? false : true;
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
     * @return void
     */
    public function closeFile(): void{
        if (is_resource($this->fileDescriptor)){
            fclose($this->fileDescriptor);
        }
        $this->fileIsOpen = false;
    }

    /**
     * Проверят достигнут ли конец файла
     * @return bool     Результат выполнения true - если конец или нет файла вовсе
     */
    public function isReachedEOF(): bool{

        $result = is_resource($this->fileDescriptor) ? feof($this->fileDescriptor) : true;

        return (bool)$result;
    }

    /**
     * Открыт ли файл
     * @return bool     true - если открыт, иначе false
     */
    public function isFileOpen(): bool{
        return (bool)$this->fileIsOpen;
    }
}