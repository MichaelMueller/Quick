<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class File implements \Qck\Interfaces\File
{

  function __construct($DirPath, $FileBaseName)
  {
    $this->DirPath      = $DirPath;
    $this->FileBaseName = $FileBaseName;
  }

  public function getBasename()
  {
    return $this->FileBaseName;
  }

  public function getExtension()
  {
    return pathinfo($this->getPath(), PATHINFO_EXTENSION);
  }

  public function getFileName()
  {
    return pathinfo($this->getPath(), PATHINFO_FILENAME);
  }

  public function getPath()
  {
    return $this->join($this->DirPath, $this->FileBaseName);
  }

  public function isDir()
  {
    return is_dir($this->getPath());
  }

  public function readContents()
  {
    $FilePath = $this->getPath();
    if (!file_exists($FilePath) || filesize($FilePath) == 0)
      return null;

    $f       = fopen($FilePath, "r");
    flock($f, LOCK_SH);
    $content = fread($f, filesize($FilePath));
    flock($f, LOCK_UN);
    fclose($f);
    return $content;
  }

  public function writeContents($Data)
  {
    file_put_contents($this->getPath(), $Data, LOCK_EX);
  }

  public function getParentDir()
  {
    return dirname($this->getPath());
  }

  public function join($BasePath, $FileName)
  {
    $Path = $BasePath . DIRECTORY_SEPARATOR . $FileName;

    return strpos($Path, "\\") !== false ? str_replace("/", "\\", $Path) : str_replace("\\", "/", $Path);
  }

  public function getSize()
  {
    return filesize($this->getPath());
  }

  protected $DirPath;
  protected $FileBaseName;

}
