<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps  = false;

    use ImageTrait;

    public static function boot() {
        parent::boot();

        static::deleting(function($product) {
             $product->buyitnow()->delete();
             $product->auction()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_owner');
    }

    /**
     * Get the Buyitnow record associated with the product.
     */
    public function buyitnow()
    {
        return $this->hasOne('App\Buyitnow', 'id_buy', 'id');
    }


    /**
    * 
    */
    public function transaction()
    {
        return $this->hasOne('App\Transaction', 'id_transac');
    }

   /**
    * 
    */
    public function buyer()
    {
        return $this->hasOneThrough('App\Transaction', 'App\Buyitnow',  'id_transac', 'id_buy');
    }


  

    /**
     * Get the Auction record associated with the product.
     */
    public function auction()
    {
        return $this->hasOne('App\Auction', 'id_auction', 'id');
    }

    /**
     * Get the Comment record associated with the product.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'id');
    }

    /**
     * Returns all products with state active
     */
    public function scopeActive($query)
    {
        return $query->where('state_product', 'active');
    }

    /**
     * Returns all products with state active
     */
    public function scopeInactive($query)
    {
        return $query->where('state_product', 'inactive');
    }

        /**
     * Returns all products with state active
     */
    public function scopeBought($query)
    {
        return $query->where('state_product', 'bought');
    }

    public function scopeCategory($query, $category)
    {
        switch ($category) {
            case "all":
                return $query;
            case "others":
                 return ($query->whereNotIn('category', ['antiques','art','crafts','baby','travel','electronics','toys','cars','sports','collecting','computers',
                 'music','movies','photo','watches','comics','stamps','stationary','bargains','pottery','philately','coins']))->where('state_product', 'active');
            default:
                return $query->where('category', $category);
        } 
    }

    public function setStateProductAttribute($value)
    {
        $this->attributes['state_product'] = $value;
    }

    /**
     * Full text Search
     */
    public function scopeFTS($query, $search)
    {
        if(!empty($search))
            $specialCharacters = [
                '-',
                '+',
                '*',
                '/',
                '=',
                '!',
                '&',
                '|',
                '?',
                '<',
                '>',
                '@',
                '(',
                ')',
                '[',
                ']',
                '~',
                ':',
                ';',
                '#',
                '%',
                '^',
                '`'
            ];
            $search = str_replace($specialCharacters, '', $search);
            $search = preg_replace('/\s\s+/', ' ', $search);
            $search = trim($search);
            $wildcard = ':*';
            $joinOperator = '|';

        if (!strpos($search, ' ')) {
            $search= $search . $wildcard;
        }else{
            $search= str_replace(' ', $wildcard . $joinOperator, $search) . $wildcard;
        }
            return $query->selectRaw('*')
            //plainto_tsquery simplifies  the to_tsquery 
            ->whereRaw("to_tsvector('english', name_product || description) @@ to_tsquery('english', ?)", [$search])
            ->orderByRaw("ts_rank(setweight(to_tsvector('english', name_product),'A') || setweight(to_tsvector('english', description),'B'), to_tsquery('english', ?)) DESC, name_product", [$search]);       
            //Considering that search is a pre-calculated column containing the ts_vector of the columns we want to search.
    }
}