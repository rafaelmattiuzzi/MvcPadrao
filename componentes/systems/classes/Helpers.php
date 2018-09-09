<?php

class Helpers
{
	/**
	*** Declarando variáveis necessárias;
	**/


	/**
	*** Método que valida um CPF;
	**/
	public static function validCpf(string $cpf)
	{
		$data = preg_replace('/[^0-9]/', '', $cpf);
		if(strlen($data) == 11) {
			$digitoA = 0;
			$digitoB = 0;

			for($i=0, $x=10; $i<=8; $i++, $x--) {
				$digitoA += $data[$i] * $x;
			}

			for ($i=0, $x=11; $i<=9; $i++, $x--) {
				if(str_repeat($i, 11) == $data) {
					return false;
				}
				$digitoB += $data[$i] * $x;
			}

			$somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11); //($a % $b) => neste caso ele calcula o resto da divisão de $a por $b, exemplo se $a é 10 e $b é 3 o resto vai ser 1;
	        $somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);

	        if ($somaA != $data[9] || $somaB != $data[10]) {
	            return false;
	        } else {
	            return true;
	        }

		} else {
			return false;
		}
	}

	/**
	*** Método que valida um CNPJ;
	**/
	public static function validCnpj(string $cnpj)
	{
		$data = preg_replace('/[^0-9]/', '', $cnpj);

		// Valida tamanho
		if (strlen($data) == 14) {

			// Valida primeiro dígito verificador
			for ($i=0, $j=5, $soma1=0; $i<12; $i++) {
				$soma1 += $data{$i} * $j;
				$j = ($j == 2) ? 9 : $j-1;
			}

			$resto1 = $soma1 % 11;

			if ($data{12} != ($resto1 < 2 ? 0 : 11 - $resto1)) {
				return false;
			} else {

				// Valida segundo dígito verificador
				for ($i=0, $j=6, $soma2=0; $i<13; $i++) {
					$soma2 += $data{$i} * $j;
					$j = ($j == 2) ? 9 : $j - 1;
				}

				$resto2 = $soma2 % 11;

				if ($data{13} != ($resto2 < 2 ? 0 : 11 - $resto2)) {
					return false;
				} else {
					return true;
				}
			}
		} else {
			return false;
		}
	}

	/**
	*** Método que valida se é um email válido;
	**/
	public static function validEmail(string $email)
	{
		$formato = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

		if(preg_match($formato, $email)) {
			return true;
		} else {
			return false;
		}
	}
}