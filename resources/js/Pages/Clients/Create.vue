<script setup>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

// Получаем данные из страницы
const props = defineProps({
    client: Object, // Если редактируем, сюда передается объект клиента
    cities: Array
});

// Поля формы
const firstName = ref(props.client?.first_name || '');
const lastName = ref(props.client?.last_name || '');
const cityId = ref(props.client?.city_id || '');
const contacts = ref(props.client?.contacts || [{ type: 'phone', value: '' }]);

// Функция для добавления нового контакта
const addContact = () => {
    contacts.value.push({ type: 'phone', value: '' });
};

// Функция для удаления контакта
const removeContact = (index) => {
    contacts.value.splice(index, 1);
};

// Функция отправки формы
const submitForm = () => {
    const url = props.client ? `/clients/${props.client.id}` : '/clients';
    const method = props.client ? 'put' : 'post';

    router[method](url, {
        first_name: firstName.value,
        last_name: lastName.value,
        city_id: cityId.value,
        contacts: contacts.value
    });
};
</script>

<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">{{ client ? 'Редактировать клиента' : 'Создать клиента' }}</h1>

        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Имя</label>
                <input v-model="firstName" class="w-full border rounded-lg p-2" required />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Фамилия</label>
                <input v-model="lastName" class="w-full border rounded-lg p-2" required />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Город</label>
                <select v-model="cityId" class="w-full border rounded-lg p-2">
                    <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                </select>
            </div>

            <!-- Секция контактов -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Контакты</label>
                <div v-for="(contact, index) in contacts" :key="index" class="flex gap-2 items-center mb-2">
                    <select v-model="contact.type" class="border rounded-lg p-2">
                        <option value="phone">Телефон</option>
                        <option value="email">Email</option>
                    </select>
                    <input v-model="contact.value" class="border rounded-lg p-2 w-full" required />
                    <button @click.prevent="removeContact(index)" class="bg-red-500 text-white px-2 py-1 rounded-lg">
                        ❌
                    </button>
                </div>
                <button @click.prevent="addContact" class="bg-green-500 text-white px-4 py-2 rounded-lg mt-2">
                    ➕ Добавить контакт
                </button>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                {{ client ? 'Сохранить изменения' : 'Создать клиента' }}
            </button>
        </form>
    </div>
</template>
