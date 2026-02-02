<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Update Username & Password (API/AJAX endpoint)
     * Digunakan oleh semua role (admin, guru, siswa, uks, piket)
     */
    public function updateCredentials(Request $request, $userId)
    {
        try {
            // ========== 1. SECURITY: Authorization Check ==========
            if ((int) Auth::id() !== (int) $userId) {
                Log::warning('Unauthorized credential update attempt', [
                    'authenticated_user' => Auth::id(),
                    'target_user' => $userId,
                    'ip' => request()->ip(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengubah akun ini.',
                ], 403);
            }

            // ========== 2. AUTHENTICATION: Verify User Exists ==========
            $user = User::findOrFail($userId);

            // ========== 3. VALIDATION: Validate Input ==========
            $validated = $request->validate([
                'password_lama' => 'required|string|min:6',
                'username_baru' => 'nullable|string|min:3|max:50|regex:/^[a-zA-Z0-9._-]+$/',
                'password_baru' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
                'password_confirm' => 'nullable|string|same:password_baru',
            ], [
                'password_lama.required' => 'Password lama harus diisi.',
                'password_lama.min' => 'Password lama minimal 6 karakter.',
                'username_baru.regex' => 'Username hanya boleh huruf, angka, dan karakter ._-',
                'password_baru.regex' => 'Password minimal 8 karakter, harus mengandung huruf besar, kecil, angka, dan simbol (@$!%*?&).',
                'password_confirm.same' => 'Konfirmasi password tidak cocok.',
            ]);

            // ========== 4. SECURITY: Verify Old Password ==========
            if (!Hash::check($validated['password_lama'], $user->password)) {
                Log::warning('Invalid old password attempt', [
                    'user_id' => $userId,
                    'ip' => request()->ip(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai.',
                ], 422);
            }

            // ========== 5. VALIDATION: Check at least one field is updated ==========
            $usernameChanged = !empty($validated['username_baru']) && $validated['username_baru'] !== $user->username;
            $passwordChanged = !empty($validated['password_baru']);

            if (!$usernameChanged && !$passwordChanged) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setidaknya username atau password harus diubah.',
                ], 422);
            }

            // ========== 6. VALIDATION: Check New Password ≠ Old Password ==========
            if ($passwordChanged && Hash::check($validated['password_baru'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama.',
                ], 422);
            }

            // ========== 7. DATABASE: Check Username Uniqueness ==========
            if ($usernameChanged) {
                $existingUser = User::where('username', $validated['username_baru'])
                    ->where('id_user', '!=', $userId)
                    ->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Username sudah digunakan oleh user lain.',
                    ], 422);
                }
            }

            // ========== 8. DATABASE TRANSACTION: Update User ==========
            DB::beginTransaction();

            try {
                $updateData = [];

                if ($usernameChanged) {
                    $updateData['username'] = $validated['username_baru'];
                }

                if ($passwordChanged) {
                    // Password akan di-hash otomatis
                    $updateData['password'] = Hash::make($validated['password_baru']);
                }

                $user->update($updateData);

                DB::commit();

                // ========== 9. LOGGING: Log successful update ==========
                Log::info('User credentials updated successfully', [
                    'user_id' => $userId,
                    'username_changed' => $usernameChanged,
                    'password_changed' => $passwordChanged,
                    'ip' => request()->ip(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $passwordChanged
                        ? 'Kredensial berhasil diperbarui. Silakan login kembali dengan password baru.'
                        : 'Kredensial berhasil diperbarui.',
                    'redirect_url' => $passwordChanged ? route('login') : null,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Database error during credential update', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error in updateCredentials', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan hubungi administrator.',
            ], 500);
        }
    }
}
