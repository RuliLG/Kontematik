<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AlreadyGenerating;
use App\Exceptions\LimitReachedException;
use App\Exceptions\RateLimitException;
use App\Exceptions\UnsafePrompt;
use App\Http\Controllers\Controller;
use App\Models\SavedResult;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\Copywriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToolsController extends Controller
{
    public function index ()
    {
        $serviceIds = Service::enabled()->select('id')->get()->pluck('id');
        $categories = ServiceCategory::with([
                'services' => function ($query) use ($serviceIds) {
                    $query->whereIn('id', empty($serviceIds) ? [-1] : $serviceIds);
                }
            ])
            ->with('services.fields')
            ->orderBy('order', 'ASC')
            ->get()
            ->filter(function ($category) {
                return !$category->services->isEmpty();
            })
            ->values();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function show (Service $tool)
    {
        if (!$tool->is_enabled) {
            return response()->json(['error' => 'Tool not found'], 404);
        }

        $tool->load('fields');

        return response()->json($tool);
    }

    public function save (Service $tool, Request $request)
    {
        $request->validate([
            'result' => 'required|string',
            'result_id' => 'required|exists:results,id',
            'params' => 'required|array',
        ]);
        if (!$tool->is_enabled) {
            return response()->json(['error' => 'Tool not found'], 404);
        }

        $saved = new SavedResult;
        $saved->service_id = $tool->id;
        $saved->result_id = $request->result_id;
        $saved->user_id = auth()->id();
        $saved->params = is_string($request->params) ? $request->params : json_encode($request->params);
        $saved->output = $request->result;
        $saved->save();

        return response()->json($tool);
    }

    public function inference (Service $tool, Request $request)
    {
        $copywriter = new Copywriter;
        Validator::make($request->except(['token', 'provider']), $copywriter->validationRules($tool))->validate();

        $copywriter->setOrigin($request->get('origin', 'website'));
        $copywriter->setOriginUrl($request->get('origin_url'));

        try {
            $response = $copywriter->generate($tool, $request->except(['token', 'provider']), $request->get('language', 'auto'));
        } catch (LimitReachedException $e) {
            return response()->json(['error' => 'limit_reached'], 403);
        } catch (UnsafePrompt $e) {
            return response()->json(['error' => 'unsafe_prompt'], 403);
        } catch (AlreadyGenerating $e) {
            return response()->json(['error' => 'already_generating'], 403);
        } catch (RateLimitException $e) {
            return response()->json(['error' => 'rate_limit', 'message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'unknown'], 500);
        }

        return response()->json($response);
    }
}
