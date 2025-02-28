<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\City;
use App\Models\Contact;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Получаем фильтры из запроса, или устанавливаем значения по умолчанию
        $sortField = $request->get('sort', 'contacts_count');
        $sortOrder = $request->get('order', 'desc');

        // Фильтрация клиентов
        $clients = Client::with(['city', 'contacts'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', "%{$request->search}%")
                        ->orWhere('last_name', 'like', "%{$request->search}%");
                });
            })
            ->when($request->city, function ($q) use ($request) {
                $q->where('city_id', $request->city);
            })
            ->withCount('contacts')
            ->orderBy($sortField, $sortOrder)
            ->paginate(25);

        // Отправляем данные в Inertia
        return Inertia::render('Clients/Index', [
            'clients' => $clients, // данные о клиентах с пагинацией
            'cities'  => City::all(), // все города для фильтра
            'filters' => $request->only(['search', 'city', 'sort', 'order']), // сохраняем фильтры
        ]);
    }

    public function create($id = null)
    {
        $client = $id ? Client::findOrFail($id) : new Client();
        $cities = City::all();

        return Inertia::render('Clients/Create', [
            'client' => $client,
            'cities' => $cities,
        ]);
    }

    public function store(Request $request, $id = null)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'contacts' => 'required|array',
            'contacts.*.type' => 'required|in:phone,email',
            'contacts.*.value' => 'required|string',
        ]);

        $client = $id ? Client::findOrFail($id) : new Client();

        $client->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'city_id' => $data['city_id'],
        ]);
        $client->save();

        $client->contacts()->delete();
        foreach ($data['contacts'] as $contact) {
            $client->contacts()->create([
                'type' => $contact['type'],
                'value' => $contact['value'],
            ]);
        }

        return redirect()->route('clients.index');
    }

    public function edit(Request $request, $id)
    {
        $client = Client::with('contacts')->findOrFail($id);
        return Inertia::render('Clients/Create', [
            'client' => $client,
            'cities' => City::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        // Валидация входящих данных
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'contacts' => 'required|array',
            'contacts.*.type' => 'required|in:phone,email',
            'contacts.*.value' => 'required|string',
        ]);

        // Обновляем данные клиента
        $client->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'city_id' => $data['city_id'],
        ]);

        // Удаляем старые контакты
        $client->contacts()->delete();

        // Добавляем новые контакты
        foreach ($data['contacts'] as $contact) {
            $client->contacts()->create([
                'type' => $contact['type'],
                'value' => $contact['value'],
            ]);
        }

        // Перенаправляем на список клиентов
        return Inertia::location(route('clients.index'));
    }


    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->contacts()->delete();
        $client->delete();
        return response()->json(['message' => 'Клиент успешно удален']);
    }
}
