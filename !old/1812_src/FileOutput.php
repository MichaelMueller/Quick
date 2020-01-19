<?php

namespace Qck;

/**
 * Description of ProjectDashboard
 *
 * @author muellerm
 */
class FileOutput implements \Qck\Interfaces\Output
{

  function __construct($AbsFilePath)
  {
    $this->AbsFilePath = $AbsFilePath;
  }

  public function getAdditionalHeaders()
  {
    $this->readFileInfo();
    return ['Content-Length: ' . $this->ContentLength];
  }

  public function getCharset()
  {
    $this->readFileInfo();
    return $this->Charset;
  }

  public function getContentType()
  {
    $this->readFileInfo();
    return $this->ContentType;
  }

  public function render()
  {
    $this->readFileInfo();
    readfile($this->AbsFilePath);
  }

  protected function readFileInfo()
  {
    if (!$this->ContentType)
    {
      if (!file_exists($this->AbsFilePath))
        throw new \InvalidArgumentException("File " . $this->AbsFilePath . "not found");
      $finfo               = finfo_open(FILEINFO_MIME_TYPE);
      $this->ContentType   = finfo_file($finfo, $this->AbsFilePath);
      $this->Charset       = $this->startsWith($this->ContentType, "text") ? mb_detect_encoding(file_get_contents($this->AbsFilePath)) : \Qck\Interfaces\Output::CHARSET_BINARY;
      $this->ContentLength = filesize($this->AbsFilePath);
      finfo_close($finfo);
    }
  }

  function startsWith($haystack, $needle)
  {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  private $ContentType;
  private $Charset;
  private $ContentLength;
  private $AbsFilePath;

}
