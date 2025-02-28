<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientSeeder extends Seeder
{
    /**
     * Запуск сидера.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Создаем несколько городов, если таблица городов пуста
        if (DB::table('cities')->count() == 0) {
            foreach (range(1, 10) as $index) {
                DB::table('cities')->insert([
                    'name' => $faker->city,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Получаем все доступные city_id из таблицы cities
        $cityIds = DB::table('cities')->pluck('id')->toArray();

        // Пример создания 50 клиентов
        foreach (range(1, 50) as $index) {
            // Сначала создаем клиента в таблице clients
            $clientId = DB::table('clients')->insertGetId([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'city_id' => $faker->randomElement($cityIds),  // Присваиваем случайный city_id
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Массив только для типов контактов "phone" и "email"
            $contactTypes = ['phone', 'email'];

            // Генерация случайного числа контактов для клиента (от 1 до 5)
            $numContacts = rand(1, 5);

            // Генерация случайных контактов
            $contacts = [];
            foreach (range(1, $numContacts) as $contactIndex) {
                // Случайным образом выбираем тип контакта
                $contactType = $faker->randomElement($contactTypes);

                // Для типов "email" и "phone" генерируем уникальные данные
                $value = $contactType === 'email' ? $faker->unique()->safeEmail : $faker->phoneNumber;

                $contacts[] = [
                    'client_id' => $clientId,
                    'type' => $contactType,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Вставляем все сгенерированные контакты
            DB::table('contacts')->insert($contacts);
        }
    }
}
