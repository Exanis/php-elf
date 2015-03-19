<?php

namespace Elf\Readers;

class ReaderBigEndian extends AReader 
{
  public function readUShort()
  {
    return unpack('n', $this->_read(2))[1];
  }

  public function readUInt()
  {
    return unpack('N', $this->_read(4))[1];
  }
}