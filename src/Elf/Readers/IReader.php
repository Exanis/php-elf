<?php

namespace Elf\Readers;

interface IReader
{
  public function setFd($fd);
  public function readChar();
  public function readUChar();
  public function readShort();
  public function readUShort();
  public function readInt();
  public function readUInt();
  public function read($size);
  public function skip($size);
  public function seek($position);
  public function close();
}