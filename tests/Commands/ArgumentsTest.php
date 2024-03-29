<?php

namespace Commands;

use Blog\Defox\Blog\Commands\Arguments;
use Blog\Defox\Blog\Exceptions\ArgumentsException;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{
    /**
     * @throws ArgumentsException
     */
    public function testItReturnsArgumentsValueByName(): void
    {
        // Подготовка
        $arguments = new Arguments(['some_key' => 'some_value']);

        // Действие
        $value = $arguments->get('some_key');

        // Проверка
        $this->assertEquals('some_value', $value);
    }

    public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
    {
        // Подготавливаем объект с пустым набором данныъ
        $arguments = new Arguments([]);

        // Описываем тип ожидаемого исключения
        $this->expectException(ArgumentsException::class);

        // и его сообщение
        $this->expectExceptionMessage("No such argument: some_key");

        // Выполняем действие, приводящее к выбрасыванию исключения
        $arguments->get('some_key');
    }

    /**
     * @dataProvider argumentsProvider
     * @throws ArgumentsException
     */
    public function testItConvertsArgumentsToStrings($inputValue, $expectedValue): void
    {
        $arguments = new Arguments(['some_key' => $inputValue]);
        $value = $arguments->get('some_key');
        $this->assertEquals($expectedValue, $value);
    }

    public static function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'], // Тестовый набор
            // Первое значение будет передано
            // в тест первым аргументом,
            // второе значение будет передано
            // в тест вторым аргументом
            [' some_string', 'some_string'], // Тестовый набор №2
            [' some_string ', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

}