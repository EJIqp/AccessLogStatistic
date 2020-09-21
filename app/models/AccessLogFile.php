<?php

namespace App\Models;

use App\Kernel\Model;
use App\Helpers\RegExpService;

class AccessLogFile extends Model
{

    private $logFile = null;
    private $currentLogLine = '';
    
    /**
     * Инициализации модели.
     * @param App\Helpers\File $fileService Объект для работы с файлами
     */
    public function __construct(\App\Helpers\FileService $fileService){
        $this->logFile = $fileService;
    }

    /**
     * Открытваем файл $fileName
     * @param  string $fileName Полное имя файла
     * @return bool             Результат откртыия true - успешно, false - ошибка
     */
    public function openFile(string $fileName): bool{
        $openingStatus = $this->logFile->openFile($fileName, "r");
        return (bool)$openingStatus;
    }

    /**
     * Доступно ли чтение файла с логами
     * @return boolean  Результат проверки true - доступен, false - достигнут конец
     */
    public function canBeRead(): bool{
        return $this->logFile->isReachedEOF() ? false : true;
    }

    /**
     * Чтение строки файла. Указатель передвигатеся на следующую строку.
     * @return AccessLogFile     Тукущее состояние объекта AccessLogFile
     */
    public function readNextLine(): AccessLogFile{
        $this->currentLogLine = $this->logFile->readLine();
        return $this;
    }

    /**
     * Getter текущей записи лога в текстовом формате
     * @return string     Тукущая запись файла логов
     */
    public function logInText(): ?string{
        return $this->currentLogLine !== '' ? $this->currentLogLine : null;
    }

    /**
     * Getter текущей записи лога в виде ассоциативного массива
     * @return array     Тукущая запись файла логов
     */
    public function logInArray(): array{
        $result = [];

        if($this->currentLogLine !== ''){

            $pattern = '/(\S+) (\S+) (\S+) \[(.+?)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) \"(.*?)\" \"(.*?)\"/';
            
            $matches = RegExpService::splitByPattern($pattern, $this->currentLogLine);

	        $result['ip']       = $matches[1];
	        $result['identity'] = $matches[2];
	        $result['user']     = $matches[3];
	        $result['date']     = $matches[4];
	        $result['method']   = $matches[5];
	        $result['path']     = $matches[6];
	        $result['protocol'] = $matches[7];
	        $result['status']   = $matches[8];
	        $result['bytes']    = $matches[9];
	        $result['referer']  = $matches[10];
	        $result['agent']    = $matches[11];
        }


        return $result;
    }



    /**
     * Закрывает соединение с файлом
     */
    public function __destruct(){
        $this->logFile = $this->logFile->closeFile();
    }
}