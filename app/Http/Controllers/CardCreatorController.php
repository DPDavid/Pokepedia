<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CardCreatorController extends Controller
{
    public function index()
    {
        return view('pokemon.generator');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
            'hp' => 'required|numeric|min:10|max:999',
            'type' => 'required|max:20',
            'description' => 'nullable|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bg_color' => 'required|regex:/^#[a-f0-9]{6}$/i',
            'weakness_type' => 'nullable|string|max:20',
            'weakness_amount' => 'nullable|integer|min:0|max:100',
            'resistance_type' => 'nullable|string|max:20',
            'resistance_amount' => 'nullable|integer|min:-100|max:100',
        ]);

        // 1. Cargar la plantilla base
        $template = $request->input('template');
        $templatePath = public_path("images/templates/{$template}.png");
        $templateImg = imagecreatefrompng($templatePath);
        $cardWidth = imagesx($templateImg);
        $cardHeight = imagesy($templateImg);

        // 2. Crear el lienzo principal
        $card = imagecreatetruecolor($cardWidth, $cardHeight);
        imagealphablending($card, true);
        imagesavealpha($card, true);

        // 3. Rellenar con el color de fondo seleccionado SOLO el área visible
        $bgColor = $request->input('bg_color');
        list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
        $backgroundColor = imagecolorallocate($card, $r, $g, $b);

        // Definir el área rectangular que debe tener el color de fondo
        // Estos valores deben coincidir con el área visible de tu plantilla
        $bgAreaX = 50;  // Margen izquierdo
        $bgAreaY = 50;  // Margen superior
        $bgAreaWidth = $cardWidth - 100;  // Ancho total menos márgenes
        $bgAreaHeight = $cardHeight - 100; // Alto total menos márgenes

        // Rellenar solo el área visible
        imagefilledrectangle(
            $card,
            $bgAreaX,
            $bgAreaY,
            $bgAreaX + $bgAreaWidth,
            $bgAreaY + $bgAreaHeight,
            $backgroundColor
        );

        // 4. Procesar imagen del Pokémon
        $uploadedImage = $request->file('image');
        $imagePath = $uploadedImage->getRealPath();
        $pokemonImg = imagecreatefromstring(file_get_contents($imagePath));

        // Área designada para la imagen
        $imageAreaX = 70;
        $imageAreaY = 112;
        $imageAreaWidth = 615;
        $imageAreaHeight = 407;

        // Redimensionar manteniendo relación de aspecto
        $srcWidth = imagesx($pokemonImg);
        $srcHeight = imagesy($pokemonImg);

        $srcRatio = $srcWidth / $srcHeight;
        $targetRatio = $imageAreaWidth / $imageAreaHeight;

        if ($targetRatio > $srcRatio) {
            $newHeight = $imageAreaHeight;
            $newWidth = $srcWidth * ($imageAreaHeight / $srcHeight);
        } else {
            $newWidth = $imageAreaWidth;
            $newHeight = $srcHeight * ($imageAreaWidth / $srcWidth);
        }

        // Crear capa para la imagen del Pokémon con transparencia
        $pokemonLayer = imagecreatetruecolor($imageAreaWidth, $imageAreaHeight);
        imagealphablending($pokemonLayer, false);
        imagesavealpha($pokemonLayer, true);
        $transparent = imagecolorallocatealpha($pokemonLayer, 0, 0, 0, 127);
        imagefill($pokemonLayer, 0, 0, $transparent);

        // Centrar la imagen en la capa
        $xOffset = ($imageAreaWidth - $newWidth) / 2;
        $yOffset = ($imageAreaHeight - $newHeight) / 2;

        imagecopyresampled(
            $pokemonLayer,
            $pokemonImg,
            $xOffset,
            $yOffset,
            0,
            0,
            $newWidth,
            $newHeight,
            $srcWidth,
            $srcHeight
        );

        // 5. Colocar la imagen del Pokémon en el lienzo principal
        imagecopy(
            $card,
            $pokemonLayer,
            $imageAreaX,
            $imageAreaY,
            0,
            0,
            $imageAreaWidth,
            $imageAreaHeight
        );

        // 6. Colocar la plantilla ENCIMA de todo
        imagecopy(
            $card,
            $templateImg,
            0,
            0,
            0,
            0,
            $cardWidth,
            $cardHeight
        );

        // 6. Añadir texto (sobre todo lo anterior)
        $black = imagecolorallocate($card, 0, 0, 0);
        $fontPath = public_path('fonts/arial.ttf');
        // Añadir debilidades y resistencias si existen
        if ($request->has('weakness_type') && $request->weakness_type) {
            $weaknessIcon = public_path("images/energy/{$request->weakness_type}.png");
            if (file_exists($weaknessIcon)) {
                $icon = imagecreatefrompng($weaknessIcon);
                imagecopy($card, $icon, 50, 450, 0, 0, 30, 30);
                imagettftext($card, 16, 0, 85, 470, $black, $fontPath, "-{$request->weakness_amount}");
            }
        }

        if ($request->has('resistance_type') && $request->resistance_type) {
            $resistanceIcon = public_path("images/energy/{$request->resistance_type}.png");
            if (file_exists($resistanceIcon)) {
                $icon = imagecreatefrompng($resistanceIcon);
                imagecopy($card, $icon, 150, 450, 0, 0, 30, 30);
                imagettftext($card, 16, 0, 185, 470, $black, $fontPath, "-{$request->resistance_amount}");
            }
        }
        function centerText($image, $text, $fontSize, $yPos, $color, $font)
        {
            $bbox = imagettfbbox($fontSize, 0, $font, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (imagesx($image) - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, $yPos, $color, $font, $text);
        }

        centerText($card, $request->input('name'), 24, 90, $black, $fontPath);
        imagettftext($card, 20, 0, 600, 120, $black, $fontPath, 'HP: ' . $request->input('hp'));

        // 7. Guardar imagen final
        $fileName = Str::uuid() . '.png';
        $savePath = public_path('generated/' . $fileName);

        if (!file_exists(public_path('generated'))) {
            mkdir(public_path('generated'), 0777, true);
        }

        imagepng($card, $savePath);

        // Liberar memoria
        imagedestroy($card);
        imagedestroy($pokemonImg);
        imagedestroy($templateImg);
        imagedestroy($pokemonLayer);

        return view('pokemon.generator', ['image' => asset('generated/' . $fileName)]);
    }
}
