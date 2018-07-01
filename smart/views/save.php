<?php
/**
 * @var $this \smart\Application
 */

if (!isset($_POST['action'])) {
    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задано действие для выполнения']));
}
switch ($_POST['action']){
    case 'main-tab':
    {
        if (!isset($_POST['data'])){
            die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не заданы параметры для выполнения']));
        }
        foreach ($_POST['data'] as $value){
            $this->setParam($value['name'], $value['value']);
        }
        $this->saveConfiguration();
        die(json_encode(['success' => true, 'message' => 'Изменения успешно сохранены']));
    }
    default:
        die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задано действие для выполнения']));
}