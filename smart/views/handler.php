<?php
/**
 * @var $this \smart\Application
 */

if (!isset($_POST['action'])) {
    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задано действие для выполнения']));
}
try {
    switch ($_POST['action']){
        case 'get-param':
        {
            if (!isset($_POST['param']) || $_POST['param'] == 'access'){
                die(json_encode(['success'=>false, 'message' => 'Произошла ошибка: не задан параметр для получения']));
            }
            die(json_encode(['success' => true, 'data' => $this->getParam($_POST['param'])]));
        }
        case 'main-tab':
        case 'meta-tab':
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
} catch (Exception $ex){
    die(json_encode(['success' => false, 'message' => 'Произошла ошибка при выполнении запроса']));
}
