<?php
// Valida se o título contém apenas letras, números, espaços e símbolos permitidos
function titulo_valido($titulo) {
    return preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\:\.\(\)]+$/u", $titulo);
}
