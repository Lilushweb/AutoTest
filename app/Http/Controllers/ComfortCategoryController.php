<?php

namespace App\Http\Controllers;

use App\Http\DTO\ComfortCategoryDTO;
use App\Http\Requests\ComfortCategory\ComfortCategoryCreateRequest;
use App\Http\Requests\ComfortCategory\ComfrotCategoryUpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\ComfortCategory;
use Illuminate\Http\Request;

class ComfortCategoryController extends Controller
{

    public function index(PaginationRequest $request)
    {
        $paginatior = $request->validated();
        $response = ComfortCategory::paginate(
            $paginatior['per_page'],
            ['*'],
            'page',
            $paginatior['page']
        );

        return response()->json($response);
    }

    public function store(ComfortCategoryCreateRequest $request)
    {
        $name = $request->validated()['name'];
        $dto = new ComfortCategoryDTO(
            name: $name,
        );
        $createComfortCategory = ComfortCategory::create([
            'name' => $dto->name,
        ]);
        return response()->json($createComfortCategory, 201);
    }


    public function update(ComfrotCategoryUpdateRequest $request, ComfortCategory $comfortCategory)
    {
        $dto = new ComfortCategoryDTO(
            name: $request->validated('name'),
        );
        $comfortCategory->update([
            'name' => $dto->name,
        ]);
        return response()->json($comfortCategory, 200);
    }

    public function destroy(ComfortCategory $comfortCategory)
    {
        $comfortCategory->delete();
        return response()->json(null, 200);
    }
}
