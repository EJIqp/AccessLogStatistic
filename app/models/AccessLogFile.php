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
    public function fileExists(string $fileName): bool{
        $fileExists = $this->logFile->fileExists($fileName);
        return (bool)$fileExists;
    }

    /**
     * Открытваем файл $fileName
     * @param  string $fileName Полное имя файла
     * @return void
     */
    public function openFile(string $fileName): void{
        $this->logFile->openFile($fileName, "r");
    }

    /**
     * Проверка, открыт ли файл
     * @return bool Если true - открыт, иначе нет.
     */
    public function isFileOpen(): bool{
        return (bool)$this->logFile->isFileOpen();
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
            
            $logLineParameters = RegExpService::splitByPattern($pattern, $this->currentLogLine);

            if( count($logLineParameters) === 12 ){
                $result['ip']       = $logLineParameters[1];
                $result['identity'] = $logLineParameters[2];
                $result['user']     = $logLineParameters[3];
                $result['date']     = $logLineParameters[4];
                $result['method']   = $logLineParameters[5];
                $result['path']     = $logLineParameters[6];
                $result['protocol'] = $logLineParameters[7];
                $result['status']   = $logLineParameters[8];
                $result['bytes']    = $logLineParameters[9];
                $result['referer']  = $logLineParameters[10];
                $result['agent']    = $logLineParameters[11];
            }
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