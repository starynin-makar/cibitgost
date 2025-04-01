<div class="mb-4">
    <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="pending" {{ $audit->status === 'pending' ? 'selected' : '' }}>В процессе</option>
        <option value="completed" {{ $audit->status === 'completed' ? 'selected' : '' }}>Завершен</option>
    </select>
</div> 