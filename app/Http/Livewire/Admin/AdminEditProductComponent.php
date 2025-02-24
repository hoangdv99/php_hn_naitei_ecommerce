<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Images;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $SKU;
    public $quantity;
    public $image;
    public $category_id;
    public $product_id;
    public $newimage;
    protected $rules = [
        'name' => 'required',
        'slug' => 'required',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required|numeric',
        'SKU' => 'required',
        'quantity' => 'required|numeric',
        'image' => 'required',
        'category_id' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->regular_price = $product->regular_price;
        $this->quantity = $product->quantity;
        $this->SKU = $product->SKU;
        $this->category_id = $product->category_id;
        $this->image = $product->images->get(0)->name;
        $this->product_id = $product->id;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name, '-');
    }

    public function editProduct(Request $request)
    {
        $data = $this->validate();
        $product = Product::find($this->product_id);
        $images = Images::where('product_id', $this->product_id)->first();
        $product->update($data);
        if ($this->newimage) {
            $imageName = Carbon::now()->timestamp. '.' . $this->newimage->extension();
            $this->newimage->storeAs('products', $imageName);
            $images->name = $imageName;
            $images->update();
        }
        session()->flash('message', __('admin-product.success_edit'));
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.admin.admin-edit-product-component', compact('categories'))->layout('layouts.base');
    }
}
