<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Doctor;

class AiDoctorController extends Controller
{
    public function ask(Request $request)
    {
        $question = $request->input('question');

        if (!$question) {
            return response()->json([
                'error' => 'Question is required'
            ], 400);
        }

        // 1️⃣ جلب بيانات الأطباء (الاسم + التخصص + الخدمات فقط)
        $doctors = Doctor::with('specialty')
            ->get()
            ->map(function ($doctor) {
                return [
                    'name' => $doctor->name,
                    'specialty' => $doctor->specialty->name ?? 'غير محدد',
                    'services' => $doctor->services
                ];
            })
            ->toArray();

        $doctorsJson = json_encode(
            $doctors,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        // 2️⃣ بناء الـ Prompt
        $prompt = "
أنت مساعد ذكي.
مهمتك الوحيدة هي الإجابة باستخدام بيانات الأطباء التالية فقط.

بيانات الأطباء:
$doctorsJson

القواعد:
- أجب فقط عن (اسم الطبيب، تخصصه، خدماته)
- لا تضف أي معلومات خارج هذه البيانات
- إذا لم يكن الطبيب موجودًا قل: \"هذا الطبيب غير موجود\"
- إذا كان السؤال خارج هذه المعلومات قل: \"لا أستطيع الإجابة على هذا السؤال\"

سؤال المستخدم:
$question
";

        // 3️⃣ الاتصال بـ Gemini API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                "contents" => [
                    [
                        "role" => "user",
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]
        );

        // 4️⃣ استخراج الرد
        $answer = $response->json(
            'candidates.0.content.parts.0.text'
        ) ?? 'لم أستطع توليد إجابة';

        // 5️⃣ إرجاع النتيجة
        return response()->json([
            'question' => $question,
            'answer' => trim($answer)
        ]);
    }
}
