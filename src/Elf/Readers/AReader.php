<?php

namespace Elf\Readers;

abstract class AReader implements IReader
{
  const MAX_SIGNED_SHORT = 0x7FFF;
  const MAX_SIGNED_INT = 0x7FFFFFFF;

  protected $_fd;

  protected function _read($size)
  {
    $datas = fread($this->_fd, $size);
    if (strlen($datas) !== $size)
      throw new \Exception("Invalid data size");
    return $datas;
  }

  public function setFd($fd)
  {
    $this->_fd = $fd;
  }

  public function skip($size)
  {
    $this->_read($size);
  }

  public function seek($position)
  {
    fseek($this->_fd, $position);
  }

  public function close()
  {
    fclose($this->_fd);
  }

  public function read($size)
  {
    return $this->_read($size);
  }

  public function readChar()
  {
    return unpack('c', $this->_read(1))[1];
  }

  public function readUChar()
  {
    return unpack('C', $this->_read(1))[1];
  }

  public function readInt()
  {
    $val = $this->readUInt();
    if ($val > AReader::MAX_SIGNED_INT)
      $val = -(AReader::MAX_SIGNED_INT & $val);
    return $val;
  }

  public function readShort()
  {
    $val = $this->readUShort();
    if ($val > AReader::MAX_SIGNED_SHORT)
      $val = -(AReader::MAX_SIGNED_SHORT & $val);
    return $val;
  }
}