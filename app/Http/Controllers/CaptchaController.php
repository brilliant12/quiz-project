<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generateCaptcha()
    {

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);

        $result = 0;
        $operation = [1 => '+', 2 => '-', 3 => 'X'];
                        // Red                     Green               Blue
        $captchaColor = ['1' => '255, 0, 0', 2 => '0, 255, 0', '3' => '0, 0, 255'];
        $temp = $num2 > $num1 ? $num1 : '';
        if ($temp) {
            $num2 = $num1;
            $num1 = $temp;
        }

        $Rselected = rand(1, 3);
        $captcha = ' ' . $num1 . ' ' . $operation[$Rselected] . ' ' . $num2 . ' = ?  ';
        $selectColor = $captchaColor[$Rselected];
        switch ($Rselected) {
            case 1:
                $result = $num1 + $num2;

                break;
            case 2:
                $result = $num1 - $num2;
                break;
            case 3:
                $result = $num1 * $num2;
                break;
        }

        Session::put('captcha', $result);

        $width = 140;
        $height = 40;
        $image = imagecreate($width, $height);

                                           
        $backgroundColor = imagecolorallocate($image, 255, 255, 255); // White
        list($first, $second, $third) = explode(',', $selectColor);
        $textColor = imagecolorallocate($image, $first, $second, $third); 
        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

        imagestring($image, 5, 30, 10, $captcha, $textColor);
        header('Content-Type: image/png');
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);
        $base64Image = base64_encode($imageData);
        return response()->json(['captcha' => 'data:image/png;base64,' . $base64Image]);
    }

    public function handleForm(Request $request)
    {
        // Validate the input
        $request->validate([
            'captcha_input' => 'required',
        ]);


        if ($request->input('captcha_input') !== session('captcha')) {
            return back()->withErrors(['captcha_input' => 'CAPTCHA is incorrect.']);
        }


        return back()->with('success', 'CAPTCHA validated successfully!');
    }
}
