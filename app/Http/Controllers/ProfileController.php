<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $totalUsers = \App\Models\User::count();

        return view('profile.edit', [
            'user' => $request->user(),
            'totalUsers' => $totalUsers,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Check if this is the last user
        $totalUsers = \App\Models\User::count();

        if ($totalUsers <= 1) {
            return back()->withErrors([
                'account_deletion' => 'Cannot delete the last admin account. At least one admin must remain.'
            ]);
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Backup the database.
     */
    public function backup(Request $request)
    {
        // Validate password confirmation
        $request->validateWithBag('databaseBackup', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Create filename with timestamp
            $filename = 'library_backup_' . date('Y-m-d_His') . '.sql';
            $filepath = storage_path('app/backups/' . $filename);

            // Create backups directory if it doesn't exist
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Build mysqldump command with proper escaping for Docker environment
            $passwordArg = $password ? '-p' . escapeshellarg($password) : '';

            $command = sprintf(
                'mysqldump -h%s -P%s -u%s %s --skip-comments --skip-extended-insert %s > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                $passwordArg,
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute the command
            exec($command, $output, $returnVar);

            // Check if file was created and has content
            if (!file_exists($filepath) || filesize($filepath) == 0) {
                // Fallback: Use PHP to generate SQL dump
                return $this->generateSqlBackup($filename, $filepath);
            }

            // Download the file and delete it after
            return response()->download($filepath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('backup-error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate SQL backup using PHP (fallback method)
     */
    private function generateSqlBackup($filename, $filepath)
    {
        try {
            $sql = "-- Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // Get all tables
            $tables = \DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableKey = 'Tables_in_' . $dbName;

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Get CREATE TABLE statement
                $createTable = \DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

                // Get table data
                $rows = \DB::table($tableName)->get();

                if ($rows->count() > 0) {
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Write to file
            file_put_contents($filepath, $sql);

            if (file_exists($filepath) && filesize($filepath) > 0) {
                return response()->download($filepath, $filename)->deleteFileAfterSend(true);
            }

            return back()->with('backup-error', 'Failed to create database backup.');

        } catch (\Exception $e) {
            return back()->with('backup-error', 'Error generating backup: ' . $e->getMessage());
        }
    }
}
