<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Database\QueryException;
use App\Services\ProductCategoryService;
use App\Http\Requests\Product\StoreProductCategoryRequest;
use App\Http\Requests\Product\UpdateProductCategory;
use Illuminate\Support\Facades\Log;
use App\Models\ProductCategory;
class ProductCategoryController extends Controller
{
    protected $productCategoryService;
    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    public function index()
    {
        $categories = $this->productCategoryService->getProductsCategories();
        return view('pages.product-categories.index', compact('categories'));
    }

    public function store(StoreProductCategoryRequest $request)
    {
        try {
            $this->productCategoryService->createProductCategory($request->validated());
            return redirect()->route('products-category.index')->with('success', 'Categoría creada con éxito');
        } catch (QueryException $e) {

            $this->logError('fallo de la BD', $e);

            if ($this->isUniqueConstraintError($e)) {
                return back()
                    ->withInput()
                    ->withErrors(['name' => 'la categoria ya está registrada']);
            }
            return back()->withInput()->withErrors(['error' => 'Ocurrió un problema de consistencia en los datos.']);

        } catch (Exception $e) {
            $this->logError('fallo critico en el registro', $e);
            return back()->withInput()->withErrors(['error' => 'Ocurrio un error inesperado al registrar...']);
        }
    }

    public function edit(ProductCategory $productCategory){
        return view('pages.product-categories.edit', compact('productCategory'));
    }
    public function update(UpdateProductCategory $request, ProductCategory $productCategory)
    {
        try {
            $this->productCategoryService->updateProductCategory($productCategory, $request->validated());
            return redirect()->route('products-category.index')->with('success', 'categoria actualizada correctamente');
        } catch (QueryException $e) {

            if ($this->isUniqueConstraintError($e)) {
                $this->logError('Nombre de categoria repetido ', $e);
                return back()->withInput()->withErrors(['name' => 'El nombre de la categoria ya esta registrado']);
            }
            return back()->withInput()->withErrors(['name' => 'ocurrio un error inesperado al registrar la categoria..']);
        } catch (Exception $e) {
            $this->logError('fallo critico en el registro de categoria', $e);
            return back()->withInput()->withErrors(['error' => 'Ocurrio un error inesperado al actualizar...']);
        }
    }
  
public function softDelete($id)
{
    try {
        $isDeleted = $this->productCategoryService->softDelete((int) $id);
        
        if (!$isDeleted) {
            return redirect()->route('products-category.index')->with('error', 'La categoría ya no existe o fue eliminada por otro usuario');
        }

       
        $currentPage = request()->get('page', 1);

       
        if ($currentPage > 1) {
           
            $totalCategories = \App\Models\ProductCategory::count(); 
            
         
            $perPage = 10; 
    
            if ($totalCategories <= ($currentPage - 1) * $perPage) {
                return redirect()->route('products-category.index')->with('success', 'Categoría eliminada correctamente');
            }
        }

       
        return redirect()->back()->with('success', 'Categoría eliminada correctamente');

    } catch (Exception $e) {
        $this->logError('Fallo crítico al eliminar categoría: ', $e);
        return redirect()
            ->route('products-category.index')
            ->with('error', 'Ocurrió un error interno en el servidor al intentar eliminar la categoría.');
    }
}

    private function isUniqueConstraintError(QueryException $e): bool
    {
        return ($e->getCode() === '23000') || (isset($e->errorInfo[1]) && $e->errorInfo[1] === 19);
    }
    private function logError(string $context, Exception $e): void
    {
        Log::error($context, [
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile(),
        ]);
    }
}
