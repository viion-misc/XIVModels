<? function TranslateWord($Word, $Language) { $TranslationArray = array
(
)
; $NewWord = $TranslationArray[$Language][addslashes($Word)]; if (empty($NewWord)) { return $Word; } else { return $NewWord; } } ?>