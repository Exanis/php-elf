<?php

namespace Elf;

class Elf32_Ehdr extends ElfN_Ehdr
{
  public function ei_class()
  {
    return ElfN_Ehdr::ELFCLASS32;
  }
}