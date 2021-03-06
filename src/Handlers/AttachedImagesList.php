<?php

namespace Froala\NovaFroalaField\Handlers;

use Illuminate\Http\Request;
use Froala\NovaFroalaField\Froala;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class AttachedImagesList
{
    /**
     * The field instance.
     *
     * @var \Froala\NovaFroalaField\Froala
     */
    public $field;

    /**
     * Create a new invokable instance.
     *
     * @param  \Froala\NovaFroalaField\Froala  $field
     * @return void
     */
    public function __construct(Froala $field)
    {
        $this->field = $field;
    }

    /**
     * Attach a pending attachment to the field.
     */
    public function __invoke(Request $request): array
    {
        $images = [];

        $Storage = Storage::disk($this->field->disk);

        foreach ($Storage->allFiles() as $file) {
            if (! in_array(
                (new File($Storage->path($file)))->guessExtension(),
                ['jpeg', 'png', 'gif', 'bmp', 'svg']
            )) {
                continue;
            }

            $url = $Storage->url($file);
            $images[] = [
                'url' => $url,
                'thumb' => $url,
            ];
        }

        return $images;
    }
}
