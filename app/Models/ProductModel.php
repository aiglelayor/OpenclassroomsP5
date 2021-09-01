<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    public function getFrenchPrice()
    {
        $price = $this->price / 100;

        // Paramètres prix affichage : fr utilsent virgules et non points (2ème param), fr mettent espace entre mille et reste (3ème param)
        return number_format($price, 2, ',', ' ') . ' €';
    }
}
