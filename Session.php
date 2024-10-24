<?php
namespace JurateVilima\MvcCore;

class Session
{
    // Инициализация сессии
    public function __construct()
    {
        // Запуск сессии, если она еще не запущена
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->checkTimeout();
    }

    // Установка значения в сессию
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Получение значения из сессии
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Проверка существования значения в сессии
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function isGuest() {
        return isset($_SESSION['user']);
    }

    // Удаление значения из сессии
    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Очистка всей сессии
    public function clear()
    {
        $_SESSION = array();
    }

    // Завершение сессии
    public function destroy()
    {
        // 1. Remove the session cookie if it exists
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    
        // 2. Unset all session variables
        $this->clear(); // Corrected from $_SESSION[] = array();
    
        // 3. Destroy the session
        session_destroy();
    }    

    public function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); // Удалить после получения
            return $message;
        }
        return null;
    }

    public function setTimeout($seconds = 150)
    {
        $this->set('last_activity', time());
        $this->set('session_duration', $seconds);
    }

    public function checkTimeout() {
        $lastActivity = $this->get('last_activity');
        $duration = $this->get('session_duration');

        if ($lastActivity && $duration) {
            if(time() - $lastActivity > $duration) {
                $this->destroy();
                redirect(Application::$app::$BASE_URL . '/');
            } else {
                $this->set('last_activity', time());
            }  
        } 
    }
}
