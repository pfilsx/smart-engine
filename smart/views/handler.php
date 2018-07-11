<?php
/**
 * @var $this \smart\Application
 */

if (!isset($_POST['action'])) {
    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задано действие для выполнения']));
}
try {
    switch ($_POST['action']) {
        case 'get-param':
            {
                if (!isset($_POST['param']) || $_POST['param'] == 'access') {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задан параметр для получения']));
                }
                die(json_encode(['success' => true, 'data' => $this->getParam($_POST['param'])]));
            }
        case 'robots-tab':
            {
                if (!isset($_POST['data'])) {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не заданы параметры для выполнения']));
                }
                $this->setRobots($_POST['data']);
                die(json_encode(['success' => true, 'message' => 'Изменения успешно сохранены']));
            }
        case 'get-css-template':
            {
                if (!isset($_POST['path'])) {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не заданы параметры для выполнения']));
                }
                $path = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'css', str_replace('#', '', $_POST['path'])]);
                if (!is_file($path) || pathinfo($path, PATHINFO_EXTENSION) !== 'css') {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не найден файл для отображения']));
                }
                die(json_encode(['success' => true, 'content' => file_get_contents($path)]));
            }
        case 'template-tab':
            {
                if (!isset($_POST['path']) || !isset($_POST['content'])) {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не заданы параметры для выполнения']));
                }
                $path = implode(DIRECTORY_SEPARATOR, [Loader::$rootDir, 'css', str_replace('#', '', $_POST['path'])]);
                if (!is_file($path) || pathinfo($path, PATHINFO_EXTENSION) !== 'css'){
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не найден файл для редактирования']));
                }
                if (file_put_contents($path, $_POST['content'], LOCK_EX) !== false)
                    die(json_encode(['success' => true, 'message' => 'Изменения успешно сохранены']));
                die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не удалось сохранить файл']));
            }
        case 'main-tab':
        case 'meta-tab':
        case 'og-tab':
        case 'metrics-tab':
        case 'code-tab':
            {
                if (!isset($_POST['data'])) {
                    die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не заданы параметры для выполнения']));
                }
                foreach ($_POST['data'] as $value) {
                    $this->setParam($value['name'], $value['value']);
                }
                $this->saveConfiguration();
                die(json_encode(['success' => true, 'message' => 'Изменения успешно сохранены']));
            }
        default:
            die(json_encode(['success' => false, 'message' => 'Произошла ошибка: не задано действие для выполнения']));
    }
} catch (Exception $ex) {
    die(json_encode(['success' => false, 'message' => 'Произошла ошибка при выполнении запроса']));
}
