<?php

namespace Depakespedro\Grastin;

class ProductGrastin
{
    private $article = '';
    private $name = '';
    private $cost = '';
    private $amount = '';

    //устанавлваиет артикул товара
    public function set_article($article)
    {
        $this->article = trim($article);
        
        return $this;
    }
    
    //устанавливает название товара
    public function set_name($name)
    {
        $this->name = trim($name);

        return $this;
    }
    
    //устанавливает стоимость товара
    public function set_cost($cost)
    {
        $this->cost = trim($cost);

        return $this;
    }
    
    //устанавлваиет колличество товара
    public function set_amount($amount)
    {
        $this->amount = trim($amount);

        return $this;
    }

    public function convertToXML()
    {
        $values = array_map('htmlspecialchars', [
            $this->article,
            $this->name,
            $this->cost,
            $this->amount,
        ]);
        return vsprintf('<good article="%s" name="%s" cost="%s" amount="%s" />', $values);
    }
}
