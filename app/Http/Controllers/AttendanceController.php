<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    //
    public function receive(Request $request)
    {
        $data = $request->input('data');

        // Validasi payload
        if (!is_array($data)) {
            return response()->json([
                'message' => 'Invalid payload'
            ], 400);
        }

        $inserted = 0;
        $skipped  = 0;

        DB::beginTransaction();

        try {
            foreach ($data as $row) {

                if (!isset($row['id'])) {
                    continue;
                }

                $exists = DB::connection('kantin')->table('attendance_logs')
                    ->where('id', $row['id'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::connection('kantin')->table('attendance_logs')->insert([
                    'id'               => $row['id'],
                    'nik'              => $row['nik'] ?? null,
                    'meal_type'        => $row['meal_type'] ?? null,
                    'status'           => $row['status'] ?? null,
                    'quantity'         => $row['quantity'] ?? 0,
                    'remarks'          => $row['remarks'] ?? null,
                    'created_by'       => $row['created_by'] ?? null,
                    'rating'           => $row['rating'] ?? null,
                    'attendance_date'  => $row['attendance_date'] ?? null,
                    'attendance_time'  => $row['attendance_time'] ?? null,
                    'similarity_score' => $row['similarity_score'] ?? null,
                    'confidence_score' => $row['confidence_score'] ?? null,
                    'created_at'       => $row['created_at'] ?? now(),
                    'updated_at'       => now(),
                ]);

                $inserted++;
            }

            DB::commit();

            return response()->json([
                'message'  => 'Data berhasil dikirim',
                'inserted' => $inserted,
                'skipped'  => $skipped
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Receive Attendance API Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }
}
