<?php



// ������ ����������� ��������
function myscandir($dir, $sort=0)
{
    $list = scandir($dir, $sort);
    
    // ���� ���������� �� ����������
    if (!$list) return false;
    
    // ������� . � .. (� ��� �� ��������)
    unset($list[count($list)-1], $list[count($list)-1]);
    return $list;
}


?>