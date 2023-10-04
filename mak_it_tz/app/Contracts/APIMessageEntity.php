<?php

namespace App\Contracts;

interface APIMessageEntity
{
    /**
     * @var string
     */
    public const DEFAULT_ERROR_MESSAGE = 'Что-то пошло не так!';

    /**
     * @var string
     */
    public const READY = 'Готово';

    /**
     * @var string
     */
    public const WAIT = 'wait';

    /**
     * @var string
     */
    public const UNAUTHORIZED = 'Вы не авторизованы';

    /**
     * @var string
     */
    public const AUTHORIZED = 'Вы вошли';

    /**
     * @var string
     */
    public const UIN_NOT_FOUND = 'ИИН/БИН не найдены';

    /**
     * @var string
     */
    public const INVALID_CHECK_REQUEST = 'Неверные запросы проверки';

    /**
     * @var string
     */
    public const LIMIT_EXCEEDED = 'Превышено количество запросов';

    /**
     * @var string
     */
    public const MASS_REQUEST_LIMIT_EXCEEDED = 'Недостаточное количество запросов токенов для массовых запросов';

    /**
     * @var string
     */
    public const MODULES_NOT_CONNECTED = 'Модули не подключены';

    /**
     * @var string
     */
    public const SMS_REJECTED = 'Отклонено';

    /**
     * @var string
     */
    public const SMS_TIMEOUT = 'Время ожидания превышено';

    /**
     * @var string
     */
    public const TOO_MANY_REQUESTS = 'Слишком много запросов';

    /**
     * @var string
     */
    public const URL_NOT_FOUND = 'Маршрут не найден';

    /**
     * @var string
     */
    public const UNAUTHORIZED_RUS = 'Неавторизованная система';

    /**
     * @var string
     */
    public const CREATED = 'Создано';

    /**
     * @var string
     */
    public const DELETED = 'Удалено';

    /**
     * @var string
     */
    public const UPDATED = 'Обновлено';

    /**
     * @var string
     */
    public const METHOD_NOT_ALLOWED = 'Метод не разврешен';

    /**
     * @var string
     */
    public const NOT_FOUND = 'Не найдено';

    /**
     * @var string
     */
    public const INVALID_REFRESH_TOKEN = 'Недействительный токен';

    /**
     * @var string
     */
    public const INVALID_CREDENTIALS = 'Недействительные данные';
}
