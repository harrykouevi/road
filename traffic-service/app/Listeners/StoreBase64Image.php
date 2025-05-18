<?php

namespace App\Listeners;

use App\Events\ImageProcessed;
use Illuminate\Support\Facades\Storage;
use App\Models\RoadReport;

class StoreBase64Image
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImageProcessed $event): void
    {
        $imageData = base64_decode($event->base64Image);
        $imageName = uniqid() . '.jpg';
        Storage::disk('public')->put("images/{$imageName}", $imageData);

        $element = RoadReport::find($event->elementId);
        if ($element) {
            $element->image_path = "images/{$imageName}";
            $element->save();
        }
    }
}
