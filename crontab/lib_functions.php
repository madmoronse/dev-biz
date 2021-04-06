<?php



// чтение содержимого каталога
function myscandir($dir, $sort=0)
{
    $list = scandir($dir, $sort);
    
    // если директории не существует
    if (!$list) return false;
    
    // удаляем . и .. (в хуй не усрались)
    unset($list[count($list)-1], $list[count($list)-1]);
    return $list;
}


?>