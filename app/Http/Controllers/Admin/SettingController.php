<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
        ]);

        $this->setEnvironmentValue('APP_NAME', $request->app_name);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    private function setEnvironmentValue($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $oldValue = env($key);
            $escapedValue = str_replace('"', '\\"', $value);

            if (str_contains(file_get_contents($path), $key . '=')) {
                file_put_contents($path, preg_replace(
                    "/^" . $key . "=.*/m",
                    $key . '="' . $escapedValue . '"',
                    file_get_contents($path)
                ));
            } else {
                file_put_contents($path, PHP_EOL . $key . '="' . $escapedValue . '"', FILE_APPEND);
            }
        }
    }
}
