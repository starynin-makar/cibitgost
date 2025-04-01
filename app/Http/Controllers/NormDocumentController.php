<?php

namespace App\Http\Controllers;

use App\Models\NormDocument;
use App\Models\Norm;
use App\Models\Audit;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NormDocumentController extends Controller
{
    public function store(Request $request, $organization, $audit, Norm $norm)
    {
        $auditModel = Audit::findOrFail($audit);

        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'comment' => 'nullable|string',
            'responsible_person' => 'required|string',
            'version' => 'required|string',
            'employee_name' => 'nullable|string',
        ]);

        try {
            $path = $request->file('file')->store('documents');

            $document = NormDocument::create([
                'norm_id' => $norm->id,
                'audit_id' => $auditModel->id,
                'file_path' => $path,
                'comment' => $request->comment,
                'responsible_person' => $request->responsible_person,
                'version' => $request->version,
                'employee_name' => $request->employee_name ?? null,
                'uploaded_by' => auth()->id()
            ]);

            return redirect()->route('organizations.audits.conduct', [
                'organization' => $organization,
                'audit' => $auditModel->id
            ])->with('success', 'Документ успешно добавлен');

        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::delete($path);
            }
            
            return back()->with('error', 'Ошибка при сохранении документа: ' . $e->getMessage());
        }
    }

    public function update(Request $request, NormDocument $document)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string',
            'source_type' => 'required|string',
            'results' => 'required|string',
            'document' => 'nullable|file|mimes:txt,log,xls,xlsx,doc,docx,rtf,pdf,jpg,jpeg,png,bmp,gif,zip,rar,webp,vsdx,vsdm,vsd',
            'comment' => 'nullable|string'
        ]);

        $document->fill($validated);

        if ($request->hasFile('document')) {
            // Удаляем старый файл
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }
            
            $file = $request->file('document');
            $path = $file->store('norm-documents');
            $document->file_path = $path;
            $document->file_name = $file->getClientOriginalName();
        }

        $document->save();

        return response()->json([
            'success' => true,
            'document' => $document
        ]);
    }

    public function destroy(NormDocument $document)
    {
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }
        
        $document->delete();

        return response()->json(['success' => true]);
    }

    public function download(NormDocument $document)
    {
        if (!$document->file_path) {
            abort(404);
        }

        return Storage::download($document->file_path, $document->file_name);
    }

    public function create(Organization $organization, Audit $audit, Norm $norm)
    {
        return view('norm-documents.create', compact('organization', 'audit', 'norm'));
    }
    
    /**
     * Создание тестового документа
     */
    public function createTestDocument(Audit $audit)
    {
        try {
            // Создаем тестовый файл
            $content = "Тестовый файл для аудита {$audit->id} - " . date('Y-m-d H:i:s');
            $filePath = 'documents/test_' . time() . '.txt';
            $fullPath = storage_path('app/' . $filePath);
            
            // Создаем директорию, если не существует
            $dir = dirname($fullPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Записываем содержимое в файл
            file_put_contents($fullPath, $content);
            
            // Находим первую норму
            $norm = Norm::first();
            if (!$norm) {
                return back()->with('error', 'В системе нет норм');
            }
            
            // Создаем запись в базе данных
            $document = new NormDocument();
            $document->audit_id = $audit->id;
            $document->norm_id = $norm->id;
            $document->file_path = $filePath;
            $document->responsible_person = 'Тестовый ответственный';
            $document->version = '1.0';
            $document->uploaded_by = auth()->id() ?: 1;
            $document->save();
            
            return back()->with('success', 'Тестовый документ успешно создан (ID: ' . $document->id . ')');
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании тестового документа: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при создании тестового документа: ' . $e->getMessage());
        }
    }
} 