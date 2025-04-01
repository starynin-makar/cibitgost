<div class="border-b border-gray-200">
    <div class="flex justify-between px-4">
        @foreach($tabs as $id => $name)
            <div class="text-center">
                <a href="{{ url('/audit/' . $audit->id . '/process/' . $process . '/' . $id) }}"
                   class="inline-block px-4 py-3 text-base font-medium border-b-2 {{ $tab == $id 
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                   }}">
                    {{ $name }}
                </a>
                <div class="flex justify-center space-x-2 text-xs text-gray-400 -mt-1">
                    <a href="{{ route('organizations.audits.print', [$organization->id, $audit->id, $id]) }}" 
                       class="hover:text-blue-600" target="_blank">P</a>
                    <span>/</span>
                    <a href="{{ route('organizations.audits.list', [$organization->id, $audit->id, $id]) }}" 
                       class="hover:text-blue-600" target="_blank">L</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
  </rewritten_file>
  