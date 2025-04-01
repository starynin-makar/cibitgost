<div class="modal fade" id="auditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-xl font-semibold" id="auditModalLabel">Создать аудит</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="auditForm" method="POST" action="{{ route('organizations.audits.store', $organization) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Название аудита *
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Описание
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                  rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                            Дата начала *
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                               value="{{ old('start_date', date('Y-m-d')) }}"
                               required>
                        @error('start_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                            Дата окончания
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Статус
                        </label>
                        <select name="status" 
                                id="status" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="planned">Запланирован</option>
                            <option value="in_progress">В процессе</option>
                            <option value="completed">Завершен</option>
                            <option value="cancelled">Отменен</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" 
                            data-dismiss="modal">Отмена</button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 