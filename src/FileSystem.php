<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class FileSystem implements \Qck\Interfaces\FileSystem
{

  public function clearFolder($FilePath)
  {
    $this->deleteInternal($FilePath, true);
  }

  public function createDir($FilePath, $DeleteIfExists = false)
  {
    if (file_exists($FilePath) && $DeleteIfExists)
      $this->delete($FilePath);

    if (!file_exists($FilePath))
      mkdir($FilePath, 0777, true);
  }

  public function createFile($Name, $Dir = null, $DeleteIfExists = false)
  {
    $Dir      = $Dir ? $Dir : ".";
    $FilePath = $Dir . "/" . $Name;
    if ($DeleteIfExists && file_exists($FilePath))
      unlink($FilePath);
    $this->assureParentDirExists($FilePath);
    touch($FilePath);
    return $FilePath;
  }

  function ls($Dir, $ListType = 0)
  {
    $Files      = $this->getAllFiles($Dir, false);
    $LsContents = [];
    foreach ($Files as $File)
    {
      if ($ListType == 2 && !is_dir($File))
        continue;
      else if ($ListType == 1 && !is_file($File))
        continue;
      $BaseName     = pathinfo($File, PATHINFO_FILENAME);
      $LsContents[] = $BaseName;
    }
    return $LsContents;
  }

  public function createRandomFile($NamePrefix = null, $Ext = null, $Dir = null)
  {
    $Dir = $Dir ? $Dir : sys_get_temp_dir();
    $i   = 0;
    do
    {
      $FilePath = $Dir . "/" . $NamePrefix . ($i > 0 ? strval($i) : "") . ($Ext ? "." . $Ext : "");
      $i++;
    }
    while (file_exists($FilePath));

    touch($FilePath);
    return $FilePath;
  }

  public function delete($FilePath)
  {
    $this->deleteInternal($FilePath, true);
  }

  public function getDirectories($Dir, $Recursive = true)
  {
    $AllFiles = $this->getAllFiles($Dir, $Recursive);
    $Dirs     = [];
    foreach ($AllFiles as $File)
    {
      if (is_dir($File))
        $Dirs[] = $File;
    }
    return $Dirs;
  }

  public function getAllFiles($Dir, $Recursive = true)
  {
    if (!is_dir($Dir))
      return [];
    $Files  = [];
    $TheDir = realpath($Dir);
    $Handle = opendir($TheDir);

    while (false !== ($Entry = readdir($Handle)))
    {
      if ($Entry == "." || $Entry == "..")
        continue;
      $FilePath = $TheDir . DIRECTORY_SEPARATOR . $Entry;
      $Files[]  = $FilePath;
      if (is_dir($FilePath) && $Recursive)
      {
        $Files = array_merge($Files, $this->getAllFiles($FilePath, $Recursive));
      }
    }
    closedir($Handle);
    return $Files;
  }

  public function getContents($FilePath)
  {
    if (!file_exists($FilePath) || filesize($FilePath) == 0)
      return null;

    $f       = fopen($FilePath, "r");
    flock($f, LOCK_SH);
    $content = fread($f, filesize($FilePath));
    flock($f, LOCK_UN);
    fclose($f);
    return $content;
  }

  public function getFiles($Dir, $Extensions, $Recursive = true)
  {
    $FilePaths          = $this->getAllFiles($Dir, $Recursive);
    $CleanedFilePaths   = [];
    foreach ($FilePaths as $FilePath)
      if (in_array(pathinfo($FilePath, PATHINFO_EXTENSION), $Extensions))
        $CleanedFilePaths[] = $FilePath;
    return $CleanedFilePaths;
  }

  public function getFolderSize($Dir)
  {
    $size = 0;
    foreach (glob(rtrim($Dir, '/') . '/*', GLOB_NOSORT) as $each)
      $size += is_file($each) ? filesize($each) : $this->getFolderSize($each);
    return $size;
  }

  public function writeFile($FilePath, $Data)
  {
    $this->assureParentDirExists($FilePath);
    file_put_contents($FilePath, $Data, LOCK_EX);
  }

  protected function deleteInternal($FilePath, $Delete = true)
  {
    if (is_dir($FilePath))
    {
      $objects = scandir($FilePath);
      foreach ($objects as $object)
      {
        if ($object != "." && $object != "..")
        {
          $CurrentFilePath = $FilePath . "/" . $object;
          $this->delete($CurrentFilePath, true);
        }
      }
      if ($Delete)
        rmdir($FilePath);
    }
    else if (is_file($FilePath) && $Delete)
      unlink($FilePath);
  }

  protected function assureParentDirExists($FilePath)
  {
    $dir = dirname($FilePath);
    if (!is_dir($dir))
      $this->createDir($dir);
  }

  public function move($path, $newPath)
  {
    $this->assureParentDirExists($newPath);
    rename($path, $newPath);

    return true;
  }

  function copy($path, $newPath)
  {
    $this->assureParentDirExists($newPath);
    copy($path, $newPath);

    return true;
  }

  public function findMatchingHashedName($Dir, $Recursive = true, $HashValue, $Function = "md5")
  {
    $Files = $this->getAllFiles($Dir, $Recursive);
    foreach ($Files as $File)
    {
      $Hash = call_user_func($Function, $File);
      if ($Hash == $HashValue)
        return $File;
    }
  }

}
