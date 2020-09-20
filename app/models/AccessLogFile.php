<?php

namespace App\Models;

use App\Kernel\Model;

class AccessLogFile extends Model
{	

	private $fileService = null;
    
    /**
     * Инициализации модели.
     * @param App\Helpers\File $fileService Объект для работы с файлами
     */
    public function __construct(\App\Helpers\FileService $fileService){
    	$this->fileService = $fileService;
    }

    /**
     * Открытваем файл $fileName
     * @param  string $fileName Полное имя файла
     * @return bool             Результат откртыия true - успешно, false - ошибка
     */
    public function openFile(string $fileName): bool{
    	$openingStatus = $this->fileService->openFile($fileName, "r");
    	return (bool)$openingStatus;
	}

	
	/**
     * Чтение строки файла. Указатель передвигатеся на следующую строку.
     * @return string 	Тукущая строка файла
     */
    public function readLine(): ?string{
    	$logLine = $this->fileService->readLine();
    	return $logLine;
	}

	/**
	 * Закрывает соединение с файлом
	 */
	public function __destruct(){
    	$this->fileService = $this->fileService->closeFile();
    }
}