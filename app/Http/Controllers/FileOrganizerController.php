<?php

namespace App\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FileOrganizerController extends Controller
{
    private function validate(Request $request): array|JsonResponse
    {
        if (!$request->files->has('file')) {
            return response()->json(['message' => 'file not found'], 400);
        }
        $path = $request->files->get('file');
        if (!$path) {
            return response()->json(['message' => 'file not found'], 400);
        }
        if ($path->getClientOriginalExtension() !== 'txt') {
            return response()->json(['message' => 'file must be a text file'], 400);
        }
        if (filesize($path) > 1024 * 100) {
            return response()->json(['message' => 'file is too big, it must be 100KB or less'], 400);
        }
        $content = file_get_contents($path);
        $data = json_decode($content);
        if (json_last_error() != JSON_ERROR_NONE || !is_array($data)) {
            return response()->json(['message' => 'invalid file format'], 400);
        }
        return $data;
    }

    private function organize(array $data)
    {
        if (!is_array($data)) {
            throw new ValidationException('invalid data');
        }
        $organized_files = [];

        foreach ($data as $person) {
            foreach ($person as $key => $value) {
                $organized_files[$value][] = $key;
            }
        }

        return response()
            ->json(
                [
                    'message' => 'data organized successfuly',
                    'data' => $organized_files
                ]
            );
    }

    public function organizeFile(Request $request)
    {
        $data = $this->validate($request);

        if ($data instanceof JsonResponse) {
            return $data;
        }
        try {
            return $this->organize($data);
        } catch (ValidationException $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        } catch (Exception) {
            return response()->json(['message' => 'something went wrong'], 500);
        }
    }

    public function organizeJson(Request $request)
    {
        $data = $request->json()->all();
        if (json_last_error() != JSON_ERROR_NONE || !is_array($data)) {
            return response()->json(['message' => 'invalid file format'], 400);
        }
        try {
            return $this->organize($data);
        } catch (ValidationException $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        } catch (Exception) {
            return response()->json(['message' => 'something went wrong'], 500);
        }
    }
}
