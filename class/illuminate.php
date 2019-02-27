<?php
	namespace tools\illuminate;
	class illuminate
	{
		static function render($path, $array)
		{
				echo strtr(file_get_contents($path), $array);
		}
	}