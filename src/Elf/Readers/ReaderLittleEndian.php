<?php

namespace Elf\Readers;

class ReaderLittleEndian extends AReader 
{
  public function readUShort()
  {
    return unpack('v', $this->_read(2))[1];
  }

  public function readUInt()
  {
    return unpack('V', $this->_read(4))[1];
  }

  public function readULongLong()
  {
    return unpack('P', $this->_read(8))[1];
  }
}