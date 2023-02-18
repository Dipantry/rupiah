<?php /** @noinspection SpellCheckingInspection */

namespace Dipantry\Rupiah\Service;

class NumberService
{
    public function formatText(int $value): string
    {
        if ($value < 0) {
            $result = "minus " . trim($this->indoNaming($value));
        } else {
            $result = trim($this->indoNaming($value));
        }
        return ucwords($result) . " Rupiah";
    }

    private function indoNaming(int $value): string
    {
        $value = abs($value);
        $word = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        return match ($value > 0) {
            $value < 12 => " " . $word[$value],
            $value < 20 => $this->indoNaming($value - 10) . " belas",
            $value < 100 => $this->indoNaming($value / 10) . " puluh" . $this->indoNaming(fmod($value, 10)),
            $value < 200 => " seratus" . $this->indoNaming($value - 100),
            $value < 1000 => $this->indoNaming($value / 100) . " ratus" . $this->indoNaming(fmod($value, 100)),
            $value < 2000 => " seribu" . $this->indoNaming($value - 1000),
            $value < 1000000 => $this->indoNaming($value / 1000) . " ribu" . $this->indoNaming(fmod($value, 1000)),
            $value < 1000000000 => $this->indoNaming($value / 1000000) . " juta" . $this->indoNaming(fmod($value, 1000000)),
            $value < 1000000000000 => $this->indoNaming($value / 1000000000) . " miliar" . $this->indoNaming(fmod($value, 1000000000)),
            $value < 1000000000000000 =>  $this->indoNaming($value / 1000000000000) . " triliun" . $this->indoNaming(fmod($value, 1000000000000)),
            default => ""
        };
    }
}