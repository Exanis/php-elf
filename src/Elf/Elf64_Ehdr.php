<?php

namespace Elf;

class Elf64_Ehdr extends ElfN_Ehdr
{
  public function ei_class()
  {
    return ElfN_Ehdr::ELFCLASS64;
  }
}