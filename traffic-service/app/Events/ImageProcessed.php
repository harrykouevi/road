<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;

class ImageProcessed
{
    use Dispatchable;

    public string $base64Image;
    public int $elementId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $base64Image, int $elementId)
    {
        $this->base64Image = $base64Image;
        $this->elementId = $elementId;
    }

   
}
