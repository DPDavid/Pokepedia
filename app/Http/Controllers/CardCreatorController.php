<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CardCreatorController extends Controller
{
    //Funcion para mostrar la vista
    public function index()
    {
        return view('pokemon.generator');
    }

    public function generate(Request $request)
    {
        //Validacion de los campos del formulario
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

        //Carga de la plantilla seleccionada
        $template = $request->input('template');
        $templatePath = public_path("images/templates/{$template}.png");
        $templateImg = imagecreatefrompng($templatePath);

        //Obtencion de las dimensiones de la plantilla
        $cardWidth = imagesx($templateImg);
        $cardHeight = imagesy($templateImg);

        //Creacion del lienzo principal de la carta
        $card = imagecreatetruecolor($cardWidth, $cardHeight);
        imagealphablending($card, true);
        imagesavealpha($card, true);

        //Rellena el fondo de la carta con el color seleccionado
        $bgColor = $request->input('bg_color');
        list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
        $backgroundColor = imagecolorallocate($card, $r, $g, $b);

        //Area donde va la imagen en la plantilla
        imagefilledrectangle($card, 70, 112, 70 + 615, 112 + 407, $backgroundColor);

        //Procesa la imagen subida por el usuario
        $uploadedImage = $request->file('image');
        $imagePath = $uploadedImage->getRealPath();
        $pokemonImg = imagecreatefromstring(file_get_contents($imagePath));

        //Dimensiones de la imagen
        $imageAreaX = 70;
        $imageAreaY = 112;
        $imageAreaWidth = 615;
        $imageAreaHeight = 407;

        //Mantiene relacion de aspecto de la imagen
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

        //Creacion de una capa transparente para el pokemon
        $pokemonLayer = imagecreatetruecolor($imageAreaWidth, $imageAreaHeight);
        imagealphablending($pokemonLayer, false);
        imagesavealpha($pokemonLayer, true);
        $transparent = imagecolorallocatealpha($pokemonLayer, 0, 0, 0, 127);
        imagefill($pokemonLayer, 0, 0, $transparent);

        //Centrar la imagen en la capa
        $xOffset = ($imageAreaWidth - $newWidth) / 2;
        $yOffset = ($imageAreaHeight - $newHeight) / 2;

        //Redibuja la imagen escalada en la capa
        imagecopyresampled($pokemonLayer, $pokemonImg, $xOffset, $yOffset, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        //Colocar la imagen en el lienzo principal
        imagecopy($card, $pokemonLayer, $imageAreaX, $imageAreaY, 0, 0, $imageAreaWidth, $imageAreaHeight);

        //Finalmente superpone la plantilla final sobre todo lo demás
        imagecopy($card, $templateImg, 0, 0, 0, 0, $cardWidth, $cardHeight);

        //Variables para el texto
        $black = imagecolorallocate($card, 0, 0, 0);
        $fontPath = public_path('fonts/arial.ttf');

        //Funcion para centrar el texto 
        function centerText($image, $text, $fontSize, $yPos, $color, $font)
        {
            $bbox = imagettfbbox($fontSize, 0, $font, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = (imagesx($image) - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, $yPos, $color, $font, $text);
        }

        //Añade el nombre y el hp a la carta
        centerText($card, $request->input('name'), 24, 90, $black, $fontPath);
        imagettftext($card, 20, 0, 530, 90, $black, $fontPath, 'HP: ' . $request->input('hp'));

        //Añade la descripcion a la carta
        $description = $request->input('description');
        if (!empty($description)) {
            $fontSize = 16;
            $maxWidth = 560;
            //Limite de 50 caracteres por linea
            $lines = wordwrap($description, 50, "\n");
            $y = 600;

            foreach (explode("\n", $lines) as $line) {
                imagettftext($card, $fontSize, 0, 80, $y, $black, $fontPath, $line);
                $y += 25; // Espacio entre líneas
            }
        }

        //Añade las debilidades y resistencias si se indica
        if ($request->has('weakness_type') && $request->weakness_type) {
            $weaknessIcon = public_path("images/energy/{$request->weakness_type}.png");
            if (file_exists($weaknessIcon)) {
                $icon = imagecreatefrompng($weaknessIcon);
                $weaknessY = 920;

                //Transparencia de las imagenes de energias
                imagealphablending($icon, false);
                imagesavealpha($icon, true);

                //Renderizacion del icono de energia
                imagecopyresampled($card, $icon, 150, $weaknessY, 0, 0, 30, 30, imagesx($icon), imagesy($icon));

                //Dibujo dedl valor de la debilidad
                $weaknessText = "-{$request->weakness_amount}";
                imagettftext($card, 16, 0, 185, $weaknessY + 20, $black, $fontPath, $weaknessText);
                imagedestroy($icon);
            }
        }

        //Resistencia
        if ($request->has('resistance_type') && $request->resistance_type) {
            $resistanceIcon = public_path("images/energy/{$request->resistance_type}.png");
            if (file_exists($resistanceIcon)) {
                $icon = imagecreatefrompng($resistanceIcon);
                $resistanceY = 920;

                //Transparencia de las imagenes de energias
                imagealphablending($icon, false);
                imagesavealpha($icon, true);

                //Renderizacion del icono de energia
                imagecopyresampled($card, $icon, 340, $resistanceY, 0, 0, 30, 30, imagesx($icon), imagesy($icon));

                //Determina el signo de la resistencia
                $resistanceText = $request->resistance_amount;
                if ($resistanceText >= 0) {
                    $resistanceText = '-' . $resistanceText;
                } else {
                    $resistanceText = '+' . abs($resistanceText);
                }
                imagettftext($card, 16, 0, 370, $resistanceY + 20, $black, $fontPath, $resistanceText);
                imagedestroy($icon);
            }
        }

        //Guarda la imagen generada
        $fileName = Str::uuid() . '.png';
        $savePath = public_path('generated/' . $fileName);

        if (!file_exists(public_path('generated'))) {
            mkdir(public_path('generated'), 0777, true);
        }
        imagepng($card, $savePath);

        //Libera recursos y memoria
        imagedestroy($card);
        imagedestroy($pokemonImg);
        imagedestroy($templateImg);
        imagedestroy($pokemonLayer);

        //Muestra en la vista la imagen generada
        return view('pokemon.generator', ['image' => asset('generated/' . $fileName)]);
    }
}
