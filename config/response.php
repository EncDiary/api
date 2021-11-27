<?php

return [
  'userAlreadyExist' => response(['message' => 'Пользователь с подобным логином уже существует'], 400),
  'oneTimeKeyExpired' => response(['message' => 'Время запроса истекло'], 400),
  'wrongLoginData' => response(['message' => 'Неверные входные данные'], 401),
  'noteNotFound' => response(['message' => 'Заметка не найдена'], 404),
  'unauthorized' => response(['message' => 'Неавторизовано'], 401),
  'publicKeyIsInvalid' => response(['message' => 'Публичный ключ не валиден'], 400),
  'thisIsDemo' => response(['message' => 'В демоверсии нельзя произвести это действие'], 400)
];