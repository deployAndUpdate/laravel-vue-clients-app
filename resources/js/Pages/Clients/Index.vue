<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import axios from 'axios';

const { clients, cities, filters } = usePage().props

// Инициализируем фильтры из инерции
const search = ref(filters.search || '')
const cityFilter = ref(filters.city || '')
const sortField = ref(filters.sort || 'contacts_count')
const sortOrder = ref(filters.order || 'desc')

// Функция для применения фильтров
const applyFilters = () => {
    router.visit('/clients', {
        method: 'get',
        data: {
            search: search.value,
            city: cityFilter.value,
            sort: sortField.value,
            order: sortOrder.value,
        },
    });
}

// Функция для изменения сортировки
const changeSort = (field) => {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'; // Переключение порядка сортировки
    } else {
        sortField.value = field;
        sortOrder.value = 'asc'; // Сортировка по умолчанию по возрастанию
    }
    applyFilters();
}

// Функция для удаления клиента
const deleteClient = (clientId) => {
    if (confirm('Вы уверены, что хотите удалить этого клиента?')) {
        axios.delete(`/clients/${clientId}`).then(() => {
            // Перезагружаем страницу после удаления
            router.get('/clients');
        }).catch((error) => {
            console.error(error);
            alert('Произошла ошибка при удалении клиента.');
        });
    }
};

const createUser = () => {
    axios.get('/clients/create')
        .then(response => {
            // Обрабатываем ответ (например, отрисовываем форму на странице)
            window.location.href = '/clients/create'; // Перенаправление на страницу создания клиента
        })
        .catch(error => {
            console.error("Ошибка при загрузке страницы создания клиента:", error);
        });
};

const editUser = (clientId) => {
    router.get(`/clients/${clientId}/edit`); // Перенаправление на страницу редактирования
};
</script>

<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">Список клиентов</h1>

        <div class="flex gap-4 mb-4">
            <button @click="createUser" class="bg-green-300 text-white px-4 py-2 rounded-lg">Создать клиента</button>
            <input
                v-model="search"
                placeholder="Поиск по имени"
                class="border rounded-lg p-2 w-full"
            />
            <select
                v-model="cityFilter"
                class="border rounded-lg p-2"
            >
                <option value="">Все города</option>
                <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
            </select>
            <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Применить</button>
        </div>

        <table class="min-w-full bg-white border rounded-lg overflow-hidden shadow-lg">
            <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 cursor-pointer" @click="changeSort('city_id')">
                    Город
                    <span v-if="sortField === 'city_id'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="py-2 px-4 cursor-pointer" @click="changeSort('first_name')">
                    Имя
                    <span v-if="sortField === 'first_name'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="py-2 px-4 cursor-pointer" @click="changeSort('contacts_count')">
                    Контакты
                    <span v-if="sortField === 'contacts_count'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="py-2 px-4">Действия</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients.data" :key="client.id" class="border-t hover:bg-gray-100 group">
                <td class="py-2 px-4">{{ client.city.name }}</td>
                <td class="py-2 px-4">{{ client.first_name }} {{ client.last_name }}</td>
                <td class="py-2 px-4 text-center">{{ client.contacts_count }}</td>
                <td class="py-2 px-4">
                    <!-- Кнопка удаления, которая будет видна только при наведении на строку -->
                    <button
                        @click="deleteClient(client.id)"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Удалить
                    </button>

                    <button @click="editUser(client.id)"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Редактировать
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Пагинация -->
        <div class="mt-4">
            <Link v-if="clients.prev_page_url" :href="clients.prev_page_url" class="text-blue-500">Предыдущая</Link>
            <Link v-if="clients.next_page_url" :href="clients.next_page_url" class="text-blue-500 ml-4">Следующая</Link>
        </div>
    </div>
</template>
