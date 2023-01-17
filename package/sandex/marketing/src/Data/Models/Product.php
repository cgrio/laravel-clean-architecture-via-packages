<?php

namespace Sandex\Marketing\Data\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sandex\Marketing\Domain\Entities\Product as ProductEntity;

class Product extends Model
{
    use \Sandex\Marketing\Core\Traits\HasPackageFactory;

    protected $table = "products";
    protected $fillable = [ "name","description","sku","ncm"];

    public function toEntity(): ProductEntity
    {
        $entity = new ProductEntity();
        $entity->id = $this->id;
        $entity->name = $this->name;
        $entity->description = $this->description;
        $entity->sku = $this->sku;
        $entity->ncm = $this->ncm;
        $entity->created_at = new \DateTime(Carbon::parse($this->created_at)->toDateTimeString());
        $entity->updated_at = new \DateTime(Carbon::parse($this->updated_at)->toDateTimeString());
        return $entity;
    }
}
